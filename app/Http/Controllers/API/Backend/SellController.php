<?php

namespace App\Http\Controllers\API\Backend;

use DB;
use App\Sell;
use App\Product;
use App\Payment;
use App\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SellController extends Controller
{
    public function post (Request $request) {
    	$customer = $request->get('customer');
        $enableProductTax = settings('product_tax');
        
        $row = Transaction::where('transaction_type', 'sell')->withTrashed()->get()->count() > 0 ? Transaction::where('transaction_type', 'sell')->withTrashed()->get()->count() + 1 : 1;
        $ref_no = "SL-".ref($row);
        $total = 0;
        $totalProductTax = 0;
        $productTax = 0;
        $total_cost_price = 0;
        $sells = $request->get('sells');
        $paid = floatval($request->get('paid')) ?: 0;

        DB::transaction(function() use ($request , $sells, $ref_no, &$total, &$total_cost_price, &$totalProductTax, $customer, $paid, $enableProductTax, $productTax){
            foreach ($sells as $sell_item) {
                
                /*if (intval($sell_item['sell_quantity']) === 0) {
                    throw new ValidationException('Product quantity is required');
                }

                if (!$sell_item['id']) {
                    throw new ValidationException('Product ID is required');
                }*/
                
                $total = $total + ($sell_item['sell_quantity'] * $sell_item['mrp']);
                $total_cost_price = $total_cost_price + ($sell_item['cost_price'] * $sell_item['sell_quantity']);

                $sell = new Sell;
                    $sell->reference_no = $ref_no;
                    $sell->product_id = $sell_item['id'];
                    $sell->quantity = $sell_item['sell_quantity'];

                    if($enableProductTax == 1){
                        //product tax calculation
                        $product_row = Product::findorFail($sell_item['id']);
                        $taxRate = $product_row->tax->rate;
                        $taxType = $product_row->tax->type;

                        $productTax = ($taxType == 1) ? (($sell_item['sell_quantity'] * $taxRate * $product_row->mrp) / 100) : ($sell_item['sell_quantity'] * $taxRate);

                        $sell->product_tax = $productTax;
                        //ends
                        $totalProductTax = $totalProductTax + $productTax;
                    }

                    $sell->unit_cost_price = $sell_item['cost_price'];
                    $sell->sub_total = ($sell_item['sell_quantity'] * $sell_item['mrp'])- $productTax;
                    $sell->client_id = $customer;
                $sell->save();

                //update quantity of product after every sell
                $product = $sell->product;
                $product->quantity = $product->quantity - intval($sell_item['sell_quantity']);
                $product->save();
            }

            //discount
	    	$discount = $request->get('discount_amount');
	    	$total_payable = $total - $discount;
	    	//discount ends

            //invoice tax
            if(settings('invoice_tax') == 1){
                $invoice_tax = $request->get('invoice_tax');
            }else{
                $invoice_tax = 0;
            }
            //ends

            $transaction = new Transaction;
                $transaction->reference_no = $ref_no;
                $transaction->client_id = $customer;
                $transaction->transaction_type = 'sell';
                $transaction->total_cost_price = $total_cost_price;
                $transaction->discount = $discount;
                //saving total without product tax and shipping cost
                $transaction->total = $total_payable - $totalProductTax;
                $transaction->invoice_tax = $invoice_tax;
                $transaction->total_tax = $totalProductTax + $invoice_tax;
                $transaction->labor_cost = 0;
                $transaction->net_total = $total_payable + $invoice_tax;
                $transaction->paid = $paid;
            $transaction->save();

            if($paid > 0){
                $payment = new Payment;
                    $payment->client_id = $customer;
                    $payment->amount = $paid;
                    $payment->method = $request->get('method');
                    $payment->type = 'credit';
                    $payment->reference_no = $ref_no;
                    $payment->note = "Paid for Invoice ".$ref_no;
                $payment->save();
            }
        });

        return response(['message' => 'Successfully saved transaction.']);
    	
    }
}
