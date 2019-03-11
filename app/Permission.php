<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{

    public $timestamps = false;

    protected $fillable = ['type', 'name'];

    /**
     * @param $permission
     * @return array
     */
    private static function getPermissionParts($permission)
    {
        $parts = explode('.', $permission);

        $params = [
            'type' => isset($parts[0]) ? $parts[0] : null,
            'name' => isset($parts[1]) ? $parts[1] : null
        ];

        return $params;
    }

    public function roles()
    {
        return $this->belongsToMany('App\Role', 'role_permission');
    }

    /**
     * Creates a Permission passed in the form `type.name`
     *
     * @param $permission
     * @return \App\Permission
     */
    public static function createPermission($permission)
    {
        $params = self::getPermissionParts($permission);

        return static::updateOrCreate($params);
    }

    /**
     * Finds a Permission passed in the form `type.name`
     *
     * @param $permission
     * @return \App\Permission | null
     */
    public static function getPermission($permission)
    {
        $params = self::getPermissionParts($permission);

        return static::where($params)->first();
    }
}
