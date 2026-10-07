<?php

namespace App\Http\Controllers;

use App\Mail\UpdateMail;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Fluent;

class Usercontroller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $users = User::all();
        // $users = User::orderBy('id', 'desc')->get();
        // $users = User::orderBy('id', 'asc')->offset(10)->limit(5)->get();
        // $users = User::orderBy('id', 'asc')->offset(10)->first();
        // $users = User::orderBy('id', 'asc')->where('role_id', 1)->get();
        // $users = User::orderBy('id', 'asc')->where('role_id', 1)->first();
        // $users = User::orderBy('id', 'asc')
        //   ->whereIN('role_id', [2,3])
        //    ->get();

        // $users = User::orderBy('id', 'asc')
        //   ->select('id', 'name', 'email', 'role_id')
        //    ->first();
        // dd($users);

        // if (Auth::user()->role_id == 4) {
        //     abort(403);
        //     exit;
        // }

        $users = User::join('roles as r', 'users.role_id', '=', 'r.id')
            ->orderBy('id', 'desc')
            ->select('users.id', 'users.name', 'users.email', 'r.name as role')
               ->get();
            // ->paginate(10);
        // dd($users);
        // return view('admin.pages.user.index', compact('users'));
        return response()->json([
            'users'  =>$users
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->role_id == 4) {
            abort(403);
            exit;
        }

        $roles = Role::all();
        $roles = Role::orderBy('name', 'asc')->get();
        return view('admin.pages.user.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());

        // if (Auth::user()->role_id == 4) {
        //     abort(403);
        //     exit;
        // }

        $request->validate([
            'name' => 'required|min:3|max:100',
            'email' => 'required|email|unique:users,email',
            'role_id' => 'required|exists:roles,id',
            'password' => 'required|min:3|max:15',
            'password_confirmation' => 'required|same:password',
        ]);

        // $user = User::create([
        //     'name'=>$request->name,
        //     'email'=>$request->email,
        //     'role_id'=>$request->role_id,
        //     'password'=>Hash::make($request->password),
        // ]);

        $user       = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role_id = $request->role_id;
        $user->password = Hash::make($request->password);

        // $user=false;

        // if($user){
        if ($user->save()) {
            // return redirect()
            //     ->route('users.index')
            //     ->with('success', 'user created successfully');

            return response()->json([
                'success' =>'user created successfully'
            ]);
        } else {
            // return redirect()
            //     ->route('users.create')
            //     ->with('error', 'user not created');

            return response()->json([
                'error' =>'user not  created. Try again later'
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // if (Auth::user()->role_id == 4 &&  Auth::user()->id != $id) {
        //     abort(403);
        // }
        $user = User::join('roles as r', 'users.role_id', '=', 'r.id')
            ->where('users.id', $id)
            ->select('users.id', 'users.name', 'users.email', 'r.name as role')
            ->first();
        // return view('admin.pages.user.show', compact('user'));
        if($user){
            return response()->json([
                   'success' => true,
                    'users'  =>$user
   
               ]);

        }else{
            return response()->json([
                   'error' => true,
                    'message'  => 'user not Found'
   
               ], 404);
        }


    }

    /**
     * Show the form for editing the specified resource.
     */

    public function edit(string $id)
    {
        if (Auth::user()->role_id == 4  &&  Auth::user()->id != $id) {
            abort(403);
        } else {
            $roles = Role::all();
            $user = User::find($id);
            // dd($user);
            return view('admin.pages.user.edit', compact('roles', 'user'));
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // if (Auth::user()->role_id == 4  &&  Auth::user()->id != $id) {
        //     abort(403);
        // }
        // dd($request->all());
        $request->validate([
            'name' => 'required|min:3|max:100',
            'email' => "required|email|unique:users,email, $id",
            'role_id' => 'required'
        ]);


        $user        = User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role_id = $request->role_id;
        $user->save();


        if ($user->save()) {

            $role = Role::find($request->role_id);
            $user = User::find($id);
            $userData = [
                'name' =>$user->name,
                'email' =>$user->email,
                'role' =>$role->name,
                'updated' =>$user->updated_at,
            ];

            // $userData = (object) $userData;  // core-php
            $userData = new Fluent($userData);  //laravel

            Mail::to($user->email)->send(new UpdateMail($userData));

            // return redirect()
            //     ->route('users.show', ['user' => $user->id])
            //     ->with('success', 'user updated successfully');
            return response()->json([
                'success' => true,
                'message' => 'user updated successFully'
            ]);
        } else {
            // return redirect()
            //     ->route('users.create')
            //     ->with('error', 'user not updated');

             return response()->json([
                'error' => true,
                'message' => 'user not  updated'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // dd($id);

        // if (Auth::user()->role_id != 1  && Auth::user()->role_id != 3) {
        //     abort(403);
        // } else {
        //     User::destroy($id);
        //     return redirect()
        //         ->route('users.index')
        //         ->with('success', 'user deleted successfully');
        // }

        $user = User::find($id);

        if($user){
           $user->delete();
           return response()->json([
              'success' =>true,
              'message' => 'user delated Successfully'
           ]);
        }else{
             return response()->json([
              'error' =>true,
              'message' => 'user not Found'
           ], 404);
        }
    }
}
