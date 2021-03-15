<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['auth.shopify','billable'])->group(function(){
    Route::get('/', [\App\Http\Controllers\OrderController::class,'dashboard'])->name('home');
    Route::get('/orders/synchronize','OrderController@SynchronizeOrders')->name('sync.Orders');
    Route::get('/orders',[\App\Http\Controllers\OrderController::class,'orders'])->name('admin.orders');
    Route::get('/orders/filter',[\App\Http\Controllers\OrderController::class,'filter_orders'])->name('admin.orders.filter');
    Route::get('/orders/{id}/detail',[\App\Http\Controllers\OrderController::class,'filter_orders'])->name('admin.order.detail');
});


