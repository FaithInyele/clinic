<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    /**
     * list all users of the system
     */
    public function listAll(){
        $title = 'iHospital | Users';
        $rightbar = 'user';
        $users = User::all();
        //dd($users);

        return view('users.view', compact('users', 'rightbar', 'title'));
    }

    /**
     * api call to get all users of the system
     *
     * @return mixed
     */
    public function allUsers(){
        $users = User::all();

        return Response::json($users);
    }

    /**
     * api call too either activate or deactivate a given user
     *
     * @param Request $request
     * @return mixed
     */
    public function activate(Request $request){
        $request['active'] = $request->active == 1 ? 0:1;
        DB::table('users')
            ->where('id', $request->id)
            ->update(['active'=>$request->active]);

        return Response::json($request->all());
    }

    /**
     * show registration form to add new user.
     */
    public function regForm(){
        $title = 'iHospital | Register New User';
        $rightbar = 'user';

        $total_users = count(User::all());
        $active_users = count(User::where('active', 1)->get());
        $inactive_users = count(User::where('active', 0)->get());

        return view('auth.register', compact('rightbar','title', 'total_users', 'active_users', 'inactive_users'));
    }

    /**
     * save a new user
     *
     * @param Request $request
     */
    public function store(Request $request){

        //validate all the inputs
        $this->validate($request, [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'id_no' => 'required|max:255',
            'gender' => 'required|max:255',
            'yob' => 'required|max:255',
            'role' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        $request['password'] = bcrypt($request->password);
        $request['active'] = 1;
        unset($request['password_confirmation']);
        //dd($request->all());

        //save user to db
        $user = new User($request->all());
        $user->save();

        //return success message to page
        return redirect()->action('RegisterController@regForm')
            ->with('status', $request->first_name.' Added to the System as a User')
            ->with('rightbar', 'user');
    }
}
