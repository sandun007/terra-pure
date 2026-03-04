@extends('seller.layout.app')

@push('title')
    Not Answered Calls
@endpush

@push('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <style>
        :root {
            --primary: #64748b;
            --primary-dark: #475569;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --info: #3b82f6;
            --dark: #0f172a;
            --mid: #64748b;
            --border: #e2e8f0;
            --surface: #ffffff;
            --bg: #f8fafc;
        }

        body { font-family: 'DM Sans', sans-serif; background: var(--bg); }

        .page-header {
            background: linear-gradient(135deg, #64748b 0%, #475569 100%);
            color: white;
            padding: 2rem;
            border-radius: 16px;
            margin-bottom: 2rem;
            box-shadow: 0 8px 20px rgba(100,116,139,.2);
        }

        .page-header h1 {
            font-size: 28px;
            font-weight: 800;
            margin-bottom: .5rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .call-count {
            background: rgba(255,255,255,.2);
            padding: 8px 20px;
            border-radius: 999px;
            display: inline-block;
            font-weight: 600;
        }

        /* Tab Navigation */
        .call-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 24px;
            background: white;
            padding: 8px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,.05);
            flex-wrap: wrap;
        }

        .call-tab {
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            color: var(--mid);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all .3s;
            position: relative;
        }

        .call-tab:hover {
            background: rgba(100,116,139,.1);
            color: var(--primary);
        }

        .call-tab.active {
            background: var(--primary);
            color: white;
        }

        .tab-badge {
            background: var(--danger);
            color: white;
            font-size: 11px;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 999px;
            margin-left: 5px;
        }

        /* Filter Section */
        .filter-section {
            background: white;
            border-radius: 12px;
            padding: 1.25rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,.05);
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            align-items: center;
        }

        .filter-group {
            flex: 1;
            min-width: 200px;
        }

        .filter-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            color: var(--mid);
            margin-bottom: 4px;
            display: block;
        }

        .filter-select {
            width: 100%;
            padding: 8px 12px;
            border: 2px solid var(--border);
            border-radius: 8px;
            font-size: 14px;
        }

        .clear-filters {
            background: var(--bg);
            color: var(--mid);
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            border: 1px solid var(--border);
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        /* Table Styles */
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 2px 8px rgba(0,0,0,.05);
            background: white;
        }

        .card-header {
            background: white;
            border-bottom: 1px solid var(--border);
            padding: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .table-responsive {
            border-radius: 0 0 16px 16px;
            overflow-x: auto;
        }

        .table {
            margin: 0;
            border-collapse: collapse;
            width: 100%;
        }

        .table thead th {
            background: var(--bg);
            border-bottom: 2px solid var(--border);
            padding: 1rem;
            font-weight: 700;
            color: var(--dark);
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: .5px;
            white-space: nowrap;
        }

        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid var(--border);
        }

        .table tbody tr:hover {
            background-color: rgba(100,116,139,.03);
        }

        .badge-platform {
            background: rgba(100,116,139,.1);
            color: var(--primary);
            padding: 4px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            white-space: nowrap;
        }

        .badge-call-status {
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .5px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .badge-call-status.not-answered {
            background: #f1f5f9;
            color: #475569;
        }

        .action-btn {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all .2s;
            cursor: pointer;
            text-decoration: none;
        }

        .action-btn.view {
            background: var(--primary);
            color: white;
        }

        .action-btn.call {
            background: var(--success);
            color: white;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,.15);
        }

        .search-wrapper {
            position: relative;
            display: inline-block;
            width: 100%;
            max-width: 400px;
        }

        .search-input {
            width: 100%;
            padding: 10px 16px 10px 40px;
            border: 2px solid var(--border);
            border-radius: 8px;
            font-size: 14px;
        }

        .search-input:focus {
            border-color: var(--primary);
            outline: none;
        }

        .search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--mid);
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-icon {
            font-size: 64px;
            color: var(--border);
            margin-bottom: 16px;
        }

        .retry-badge {
            background: #f59e0b;
            color: white;
            padding: 4px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        @media(max-width:768px){
            .page-header { padding: 1.5rem; }
            .page-header h1 { font-size: 22px; }
            .call-tabs { flex-direction: column; }
            .call-tab { width: 100%; justify-content: center; }
            .filter-section { flex-direction: column; }
            .filter-group { width: 100%; }
            .table { font-size: 13px; }
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
                        <span class="material-icons-round">phone_missed</span>
                        Not Answered Calls
                    </h1>
                    <p class="mb-0 opacity-75">Orders where customers didn't answer the call</p>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <div class="call-count">
                        <span class="material-icons-round me-2" style="vertical-align: middle;">phone_missed</span>
                        {{ $count }} {{ $count === 1 ? 'Missed Call' : 'Missed Calls' }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Call Management Tabs -->
        <div class="call-tabs">
            <a href="{{ route('seller.call.pending') }}" class="call-tab">
                <span class="material-icons-round">pending_actions</span>
                Pending Calls
            </a>
            <a href="{{ route('seller.call.called') }}" class="call-tab">
                <span class="material-icons-round">call</span>
                Called
            </a>
            <a href="{{ route('seller.call.confirmed') }}" class="call-tab">
                <span class="material-icons-round">check_circle</span>
                Confirmed
            </a>
            <a href="{{ route('seller.call.not-answered') }}" class="call-tab active">
                <span class="material-icons-round">phone_missed</span>
                Not Answered
            </a>
            <a href="{{ route('seller.call.cancelled') }}" class="call-tab">
                <span class="material-icons-round">cancel</span>
                Cancelled
            </a>
        </div>

        <!-- Info Alert -->
        <div class="alert alert-warning mb-4" style="border-radius: 12px; padding: 1rem;">
            <div class="d-flex align-items-center gap-3">
                <span class="material-icons-round" style="color: var(--warning);">info</span>
                <div>
                    <strong>These customers didn't answer the call.</strong>
                    <span class="text-muted">Try calling again at a different time or send a text message.</span>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <div class="filter-group">
                <span class="filter-label">Filter by Platform</span>
                <select id="platformFilter" class="filter-select">
                    <option value="all">All Platforms</option>
                    <option value="fb">Facebook</option>
                    <option value="ig">Instagram</option>
                    <option value="tiktok">TikTok</option>
                    <option value="website">Website</option>
                    <option value="manual">Manual</option>
                </select>
            </div>
            <div>
                <button class="clear-filters" onclick="clearFilters()">
                    <span class="material-icons-round">clear</span>
                    Clear Filters
                </button>
            </div>
        </div>

        <!-- Search -->
        <div class="mb-4">
            <div class="search-wrapper">
                <span class="material-icons-round search-icon">search</span>
                <input type="text" id="searchInput" class="search-input" placeholder="Search by customer name, phone, or order ID...">
            </div>
        </div>

        <!-- Calls Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 fw-bold">
                    <span class="material-icons-round me-2" style="vertical-align: middle;">phone_missed</span>
                    Not Answered Orders List
                    <span class="badge bg-secondary ms-2">{{ $count }}</span>
                </h5>
                <div>
                    <span class="retry-badge me-2">
                        <span class="material-icons-round" style="font-size: 14px; vertical-align: middle;">schedule</span>
                        Retry Recommended
                    </span>
                    <a href="{{ route('seller.call.pending') }}" class="btn btn-primary">
                        <span class="material-icons-round me-2">pending_actions</span>
                        View Pending
                    </a>
                </div>
            </div>

            @if($count > 0)
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover" id="callsTable">
                            <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Phone</th>
                                <th>Product</th>
                                <th>City</th>
                                <th>Platform</th>
                                <th>Last Attempt</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody id="callsTableBody">
                            @foreach($calls as $call)
                                <tr data-call-id="{{ $call->id }}"
                                    data-platform="{{ $call->platform ?? 'manual' }}"
                                    data-search="{{ strtolower($call->customer_name . ' ' . $call->customer_phone . ' ' . ($call->order_number ?? $call->id)) }}">
                                    <td>
                                        <strong style="font-family: monospace;">#{{ $call->order_number ?? $call->id }}</strong>
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $call->customer_name }}</div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="material-icons-round" style="font-size: 16px; color: var(--warning);">phone</span>
                                            {{ $call->customer_phone }}
                                        </div>
                                        @if($call->customer_phone_2)
                                            <small class="text-muted d-block mt-1">{{ $call->customer_phone_2 }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <div>
                                            <div class="fw-semibold">{{ Str::limit($call->product_name, 30) }}</div>
                                            <small class="text-muted">{{ $call->product_code }}</small>
                                        </div>
                                    </td>
                                    <td>{{ $call->district ?? $call->destination_city_name ?? '-' }}</td>
                                    <td>
                                        <span class="badge-platform">{{ ucfirst($call->platform ?? 'Manual') }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="material-icons-round text-secondary me-1" style="font-size: 16px;">schedule</span>
                                            {{ \Carbon\Carbon::parse($call->updated_at)->format('M d, H:i') }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('seller.order.show', $call->id) }}"
                                               class="action-btn view"
                                               title="View Details">
                                                <span class="material-icons-round">visibility</span>
                                            </a>
                                            <a href="tel:{{ $call->customer_phone }}"
                                               class="action-btn call"
                                               title="Call Again">
                                                <span class="material-icons-round">phone</span>
                                            </a>
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
                            <span class="material-icons-round">phone_forwarded</span>
                        </div>
                        <h5>No Missed Calls</h5>
                        <p class="text-muted">All your calls have been answered!</p>
                        <a href="{{ route('seller.call.pending') }}" class="btn btn-primary mt-3">
                            <span class="material-icons-round me-2">pending_actions</span>
                            View Pending Calls
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Search functionality
            const searchInput = document.getElementById('searchInput');
            const platformFilter = document.getElementById('platformFilter');

            function filterTable() {
                const searchTerm = searchInput?.value.toLowerCase() || '';
                const platform = platformFilter?.value || 'all';

                const rows = document.querySelectorAll('#callsTable tbody tr');
                let visibleCount = 0;

                rows.forEach(row => {
                    const searchText = row.dataset.search || '';
                    const rowPlatform = row.dataset.platform || 'manual';

                    const matchesSearch = searchTerm === '' || searchText.includes(searchTerm);
                    const matchesPlatform = platform === 'all' || rowPlatform === platform;

                    if (matchesSearch && matchesPlatform) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                // Update visible count
                const countBadge = document.querySelector('.badge.bg-secondary');
                if (countBadge) {
                    countBadge.textContent = visibleCount;
                }
            }

            searchInput?.addEventListener('keyup', filterTable);
            platformFilter?.addEventListener('change', filterTable);
        });

        function clearFilters() {
            document.getElementById('platformFilter').value = 'all';
            document.getElementById('searchInput').value = '';

            // Trigger filter
            const event = new Event('keyup');
            document.getElementById('searchInput')?.dispatchEvent(event);
        }

        // Auto-hide alerts after 5 seconds
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(alert => {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
    </script>
@endpush
