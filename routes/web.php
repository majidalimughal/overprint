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

Route::middleware(['auth.shopify'])->group(function(){
    Route::get('/', [\App\Http\Controllers\OrderController::class,'dashboard'])->name('home');
    Route::get('/orders/synchronize',[\App\Http\Controllers\OrderController::class,'SynchronizeOrders'])->name('sync.Orders');
    Route::get('/orders',[\App\Http\Controllers\OrderController::class,'orders'])->name('admin.orders');
    Route::get('/orders/filter',[\App\Http\Controllers\OrderController::class,'filter_orders'])->name('admin.orders.filter');
    Route::get('/orders/{id}/detail',[\App\Http\Controllers\OrderController::class,'orderDetail'])->name('admin.order.detail');
    Route::get('/plans',[\App\Http\Controllers\OrderController::class,'plans'])->name('app.plans');
    Route::get('/billing/methods',[\App\Http\Controllers\SettingController::class,'billingMethods'])->name('billing.methods');
});


