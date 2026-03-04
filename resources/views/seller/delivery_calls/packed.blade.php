@extends('seller.layout.app')

@push('title')
    Packed Delivery Calls
@endpush

@push('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <style>
        :root {
            --primary: #8b5cf6;
            --primary-dark: #7c3aed;
            --packed: #8b5cf6;
            --pending: #f59e0b;
            --packing: #3b82f6;
            --givedriver: #10b981;
            --danger: #ef4444;
            --dark: #0f172a;
            --mid: #64748b;
            --border: #e2e8f0;
            --surface: #ffffff;
            --bg: #f8fafc;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
        }

        .delivery-container {
            padding: 24px;
            max-width: 1600px;
            margin: 0 auto;
        }

        /* Header */
        .page-header {
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
            color: white;
            padding: 2rem;
            border-radius: 16px;
            margin-bottom: 2rem;
            box-shadow: 0 8px 20px rgba(139,92,246,0.2);
        }

        .page-header h1 {
            font-size: 28px;
            font-weight: 800;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .header-count {
            background: rgba(255,255,255,0.2);
            padding: 8px 20px;
            border-radius: 999px;
            display: inline-block;
            font-weight: 600;
        }

        /* Status Tabs */
        .status-tabs {
            display: flex;
            gap: 8px;
            background: white;
            padding: 8px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 24px;
            flex-wrap: wrap;
        }

        .status-tab {
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 13px;
            color: var(--mid);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
        }

        .status-tab:hover {
            background: #f1f5f9;
            color: var(--dark);
        }

        .status-tab.active {
            background: var(--primary);
            color: white;
        }

        .status-tab .count {
            background: rgba(0,0,0,0.1);
            padding: 2px 8px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
        }

        .status-tab.active .count {
            background: rgba(255,255,255,0.3);
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            border-left: 4px solid transparent;
            transition: transform 0.2s;
            cursor: pointer;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .stat-card.pending { border-left-color: var(--pending); }
        .stat-card.packing { border-left-color: var(--packing); }
        .stat-card.packed { border-left-color: var(--packed); }
        .stat-card.givedriver { border-left-color: var(--givedriver); }

        .stat-value {
            font-size: 28px;
            font-weight: 800;
            color: var(--dark);
            line-height: 1.2;
        }

        .stat-label {
            font-size: 12px;
            color: var(--mid);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Ready for Dispatch Banner */
        .dispatch-banner {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border: 2px solid #38bdf8;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
        }

        .dispatch-banner-content {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .dispatch-icon {
            width: 48px;
            height: 48px;
            background: #0ea5e9;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
        }

        .dispatch-text h4 {
            font-weight: 700;
            color: #0369a1;
            margin-bottom: 4px;
        }

        .dispatch-text p {
            color: #64748b;
            margin: 0;
            font-size: 13px;
        }

        .dispatch-btn {
            background: #0ea5e9;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .dispatch-btn:hover {
            background: #0284c7;
            transform: translateY(-1px);
        }

        /* Bulk Actions */
        .bulk-actions {
            background: white;
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 20px;
            border: 2px solid var(--primary);
            box-shadow: 0 4px 12px rgba(139,92,246,0.15);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
        }

        .selected-count {
            background: var(--primary);
            color: white;
            padding: 4px 12px;
            border-radius: 30px;
            font-size: 13px;
            font-weight: 600;
        }

        /* Table Styles */
        .table-container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            overflow: hidden;
        }

        .table-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
        }

        .table-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .search-box {
            position: relative;
            width: 300px;
        }

        .search-box input {
            width: 100%;
            padding: 10px 16px 10px 40px;
            border: 2px solid var(--border);
            border-radius: 8px;
            font-size: 14px;
        }

        .search-box .material-icons-round {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--mid);
            font-size: 18px;
        }

        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead th {
            background: #f8fafc;
            padding: 16px 20px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--mid);
            text-align: left;
            border-bottom: 2px solid var(--border);
            white-space: nowrap;
        }

        tbody td {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
        }

        tbody tr:hover {
            background: #f8fafc;
        }

        tbody tr.dispatched {
            background: #f0fdf4;
        }

        .call-number {
            font-family: monospace;
            font-weight: 700;
            color: var(--primary);
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .status-packed {
            background: #ede9fe;
            color: #5b21b6;
        }

        .waybill-badge {
            display: inline-block;
            padding: 4px 10px;
            background: #dbeafe;
            color: #1e40af;
            border-radius: 6px;
            font-size: 11px;
            font-family: monospace;
            font-weight: 600;
        }

        .action-btn {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            margin: 0 2px;
            text-decoration: none;
        }

        .action-btn.view {
            background: var(--primary);
            color: white;
        }

        .action-btn.dispatch {
            background: #0ea5e9;
            color: white;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .pagination {
            padding: 20px 24px;
            border-top: 1px solid var(--border);
            display: flex;
            justify-content: flex-end;
        }

        /* Progress Indicator */
        .progress-step {
            display: flex;
            align-items: center;
            gap: 4px;
            font-size: 11px;
            color: var(--mid);
        }

        .progress-step .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--packed);
        }

        @media (max-width: 1200px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .delivery-container { padding: 16px; }
            .table-header { flex-direction: column; align-items: stretch; }
            .search-box { width: 100%; }
            .stats-grid { grid-template-columns: 1fr; }
            .dispatch-banner { flex-direction: column; text-align: center; }
            .dispatch-banner-content { flex-direction: column; }
        }
    </style>
@endpush

@section('content')
    <div class="delivery-container">
        <!-- Header -->
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1>
                        <span class="material-icons-round">checklist</span>
                        Packed & Ready
                    </h1>
                    <p class="mb-0 opacity-75">Orders that are packed and ready for courier dispatch</p>
                </div>
                <div class="header-count">
                    <span class="fw-bold">{{ $calls->total() }}</span> packed calls
                </div>
            </div>
        </div>

        <!-- Status Tabs -->
        <div class="status-tabs">
            <a href="{{ route('seller.delivery_calls.pending') }}" class="status-tab">
                <span class="material-icons-round">pending_actions</span>
                Pending
                <span class="count">{{ $counts['pending'] }}</span>
            </a>
            <a href="{{ route('seller.delivery_calls.packing') }}" class="status-tab">
                <span class="material-icons-round">inventory</span>
                Packing
                <span class="count">{{ $counts['packing'] }}</span>
            </a>
            <a href="{{ route('seller.delivery_calls.packed') }}" class="status-tab active">
                <span class="material-icons-round">checklist</span>
                Packed
                <span class="count">{{ $counts['packed'] }}</span>
            </a>
            <a href="{{ route('seller.delivery_calls.givedriver') }}" class="status-tab">
                <span class="material-icons-round">local_shipping</span>
                Give Driver
                <span class="count">{{ $counts['givedriver'] }}</span>
            </a>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card pending" onclick="window.location.href='{{ route('seller.delivery_calls.pending') }}'">
                <div class="stat-value">{{ $counts['pending'] }}</div>
                <div class="stat-label">Pending</div>
            </div>
            <div class="stat-card packing" onclick="window.location.href='{{ route('seller.delivery_calls.packing') }}'">
                <div class="stat-value">{{ $counts['packing'] }}</div>
                <div class="stat-label">Packing</div>
            </div>
            <div class="stat-card packed" onclick="window.location.href='{{ route('seller.delivery_calls.packed') }}'">
                <div class="stat-value">{{ $counts['packed'] }}</div>
                <div class="stat-label">Packed</div>
            </div>
            <div class="stat-card givedriver" onclick="window.location.href='{{ route('seller.delivery_calls.givedriver') }}'">
                <div class="stat-value">{{ $counts['givedriver'] }}</div>
                <div class="stat-label">Give Driver</div>
            </div>
        </div>

        <!-- Dispatch Ready Banner -->
        @if($calls->count() > 0)
            <div class="dispatch-banner">
                <div class="dispatch-banner-content">
                    <div class="dispatch-icon">🚚</div>
                    <div class="dispatch-text">
                        <h4>{{ $calls->count() }} Orders Ready for Dispatch</h4>
                        <p>These orders are packed and waiting to be handed to the courier driver</p>
                    </div>
                </div>
                <div>
                    <button class="dispatch-btn" onclick="bulkDispatch()">
                        <span class="material-icons-round">local_shipping</span>
                        Bulk Dispatch to Courier
                    </button>
                </div>
            </div>
        @endif

        <!-- Bulk Actions -->
        <div class="bulk-actions">
            <div class="d-flex align-items-center gap-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="selectAll" style="width: 18px; height: 18px;">
                    <label class="form-check-label fw-semibold ms-2">Select All</label>
                </div>
                <span class="selected-count" id="selectedCount">0 selected</span>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-primary" id="bulkDispatchBtn" disabled onclick="bulkDispatch()">
                    <span class="material-icons-round me-1">local_shipping</span>
                    Dispatch to Courier
                </button>
            </div>
        </div>

        <!-- Main Table -->
        <div class="table-container">
            <div class="table-header">
                <div class="table-title">
                    <span class="material-icons-round" style="color: var(--primary);">checklist</span>
                    Packed Orders ({{ $calls->count() }})
                </div>
                <div class="search-box">
                    <span class="material-icons-round">search</span>
                    <input type="text" id="searchInput" placeholder="Search packed orders...">
                </div>
            </div>

            @if($calls->count() > 0)
                <div class="table-responsive">
                    <table>
                        <thead>
                        <tr>
                            <th style="width: 40px;"></th>
                            <th>Call #</th>
                            <th>Order Code</th>
                            <th>Customer</th>
                            <th>Destination</th>
                            <th>Pickup Origin</th>
                            <th>Item</th>
                            <th>Qty</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($calls as $call)
                            <tr id="row_{{ $call->id }}" class="{{ $call->curfox_waybill ? 'dispatched' : '' }}">
                                <td>
                                    <input type="checkbox" class="rowCheckbox" value="{{ $call->id }}" {{ $call->curfox_waybill ? 'disabled' : '' }}>
                                </td>
                                <td>
                                    <span class="call-number">{{ $call->call_number }}</span>
                                </td>
                                <td>{{ $call->order_code ?? '-' }}</td>
                                <td>
                                    <div class="fw-semibold">{{ $call->customer_name }}</div>
                                    <div style="font-size: 11px; color: var(--mid);">{{ $call->customer_phone_01 }}</div>
                                </td>
                                <td>
                                    <div>{{ $call->city ?? $call->district }}</div>
                                    @if($call->destination_city_id)
                                        <small class="text-success">✓ API City</small>
                                    @endif
                                </td>
                                <td>
                                        <span class="badge bg-info bg-opacity-10 text-info px-2 py-1 rounded">
                                            {{ $call->effectiveOriginCityName() }}
                                        </span>
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ Str::limit($call->item_name, 20) }}</div>
                                    <small class="text-muted">{{ $call->item_code }}</small>
                                </td>
                                <td class="fw-bold">{{ $call->qty }}</td>
                                <td class="fw-bold text-primary">Rs. {{ number_format($call->total, 2) }}</td>
                                <td>
                                    @if($call->curfox_waybill)
                                        <span class="waybill-badge">{{ $call->curfox_waybill }}</span>
                                    @else
                                        <span class="status-badge status-packed">Packed</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('seller.delivery_calls.show', $call->id) }}"
                                           class="action-btn view" title="View Details">
                                            <span class="material-icons-round">visibility</span>
                                        </a>
                                        @if(!$call->curfox_waybill)
                                            <button class="action-btn dispatch" onclick="dispatchSingle({{ $call->id }})" title="Dispatch to Courier">
                                                <span class="material-icons-round">local_shipping</span>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="pagination">
                    {{ $calls->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <span class="material-icons-round" style="font-size: 64px; color: var(--border);">checklist</span>
                    <h5 class="mt-3">No Packed Orders</h5>
                    <p class="text-muted">Move calls from Packing to see them here</p>
                    <a href="{{ route('seller.delivery_calls.packing') }}" class="btn btn-primary mt-3">
                        <span class="material-icons-round me-1">inventory</span>
                        View Packing Calls
                    </a>
                </div>
            @endif
        </div>
    </div>

    @push('script')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            const CSRF_TOKEN = '{{ csrf_token() }}';
            const DISPATCH_URL = '/seller/delivery-calls/dispatch';

            // Checkbox handling
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.rowCheckbox');
            const selectedCount = document.getElementById('selectedCount');
            const bulkDispatchBtn = document.getElementById('bulkDispatchBtn');

            function updateSelection() {
                const checked = document.querySelectorAll('.rowCheckbox:checked').length;
                selectedCount.textContent = checked + ' selected';
                bulkDispatchBtn.disabled = checked === 0;

                if (selectAll) {
                    selectAll.indeterminate = checked > 0 && checked < checkboxes.length;
                    selectAll.checked = checked === checkboxes.length;
                }
            }

            selectAll?.addEventListener('change', function() {
                checkboxes.forEach(cb => cb.checked = this.checked);
                updateSelection();
            });

            checkboxes.forEach(cb => cb.addEventListener('change', updateSelection));

            // Search functionality
            document.getElementById('searchInput')?.addEventListener('keyup', function() {
                const term = this.value.toLowerCase();
                document.querySelectorAll('tbody tr').forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(term) ? '' : 'none';
                });
            });

            // Dispatch single order
            async function dispatchSingle(id) {
                const result = await Swal.fire({
                    title: 'Dispatch to Courier?',
                    text: 'This will create a waybill and mark as ready for pickup',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Dispatch',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#0ea5e9'
                });

                if (result.isConfirmed) {
                    try {
                        const response = await fetch(`${DISPATCH_URL}/${id}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': CSRF_TOKEN,
                                'Content-Type': 'application/json'
                            }
                        });

                        const data = await response.json();
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Dispatched!',
                                text: `Waybill: ${data.waybill}`,
                                timer: 2000
                            }).then(() => location.reload());
                        } else {
                            throw new Error(data.message);
                        }
                    } catch (error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Dispatch Failed',
                            text: error.message
                        });
                    }
                }
            }

            // Bulk dispatch
            async function bulkDispatch() {
                const selected = Array.from(document.querySelectorAll('.rowCheckbox:checked')).map(cb => cb.value);
                if (selected.length === 0) return;

                const result = await Swal.fire({
                    title: 'Bulk Dispatch to Courier?',
                    html: `Dispatch <strong>${selected.length}</strong> order(s) to courier?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Dispatch All',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#0ea5e9'
                });

                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Dispatching...',
                        html: 'Please wait while orders are dispatched',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Simulate bulk dispatch - replace with actual API call
                    setTimeout(() => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Dispatched!',
                            text: `${selected.length} orders dispatched successfully`,
                            timer: 2000
                        }).then(() => location.reload());
                    }, 2000);
                }
            }
        </script>
    @endpush
@endsection
