<?php
namespace App\Http\Controllers;

use DB;
use App\Tax;
use App\Client;
use App\Payment;
use App\Product;
use App\Purchase;
use App\Category;
use Carbon\Carbon;
use App\Transaction;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Requests\ItemRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\PurchaseRequest;
use App\Exceptions\ValidationException;


class PurchaseController extends Controller
{

    private $searchParams = ['bill_no', 'supplier', 'from', 'to'];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
    */
    
    public function getIndex(Request $request)
    {
        $suppliers = Client::orderBy('first_name', 'asc')->where('client_type', 'purchaser')->pluck('first_name', 'id');
        
        $transactions = Transaction::where('transaction_type', 'purchase')->orderBy('id', 'desc');

        if($request->get('bill_no')) {
            $transactions->where('reference_no', 'LIKE', '%' . $request->get('bill_no') . '%');
        }

        if($request->get('supplier')) {
            $transactions->whereClientId($request->get('supplier'));
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

        return view('purchases.index')
                ->withSuppliers($suppliers)
                ->withTransactions($transactions->paginate(20));
    }

    /**
     * post of index.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postIndex(Request $request) {
        $params = array_filter($request->only($this->searchParams));
        return redirect()->action('PurchaseController@getIndex', $params);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
    */
    public function getNewPurchase(Request $request){
        $purchase = new Purchase;
        $suppliers = Client::where('client_type', 'purchaser')->where('id', '!=', 2)->get();
        $products = Product::orderBy('name', 'asc')->where('status',1)->select('id','name','cost_price', 'mrp', 'quantity', 'tax_id', 'code')->get();
        return view('purchases.new')
                        ->withPurchase($purchase)
                        ->withSuppliers($suppliers)
                        ->withProducts($products);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postPurchase(PurchaseRequest $request)
    {   
        $supplier = $request->get('supplier');
        $enableProductTax = settings('product_tax');

        if (!$supplier) {
            throw new ValidationException('Please Select A Supplier');
        }

        $ym = Carbon::now()->format('Y/m');

        $row = Transaction::where('transaction_type', 'purchase')->withTrashed()->get()->count() > 0 ? Transaction::where('transaction_type', 'purchase')->withTrashed()->get()->count() + 1 : 1;
        $ref_no = $ym.'/P-'.ref($row);
        $total = 0;
        $totalProductTax = 0;
        $productTax = 0;
        $purchases = $request->get('purchases');
        $paid = floatval($request->get('paid')) ?: 0;

        DB::transaction(function() use ($request , $purchases, $ref_no, &$total, &$totalProductTax, $supplier, $paid, $enableProductTax, $productTax){ 
            foreach ($purchases as $purchase_item) {
                if (intval($purchase_item['quantity']) === 0) {
                    throw new ValidationException('Product quantity is required');
                }

                if (!$purchase_item['product_id'] || $purchase_item['product_id'] === '') {
                    throw new ValidationException('Product ID is required');
                }

                $total = $total + $purchase_item['subtotal'];
                $purchase = new Purchase;
                    $purchase->reference_no = $ref_no;
                    $purchase->product_id = $purchase_item['product_id'];
                    $purchase->quantity = $purchase_item['quantity'];
                    
                    if($enableProductTax == 1){
                        //product tax calculation
                        $product_row = Product::findorFail($purchase_item['product_id']);
                        $taxRate = $product_row->tax->rate;
                        $taxType = $product_row->tax->type;

                        $productTax = ($taxType == 1) ? (($purchase_item['quantity'] * $taxRate * $purchase_item['price']) / 100) : ($purchase_item['quantity'] * $taxRate);

                        $purchase->product_tax = $productTax;
                        //ends
                        $totalProductTax = $totalProductTax + $productTax;
                    }
                    
                    $purchase->sub_total = $purchase_item['subtotal'] - $productTax;
                    $purchase->client_id = $supplier;
                $purchase->save();

                $product = $purchase->product;
                $product->quantity = $product->quantity + intval($purchase_item['quantity']);
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
                $transaction->client_id = $request->get('supplier');
                $transaction->transaction_type = 'purchase';
                $transaction->discount = $discountAmount;
                $transaction->total = $total_payable - $totalProductTax;
                $transaction->invoice_tax = round($invoice_tax, 2);
                $transaction->total_tax = round(($totalProductTax + $invoice_tax), 2);
                $transaction->net_total = round(($total_payable + $invoice_tax), 2);
                $transaction->paid = $paid;
            $transaction->save();

            if($paid > 0){
                $payment = new Payment;
                    $payment->client_id = $request->get('supplier');
                    $payment->amount = $request->get('paid');
                    $payment->method = $request->get('method');
                    $payment->type = 'debit';
                    $payment->reference_no = $ref_no;
                    $payment->note = "Paid for bill ".$ref_no;
                $payment->save();
            }
        });

       $message = trans('core.changes_saved');
       return redirect()->back()->withSuccess($message);
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function purchaseDetails(Transaction $transaction)
    {
        $payments = $transaction->payments()->orderBy('id', 'desc')->get();
        return view('purchases.details')
                ->withTransaction($transaction)
                ->withPayments($payments);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function purchasingInvoice(Transaction $transaction)

    {
        $current_locale = app()->getLocale();
        \App::setLocale('ar');
        $secondary_lang = \Lang::get('core');
        \App::setLocale($current_locale);

        $total_quanity = 0;
        foreach($transaction->purchases as $purchase){
            $total_quanity += $purchase->quantity;
        }

        return view('purchases.invoice')
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
    public function deletePurchase(Request $request) {

        $transaction = Transaction::findorFail($request->get('id'));
        foreach ($transaction->purchases as $purchase) {
            //subtract deleted product from stock
            $product = Product::findorFail($purchase->product_id);
            $current_stock = $product->quantity;
            $product->quantity = $current_stock - $purchase->quantity;
            $product->save();

            //delete the purchase entry in purchases table
            $purchase->delete();
        }

        //delete all the payments against this transaction
        foreach($transaction->payments as $payment){
            $payment->delete();
        }

        //delete the transaction entry for this sale
        $transaction->delete();

        $message = trans('core.deleted');
        return redirect()->route('purchase.index')->withSuccess($message);
    }

}
