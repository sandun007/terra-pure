@extends('wholesaler.layouts.app')

@push('title')
    Pending Orders | Wholesaler Dashboard
@endpush

@push('css')
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    <style>
        .page-header {
            background: linear-gradient(135deg, #4f46e5, #3b82f6);
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

        .table th {
            font-size: 0.85rem;
            text-transform: uppercase;
            color: #6b7280;
        }

        .status-pill {
            padding: 0.35rem 0.7rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .status-paid {
            background: #dcfce7;
            color: #166534;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .action-btn {
            width: 34px;
            height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }

        .empty-state i {
            font-size: 4rem;
            color: #22c55e;
        }
    </style>
@endpush

@section('content')
    <div class="container py-4">

        {{-- Header --}}
        <div class="page-header d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h2 class="mb-1">Pending Orders</h2>
                <small>Manage and confirm customer orders</small>
            </div>
            <div class="d-flex gap-2 mt-2 mt-md-0">
            <span class="badge bg-danger fs-6 px-3 py-2">
                {{ $orders->total() }} Pending
            </span>
                <a href="{{ route('wholesaler.orders.index') }}" class="btn btn-light">
                    <i class="material-icons-outlined me-1">arrow_back</i> All Orders
                </a>
            </div>
        </div>

        {{-- Orders --}}
        @if($orders->count())
            <div class="card order-card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle table-hover">
                            <thead>
                            <tr>
                                <th>Order</th>
                                <th>Customer</th>
                                <th>Date</th>
                                <th>Total</th>
                                <th>Payment</th>
                                <th class="text-end">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td class="fw-semibold">
                                        <a href="{{ route('wholesaler.orders.show', $order->id) }}"
                                           class="text-decoration-none text-primary">
                                            #{{ $order->order_number }}
                                        </a>
                                    </td>

                                    <td>
                                        <div class="fw-semibold">{{ $order->customer_name }}</div>
                                        <small class="text-muted">{{ $order->customer_email }}</small>
                                    </td>

                                    <td>{{ $order->created_at->format('d M Y') }}</td>

                                    <td class="fw-bold">
                                        Rs. {{ number_format($order->total_amount, 2) }}
                                    </td>

                                    <td>
                                    <span class="status-pill {{ $order->payment_status === 'paid' ? 'status-paid' : 'status-pending' }}">
                                        <i class="material-icons-outlined" style="font-size:16px">
                                            {{ $order->payment_status === 'paid' ? 'check_circle' : 'schedule' }}
                                        </i>
                                        {{ ucfirst($order->payment_status) }}
                                    </span>
                                    </td>

                                    <td class="text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('wholesaler.orders.show', $order->id) }}"
                                               class="btn btn-outline-primary action-btn"
                                               title="View Order">
                                                <i class="material-icons-outlined">visibility</i>
                                            </a>

                                            <button onclick="confirmOrder({{ $order->id }})"
                                                    class="btn btn-success action-btn"
                                                    title="Confirm Order">
                                                <i class="material-icons-outlined">check</i>
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
            {{-- Empty State --}}
            <div class="card order-card">
                <div class="card-body text-center py-5 empty-state">
                    <i class="material-icons-outlined mb-3">check_circle</i>
                    <h4>No Pending Orders</h4>
                    <p class="text-muted mb-0">You're all caught up 🎉</p>
                </div>
            </div>
        @endif

    </div>
@endsection

@push('script')
    <script>
        function confirmOrder(orderId) {
            if (!confirm('Are you sure you want to confirm this order?')) return;

            fetch(`/wholesaler/orders/${orderId}/status`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    status: 'confirmed',
                    notes: 'Order confirmed from pending page'
                })
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Something went wrong!');
                    }
                });
        }
    </script>
@endpush
