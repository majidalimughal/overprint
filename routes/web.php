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

Auth::routes();
Route::middleware(['auth.shopify'])->group(function(){
    Route::get('/', [\App\Http\Controllers\OrderController::class,'dashboard'])->name('home');

    Route::prefix('store')->group(function(){
        Route::get('/orders/synchronize',[\App\Http\Controllers\OrderController::class,'SynchronizeOrders'])->name('sync.Orders');
        Route::get('/orders',[\App\Http\Controllers\OrderController::class,'orders'])->name('admin.orders');
        Route::get('/orders/filter',[\App\Http\Controllers\OrderController::class,'filter_orders'])->name('admin.orders.filter');
        Route::get('/orders/{id}/detail',[\App\Http\Controllers\OrderController::class,'orderDetail'])->name('admin.order.detail');
        Route::get('/plans',[\App\Http\Controllers\OrderController::class,'plans'])->name('app.plans');
        Route::get('/billing/methods',[\App\Http\Controllers\SettingController::class,'billingMethods'])->name('billing.methods');


        Route::get('/shopify/synchronize/products',[\App\Http\Controllers\ProductController::class,'synchronzeProducts'])->name('shopify.synchronize.products');
        Route::get('shopify/products',[\App\Http\Controllers\ProductController::class,'index'])->name('shopify.products');

        Route::get('/app/products',[\App\Http\Controllers\ProductController::class,'availableProducts'])->name('available.products');

        Route::get('/shopify/products/{id}/details',[\App\Http\Controllers\ProductController::class,'productDetail'])->name('shopify.product.detail');
    });

});


Route::middleware('auth')->prefix('admin')->group(function (){
    Route::get('/',[\App\Http\Controllers\AdminController::class,'index'])->name('admin');
    Route::get('/orders',[\App\Http\Controllers\AdminController::class,'orders'])->name('admin.orders.index');

});
Route::get('/orders/{id}/fulfillment',[\App\Http\Controllers\OrderController::class,'orderFulfillment'])->name('admin.order.fulfillment');
Route::post('/order/{id}/add/tracking', [\App\Http\Controllers\OrderController::class,'addOrderTracking'])->name('admin.order.fulfillment.tracking');
Route::get('/order/{id}/cancel/fulfillment/{fulfillment_id}',  [\App\Http\Controllers\OrderController::class,'cancelOrderFulfillment'])->name('admin.order.fulfillment.cancel');
Route::post('/order/{id}/fulfillment/process', [\App\Http\Controllers\OrderController::class,'processOrderFulfillment'])->name('admin.order.fulfillment.process');




//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
