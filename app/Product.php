<?php

namespace App;

use DNS1D;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    
    public function category(){
    	return $this->belongsTo('App\Category');
    }

    public function subcategory(){
    	return $this->belongsTo('App\Subcategory');
    }

    public function tax(){
        return $this->belongsTo('App\Tax');
    }

    public function purchases() {
    	return $this->hasMany('App\Purchase');
    }

    public function getBarCodeAttribute()
    {
        return 'data:image/png;base64,' . DNS1D::getBarcodePNG($this->code, "c128A",1,33,array(1,1,1), true);
    }

    public function sells() {
    	return $this->hasMany('App\Sell');
    }
}
