@extends('wholesaler.layouts.app')

@push('title')
    Completed Orders | Wholesaler Dashboard
@endpush

@push('css')
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">

    <style>
        .page-header {
            background: linear-gradient(135deg, #22c55e, #15803d);
            color: #fff;
            border-radius: 1rem;
            padding: 1.75rem;
            margin-bottom: 1.5rem;
        }

        .revenue-box {
            background: rgba(255,255,255,0.15);
            padding: 0.75rem 1rem;
            border-radius: 0.75rem;
            font-size: 0.95rem;
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

        .status-pill {
            padding: 0.35rem 0.7rem;
            border-radius: 999px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 5px;
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
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
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
                <h2 class="mb-1">Completed Orders</h2>
                <div class="revenue-box mt-2">
                    Total Revenue: <strong>Rs. {{ number_format($totalRevenue, 2) }}</strong>
                </div>
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
                                <th>Completed On</th>
                                <th>Total</th>
                                <th>Payment</th>
                                <th class="text-end">Invoice</th>
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
                                    <span class="status-pill {{ $order->payment_status === 'paid' ? 'status-paid' : 'status-pending' }}">
                                        <i class="material-icons-outlined" style="font-size:16px">
                                            {{ $order->payment_status === 'paid' ? 'check_circle' : 'schedule' }}
                                        </i>
                                        {{ ucfirst($order->payment_status) }}
                                    </span>
                                    </td>

                                    <td class="text-end">
                                        <a href="{{ route('wholesaler.orders.invoice.download', $order->id) }}"
                                           class="btn btn-outline-primary action-btn"
                                           title="Download Invoice">
                                            <i class="material-icons-outlined">download</i>
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
                    <i class="material-icons-outlined mb-3">check_circle</i>
                    <h4>No Completed Orders</h4>
                    <p class="text-muted mb-0">
                        Completed orders will appear here once delivered.
                    </p>
                </div>
            </div>
        @endif

    </div>
@endsection
