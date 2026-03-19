<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Employee\EmployeeWholesalerOrderController;
use App\Http\Controllers\Employee\EmployeeWholesalerOrderExchangeController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Seller\SystemSellerDeliveryTrackingController;
use App\Http\Controllers\Seller\SelfSellerDeliveryCallController;
use App\Http\Controllers\Seller\SellerBrandController;
use App\Http\Controllers\Seller\SellerCategoryController;
use App\Http\Controllers\Seller\SellerOrderController;
use App\Http\Controllers\Seller\SellerPaymentController;
use App\Http\Controllers\Seller\SellerProductController;
use App\Http\Controllers\Seller\SellerShopController;
use App\Http\Controllers\Seller\Sellerprofilecontroller;
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
use App\Http\Controllers\Employee\EmployeeWholesalerExchangeController;
use Illuminate\Support\Facades\Route;
use App\Services\Curfoxapiservice;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ── Curfox debug (dev only) ─────────────────────────────────────────────────
Route::middleware('auth:seller')->get('/seller/debug/curfox', function (Curfoxapiservice $curfox) {
    try {
        $token  = $curfox->getToken();
        $states = $curfox->getStatesWithCities();
        $cities = $curfox->getAllCities();
        return response()->json([
            'success'       => true,
            'token_ok'      => !empty($token),
            'states_count'  => count($states),
            'cities_count'  => count($cities),
            'sample_state'  => $states[0]['name'] ?? null,
            'sample_city'   => $cities[0]['name'] ?? null,
        ]);
    } catch (\Throwable $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
});

// ── Login ───────────────────────────────────────────────────────────────────
Route::get('/',  [PageController::class, 'login'])->name('login');
Route::post('/', [LoginController::class, 'loginCheck'])->name('login.check');


// ═══════════════════════════════════════════════════════════════════════════
// ADMIN ROUTES
// ═══════════════════════════════════════════════════════════════════════════
Route::middleware(['auth:admin', \App\Http\Middleware\LastUserActivity::class])
    ->prefix('admin')
    ->group(function () {

        Route::post('/logout',    [LoginController::class, 'logout'])->name('admin.logout');
        Route::get('/',           [PageController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/dashboard',  [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard.index');
        Route::get('/dashboard/chart-data', [App\Http\Controllers\Admin\DashboardController::class, 'getChartData'])->name('admin.dashboard.chart-data');

        // Admins
        Route::prefix('admins')->group(function () {
            Route::get('/',         [AdminController::class, 'index'])->name('admin.admins.index');
            Route::get('/{id}/view',[AdminController::class, 'show'])->name('admin.admins.view');
        });
        Route::get('/create',  [AdminController::class, 'create'])->name('admin.create');
        Route::post('/store',  [AdminController::class, 'store'])->name('admin.store');

        // Products
        Route::prefix('product')->group(function () {
            Route::get('/', [ProductController::class, 'index'])->name('product.index');
            Route::get('/create', [ProductController::class, 'create'])->name('product.create');
            Route::post('/create', [ProductController::class, 'store'])->name('product.store');
            Route::get('/edit/{type}/{id}', [ProductController::class, 'edit'])->name('product.edit');
            Route::put('/update/{type}/{id}', [ProductController::class, 'update'])->name('product.update');
            Route::get('/view/{type}/{id}', [ProductController::class, 'product_view'])->name('product.view');
            Route::get('/delete/{type}/{id}', [ProductController::class, 'delete'])->name('product.delete');
        });

        // Brands
        Route::prefix('brand')->group(function () {
            Route::get('/list',         [BrandController::class, 'index'])->name('brand.index');
            Route::get('/',             [BrandController::class, 'create'])->name('brand.create');
            Route::post('/',            [BrandController::class, 'store'])->name('brand.store');
            Route::get('/view/{id}',    [BrandController::class, 'show'])->name('brand.show');
            Route::get('/edit/{id}',    [BrandController::class, 'edit'])->name('brand.edit');
            Route::put('/update/{id}',  [BrandController::class, 'update'])->name('brand.update');
            Route::get('/get-creators', [BrandController::class, 'getCreators'])->name('brand.get-creators');
            Route::get('/{id}',         [BrandController::class, 'delete'])->name('brand.delete');
        });

        // Categories
        Route::prefix('category')->group(function () {
            Route::get('/list',           [CategoryController::class, 'index'])->name('category.index');
            Route::get('/',               [CategoryController::class, 'create'])->name('category.create');
            Route::post('/',              [CategoryController::class, 'store'])->name('category.store');
            Route::get('/view/{id}',      [CategoryController::class, 'show'])->name('category.show');
            Route::get('/edit/{id}',      [CategoryController::class, 'edit'])->name('category.edit');
            Route::post('/update/{id}',   [CategoryController::class, 'update'])->name('category.update');
            Route::get('/get-creators',   [CategoryController::class, 'getCreators'])->name('category.get-creators');
            Route::get('/{id}',           [CategoryController::class, 'delete'])->name('category.delete');
        });

        // Sellers
        Route::prefix('seller')->group(function () {
            Route::get('/list', [SellerController::class, 'index'])->name('seller.list');
            Route::get('/', [SellerController::class, 'create'])->name('seller.create');
            Route::post('/', [SellerController::class, 'store'])->name('admin.seller.store');
            Route::get('/delete/{id}', [SellerController::class, 'delete'])->name('seller.delete');
            Route::get('/check-referral', [SellerController::class, 'checkReferral'])->name('admin.seller.check-referral');
            Route::get('/{id}', [SellerController::class, 'edit'])->name('admin.seller.edit');
            Route::get('seller/{id}', [SellerController::class, 'show'])->name('admin.seller.view');
            Route::post('/update', [SellerController::class, 'update'])->name('admin.seller.update');
        });

        // Wholesalers
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

        // Orders
        Route::prefix('order')->group(function () {
            Route::get('new-order',           [OrderController::class, 'new_order'])->name('admin.new_order');
            Route::get('/complete-order',     [OrderController::class, 'completeOrder'])->name('admin.complete_order_list');
            Route::get('/return-order',       [OrderController::class, 'returnOrder'])->name('admin.return_order_list');
            Route::get('/rescheduling-order', [OrderController::class, 'reschedulingOrder'])->name('admin.rescheduling_order_list');
            Route::get('/pending-order',      [OrderController::class, 'pendingOrder'])->name('admin.pending_order_list');
        });

        // Stock
        Route::prefix('stock')->group(function () {
            Route::get('/low-stock', [StockController::class, 'low_stock'])->name('low-stock');
        });

        // Shop
        Route::prefix('shop')->group(function () {
            Route::get('/',              [ShopController::class, 'index'])->name('shop.index');
            Route::get('product/{id}',   [ProductController::class, 'product_view'])->name('product.product_view');
        });

        // Employees
        Route::prefix('employee')->group(function () {
            Route::get('/', [EmployeeController::class, 'index'])->name('employee.list');
            Route::get('/create', [EmployeeController::class, 'create'])->name('employee.create');
            Route::post('/', [EmployeeController::class, 'store'])->name('employee.store');
            Route::delete('/destroy/{id}',[EmployeeController::class,'destroy'])->name('employee.delete');
            Route::get('/edit/{id}',[EmployeeController::class,'edit'])->name('employee.edit');
            Route::put('/update/{id}',[EmployeeController::class,'update'])->name('employee.update');
            Route::get('/show/{id}',[EmployeeController::class,'show'])->name('employee.view');
        });
    });


// ═══════════════════════════════════════════════════════════════════════════
// SELLER ROUTES
// ═══════════════════════════════════════════════════════════════════════════
Route::middleware('auth:seller')->group(function () {

    // ── System Seller: Delivery (reads EmployeeDeliveryCall) ────────────────
    // NOTE: All static paths BEFORE the /{id} wildcard — wildcard MUST be last
    Route::prefix('system-delivery')->name('seller.system_delivery.')->group(function () {

        $ctrl = [SystemSellerDeliveryTrackingController::class];

        Route::get('/pipeline',             [...$ctrl, 'pipeline'])->name('pipeline');
        Route::get('/tracking',             [...$ctrl, 'index'])->name('tracking.index');
        Route::get('/tracking/confirmed',   [...$ctrl, 'confirmed'])->name('tracking.confirmed');
        Route::get('/tracking/completed',   [...$ctrl, 'completed'])->name('tracking.completed');
        Route::get('/tracking/rescheduled', [...$ctrl, 'rescheduled'])->name('tracking.rescheduled');
        Route::get('/tracking/cancelled',   [...$ctrl, 'cancelled'])->name('tracking.cancelled');
        Route::post('/sync-all',            [...$ctrl, 'syncAll'])->name('sync_all');
        Route::post('/tracking-info',       [...$ctrl, 'tracking'])->name('tracking_info');
        Route::post('/{id}/sync-waybill',   [...$ctrl, 'syncWaybill'])->name('sync_waybill')->whereNumber('id');

        // Wildcard LAST
        Route::get('/{id}',                 [...$ctrl, 'show'])->name('show')->whereNumber('id');
    });

    // ── All other seller routes ──────────────────────────────────────────────
    Route::prefix('seller')->name('seller.')->group(function () {

        // Auth & Dashboard
        Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
        Route::get('/',        [SellerController::class, 'dashboard'])->name('dashboard');

        // Profile
        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/',          [Sellerprofilecontroller::class, 'index'])->name('index');
            Route::post('/update',   [Sellerprofilecontroller::class, 'update'])->name('update');
            Route::post('/password', [Sellerprofilecontroller::class, 'updatePassword'])->name('password');
        });

        // Create Order
        Route::prefix('create')->name('create.')->group(function () {
            Route::get('/new-order', [SellerOrderController::class, 'index'])->name('new-order');
        });

        // Order Management
        Route::prefix('order')->name('order.')->group(function () {
            Route::get('/pending',    [SellerOrderController::class, 'pendingOrder'])->name('pending');
            Route::get('/confirmed',  [SellerOrderController::class, 'confirmedOrders'])->name('confirmed');
            Route::get('/rescheduled',[SellerOrderController::class, 'rescheduledOrders'])->name('rescheduled');
            Route::get('/completed',  [SellerOrderController::class, 'completedOrders'])->name('completed');
            Route::get('/returned',   [SellerOrderController::class, 'returnedOrders'])->name('returned');
            Route::get('/show/{id}',  [SellerOrderController::class, 'show'])->name('show');
            Route::get('/edit/{id}',  [SellerOrderController::class, 'edit'])->name('edit');
            Route::get('/cities/json',[SellerOrderController::class, 'citiesJson'])->name('cities.json');
            Route::get('/product/{code}/details', [SellerOrderController::class, 'getProductDetails'])
                ->name('product.details')->where('code', '.*');
            Route::post('/store',          [SellerOrderController::class, 'store'])->name('store');
            Route::post('/bulk-store',     [SellerOrderController::class, 'bulkStore'])->name('bulk-store');
            Route::post('/bulk-validate',  [SellerOrderController::class, 'bulkValidate'])->name('bulk-validate');
            Route::put('/update/{id}',     [SellerOrderController::class, 'update'])->name('update');
            Route::delete('/destroy/{id}', [SellerOrderController::class, 'destroy'])->name('destroy');
        });

        // Call Management
        Route::prefix('call')->name('call.')->group(function () {
            Route::get('/pending',      [SellerOrderController::class, 'pendingCalls'])->name('pending');
            Route::get('/called',       [SellerOrderController::class, 'calledCalls'])->name('called');
            Route::get('/confirmed',    [SellerOrderController::class, 'confirmedCalls'])->name('confirmed');
            Route::get('/not-answered', [SellerOrderController::class, 'notAnsweredCalls'])->name('not-answered');
            Route::get('/cancelled',    [SellerOrderController::class, 'cancelledCalls'])->name('cancelled');
        });

        // Product Management
        Route::prefix('product')->name('product.')->group(function () {
            // Static paths before wildcards
            Route::get('/trashed',  [SellerProductController::class, 'trashed'])->name('trashed');
            Route::get('/next-code',[SellerProductController::class, 'getNextProductCode'])->name('next-code');
            Route::get('/',         [SellerProductController::class, 'index'])->name('index');
            Route::get('/create',   [SellerProductController::class, 'create'])->name('create');
            Route::post('/',        [SellerProductController::class, 'store'])->name('store');
            // Wildcard /{id} routes last
            Route::get('/{id}',                 [SellerProductController::class, 'show'])->name('show');
            Route::get('/{id}/edit',            [SellerProductController::class, 'edit'])->name('edit');
            Route::put('/{id}',                 [SellerProductController::class, 'update'])->name('update');
            Route::delete('/{id}',              [SellerProductController::class, 'destroy'])->name('destroy');
            Route::post('/{id}/status',         [SellerProductController::class, 'updateStatus'])->name('status.update');
            Route::post('/{id}/restore',        [SellerProductController::class, 'restore'])->name('restore');
            Route::delete('/{id}/force-delete', [SellerProductController::class, 'forceDelete'])->name('force-delete');
        });

        // Brand Management
        Route::prefix('brands')->name('brands.')->group(function () {
            Route::get('/',              [SellerBrandController::class, 'index'])->name('index');
            Route::get('/create',        [SellerBrandController::class, 'create'])->name('create');
            Route::post('/',             [SellerBrandController::class, 'store'])->name('store');
            Route::get('/{brand}',       [SellerBrandController::class, 'show'])->name('show');
            Route::get('/{brand}/edit',  [SellerBrandController::class, 'edit'])->name('edit');
            Route::put('/{brand}',       [SellerBrandController::class, 'update'])->name('update');
            Route::delete('/{brand}',    [SellerBrandController::class, 'destroy'])->name('destroy');
            Route::post('/{brand}/status',[SellerBrandController::class, 'updateStatus'])->name('status.update');
        });

        // Category Management
        Route::prefix('category')->name('category.')->group(function () {
            Route::get('/',                     [SellerCategoryController::class, 'index'])->name('index');
            Route::get('/create',               [SellerCategoryController::class, 'create'])->name('create');
            Route::post('/',                    [SellerCategoryController::class, 'store'])->name('store');
            Route::get('/{id}',                 [SellerCategoryController::class, 'show'])->name('show');
            Route::get('/{id}/edit',            [SellerCategoryController::class, 'edit'])->name('edit');
            Route::put('/{id}',                 [SellerCategoryController::class, 'update'])->name('update');
            Route::delete('/{id}',              [SellerCategoryController::class, 'destroy'])->name('destroy');
            Route::patch('/{id}/toggle-status', [SellerCategoryController::class, 'toggleStatus'])->name('toggle-status');
        });

        // Payment Management
        Route::prefix('payment')->name('payment.')->group(function () {
            Route::get('/my-payment',        [SellerPaymentController::class, 'my_payment'])->name('my-payment');
            Route::get('/withdraw',          [SellerPaymentController::class, 'withdraw'])->name('withdraw');
            Route::get('/history',           [SellerPaymentController::class, 'payment_history'])->name('history');
            Route::get('/withdrawals',       [SellerPaymentController::class, 'withdrawalHistory'])->name('withdrawals');
            Route::get('/methods',           [SellerPaymentController::class, 'paymentMethods'])->name('methods');
            Route::get('/methods/create',    [SellerPaymentController::class, 'createPaymentMethod'])->name('methods.create');
            Route::post('/methods',          [SellerPaymentController::class, 'storePaymentMethod'])->name('methods.store');
            Route::get('/methods/{id}/edit', [SellerPaymentController::class, 'editPaymentMethod'])->name('methods.edit');
            Route::put('/methods/{id}',      [SellerPaymentController::class, 'updatePaymentMethod'])->name('methods.update');
            Route::delete('/methods/{id}',   [SellerPaymentController::class, 'destroyPaymentMethod'])->name('methods.destroy');
        });

        // Shop
        Route::prefix('shop')->name('shop.')->group(function () {
            Route::get('/',             [SellerShopController::class, 'index'])->name('index');
            Route::get('/search',       [SellerShopController::class, 'search'])->name('search');
            Route::get('/redirect-to-order', [SellerShopController::class, 'redirectToNewOrder'])->name('redirect.order');
            Route::post('/order-now',   [SellerShopController::class, 'orderNow'])->name('order.now');
            Route::post('/quick-order', [SellerShopController::class, 'quickOrder'])->name('quick.order');
            Route::get('/product/{code}/details', [SellerShopController::class, 'getProductDetails'])
                ->name('product.details')->where('code', '.*');
            Route::get('/product/find/{code}', [SellerShopController::class, 'findByCode'])
                ->name('product.find')->where('code', '.*');
            Route::post('/product/copy/{code}', [SellerShopController::class, 'copyProductCode'])
                ->name('product.copy')->where('code', '.*');
        });

        // Dashboard Stats
        Route::prefix('dashboard')->name('dashboard.')->group(function () {
            Route::get('/stats',         [SellerController::class, 'getStats'])->name('stats');
            Route::get('/chart-data',    [SellerController::class, 'getChartData'])->name('chart-data');
            Route::get('/recent-orders', [SellerController::class, 'getRecentOrders'])->name('recent-orders');
        });

        // Reports
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/sales',         [SellerController::class, 'salesReport'])->name('sales');
            Route::get('/inventory',     [SellerController::class, 'inventoryReport'])->name('inventory');
            Route::get('/customers',     [SellerController::class, 'customerReport'])->name('customers');
            Route::get('/export/{type}', [SellerController::class, 'exportReport'])->name('export');
        });

        // Self-Seller Delivery Management
        // NOTE: Only ONE delivery-calls group — duplicates removed
        Route::prefix('delivery-calls')->name('delivery_calls.')->group(function () {

            // Tracking pages — all before wildcards
            Route::get('/tracking',            [SelfSellerDeliveryCallController::class, 'trackingIndex'])->name('tracking.index');
            Route::get('/tracking/confirmed',  [SelfSellerDeliveryCallController::class, 'trackingConfirmed'])->name('tracking.confirmed');
            Route::get('/tracking/completed',  [SelfSellerDeliveryCallController::class, 'trackingCompleted'])->name('tracking.completed');
            Route::get('/tracking/rescheduled',[SelfSellerDeliveryCallController::class, 'trackingRescheduled'])->name('tracking.rescheduled');
            Route::get('/tracking/cancelled',  [SelfSellerDeliveryCallController::class, 'trackingCancelled'])->name('tracking.cancelled');

            // Sync
            Route::post('/sync-all-waybills',  [SelfSellerDeliveryCallController::class, 'syncAllWaybills'])->name('sync_all_waybills');

            // Pipeline pages
            Route::get('/',           [SelfSellerDeliveryCallController::class, 'index'])->name('index');
            Route::get('/pending',    [SelfSellerDeliveryCallController::class, 'pending'])->name('pending');
            Route::get('/packing',    [SelfSellerDeliveryCallController::class, 'packing'])->name('packing');
            Route::get('/packed',     [SelfSellerDeliveryCallController::class, 'packed'])->name('packed');
            Route::get('/givedriver', [SelfSellerDeliveryCallController::class, 'givedriver'])->name('givedriver');
            Route::get('/create',     [SelfSellerDeliveryCallController::class, 'create'])->name('create');

            // Bulk actions
            Route::post('/bulk-status',  [SelfSellerDeliveryCallController::class, 'bulkUpdateStatus'])->name('bulk_status');
            Route::patch('/bulk-origin', [SelfSellerDeliveryCallController::class, 'bulkUpdateOrigin'])->name('bulk_origin');

            // Business management
            Route::get('/businesses',           [SelfSellerDeliveryCallController::class, 'businessIndex'])->name('businesses');
            Route::post('/business/create',     [SelfSellerDeliveryCallController::class, 'businessCreate'])->name('business_create');
            Route::post('/business/set-active', [SelfSellerDeliveryCallController::class, 'businessSetActive'])->name('business_set_active');
            Route::delete('/business/{id}',     [SelfSellerDeliveryCallController::class, 'businessDelete'])->name('business_delete');

            // AJAX tracking POST
            Route::post('/tracking', [SelfSellerDeliveryCallController::class, 'tracking'])->name('tracking');

            // Store
            Route::post('/', [SelfSellerDeliveryCallController::class, 'store'])->name('store');

            // Wildcard /{id} routes — MUST be last
            Route::post('/{id}/sync-waybill', [SelfSellerDeliveryCallController::class, 'syncWaybill'])->name('sync_waybill')->whereNumber('id');
            Route::get('/{id}',               [SelfSellerDeliveryCallController::class, 'show'])->name('show')->whereNumber('id');
            Route::post('/{id}/status',       [SelfSellerDeliveryCallController::class, 'updateStatus'])->name('update_status')->whereNumber('id');
            Route::patch('/{id}/origin',      [SelfSellerDeliveryCallController::class, 'updateOrigin'])->name('update_origin')->whereNumber('id');
        });
    });
});


