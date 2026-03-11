<?php

use App\Exports\OrdersExport;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Employee\EmployeeWholesalerOrderController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Seller\CallController;
use App\Http\Controllers\Seller\SellerBrandController;
use App\Http\Controllers\Seller\SellerCallController;
use App\Http\Controllers\Seller\SellerCategoryController;
use App\Http\Controllers\Seller\SellerOrderController;
use App\Http\Controllers\Seller\SellerPaymentController;
use App\Http\Controllers\Seller\SellerProductController;
use App\Http\Controllers\Seller\SellerShopController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\Wholesaler\WholesalerBrandController;
use App\Http\Controllers\Wholesaler\WholesalerCategoryController;
use App\Http\Controllers\Wholesaler\WholesalerOrderController;
use App\Http\Controllers\Wholesaler\WholesalerPaymentController;
use App\Http\Controllers\Wholesaler\WholesalerProductController;
use App\Http\Controllers\Employee\EmployeeDeliveryCallController;
use App\Http\Controllers\Wholesaler\WholesalerShopController;
use App\Http\Controllers\WholesalerController;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use Maatwebsite\Excel\Facades\Excel;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

use App\Services\Curfoxapiservice;


Route::middleware('auth:seller')->get('/seller/debug/curfox', function (Curfoxapiservice $curfox) {
    $tokenOk = false;
    $statesCount = 0;
    $citiesCount = 0;

    try {
        $token = $curfox->getToken();
        $tokenOk = !empty($token);

        $states = $curfox->getStatesWithCities();
        $statesCount = count($states);

        $cities = $curfox->getAllCities();
        $citiesCount = count($cities);

        return response()->json([
            'success' => true,
            'token_ok' => $tokenOk,
            'states_count' => $statesCount,
            'cities_count' => $citiesCount,
            'sample_state' => $states[0]['name'] ?? null,
            'sample_city' => $cities[0]['name'] ?? null,
        ]);
    } catch (\Throwable $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
        ], 500);
    }
});


Route::get('/', [PageController::class, 'login'])->name('login');
Route::post('/',[LoginController::class,'loginCheck'])->name('login.check');




