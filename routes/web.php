<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'HomeController@getIndex')->middleware(['auth', 'revalidate'])->name('home');
Route::get('home', 'HomeController@getIndex')->middleware(['auth', 'revalidate'])->name('home');
Route::get('logout','UserController@logout')->name('logout');
Route::get('lock', 'UserController@lock')->middleware('auth')->name('lock');
Route::get('locked', 'UserController@locked')->name('locked');
Route::post('locked', 'UserController@unlock')->name('unlock');
Route::get('verify-purchase', 'SettingsController@verifyPurchase')->name('verify-purchase');
Route::post('verify-purchase', 'SettingsController@postVerifyPurchase')->name('verify-purchase-post');

Route::group(['prefix'=>'admin', 'middleware' => ['auth', 'revalidate'] ], function(){

	/*========================================================
		Admin Routes
	=========================================================*/
	// Language Switcher
	Route::get('locale/{locale}', 'SettingsController@switchLocale')->name('locale.set');
	
	/*========================================================
		Agents route
	=========================================================*/
	Route::get('agent', 'AgentController@getIndex')->name('agents.index');
	
	Route::get('agent/details/{transaction}', 'AgentController@agentDetails')->name('agents.details');

	/*========================================================
		Dashboard Routes
	=========================================================*/

	Route::get('/cashin/today', 'HomeController@todayCashIn')->middleware('permission:cash.view')->name('cashin.today');
	Route::get('/cashout/today', 'HomeController@todayCashOut')->middleware('permission:cash.view')->name('cashout.today');
	Route::get('/transactions/today', 'HomeController@todayTransaction')->middleware('permission:cash.view')->name('transactions.today');
	Route::get('/invoice/today', 'HomeController@todayInvoice')->middleware('permission:cash.view')->name('invoice.today');
	Route::post('/invoice/today', 'HomeController@postTodayInvoice');

	Route::get('/bill/today', 'HomeController@todaysBill')->middleware('permission:cash.view')->name('bill.today');
	Route::post('/bill/today', 'HomeController@postTodayBill');

	Route::get('/expense/today', 'HomeController@todayExpense')->middleware('permission:expense.create')->name('expense.today');
	Route::get('profit/details', 'HomeController@profitDetails')->middleware('permission:profit.view')->name('profit.details');


	/*========================================================
		Category Routes
	=========================================================*/
	Route::model('category', 'App\Category');
	Route::get('category', 'CategoryController@getIndex')->name('category.index');
	Route::post('category', 'CategoryController@postIndex');
	
	Route::get('category/new', 'CategoryController@getNewCategory')->middleware('permission:category.create')->name('category.new');
	Route::post('category/new', 'CategoryController@postCategory')->middleware('permission:category.create')->name('category.post');

	Route::get('category/{category}', 'CategoryController@getEditCategory')->middleware('permission:category.manage')->name('category.edit');
	Route::post('category/{category}', 'CategoryController@postCategory')->middleware('permission:category.manage')->name('category.post');
	Route::delete('category/delete/{category}', 'CategoryController@deleteCategory')->middleware('permission:category.manage')->name('category.delete');

	Route::get('product/ajaxData', 'CategoryController@ajaxRequest')->name('category.subcategory');


	/*========================================================
		Subcategory Routes
	=========================================================*/
	Route::model('subcategory', 'App\Subcategory');
	Route::get('subcategory', 'SubcategoryController@getIndex')->name('subcategory.index');
	Route::post('subcategory', 'SubcategoryController@postIndex');
	
	Route::get('subcategory/new', 'SubcategoryController@getNewSubcategory')->middleware('permission:category.create')->name('subcategory.new');
	Route::post('subcategory/new', 'SubcategoryController@postSubcategory')->middleware('permission:category.create')->name('subcategory.post');

	Route::get('subcategory/{subcategory}', 'SubcategoryController@getEditSubcategory')->middleware('permission:category.manage')->name('subcategory.edit');
	Route::post('subcategory/{subcategory}', 'SubcategoryController@postSubcategory')->middleware('permission:category.manage')->name('subcategory.post');
	Route::delete('subcategory/delete/{subcategory}', 'SubcategoryController@deleteSubcategory')->middleware('permission:category.manage')->name('subcategory.delete');

	Route::get('subcategory/{subcategory}/product', 'SubcategoryController@getProductList')->name('subcategory.products');


	/*========================================================
		Product Route
	=========================================================*/
	Route::model('product', 'App\Product');
	Route::get('product', 'ProductController@getIndex')->middleware('permission:product.view')->name('product.index');
	Route::post('product', 'ProductController@postIndex');
	
	Route::get('product/new', 'ProductController@getNewProduct')->middleware('permission:product.create')->name('product.new');
	Route::post('product/new', 'ProductController@postProduct')->middleware('permission:product.create')->name('product.post');

	Route::get('product/{product}', 'ProductController@getEditProduct')->middleware('permission:product.manage')->name('product.edit');
	Route::post('product/{product}', 'ProductController@postProduct')->middleware('permission:product.manage')->name('product.post');

	Route::get('product/{product}/details', 'ProductController@getProductDetails')->name('product.details');

	Route::delete('product/delete/{product}', 'ProductController@deleteProduct')->middleware('permission:product.manage')->name('product.delete');

	Route::get('product/print/all', 'ProductController@printAllProduct')->name('product.printall');
	
	//print barcode
	Route::get('product/{product}/print/barcode', 'ProductController@printSingleBarcode')->name('single.print_barcode');
	Route::get('product/print/barcode', 'ProductController@printBarcode')->name('product.print_barcode');

	Route::post('product/price/update', 'ProductController@updatePrice')->middleware('permission:product.manage')->name('product.price.update');

	//route for the list of alert products
	Route::get('product/alert/all', 'ProductController@alertProduct')->name('product.alert');

	//Transaction Model
	Route::model('transaction', 'App\Transaction');

	/*=======================================================
	Purchase route
	=========================================================*/
	Route::model('purchase', 'App\Purchase');
	Route::get('purchase', 'PurchaseController@getIndex')->name('purchase.index');
	Route::post('purchase', 'PurchaseController@postIndex');
	
	Route::get('purchase/new', 'PurchaseController@getNewPurchase')->middleware('permission:purchase.create')->name('purchase.item');
	Route::post('purchase/new', 'PurchaseController@postPurchase')->name('permission:purchase.create')->name('purchase.post');

	Route::get('purchase/details/{transaction}', 'PurchaseController@purchaseDetails')->name('purchase.details');
	Route::get('purchase/{transaction}/invoice', 'PurchaseController@purchasingInvoice')->name('purchase.invoice');
  Route::post('purchase/delete','PurchaseController@deletePurchase')->name('purchase.delete');


	/*========================================================
		Sells route
	=========================================================*/
	Route::model('sell', 'App\Sell');
	Route::get('sell', 'SellController@getIndex')->name('sell.index');
	Route::post('sell', 'SellController@postIndex');

	Route::get('sell/new', 'SellController@getNewsell')->middleware('permission:sell.create')->name('sell.form');
	Route::post('sell/new', 'SellController@postSell')->middleware('permission:sell.create')->name('sell.post');
	
	Route::get('sells/details/{transaction}', 'SellController@sellsDetails')->name('sells.details');

	Route::get('sell/{transaction}/invoice', 'SellController@sellingInvoice')->name('sell.invoice');	
	//return routes
	Route::get('sell/return/{transaction}', 'SellController@returnSell')->middleware('permission:return.create')->name('sell.return');
	Route::post('sell/return/{transaction}', 'SellController@returnSellPost')->middleware('permission:return.create')->name('return.post');

	Route::delete('sell/delete/{transaction}', 'SellController@deleteSell')->middleware('permission:sell.manage')->name('sell.delete');

	/*========================================================
		POS route
	=========================================================*/
	Route::get('pos', 'posController@getPOS')->name('sell.pos');
	Route::post('pos/sell/save', 'posController@posPost')->name('api.v1.sell.save');
	Route::get('pos/sell/invoice/{id}', 'posController@posInvoice')->name('pos.invoice');
	


	/*========================================================
		Payment route
	=========================================================*/
	Route::model('payment', 'App\Payment');
	Route::post('payment', 'PaymentController@postPayment')->name('payment.post');

	Route::get('transaction/all', 'PaymentController@getIndex')->middleware('permission:transaction.view')->name('payment.list');
	Route::post('transaction/all', 'PaymentController@postIndex');
	
	Route::get('print/{payment}/receipt', 'PaymentController@printReceipt')->name('payment.voucher');



	/*========================================================
		Client route
	=========================================================*/
	Route::model('client', 'App\Client');
	Route::get('client', 'ClientController@getIndex')->middleware('permission:customer.view')->name('client.index');
	Route::post('client', 'ClientController@postIndex');
	
	Route::get('client/new', 'ClientController@getNewClient')->middleware('permission:customer.create')->name('client.new');
	Route::get('client/{client}', 'ClientController@getEditClient')->middleware('permission:customer.manage')->name('client.edit');
	
	Route::post('client/save', 'ClientController@postClient')->middleware('permission:customer.manage')->name('client.save');

	Route::get('client/invoices/{client}', 'ClientController@getClientInvoices')->name('client.invoices');
	Route::get('client/{client}/details', 'ClientController@getDetailsClient')->name('client.details');
	Route::get('client/transaction/{id}', 'ClientController@paymentList')->name('client.payment.list');
	Route::delete('client/delete/{client}', 'ClientController@deleteClient')->middleware('permission:customer.manage')->name('client.delete');



	/*========================================================
		Supplier route
	=========================================================*/
	Route::get('purchaser', 'PurchaserController@getIndex')->middleware('permission:supplier.view')->name('purchaser.index');
	Route::post('purchaser', 'PurchaserController@postIndex');
	
	Route::get('purchaser/new', 'PurchaserController@getNewPurchaser')->middleware('permission:customer.create')->name('purchaser.new');
	Route::post('purchaser/new', 'PurchaserController@postPurchaser')->middleware('permission:customer.create')->name('purchaser.post');


	/*========================================================
		User route
	=========================================================*/
	Route::model('user', 'App\User');
	Route::get('user', 'UserController@getIndex')->name('user.index');
	Route::post('user', 'UserController@postIndex');

	Route::get('user/new', 'UserController@getNewUser')->middleware('permission:user.create')->name('user.new');
	Route::post('user/new', 'UserController@postUser')->middleware('permission:user.create')->name('user.post');
	Route::get('user/profile', 'UserController@viewProfile')->name('user.profile');
	Route::post('user/verify-old-password', 'UserController@verifyOldPassword')->name('user.old-password');
	Route::post('user/profile', 'UserController@postProfile')->name('user.profile.post');
	Route::post('change/password', 'UserController@changePassword')->name('change.password');

	Route::post('user/status', 'UserController@postStatus')->middleware('permission:user.manage')->name('user.status');

	Route::get('user/{user}/edit', 'UserController@getEditUser')->middleware('permission:user.manage')->name('user.edit');
	Route::post('user/{user}/edit', 'UserController@postUser')->middleware('permission:user.manage')->name('user.post');



	/*========================================================
		Expense route
	=========================================================*/
	Route::model('expense', 'App\Expense');

	Route::get('expense', 'ExpenseController@getIndex')->name('expense.index');
	Route::post('expense', 'ExpenseController@postIndex')->name('expense.post')->middleware('permission:expense.create');

	Route::post('expense/search', 'ExpenseController@postSearch')->name('expense.search');

	Route::post('expense/edit', 'ExpenseController@editExpense')->name('expense.edit');
	Route::post('expense/delete', 'ExpenseController@deleteExpense')->name('expense.delete');


	/*========================================================
		Cash Register route
	=========================================================*/
	Route::model('cash_register', 'App\CashRegister');
	Route::get('cash/details', 'HomeController@cashDetails')->name('cash.details');
	Route::post('cash_register', 'ExpenseController@cashRegister')->name('cash_register.post');


	/*========================================================
		Settings route
	=========================================================*/
	Route::model('setting', 'App\Setting');
	Route::get('settings', 'SettingsController@getIndex')->name('settings.index');
	Route::post('settings', 'SettingsController@postIndex')->middleware('permission:settings.manage')->name('settings.post');
	Route::get('settings/backup', 'SettingsController@getBackup')->middleware('permission:admins.manage')->name('settings.backup');


	/*========================================================
		Report route
	=========================================================*/
	Route::get('report', 'ReportingController@getIndex')->middleware('permission:report.view')->name('report.index');
	Route::post('report/purchase-report', 'ReportingController@postPurchaseReport')->name('report.purchase');
	Route::post('report/sells-report', 'ReportingController@postSellsReport')->name('report.sells');
	Route::post('report/product-report', 'ReportingController@postProductReport')->name('report.product');
	Route::post('report/client-report', 'ReportingController@postClientReport')->name('report.client');
	Route::post('report/stock-report', 'ReportingController@postStockReport')->name('report.stock');
	Route::post('report/category-report', 'ReportingController@postCategoryReport')->name('report.category');
	Route::post('report/subcategory-report', 'ReportingController@postSubCategoryReport')->name('report.subcategory');
	Route::post('report/branch', 'ReportingController@postBranchReport')->name('report.branch');
	Route::post('report/profit', 'ReportingController@postProfitReport')->name('report.profit');


	/*========================================================
		Role Permission
	=========================================================*/
	Route::get('role', 'RolePermissionController@getIndex')->name('role.index');
	Route::post('role', 'RolePermissionController@postRole')->name('role.post');

	Route::get('role/{role}/permission', 'RolePermissionController@setRolePermissions')->middleware('permission:admins.manage')->name('role.permission');
	Route::post('role/{role}/permission', 'RolePermissionController@postRolePermissions')->middleware('permission:admins.manage')->name('post.role.permission');

	/*========================================================
		Tax Rates
	=========================================================*/
	Route::get('vat', 'TaxController@getIndex')->name('tax.index');
	Route::post('vat', 'TaxController@postTax')->name('tax.post');
	Route::post('vat/delete','TaxController@deleteTax')->name('tax.delete');
	Route::post('vat/edit', 'TaxController@editTax')->name('tax.edit');

	/*========================================================
		Warehouse Routes
	=========================================================*/
	Route::model('warehouse', 'App\Warehouse');
	Route::get('warehouse', 'WarehouseController@getIndex')->name('warehouse.index');
	
	Route::get('warehouse/new', 'WarehouseController@getNewWarehouse')->name('warehouse.new');
	Route::post('warehouse/new', 'WarehouseController@postWarehouse')->name('warehouse.post');

	Route::get('warehouse/{warehouse}', 'WarehouseController@getEditWarehouse')->name('warehouse.edit');
	Route::post('warehouse/{warehouse}', 'WarehouseController@postWarehouse')->name('warehouse.post');
	Route::delete('warehouse/delete/{warehouse}', 'WarehouseController@deleteWarehouse')->name('warehouse.delete');
	
});

Auth::routes();

