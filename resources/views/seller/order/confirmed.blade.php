@extends('seller.layout.app')

@push('title')
    Confirmed Orders
@endpush

@push('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <style>
        /* Reuse styles from pending.blade.php */
        :root {
            --primary: #10b981; --primary-dark: #059669;
            --success: #10b981; --danger: #ef4444;
            --warning: #f59e0b; --info: #3b82f6;
            --dark: #0f172a; --mid: #64748b;
            --border: #e2e8f0; --surface: #ffffff; --bg: #f8fafc;
        }
        body { font-family: 'DM Sans', sans-serif; background: var(--bg); }

        .page-header { background: linear-gradient(135deg,#10b981 0%,#059669 100%); color:white; padding:2rem; border-radius:16px; margin-bottom:2rem; box-shadow:0 8px 20px rgba(16,185,129,.2); }
        .page-header h1 { font-size:28px; font-weight:800; margin-bottom:.5rem; }
        .order-count { background:rgba(255,255,255,.2); padding:8px 20px; border-radius:999px; display:inline-block; font-weight:600; }

        .card { border:none; border-radius:16px; box-shadow:0 2px 8px rgba(0,0,0,.05); }
        .card-header { background:white; border-bottom:1px solid var(--border); padding:1.5rem; }
        .table-responsive { border-radius:0 0 16px 16px; overflow:auto; }
        .table { margin:0; border-collapse:collapse; }
        .table thead th { background:var(--bg); border-bottom:2px solid var(--border); padding:1rem; font-weight:700; color:var(--dark); font-size:13px; text-transform:uppercase; letter-spacing:.5px; white-space:nowrap; }
        .table tbody td { padding:1rem; vertical-align:middle; border-bottom:1px solid var(--border); }

        .badge-platform { background:rgba(16,185,129,.1); color:var(--primary); padding:4px 12px; border-radius:999px; font-size:12px; font-weight:600; }
        .badge-call-status.confirmed { background:#d1fae5; color:#065f46; padding:6px 12px; border-radius:999px; font-size:11px; font-weight:700; }

        .action-btn { width:36px; height:36px; border-radius:8px; border:none; display:inline-flex; align-items:center; justify-content:center; transition:all .2s; cursor:pointer; }
        .action-btn.view   { background:var(--primary); color:white; }
        .action-btn.print  { background:var(--warning); color:white; }
        .action-btn:hover  { transform:translateY(-2px); box-shadow:0 4px 12px rgba(0,0,0,.15); }

        .search-wrapper { position:relative; display:inline-block; width:100%; max-width:400px; }
        .search-input { width:100%; padding:10px 16px 10px 40px; border:2px solid var(--border); border-radius:8px; font-size:14px; }
        .search-input:focus { border-color:var(--primary); outline:none; }
        .search-icon { position:absolute; left:12px; top:50%; transform:translateY(-50%); color:var(--mid); }

        .empty-state { text-align:center; padding:60px 20px; }
        .empty-icon { font-size:64px; color:var(--border); margin-bottom:16px; }

        @media(max-width:768px){
            .page-header { padding:1.5rem; }
            .page-header h1 { font-size:22px; }
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
                        <span class="material-icons-round me-2" style="vertical-align: middle; font-size: 32px;">check_circle</span>
                        Confirmed Orders
                    </h1>
                    <p class="mb-0 opacity-75">Orders that have been confirmed by customers</p>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <div class="order-count">
                        <span class="material-icons-round me-2" style="vertical-align: middle;">shopping_bag</span>
                        {{ $count }} {{ $count === 1 ? 'Order' : 'Orders' }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Session Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <span class="material-icons-round">check_circle</span>
                <div class="flex-grow-1">{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Search -->
        <div class="mb-4">
            <div class="search-wrapper">
                <span class="material-icons-round search-icon">search</span>
                <input type="text" id="searchInput" class="search-input" placeholder="Search confirmed orders...">
            </div>
        </div>

        <!-- Orders Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 fw-bold">
                    <span class="material-icons-round me-2" style="vertical-align: middle;">list_alt</span>
                    Confirmed Orders List
                    <span class="badge bg-success ms-2">{{ $count }}</span>
                </h5>
            </div>

            @if($count > 0)
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover" id="ordersTable">
                            <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Product</th>
                                <th>Customer</th>
                                <th>Qty</th>
                                <th>Amount</th>
                                <th>Confirmed Date</th>
                                <th>Platform</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody id="ordersTableBody">
                            @foreach($orders as $order)
                                <tr>
                                    <td>
                                        <strong style="font-family: monospace;">{{ $order->order_number }}</strong>
                                    </td>
                                    <td>
                                        <div>
                                            <div class="fw-semibold">{{ \Illuminate\Support\Str::limit($order->product_name, 30) }}</div>
                                            <small class="text-muted">{{ $order->product_code }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div>{{ $order->customer_name }}</div>
                                        <small class="text-muted">{{ $order->customer_phone }}</small>
                                    </td>
                                    <td><strong>{{ $order->quantity }}</strong></td>
                                    <td><strong class="text-danger">Rs. {{ number_format($order->total_amount, 2) }}</strong></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="material-icons-round text-secondary me-1" style="font-size: 16px;">event</span>
                                            {{ \Carbon\Carbon::parse($order->updated_at)->format('M d, Y') }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge-platform">{{ ucfirst($order->platform ?? 'Manual') }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('seller.order.show', $order->id) }}"
                                               class="action-btn view" title="View Details">
                                                <span class="material-icons-round" style="font-size: 18px;">visibility</span>
                                            </a>
{{--                                            <a href="{{ route('seller.order.', $order->id) }}"--}}
{{--                                               class="action-btn print" title="Print Invoice" target="_blank">--}}
{{--                                                <span class="material-icons-round" style="font-size: 18px;">print</span>--}}
{{--                                            </a>--}}
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
                        <div class="empty-icon">
                            <span class="material-icons-round" style="font-size: inherit;">check_circle_outline</span>
                        </div>
                        <h5>No Confirmed Orders</h5>
                        <p class="text-muted">You don't have any confirmed orders yet.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('script')
    <script>
        document.getElementById('searchInput')?.addEventListener('keyup', function() {
            const term = this.value.toLowerCase();
            const rows = document.querySelectorAll('#ordersTable tbody tr');
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(term) ? '' : 'none';
            });
        });
    </script>
@endpush
