@extends('wholesaler.layouts.app')

@push('title')
    Cancelled Orders | Wholesaler Dashboard
@endpush

@push('css')
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">

    <style>
        .page-header {
            background: linear-gradient(135deg, #ef4444, #b91c1c);
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

        .reason-pill {
            background: #fee2e2;
            color: #7f1d1d;
            padding: 0.35rem 0.6rem;
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
                <h2 class="mb-1">Cancelled Orders</h2>
                <small>Orders that were cancelled by customer or admin</small>
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
                                <th>Cancelled On</th>
                                <th>Total</th>
                                <th>Reason</th>
                                <th class="text-end">View</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td class="fw-semibold text-decoration-line-through text-muted">
                                        #{{ $order->order_number }}
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
                                    <span class="reason-pill">
                                        <i class="material-icons-outlined" style="font-size:16px">info</i>
                                        {{ $order->notes ? Str::limit($order->notes, 30) : 'No reason provided' }}
                                    </span>
                                    </td>

                                    <td class="text-end">
                                        <a href="{{ route('wholesaler.orders.show', $order->id) }}"
                                           class="btn btn-outline-secondary action-btn"
                                           title="View Order">
                                            <i class="material-icons-outlined">visibility</i>
                                        </a>
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
                    <i class="material-icons-outlined mb-3">block</i>
                    <h4>No Cancelled Orders</h4>
                    <p class="text-muted mb-0">
                        That’s great! No orders have been cancelled.
                    </p>
                </div>
            </div>
        @endif

    </div>
@endsection
