<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Role;
use App\Permission;
use App\RolePermission;
use Illuminate\Support\Facades\Auth;



class RolePermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndex()
    {
        $roles = Role::all();
        return view('acl.role.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getNewRole()
    {
        $role = new Role;
        return view('acl.role.form', compact('role'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postRole(Role $role, Request $request)
    {
        $role->name = $request->get('role_name');
        $role->save();

        $message = trans('core.changes_saved');
        return redirect()->back()->withSuccess($message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function setRolePermissions(Role $role){

        $permissions = Permission::all();
        $rolePermissionNameLists = [];
        
        if($role->permissions->count() != 0){
            $rolePermissions =  $role->permissions;
            foreach($rolePermissions as $rolePermission){
                $rolePermissionNameLists[] =  ucwords($rolePermission->type).' '.ucwords($rolePermission->name);
            }
        }
       return view('acl.role-permissions.form', compact('role', 'permissions', 'rolePermissionNameLists'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function postRolePermissions(Request $request)
    {
        $role = Role::find($request->get('role_id'));

        $permissions = Permission::all();
        $newPermissions = [];
        foreach ($permissions as $permission) {
            if(!empty($request->get('permissions'.$permission->id))){
                $newPermissions[] = $permission->id; 
            }
            $role->permissions()->sync($newPermissions);
        }
        
        $message = trans('core.changes_saved');
        return redirect()->route('role.index')->withSuccess($message);
    }
}
