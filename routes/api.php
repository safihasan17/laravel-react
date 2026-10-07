<?php

use App\Http\Controllers\RoleController;
use App\Http\Controllers\Usercontroller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

 Route::apiResource('users', Usercontroller::class);
 Route::apiResource('roles', RoleController::class);
