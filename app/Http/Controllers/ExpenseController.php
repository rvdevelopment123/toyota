<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Expense;
use App\CashRegister;
use Carbon\Carbon;

class ExpenseController extends Controller
{
    private $searchParams = ['purpose', 'from', 'to'];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndex(Request $request)
    {
        $expenses = Expense::orderBy('id', 'desc');
        if($request->get('purpose')) {
            $expenses->where('purpose', 'LIKE', '%' . $request->get('purpose') . '%');
        }

        $from = $request->get('from');
        $to = $request->get('to')?:date('Y-m-d');
        $to = Carbon::createFromFormat('Y-m-d',$to);
        $to = filterTo($to);

        if($request->get('from') || $request->get('to')) {
            if(!is_null($from)){
                $from = Carbon::createFromFormat('Y-m-d',$from);
                $from = filterFrom($from);
                $expenses->whereBetween('created_at',[$from,$to]);
            }else{
                $expenses->where('created_at','<=',$to);
            }
        }

        return view('expenses.index')->withExpenses($expenses->paginate(20));
    }

     /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function postSearch(Request $request) {
        $params = array_filter($request->only($this->searchParams));
        return redirect()->action('ExpenseController@getIndex', $params);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postIndex(Request $request)
    {
        $this->validate($request, [
            'purpose' => 'required',
            'amount' => 'required|numeric',
        ]);
        
        $expense = new Expense;
            $expense->purpose = $request->get('purpose');
            $expense->amount = $request->get('amount');
        $expense->save();

        $message = trans('core.saved');
        return redirect()->back()->withSuccess($message);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function cashRegister(Request $request)
    {
        $cash_register = new CashRegister;
            $cash_register->cash_in_hands = $request->get('cash_in_hands');
            $cash_register->date = Carbon::now()->format('Y-m-d');
        $cash_register->save();

        return redirect()->back();

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function editExpense(Request $request)
    {
        $this->validate($request, [
            'purpose' => 'required',
            'amount' => 'required|numeric',
        ]);

        $expense = Expense::find($request->get('id'));
            $expense->purpose = $request->get('purpose');
            $expense->amount = $request->get('amount');
        $expense->save();

        $message = trans('core.changes_saved');
        return redirect()->back()->withSuccess($message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function deleteExpense(Request $request)
    {
        $expense = Expense::find($request->get('id'));
        $expense->delete();
        return redirect()->route('expense.index');
    }
}
