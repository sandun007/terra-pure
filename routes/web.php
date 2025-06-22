<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\SellerPaymentController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\StockController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [PageController::class, 'login'])->name('login');
Route::post('/',[LoginController::class,'loginCheck'])->name('login.check');

Route::middleware('auth:admin')->group(function(){
    Route::prefix('admin')->group(function () {
        Route::post('/logout', [LoginController::class, 'logout'])->name('admin.logout');
        Route::get('/', [PageController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/create',[AdminController::class,'create'])->name('admin.create');
        Route::post('/',[AdminController::class,'store'])->name('admin.store');

        Route::prefix('product')->group(function () {
            Route::get('/list', [ProductController::class, 'index'])->name('product.index');
            Route::get('/', [ProductController::class, 'create'])->name('product.create');
        });

        Route::prefix('brand')->group(function () {
            Route::get('/list', [BrandController::class, 'index'])->name('brand.index');
            Route::get('/', [BrandController::class, 'create'])->name('brand.create');
        });

        Route::prefix('category')->group(function () {
            Route::get('/list', [CategoryController::class, 'index'])->name('category.index');
            Route::get('/', [CategoryController::class, 'create'])->name('category.create');
        });

        Route::prefix('seller')->group(function () {
            Route::get('/list', [SellerController::class, 'index'])->name('seller.list');
            Route::get('/', [SellerController::class, 'create'])->name('seller.create');
            Route::post('/',[SellerController::class,'store'])->name('admin.seller.store');
        });
        Route::prefix('order')->group(function () {
            Route::get('/complete-order', [OrderController::class, 'completeOrder'])->name('complete-order');
            Route::get('/return-order', [OrderController::class, 'returnOrder'])->name('return-order');
            Route::get('/rescheduling-order', [OrderController::class, 'reschedulingOrder'])->name('rescheduling-order');
            Route::get('/pending-order', [OrderController::class, 'pendingOrder'])->name('pending-order');
        });
        Route::prefix('stock')->group(function () {
            Route::get('/low-stock', [StockController::class, 'low_stock'])->name('low-stock');
        });
        Route::prefix('shop')->group(function () {
            Route::get('/',[ShopController::class,'index'])->name('shop.index');
            Route::get('product/{id}', [ProductController::class, 'product_view'])->name('product.product_view');

        });

    });

});

Route::middleware('auth:seller')->group(function(){
    Route::prefix('seller')->group(function () {
        Route::post('/logout', [LoginController::class, 'logout'])->name('seller.logout');
        Route::get('/', [SellerController::class, 'dashboard'])->name('seller.dashboard');

        Route::prefix('shop')->group(function () {
            Route::get('/',[SellerController::class,'shop'])->name('seller.shop');
            Route::get('product/{id}', [ProductController::class, 'product_view'])->name('seller.product.product_view');

        });

        Route::prefix('order')->group(function () {
            Route::get('new-order', [SellerController::class, 'new_order'])->name('seller.new-order');
            Route::get('/complete-order', [SellerController::class, 'completeOrder'])->name('seller.complete-order');
            Route::get('/return-order', [SellerController::class, 'returnOrder'])->name('seller.return-order');
            Route::get('/rescheduling-order', [SellerController::class, 'reschedulingOrder'])->name('seller.rescheduling-order');
            Route::get('/pending-order', [SellerController::class, 'pendingOrder'])->name('seller.pending-order');
        });

        Route::prefix('product')->group(function () {
            Route::get('/',[SellerController::class,'seller_product'])->name('seller.product.index');
        });

        Route::prefix('payment')->group(function () {
            Route::get('/my-payment',[SellerPaymentController::class,'my_payment'])->name('seller.my_payment');
            Route::get('/withdraw',[SellerPaymentController::class,'withdraw'])->name('seller.withdraw');
            Route::get('/history',[SellerPaymentController::class,'payment_history'])->name('seller.payment_history');
        });
    });
});
