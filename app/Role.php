<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * Timestamps flag (false will disable timestamps)
     *
     * @var boolean
     */
    public $timestamps = false;

    public function users()
    {
        return $this->belongsToMany('App\User', 'role_user');
    }

    public function permissions()
    {
        return $this->belongsToMany('App\Permission', 'role_permission');
    }

    public function can($permission) {
        $permission = explode('.', $permission, 2);
        
        return !$this->permissions->filter(function($item) use($permission) {
            if($item->type == $permission[0] && $item->name == '*') { return true; }
            if (!isset($permission[1])) {
                return false;
            }
            if($item->type == $permission[0] && $item->name == $permission[1]) { return true; }
            return false;
        })->isEmpty();
    }
}
