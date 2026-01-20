@extends('employee.layout.app')

@push('title')
    Wholesaler Cancelled Orders
@endpush

@push('css')
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons|Material+Icons+Outlined" rel="stylesheet">
    <style>
        .page-header {
            background: linear-gradient(135deg, #ef4444, #dc2626);
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
            border-left: 4px solid #ef4444;
        }

        .revenue-card {
            border-left: 4px solid #f59e0b;
        }

        .refund-card {
            border-left: 4px solid #8b5cf6;
        }

        .cancelled-badge {
            background: #fee2e2;
            color: #dc2626;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }

        .reason-text {
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .bg-red-100 { background-color: #fee2e2; }
        .text-red-600 { color: #dc2626; }
        .bg-yellow-100 { background-color: #fef3c7; }
        .text-yellow-600 { color: #d97706; }
        .bg-purple-100 { background-color: #f3e8ff; }
        .text-purple-600 { color: #7c3aed; }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1">Cancelled Orders</h1>
                    <p class="mb-0 opacity-75">Orders that have been cancelled</p>
                </div>
                <a href="{{ route('employee.wholesaler_orders.index') }}" class="btn btn-light">
                    <i class="material-icons-outlined me-1">arrow_back</i> All Orders
                </a>
            </div>
        </div>

        <!-- Statistics -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="stat-card total-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Total Cancelled</h6>
                            <h3 class="mb-0">{{ $totalCancelled ?? 0 }}</h3>
                        </div>
                        <div class="bg-red-100 p-3 rounded">
                            <i class="material-icons-outlined text-red-600" style="font-size: 2rem;">cancel</i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card revenue-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Cancelled Revenue</h6>
                            <h3 class="mb-0">Rs. {{ number_format($cancelledRevenue ?? 0, 2) }}</h3>
                        </div>
                        <div class="bg-yellow-100 p-3 rounded">
                            <i class="material-icons-outlined text-yellow-600" style="font-size: 2rem;">payments</i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card refund-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Refunded Amount</h6>
                            <h3 class="mb-0">Rs. {{ number_format($refundedAmount ?? 0, 2) }}</h3>
                        </div>
                        <div class="bg-purple-100 p-3 rounded">
                            <i class="material-icons-outlined text-purple-600" style="font-size: 2rem;">undo</i>
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
                            <th>Cancelled Date</th>
                            <th>Cancelled By</th>
                            <th>Reason</th>
                            <th>Total Amount</th>
                            <th>Refund Status</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td>
                                    <strong class="text-decoration-line-through text-muted">#{{ $order->order_number }}</strong>
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
                                    @if($order->cancelled_by === 'customer')
                                        <span class="badge bg-info">Customer</span>
                                    @elseif($order->cancelled_by === 'admin')
                                        <span class="badge bg-danger">Admin</span>
                                    @elseif($order->cancelled_by === 'employee')
                                        <span class="badge bg-warning">Employee</span>
                                    @else
                                        <span class="badge bg-secondary">System</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="reason-text" title="{{ $order->notes ?? 'No reason provided' }}">
                                        {{ $order->notes ?? 'No reason provided' }}
                                    </div>
                                </td>
                                <td>
                                    <strong>Rs. {{ number_format($order->total_amount, 2) }}</strong>
                                </td>
                                <td>
                                    @if($order->refund_status === 'refunded')
                                        <span class="badge bg-success">
                                            <i class="material-icons-outlined" style="font-size: 1rem;">check_circle</i> Refunded
                                        </span>
                                    @elseif($order->refund_status === 'pending')
                                        <span class="badge bg-warning">
                                            <i class="material-icons-outlined" style="font-size: 1rem;">pending</i> Pending
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">No Refund</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('employee.wholesaler_orders.show', $order->id) }}"
                                           class="btn btn-outline-primary">
                                            <i class="material-icons-outlined">visibility</i>
                                        </a>
                                        @if($order->refund_status !== 'refunded' && $order->payment_status === 'paid')
                                            <button onclick="processRefund({{ $order->id }})"
                                                    class="btn btn-outline-warning">
                                                <i class="material-icons-outlined">undo</i> Refund
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="material-icons-outlined" style="font-size: 3rem;">cancel</i>
                                        <h5>No Cancelled Orders</h5>
                                        <p>Great! No orders have been cancelled</p>
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
        function processRefund(orderId) {
            if (confirm('Are you sure you want to process refund for this order?')) {
                // Use the correct route
                fetch(`/employee/wholesaler-orders/${orderId}/refund`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert(data.message || 'Refund processed successfully');
                            location.reload(); // Reload page to update status
                        } else {
                            alert(data.message || 'Error processing refund');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Network error occurred');
                    });
            }
        }
    </script>
@endpush
