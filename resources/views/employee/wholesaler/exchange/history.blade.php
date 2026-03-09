@extends('employee.layout.app')

@push('title')
    Exchange History
@endpush

@push('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .history-header {
            background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
            color: white;
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }

        .history-header::after {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            border-radius: 50%;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            font-size: 1.5rem;
        }

        .stat-icon.total {
            background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
            color: white;
        }

        .stat-icon.completed {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }

        .stat-icon.pending {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
        }

        .stat-icon.value {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
        }

        .stat-value {
            font-size: 1.8rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.25rem;
        }

        .stat-label {
            color: #64748b;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .filter-section {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
            border: 1px solid #e2e8f0;
        }

        .filter-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .filter-title i {
            color: #f97316;
        }

        .table-container {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
            border: 1px solid #e2e8f0;
            margin-bottom: 2rem;
        }

        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background: #f8fafc;
            color: #475569;
            font-weight: 600;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #e2e8f0;
            padding: 1rem;
        }

        .table tbody tr {
            transition: all 0.3s ease;
        }

        .table tbody tr:hover {
            background: #f8fafc;
        }

        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
            color: #475569;
            border-bottom: 1px solid #e2e8f0;
        }

        .exchange-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.35rem 1rem;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .badge-completed {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-approved {
            background: #dbeafe;
            color: #1e40af;
        }

        .badge-cancelled {
            background: #fee2e2;
            color: #991b1b;
        }

        .type-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .type-full {
            background: #8b5cf6;
            color: white;
        }

        .type-partial {
            background: #10b981;
            color: white;
        }

        .type-credit {
            background: #f59e0b;
            color: white;
        }

        .product-link {
            color: #1e293b;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .product-link:hover {
            color: #f97316;
        }

        .product-code {
            font-size: 0.85rem;
            color: #64748b;
            font-family: monospace;
        }

        .wholesaler-badge {
            background: #f1f5f9;
            padding: 0.25rem 0.75rem;
            border-radius: 50px;
            font-size: 0.85rem;
            color: #475569;
            display: inline-block;
        }

        .price-positive {
            color: #10b981;
            font-weight: 600;
        }

        .price-negative {
            color: #ef4444;
            font-weight: 600;
        }

        .price-neutral {
            color: #64748b;
            font-weight: 600;
        }

        .view-toggle {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .toggle-btn {
            padding: 0.5rem 1rem;
            border: 2px solid #e2e8f0;
            background: white;
            color: #475569;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .toggle-btn.active {
            background: #f97316;
            color: white;
            border-color: #f97316;
        }

        .toggle-btn:hover:not(.active) {
            border-color: #f97316;
            color: #f97316;
        }

        .timeline-view {
            display: none;
        }

        .timeline-view.active {
            display: block;
        }

        .table-view {
            display: none;
        }

        .table-view.active {
            display: block;
        }

        .timeline {
            position: relative;
            padding: 1rem 0;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 1.5rem;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e2e8f0;
        }

        .timeline-item {
            position: relative;
            padding-left: 4rem;
            padding-bottom: 2rem;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: 1rem;
            top: 0;
            width: 1rem;
            height: 1rem;
            border-radius: 50%;
            background: #f97316;
            border: 2px solid white;
            z-index: 1;
        }

        .timeline-item.completed::before {
            background: #10b981;
        }

        .timeline-item.pending::before {
            background: #f59e0b;
        }

        .timeline-item.cancelled::before {
            background: #ef4444;
        }

        .timeline-date {
            font-size: 0.85rem;
            color: #64748b;
            margin-bottom: 0.25rem;
        }

        .timeline-content {
            background: #f8fafc;
            border-radius: 12px;
            padding: 1.25rem;
            border: 1px solid #e2e8f0;
        }

        .export-btn {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            z-index: 100;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: #f97316;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            box-shadow: 0 4px 10px rgba(249, 115, 22, 0.3);
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
        }

        .export-btn:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(249, 115, 22, 0.4);
        }

        .export-menu {
            position: absolute;
            bottom: 70px;
            right: 0;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            padding: 0.5rem;
            min-width: 180px;
            display: none;
        }

        .export-btn:hover .export-menu {
            display: block;
        }

        .export-menu-item {
            padding: 0.75rem 1rem;
            color: #1e293b;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .export-menu-item:hover {
            background: #f8fafc;
            color: #f97316;
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr 1fr;
            }

            .filter-section .row > div {
                margin-bottom: 1rem;
            }

            .table-container {
                overflow-x: auto;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="history-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mb-2">Exchange History</h1>
                    <p class="mb-0 opacity-75">Complete history of all product exchanges between wholesalers</p>
                </div>
                <div class="d-flex gap-2">
                <span class="badge bg-white bg-opacity-25 p-3">
                    <i class="fas fa-exchange-alt me-2"></i>
                    Total: {{ $exchanges->total() }} Exchanges
                </span>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon total">
                    <i class="fas fa-exchange-alt"></i>
                </div>
                <div class="stat-value">{{ number_format($totalExchanges ?? $exchanges->total()) }}</div>
                <div class="stat-label">Total Exchanges</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon completed">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-value">{{ number_format($completedExchanges ?? $exchanges->where('status', 'completed')->count()) }}</div>
                <div class="stat-label">Completed</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon pending">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-value">{{ number_format($pendingExchanges ?? $exchanges->where('status', 'pending')->count()) }}</div>
                <div class="stat-label">Pending</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon value">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="stat-value">Rs. {{ number_format($totalValueTransferred ?? $exchanges->sum('destination_price_at_time'), 2) }}</div>
                <div class="stat-label">Total Value</div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <h5 class="filter-title">
                <i class="fas fa-filter"></i>
                Filter Exchange History
            </h5>

            <form method="GET" action="{{ route('employee.wholesaler.exchanges.history') }}" id="filterForm">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="all">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Source Wholesaler</label>
                        <select name="source_wholesaler_id" class="form-select">
                            <option value="all">All</option>
                            @foreach($wholesalers ?? [] as $wholesaler)
                                <option value="{{ $wholesaler->id }}" {{ request('source_wholesaler_id') == $wholesaler->id ? 'selected' : '' }}>
                                    {{ $wholesaler->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Destination Wholesaler</label>
                        <select name="destination_wholesaler_id" class="form-select">
                            <option value="all">All</option>
                            @foreach($wholesalers ?? [] as $wholesaler)
                                <option value="{{ $wholesaler->id }}" {{ request('destination_wholesaler_id') == $wholesaler->id ? 'selected' : '' }}>
                                    {{ $wholesaler->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Exchange Type</label>
                        <select name="exchange_type" class="form-select">
                            <option value="all">All Types</option>
                            <option value="full" {{ request('exchange_type') == 'full' ? 'selected' : '' }}>Full Exchange</option>
                            <option value="partial" {{ request('exchange_type') == 'partial' ? 'selected' : '' }}>Partial Exchange</option>
                            <option value="credit" {{ request('exchange_type') == 'credit' ? 'selected' : '' }}>Credit Exchange</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Employee</label>
                        <select name="employee_id" class="form-select">
                            <option value="all">All Employees</option>
                            @foreach($employees ?? [] as $employee)
                                <option value="{{ $employee->id }}" {{ request('employee_id') == $employee->id ? 'selected' : '' }}>
                                    {{ $employee->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Date From</label>
                        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Date To</label>
                        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Search</label>
                        <input type="text" name="search" class="form-control" placeholder="Product name/code..." value="{{ request('search') }}">
                    </div>

                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-primary" style="background: #f97316; border-color: #f97316;">
                            <i class="fas fa-filter me-2"></i>Apply Filters
                        </button>
                        <a href="{{ route('employee.wholesaler.exchanges.history') }}" class="btn btn-secondary">
                            <i class="fas fa-redo me-2"></i>Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- View Toggle -->
        <div class="view-toggle">
            <button class="toggle-btn active" onclick="toggleView('table')" id="tableViewBtn">
                <i class="fas fa-table"></i>
                Table View
            </button>
            <button class="toggle-btn" onclick="toggleView('timeline')" id="timelineViewBtn">
                <i class="fas fa-stream"></i>
                Timeline View
            </button>
        </div>

        <!-- Table View -->
        <div id="tableView" class="table-view active">
            <div class="table-container">
                <div class="table-responsive">
                    <table class="table" id="exchangesTable">
                        <thead>
                        <tr>
                            <th>Date & Time</th>
                            <th>Exchange ID</th>
                            <th>Source Product</th>
                            <th>Destination Product</th>
                            <th>Quantity</th>
                            <th>Value</th>
                            <th>Difference</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Employee</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($exchanges as $exchange)
                            <tr>
                                <td>
                                    {{ $exchange->created_at->format('M d, Y') }}
                                    <br><small class="text-muted">{{ $exchange->created_at->format('h:i A') }}</small>
                                </td>
                                <td>
                                    <span class="fw-bold">#{{ $exchange->id }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('employee.wholesaler.products.show', $exchange->source_product_id) }}" class="product-link">
                                        {{ $exchange->sourceProduct->name ?? 'N/A' }}
                                    </a>
                                    <div class="product-code">{{ $exchange->sourceProduct->code ?? '' }}</div>
                                    <span class="wholesaler-badge">
                                    <i class="fas fa-store me-1"></i>
                                    {{ $exchange->sourceWholesaler->business_name ?? 'N/A' }}
                                </span>
                                </td>
                                <td>
                                    <a href="{{ route('employee.wholesaler.products.show', $exchange->destination_product_id) }}" class="product-link">
                                        {{ $exchange->destinationProduct->name ?? 'N/A' }}
                                    </a>
                                    <div class="product-code">{{ $exchange->destinationProduct->code ?? '' }}</div>
                                    <span class="wholesaler-badge">
                                    <i class="fas fa-store me-1"></i>
                                    {{ $exchange->destinationWholesaler->business_name ?? 'N/A' }}
                                </span>
                                </td>
                                <td>
                                    <span class="fw-bold">{{ $exchange->exchange_quantity }}</span>
                                </td>
                                <td>
                                    Rs. {{ number_format($exchange->destination_price_at_time, 2) }}
                                    <br><small class="text-muted">Src: Rs. {{ number_format($exchange->source_price_at_time, 2) }}</small>
                                </td>
                                <td>
                                    @if($exchange->price_difference > 0)
                                        <span class="price-positive">
                                        +Rs. {{ number_format($exchange->price_difference, 2) }}
                                    </span>
                                    @elseif($exchange->price_difference < 0)
                                        <span class="price-negative">
                                        -Rs. {{ number_format(abs($exchange->price_difference), 2) }}
                                    </span>
                                    @else
                                        <span class="price-neutral">
                                        Rs. 0.00
                                    </span>
                                    @endif
                                    @if($exchange->adjustment_amount != 0)
                                        <br><small class="text-muted">Adj: Rs. {{ number_format($exchange->adjustment_amount, 2) }}</small>
                                    @endif
                                </td>
                                <td>
                                <span class="type-badge type-{{ $exchange->exchange_type }}">
                                    {{ ucfirst($exchange->exchange_type) }}
                                </span>
                                </td>
                                <td>
                                <span class="exchange-badge badge-{{ $exchange->status }}">
                                    <i class="fas fa-{{
                                        $exchange->status == 'completed' ? 'check-circle' :
                                        ($exchange->status == 'pending' ? 'clock' :
                                        ($exchange->status == 'approved' ? 'thumbs-up' :
                                        ($exchange->status == 'cancelled' ? 'ban' : 'times-circle')))
                                    }}"></i>
                                    {{ ucfirst($exchange->status) }}
                                </span>
                                </td>
                                <td>
                                    {{ $exchange->employee->name ?? 'N/A' }}
                                    @if($exchange->employee_id == Auth::guard('employee')->id())
                                        <br><span class="badge bg-primary">You</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('employee.wholesaler.exchanges.show', $exchange->id) }}"
                                       class="btn btn-sm btn-outline-primary"
                                       title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center py-5">
                                    <i class="fas fa-exchange-alt fa-4x text-muted mb-3"></i>
                                    <h5 class="text-muted">No Exchanges Found</h5>
                                    <p class="text-muted">Try adjusting your filters or create a new exchange</p>
                                    <a href="{{ route('employee.wholesaler.exchanges.create') }}" class="btn btn-primary mt-3">
                                        <i class="fas fa-plus me-2"></i>Create Exchange
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div>
                        Showing {{ $exchanges->firstItem() ?? 0 }} to {{ $exchanges->lastItem() ?? 0 }} of {{ $exchanges->total() }} exchanges
                    </div>
                    <div>
                        {{ $exchanges->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Timeline View -->
        <div id="timelineView" class="timeline-view">
            <div class="table-container">
                <div class="timeline">
                    @forelse($exchanges as $exchange)
                        <div class="timeline-item {{ $exchange->status }}">
                            <div class="timeline-date">
                                <i class="far fa-calendar-alt me-1"></i> {{ $exchange->created_at->format('M d, Y h:i A') }}
                                <span class="exchange-badge badge-{{ $exchange->status }} ms-2">
                            {{ ucfirst($exchange->status) }}
                        </span>
                                <span class="type-badge type-{{ $exchange->exchange_type }} ms-2">
                            {{ ucfirst($exchange->exchange_type) }}
                        </span>
                            </div>
                            <div class="timeline-content">
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="text-end" style="width: 120px;">
                                                <small class="text-muted">Source</small>
                                                <div class="fw-bold">{{ $exchange->sourceProduct->name ?? 'N/A' }}</div>
                                                <div class="product-code">{{ $exchange->sourceProduct->code ?? '' }}</div>
                                                <span class="wholesaler-badge">
                                            {{ $exchange->sourceWholesaler->business_name ?? 'N/A' }}
                                        </span>
                                            </div>
                                            <div class="text-center">
                                                <i class="fas fa-arrow-right fa-2x text-muted"></i>
                                                <div class="badge bg-warning text-dark mt-1">
                                                    {{ $exchange->exchange_quantity }} units
                                                </div>
                                            </div>
                                            <div>
                                                <small class="text-muted">Destination</small>
                                                <div class="fw-bold">{{ $exchange->destinationProduct->name ?? 'N/A' }}</div>
                                                <div class="product-code">{{ $exchange->destinationProduct->code ?? '' }}</div>
                                                <span class="wholesaler-badge">
                                            {{ $exchange->destinationWholesaler->business_name ?? 'N/A' }}
                                        </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="d-flex gap-3">
                                            <div>
                                                <small class="text-muted">Source Value</small>
                                                <div class="fw-bold">Rs. {{ number_format($exchange->source_price_at_time, 2) }}</div>
                                            </div>
                                            <div>
                                                <small class="text-muted">Destination Value</small>
                                                <div class="fw-bold">Rs. {{ number_format($exchange->destination_price_at_time, 2) }}</div>
                                            </div>
                                        </div>
                                        <div class="mt-2">
                                            <small class="text-muted">Difference</small>
                                            <div class="{{
                                        $exchange->price_difference > 0 ? 'price-positive' :
                                        ($exchange->price_difference < 0 ? 'price-negative' : 'price-neutral')
                                    }}">
                                                {{ $exchange->price_difference > 0 ? '+' : '' }}{{ number_format($exchange->price_difference, 2) }}
                                                @if($exchange->adjustment_amount != 0)
                                                    <br><small>(Adj: {{ number_format($exchange->adjustment_amount, 2) }})</small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <small class="text-muted">Payment</small>
                                        <div>{{ ucfirst($exchange->payment_method ?? 'N/A') }}</div>
                                        <small class="text-muted">{{ ucfirst($exchange->payment_status ?? 'pending') }}</small>
                                        @if($exchange->transaction_reference)
                                            <br><small class="text-muted">Ref: {{ $exchange->transaction_reference }}</small>
                                        @endif
                                    </div>
                                    <div class="col-md-2">
                                        <small class="text-muted">Employee</small>
                                        <div>{{ $exchange->employee->name ?? 'N/A' }}</div>
                                        @if($exchange->notes)
                                            <small class="text-muted" title="{{ $exchange->notes }}">
                                                <i class="fas fa-comment"></i> Notes
                                            </small>
                                        @endif
                                        <div class="mt-2">
                                            <a href="{{ route('employee.wholesaler.exchanges.show', $exchange->id) }}"
                                               class="btn btn-sm btn-outline-primary">
                                                View Details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="fas fa-exchange-alt fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">No Exchanges Found</h5>
                            <p class="text-muted">Try adjusting your filters</p>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $exchanges->appends(request()->query())->links() }}
                </div>
            </div>
        </div>

        <!-- Export Button -->
        <div class="export-btn">
            <i class="fas fa-download"></i>
            <div class="export-menu">
                <a href="{{ route('employee.wholesaler.exchanges.export') }}?{{ http_build_query(request()->query()) }}" class="export-menu-item">
                    <i class="fas fa-file-csv"></i>
                    Export as CSV
                </a>
                <a href="#" class="export-menu-item" onclick="printTable()">
                    <i class="fas fa-print"></i>
                    Print Report
                </a>
                <a href="{{ route('employee.wholesaler.exchanges.export') }}?format=excel&{{ http_build_query(request()->query()) }}" class="export-menu-item">
                    <i class="fas fa-file-excel"></i>
                    Export as Excel
                </a>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        function toggleView(view) {
            const tableView = document.getElementById('tableView');
            const timelineView = document.getElementById('timelineView');
            const tableViewBtn = document.getElementById('tableViewBtn');
            const timelineViewBtn = document.getElementById('timelineViewBtn');

            if (view === 'table') {
                tableView.classList.add('active');
                timelineView.classList.remove('active');
                tableViewBtn.classList.add('active');
                timelineViewBtn.classList.remove('active');

                // Initialize DataTable if not already initialized
                if (!$.fn.DataTable.isDataTable('#exchangesTable')) {
                    $('#exchangesTable').DataTable({
                        paging: false,
                        ordering: true,
                        info: false,
                        searching: false,
                        order: [[0, 'desc']]
                    });
                }
            } else {
                tableView.classList.remove('active');
                timelineView.classList.add('active');
                tableViewBtn.classList.remove('active');
                timelineViewBtn.classList.add('active');
            }
        }

        function printTable() {
            const printContent = document.getElementById('tableView').innerHTML;
            const originalContent = document.body.innerHTML;

            document.body.innerHTML = `
            <html>
                <head>
                    <title>Exchange History Report</title>
                    <style>
                        body { font-family: Arial, sans-serif; padding: 20px; }
                        table { width: 100%; border-collapse: collapse; }
                        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                        th { background-color: #f2f2f2; }
                        .badge-completed { color: #065f46; }
                        .badge-pending { color: #92400e; }
                        .badge-cancelled { color: #991b1b; }
                    </style>
                </head>
                <body>
                    <h1>Exchange History Report</h1>
                    <p>Generated on: ${new Date().toLocaleString()}</p>
                    ${printContent}
                </body>
            </html>
        `;

            window.print();
            document.body.innerHTML = originalContent;
            location.reload();
        }

        // Auto-submit form when filters change
        document.querySelectorAll('#filterForm select').forEach(select => {
            select.addEventListener('change', function() {
                document.getElementById('filterForm').submit();
            });
        });

        // Add date range validation
        document.getElementById('filterForm').addEventListener('submit', function(e) {
            const dateFrom = document.querySelector('input[name="date_from"]').value;
            const dateTo = document.querySelector('input[name="date_to"]').value;

            if (dateFrom && dateTo && dateFrom > dateTo) {
                e.preventDefault();
                alert('Date From cannot be after Date To');
            }
        });

        // Initialize tooltips
        $(document).ready(function() {
            $('[title]').tooltip();
        });
    </script>
@endpush
