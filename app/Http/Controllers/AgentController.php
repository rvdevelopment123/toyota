<?php

namespace App\Http\Controllers;

use DB;
use App\Tax;
use App\Sell;
use App\User;
use App\Role;
use App\UserRole;
use App\Client;
use App\Product;
use App\Payment;
use App\Category;
use Carbon\Carbon;
use App\Transaction;
use App\Http\Requests;
use App\ReturnTransaction;
use Illuminate\Http\Request;
use App\Http\Requests\ItemRequest;
use App\Http\Requests\SellRequest;
use App\Http\Controllers\Controller;
use App\Exceptions\ValidationException;

class AgentController extends Controller
{
    private $searchParams = ['invoice_no', 'customer', 'from', 'to', 'type'];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function getIndex(Request $request)
    {
        $customers =  Client::orderBy('first_name', 'asc')
                                ->where('client_type', "!=" ,'purchaser')
                                ->get();
                                
        $agents = User::with(['user_role' => function($query) {
            $query->with(['role' => function($query1) {
                $query1->where('name', 'Agent')->orWhere('name', 'Agents');
            }]);
        }])->get();
                                
        $transactions = Transaction::where('transaction_type', 'sell')->orderBy('id', 'desc');

        if($request->get('invoice_no')) {
            $transactions->where('reference_no', 'LIKE', '%' . $request->get('invoice_no') . '%');
        }

        if($request->get('customer')) {
            $transactions->whereClientId($request->get('customer'));
        }
        
        if($request->get('agent')) {
            $transactions->where('user_agent_id', $request->get('agent'));
        }

        if($request->get('type') == 'pos') {
            $transactions->wherePos(1);
        }

        $from = $request->get('from');
        $to = $request->get('to')?:date('Y-m-d');
        $to = Carbon::createFromFormat('Y-m-d',$to);
        $to = filterTo($to);

        if($request->get('from') || $request->get('to')) {
            if(!is_null($from)){
                $from = Carbon::createFromFormat('Y-m-d',$from);
                $from = filterFrom($from);
                $transactions->whereBetween('created_at',[$from,$to]);
            }else{
                $transactions->where('created_at','<=',$to);
            }
        }

        $cloneTransactionForNetTotal = clone $transactions;
        $cloneTransactionForTotalTax = clone $transactions;
        $cloneTransactionForInvoiceTax = clone $transactions;
        $cloneTransactionForTotalCostPrice = clone $transactions;

        $invoice_tax = $cloneTransactionForInvoiceTax->sum('invoice_tax');
        $total_tax = $cloneTransactionForTotalTax->sum('total_tax');
        $product_tax = $total_tax - $invoice_tax;

        $net_total = $cloneTransactionForNetTotal->sum('net_total');
        $total = $net_total - $total_tax;

        $total_cost_price = $cloneTransactionForTotalCostPrice->sum('total_cost_price');
        
        $profit = $total - $total_cost_price;
        
        return view('agents.index')
                ->withCustomers($customers)
                ->withAgents($agents)
                ->withTransactions($transactions->paginate(20))
                ->with('net_total', $net_total)
                ->with('invoice_tax', $invoice_tax)
                ->with('product_tax', $product_tax)
                ->with('total', $total)
                ->with('total_cost_price', $total_cost_price)
                ->with('profit', $profit);
    }
    
    /**
     * post of index.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postIndex(Request $request) {
        $params = array_filter($request->only($this->searchParams));
        return redirect()->action('AgentController@getIndex', $params);
    }
    
    /**
     * Show agent details.
     *
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
    */
    public function agentDetails(Transaction $transaction)
    {
        $payments = $transaction->payments()->orderBy('id', 'desc')->get();
        $sells = $transaction->sells()->orderBy('id', 'desc')->get();
        $sell = $transaction->sell;
        $payment = $transaction->payment;
        
        $total_paid = $transaction->payments()->where('type', 'credit')->sum('amount');
        $total_return = $transaction->payments()->where('type', 'return')->sum('amount');
        $total = $total_paid -  $total_return;
        
        return view('agents.details')
                ->withSells($sells)
                ->withSell($sell)
                ->withPayment($payment)
                ->withTransaction($transaction)
                ->withPayments($payments)
                ->withTotal($total);
    }
}
