<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\Usercontroller;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('admin.layouts.single-master');
});

Route::get('/dashboard', function () {
    return view('admin.pages.dashboard');
})->name('dashboard');

Route::get('/login', function () {
    return view('admin.pages.auth.login');
});

// Route::get('/users', [Usercontroller::class, 'index'])->name('users.index');
// Route::get('/users/create', [Usercontroller::class, 'create'])->name('users.create');
// Route::get('/users/{id}/edit', [Usercontroller::class, 'edit'])->name('users.edit');
// Route::get('/users/{id}', [Usercontroller::class, 'show'])->name('users.show');
// Route::post('/users', [Usercontroller::class, 'store'])->name('users.store');
// Route::put('/users/{id}', [Usercontroller::class, 'update'])->name('users.update');
// Route::delete('/users/{id}', [Usercontroller::class, 'destroy'])->name('users.destroy');


Route::resource('users', Usercontroller::class);

Route::resource('products', ProductController::class);
