<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $roles = DB::table('roles')->get();
        // $roles = DB::table('roles')->paginate(2);
        // $roles = DB::table('roles')->where('name', 'admin')->first();
        // $roles = DB::table('roles')->offset(2)->limit(2)->get();
        // $roles = DB::table('products')->select('name', 'price')->get();
        // $roles = DB::table('products as p')
        //        ->join('categories as c', 'p.category_id', '=', 'c.id')     
        //        ->join('brands as b', 'p.brand_id', '=', 'b.id') 
        //        ->select('p.name', 'c.name as categoty', 'b.name as brand', 'p.price')
        //        ->get();

        // $roles = DB::table('roles as r')    
        //        ->join('users as u', 'r.id', '=', 'u.role_id') 
        //        ->select('r.name as role', 'u.name as user')
               
        //        ->get();
        // dd($roles);

         $roles = DB::table('roles')->orderBy('name', 'asc')->get();
        return view('admin.pages.role.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pages.role.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required| unique:roles,name|min:2|max:255'
        ]);
        $role = DB::table('roles')
                  ->insert([
                       'name' =>$request->name
                   ]);

        if($role){
            return redirect()->route('roles.index')->with('success', 'Role created successfully');
        }else{
            return redirect()->route('roles.index')->with('error', 'Role not created');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        return view('admin.pages.role.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
         $request->validate([
            'name' => 'required| unique:roles,name|min:2|max:255'
        ]);
        $role = DB::table('roles')
                  ->where('id', $role->id)
                  ->update([
                       'name' =>$request->name
                   ]);

        if($role){
            return redirect()->route('roles.index')->with('success', 'Role updated successfully');
        }else{
            return redirect()->route('roles.index')->with('error', 'Role not updated');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        
        $role = DB::table('roles')
                  ->where('id', $role->id)
                  ->delete();

        if($role){
            return redirect()->route('roles.index')->with('success', 'Role delated successfully');
        }else{
            return redirect()->route('roles.index')->with('error', 'Role not delated');
        }
    }
}
