@extends('employee.layout.app')

@push('title')
    Weekly Wholesaler Orders
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
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .stats-card {
            background: white;
            border-radius: 0.75rem;
            padding: 1.25rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            margin-bottom: 1.5rem;
        }

        .stats-value {
            font-size: 1.75rem;
            font-weight: 800;
            color: #1e293b;
            margin-bottom: 0.25rem;
        }

        .stats-label {
            font-size: 0.8rem;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid py-4">
        {{-- Header --}}
        <div class="page-header d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h2 class="mb-2 fw-bold">📅 Weekly Wholesaler Orders</h2>
                <p class="mb-0 text-white-80">
                    Orders placed this week ({{ \Carbon\Carbon::now()->startOfWeek()->format('M d') }} - {{ \Carbon\Carbon::now()->endOfWeek()->format('M d, Y') }})
                </p>
            </div>
            <div class="d-flex gap-3 mt-2 mt-md-0 align-items-center flex-wrap">
                <a href="{{ route('employee.wholesaler_orders.index') }}" class="btn btn-light">
                    <i class="material-icons-outlined">arrow_back</i>
                    All Orders
                </a>
            </div>
        </div>

        {{-- Stats --}}
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="stats-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stats-label">Weekly Orders</div>
                            <div class="stats-value">{{ number_format($weeklyCount) }}</div>
                        </div>
                        <div class="bg-purple-100 p-3 rounded">
                            <i class="material-icons-outlined text-purple-600" style="font-size: 2rem;">date_range</i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="stats-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stats-label">Weekly Revenue</div>
                            <div class="stats-value">Rs. {{ number_format($weeklyRevenue, 2) }}</div>
                        </div>
                        <div class="bg-green-100 p-3 rounded">
                            <i class="material-icons-outlined text-green-600" style="font-size: 2rem;">payments</i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Orders Table --}}
        @if($orders->count())
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Wholesaler</th>
                                <th>Customer</th>
                                <th>Date</th>
                                <th>Day</th>
                                <th>Items</th>
                                <th>Total Amount</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td>
                                        <a href="{{ route('employee.wholesaler_orders.show', $order->id) }}"
                                           class="text-decoration-none text-primary fw-bold">
                                            #{{ $order->order_number }}
                                        </a>
                                    </td>
                                    <td>{{ $order->wholesaler->business_name ?? 'N/A' }}</td>
                                    <td>{{ $order->customer_name }}</td>
                                    <td>{{ $order->created_at->format('M d') }}</td>
                                    <td>{{ $order->created_at->format('D') }}</td>
                                    <td>{{ $order->items->count() }} items</td>
                                    <td class="fw-bold text-success">
                                        Rs. {{ number_format($order->total_amount, 2) }}
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $order->status == 'pending' ? 'warning' : ($order->status == 'completed' ? 'success' : ($order->status == 'cancelled' ? 'danger' : 'info')) }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('employee.wholesaler_orders.show', $order->id) }}"
                                               class="btn btn-outline-info">
                                                <i class="material-icons-outlined">visibility</i>
                                            </a>
                                            <a href="{{ route('employee.print.out', $order->id) }}" target="_blank"
                                               class="btn btn-outline-warning">
                                                <i class="material-icons-outlined">print</i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Pagination --}}
            <div class="mt-4">
                {{ $orders->links() }}
            </div>

        @else
            {{-- Empty State --}}
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="material-icons-outlined mb-3" style="font-size: 4rem; color: #94a3b8;">date_range</i>
                    <h4 class="mb-2">No Orders This Week</h4>
                    <p class="text-muted mb-4">No wholesaler orders have been placed this week.</p>
                    <a href="{{ route('employee.wholesaler_orders.index') }}" class="btn btn-primary">
                        <i class="material-icons-outlined me-2">arrow_back</i>
                        View All Orders
                    </a>
                </div>
            </div>
        @endif

    </div>
@endsection
