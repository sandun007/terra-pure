@extends('employee.layout.app')

@push('title')
    Wholesaler Shipped Orders
@endpush

@push('css')
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons|Material+Icons+Outlined" rel="stylesheet">
    <style>
        .page-header {
            background: linear-gradient(135deg, #10b981, #059669);
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
            border-left: 4px solid #10b981;
        }

        .shipped-badge {
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

        .tracking-info {
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
                    <h1 class="h3 mb-1">Shipped Orders</h1>
                    <p class="mb-0 opacity-75">Orders in transit to customers</p>
                </div>
                <a href="{{ route('employee.wholesaler_orders.index') }}" class="btn btn-light">
                    <i class="material-icons-outlined me-1">arrow_back</i> All Orders
                </a>
            </div>
        </div>

        <!-- Statistics -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">In Transit</h6>
                            <h3 class="mb-0">{{ $orders->total() }}</h3>
                        </div>
                        <div class="bg-green-100 p-3 rounded">
                            <i class="material-icons-outlined text-green-600" style="font-size: 2rem;">local_shipping</i>
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
                            <th>Shipped Date</th>
                            <th>Tracking Info</th>
                            <th>Items</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($orders as $order)
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
                                <td>
                                    {{ $order->updated_at->format('M d, Y') }}
                                </td>
                                <td>
                                    @if($order->tracking_number)
                                        <div class="tracking-info">{{ $order->tracking_number }}</div>
                                    @else
                                        <span class="text-muted">No tracking</span>
                                    @endif
                                </td>
                                <td>{{ $order->items->count() }} items</td>
                                <td>
                                    <strong>Rs. {{ number_format($order->total_amount, 2) }}</strong>
                                </td>
                                <td>
                                        <span class="shipped-badge">
                                            <i class="material-icons-outlined" style="font-size: 1rem;">local_shipping</i>
                                            Shipped
                                        </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('employee.wholesaler_orders.show', $order->id) }}"
                                           class="btn btn-outline-primary">
                                            <i class="material-icons-outlined">visibility</i>
                                        </a>
                                        <button onclick="markAsDelivered({{ $order->id }})"
                                                class="btn btn-outline-success">
                                            <i class="material-icons-outlined">check_circle</i> Deliver
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="material-icons-outlined" style="font-size: 3rem;">local_shipping</i>
                                        <h5>No Shipped Orders</h5>
                                        <p>No orders have been shipped yet</p>
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
        function markAsDelivered(orderId) {
            if (confirm('Mark this order as delivered?')) {
                fetch(`/employee/wholesaler/orders/status/update/${orderId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        status: 'completed',
                        notes: 'Order delivered to customer'
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('Error updating status');
                        }
                    });
            }
        }
    </script>
@endpush
