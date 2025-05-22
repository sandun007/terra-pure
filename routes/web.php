<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
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

Route::get('/',[PageController::class,'login'])->name('login');
Route::prefix('admin')->group(function(){
    Route::get('/',[PageController::class,'dashboard'])->name('admin.dashboard');

    Route::prefix('product')->group(function(){
        Route::get('/',[ProductController::class,'create'])->name('product.create');
    });

    Route::prefix('brand')->group(function(){
        Route::get('/',[BrandController::class,'create'])->name('brand.create');
    });

    Route::prefix('category')->group(function(){
        Route::get('/',[CategoryController::class,'create'])->name('category.create');
    });
});
