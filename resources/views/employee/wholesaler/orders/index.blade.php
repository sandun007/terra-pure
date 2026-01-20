@extends('employee.layout.app')
@push('title')
    Wholesaler Orders Management
@endpush

@push('css')
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons|Material+Icons+Outlined" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        .container-fluid {
            width: 100%;
            padding-right: 15px;
            padding-left: 15px;
            margin-right: auto;
            margin-left: auto;
        }

        .page-header {
            background: linear-gradient(135deg, #4f46e5, #3b82f6);
            color: #fff;
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .order-card {
            border-radius: 1rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            border: none;
            overflow: hidden;
        }

        .table th {
            font-size: 0.85rem;
            text-transform: uppercase;
            color: #6b7280;
            background-color: #f9fafb;
            border-top: none;
            font-weight: 700;
            padding: 1rem 0.75rem;
        }

        .table td {
            padding: 1rem 0.75rem;
            vertical-align: middle;
            border-top: 1px solid #f1f5f9;
        }

        .status-badge {
            padding: 0.4rem 0.8rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            text-transform: capitalize;
        }

        .status-pending {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            color: #92400e;
            border: 1px solid #fbbf24;
        }
        .status-confirmed {
            background: linear-gradient(135deg, #dbeafe, #93c5fd);
            color: #1e40af;
            border: 1px solid #3b82f6;
        }
        .status-processing {
            background: linear-gradient(135deg, #f3e8ff, #d8b4fe);
            color: #6d28d9;
            border: 1px solid #8b5cf6;
        }
        .status-shipped {
            background: linear-gradient(135deg, #ecfccb, #bef264);
            color: #3f6212;
            border: 1px solid #84cc16;
        }
        .status-completed {
            background: linear-gradient(135deg, #dcfce7, #86efac);
            color: #166534;
            border: 1px solid #22c55e;
        }
        .status-cancelled {
            background: linear-gradient(135deg, #fee2e2, #fca5a5);
            color: #991b1b;
            border: 1px solid #ef4444;
        }

        .stats-card {
            background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
            border-radius: 0.75rem;
            padding: 1.25rem;
            text-align: center;
            transition: transform 0.2s;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }

        .stats-card:hover {
            transform: translateY(-2px);
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

        /* Checkbox styles */
        .checkbox-cell {
            width: 40px;
            text-align: center;
        }

        .order-checkbox {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        .order-checkbox:checked {
            background-color: #4f46e5;
            border-color: #4f46e5;
        }

        .bulk-actions {
            background: #f8fafc;
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
            display: none;
        }

        .bulk-actions.show {
            display: block;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .selected-count {
            background: #4f46e5;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .btn-bulk {
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-weight: 600;
            font-size: 0.875rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s;
            border: 1px solid transparent;
        }

        .btn-bulk-print {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }

        .btn-bulk-print:hover {
            background: linear-gradient(135deg, #059669, #047857);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .btn-bulk-clear {
            background: #f3f4f6;
            color: #6b7280;
            border: 1px solid #d1d5db;
        }

        .btn-bulk-clear:hover {
            background: #e5e7eb;
            color: #374151;
        }

        .btn-bulk-status {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
        }

        .btn-bulk-status:hover {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .filter-card {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-radius: 0.75rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }

        .quick-filter {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .quick-filter-btn {
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-weight: 600;
            font-size: 0.875rem;
            border: 1px solid #d1d5db;
            background: white;
            color: #4b5563;
            transition: all 0.2s;
            cursor: pointer;
        }

        .quick-filter-btn:hover {
            border-color: #4f46e5;
            color: #4f46e5;
        }

        .quick-filter-btn.active {
            background: #4f46e5;
            border-color: #4f46e5;
            color: white;
        }

        .btn-print-selected {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-print-selected:hover {
            background: linear-gradient(135deg, #059669, #047857);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .btn-print-selected.disabled {
            opacity: 0.5;
            cursor: not-allowed;
            pointer-events: none;
        }

        .status-filter-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .status-filter-btn {
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-weight: 600;
            font-size: 0.875rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s;
        }

        .status-filter-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        /* Action buttons */
        .action-buttons {
            display: flex;
            gap: 5px;
            flex-wrap: nowrap;
        }

        .btn-action {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .btn-view {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
        }

        .btn-invoice {
            background: linear-gradient(135deg, #10b981, #059669);
        }

        .btn-print {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }

        .btn-edit {
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
        }

        @media (max-width: 768px) {
            .bulk-actions {
                flex-direction: column;
                gap: 0.5rem;
            }

            .quick-filter {
                flex-direction: column;
            }

            .quick-filter-btn {
                width: 100%;
            }

            .status-filter-buttons {
                flex-direction: column;
            }

            .status-filter-btn {
                width: 100%;
                justify-content: center;
            }

            .action-buttons {
                flex-direction: column;
                gap: 3px;
            }

            .btn-action {
                width: 32px;
                height: 32px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid py-4">
        {{-- Header --}}
        <div class="page-header d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h2 class="mb-2 fw-bold">📦 Wholesaler Orders Management</h2>
                <p class="mb-0 text-white-80">Manage and monitor all wholesaler orders efficiently</p>
            </div>
            <div class="d-flex gap-3 mt-2 mt-md-0 align-items-center flex-wrap">
                <a href="{{ route('employee.wholesaler_orders.print.selection') }}"
                   class="btn-print-selected {{ count($selectedOrders) > 0 ? '' : 'disabled' }}">
                    <i class="material-icons-outlined">print</i>
                    Print Selected ({{ count($selectedOrders) }})
                </a>
                <button class="btn btn-light" onclick="window.location.href='{{ route('employee.wholesaler_orders.export') }}'">
                    <i class="material-icons-outlined">download</i>
                    Export
                </button>
            </div>
        </div>

        {{-- Quick Stats --}}
        <div class="row mb-4 g-3">
            <div class="col-md-2 col-6">
                <div class="stats-card">
                    <div class="stats-value">{{ number_format($statusCounts['all']) }}</div>
                    <div class="stats-label">Total Orders</div>
                </div>
            </div>
            <div class="col-md-2 col-6">
                <div class="stats-card">
                    <div class="stats-value">{{ number_format($statusCounts['today']) }}</div>
                    <div class="stats-label">Today's Orders</div>
                </div>
            </div>
            <div class="col-md-2 col-6">
                <div class="stats-card">
                    <div class="stats-value">{{ number_format($statusCounts['week']) }}</div>
                    <div class="stats-label">This Week</div>
                </div>
            </div>
            <div class="col-md-2 col-6">
                <div class="stats-card">
                    <div class="stats-value">Rs. {{ number_format($todayRevenue, 2) }}</div>
                    <div class="stats-label">Today's Revenue</div>
                </div>
            </div>
            <div class="col-md-2 col-6">
                <div class="stats-card">
                    <div class="stats-value">Rs. {{ number_format($weeklyRevenue, 2) }}</div>
                    <div class="stats-label">Weekly Revenue</div>
                </div>
            </div>
            <div class="col-md-2 col-6">
                <div class="stats-card">
                    <div class="stats-value">Rs. {{ number_format($totalRevenue, 2) }}</div>
                    <div class="stats-label">Total Revenue</div>
                </div>
            </div>
        </div>

        {{-- Status Filter Buttons --}}
        <div class="status-filter-buttons">
            <a href="{{ route('employee.wholesaler_orders.today') }}"
               class="status-filter-btn btn btn-info">
                <i class="material-icons-outlined">today</i>
                Today's Orders
            </a>
            <a href="{{ route('employee.wholesaler_orders.weekly') }}"
               class="status-filter-btn btn btn-warning">
                <i class="material-icons-outlined">date_range</i>
                Weekly Orders
            </a>
            <a href="{{ route('employee.wholesaler_orders.index') }}"
               class="status-filter-btn btn btn-primary">
                <i class="material-icons-outlined">list</i>
                All Orders
            </a>
            <a href="{{ route('employee.wholesaler_orders.index', ['status' => 'pending']) }}"
               class="status-filter-btn btn btn-secondary">
                <i class="material-icons-outlined">pending</i>
                Pending
            </a>
            <a href="{{ route('employee.wholesaler_orders.index', ['status' => 'completed']) }}"
               class="status-filter-btn btn btn-success">
                <i class="material-icons-outlined">check_circle</i>
                Completed
            </a>
            <a href="{{ route('employee.wholesaler_orders.index', ['status' => 'cancelled']) }}"
               class="status-filter-btn btn btn-danger">
                <i class="material-icons-outlined">cancel</i>
                Cancelled
            </a>
        </div>

        {{-- Filter Section --}}
        <div class="filter-card">
            <form method="GET" action="{{ route('employee.wholesaler_orders.index') }}" id="filterForm">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label small text-muted mb-1">Search Orders</label>
                        <input type="text" class="form-control" name="search"
                               placeholder="Search by order #, customer name, email..."
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small text-muted mb-1">Order Status</label>
                        <select class="form-select" name="status">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small text-muted mb-1">Wholesaler</label>
                        <select class="form-select" name="wholesaler_id">
                            <option value="">All Wholesalers</option>
                            @foreach($wholesalers as $wholesaler)
                                <option value="{{ $wholesaler->id }}" {{ request('wholesaler_id') == $wholesaler->id ? 'selected' : '' }}>
                                    {{ $wholesaler->business_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small text-muted mb-1">From Date</label>
                        <input type="date" class="form-control" name="date_from" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small text-muted mb-1">To Date</label>
                        <input type="date" class="form-control" name="date_to" value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-primary w-100 h-100">
                            <i class="material-icons-outlined">filter_alt</i>
                            <span class="d-none d-md-inline">Filter</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Quick Filter Buttons --}}
        <div class="mb-4">
            <div class="quick-filter">
                <button class="quick-filter-btn {{ !request('filter') ? 'active' : '' }}"
                        onclick="setQuickFilter('')">All Time</button>
                <button class="quick-filter-btn {{ request('filter') == 'today' ? 'active' : '' }}"
                        onclick="setQuickFilter('today')">Today</button>
                <button class="quick-filter-btn {{ request('filter') == 'week' ? 'active' : '' }}"
                        onclick="setQuickFilter('week')">This Week</button>
                <button class="quick-filter-btn {{ request('filter') == 'last7days' ? 'active' : '' }}"
                        onclick="setQuickFilter('last7days')">Last 7 Days</button>
                <button class="quick-filter-btn {{ request('filter') == 'month' ? 'active' : '' }}"
                        onclick="setQuickFilter('month')">This Month</button>
            </div>
        </div>

        {{-- Bulk Actions --}}
        <div class="bulk-actions d-flex justify-content-between align-items-center flex-wrap gap-2" id="bulkActions">
            <div class="d-flex align-items-center gap-2">
                <span class="selected-count" id="selectedCount">0 selected</span>
                <span>Orders selected for bulk action</span>
            </div>
            <div class="d-flex gap-2">
                <button class="btn-bulk btn-bulk-print" onclick="printSelectedOrders()">
                    <i class="material-icons-outlined">print</i>
                    Print Selected
                </button>
                <button class="btn-bulk btn-bulk-status" onclick="showBulkStatusModal()">
                    <i class="material-icons-outlined">sync</i>
                    Update Status
                </button>
                <button class="btn-bulk btn-bulk-clear" onclick="clearSelectedOrders()">
                    <i class="material-icons-outlined">clear</i>
                    Clear Selection
                </button>
            </div>
        </div>

        {{-- Orders Table --}}
        @if($orders->count())
            <div class="card order-card">
                <div class="card-header bg-white border-bottom-0 py-3">
                    <h5 class="mb-0 fw-semibold">📋 Order List</h5>
                    <p class="text-muted mb-0 small">Showing {{ $orders->firstItem() ?? 0 }}-{{ $orders->lastItem() ?? 0 }} of {{ $orders->total() }} orders</p>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle table-hover mb-0">
                            <thead>
                            <tr>
                                <th class="checkbox-cell">
                                    <input type="checkbox" id="selectAll" class="order-checkbox">
                                </th>
                                <th>Order #</th>
                                <th>Wholesaler</th>
                                <th>Customer</th>
                                <th>Order Date</th>
                                <th>Items</th>
                                <th>Total Amount</th>
                                <th>Order Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td class="checkbox-cell">
                                        <input type="checkbox"
                                               class="order-checkbox order-select"
                                               value="{{ $order->id }}"
                                               data-order-number="{{ $order->order_number }}"
                                            {{ in_array($order->id, $selectedOrders) ? 'checked' : '' }}>
                                    </td>
                                    <td>
                                        <a href="{{ route('employee.wholesaler_orders.show', $order->id) }}"
                                           class="text-decoration-none text-primary fw-bold d-flex align-items-center gap-2">
                                            <i class="material-icons-outlined">receipt_long</i>
                                            #{{ $order->order_number }}
                                        </a>
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $order->wholesaler->business_name ?? 'N/A' }}</div>
                                        <small class="text-muted">{{ $order->wholesaler->phone ?? 'No contact' }}</small>
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $order->customer_name }}</div>
                                        <small class="text-muted">{{ $order->customer_email }}</small>
                                        <div class="small text-muted">{{ $order->customer_phone ?? 'No phone' }}</div>
                                    </td>
                                    <td>
                                        <div class="fw-medium">{{ $order->created_at->format('M d, Y') }}</div>
                                        <small class="text-muted">{{ $order->created_at->format('h:i A') }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            <i class="material-icons-outlined me-1" style="font-size: 14px;">inventory_2</i>
                                            {{ $order->items->count() }} items
                                        </span>
                                    </td>
                                    <td class="fw-bold text-success">
                                        <i class="material-icons-outlined me-1" style="font-size: 16px;">currency_rupee</i>
                                        {{ number_format($order->total_amount, 2) }}
                                    </td>
                                    <td>
                                        <span class="status-badge status-{{ $order->status }}">
                                            @if($order->status == 'pending')
                                                <i class="material-icons-outlined me-1">schedule</i>
                                            @elseif($order->status == 'confirmed')
                                                <i class="material-icons-outlined me-1">check_circle</i>
                                            @elseif($order->status == 'processing')
                                                <i class="material-icons-outlined me-1">build_circle</i>
                                            @elseif($order->status == 'shipped')
                                                <i class="material-icons-outlined me-1">local_shipping</i>
                                            @elseif($order->status == 'completed')
                                                <i class="material-icons-outlined me-1">task_alt</i>
                                            @else
                                                <i class="material-icons-outlined me-1">cancel</i>
                                            @endif
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <div class="action-buttons">
                                            <a href="{{ route('employee.wholesaler_orders.show', $order->id) }}"
                                               class="btn-action btn-view" title="View Order Details">
                                                <i class="material-icons-outlined" style="font-size: 18px;">visibility</i>
                                            </a>
                                            <a href="{{ route('employee.wholesaler_orders.invoice.download', $order->id) }}"
                                               class="btn-action btn-invoice" title="Download Invoice">
                                                <i class="material-icons-outlined" style="font-size: 18px;">download</i>
                                            </a>
                                            <a href="{{ route('employee.wholesaler.print.out', $order->id) }}" target="_blank"
                                               class="btn-action btn-print" title="Print Label">
                                                <i class="material-icons-outlined" style="font-size: 18px;">print</i>
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
            <div class="card order-card">
                <div class="card-body text-center py-5 empty-state">
                    <i class="material-icons-outlined mb-3">inventory</i>
                    <h4 class="mb-2">No Orders Found</h4>
                    <p class="text-muted mb-4">
                        @if(request('status'))
                            No {{ ucfirst(request('status')) }} orders found matching your criteria.
                        @else
                            No wholesaler orders available at the moment.
                        @endif
                    </p>
                    @if(request()->hasAny(['search', 'status', 'wholesaler_id', 'date_from', 'date_to']))
                        <button onclick="window.location.href='{{ route('employee.wholesaler_orders.index') }}'" class="btn btn-primary">
                            <i class="material-icons-outlined">clear_all</i>
                            Clear Filters
                        </button>
                    @endif
                </div>
            </div>
        @endif

    </div>

    <!-- Bulk Status Modal -->
    <div class="modal fade" id="bulkStatusModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Status for Selected Orders</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">New Status</label>
                        <select class="form-select" id="bulkStatusSelect">
                            <option value="confirmed">Confirmed</option>
                            <option value="processing">Processing</option>
                            <option value="shipped">Shipped</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes (Optional)</label>
                        <textarea class="form-control" id="bulkStatusNotes" rows="3"
                                  placeholder="Add notes about this status change..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="updateBulkStatus()">
                        Update Status
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Selected orders array
        let selectedOrders = @json($selectedOrders);

        // Update bulk actions visibility
        function updateBulkActions() {
            const count = selectedOrders.length;
            const bulkActions = document.getElementById('bulkActions');
            const selectedCount = document.getElementById('selectedCount');
            const printBtn = document.querySelector('.btn-print-selected');

            if (count > 0) {
                bulkActions.classList.add('show');
                selectedCount.textContent = `${count} selected`;

                if (printBtn) {
                    printBtn.classList.remove('disabled');
                    printBtn.innerHTML = `<i class="material-icons-outlined">print</i> Print Selected (${count})`;
                }
            } else {
                bulkActions.classList.remove('show');
                if (printBtn) {
                    printBtn.classList.add('disabled');
                    printBtn.innerHTML = `<i class="material-icons-outlined">print</i> Print Selected (0)`;
                }
            }

            // Update checkboxes
            document.querySelectorAll('.order-select').forEach(checkbox => {
                checkbox.checked = selectedOrders.includes(parseInt(checkbox.value));
            });

            // Update select all checkbox
            const totalCheckboxes = document.querySelectorAll('.order-select').length;
            const checkedCheckboxes = document.querySelectorAll('.order-select:checked').length;
            document.getElementById('selectAll').checked = totalCheckboxes > 0 && totalCheckboxes === checkedCheckboxes;

            // Save to session via AJAX
            saveSelectedOrders();
        }

        // Save selected orders to session
        function saveSelectedOrders() {
            fetch('{{ route("employee.wholesaler_orders.saveSelected") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    selected_orders: selectedOrders
                })
            }).catch(error => console.error('Error saving selection:', error));
        }

        // Handle individual checkbox change
        document.addEventListener('DOMContentLoaded', function() {
            // Initial update
            updateBulkActions();

            // Individual checkbox change
            document.addEventListener('change', function(e) {
                if (e.target.classList.contains('order-select')) {
                    const orderId = parseInt(e.target.value);
                    const isChecked = e.target.checked;

                    if (isChecked && !selectedOrders.includes(orderId)) {
                        selectedOrders.push(orderId);
                    } else if (!isChecked && selectedOrders.includes(orderId)) {
                        selectedOrders = selectedOrders.filter(id => id !== orderId);
                    }

                    updateBulkActions();
                }

                // Select all checkbox
                if (e.target.id === 'selectAll') {
                    const isChecked = e.target.checked;
                    const checkboxes = document.querySelectorAll('.order-select');

                    selectedOrders = [];
                    if (isChecked) {
                        checkboxes.forEach(checkbox => {
                            selectedOrders.push(parseInt(checkbox.value));
                        });
                    }

                    updateBulkActions();
                }
            });
        });

        // Print selected orders
        function printSelectedOrders() {
            if (selectedOrders.length === 0) {
                Swal.fire({
                    title: 'No Orders Selected',
                    text: 'Please select at least one order to print.',
                    icon: 'warning',
                    confirmButtonColor: '#4f46e5'
                });
                return;
            }

            Swal.fire({
                title: 'Print Selected Orders?',
                text: `You are about to print ${selectedOrders.length} order(s).`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, Print',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#10b981'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect to print selection page
                    window.location.href = '{{ route("employee.wholesaler_orders.print.selection") }}';
                }
            });
        }

        // Clear selected orders
        function clearSelectedOrders() {
            if (selectedOrders.length === 0) return;

            Swal.fire({
                title: 'Clear Selection?',
                text: `Are you sure you want to clear ${selectedOrders.length} selected order(s)?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Clear',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#ef4444'
            }).then((result) => {
                if (result.isConfirmed) {
                    selectedOrders = [];
                    updateBulkActions();

                    // Clear from session
                    fetch('{{ route("employee.wholesaler_orders.print.clear") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });

                    Swal.fire({
                        title: 'Cleared!',
                        text: 'Selection has been cleared.',
                        icon: 'success',
                        confirmButtonColor: '#10b981',
                        timer: 1500
                    });
                }
            });
        }

        // Show bulk status modal
        function showBulkStatusModal() {
            if (selectedOrders.length === 0) {
                Swal.fire({
                    title: 'No Orders Selected',
                    text: 'Please select at least one order to update.',
                    icon: 'warning',
                    confirmButtonColor: '#4f46e5'
                });
                return;
            }

            const modal = new bootstrap.Modal(document.getElementById('bulkStatusModal'));
            modal.show();
        }

        // Update bulk status
        function updateBulkStatus() {
            const newStatus = document.getElementById('bulkStatusSelect').value;
            const notes = document.getElementById('bulkStatusNotes').value;

            Swal.fire({
                title: 'Update Status?',
                text: `Update ${selectedOrders.length} order(s) to "${newStatus}"?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, Update',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#4f46e5',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return fetch('{{ route("employee.wholesaler_orders.bulkUpdateStatus") }}', {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            order_ids: selectedOrders,
                            status: newStatus,
                            notes: notes
                        })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (!data.success) {
                                throw new Error(data.message);
                            }
                            return data;
                        });
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Success!',
                        text: result.value.message,
                        icon: 'success',
                        confirmButtonColor: '#10b981'
                    }).then(() => {
                        location.reload();
                    });
                }
            });
        }

        // Quick filter function
        function setQuickFilter(filter) {
            const form = document.getElementById('filterForm');
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'filter';
            input.value = filter;
            form.appendChild(input);
            form.submit();
        }

        // Download invoice function
        function downloadInvoice(orderId) {
            // Show loading
            const button = event.target.closest('.btn-invoice');
            const originalHtml = button.innerHTML;
            button.innerHTML = '<i class="material-icons-outlined spinner">download</i>';
            button.disabled = true;

            // Trigger download
            window.location.href = `/employee/wholesaler-orders/${orderId}/invoice/download`;

            // Reset button after 2 seconds
            setTimeout(() => {
                button.innerHTML = originalHtml;
                button.disabled = false;
            }, 2000);
        }
    </script>
@endpush
