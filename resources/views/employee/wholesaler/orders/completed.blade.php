@extends('employee.layout.app')

@push('title')
    Wholesaler Completed Orders
@endpush

@push('css')
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons|Material+Icons+Outlined" rel="stylesheet">
    <style>
        .page-header {
            background: linear-gradient(135deg, #22c55e, #16a34a);
            color: #fff;
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .stat-card {
            background: white;
            border-radius: 0.75rem;
            padding: 1.25rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .revenue-card {
            border-left: 4px solid #22c55e;
        }

        .count-card {
            border-left: 4px solid #3b82f6;
        }

        .month-card {
            border-left: 4px solid #f59e0b;
        }

        .avg-card {
            border-left: 4px solid #8b5cf6;
        }

        .completed-badge {
            background: #dcfce7;
            color: #166534;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }

        .profit-text {
            font-size: 0.875rem;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1">Completed Orders</h1>
                    <p class="mb-0 opacity-75">Successfully delivered orders</p>
                </div>
                <a href="{{ route('employee.wholesaler_orders.index') }}" class="btn btn-light">
                    <i class="material-icons-outlined me-1">arrow_back</i> All Orders
                </a>
            </div>
        </div>

        <!-- Statistics -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stat-card revenue-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Total Revenue</h6>
                            <h3 class="mb-0">Rs. {{ number_format($totalRevenue, 2) }}</h3>
                        </div>
                        <div class="bg-green-100 p-3 rounded">
                            <i class="material-icons-outlined text-green-600" style="font-size: 2rem;">payments</i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card count-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Completed Orders</h6>
                            <h3 class="mb-0">{{ $orders->total() }}</h3>
                        </div>
                        <div class="bg-blue-100 p-3 rounded">
                            <i class="material-icons-outlined text-blue-600" style="font-size: 2rem;">check_circle</i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card month-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">This Month</h6>
                            <h3 class="mb-0">{{ $monthlyCompleted }}</h3>
                        </div>
                        <div class="bg-yellow-100 p-3 rounded">
                            <i class="material-icons-outlined text-yellow-600" style="font-size: 2rem;">calendar_today</i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card avg-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Avg. Delivery</h6>
                            <h3 class="mb-0">{{ $avgDeliveryDays }} days</h3>
                        </div>
                        <div class="bg-purple-100 p-3 rounded">
                            <i class="material-icons-outlined text-purple-600" style="font-size: 2rem;">schedule</i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Wholesaler</th>
                            <th>Customer</th>
                            <th>Order Date</th>
                            <th>Completed Date</th>
                            <th>Delivery Time</th>
                            <th>Items</th>
                            <th>Total Amount</th>
                            <th>Profit Margin</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($orders as $order)
                            @php
                                // Calculate profit for this order
                                $totalWholesalerCost = 0;
                                $totalEmployeePrice = 0;
                                foreach($order->items as $item) {
                                    $product = $item->product;
                                    $wholesalerPrice = $product->wholesaler_price ?? $item->unit_price;
                                    $employeePrice = $product->employee_price ?? $wholesalerPrice;
                                    $totalWholesalerCost += $wholesalerPrice * $item->quantity;
                                    $totalEmployeePrice += $employeePrice * $item->quantity;
                                }
                                $profit = $totalEmployeePrice - $totalWholesalerCost;
                                $profitMargin = $totalWholesalerCost > 0 ? ($profit / $totalWholesalerCost) * 100 : 0;
                            @endphp
                            <tr>
                                <td>
                                    <strong>#{{ $order->order_number }}</strong>
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $order->wholesaler->shop_name ?? 'N/A' }}</div>
                                    <small class="text-muted">{{ $order->wholesaler->name ?? '' }}</small>
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $order->customer_name }}</div>
                                    <small class="text-muted">{{ $order->customer_email }}</small>
                                </td>
                                <td>{{ $order->created_at->format('M d, Y') }}</td>
                                <td>{{ $order->updated_at->format('M d, Y') }}</td>
                                <td>
                                        <span class="badge bg-light text-dark">
                                            {{ $order->created_at->diffInDays($order->updated_at) }} days
                                        </span>
                                </td>
                                <td>{{ $order->items->count() }} items</td>
                                <td>
                                    <strong>Rs. {{ number_format($order->total_amount, 2) }}</strong>
                                </td>
                                <td>
                                        <span class="{{ $profit >= 0 ? 'text-success' : 'text-danger' }} profit-text">
                                            <strong>{{ $profit >= 0 ? '+' : '' }}Rs. {{ number_format($profit, 2) }}</strong>
                                            <br>
                                            <small>({{ number_format($profitMargin, 2) }}%)</small>
                                        </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('employee.wholesaler_orders.show', $order->id) }}"
                                           class="btn btn-outline-primary">
                                            <i class="material-icons-outlined">visibility</i>
                                        </a>
                                        <a href="{{ route('wholesaler.orders.invoice.download', $order->id) }}"
                                           class="btn btn-outline-secondary">
                                            <i class="material-icons-outlined">download</i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="material-icons-outlined" style="font-size: 3rem;">check_circle</i>
                                        <h5>No Completed Orders</h5>
                                        <p>No orders have been marked as completed yet</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($orders->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $orders->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        // Any additional JavaScript for completed orders
    </script>
@endpush
