<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('client', 'ClientController@getClientAPI')->name('client.api');

Route::prefix('v1')->namespace('API\Backend')->group(function () {
	Route::post('customer/save', 'CustomersController@post')->name('api.v1.customer.save');	

	// Frequest products
	Route::get('products', 'ProductsController@getFrequent')->name('api.v1.products.frequent');
	Route::get('category/{category}/products', 'ProductsController@getCategoryProducts')->name('api.v1.category.frequent');	
	Route::get('product-by-barcode/{barcode}','ProductsController@getProductByBarcode')->name('api.v1.product.by_barcode');

	//Product by search
	Route::get('product-by-search/{search}','ProductsController@getProductBySearch')->name('api.v1.product.by_search');

	//post sell by pos
	Route::post('pos/save', 'SellController@posPost')->name('api.v1.sell.save');	

});
