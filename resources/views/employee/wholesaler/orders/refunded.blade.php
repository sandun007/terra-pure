@extends('employee.layout.app')

@push('title')
    Wholesaler Refunded Orders
@endpush

@push('css')
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons|Material+Icons+Outlined" rel="stylesheet">
    <style>
        .page-header {
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
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

        .total-card {
            border-left: 4px solid #8b5cf6;
        }

        .amount-card {
            border-left: 4px solid #ef4444;
        }

        .time-card {
            border-left: 4px solid #f59e0b;
        }

        .month-card {
            border-left: 4px solid #10b981;
        }

        .refunded-badge {
            background: #f3e8ff;
            color: #7c3aed;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }

        .refund-id {
            font-family: monospace;
            background: #f3f4f6;
            padding: 0.25rem 0.5rem;
            border-radius: 0.375rem;
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
                    <h1 class="h3 mb-1">Refunded Orders</h1>
                    <p class="mb-0 opacity-75">Orders that have been refunded to customers</p>
                </div>
                <a href="{{ route('employee.wholesaler.orders.cancelled') }}" class="btn btn-light">
                    <i class="material-icons-outlined me-1">arrow_back</i> Cancelled Orders
                </a>
            </div>
        </div>

        <!-- Statistics -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stat-card total-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Total Refunded</h6>
                            <h3 class="mb-0">{{ $totalRefunded }}</h3>
                        </div>
                        <div class="bg-purple-100 p-3 rounded">
                            <i class="material-icons-outlined text-purple-600" style="font-size: 2rem;">undo</i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card amount-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Refunded Amount</h6>
                            <h3 class="mb-0">Rs. {{ number_format($totalRefundAmount, 2) }}</h3>
                        </div>
                        <div class="bg-red-100 p-3 rounded">
                            <i class="material-icons-outlined text-red-600" style="font-size: 2rem;">payments</i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card time-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Avg. Processing Time</h6>
                            <h3 class="mb-0">{{ $avgProcessingHours }} hours</h3>
                        </div>
                        <div class="bg-yellow-100 p-3 rounded">
                            <i class="material-icons-outlined text-yellow-600" style="font-size: 2rem;">schedule</i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card month-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">This Month</h6>
                            <h3 class="mb-0">Rs. {{ number_format($monthlyRefunds, 2) }}</h3>
                        </div>
                        <div class="bg-green-100 p-3 rounded">
                            <i class="material-icons-outlined text-green-600" style="font-size: 2rem;">calendar_today</i>
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
                            <th>Refund Date</th>
                            <th>Refund Method</th>
                            <th>Refund Amount</th>
                            <th>Original Amount</th>
                            <th>Refund ID</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td>
                                    <strong class="text-decoration-line-through">#{{ $order->order_number }}</strong>
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
                                            {{ ucfirst($order->refund_method ?? 'original') }}
                                        </span>
                                </td>
                                <td>
                                    <strong class="text-danger">Rs. {{ number_format($order->refund_amount, 2) }}</strong>
                                </td>
                                <td>
                                    <del class="text-muted">Rs. {{ number_format($order->total_amount, 2) }}</del>
                                </td>
                                <td>
                                    @if($order->refund_reference)
                                        <div class="refund-id">{{ $order->refund_reference }}</div>
                                    @else
                                        <span class="text-muted">No reference</span>
                                    @endif
                                </td>
                                <td>
                                    @if($order->refund_status === 'completed')
                                        <span class="refunded-badge">
                                                <i class="material-icons-outlined" style="font-size: 1rem;">check_circle</i>
                                                Completed
                                            </span>
                                    @elseif($order->refund_status === 'pending')
                                        <span class="badge bg-warning">
                                                <i class="material-icons-outlined" style="font-size: 1rem;">pending</i>
                                                Pending
                                            </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('employee.wholesaler.order-view', $order->id) }}"
                                           class="btn btn-outline-primary">
                                            <i class="material-icons-outlined">visibility</i>
                                        </a>
                                        <a href="{{ route('employee.wholesaler.orders.refund-details', $order->id) }}"
                                           class="btn btn-outline-info">
                                            <i class="material-icons-outlined">receipt</i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="material-icons-outlined" style="font-size: 3rem;">undo</i>
                                        <h5>No Refunded Orders</h5>
                                        <p>No orders have been refunded yet</p>
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
        // Any additional JavaScript for refunded orders
    </script>
@endpush
