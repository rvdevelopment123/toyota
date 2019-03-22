<?php

namespace App\Http\Controllers;

use URL;
use File;
use Session;
use App\Role;
use App\User;
use App\Warehouse;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Exceptions\ValidationException;

class UserController extends Controller
{
    private $searchParams = ['name', 'email'];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndex(Request $request)
    {
        if (auth()->user()->can('user.manage')) {
            $users = User::orderBy('first_name', 'asc');
            if ($request->get('name')) {
                $users->where(function($q) use($request) {
                    $q->where('first_name', 'LIKE', '%' . $request->get('name') . '%');
                    $q->orWhere('last_name', 'LIKE', '%' . $request->get('name') . '%');
                });
            }
            if ($request->get('email')) {
                $users->where(function($q) use($request) {
                    $q->where('email', 'LIKE', '%' . $request->get('email') . '%');
                });
            }
        } 
        else{
            $users = User::orderBy('first_name', 'asc')->whereId(auth()->user()->id);
        }
        return view('users.index')->withUsers($users->paginate(20));
    }


    public function postIndex(Request $request) {
        $params = array_filter($request->only($this->searchParams));
        return redirect()->action('UserController@getIndex', $params);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getNewUser () {
        $user = new User;
        if (auth()->user()->can('admins.create')) {
            $roles = Role::all();
        } else {
            $roles = Role::where('name', '!=', 'Super User')->get();
        }

        $warehouses = Warehouse::all();
        return view('users.form', compact('roles', 'user', 'warehouses'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postUser(UserRequest $request, User $user)
    {
        $exists = $user->exists();

        $user->first_name = $request->get('first_name');
        $user->last_name = $request->get('last_name');
        $user->email = $request->get('email');
        if ($request->get('password')) {
            $user->password = $request->get('password');
        }
        $user->address = $request->get('address');
        $user->phone = $request->get('phone');
        $user->national_id = $request->get('national_id');
        $user->sponsor_name = $request->get('sponsor_name');
        $user->warehouse_id = $request->get('warehouse_id');

        if($request->hasFile('image')){
            $file = $request->file('image');
            $file_extension = $file->getClientOriginalExtension();
            $random_name = str_random(12);
            $destination_path = public_path().'/uploads/profiles/';
            $filename = $random_name.'.'.$file_extension;
            $request->file('image')->move($destination_path,$filename);

            $user->image = $filename;
        }

        $user->save();

        if ($request->get('role')) {
            $user->roles()->sync($request->get('role'));
        }

        $message = trans('core.changes_saved');
        return redirect()->route('user.index')->withMessage($message);

    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function postProfile(Request $request)
    {
        $user_id = $request->get('user_id');
        $user = User::find($user_id);

        $user->first_name = empty($request->get('first_name')) ? $user->first_name : $request->get('first_name');
        $user->last_name = empty($request->get('last_name')) ? $user->last_name : $request->get('last_name');
        $user->email = empty($request->get('name')) ? $user->email  : $request->get('email');
        /*$user->password = $request->get('password');*/
        $user->address = $request->get('address');
        $user->phone = $request->get('phone');

        $user->save();

        $message = trans('core.changes_saved');
        return redirect()->route('user.profile')->withMessage($message);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function viewProfile()
    {
        $user = \Auth::user();
        return view('users.profile')->withUser($user);
    }


    public function getEditUser(User $user){ 
        $roles = Role::where('name', '!=', 'Super User')->get();
        $warehouses = Warehouse::all();
        return view('users.form', compact('roles', 'user', 'warehouses'));
    }

    /**
     * Lock current user session
     *
     */
    public function lock()
    {
        session(['lockedOutUser' => auth()->user()->id]);
        session(['lockedRoute' => url()->previous()]);
        \Auth::logout();
        return redirect()->route('locked');
    }


    /**
     * Lock current user session
     *
     */
    public function unlock(Request $request)
    {
        $intended = session('lockedRoute');
        $email = $request->get('email');
        $password = $request->get('password');

        if (\Auth::attempt(['email' => $email, 'password' => $password])) {
            // Authentication passed...
            return redirect()->intended($intended);
        }
        $message = trans('core.wrong_password');
        return redirect()->back()->withMessage($message);
    }

    public function locked () {
        $user = User::find(session('lockedOutUser'));
        if (!$user) {
            return redirect()->to('/login');
        }
        return view('users.locked')->withUser($user);
    }

    public function logout () {
        \Auth::logout();
        return redirect()->to('/');
    }


    public function changePassword(Request $request) {
        $user_id = $request->get('user_id');
        $user = User::find($user_id);
        if($request->get('password') == $request->get('confirm_password')){
            $user->password = $request->get('password');
            $user->save();

            $message = trans('core.changes_saved');
            return redirect()->back()->withMessage($message);       
           
        }
        
         $message = trans('core.oops');
         return redirect()->back()->withMessage($message);
          
    }

    public function verifyOldPassword (Request $request) {

        $oldPassword = auth()->user()->password;
        $password = trim($request->get('password'));

        if (Hash::check($password, $oldPassword)) {
            return ['value' => true, 'code' => 200];
        } 
        
        throw new ValidationException('Passwords does not match');

    }

    public function postStatus (Request $request) {
        $user = User::findorFail($request->get('user_id'));
        $status = ($user->inactive == 1) ? 0 : 1;

        $user->inactive = $status;
        $user->save();

        $message = trans('core.changes_saved');
        return redirect()->route('user.index')->withSuccess($message);

    }
}
