<?php

use App\Exports\OrdersExport;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CallController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SellerBrandController;
use App\Http\Controllers\SellerCategoryController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\SellerPaymentController;
use App\Http\Controllers\SellerProductController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\StockController;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;



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

        Route::get('/admin/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('/admin/dashboard/chart-data', [App\Http\Controllers\Admin\DashboardController::class, 'getChartData'])->name('admin.dashboard.chart-data');
        Route::get('/', [PageController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/create',[AdminController::class,'create'])->name('admin.create');
        Route::post('/',[AdminController::class,'store'])->name('admin.store');

        Route::prefix('product')->group(function () {
            Route::get('/list', [ProductController::class, 'index'])->name('product.index');
            Route::get('/', [ProductController::class, 'create'])->name('product.create');
            Route::post('/', [ProductController::class, 'store'])->name('product.store');
            Route::get('/{id}', [ProductController::class, 'delete'])->name('product.delete');
             Route::get('/edit/{id}', [ProductController::class, 'edit'])->name('product.edit');
        });

        Route::prefix('brand')->group(function () {
            Route::get('/list', [BrandController::class, 'index'])->name('brand.index');
            Route::get('/', [BrandController::class, 'create'])->name('brand.create');
            Route::post('/', [BrandController::class, 'store'])->name('brand.store');
            Route::get('/{id}', [BrandController::class, 'delete'])->name('brand.delete');
        });

        Route::prefix('category')->group(function () {
            Route::get('/list', [CategoryController::class, 'index'])->name('category.index');
            Route::get('/', [CategoryController::class, 'create'])->name('category.create');
            Route::post('/', [CategoryController::class, 'store'])->name('category.store');
            Route::get('/{id}', [CategoryController::class, 'delete'])->name('category.delete');
        });

        Route::prefix('seller')->group(function () {
            Route::get('/list', [SellerController::class, 'index'])->name('seller.list');
            Route::get('/', [SellerController::class, 'create'])->name('seller.create');
            Route::post('/',[SellerController::class,'store'])->name('admin.seller.store');
            Route::get('/delete/{id}', [SellerController::class, 'delete'])->name('seller.delete');
            Route::get('/{id}',[SellerController::class,'edit'])->name('admin.seller.edit');
            Route::post('/update',[SellerController::class,'update'])->name('admin.seller.update');


        });
        Route::prefix('order')->group(function () {
            Route::get('new-order', [SellerController::class, 'new_order'])->name('admin.new-order');
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

          Route::prefix('employee')->group(function () {
            Route::get('/',[EmployeeController::class,'index'])->name('employee.list');
            Route::get('/create',[EmployeeController::class,'create'])->name('employee.create');
            Route::post('/',[EmployeeController::class,'store'])->name('employee.store');
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
             Route::post('/', [OrderController::class, 'store'])->name('seller.order.store');
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
         Route::prefix('call')->group(function (){
            Route::get('/',[CallController::class,'index'])->name('call.index');
            Route::get('/view/{id}',[CallController::class,'call_conform_form'])->name('call.view');
            Route::post('/',[CallController::class,'store'])->name('call.store');
            Route::get('/conform_call_list',[CallController::class,'conform_call_list'])->name('call.conform');
             Route::get('/reject_call_list',[CallController::class,'reject_call_list'])->name('call.reject');
             Route::get('/not_answer_call_list',[CallController::class,'not_answer_call_list'])->name('call.not_answer_call_list');
                Route::get('/other_call_list',[CallController::class,'other_call_list'])->name('call.other_call_list');




             Route::get('/export-orders', function () {
                 $seller = Auth::user();

                 // 1. Get the orders where download_status = 0
                 $orders = Order::where('seller_no', $seller->code)
                     ->where('status', 'Conform')
                     ->where('download_status', 0)
                     ->get();

                 if ($orders->isEmpty()) {
                     return back()->with('error', 'No orders to export.');
                 }

                 // 2. Update download_status to 1
                 $orderIds = $orders->pluck('id');
                 Order::whereIn('id', $orderIds)->update(['download_status' => 1]);

                 // 3. Export the orders
                 $date = Carbon::now()->format('Y-m-d');
                 $fileName = 'conform_orders_' . $date . '.xlsx';

                 return Excel::download(new OrdersExport($orders), $fileName);
             })->name('orders.export');

        });
        Route::prefix('product')->group(function (){
            Route::get('/list', [SellerProductController::class, 'index'])->name('seller.product.index');
            Route::get('/', [SellerProductController::class, 'create'])->name('seller.product.create');
            Route::post('/', [SellerProductController::class, 'store'])->name('seller.product.store');
            Route::get('/{id}', [SellerProductController::class, 'delete'])->name('seller.product.delete');
            Route::get('/edit/{id}', [SellerProductController::class, 'edit'])->name('seller.product.edit');
        });

        Route::prefix('brand')->group(function (){
            Route::get('/list', [SellerBrandController::class, 'seller_index'])->name('seller.brand.index');
            Route::get('/', [SellerBrandController::class, 'seller_create'])->name('seller.brand.create');
            Route::post('/', [SellerBrandController::class, 'seller_store'])->name('seller.brand.store');
            Route::get('/{id}', [SellerBrandController::class, 'seller_delete'])->name('seller.brand.delete');
        });

        Route::prefix('category')->group(function () {
            Route::get('/list', [SellerCategoryController::class, 'seller_index'])->name('seller.category.index');
            Route::get('/', [SellerCategoryController::class, 'seller_create'])->name('seller.category.create');
            Route::post('/', [SellerCategoryController::class, 'seller_store'])->name('seller.category.store');
            Route::get('/{id}', [SellerCategoryController::class, 'seller_delete'])->name('seller.category.delete');
        });
    });
});

Route::prefix('employee')->group(function(){
    Route::middleware('auth:employee')->group(function(){
    Route::get('/',[EmployeeController::class,'dashboard'])->name('employee.dashboard');
     Route::get('/print/{id}',[EmployeeController::class,'delivery_label_print'])->name('employee.print-out');
        Route::get('/{id}', [EmployeeController::class, 'view_order'])->name('employee.order_view');
    });

});

