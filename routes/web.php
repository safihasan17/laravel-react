<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\Site\HomeController;
use App\Http\Controllers\Usercontroller;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products-details/{id}', [HomeController::class, 'details'])->name('products.details');
Route::get('/cart', [HomeController::class, 'cart'])->name('cart');
Route::resource('orders', OrderController::class);


Route::get('/dashboard', function () {
    return view('admin.pages.dashboard');
})->middleware(['auth', 'verified', 'role_id:1,2,3'])->name('dashboard');



Route::middleware('auth')->group(function () {
    Route::resource('users', Usercontroller::class);
    Route::resource('roles', RoleController::class);
    
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware('auth','role_id:1,2,3' )->group(function () {

    Route::resource('products', ProductController::class);

});




require __DIR__.'/auth.php';
require __DIR__.'/payment.php';


