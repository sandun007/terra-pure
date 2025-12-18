@extends('wholesaler.layouts.app')

@push('title')
    Shipped Orders | Wholesaler Dashboard
@endpush

@push('css')
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">

    <style>
        .page-header {
            background: linear-gradient(135deg, #22c55e, #16a34a);
            color: #fff;
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .order-card {
            border-radius: 1rem;
            border: none;
            box-shadow: 0 10px 25px rgba(0,0,0,0.06);
        }

        .table th {
            font-size: 0.8rem;
            text-transform: uppercase;
            color: #6b7280;
        }

        .address-pill {
            background: #f1f5f9;
            color: #334155;
            padding: 0.4rem 0.6rem;
            border-radius: 0.5rem;
            font-size: 0.75rem;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .action-btn {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .empty-state i {
            font-size: 4rem;
            color: #94a3b8;
        }
    </style>
@endpush

@section('content')
    <div class="container py-4">

        {{-- Header --}}
        <div class="page-header d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h2 class="mb-1">Shipped Orders</h2>
                <small>Orders sent to customers</small>
            </div>
            <a href="{{ route('wholesaler.orders.index') }}" class="btn btn-light mt-2 mt-md-0">
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
                                <th>Order</th>
                                <th>Customer</th>
                                <th>Shipped On</th>
                                <th>Total</th>
                                <th>Address</th>
                                <th class="text-end">Action</th>
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

                                    <td class="fw-semibold">
                                        {{ $order->customer_name }}
                                    </td>

                                    <td>
                                        {{ $order->updated_at->format('d M Y') }}
                                    </td>

                                    <td class="fw-bold">
                                        Rs. {{ number_format($order->total_amount, 2) }}
                                    </td>

                                    <td>
                                    <span class="address-pill">
                                        <i class="material-icons-outlined" style="font-size:16px">location_on</i>
                                        {{ Str::limit($order->shipping_address, 30) }}
                                    </span>
                                    </td>

                                    <td class="text-end">
                                        <button onclick="markAsDelivered({{ $order->id }})"
                                                class="btn btn-primary action-btn"
                                                title="Mark as Delivered">
                                            <i class="material-icons-outlined">done_all</i>
                                        </button>
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
                    <i class="material-icons-outlined mb-3">local_shipping</i>
                    <h4>No Shipped Orders</h4>
                    <p class="text-muted mb-0">
                        Shipped orders will appear here once dispatched.
                    </p>
                </div>
            </div>
        @endif

    </div>
@endsection

@push('script')
    <script>
        function markAsDelivered(orderId) {
            if (!confirm('Mark this order as delivered?')) return;

            fetch(`/wholesaler/orders/${orderId}/status`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    status: 'completed',
                    notes: 'Order delivered to customer'
                })
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Unable to update order status.');
                    }
                });
        }
    </script>
@endpush
