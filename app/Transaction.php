<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use SoftDeletes;

    public function client(){
    	return $this->belongsTo('App\Client');
    }

    public function purchases() {
    	return $this->hasMany('App\Purchase', 'reference_no', 'reference_no');
    }

    public function sells() {
    	return $this->hasMany('App\Sell', 'reference_no', 'reference_no');
    }

    public function payments() {
        return $this->hasMany('App\Payment', 'reference_no', 'reference_no');
    }

    public function returnSales() {
        return $this->hasMany('App\ReturnTransaction', 'sells_reference_no', 'reference_no');
    }

    public function warehouse () {
        return $this->belongsTo('App\Warehouse');
    }

    protected static function boot () {
        parent::boot();
        self::saving(function ($model) {
            $model->warehouse_id = auth()->user()->warehouse_id;
        });
    }
}
