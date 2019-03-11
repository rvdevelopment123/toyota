<?php

namespace App\Http\Controllers\API\Backend;

use Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ClientRequest;

class CustomersController extends Controller
{
    public function post (ClientRequest $request) {
    	$response = Customer::save($request);
    	return response()->json(['created'], 201);
    }
}
