<?php
namespace App\Services;

use App\Client;
use App\Transaction;
use Illuminate\Http\Request;
use App\Http\Requests\ClientRequest;

class CustomerService
{

    public function save (ClientRequest $request) {
        $client = Client::firstOrNew(['id' => $request->get('id')]);
        $editing  = ($client->id != null) ? 1 : 0;
        $client->first_name = ucwords($request->get('first_name'));
        $client->last_name = ucwords($request->get('last_name'));
        $client->company_name = ucwords($request->get('company_name'));
        $client->email = $request->get('email');
        $client->phone = $request->get('phone');
        $client->address = ucwords($request->get('address'));
        $client->account_no = $request->get('account_no');
        $client->client_type = empty($request->get('client_type')) ? 'purchaser' : $request->get('client_type') ;
        if($request->get('previous_due') != null){
            $client->provious_due = $request->get('previous_due');
        }

        $client->save();

        if($request->get('previous_due') != null){
            $isOpening = Transaction::where('client_id', $client->id)->where('transaction_type', 'opening')->count();
            $transaction = ($editing != 1 || $isOpening == 0) ? new Transaction : Transaction::where('client_id', $client->id)->where('transaction_type', 'opening')->first();
            
            $row = Transaction::where('transaction_type', 'opening')->withTrashed()->get()->count() > 0 ? Transaction::where('transaction_type', 'opening')->withTrashed()->get()->count() + 1 : 1;
            $ref_no = "OPENING-".ref($row);
            $transaction->reference_no = ($editing != 1 || $isOpening == 0) ? $ref_no : $transaction->reference_no;
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

        return response(['client' => $client]);
    }

}
