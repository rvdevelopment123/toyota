<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use SoftDeletes;

    protected $fillable = ['id'];
    /**
     * Get full name attribute
     * */

    public function getNameAttribute(){
        return $this->first_name.' '.$this->last_name;
    }

    public function purchases(){
        return $this->hasMany('App\Purchase');
    }

    public function sells(){
        return $this->hasMany('App\Sell');
    }

    public function transactions(){
        return $this->hasMany('App\Transaction');
    }

    public function payments(){
        return $this->hasMany('App\Payment');
    }

    public function returns(){
        return $this->hasMany('App\ReturnTransaction');
    }
}

