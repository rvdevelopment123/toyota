<?php
namespace App\Http\Controllers;

use App\Sell;
use DateTime;
use App\Client;
use DatePeriod;
use App\Payment;
use App\Expense;
use App\Product;
use App\Purchase;
use DateInterval;
use Carbon\Carbon;
use App\Transaction;
use App\CashRegister;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndex()
    {
        /*Dashboard box with last seven days graph*/
        $dayNames = [];
        $lastSevenDaySells = [];
        $lastSevenDayPurchases = [];
        $todays_stats['total_selling_quantity'] = 0;
        $todays_stats['total_purchasing_quantity'] = 0;
        
        for($i = 0; $i <= 5; $i++)
        {
            $dayNames[] = now()->subDays($i)->format('D');

            //check if today or not
            if($i == 0)
            {
                $getNow = now()->format("Y-m-d");
                $getStarts = Carbon::createFromFormat('Y-m-d', $getNow)->startOfDay();
                $getEnds = Carbon::createFromFormat('Y-m-d', $getNow)->endOfDay();
                //calculation of total expense today
                /*$todaysExpense = Expense::whereBetween('created_at',[$getStarts,$getEnds])->sum('amount');*/
                
                if(settings('dashboard') == 'tile-box'){
                    //calculation of total sells & invoices today
                    $todaysInvoices = Transaction::whereBetween('created_at',[$getStarts,$getEnds])->where('transaction_type', 'sell')->get();
                    
                    foreach($todaysInvoices as $todaysInvoice){
                        $todays_stats['total_selling_quantity'] = $todays_stats['total_selling_quantity'] + $todaysInvoice->sells->sum('quantity');
                    }

                    //calculation of total purchasing price and purchased quantity today
                    $todaysBills = Transaction::whereBetween('created_at',[$getStarts,$getEnds])->where('transaction_type', 'purchase')->get();
                    
                    foreach($todaysBills as $todaysBill){
                        $todays_stats['total_purchasing_quantity'] = $todays_stats['total_purchasing_quantity'] + $todaysBill->purchases->sum('quantity');
                    }
                }
            }else
            {
                $getNow = now()->subDays($i)->format('Y-m-d');
                $getStarts = Carbon::createFromFormat('Y-m-d', $getNow)->startOfDay();
                $getEnds = Carbon::createFromFormat('Y-m-d', $getNow)->endOfDay();
            }

            
            $lastSevenDaySells[] = Transaction::whereBetween('created_at' , [$getStarts , $getEnds])->where('transaction_type', 'sell')->sum('net_total');
            $lastSevenDayPurchases[] = Transaction::whereBetween('created_at' , [$getStarts , $getEnds])->where('transaction_type', 'purchase')->sum('net_total');
            $lastSevenDayTransactions[] = Payment::whereBetween('created_at',[$getStarts,$getEnds])->sum('amount');

        }
        
        //today's total transaction
        $todays_stats['total_transactions_today'] = $lastSevenDayTransactions[0];
        //today's total selling price
        $todays_stats['total_selling_price'] = $lastSevenDaySells[0];
        //today's total purchasing price
        $todays_stats['total_purchasing_price'] = $lastSevenDayPurchases[0];
        //get the name of last seven days
        $daynames = array_reverse($dayNames);

        $lastSevenDaySells = implode(',', array_reverse($lastSevenDaySells));
        $lastSevenDayPurchases = implode(',', array_reverse($lastSevenDayPurchases));
        $lastSevenDayTransactions = implode(',', array_reverse($lastSevenDayTransactions));
        /*Dashboard box with last seven days graph ends*/
       
        //top 5 products
        $start = Carbon::now()->startOfMonth();
        $end = Carbon::now()->endOfMonth();

        $stock_value_by_cost = 0;
        $stock_value_by_price = 0;
        $products = Product::select('id','name','cost_price', 'mrp', 'quantity')->get();
        foreach($products as $product){
           $top_products_all[$product->name] =  $product->sells->sum('quantity');
           $top_products_month[$product->name] =  $product->sells()->whereBetween('created_at',[$start,$end])->sum('quantity');
           
           //cost & sell value of stock
           $stock_value_by_cost = $stock_value_by_cost + $product->quantity * $product->cost_price;
           
           $stock_value_by_price = $stock_value_by_price + $product->quantity * $product->mrp;
        }
        
        if($products->count() != 0){
            arsort($top_products_month);
            $top_products =  array_slice($top_products_month, 0, 5);
        }else{
            $top_products = [];
        }
        
        $top_product_name = [];
        $selling_quantity = [];
        foreach($top_products as $x => $top_product){
            $top_product_name[] = $x;
            $selling_quantity[] = $top_product;
        }
        /*top products ends*/
            
        //stock value by cost price and mrp
        $profit_estimate = $stock_value_by_price - $stock_value_by_cost;
        $stock = [$stock_value_by_cost, $stock_value_by_price, $profit_estimate];

        //sell vs purchase graph
        for($i = 0; $i <= 5; $i++){
            $nowM = Carbon::now()->month;
            $nowY = Carbon::now()->year;
            $now = $nowY."-".$nowM."-15 00:00:00";
            $now = Carbon::parse($now);

            if($i == 0){
                $now = $now->format("Y-m-d");
            }else{
                $now = $now->subMonths($i)->format('Y-m-d');
            }        

            $from = Carbon::createFromFormat('Y-m-d', $now )->startOfMonth();
            $to = Carbon::createFromFormat('Y-m-d', $now )->endOfMonth();
            $month = Carbon::createFromFormat('Y-m-d',$now)->format("M");
            $months[] = $month;

            $transactionThisMonth = Transaction::whereBetween('created_at' , [$from , $to]);
            $sellDiscount = $transactionThisMonth->where('transaction_type', 'sell')->sum('discount');
            $total_selling_tax = Transaction::whereBetween('created_at' , [$from , $to])->where('transaction_type', 'sell')->sum('total_tax');
            
            $purchaseDiscount = Transaction::whereBetween('created_at' , [$from , $to])->where('transaction_type', 'purchase')->sum('discount');
            $total_purchasing_tax = Transaction::whereBetween('created_at' , [$from , $to])->where('transaction_type', 'purchase')->sum('total_tax');

            $sell_array = Sell::whereBetween('created_at' , [$from , $to]);
            $sells[] = $sell_array->sum('sub_total') + $total_selling_tax - $sellDiscount;
            
            $purchase_array = Purchase::whereBetween('created_at' , [$from , $to]);
            $purchases[] = $purchase_array->sum('sub_total') + $total_purchasing_tax - $purchaseDiscount;


            //last six month's profit calculation
            $lastSixMonthsSellTransactions = Transaction::whereBetween('created_at' , [$from , $to])->where('transaction_type', 'sell');
            
            $last_six_months_profit[] = $lastSixMonthsSellTransactions->sum('total') - $lastSixMonthsSellTransactions->sum('total_cost_price');
            
        }

        return view('home', compact('totalCashReceived', 'totalCashGiven', 'todays_stats', 'top_product_name', 'selling_quantity', 'stock', 'months', 'sells', 'purchases', 'last_six_months_profit', 'lastSevenDaySells', 'lastSevenDayPurchases', 'lastSevenDayTransactions', 'daynames'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function todayCashIn()
    {
        $today = Carbon::now()->format('Y-m-d');
        $today_starts = Carbon::createFromFormat('Y-m-d',$today)->startOfDay();
        $today_ends = Carbon::createFromFormat('Y-m-d',$today)->endOfDay();
        
        $received_cash = Payment::whereBetween('created_at',[$today_starts,$today_ends])->where('type', 'credit')->get();

        $total_received_cash = $received_cash->sum('amount');
        return view('dashboard.cashin', compact('received_cash', 'total_received_cash'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function todayCashOut()
    {
        $today = Carbon::now()->format('Y-m-d');
        $today_starts = Carbon::createFromFormat('Y-m-d',$today)->startOfDay();
        $today_ends = Carbon::createFromFormat('Y-m-d',$today)->endOfDay();
        
        $paid_rows = Payment::whereBetween('created_at',[$today_starts,$today_ends])->where('type', '!=', 'credit')->get();

        $total_paid_sum = $paid_rows->sum('amount');
        return view('dashboard.cashout', compact('paid_rows', 'total_paid_sum'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    private $searchParams = ['invoice_no', 'customer', 'bill_no'];

    public function todayInvoice(Request $request)
    {
        $customers =  Client::orderBy('first_name', 'asc')->where('client_type', "!=" ,'purchaser')->pluck('first_name', 'id');

        $today = Carbon::now()->format('Y-m-d');
        $today_starts = Carbon::createFromFormat('Y-m-d',$today)->startOfDay();
        $today_ends = Carbon::createFromFormat('Y-m-d',$today)->endOfDay();
        
        $invoices = Transaction::whereBetween('created_at',[$today_starts,$today_ends])
                                    ->where('transaction_type', 'sell');
        if($request->get('invoice_no')) {
            $invoices->where('reference_no', 'LIKE', '%' . $request->get('invoice_no') . '%');
        }

        if($request->get('customer')) {
            $invoices->whereClientId($request->get('customer'));
        }

        return view('dashboard.invoice-list-today')
                    ->withCustomers($customers)
                    ->withInvoices($invoices->paginate(20));
    }

    /**
     * post of index.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postTodayInvoice(Request $request) {
        $params = array_filter($request->only($this->searchParams));
        return redirect()->action('HomeController@todayInvoice', $params);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function todaysBill(Request $request)
    {
        $suppliers = Client::orderBy('first_name', 'asc')->where('client_type', 'purchaser')->pluck('first_name', 'id');
        $today = Carbon::now()->format('Y-m-d');
        $today_starts = Carbon::createFromFormat('Y-m-d',$today)->startOfDay();
        $today_ends = Carbon::createFromFormat('Y-m-d',$today)->endOfDay();
        
        $bills = Transaction::whereBetween('created_at',[$today_starts,$today_ends])
                                    ->where('transaction_type', 'purchase')->orderBy('id', 'desc');

        if($request->get('bill_no')) {
            $bills->where('reference_no', 'LIKE', '%' . $request->get('bill_no') . '%');
        }

        if($request->get('supplier')) {
            $bills->whereClientId($request->get('supplier'));
        }
        return view('dashboard.bill-list-today')
                    ->withBills($bills->paginate(20))
                    ->withSuppliers($suppliers);
    }

     /**
     * post of index.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postTodayBill(Request $request) {
        $params = array_filter($request->only($this->searchParams));
        return redirect()->action('HomeController@todaysBill', $params);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function todayExpense()
    {
        $today = Carbon::now()->format('Y-m-d');
        $today_starts = Carbon::createFromFormat('Y-m-d',$today)->startOfDay();
        $today_ends = Carbon::createFromFormat('Y-m-d',$today)->endOfDay();
        
        $expense_rows = Expense::whereBetween('created_at',[$today_starts,$today_ends])->get();
        $total_expense_sum = $expense_rows->sum('amount');
        return view('dashboard.expense', compact('expense_rows', 'total_expense_sum'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function todayTransaction()
    {
        $today = Carbon::now()->format('Y-m-d');
        $today_starts = Carbon::createFromFormat('Y-m-d',$today)->startOfDay();
        $today_ends = Carbon::createFromFormat('Y-m-d',$today)->endOfDay();
        
        $transaction_lists = Payment::whereBetween('created_at',[$today_starts,$today_ends])->orderBy('id', 'desc')->get();

        $transaction_amount = $transaction_lists->sum('amount');
        return view('dashboard.transaction-list-today', compact('transaction_lists', 'transaction_amount'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function cashDetails()
    {   
        $today = Carbon::now()->format('Y-m-d');
        $today_starts = Carbon::createFromFormat('Y-m-d',$today)->startOfDay();
        $today_ends = Carbon::createFromFormat('Y-m-d',$today)->endOfDay();

        $cash_in_hands = CashRegister::where('date', $today)->sum('cash_in_hands');
        $total_received = Payment::whereBetween('created_at',[$today_starts,$today_ends])->where('type', 'credit')->where('method', 'cash')->sum('amount');

        $total_paid = Payment::whereBetween('created_at',[$today_starts,$today_ends])->where('type', '!=', 'credit')->where('method', 'cash')->sum('amount');
        $total_expense = Expense::whereBetween('created_at',[$today_starts,$today_ends])->sum('amount');
        
        $remaining_cash = $cash_in_hands + $total_received - $total_paid - $total_expense;

        return view('dashboard.today-cash-details', compact('cash_in_hands', 'total_received', 'total_paid', 'total_expense', 'remaining_cash'));
    }


    public function profitDetails() {
        $today = Carbon::now()->format('Y-m-d');
        $today_starts = Carbon::createFromFormat('Y-m-d',$today)->startOfDay();
        $today_ends = Carbon::createFromFormat('Y-m-d',$today)->endOfDay();

        //calculation of total profit today
        $sellTransactionToday = Transaction::whereBetween('created_at',[$today_starts,$today_ends])->where('transaction_type', 'sell');

        $SellItemMrp = $sellTransactionToday->sum('total');
        $SellItemCost = $sellTransactionToday->sum('total_cost_price');
        $SellItemTax = $sellTransactionToday->sum('total_tax');

        $todaysExpense = Expense::whereBetween('created_at',[$today_starts,$today_ends])->sum('amount');
        

        $todaysProfit = $SellItemMrp - $SellItemCost;
        return view('dashboard.profit-details', compact('todaysProfit', 'SellItemCost', 'SellItemMrp', 'todaysExpense', 'SellItemTax'));
    }

}
