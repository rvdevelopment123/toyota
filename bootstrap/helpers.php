<?php

	use \NumberFormatter as NumberFormatter;

    function user () {
        return auth()->user();
    }

    function licensed () {
        return Licensor::hasLicense();
    }

	function currentRoute(){
		return Route::currentRouteName();
	}

	function filterFrom($date){
    	return $date->startOfDay();
	}

	function filterTo($date){
	   return $date->endOfDay();
	}

	function settings ($key, $fallback = null) {
		$settings = App\Setting::orderBy('id', 'asc')->first();
		return $settings ? $settings->{$key} : $fallback;
	}

	function carbonDate($date, $format){
		$dtobj = Carbon\Carbon::parse($date);
		if($format == 'y-m-d'){
			return $dtformat = $dtobj->format('F jS, Y');
		}
		if($format == 'h-i-s'){
			return $dtformat = $dtobj->format('g:i A');
		}
		if($format == 'time'){
			return $dtformat = $dtobj->format('h:i A');
		}
		else{
			return $dtformat = $dtobj->format('F jS Y, g:i A');
		}
	}

	function bangla_digit($value){
    	$bn_digits = array('০','১','২','৩','৪','৫','৬','৭','৮','৯');
    	$locale = app()->getLocale();
    	$en_digits = array('0','1','2','3','4','5','6','7','8','9');
    	$hindi_digits = array('०','१','२','३','४','५','६','७','८','९');

    	if ($locale === 'bn') {
    		return str_replace(range(0, 9),$bn_digits, $value);
    	}
    	elseif($locale === 'hindy') {
    		return str_replace(range(0, 9),$hindi_digits, $value);
    	}

    	return str_replace(range(0, 9),$en_digits, $value);
	}

	function ref($num){
		switch ($num) {
		    case $num < 10:
		        return "000".$num;
		        break;
		    case $num >= 10 && $num < 100:
		        return "00".$num;
		        break;
		    case $num >+ 10 && $num >= 100 && $num < 1000:
		        return "0".$num;
		        break;
		    default:
		        return $num;;
		}
	}

	function todayProfit () {
		$today = Carbon\Carbon::now()->format('Y-m-d');
        $today_starts = Carbon\Carbon::createFromFormat('Y-m-d',$today)->startOfDay();
        $today_ends = Carbon\Carbon::createFromFormat('Y-m-d',$today)->endOfDay();

        //calculation of total profit today
        $todaysSellTransaction = App\Transaction::whereBetween('created_at',[$today_starts,$today_ends])->where('transaction_type', 'sell');

        $todaysProfit = $todaysSellTransaction->sum('total') - $todaysSellTransaction->sum('total_cost_price');
        
        return $todaysProfit;
	}


	function cashStatus () {
		$now = Carbon\Carbon::now()->format('Y-m-d');
        //check if cash open today
        $status = App\CashRegister::where('date', $now)->count();
        return $status;
	}


	function cashNow () {
		$today = Carbon\Carbon::now()->format('Y-m-d');
        $today_starts = Carbon\Carbon::createFromFormat('Y-m-d',$today)->startOfDay();
        $today_ends = Carbon\Carbon::createFromFormat('Y-m-d',$today)->endOfDay();

        $cash_in_hands = App\CashRegister::where('date', $today)->sum('cash_in_hands');
        $cash_received = App\Payment::whereBetween('created_at',[$today_starts,$today_ends])->where('type', 'credit')->where('method', 'cash')->sum('amount');
        $cash_given = App\Payment::whereBetween('created_at',[$today_starts,$today_ends])->where('type', '!=','credit')->where('method', 'cash')->sum('amount');
        $expense = App\Expense::whereBetween('created_at',[$today_starts,$today_ends])->sum('amount');

        $cash_now =  ($cash_in_hands + $cash_received) - ($cash_given + $expense);

        return $cash_now;
	}

	function rtlLocale () {
		return app()->getLocale() === 'ar';
	}


	function twoPlaceDecimal($value){
		return number_format((float)$value, 2, '.', '');
	}


	function numberFormatter($value) {
		$number = new NumberFormatter("en", NumberFormatter::SPELLOUT);
        return title_case($number->format($value));
	}

	function colSpanNumber () {
		$col = (settings('product_tax') == 1) ? 4 : 3;
		return $col; 
	}

	function sellDetailsColSpanNumber () {
		$col = (settings('product_tax') == 1) ? 5 : 4;
		return $col; 
	}

	function orderTax () {
		if(settings('invoice_tax') == 1){
			$tax_rate = settings('invoice_tax_rate');
			$tax_type = (settings('invoice_tax_type') == 1) ? "%" : " ";
		}else{
			$tax_rate = "0";
			$tax_type = "%";
		}
		
		return $tax_rate.$tax_type;
	}
