<?php
namespace App\Http\Controllers;

use DB;
use App\Tax;
use App\Sell;
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

class SellController extends Controller
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
                                ->pluck('first_name', 'id');
                                
        $transactions = Transaction::where('transaction_type', 'sell')->orderBy('id', 'desc');

        if($request->get('invoice_no')) {
            $transactions->where('reference_no', 'LIKE', '%' . $request->get('invoice_no') . '%');
        }

        if($request->get('customer')) {
            $transactions->whereClientId($request->get('customer'));
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
        
        return view('sells.index')
                ->withCustomers($customers)
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
        return redirect()->action('SellController@getIndex', $params);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
    public function getNewsell(Request $request){
        $sell = new Sell;
        $customers = Client::where('client_type','!=', 'purchaser')->where('id', '!=', 1)->get();
        $products = Product::orderBy('name', 'asc')->where('status', 1)->select('id','name','cost_price', 'mrp','minimum_retail_price','quantity', 'tax_id', 'code')->get();
        return view('sells.new')
                        ->withSell($sell)
                        ->withCustomers($customers)
                        ->withProducts($products);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postSell(SellRequest $request)
    {   
        $customer = $request->get('customer');
        $enableProductTax = settings('product_tax');

        if (!$customer) {
            throw new ValidationException('Customer ID is required.');
        }

        $ym = Carbon::now()->format('Y/m');

        $row = Transaction::where('transaction_type', 'sell')->withTrashed()->get()->count() > 0 ? Transaction::where('transaction_type', 'sell')->withTrashed()->get()->count() + 1 : 1;
        $ref_no = $ym.'/S-'.ref($row);
        $total = 0;
        $totalProductTax = 0;
        $productTax = 0;
        $total_cost_price = 0;
        $sells = $request->get('sells');
        $paid = floatval($request->get('paid')) ?: 0;

        DB::transaction(function() use ($request , $sells, $ref_no, &$total, &$total_cost_price, &$totalProductTax, $customer, $paid, $enableProductTax, $productTax){
            foreach ($sells as $sell_item) {
                
                if (intval($sell_item['quantity']) === 0) {
                    throw new ValidationException('Product quantity is required');
                }

                if (!$sell_item['product_id'] || $sell_item['product_id'] === '') {
                    throw new ValidationException('Product ID is required');
                }
                
                $total = $total + $sell_item['subtotal'];
                $total_cost_price = $total_cost_price + ($sell_item['cost_price'] * $sell_item['quantity']);

                $sell = new Sell;
                    $sell->reference_no = $ref_no;
                    $sell->product_id = $sell_item['product_id'];
                    $sell->quantity = $sell_item['quantity'];

                    if($enableProductTax == 1){
                        //product tax calculation
                        $product_row = Product::findorFail($sell_item['product_id']);
                        $taxRate = $product_row->tax->rate;
                        $taxType = $product_row->tax->type;

                        $productTax = ($taxType == 1) ? (($sell_item['quantity'] * $taxRate * $sell_item['price']) / 100) : ($sell_item['quantity'] * $taxRate);

                        $sell->product_tax = $productTax;
                        //ends
                        $totalProductTax = $totalProductTax + $productTax;
                    }

                    $sell->unit_cost_price = $sell_item['cost_price'];
                    $sell->sub_total = $sell_item['subtotal']- $productTax;
                    $sell->client_id = $customer;
                $sell->save();

                $product = $sell->product;
                $product->quantity = $product->quantity - intval($sell_item['quantity']);
                $product->save();
            }

            //discount
	    	$discount = $request->get('discount');
	    	$discountType = $request->get('discountType');
	    	$discountAmount = $discount;
	    	if($discountType == 'percentage'){
	    		$discountAmount = $total * (1 * $discount / 100);
	    	}

	    	$total_payable = $total - $discountAmount;
	    	//discount ends

            //invoice tax
            if(settings('invoice_tax') == 1){
                if(settings('invoice_tax_type') == 1){
                    $invoice_tax = (settings('invoice_tax_rate') * $total_payable) / 100;
                }else{
                    $invoice_tax = settings('invoice_tax_rate');
                }
            }else{
                $invoice_tax = 0;
            }
            //ends

            $transaction = new Transaction;
                $transaction->reference_no = $ref_no;
                $transaction->client_id = $customer;
                if (\Auth::user()->hasRole('Admin') || \Auth::user()->hasRole('Agent')) {
                    $transaction->user_agent_id = \Auth::user()->id;
                } else {
                    $transaction->user_agent_id = 0;
                }
                $transaction->transaction_type = 'sell';
                $transaction->total_cost_price = $total_cost_price;
                $transaction->discount = $discountAmount;
                //saving total without product tax and shipping cost
                $transaction->total = $total_payable - $totalProductTax;
                $transaction->invoice_tax = round($invoice_tax, 2);
                $transaction->total_tax = round(($totalProductTax + $invoice_tax), 2);
                $transaction->labor_cost = $request->get('shipping_cost');
                $transaction->net_total = round(($total_payable + $request->get('shipping_cost') + $invoice_tax), 2);
                $transaction->paid = $paid;
            $transaction->save();

            if($paid > 0){
                $payment = new Payment;
                    $payment->client_id = $customer;
                    $payment->upfront_payment = $request->get('upfront_payment');
                    $payment->monthly_payment = $request->get('monthly_payment');
                    $payment->last_payment = $request->get('last_payment');
                    $payment->total_installment = $request->get('total_installment');
                    $payment->amount = $paid;
                    $payment->method = $request->get('method');
                    $payment->type = 'credit';
                    $payment->reference_no = $ref_no;
                    $payment->note = "Paid for Invoice ".$ref_no;
                $payment->save();
            }
        });

        //round(520.34345,2)

        return response(['message' => 'Successfully saved transaction.']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function returnSell(Transaction $transaction)
    {
        $quantity = 0;
        foreach($transaction->sells as $sell){
            $quantity = $quantity + $sell->quantity;
        }
        if($quantity <= 0){
            $message = "No product in this sell is left to return";
            return redirect()->back()->withWarning($message);
        }else{
            return view('sells.return.return', compact('transaction', 'quantity'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function returnSellPost(Request $request)
    {   
        $transactionId = $request->get('transaction_id');
        $transaction = Transaction::find($transactionId);
        
        if (!$transaction) {
            return redirect()->back()->withMessage('Transaction was not found.');
        }

        $previousTotal = $transaction->total;
        $previosInvoiceTax = $transaction->invoice_tax;
        //$previosProductTax = $transaction->total_tax - $previosInvoiceTax;

        $total = 0;
        $updatedCostPrice = 0;
        $total_product_tax = 0;
        $total_return_quantity = 0;

        $client = Client::find($transaction->client_id);
        $due = $client->transactions->sum('net_total') - $client->payments->where('type', 'credit')->sum('amount');

        foreach ($transaction->sells as $sell) {

            $returnQuantity = intval($request->get('quantity_'. $sell->id)) ?: 0;
            $total_return_quantity += $returnQuantity;

            //new
            $unitProductTax = $sell->product_tax / $sell->quantity;
            
            if ($returnQuantity === 0) {
                $total =  $total + $sell->sub_total;
                $total_product_tax = $total_product_tax + $sell->product_tax;
                continue;
            }

            $returnUnitPrice = floatval($request->get('unit_price_'. $sell->id));
            
            $sellId = $request->get('sell_'. $sell->id);

            $sell = Sell::find($sellId);

            if($returnQuantity > $sell->quantity){
                $warning = "Return Quantity (".$returnQuantity.") Can't be greater than the Selling Quantity (".$sell->quantity.")";
                return redirect()->back()->withWarning($warning);
            }

            $updatedSellQuantity = $sell->quantity - $returnQuantity;
            $updatedProductTax = $unitProductTax * $updatedSellQuantity;
            $subTotal = $updatedSellQuantity * $returnUnitPrice;

            if($previosInvoiceTax > 1){
                if(settings('invoice_tax_type') == 1){
                    $return_tax_amount = (settings('invoice_tax_rate') * ($returnQuantity * $returnUnitPrice)) / 100;
                }else{
                    $return_tax_amount = settings('invoice_tax_rate');
                }
            }else{
                $return_tax_amount = 0;
            }

            $sell->quantity = $updatedSellQuantity;
            $sell->sub_total = $subTotal;
            $sell->product_tax = $updatedProductTax;
            $sell->save();

            //update the cost price to deduct from transaction table
            $updatedCostPrice += $returnQuantity * $sell->unit_cost_price;

            $product = $sell->product;
            $currentStock = $product->quantity;
            $product->quantity = $currentStock + $returnQuantity;
            $product->save();
            
            $total += $subTotal;
            $total_product_tax = $total_product_tax + $updatedProductTax;

            // Save Return statement
            $return = new ReturnTransaction;
            $return->sells_id = $sell->id;
            $return->client_id = $sell->client_id;
            $return->return_vat = $unitProductTax * $returnQuantity;
            $return->sells_reference_no = $sell->reference_no;
            $return->return_units = $returnQuantity;
            $return->return_amount = ($returnQuantity * $returnUnitPrice) + $return_tax_amount + ($unitProductTax * $returnQuantity);
            $return->returned_by = \Auth::user()->id;
            $return->save();
        }

        //invoice tax
        if($previosInvoiceTax > 1){
            if(settings('invoice_tax_type') == 1){
                $invoice_tax = (settings('invoice_tax_rate') * $total) / 100;
            }else{
                $invoice_tax = settings('invoice_tax_rate');
            }
        }else{
            $invoice_tax = 0;
        }
        //ends

        if($total_return_quantity <= 0){
            $quantityerror = "You Can't return Zero Quantity";
            return redirect()->back()->withQuantityerror($quantityerror);
        }
        
        //update transaction for this return
        $transaction->total = $total;
        $transaction->invoice_tax = $invoice_tax;
        $transaction->total_tax = $total_product_tax + $invoice_tax;
        $transaction->net_total = $total + $invoice_tax + $transaction->labor_cost + $total_product_tax;
        $transaction->total_cost_price = $transaction->total_cost_price - $updatedCostPrice;
        $transaction->return = true;
        $transaction->save();

        $diff = ( $previousTotal + $previosInvoiceTax /*+ $previosProductTax*/) - ($total + $invoice_tax + $total_product_tax);

        //if difference is greater than due amount then we need to return some money to the customer
        if ($diff > $due) {
            $payment = new Payment;
            $payment->client_id =  $client->id;
            $payment->amount =  $due < 0 ? $diff :  $diff - $due;
            $payment->method = 'cash';
            $payment->type = "return";
            $payment->reference_no = $transaction->reference_no;
            $payment->note = "Return for ".$transaction->reference_no;
            $payment->save();
        }

        return redirect()->route('sell.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
    public function sellsDetails(Transaction $transaction)
    {
        $payments = $transaction->payments()->orderBy('id', 'desc')->get();
        $total_paid = $transaction->payments()->where('type', 'credit')->sum('amount');
        $total_return = $transaction->payments()->where('type', 'return')->sum('amount');
        $total = $total_paid -  $total_return;
        return view('sells.details')
                ->withTransaction($transaction)
                ->withPayments($payments)
                ->withTotal($total);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function sellingInvoice(Transaction $transaction)
    {
        $current_locale = app()->getLocale();
        \App::setLocale('ar');
        $secondary_lang = \Lang::get('core');
        \App::setLocale($current_locale);

        $total_quanity = 0;
        foreach($transaction->sells as $sell){
            $total_quanity += $sell->quantity;
        }

        return view('sells.invoice')
                    ->withTransaction($transaction)
                    ->with('total_quanity', $total_quanity)
                    ->with('secondary_lang',$secondary_lang);
    }

     /**
     * Delete the record of a specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteSell(Request $request, Transaction $transaction) {

        foreach ($transaction->sells as $sell) {
            //add deleted product into stock
            $product = Product::find($sell->product_id);
            $current_stock = $product->quantity;
            $product->quantity = $current_stock + $sell->quantity;
            $product->save();

            //delete the sales entry in sells table
            $sell->delete();
        }

        //delete all the payments against this transaction
        foreach($transaction->payments as $payment){
            $payment->delete();
        }

        //delete all the return sells against this transaction
        foreach($transaction->returnSales as $return){
            $return->delete();
        }

        //delete the transaction entry for this sale
        $transaction->delete();

        $message = trans('core.deleted');
        return redirect()->route('sell.index')->withSuccess($message);
    }


    public function testReturn(Transaction $transaction){
        $quantity = 0;
        foreach($transaction->sells as $sell){
            $quantity = $quantity + $sell->quantity;
            $products[] = $sell->product;
        }
        $emon = $transaction->sells->count();

        return view('sells.return.test', compact('quantity', 'transaction', 'products', 'emon'));
    }
    
}
