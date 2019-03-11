<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sell extends Model
{
    use SoftDeletes;
    
    public function product(){
    	return $this->belongsTo('App\Product');
    }

    public function client()
    {
    	return $this->belongsTo('App\Client');
    }

    protected static function boot () {
        parent::boot();
        self::saving(function ($model) {
            $model->warehouse_id = auth()->user()->warehouse_id;
        });
    }
}
