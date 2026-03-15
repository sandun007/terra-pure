@extends('seller.layout.app')

@push('title')
    Rescheduled Orders
@endpush

@push('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #eab308; --primary-dark: #ca8a04;
            --success: #10b981; --danger: #ef4444;
            --warning: #f59e0b; --info: #3b82f6;
            --dark: #0f172a; --mid: #64748b;
            --border: #e2e8f0;
        }
        body { font-family: 'DM Sans', sans-serif; background: #f8fafc; }

        .page-header {
            background: linear-gradient(135deg,#eab308 0%,#ca8a04 100%);
            color: white;
            padding: 2rem;
            border-radius: 16px;
            margin-bottom: 2rem;
        }
        .page-header h1 { font-size: 28px; font-weight: 800; }
        .order-count {
            background: rgba(255,255,255,.2);
            padding: 8px 20px;
            border-radius: 999px;
            display: inline-block;
        }

        .nav-tabs {
            border: none;
            background: white;
            border-radius: 12px;
            padding: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 24px;
        }
        .nav-tabs .nav-link {
            border: none;
            font-weight: 600;
            color: var(--mid);
            padding: 12px 24px;
            border-radius: 8px;
        }
        .nav-tabs .nav-link.active {
            background: var(--primary);
            color: white;
        }

        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 2px 8px rgba(0,0,0,.05);
        }
        .table thead th {
            background: #f8fafc;
            border-bottom: 2px solid var(--border);
            padding: 1rem;
            font-weight: 700;
            font-size: 13px;
            text-transform: uppercase;
        }
        .table tbody td { padding: 1rem; vertical-align: middle; }
        .badge-rescheduled {
            background: #fef9c3;
            color: #854d0e;
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
        }
        .badge-platform {
            background: rgba(234,179,8,.1);
            color: var(--primary);
            padding: 4px 12px;
            border-radius: 999px;
        }
        .action-btn {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .action-btn.view { background: var(--primary); color: white; }
        .action-btn.edit { background: var(--warning); color: white; }
        .empty-state { text-align: center; padding: 60px 20px; }
        .date-badge {
            background: #f1f5f9;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 12px;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid px-3 px-md-4 px-lg-5 py-4">
        <!-- Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1>
                        <span class="material-icons-round me-2" style="font-size: 32px;">schedule</span>
                        Rescheduled Orders
                    </h1>
                    <p class="mb-0">Orders that have been rescheduled for later delivery</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="order-count">
                        <span class="material-icons-round me-2">shopping_bag</span>
                        {{ $count }} {{ $count === 1 ? 'Order' : 'Orders' }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a href="{{ route('seller.order.pending') }}" class="nav-link">
                    <span class="material-icons-round me-2">pending_actions</span>Pending
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('seller.order.completed') }}" class="nav-link">
                    <span class="material-icons-round me-2">check_circle</span>Completed
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('seller.order.returned') }}" class="nav-link">
                    <span class="material-icons-round me-2">assignment_return</span>Returns
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('seller.order.rescheduled') }}" class="nav-link active">
                    <span class="material-icons-round me-2">event_repeat</span>Rescheduling
                </a>
            </li>
        </ul>

        <!-- Stats -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card p-3">
                    <h6 class="text-muted">Total Rescheduled</h6>
                    <h2 class="fw-bold">{{ $count }}</h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3">
                    <h6 class="text-muted">Total Value</h6>
                    <h2 class="fw-bold">Rs. {{ number_format($orders->sum('total_amount'), 2) }}</h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3">
                    <h6 class="text-muted">Upcoming</h6>
                    <h2 class="fw-bold">{{ $orders->filter(fn($o) => $o->rescheduling_date && \Carbon\Carbon::parse($o->rescheduling_date)->isFuture())->count() }}</h2>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="card">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">Rescheduled Orders List</h5>
            </div>

            @if($count > 0)
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Product</th>
                                <th>Customer</th>
                                <th>Qty</th>
                                <th>Amount</th>
                                <th>Original Date</th>
                                <th>New Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($orders as $order)
                                @php
                                    $newDate = $order->rescheduling_date ? \Carbon\Carbon::parse($order->rescheduling_date) : null;
                                    $isUpcoming = $newDate && $newDate->isFuture();
                                    $isToday = $newDate && $newDate->isToday();
                                @endphp
                                <tr>
                                    <td><strong>{{ $order->order_number }}</strong></td>
                                    <td>
                                        <div>{{ Str::limit($order->product_name, 30) }}</div>
                                        <small class="text-muted">{{ $order->product_code }}</small>
                                    </td>
                                    <td>
                                        <div>{{ $order->customer_name }}</div>
                                        <small class="text-muted">{{ $order->customer_phone }}</small>
                                    </td>
                                    <td>{{ $order->quantity }}</td>
                                    <td><strong class="text-danger">Rs. {{ number_format($order->total_amount, 2) }}</strong></td>
                                    <td>
                                        <span class="date-badge">
                                            {{ $order->delivery_date ? \Carbon\Carbon::parse($order->delivery_date)->format('M d, Y') : 'N/A' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge-rescheduled">
                                            {{ $newDate ? $newDate->format('M d, Y') : 'N/A' }}
                                        </span>
                                        @if($isToday)
                                            <span class="badge bg-warning text-white ms-1">Today</span>
                                        @elseif($isUpcoming)
                                            <span class="badge bg-info text-white ms-1">Upcoming</span>
                                        @elseif($newDate && $newDate->isPast())
                                            <span class="badge bg-danger text-white ms-1">Overdue</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge-rescheduled">Rescheduled</span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('seller.order.show', $order->id) }}" class="action-btn view" title="View">
                                                <span class="material-icons-round">visibility</span>
                                            </a>
                                            <button onclick="editOrder({{ $order->id }})" class="action-btn edit" title="Edit">
                                                <span class="material-icons-round">edit</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="card-body">
                    <div class="empty-state">
                        <span class="material-icons-round" style="font-size: 64px; color: #cbd5e1;">inbox</span>
                        <h5>No Rescheduled Orders</h5>
                        <p class="text-muted">You don't have any rescheduled orders.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('script')
    <script>
        function editOrder(id) {
            window.location.href = `/seller/order/update/${id}/edit`;
        }
    </script>
@endpush
