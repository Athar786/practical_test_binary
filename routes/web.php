<?php

use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function() {
    Route::resource('customers',CustomerController::class)->except('show');

    Route::get('orders',[OrderController::class,'index'])->name('orders.index');
    Route::get('orders/create',[OrderController::class,'create'])->name('orders.create');
    Route::post('orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
});