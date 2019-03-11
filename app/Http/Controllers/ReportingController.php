<?php
namespace App\Http\Controllers;

use App\Sell;
use App\Client;
use App\Product;
use App\Expense;
use App\Purchase;
use App\Category;
use App\Warehouse;
use Carbon\Carbon;
use App\Transaction;
use App\Subcategory;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class ReportingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndex()
    {   
        $products = Product::all();
        $clients = Client::all();
        $categories = Category::all();
        $subcategories = Subcategory::all();
        $warehouses = Warehouse::all();
        return view('reporting.index', 
               compact(
                    'products', 
                    'clients', 
                    'categories', 
                    'subcategories', 
                    'warehouses'
                    )
                );       
    }

    /**
     * post method of purchasing report
     *
     * @return \Illuminate\Http\Response
     */
    public function postPurchaseReport(Request $request)
    {   

        $warehouse_id = $request->get('warehouse_id');
        $query = Transaction::where('transaction_type', 'purchase');
        $transactions = ($warehouse_id == 'all') ? $query : $query->where('warehouse_id', $warehouse_id );
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

        return view('reporting.purchaseReport')
                    ->withTransactions($transactions->get())
                    ->withFrom($request->get('from'))
                    ->withTo($request->get('to'));
    }

    /**
     * post method of purchasing report
     *
     * @return \Illuminate\Http\Response
     */
    public function postSellsReport(Request $request)
    {
        $warehouse_id = $request->get('warehouse_id');
        $warehouse_name = ($warehouse_id == 'all') ? 'All Branch' : Warehouse::where('id', $warehouse_id)->first()->name;

        $query = Transaction::where('transaction_type', 'sell');
        $transactions = ($warehouse_id == 'all') ? $query : $query->where('warehouse_id', $warehouse_id );

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

        return view('reporting.sellsReport')
                    ->withTransactions($transactions->get())
                    ->withFrom($request->get('from'))
                    ->withTo($request->get('to'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function postProductReport(Request $request)
    {   
        //get warehouse/branch name
        $warehouse_id = $request->get('warehouse_id');
        $warehouse_name = ($warehouse_id == 'all') ? 'All Branch' : Warehouse::where('id', $warehouse_id)->first()->name;

        $from = $request->get('from');
        $to = $request->get('to')?:date('Y-m-d');
        $to = Carbon::createFromFormat('Y-m-d',$to);
        $to = filterTo($to);

        $sells = ($warehouse_id == "all") ? Sell::query() : Sell::where('warehouse_id', $warehouse_id);
        $purchases = ($warehouse_id == "all") ? Purchase::query() : Purchase::where('warehouse_id', $warehouse_id);

        if($request->get('from') || $request->get('to')) {
            if(!is_null($from)){
                $from = Carbon::createFromFormat('Y-m-d',$from);
                $from = filterFrom($from);
                $sells->whereBetween('created_at',[$from,$to]);
                $purchases->whereBetween('created_at',[$from,$to]);
            }else{
                $sells->where('created_at','<=',$to);
                $purchases->where('created_at','<=',$to);
            }
        }

        $product_id = $request->get('product_id');
        if($product_id != 'all'){
            $products = Product::whereId($product_id)->get();
        }else{
            $products = Product::all();
        }
        
        $total = [];
        $total_profit = 0;
        foreach ($products as $product) {
            $cloneForSells = clone $sells;
            $cloneForPurchases = clone $purchases;

            $sellRow = $cloneForSells->whereProductId($product->id);
            if($sellRow->count() > 0){
                $sellItemMrp = $sellRow->first()->sub_total / $sellRow->first()->quantity;
                $sellItemCost = $sellRow->first()->unit_cost_price;
            }else{
                $sellItemMrp = 0;
                $sellItemCost = 0;
            }
            
            $total[$product->id]['name'] = $product->name;
            $total[$product->id]['sells'] = $sellRow->sum('quantity')." ".$product->unit;
            $total[$product->id]['purchase'] = $cloneForPurchases->whereProductId($product->id)->sum('quantity')." ".$product->unit;
            
            //profit
            $total[$product->id]['profit'] = ($sellItemMrp - $sellItemCost) * $sellRow->sum('quantity')." ".settings('currency_code');

            $total_profit = $total_profit + floatval($total[$product->id]['profit']);
        }

        
        return view('reporting.productReport')
                    ->withTotal($total)
                    ->withFrom($request->get('from'))
                    ->withTo($request->get('to'))
                    ->with('total_profit',$total_profit)
                    ->with('warehouse_name', $warehouse_name);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function postStockReport(Request $request)
    {
        $product_id = $request->get('product_id');
        if($product_id != 'all'){
            $products = Product::whereId($product_id)->get();
            $product_name = $products->first()->name;
        }else{
            $products = Product::all();
            $product_name = 'All Products';
        }

        $stock_value_by_cost = 0;
        $stock_value_by_price = 0;
        foreach($products as $product){
           //$top_products_all[$product->name] =  $product->sells->sum('quantity');
           
           //cost & sell value of stock
           $stock_value_by_cost = $stock_value_by_cost + ($product->quantity * $product->cost_price);
           
           $stock_value_by_price = $stock_value_by_price + ($product->quantity * $product->mrp);
        }
            
        //stock value by cost price and mrp
        $profit_estimate = $stock_value_by_price - $stock_value_by_cost;
        $stock = [$stock_value_by_cost, $stock_value_by_price, $profit_estimate];

        return view('reporting.stockReport', compact('stock', 'product_name'));    

    }

    /**
     * generate the report of specific category
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function postCategoryReport(Request $request)
    {
        $to = $request->get('to') ?: date('Y-m-d');
        $to = Carbon::createFromFormat('Y-m-d',$to);
        $from = $request->get('from');
        $from = $from ? Carbon::createFromFormat('Y-m-d',$from) : Carbon::createFromDate(1970, 1, 1, env('TIMEZONE', 'UTC'));
        
        $category_id = $request->get('category_id');
        if($category_id != 'all'){
            $categories = Category::where('id', $category_id)->get();
        }else{
            $categories = Category::all();
        }

        $data = [];
        $total_profit = 0;

        foreach($categories as $category){
            if ($category->products->count() == 0) {
                $data[$category->id] = [
                    'name' => $category->category_name,
                    'quantity' => '-',
                    'profit' => "-"
                ];
                continue;
            }

            $profit = 0;
            $quantity = 0;
            foreach($category->products as $product){
                $productSell = $product->sells()->whereBetween('created_at',[$from,$to])->get();
                foreach($productSell as $sell){
                    $mrp = $sell->sub_total;
                    $cost_price = $sell->quantity * $sell->unit_cost_price;
                    $profit += $mrp - $cost_price;
                    $quantity += $sell->quantity;
                }
            }

            $data[$category->id] = [
                'name' => $category->category_name,
                'quantity' => $quantity." ".$product->unit,
                'profit' => settings('currency_code')." ".$profit
            ];
            
            $total_profit = $total_profit + $profit;
            unset($profit);
            unset($quantity);
        }

        return view('reporting.categoryReport', compact('data','from', 'to', 'total_profit'));

    }


    /**
     * generate the report of specific SubCategory
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function postSubCategoryReport(Request $request)
    {
        $to = $request->get('to') ?: date('Y-m-d');
        $to = Carbon::createFromFormat('Y-m-d',$to);
        $from = $request->get('from');
        $from = $from ? Carbon::createFromFormat('Y-m-d',$from) : Carbon::createFromDate(1970, 1, 1, env('TIMEZONE', 'UTC'));
        
        $subcategory_id = $request->get('subcategory_id');
        if($subcategory_id != 'all'){
            $subcategories = Subcategory::where('id', $subcategory_id)->get();
        }else{
            $subcategories = Subcategory::all();
        }   

        $data = [];
        $total_profit = 0;

        foreach($subcategories as $subcategory){
            if ($subcategory->products->count() == 0) {
                $data[$subcategory->id] = [
                    'name' => $subcategory->name,
                    'quantity' => '-',
                    'profit' => "-"
                ];
                continue;
            }

            $quantity = 0;
            $profit = 0;
            
            foreach ($subcategory->products as $product) {
                $productSell = $product->sells()->whereBetween('created_at',[$from,$to])->get();
                
                foreach($productSell as $sell){
                    $quantity = $quantity + $sell->quantity;
                    $mrp = $sell->sub_total;
                    $cost_price = $sell->quantity * $sell->unit_cost_price;
                    $profit += $mrp - $cost_price;
                }

                $data[$subcategory->id] = [
                    'name' => $subcategory->name,
                    'quantity' => $quantity." ".$product->unit,
                    'profit' => settings('currency_code')." ".$profit
                ];
            }

            $total_profit = $total_profit + $profit;
        }
        return view('reporting.subCategoryReport', compact('data', 'from', 'to', 'total_profit'));
    }

    /**
     * generate the report of specific SubCategory
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */

    public function postBranchReport (Request $request){
        $branch = $request->get('warehouse_id');
        $product_id = $request->get('product_id');

        $branch_name = ($branch == 'all') ? 'All Branch' : Warehouse::where('id', $branch)->first()->name;
        $product_name = ($product_id == 'all') ? 'All Product' : Product::where('id', $product_id)->first()->name;

        $products = ($product_id == "all") ? Product::select('name', 'id', 'unit')->get() : Product::whereId($product_id)->get();
        
        $stock = [];

        foreach ($products as $product) {
            $purchase_query = ($branch == 'all') ? $product->purchases() : $product->purchases()->where('warehouse_id', $branch);
            $sell_query = ($branch == 'all') ? $product->sells() : $product->sells()->where('warehouse_id', $branch);

            $purchasing_quantity = $purchase_query->where('product_id', $product->id)->sum('quantity');
            $selling_quantity = $sell_query->where('product_id', $product->id)->sum('quantity');

            $stock[$product->id] = [
                                     'name' => $product->name,
                                     'quantity' => $product->opening_stock + $purchasing_quantity - $selling_quantity. " ". $product->unit, 
                                    ];
        }
        
        return view('reporting.warehouseReport', 
                    compact(
                            'branch_name',
                            'product_name',
                            'stock'
                        )
                    ); 
    }


    /**
     * generate the report of profit
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */

    public function postProfitReport (Request $request){
        
        $branch_id = $request->get('warehouse_id');
        $branch_name = ($branch_id == 'all') ? 'All Branch' : Warehouse::where('id', $branch_id)->first()->name;

        $query = Transaction::where('transaction_type', 'sell');
        $transactions = ($branch_id == 'all') ? $query : $query->where('warehouse_id', $branch_id );

        $from = Carbon::parse($request->get('from')?:date('Y-m-d'))->startOfDay();
        $to = Carbon::parse($request->get('to')?:date('Y-m-d'))->endOfDay();

        $transactions = $transactions->whereBetween('created_at',[$from,$to]);

        $total_selling_price = $transactions->get()->sum('total');
        $total_cost_price = $transactions->get()->sum('total_cost_price');
        $gross_profit = $total_selling_price - $total_cost_price;

        $expenses = Expense::whereBetween('created_at',[$from,$to])->get();
        $cloneExpense = clone $expenses;
        $total_expense = $cloneExpense->sum('amount');

        $net_profit = $gross_profit - $total_expense;

        $total_tax = $transactions->get()->sum('total_tax');

        $net_profit_after_tax = $net_profit - $total_tax;

        return view('reporting.profitReport', 
                    compact(
                            'from',
                            'to',
                            'branch_name',
                            'total_selling_price',
                            'total_cost_price',
                            'gross_profit',
                            'total_expense',
                            'expenses',
                            'net_profit',
                            'total_tax',
                            'net_profit_after_tax'

                        )
                    );
        

        
    }
}
