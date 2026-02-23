@extends('employee.layout.app')

@push('title')
    Wholesaler Processing Orders
@endpush

@push('css')
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons|Material+Icons+Outlined" rel="stylesheet">
    <style>
        .page-header {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
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
            border-left: 4px solid #3b82f6;
        }

        .processing-badge {
            background: #dbeafe;
            color: #1d4ed8;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1">Processing Orders</h1>
                    <p class="mb-0 opacity-75">Orders being prepared for shipment</p>
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
                            <h6 class="text-muted mb-1">In Processing</h6>
                            <h3 class="mb-0">{{ $orders->total() }}</h3>
                        </div>
                        <div class="bg-blue-100 p-3 rounded">
                            <i class="material-icons-outlined text-blue-600" style="font-size: 2rem;">settings</i>
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
                            <th>Processing Since</th>
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
                                    {{ $order->updated_at->diffForHumans() }}
                                </td>
                                <td>{{ $order->items->count() }} items</td>
                                <td>
                                    <strong>Rs. {{ number_format($order->total_amount, 2) }}</strong>
                                </td>
                                <td>
                                        <span class="processing-badge">
                                            <i class="material-icons-outlined" style="font-size: 1rem;">settings</i>
                                            Processing
                                        </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('employee.wholesaler_orders.show', $order->id) }}"
                                           class="btn btn-outline-primary">
                                            <i class="material-icons-outlined">visibility</i>
                                        </a>
                                        <button onclick="markAsShipped({{ $order->id }})"
                                                class="btn btn-outline-success">
                                            <i class="material-icons-outlined">local_shipping</i> Ship
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="material-icons-outlined" style="font-size: 3rem;">settings</i>
                                        <h5>No Orders in Processing</h5>
                                        <p>All orders are either pending or shipped</p>
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
        function markAsShipped(orderId) {
            if (confirm('Mark this order as shipped?')) {
                // AJAX call to update status
                fetch(`/employee/wholesaler/orders/status/update/${orderId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        status: 'shipped',
                        notes: 'Order shipped by employee'
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
