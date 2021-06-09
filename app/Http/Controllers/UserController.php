<?php

namespace App\Http\Controllers;

use App\User;
use App\Role;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash;
use Gate;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the users
     *
     * @param  \App\User  $model
     * @return \Illuminate\View\View
     */
    public function index(User $model)
    {
        $users = User::paginate(25);

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $roles = Role::all();    
        return view('users.create',compact('roles'));
    }

    /**
     * Store a newly created user in storage
     *
     * @param  \App\Http\Requests\UserRequest  $request
     * @param  \App\User  $model
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(UserRequest $request)
    {
        $this->validate(request(), [
            'name' => 'required',
            'email'=> 'required|email',
            'password'=>'required',
            'username'=>'required',
            'employeeid'=>'required',
            'role'  => 'required'
        ]);
       
        $request->merge(['password' => Hash::make($request->get('password'))]);

        //User::create($request->all());
         $userId = User::insertGetId([
             'name' => $request->input('name'),
             'username' => $request->input('username'),
             'email' => $request->input('email'),
             'password' => bcrypt($request->input('password')),
             'hosp_id'  =>  '0000043',
             'avatar' => 'default-avatar.png',
         ]);

         $userId = Role::insertGetId([
            'name' => $request->input('name'),
            'username' => $request->input('username'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'hosp_id'  =>  '0000043',
            'avatar' => 'default-avatar.png',
        ]);
            

        return redirect()->route('users.index')->withStatus('User successfully created.');
    }

    /**
     * Show the form for editing the specified user
     *
     * @param  \App\User  $user
     * @return \Illuminate\View\View
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage
     *
     * @param  \App\Http\Requests\UserRequest  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UserRequest $request, User $user)
    {
        $hasPassword = $request->get('password');

        $request->merge(['password' => Hash::make($request->get('password'))]);

        $request->except([$hasPassword ? '' : 'password']);

        $user->update($request->all());

        return redirect()->route('users.index')->withStatus('User successfully updated.');
    }

    /**
     * Remove the specified user from storage
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User  $user)
    {
        $user->delete();

        return redirect()->route('users.index')->withStatus('User successfully deleted.');
    }
}
