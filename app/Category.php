<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;
    
    public function subcategories(){
    	return $this->hasMany('App\Subcategory');
    }

    public function products () {
    	return $this->hasManyThrough('App\Product', 'App\Subcategory');
    }

    public function product () {
    	return $this->hasMany('App\Product');
    }
}
