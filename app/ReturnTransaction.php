<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReturnTransaction extends Model
{
	use SoftDeletes;
	
    public function sells(){
    	return $this->belongsTo('App\Sell', 'sells_id');
    }

    public function user(){
    	return $this->belongsTo('App\User', 'returned_by');
    }
}
