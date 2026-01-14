@extends('employee.layout.app')

@push('title')
    Pending Wholesaler Orders | Employee Dashboard
@endpush

@push('css')
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    <style>
        .page-header {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: #fff;
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .order-card {
            border-radius: 1rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            border: none;
        }
    </style>
@endpush

@section('content')
    <div class="container py-4">

        {{-- Header --}}
        <div class="page-header d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h2 class="mb-1">Pending Wholesaler Orders</h2>
                <small>Orders awaiting confirmation</small>
            </div>
            <a href="{{ route('employee.wholesaler_orders.index') }}" class="btn btn-light">
                <i class="material-icons-outlined me-1">arrow_back</i> All Orders
            </a>
        </div>

        {{-- Orders --}}
        @if($orders->count())
            <div class="card order-card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle table-hover">
                            <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Wholesaler</th>
                                <th>Customer</th>
                                <th>Date</th>
                                <th>Total</th>
                                <th class="text-end">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td class="fw-semibold">
                                        <a href="{{ route('employee.wholesaler_orders.show', $order->id) }}"
                                           class="text-decoration-none text-primary">
                                            #{{ $order->order_number }}
                                        </a>
                                    </td>
                                    <td>{{ $order->wholesaler->business_name ?? 'N/A' }}</td>
                                    <td>{{ $order->customer_name }}</td>
                                    <td>{{ $order->created_at->format('M d, Y') }}</td>
                                    <td class="fw-bold">Rs. {{ number_format($order->total_amount, 2) }}</td>
                                    <td class="text-end">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('employee.wholesaler_orders.show', $order->id) }}"
                                               class="btn btn-outline-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button onclick="updateStatus({{ $order->id }}, 'confirmed')"
                                                    class="btn btn-success">
                                                <i class="material-icons-outlined">check</i> Confirm
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                {{ $orders->links() }}
            </div>

        @else
            <div class="card order-card">
                <div class="card-body text-center py-5">
                    <i class="material-icons-outlined mb-3" style="font-size: 4rem; color: #94a3b8;">check_circle</i>
                    <h4>No Pending Orders</h4>
                    <p class="text-muted mb-0">All orders are processed! 🎉</p>
                </div>
            </div>
        @endif

    </div>
@endsection

@push('script')
    <script>
        function updateStatus(orderId, newStatus) {
            if (confirm('Confirm this order?')) {
                fetch(`/employee/wholesaler-orders/${orderId}/status`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        status: newStatus,
                        notes: 'Order confirmed by employee'
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert(data.message || 'Failed to update status');
                        }
                    });
            }
        }
    </script>
@endpush
