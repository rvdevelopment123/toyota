<?php

namespace App\Http\Controllers;

use App\Tax;
use Illuminate\Http\Request;

class TaxController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndex()
    {
        $taxes = Tax::paginate(10);
        return view('taxes.index')->withTaxes($taxes);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postTax(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'type' => 'required',
            'rate' => 'required|numeric',
        ]);

        $tax = new Tax;
            $tax->name = $request->get('name');
            $tax->type = $request->get('type');
            $tax->rate = $request->get('rate');
        $tax->save();

        $message = trans('core.saved');
        return redirect()->back()->withSuccess($message);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editTax(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'type' => 'required',
            'rate' => 'required|numeric',
        ]);

        $tax = Tax::find($request->get('id'));
            $tax->name = $request->get('name');
            $tax->type = $request->get('type');
            $tax->rate = $request->get('rate');
        $tax->save();

        $message = trans('core.changes_saved');
        return redirect()->back()->withSuccess($message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteTax(Request $request)
    {
        $tax = Tax::find($request->get('id'));
        $tax->delete();

        $message = trans('core.deleted');
        return redirect()->route('tax.index')->withMessage($message);
    }
}