// ========== ADMIN ROUTES ==========
Route::middleware(['auth:admin', \App\Http\Middleware\LastUserActivity::class])->group(function(){
    Route::prefix('admin')->group(function () {
        Route::post('/logout', [LoginController::class, 'logout'])->name('admin.logout');
        Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('/dashboard/chart-data', [App\Http\Controllers\Admin\DashboardController::class, 'getChartData'])->name('admin.dashboard.chart-data');
        Route::get('/', [PageController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/admins' , [AdminController::class,'index'] )->name('admin.admins.index');
        Route::get('/admins/{id}/view', [AdminController::class, 'show'])->name('admin.admins.view');
        Route::get('/create',[AdminController::class,'create'])->name('admin.create');
        Route::post('/store',[AdminController::class,'store'])->name('admin.store');
        Route::get('/profile', [AdminController::class, 'profile'])->name('admin.profile');
        Route::get('/profile/edit', [AdminController::class, 'profileEdit'])->name('admin.profile.edit');
        Route::post('/profile/update', [AdminController::class, 'profileUpdate'])->name('admin.profile.update');

        Route::prefix('product')->group(function () {
            Route::get('/', [ProductController::class, 'index'])->name('product.index');
            Route::get('/create', [ProductController::class, 'create'])->name('product.create');
            Route::post('/create', [ProductController::class, 'store'])->name('product.store');
            Route::get('/edit/{type}/{id}', [ProductController::class, 'edit'])->name('product.edit');
            Route::put('/update/{type}/{id}', [ProductController::class, 'update'])->name('product.update');
            Route::get('/view/{type}/{id}', [ProductController::class, 'product_view'])->name('product.view');
            Route::get('/delete/{type}/{id}', [ProductController::class, 'delete'])->name('product.delete');
        });

        Route::prefix('brand')->group(function () {
            Route::get('/list', [BrandController::class, 'index'])->name('brand.index');
            Route::get('/', [BrandController::class, 'create'])->name('brand.create');
            Route::post('/', [BrandController::class, 'store'])->name('brand.store');
            Route::get('/view/{id}', [BrandController::class, 'show'])->name('brand.show');
            Route::get('/edit/{id}', [BrandController::class, 'edit'])->name('brand.edit');
            Route::put('/update/{id}', [BrandController::class, 'update'])->name('brand.update');
            Route::get('/{id}', [BrandController::class, 'delete'])->name('brand.delete');
        });

        Route::prefix('category')->group(function () {
            Route::get('/list', [CategoryController::class, 'index'])->name('category.index');
            Route::get('/', [CategoryController::class, 'create'])->name('category.create');
            Route::post('/', [CategoryController::class, 'store'])->name('category.store');
            Route::get('/view/{id}', [CategoryController::class, 'show'])->name('category.show');
            Route::get('/edit/{id}', [CategoryController::class, 'edit'])->name('category.edit');
            Route::post('/update/{id}', [CategoryController::class, 'update'])->name('category.update');
            Route::get('/{id}', [CategoryController::class, 'delete'])->name('category.delete');
        });

        Route::prefix('seller')->group(function () {
            Route::get('/list', [SellerController::class, 'index'])->name('seller.list');
            Route::get('/', [SellerController::class, 'create'])->name('seller.create');
            Route::post('/',[SellerController::class,'store'])->name('admin.seller.store');
            Route::get('/delete/{id}', [SellerController::class, 'delete'])->name('seller.delete');
            Route::get('/{id}',[SellerController::class,'edit'])->name('admin.seller.edit');
            Route::get('seller/{id}', [SellerController::class, 'show'])->name('admin.seller.view');
            Route::post('/update',[SellerController::class,'update'])->name('admin.seller.update');
        });

        Route::prefix('wholesaler')->group(function () {
            Route::get('/',               [WholesalerController::class, 'index'])->name('admin.wholesaler.index');
            Route::get('/list',           [WholesalerController::class, 'index'])->name('admin.wholesaler.list');
            Route::get('/create',         [WholesalerController::class, 'create'])->name('admin.wholesaler.create');
            Route::post('/store',         [WholesalerController::class, 'store'])->name('admin.wholesaler.store');
            Route::get('/view/{id}',      [WholesalerController::class, 'show'])->name('admin.wholesaler.view');
            Route::get('/edit/{id}',      [WholesalerController::class, 'edit'])->name('admin.wholesaler.edit');
            Route::post('/update/{id}',   [WholesalerController::class, 'update'])->name('admin.wholesaler.update');
            Route::delete('/delete/{id}', [WholesalerController::class, 'destroy'])->name('admin.wholesaler.delete');
        });

        Route::prefix('order')->group(function () {
            Route::get('new-order', [OrderController::class, 'new_order'])->name('admin.new_order');
            Route::get('/complete-order', [OrderController::class, 'completeOrder'])->name('admin.complete_order_list');
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
            Route::delete('/destroy/{id}',[EmployeeController::class,'destroy'])->name('employee.delete');
            Route::get('/edit/{id}',[EmployeeController::class,'edit'])->name('employee.edit');
            Route::put('/update/{id}',[EmployeeController::class,'update'])->name('employee.update');
            Route::get('/show/{id}',[EmployeeController::class,'show'])->name('employee.view');
        });
    });
});

// ========== SELLER ROUTES ==========
Route::middleware('auth:seller')->group(function(){
    Route::prefix('seller')->group(function () {
        Route::post('/logout', [LoginController::class, 'logout'])->name('seller.logout');
        Route::get('/', [SellerController::class, 'dashboard'])->name('seller.dashboard');

        // Profile Routes
        Route::get('/profile', [App\Http\Controllers\Seller\Sellerprofilecontroller::class, 'index'])->name('profile.index');
        Route::post('/profile/update', [App\Http\Controllers\Seller\Sellerprofilecontroller::class, 'update'])->name('profile.update');
        Route::post('/profile/password', [App\Http\Controllers\Seller\Sellerprofilecontroller::class, 'updatePassword'])->name('profile.password');

        // Shop Routes
        Route::prefix('shop')->name('seller.shop.')->group(function () {
            Route::get('/', [SellerShopController::class, 'index'])->name('seller.index');
            Route::get('/product/view/{product}', [\App\Http\Controllers\Seller\SellerShopController::class, 'showProduct'])->name('product.show');
            Route::get('/product/{code}/details', [SellerShopController::class, 'getProductDetails'])->name('product.details');
            Route::get('/product/find/{code}', [SellerShopController::class, 'findByCode'])->name('product.find');
            Route::post('/product/copy/{code}', [SellerShopController::class, 'copyProductCode'])->name('product.copy');
            Route::post('/order-now', [SellerShopController::class, 'orderNow'])->name('order.now');
            Route::post('/quick-order', [SellerShopController::class, 'quickOrder'])->name('quick.order');
            Route::get('/search', [SellerShopController::class, 'search'])->name('search');
            Route::get('/redirect-to-order', [SellerShopController::class, 'redirectToNewOrder'])->name('redirect.order');
        });


        // Seller Order Routes
        Route::prefix('order')->name('seller.')->group(function () {
            Route::get('new-order', [SellerOrderController::class, 'index'])->name('new-order');
            Route::post('store', [SellerOrderController::class, 'store'])->name('order.store');

            // ✅ bulk store for same page CSV import
            Route::post('bulk-store', [SellerOrderController::class, 'bulkStore'])->name('order.bulk-store');
            Route::post('bulk-validate', [SellerOrderController::class, 'bulkValidate'])->name('order.bulk-validate');
            Route::post('{id}/dispatch', [SellerOrderController::class, 'dispatchToCurfox'])->name('order.dispatch');
            Route::get('cities/json', [SellerOrderController::class, 'citiesJson'])->name('order.cities.json');

            // Order list views
            Route::get('pending-order', [SellerOrderController::class, 'pendingOrder'])->name('pending-order');
            Route::get('complete-order', [SellerOrderController::class, 'completeOrder'])->name('complete-order');
            Route::get('return-order', [SellerOrderController::class, 'returnOrder'])->name('return-order');
            Route::get('rescheduling-order', [SellerOrderController::class, 'reschedulingOrder'])->name('rescheduling-order');

            // CRUD operations
            Route::get('show/{id}', [SellerOrderController::class, 'show'])->name('order.show');
            Route::put('update/{id}', [SellerOrderController::class, 'update'])->name('order.update');
            Route::delete('destroy/{id}', [SellerOrderController::class, 'destroy'])->name('order.destroy');

            // Product details for order form
            Route::get('product/{code}/details', [SellerOrderController::class, 'getProductDetails'])
                ->name('product.details')
                ->where('code', '.*');
        });

        Route::prefix('product')->group(function () {
            Route::get('/', [SellerController::class, 'seller_product'])->name('seller.product.index');
        });

        Route::prefix('payment')->group(function () {
            Route::get('/my-payment',[SellerPaymentController::class,'my_payment'])->name('seller.my_payment');
            Route::get('/withdraw',[SellerPaymentController::class,'withdraw'])->name('seller.withdraw');
            Route::get('/history',[SellerPaymentController::class,'payment_history'])->name('seller.payment_history');
        });

        // Seller Call Routes
        Route::prefix('call')->name('call.')->group(function () {
            // List views
            Route::get('/', [SellerCallController::class, 'index'])->name('index');
            Route::get('/conform_call_list', [SellerCallController::class, 'conformCallList'])->name('conform');
            Route::get('/reject_call_list', [SellerCallController::class, 'rejectCallList'])->name('reject');
            Route::get('/not_answer_call_list', [SellerCallController::class, 'notAnswerCallList'])->name('not_answer_call_list');
            Route::get('/other_call_list', [SellerCallController::class, 'otherCallList'])->name('other_call_list');

            // CRUD operations
            Route::get('/view/{id}', [SellerCallController::class, 'show'])->name('view');
            Route::get('/edit/{id}', [SellerCallController::class, 'edit'])->name('edit');
            Route::put('/update/{id}', [SellerCallController::class, 'update'])->name('update');
            Route::delete('/destroy/{id}', [SellerCallController::class, 'destroy'])->name('destroy');
            Route::get('/print/{id}', [SellerCallController::class, 'print'])->name('print');

            // Bulk operations
            Route::post('/bulk-status', [SellerCallController::class, 'bulkUpdateStatus'])->name('bulk_status');
            Route::get('/export-calls', [SellerCallController::class, 'export'])->name('export');
            Route::get('/statistics', [SellerCallController::class, 'statistics'])->name('statistics');

            // CSV upload
            Route::get('/csv-upload', [SellerCallController::class, 'csvUploadPage'])->name('csv_upload');
            Route::post('/csv-insert', [SellerCallController::class, 'csvInsert'])->name('csv_insert');

            // Additional routes
            Route::get('/seller-products', [SellerCallController::class, 'getSellerProducts'])->name('seller_products');
            Route::get('/csrf-refresh', [SellerCallController::class, 'csrfRefresh'])->name('csrf_refresh');

            // ── Curfox / delivery confirm routes ─────────────────────────
            Route::post('/{id}/confirm', [SellerCallController::class, 'confirmCall'])->name('confirm');
            Route::post('/bulk-confirm', [SellerCallController::class, 'bulkConfirmCalls'])->name('bulk_confirm');
            Route::get('/curfox-cities', [SellerCallController::class, 'curfoxCitiesJson'])->name('curfox_cities');
            // ─────────────────────────────────────────────────────────────
        });

        // Seller Product Routes
        Route::prefix('product')->name('seller.product.')->group(function (){
            Route::get('/', [SellerProductController::class, 'index'])->name('index');
            Route::get('/create', [SellerProductController::class, 'create'])->name('create');
            Route::post('/', [SellerProductController::class, 'store'])->name('store');
            Route::get('/{id}', [SellerProductController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [SellerProductController::class, 'edit'])->name('edit');
            Route::put('/{id}', [SellerProductController::class, 'update'])->name('update');
            Route::delete('/{id}', [SellerProductController::class, 'destroy'])->name('destroy');
            Route::post('/{id}/status', [SellerProductController::class, 'updateStatus'])->name('status.update');
        });

        // Seller Brand Routes
        Route::prefix('brands')->name('seller.brands.')->group(function () {
            Route::get('/', [SellerBrandController::class, 'index'])->name('index');
            Route::get('/create', [SellerBrandController::class, 'create'])->name('create');
            Route::post('/', [SellerBrandController::class, 'store'])->name('store');
            Route::get('/{brand}', [SellerBrandController::class, 'show'])->name('show');
            Route::get('/{brand}/edit', [SellerBrandController::class, 'edit'])->name('edit');
            Route::put('/{brand}', [SellerBrandController::class, 'update'])->name('update');
            Route::delete('/{brand}', [SellerBrandController::class, 'destroy'])->name('destroy');
        });

        // Category Management Routes
        Route::prefix('category')->name('seller.category.')->group(function () {
            Route::get('/', [SellerCategoryController::class, 'index'])->name('index');
            Route::get('/create', [SellerCategoryController::class, 'create'])->name('create');
            Route::post('/', [SellerCategoryController::class, 'store'])->name('store');
            Route::get('/{id}', [SellerCategoryController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [SellerCategoryController::class, 'edit'])->name('edit');
            Route::put('/{id}', [SellerCategoryController::class, 'update'])->name('update');
            Route::delete('/{id}', [SellerCategoryController::class, 'destroy'])->name('destroy');
            Route::patch('/{id}/toggle-status', [SellerCategoryController::class, 'toggleStatus'])->name('toggle-status');
        });

    });
});


// ========== EMPLOYEE ROUTES ==========
Route::prefix('employee')->middleware('auth:employee')->group(function(){

    // Delivery Calls
    Route::prefix('delivery-calls')->name('employee.delivery_calls.')->group(function () {
        Route::get('/',            [EmployeeDeliveryCallController::class, 'index'])->name('index');
        Route::get('/pending',     [EmployeeDeliveryCallController::class, 'pending'])->name('pending');
        Route::get('/packing',     [EmployeeDeliveryCallController::class, 'packing'])->name('packing');
        Route::get('/packed',      [EmployeeDeliveryCallController::class, 'packed'])->name('packed');
        Route::get('/givedriver',  [EmployeeDeliveryCallController::class, 'givedriver'])->name('givedriver');
        Route::get('/{id}',        [EmployeeDeliveryCallController::class, 'show'])->name('show');

        // Business selector
        Route::post('/set-business', [EmployeeDeliveryCallController::class, 'setBusiness'])->name('set_business');

        // Status transitions
        Route::post('/{id}/status',  [EmployeeDeliveryCallController::class, 'updateStatus'])->name('update_status');
        Route::post('/bulk-status',  [EmployeeDeliveryCallController::class, 'bulkUpdateStatus'])->name('bulk_status');

        // Origin updates
        Route::patch('/{id}/origin', [EmployeeDeliveryCallController::class, 'updateOrigin'])->name('update_origin');
        Route::patch('/bulk-origin', [EmployeeDeliveryCallController::class, 'bulkUpdateOrigin'])->name('bulk_origin');
    });


    // Dashboard
    Route::get('/', [EmployeeController::class, 'dashboard'])->name('employee.dashboard');
    Route::post('/logout', [LoginController::class, 'logout'])->name('employee.logout');

    // Profile Routes
    Route::get('/profile', [EmployeeController::class, 'profile'])->name('employee.profile');
    Route::post('/employee/profile/update', [EmployeeController::class, 'updateProfile'])->name('employee.profile.update');
    Route::get('/employee/profile/data', [EmployeeController::class, 'getProfileData'])->name('employee.profile.data');
    Route::post('/employee/profile/image/upload', [EmployeeController::class, 'uploadProfileImage'])->name('employee.profile.image.upload');
    Route::delete('/employee/profile/image/delete', [EmployeeController::class, 'deleteProfileImage'])->name('employee.profile.image.delete');
    // Wholesaler Management - PRICE UPDATE ROUTES
    Route::prefix('wholesaler')->name('employee.wholesaler.')->group(function() {
        // Products - List and View
        Route::get('/products', [EmployeeController::class, 'wholesalerProducts'])->name('products');
        Route::get('/product/{id}', [EmployeeController::class, 'viewProduct'])->name('product-view');
        Route::get('/price-update/{id}', [EmployeeController::class, 'showPriceUpdateForm'])->name('price-update-form');
        Route::post('/price-update', [EmployeeController::class, 'updateProductPrice'])->name('price-update');
        Route::post('/bulk-price-update', [EmployeeController::class, 'bulkPriceUpdate'])->name('bulk-price-update');

        // Seller Management (Existing)
        Route::prefix('seller')->name('seller.')->group(function() {
            Route::get('/orders', [EmployeeController::class, 'sellerOrders'])->name('employee.seller.orders');
            Route::prefix('order')->name('order.')->group(function () {
                Route::post('/bulk-store', [SellerOrderController::class, 'bulkStore'])->name('bulk-store');
            });
            Route::post('/orders/status/update/{id}', [EmployeeController::class, 'updateOrderStatus'])->name('orders.status-update');
            Route::get('/orders/view/{id}', [EmployeeController::class, 'viewOrder'])->name('order-view');
        });
        // Print route
        Route::get('/print/{id}', [EmployeeWholesalerOrderController::class, 'printDeliveryLabel'])->name('print.out');
    });

    // Wholesaler Orders Management
    Route::prefix('wholesaler-orders')->name('employee.wholesaler_orders.')->group(function() {
        Route::get('/', [EmployeeWholesalerOrderController::class, 'index'])->name('index');
        Route::get('/view/{id}', [EmployeeWholesalerOrderController::class, 'show'])->name('show');
        Route::put('/{id}/status', [EmployeeWholesalerOrderController::class, 'updateStatus'])->name('updateStatus');
        Route::put('/bulk/status', [EmployeeWholesalerOrderController::class, 'bulkUpdateStatus'])->name('bulkUpdateStatus');
        Route::post('/{id}/refund', [EmployeeWholesalerOrderController::class, 'processRefund'])->name('refund');

        // Save selected orders
        Route::post('/save-selected', [EmployeeWholesalerOrderController::class, 'saveSelectedOrders'])->name('saveSelected');

        // Print Routes
        Route::get('/print/selection', [EmployeeWholesalerOrderController::class, 'printSelection'])->name('print.selection');
        Route::post('/print/selected', [EmployeeWholesalerOrderController::class, 'printSelectedOrders'])->name('print.selected');
        Route::post('/print/clear', [EmployeeWholesalerOrderController::class, 'clearSelectedOrders'])->name('print.clear');

        // Invoice routes
        Route::get('/{id}/invoice/download', [EmployeeWholesalerOrderController::class, 'downloadInvoice'])->name('invoice.download');
        Route::get('/{id}/invoice/view', [EmployeeWholesalerOrderController::class, 'viewInvoice'])->name('invoice.view');

        // Print routes
        Route::get('/{id}/print-label', [EmployeeWholesalerOrderController::class, 'printDeliveryLabel'])->name('print.label');

        // Time-based order routes
        Route::get('/today', [EmployeeWholesalerOrderController::class, 'todayOrders'])->name('today');
        Route::get('/weekly', [EmployeeWholesalerOrderController::class, 'weeklyOrders'])->name('weekly');

        // Export
        Route::get('/export/csv', [EmployeeWholesalerOrderController::class, 'export'])->name('export');
    });

    // Seller Management (Existing)
    Route::prefix('seller')->name('seller.')->group(function() {
        Route::get('/orders', [EmployeeController::class, 'sellerOrders'])->name('employee.seller.orders');
        Route::post('/orders/status/update/{id}', [EmployeeController::class, 'updateOrderStatus'])->name('orders.status-update');
        Route::get('/orders/view/{id}', [EmployeeController::class, 'viewOrder'])->name('order-view');
    });

    // Reports & Analytics
    Route::get('/analytics', [EmployeeController::class, 'analytics'])->name('employee.analytics');
    Route::get('/price-changes/history', [EmployeeController::class, 'priceChangeHistory'])->name('price-changes.history');
    Route::get('/price-changes/export', [EmployeeController::class, 'exportPriceChanges'])->name('price-changes.export');

    // Reports Export - FIXED ROUTE
    Route::get('/export/report', [EmployeeController::class, 'exportReport'])->name('employee.export.report');

    // General Print route
    Route::get('/print/{id}', [EmployeeWholesalerOrderController::class, 'printDeliveryLabel'])->name('print.out');
});

// ========== WHOLESALER ROUTES ==========
Route::middleware('auth:wholesaler')->group(function () {
    Route::prefix('wholesaler')->name('wholesaler.')->group(function () {
        // Dashboard & Auth
        Route::get('/', [WholesalerController::class, 'dashboard'])->name('dashboard');
        Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

        // Storage info
        Route::get('/storage-info', [WholesalerController::class, 'getStorageInfo'])->name('storage.info');

        // Profile Routes
        Route::get('/profile', [WholesalerController::class, 'profile'])->name('profile');
        Route::post('/profile/update', [WholesalerController::class, 'updateProfile'])->name('profile.update');
        Route::post('/profile/avatar', [WholesalerController::class, 'uploadAvatar'])->name('profile.avatar');


        // Shop Routes (Public Facing)
        Route::prefix('shop')->name('shop.')->group(function () {
            Route::get('/', [WholesalerShopController::class, 'index'])->name('index');
            Route::get('/product/{id}', [WholesalerShopController::class, 'showProduct'])->name('product.show');
            Route::post('/order-now', [WholesalerShopController::class, 'orderNow'])->name('order.now');
            Route::get('/search', [WholesalerShopController::class, 'search'])->name('search');
        });

        // Order Management
        Route::prefix('orders')->name('orders.')->group(function () {
            Route::get('/', [WholesalerOrderController::class, 'index'])->name('index');
            Route::get('/view/{id}', [WholesalerOrderController::class, 'show'])->name('show');
            Route::put('/{id}/status', [WholesalerOrderController::class, 'updateStatus'])->name('updateStatus');

            // Status-based routes
            Route::get('/status/pending', [WholesalerOrderController::class, 'pending'])->name('pending');
            Route::get('/status/confirmed', [WholesalerOrderController::class, 'confirmed'])->name('confirmed');
            Route::get('/status/processing', [WholesalerOrderController::class, 'processing'])->name('processing');
            Route::get('/status/shipped', [WholesalerOrderController::class, 'shipped'])->name('shipped');
            Route::get('/status/completed', [WholesalerOrderController::class, 'completed'])->name('completed');
            Route::get('/status/cancelled', [WholesalerOrderController::class, 'cancelled'])->name('cancelled');

            // Invoice routes
            Route::get('/{id}/invoice/download', [WholesalerOrderController::class, 'downloadInvoice'])->name('invoice.download');
            Route::get('/{id}/invoice/view', [WholesalerOrderController::class, 'viewInvoice'])->name('invoice.view');

            // Export
            Route::get('/export/csv', [WholesalerOrderController::class, 'export'])->name('export');
        });

        // Product Management (Backend)
        Route::prefix('products')->name('products.')->group(function () {
            Route::get('/', [WholesalerProductController::class, 'index'])->name('index');
            Route::get('/create', [WholesalerProductController::class, 'create'])->name('create');
            Route::post('/', [WholesalerProductController::class, 'store'])->name('store');
            Route::get('/{id}', [WholesalerProductController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [WholesalerProductController::class, 'edit'])->name('edit');
            Route::put('/{id}', [WholesalerProductController::class, 'update'])->name('update');
            Route::delete('/{id}', [WholesalerProductController::class, 'destroy'])->name('destroy');
            Route::post('/{id}/status', [WholesalerProductController::class, 'updateStatus'])->name('updateStatus');
        });

        // Brand Management
        Route::prefix('brands')->name('brands.')->group(function () {
            Route::get('/', [WholesalerBrandController::class, 'index'])->name('index');
            Route::get('/create', [WholesalerBrandController::class, 'create'])->name('create');
            Route::post('/', [WholesalerBrandController::class, 'store'])->name('store');
            Route::get('/{brand}/edit', [WholesalerBrandController::class, 'edit'])->name('edit');
            Route::put('/{brand}', [WholesalerBrandController::class, 'update'])->name('update');
            Route::delete('/{brand}', [WholesalerBrandController::class, 'destroy'])->name('destroy');
        });

        // Category Management
        Route::prefix('categories')->name('categories.')->group(function () {
            Route::get('/', [WholesalerCategoryController::class, 'index'])->name('index');
            Route::get('/create', [WholesalerCategoryController::class, 'create'])->name('create');
            Route::post('/', [WholesalerCategoryController::class, 'store'])->name('store');
            Route::get('/{id}', [WholesalerCategoryController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [WholesalerCategoryController::class, 'edit'])->name('edit');
            Route::put('/{id}', [WholesalerCategoryController::class, 'update'])->name('update');
            Route::delete('/{id}', [WholesalerCategoryController::class, 'destroy'])->name('destroy');
        });

        // Payment Management
        Route::prefix('payments')->name('payments.')->group(function () {
            Route::get('/', [WholesalerPaymentController::class, 'index'])->name('index');
            Route::get('/history', [WholesalerPaymentController::class, 'paymentHistory'])->name('history');
            Route::get('/{id}', [WholesalerPaymentController::class, 'show'])->name('show');
            Route::get('/withdraw', [WholesalerPaymentController::class, 'withdraw'])->name('withdraw');
            Route::post('/withdraw', [WholesalerPaymentController::class, 'processWithdraw'])->name('process-withdraw');
            Route::post('/withdraw/{id}/cancel', [WholesalerPaymentController::class, 'cancelWithdraw'])->name('cancel-withdraw');
            Route::get('/export', [WholesalerPaymentController::class, 'exportPayments'])->name('export');

            // Payment Methods
            Route::get('/methods', [WholesalerPaymentController::class, 'paymentMethods'])->name('methods');
            Route::get('/methods/create', [WholesalerPaymentController::class, 'createPaymentMethod'])->name('methods.create');
            Route::post('/methods', [WholesalerPaymentController::class, 'storePaymentMethod'])->name('methods.store');
            Route::get('/methods/{id}/edit', [WholesalerPaymentController::class, 'editPaymentMethod'])->name('methods.edit');
            Route::put('/methods/{id}', [WholesalerPaymentController::class, 'updatePaymentMethod'])->name('methods.update');
            Route::delete('/methods/{id}', [WholesalerPaymentController::class, 'destroyPaymentMethod'])->name('methods.destroy');

            // Payment Gateways
            Route::get('/gateways', [WholesalerPaymentController::class, 'gateways'])->name('gateways');
            Route::get('/gateways/create', [WholesalerPaymentController::class, 'createGateway'])->name('gateways.create');
            Route::post('/gateways', [WholesalerPaymentController::class, 'storeGateway'])->name('gateways.store');
            Route::get('/gateways/{id}/test', [WholesalerPaymentController::class, 'testGateway'])->name('gateways.test');
            Route::delete('/gateways/{id}', [WholesalerPaymentController::class, 'destroyGateway'])->name('gateways.destroy');

            // Checkout
            Route::post('/checkout', [WholesalerPaymentController::class, 'createCheckoutSession'])->name('checkout');
            Route::get('/success', [WholesalerPaymentController::class, 'paymentSuccess'])->name('success');
            Route::get('/cancel', [WholesalerPaymentController::class, 'paymentCancel'])->name('cancel');
        });
    });
});

// ========== WEBHOOK ROUTES (No Auth Required) ==========
Route::prefix('webhook')->name('webhook.')->group(function () {
    Route::post('/stripe', [WholesalerPaymentController::class, 'webhookHandler'])->name('stripe');
    Route::post('/paypal', [WholesalerPaymentController::class, 'webhookHandler'])->name('paypal');
    Route::post('/jazzcash', [WholesalerPaymentController::class, 'webhookHandler'])->name('jazzcash');
});
