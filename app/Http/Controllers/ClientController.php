<?php
namespace App\Http\Controllers;

use Customer;
use App\Client;
use App\Payment;
use Carbon\Carbon;
use App\Transaction;
use App\Http\Requests;
use App\ReturnTransaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ClientRequest;

class ClientController extends Controller
{
    private $searchParams = ['name', 'phone', 'company_name', 'address'];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function getIndex(Request $request)
    {
        $clients = Client::orderBy('first_name', 'asc')->where('client_type', '!=', 'purchaser');
        if($request->get('name')) {
            $clients->where(function($q) use($request) {
                $q->where('first_name', 'LIKE', '%' . $request->get('name') . '%');
                $q->orWhere('last_name', 'LIKE', '%' . $request->get('name') . '%');
            });
        }

        if($request->get('phone')) {
            $clients->where('phone', 'LIKE', '%' . $request->get('phone') . '%');
        }

        if($request->get('company_name')) {
            $clients->where('company_name', 'LIKE', '%' . $request->get('company_name') . '%');
        }

        if($request->get('address')) {
            $clients->where('address', 'LIKE', '%' . $request->get('address') . '%');
        }

        return view('clients.index')->withClients($clients->paginate(20));
        
    }


    public function postIndex(Request $request) {
        $params = array_filter($request->only($this->searchParams));
        return redirect()->action('ClientController@getIndex', $params);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getNewClient()
    {
        $client = New Client;
        return view('clients.form')->withClient($client);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postClient(ClientRequest $request)
    {
        $response = Customer::save($request);

        $message = trans('core.changes_saved');
        if(empty($request->get('client_type'))){
            return redirect()->route('purchaser.index')->withMessage($message);
        }

        return redirect()->route('client.index')->withMessage($message);
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getEditClient(Client $client)
    {
        return view('clients.form')->withClient($client);
    }

    /**
     * Return the details of a client.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function getDetailsClient(Client $client)
    {
        $net_total = $client->transactions->sum('net_total') + $client->returns->sum('return_amount') - $client->provious_due;
        $total_return = $client->payments->where('type', 'return')->sum('amount');
        $total_received = $client->payments->where('type', '!=','return')->sum('amount') - $total_return;

        $total_due = $client->transactions->sum('net_total') - ($total_received);

        $payment_lists = $client->payments()->orderBy('id','desc')->take(10)->get();

        $total_invoice = $client->transactions()->where('transaction_type', '!=','opening')->count();
        
        return view('clients.details', compact('total_due', 'client', 'total_received', 'payment_lists', 'total_return', 'net_total', 'total_invoice'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteClient(Client $client)
    {
       if(($client->sells->count() == 0) && ($client->purchases->count() == 0)){
            $client->delete();
            $message = trans('core.deleted');
            return redirect()->back()->withMessage($message);
        }else{
            $message = trans('core.client_has_transactions');
            return redirect()->back()->withMessage($message);
        }
    }

    /**
     * Get all the invoices of a specific client
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function getClientInvoices(Client $client)
    {
        $invoices = $client->transactions()->orderBy('id', 'desc')->where('transaction_type', '!=','opening')->paginate(20);
        return view('clients.invoices')
                        ->withInvoices($invoices)
                        ->withClient($client);
    }

    /**
     * Get all the transaction list of a specific client
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function paymentList($id, Request $request)
    {
        $payments = Payment::where('client_id', $id)->orderBy('id', 'desc');
        $transactions = Transaction::where('client_id', $id)->orderBy('id', 'desc');
        $returns = ReturnTransaction::where('client_id', $id)->orderBy('id', 'desc');

        $from = $request->get('from');
        $to = $request->get('to')?:date('Y-m-d');
        $to = Carbon::createFromFormat('Y-m-d',$to);
        $to = filterTo($to);

        if($request->get('from') || $request->get('to')) {
            if(!is_null($from)){
                $from = Carbon::createFromFormat('Y-m-d',$from);
                $from = filterFrom($from);
                $payments->whereBetween('created_at',[$from,$to]);
                $transactions->whereBetween('created_at',[$from,$to]);
                $returns->whereBetween('created_at',[$from,$to]);
            }else{
                $payments->where('created_at','<=',$to);
                $transactions->whereBetween('created_at',[$from,$to]);
                $returns->whereBetween('created_at',[$from,$to]);
            }
        }

        
        $client = Client::find($id);

        $cloneForTotalReturn = clone $payments;
        $cloneForTotalReceived = clone $payments;
        $cloneTransactionForDue = clone $transactions;

        $net_total = $transactions->sum('net_total') + $returns->sum('return_amount') - $client->provious_due;

        $total_return = $cloneForTotalReturn->where('type', 'return')->sum('amount');

        $total_received = $cloneForTotalReceived->where('type', '!=','return')->sum('amount') - $total_return;

        $total_due = $cloneTransactionForDue->sum('net_total') - ($total_received);

        if($request->get('print')){
            $printable_payments = $payments->get();
            return view('clients.partials.print-payment', compact('printable_payments', 'client', 'net_total', 'total_return', 'total_received', 'total_due', 'from', 'to'));
        }

        $payments = $payments->paginate(20);


        return view('clients.partials.payment-list-all', compact('payments', 'client', 'net_total', 'total_return', 'total_received', 'total_due'));
    }


    /**
     * Get the list of API of client
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function getClientAPI () {
        $client = Client::orderBy('first_name', 'asc')->where('client_type', '!=', 'purchaser')->get()->toArray();
        return response()->json($client);
    }
}
