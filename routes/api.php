<?php

use App\Http\Controllers\Employee\EmployeeWholesalerOrderController;
use Illuminate\Support\Facades\Route;

Route::prefix('employee')->middleware(['auth:employee', 'api'])->group(function() {
    // API route for loading orders
    Route::get('/orders', function(Request $request) {
        try {
            $employee = auth()->guard('employee')->user();

            $query = \App\Models\WholesalerOrder::with(['wholesaler', 'items'])
                ->latest();

            // Apply filters
            if ($request->has('type') && $request->type !== 'all') {
                if ($request->type === 'wholesaler') {
                    // Already querying wholesaler orders
                }
                // Add other types if needed
            }

            if ($request->has('status') && $request->status !== 'all') {
                $query->where('status', $request->status);
            }

            $orders = $query->limit(20)->get();

            return response()->json([
                'success' => true,
                'orders' => $orders,
                'count' => $orders->count()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading orders'
            ], 500);
        }
    });
});
