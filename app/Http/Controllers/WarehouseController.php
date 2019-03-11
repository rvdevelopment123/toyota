<?php
namespace App\Http\Controllers;

use Input;
use App\Warehouse;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndex(Request $request)
    {
        $warehouses = Warehouse::orderBy('name', 'asc');
        return view('warehouses.index')->withWarehouses($warehouses->paginate(20));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getNewWarehouse()
    {
        $warehouse = new Warehouse;
        return view('warehouses.form')->withWarehouse($warehouse);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postWarehouse(Request $request, Warehouse $warehouse)
    {
    	$this->validate($request, [
            'name' => 'required|max:255',
        ]);

        $warehouse->name = $request->get('name');
        $warehouse->address = $request->get('address');
        $warehouse->phone = $request->get('phone');
        $warehouse->in_charge_name = $request->get('in_charge_name');
        $warehouse->save();

        $message = trans('core.changes_saved');
        return redirect()->route('warehouse.index')->withSuccess($message);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getEditWarehouse(Warehouse $warehouse)
    {
        return view('warehouses.form')->withWarehouse($warehouse);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteWarehouse(Warehouse $warehouse)
    {
        if(count($warehouse->transactions) ==  0){
            $warehouse->delete();
            $message = trans('core.deleted');
            return redirect()->back()->withMessage($message);
        }else{
            $message = trans('core.warehouse_has_transactions');
            return redirect()->back()->withMessage($message);
        }
    }
}