// ═══════════════════════════════════════════════════════════════════════════
// EMPLOYEE ROUTES
// ═══════════════════════════════════════════════════════════════════════════
Route::prefix('employee')->middleware('auth:employee')->name('employee.')->group(function () {

    // Auth & Dashboard
    Route::get('/',         [EmployeeController::class, 'dashboard'])->name('dashboard');
    Route::post('/logout',  [LoginController::class, 'logout'])->name('logout');
    Route::get('/profile',  [EmployeeController::class, 'profile'])->name('profile');
    Route::post('/employee/profile/update',        [EmployeeController::class, 'updateProfile'])->name('profile.update');
    Route::get('/employee/profile/data',           [EmployeeController::class, 'getProfileData'])->name('profile.data');
    Route::post('/employee/profile/image/upload',  [EmployeeController::class, 'uploadProfileImage'])->name('profile.image.upload');
    Route::delete('/employee/profile/image/delete',[EmployeeController::class, 'deleteProfileImage'])->name('profile.image.delete');

    // Seller management
    Route::prefix('seller')->name('seller.')->group(function () {
        Route::get('/orders',                        [EmployeeController::class, 'sellerOrders'])->name('orders');
        Route::post('/orders/status/update/{id}',   [EmployeeController::class, 'updateOrderStatus'])->name('orders.status-update');
        Route::get('/orders/view/{id}',             [EmployeeController::class, 'viewOrder'])->name('order-view');
        Route::prefix('order')->name('order.')->group(function () {
            Route::post('/bulk-store', [SellerOrderController::class, 'bulkStore'])->name('bulk-store');
        });
    });

    // Print
    Route::get('/print/{id}', [EmployeeWholesalerOrderController::class, 'printDeliveryLabel'])->name('print.out');

    // Delivery Calls
    // NOTE: static paths before /{id} wildcard
    Route::prefix('delivery-calls')->name('employee.delivery_calls.')->group(function () {

        Route::get('/tracking',            [EmployeeDeliveryCallController::class, 'trackingIndex'])->name('tracking.index');
        Route::get('/tracking/confirmed',  [EmployeeDeliveryCallController::class, 'trackingConfirmed'])->name('tracking.confirmed');
        Route::get('/tracking/completed',  [EmployeeDeliveryCallController::class, 'trackingCompleted'])->name('tracking.completed');
        Route::get('/tracking/rescheduled',[EmployeeDeliveryCallController::class, 'trackingRescheduled'])->name('tracking.rescheduled');
        Route::get('/tracking/cancelled',  [EmployeeDeliveryCallController::class, 'trackingCancelled'])->name('tracking.cancelled');

        Route::post('/sync-all-waybills',  [EmployeeDeliveryCallController::class, 'syncAllWaybills'])->name('sync_all_waybills');
        Route::post('/tracking',           [EmployeeDeliveryCallController::class, 'tracking'])->name('tracking');
        Route::post('/set-business',       [EmployeeDeliveryCallController::class, 'setBusiness'])->name('set_business');
        Route::post('/bulk-status',        [EmployeeDeliveryCallController::class, 'bulkUpdateStatus'])->name('bulk_status');
        Route::patch('/bulk-origin',       [EmployeeDeliveryCallController::class, 'bulkUpdateOrigin'])->name('bulk_origin');

        Route::get('/',           [EmployeeDeliveryCallController::class, 'index'])->name('index');
        Route::get('/pending',    [EmployeeDeliveryCallController::class, 'pending'])->name('pending');
        Route::get('/packing',    [EmployeeDeliveryCallController::class, 'packing'])->name('packing');
        Route::get('/packed',     [EmployeeDeliveryCallController::class, 'packed'])->name('packed');
        Route::get('/givedriver', [EmployeeDeliveryCallController::class, 'givedriver'])->name('givedriver');

        // Wildcard LAST
        Route::post('/{id}/sync-waybill', [EmployeeDeliveryCallController::class, 'syncWaybill'])->name('sync_waybill')->whereNumber('id');
        Route::get('/{id}',               [EmployeeDeliveryCallController::class, 'show'])->name('show')->whereNumber('id');
        Route::post('/{id}/status',       [EmployeeDeliveryCallController::class, 'updateStatus'])->name('update_status')->whereNumber('id');
        Route::patch('/{id}/origin',      [EmployeeDeliveryCallController::class, 'updateOrigin'])->name('update_origin')->whereNumber('id');
    });

    // Wholesaler Module
    Route::prefix('wholesaler')->name('wholesaler.')->group(function () {

        // Products
        Route::prefix('products')->name('products.')->group(function () {
            Route::get('/export',              [App\Http\Controllers\Employee\EmployeeWholesalerProductController::class, 'export'])->name('export');
            Route::post('/bulk-update',        [App\Http\Controllers\Employee\EmployeeWholesalerProductController::class, 'bulkUpdate'])->name('bulk-update');
            Route::post('/bulk-shop-display',  [App\Http\Controllers\Employee\EmployeeWholesalerProductController::class, 'bulkShopDisplay'])->name('bulk-shop-display');
            Route::get('/',                    [App\Http\Controllers\Employee\EmployeeWholesalerProductController::class, 'index'])->name('index');
            Route::post('/{id}/toggle-shop',   [App\Http\Controllers\Employee\EmployeeWholesalerProductController::class, 'toggleShopDisplay'])->name('toggle-shop')->whereNumber('id');
            Route::get('/{id}/price-history',  [App\Http\Controllers\Employee\EmployeeWholesalerProductController::class, 'priceHistory'])->name('price-history')->whereNumber('id');
            Route::get('/{id}',                [App\Http\Controllers\Employee\EmployeeWholesalerProductController::class, 'show'])->name('show')->whereNumber('id');
        });

        // Price Changes
        Route::prefix('price-changes')->name('price-changes.')->group(function () {
            Route::get('/bulk',    [App\Http\Controllers\Employee\EmployeeWholesalerPriceChangeController::class, 'bulkCreate'])->name('bulk.create');
            Route::post('/bulk',   [App\Http\Controllers\Employee\EmployeeWholesalerPriceChangeController::class, 'bulkStore'])->name('bulk.store');
            Route::get('/pending', [App\Http\Controllers\Employee\EmployeeWholesalerPriceChangeController::class, 'pending'])->name('pending');
            Route::get('/history', [App\Http\Controllers\Employee\EmployeeWholesalerPriceChangeController::class, 'history'])->name('history');
            Route::get('/export',  [App\Http\Controllers\Employee\EmployeeWholesalerPriceChangeController::class, 'export'])->name('export');
            Route::get('/stats',   [App\Http\Controllers\Employee\EmployeeWholesalerPriceChangeController::class, 'stats'])->name('stats');
            Route::get('/',        [App\Http\Controllers\Employee\EmployeeWholesalerPriceChangeController::class, 'index'])->name('index');
            Route::get('/create',  [App\Http\Controllers\Employee\EmployeeWholesalerPriceChangeController::class, 'create'])->name('create');
            Route::post('/',       [App\Http\Controllers\Employee\EmployeeWholesalerPriceChangeController::class, 'store'])->name('store');
        });

        // Exchanges
        Route::prefix('exchanges')->name('exchanges.')->group(function () {
            Route::get('/',        [EmployeeWholesalerExchangeController::class, 'index'])->name('index');
            Route::get('/create',  [EmployeeWholesalerExchangeController::class, 'create'])->name('create');
            Route::post('/',       [EmployeeWholesalerExchangeController::class, 'store'])->name('store');
            Route::get('/{id}',    [EmployeeWholesalerExchangeController::class, 'show'])->name('show')->whereNumber('id');
            Route::post('/{id}/cancel', [EmployeeWholesalerExchangeController::class, 'cancel'])->name('cancel')->whereNumber('id');
        });

        // Approvals
        Route::prefix('approvals')->name('approvals.')->group(function () {
            Route::get('/pending',       [App\Http\Controllers\Employee\EmployeeWholesalerApprovalController::class, 'pendingApproval'])->name('pending');
            Route::post('/bulk-approve', [App\Http\Controllers\Employee\EmployeeWholesalerApprovalController::class, 'bulkApprove'])->name('bulk-approve');
            Route::post('/bulk-reject',  [App\Http\Controllers\Employee\EmployeeWholesalerApprovalController::class, 'bulkReject'])->name('bulk-reject');
            Route::post('/{id}/approve', [App\Http\Controllers\Employee\EmployeeWholesalerApprovalController::class, 'approve'])->name('approve')->whereNumber('id');
            Route::post('/{id}/reject',  [App\Http\Controllers\Employee\EmployeeWholesalerApprovalController::class, 'reject'])->name('reject')->whereNumber('id');
        });

        // Order Exchanges
        Route::prefix('order-exchanges')->name('order-exchanges.')->group(function () {
            Route::get('/export',              [EmployeeWholesalerOrderExchangeController::class, 'export'])->name('export');
            Route::get('/stats',               [EmployeeWholesalerOrderExchangeController::class, 'stats'])->name('stats');
            Route::get('/api/orders',          [EmployeeWholesalerOrderExchangeController::class, 'getWholesalerOrders'])->name('api.orders');
            Route::get('/api/order-details',   [EmployeeWholesalerOrderExchangeController::class, 'getOrderDetails'])->name('api.order-details');
            Route::get('/api/products',        [EmployeeWholesalerOrderExchangeController::class, 'getWholesalerProducts'])->name('api.products');
            Route::get('/api/product-details', [EmployeeWholesalerOrderExchangeController::class, 'getProductDetails'])->name('api.product-details');
            Route::get('/',                    [EmployeeWholesalerOrderExchangeController::class, 'index'])->name('index');
            Route::get('/create',              [EmployeeWholesalerOrderExchangeController::class, 'create'])->name('create');
            Route::post('/',                   [EmployeeWholesalerOrderExchangeController::class, 'store'])->name('store');
            Route::get('/{id}',                [EmployeeWholesalerOrderExchangeController::class, 'show'])->name('show')->whereNumber('id');
            Route::post('/{id}/approve',       [EmployeeWholesalerOrderExchangeController::class, 'approve'])->name('approve')->whereNumber('id');
            Route::post('/{id}/reject',        [EmployeeWholesalerOrderExchangeController::class, 'reject'])->name('reject')->whereNumber('id');
            Route::post('/{id}/cancel',        [EmployeeWholesalerOrderExchangeController::class, 'cancel'])->name('cancel')->whereNumber('id');
        });
    });

    // Analytics & Reports
    Route::get('/analytics',                  [EmployeeController::class, 'analytics'])->name('analytics');
    Route::get('/price-changes/history',      [EmployeeController::class, 'priceChangeHistory'])->name('price-changes.history');
    Route::get('/price-changes/export',       [EmployeeController::class, 'exportPriceChanges'])->name('price-changes.export');
    Route::get('/export/report',              [EmployeeController::class, 'exportReport'])->name('export.report');
});


