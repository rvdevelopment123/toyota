<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subcategory extends Model
{
    use SoftDeletes;
    
    public function category() {
    	return $this->belongsTo('App\Category');
    }

    public function products(){
    	return $this->hasMany('App\Product');
    }

    public function sells () {
    	return $this->hasManyThrough('App\Sell', 'App\Product');
    }
}
