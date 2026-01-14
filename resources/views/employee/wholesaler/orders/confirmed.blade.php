@extends('employee.layout.app')

@push('title')
    Confirmed Wholesaler Orders | Employee Dashboard
@endpush

@push('css')
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    <style>
        .page-header {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: #fff;
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
    </style>
@endpush

@section('content')
    <div class="container py-4">
        <div class="page-header d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h2 class="mb-1">Confirmed Wholesaler Orders</h2>
                <small>Orders ready for processing</small>
            </div>
            <a href="{{ route('employee.wholesaler_orders.index') }}" class="btn btn-light">
                <i class="material-icons-outlined me-1">arrow_back</i> All Orders
            </a>
        </div>

        @if($orders->count())
            <div class="card">
                <div class="card-body">
                    <table class="table table-hover">
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
                                <td>
                                    <a href="{{ route('employee.wholesaler_orders.show', $order->id) }}">
                                        #{{ $order->order_number }}
                                    </a>
                                </td>
                                <td>{{ $order->wholesaler->business_name ?? 'N/A' }}</td>
                                <td>{{ $order->customer_name }}</td>
                                <td>{{ $order->created_at->format('M d, Y') }}</td>
                                <td>Rs. {{ number_format($order->total_amount, 2) }}</td>
                                <td class="text-end">
                                    <button onclick="updateStatus({{ $order->id }}, 'processing')"
                                            class="btn btn-info btn-sm">
                                        Start Processing
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {{ $orders->links() }}
                </div>
            </div>
        @else
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="material-icons-outlined mb-3" style="font-size: 4rem; color: #94a3b8;">inventory_2</i>
                    <h4>No Confirmed Orders</h4>
                    <p class="text-muted mb-0">No confirmed orders at the moment</p>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('script')
    <script>
        function updateStatus(orderId, newStatus) {
            if (confirm('Start processing this order?')) {
                fetch(`/employee/wholesaler-orders/${orderId}/status`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        status: newStatus,
                        notes: 'Order processing started by employee'
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
