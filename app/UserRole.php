<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    protected $table = 'role_user';
    
    public function users()
    {
        return $this->belongsToMany('App\User', 'id', 'user_id');
    }
    
    public function user()
    {
        return $this->belongsTo('App\User', 'id', 'user_id');
    }
    
    public function role()
    {
        return $this->hasOne('App\Role', 'id', 'role_id');
    }
}
