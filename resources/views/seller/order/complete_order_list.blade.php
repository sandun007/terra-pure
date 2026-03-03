@extends('seller.layout.app')

@push('title')
    Completed Orders
@endpush

@push('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <style>
        :root {
            --primary: #10b981; --primary-dark: #059669;
            --success: #10b981; --danger: #ef4444;
            --warning: #f59e0b; --info: #3b82f6;
            --dark: #0f172a; --mid: #64748b;
            --border: #e2e8f0; --surface: #ffffff; --bg: #f8fafc;
        }
        body { font-family: 'DM Sans', sans-serif; background: var(--bg); }

        .page-header {
            background: linear-gradient(135deg,#10b981 0%,#059669 100%);
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
            background: var(--bg);
            border-bottom: 2px solid var(--border);
            padding: 1rem;
            font-weight: 700;
            font-size: 13px;
            text-transform: uppercase;
        }
        .table tbody td { padding: 1rem; vertical-align: middle; }
        .badge-platform {
            background: rgba(16,185,129,.1);
            color: var(--primary);
            padding: 4px 12px;
            border-radius: 999px;
        }
        .badge-call-status {
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
        }
        .badge-call-status.confirmed { background:#d1fae5; color:#065f46; }
        .badge-call-status.called { background:#dbeafe; color:#1e40af; }
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
        .action-btn.print { background: var(--warning); color: white; }
        .empty-state { text-align: center; padding: 60px 20px; }
    </style>
@endpush

@section('content')
    <div class="container-fluid px-3 px-md-4 px-lg-5 py-4">
        <!-- Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1>
                        <span class="material-icons-round me-2" style="font-size: 32px;">check_circle</span>
                        Completed Orders
                    </h1>
                    <p class="mb-0">Successfully delivered and completed orders</p>
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
                <a href="{{ route('seller.order.completed') }}" class="nav-link active">
                    <span class="material-icons-round me-2">check_circle</span>Completed
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('seller.order.returned') }}" class="nav-link">
                    <span class="material-icons-round me-2">assignment_return</span>Returns
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('seller.order.rescheduled') }}" class="nav-link">
                    <span class="material-icons-round me-2">event_repeat</span>Rescheduling
                </a>
            </li>
        </ul>

        <!-- Stats -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card p-3">
                    <h6 class="text-muted">Total Completed</h6>
                    <h2 class="fw-bold">{{ $count }}</h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3">
                    <h6 class="text-muted">Total Revenue</h6>
                    <h2 class="fw-bold">Rs. {{ number_format($orders->sum('total_amount'), 2) }}</h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3">
                    <h6 class="text-muted">Average Order</h6>
                    <h2 class="fw-bold">Rs. {{ $count > 0 ? number_format($orders->sum('total_amount') / $count, 2) : 0 }}</h2>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="card">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">Completed Orders List</h5>
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
                                <th>Completed Date</th>
                                <th>Platform</th>
                                <th>Call Status</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($orders as $order)
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
                                    <td><strong class="text-success">Rs. {{ number_format($order->total_amount, 2) }}</strong></td>
                                    <td>{{ \Carbon\Carbon::parse($order->delivery_date ?? $order->updated_at)->format('M d, Y') }}</td>
                                    <td><span class="badge-platform">{{ ucfirst($order->platform ?? 'Manual') }}</span></td>
                                    <td>
                                        <span class="badge-call-status {{ strtolower(str_replace(' ', '-', $order->call_status ?? 'Pending Call')) }}">
                                            {{ $order->call_status ?? 'Pending Call' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('seller.order.show', $order->id) }}" class="action-btn view" title="View">
                                                <span class="material-icons-round">visibility</span>
                                            </a>
                                            <button onclick="printInvoice({{ $order->id }})" class="action-btn print" title="Print">
                                                <span class="material-icons-round">print</span>
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
                        <h5>No Completed Orders</h5>
                        <p class="text-muted">You don't have any completed orders yet.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function printInvoice(id) {
            window.open(`/seller/order/print/${id}`, '_blank');
        }
    </script>
@endpush
