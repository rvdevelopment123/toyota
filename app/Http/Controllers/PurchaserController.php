<?php
namespace App\Http\Controllers;

use App\Client;
use App\Transaction;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Requests\ClientRequest;
use App\Http\Controllers\Controller;

class PurchaserController extends Controller
{
    private $searchParams = ['name', 'phone', 'company_name', 'address'];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndex(Request $request)
    {
        $purchasers = Client::orderBy('first_name', 'asc')->where('client_type', 'purchaser');
        if($request->get('name')) {
            $purchasers->where(function($q) use($request) {
                $q->where('first_name', 'LIKE', '%' . $request->get('name') . '%');
            });
        }

        if($request->get('phone')) {
            $purchasers->where('phone', 'LIKE', '%' . $request->get('phone') . '%');
        }

        if($request->get('company_name')) {
            $purchasers->where('company_name', 'LIKE', '%' . $request->get('company_name') . '%');
        }

        if($request->get('address')) {
            $purchasers->where('address', 'LIKE', '%' . $request->get('address') . '%');
        }

        return view('purchaser.index')->withPurchasers($purchasers->paginate(20));
    }


    public function postIndex(Request $request) {
        $params = array_filter($request->only($this->searchParams));
        return redirect()->action('PurchaserController@getIndex', $params);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getNewPurchaser()
    {
        $purchaser = New Client;
        return view('purchaser.form', compact('purchaser'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postPurchaser(Client $client,ClientRequest $request)
    {
        $client->first_name = ucwords($request->get('first_name'));
        $client->last_name = ucwords($request->get('last_name'));
        $client->company_name = ucwords($request->get('company_name'));
        $client->email = $request->get('email');
        $client->phone = $request->get('phone');
        $client->address = $request->get('address');
        $client->client_type = 'purchaser';
        $client->account_no = $request->get('account_no');

        if($request->get('previous_due') != null){
            $client->provious_due = $request->get('previous_due');
        }

        $client->save();

        if($request->get('previous_due') != null){
            $transaction = new Transaction;
            $row = Transaction::where('transaction_type', 'opening')->withTrashed()->get()->count() > 0 ? Transaction::where('transaction_type', 'opening')->withTrashed()->get()->count() + 1 : 1;
            $ref_no = "OPENING-".ref($row);
            $transaction->reference_no = $ref_no;
            $transaction->client_id = $client->id;
            $transaction->transaction_type = "opening";
            $transaction->warehouse_id = 1;
            $transaction->total = $request->get('previous_due');
            $transaction->invoice_tax = 0;
            $transaction->total_tax = 0;
            $transaction->labor_cost = 0;
            $transaction->net_total = $request->get('previous_due');
            $transaction->paid = 0;
            $transaction->save();

        }

        $message = trans('core.changes_saved');
        return redirect()->route('purchaser.index')->withSuccess($message);
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
        }
        $message = trans('core.client_has_sells');
        return redirect()->back()->withMessage($message);
    }
}