// ═══════════════════════════════════════════════════════════════════════════
// WHOLESALER ROUTES
// ═══════════════════════════════════════════════════════════════════════════
Route::middleware('auth:wholesaler')->prefix('wholesaler')->name('wholesaler.')->group(function () {

    Route::get('/',           [WholesalerController::class, 'dashboard'])->name('dashboard');
    Route::post('/logout',    [LoginController::class, 'logout'])->name('logout');
    Route::get('/storage-info',[WholesalerController::class, 'getStorageInfo'])->name('storage.info');
    Route::get('/profile',    [WholesalerController::class, 'profile'])->name('profile');
    Route::post('/profile/update', [WholesalerController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/avatar', [WholesalerController::class, 'uploadAvatar'])->name('profile.avatar');

    // Shop
    Route::prefix('shop')->name('shop.')->group(function () {
        Route::get('/',        [WholesalerShopController::class, 'index'])->name('index');
        Route::get('/search',  [WholesalerShopController::class, 'search'])->name('search');
        Route::get('/product/id/{id}', [WholesalerShopController::class, 'showProductById'])->name('product.show-by-id');
        Route::get('/api/product/{code}/details', [WholesalerShopController::class, 'getProductDetails'])
            ->name('api.product.details')->where('code', '.*');
        Route::get('/api/product/{code}', [WholesalerShopController::class, 'findByCode'])
            ->name('api.product.find')->where('code', '.*');
        Route::get('/product/{code}', [WholesalerShopController::class, 'showProduct'])
            ->name('product.show')->where('code', '.*');
    });

    // Orders
    Route::prefix('order')->name('order.')->group(function () {
        Route::get('/new',         [WholesalerOrderController::class, 'index'])->name('new');
        Route::get('/pending',     [WholesalerOrderController::class, 'pendingOrder'])->name('pending-order');
        Route::get('/complete',    [WholesalerOrderController::class, 'completeOrder'])->name('complete-order');
        Route::get('/return',      [WholesalerOrderController::class, 'returnOrder'])->name('return-order');
        Route::get('/rescheduling',[WholesalerOrderController::class, 'reschedulingOrder'])->name('rescheduling-order');
        Route::get('/show/{id}',   [WholesalerOrderController::class, 'show'])->name('show');
        Route::get('/product/{code}/details', [WholesalerOrderController::class, 'getProductDetails'])
            ->name('product.details')->where('code', '.*');
        Route::post('/store',      [WholesalerOrderController::class, 'store'])->name('store');
        Route::post('/csv-insert', [WholesalerOrderController::class, 'csvInsert'])->name('csv_insert');
        Route::put('/update/{id}', [WholesalerOrderController::class, 'update'])->name('update');
        Route::delete('/destroy/{id}',[WholesalerOrderController::class, 'destroy'])->name('destroy');
    });

    // Products
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/',          [WholesalerProductController::class, 'index'])->name('index');
        Route::get('/create',    [WholesalerProductController::class, 'create'])->name('create');
        Route::post('/',         [WholesalerProductController::class, 'store'])->name('store');
        Route::get('/{id}',      [WholesalerProductController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [WholesalerProductController::class, 'edit'])->name('edit');
        Route::put('/{id}',      [WholesalerProductController::class, 'update'])->name('update');
        Route::delete('/{id}',   [WholesalerProductController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/status',[WholesalerProductController::class, 'updateStatus'])->name('updateStatus');
    });

    // Brands
    Route::prefix('brands')->name('brands.')->group(function () {
        Route::get('/',             [WholesalerBrandController::class, 'index'])->name('index');
        Route::get('/create',       [WholesalerBrandController::class, 'create'])->name('create');
        Route::post('/',            [WholesalerBrandController::class, 'store'])->name('store');
        Route::get('/{brand}',      [WholesalerBrandController::class, 'show'])->name('show');
        Route::get('/{brand}/edit', [WholesalerBrandController::class, 'edit'])->name('edit');
        Route::put('/{brand}',      [WholesalerBrandController::class, 'update'])->name('update');
        Route::delete('/{brand}',   [WholesalerBrandController::class, 'destroy'])->name('destroy');
    });

    // Categories
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/',          [WholesalerCategoryController::class, 'index'])->name('index');
        Route::get('/create',    [WholesalerCategoryController::class, 'create'])->name('create');
        Route::post('/',         [WholesalerCategoryController::class, 'store'])->name('store');
        Route::get('/{id}',      [WholesalerCategoryController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [WholesalerCategoryController::class, 'edit'])->name('edit');
        Route::put('/{id}',      [WholesalerCategoryController::class, 'update'])->name('update');
        Route::delete('/{id}',   [WholesalerCategoryController::class, 'destroy'])->name('destroy');
    });

    // Payments
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/history',         [WholesalerPaymentController::class, 'paymentHistory'])->name('history');
        Route::get('/export',          [WholesalerPaymentController::class, 'exportPayments'])->name('export');
        Route::get('/withdraw',        [WholesalerPaymentController::class, 'withdraw'])->name('withdraw');
        Route::post('/withdraw',       [WholesalerPaymentController::class, 'processWithdraw'])->name('process-withdraw');
        Route::get('/methods',         [WholesalerPaymentController::class, 'paymentMethods'])->name('methods');
        Route::get('/methods/create',  [WholesalerPaymentController::class, 'createPaymentMethod'])->name('methods.create');
        Route::post('/methods',        [WholesalerPaymentController::class, 'storePaymentMethod'])->name('methods.store');
        Route::get('/gateways',        [WholesalerPaymentController::class, 'gateways'])->name('gateways');
        Route::get('/gateways/create', [WholesalerPaymentController::class, 'createGateway'])->name('gateways.create');
        Route::post('/gateways',       [WholesalerPaymentController::class, 'storeGateway'])->name('gateways.store');
        Route::post('/checkout',       [WholesalerPaymentController::class, 'createCheckoutSession'])->name('checkout');
        Route::get('/success',         [WholesalerPaymentController::class, 'paymentSuccess'])->name('success');
        Route::get('/cancel',          [WholesalerPaymentController::class, 'paymentCancel'])->name('cancel');
        Route::get('/',                [WholesalerPaymentController::class, 'index'])->name('index');
        Route::get('/{id}',            [WholesalerPaymentController::class, 'show'])->name('show');
        Route::post('/withdraw/{id}/cancel',  [WholesalerPaymentController::class, 'cancelWithdraw'])->name('cancel-withdraw');
        Route::get('/methods/{id}/edit',      [WholesalerPaymentController::class, 'editPaymentMethod'])->name('methods.edit');
        Route::put('/methods/{id}',           [WholesalerPaymentController::class, 'updatePaymentMethod'])->name('methods.update');
        Route::delete('/methods/{id}',        [WholesalerPaymentController::class, 'destroyPaymentMethod'])->name('methods.destroy');
        Route::get('/gateways/{id}/test',     [WholesalerPaymentController::class, 'testGateway'])->name('gateways.test');
        Route::delete('/gateways/{id}',       [WholesalerPaymentController::class, 'destroyGateway'])->name('gateways.destroy');
    });
});


// ═══════════════════════════════════════════════════════════════════════════
// WEBHOOK ROUTES
// ═══════════════════════════════════════════════════════════════════════════
Route::prefix('webhook')->name('webhook.')->group(function () {
    Route::post('/stripe',   [WholesalerPaymentController::class, 'webhookHandler'])->name('stripe');
    Route::post('/paypal',   [WholesalerPaymentController::class, 'webhookHandler'])->name('paypal');
    Route::post('/jazzcash', [WholesalerPaymentController::class, 'webhookHandler'])->name('jazzcash');
});
