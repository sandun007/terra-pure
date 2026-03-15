@extends('seller.layout.app')

@push('title')
    Packing Delivery Calls
@endpush

@push('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <style>
        :root {
            --primary: #3b82f6;
            --primary-dark: #2563eb;
            --packing: #3b82f6;
            --pending: #f59e0b;
            --packed: #8b5cf6;
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
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            padding: 2rem;
            border-radius: 16px;
            margin-bottom: 2rem;
            box-shadow: 0 8px 20px rgba(59,130,246,0.2);
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

        /* Bulk Actions Bar */
        .bulk-actions {
            background: white;
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 20px;
            border: 2px solid var(--primary);
            box-shadow: 0 4px 12px rgba(59,130,246,0.15);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
        }

        .bulk-actions-left {
            display: flex;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }

        .selected-count {
            background: var(--primary);
            color: white;
            padding: 4px 12px;
            border-radius: 30px;
            font-size: 13px;
            font-weight: 600;
        }

        /* Bulk Origin Bar */
        .bulk-origin-bar {
            display: none;
            background: #fffbeb;
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 20px;
            border: 2px solid #f59e0b;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }

        .bulk-origin-bar.show {
            display: flex;
        }

        .origin-input-group {
            flex: 1;
            min-width: 250px;
            position: relative;
        }

        .origin-input-group input {
            width: 100%;
            padding: 10px 14px;
            border: 2px solid var(--border);
            border-radius: 8px;
            font-size: 14px;
        }

        .origin-input-group input:focus {
            border-color: var(--primary);
            outline: none;
        }

        .city-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 2px solid var(--primary);
            border-radius: 8px;
            max-height: 200px;
            overflow-y: auto;
            z-index: 1000;
            display: none;
        }

        .city-dropdown.open {
            display: block;
        }

        .city-item {
            padding: 10px 14px;
            cursor: pointer;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
        }

        .city-item:hover {
            background: #eef2ff;
        }

        .city-name {
            font-weight: 600;
        }

        .city-state {
            font-size: 11px;
            color: var(--mid);
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

        .status-packing {
            background: #dbeafe;
            color: #1e40af;
        }

        .origin-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
            background: #f1f5f9;
            color: var(--mid);
        }

        .origin-badge.custom {
            background: #dbeafe;
            color: #1e40af;
        }

        .origin-edit-btn {
            background: none;
            border: none;
            color: var(--mid);
            cursor: pointer;
            padding: 2px 5px;
            border-radius: 4px;
            margin-left: 4px;
        }

        .origin-edit-btn:hover {
            background: #e2e8f0;
            color: var(--dark);
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

        .action-btn.next {
            background: var(--packed);
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
            background: var(--packing);
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
                        <span class="material-icons-round">inventory</span>
                        Packing Stage
                    </h1>
                    <p class="mb-0 opacity-75">Orders currently being packed and prepared for shipment</p>
                </div>
                <div class="header-count">
                    <span class="fw-bold">{{ $calls->total() }}</span> calls in packing
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
            <a href="{{ route('seller.delivery_calls.packing') }}" class="status-tab active">
                <span class="material-icons-round">inventory</span>
                Packing
                <span class="count">{{ $counts['packing'] }}</span>
            </a>
            <a href="{{ route('seller.delivery_calls.packed') }}" class="status-tab">
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

        <!-- Bulk Actions -->
        <div class="bulk-actions">
            <div class="bulk-actions-left">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="selectAll" style="width: 18px; height: 18px;">
                    <label class="form-check-label fw-semibold ms-2">Select All</label>
                </div>
                <span class="selected-count" id="selectedCount">0 selected</span>
                <span class="text-muted" style="font-size: 13px;">
                    <span class="material-icons-round" style="font-size: 16px; vertical-align: middle;">info</span>
                    Selected calls will be moved to Packed stage
                </span>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-primary" id="bulkNextBtn" disabled onclick="bulkNext()">
                    <span class="material-icons-round me-1">arrow_forward</span>
                    Move to Packed
                </button>
            </div>
        </div>

        <!-- Bulk Origin Bar -->
        <div class="bulk-origin-bar" id="bulkOriginBar">
            <span class="fw-bold" style="color: #f59e0b;">
                <span class="material-icons-round me-1" style="font-size: 18px;">location_on</span>
                Set Pickup Origin:
            </span>
            <div class="origin-input-group">
                <input type="text" id="bulkOriginInput" placeholder="Type city name..." autocomplete="off">
                <div class="city-dropdown" id="bulkOriginDropdown"></div>
            </div>
            <button class="btn btn-warning" id="bulkOriginApplyBtn" disabled onclick="applyBulkOrigin()">
                Apply to Selected
            </button>
        </div>

        <!-- Main Table -->
        <div class="table-container">
            <div class="table-header">
                <div class="table-title">
                    <span class="material-icons-round" style="color: var(--primary);">inventory</span>
                    Packing Calls ({{ $calls->count() }})
                </div>
                <div class="search-box">
                    <span class="material-icons-round">search</span>
                    <input type="text" id="searchInput" placeholder="Search calls...">
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
                            <th>Phone</th>
                            <th>Destination</th>
                            <th>Pickup Origin</th>
                            <th>Item</th>
                            <th>Qty</th>
                            <th>Total</th>
                            <th>Progress</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($calls as $call)
                            @php
                                $progress = $call->progress_percentage ?? 50;
                            @endphp
                            <tr id="row_{{ $call->id }}">
                                <td>
                                    <input type="checkbox" class="rowCheckbox" value="{{ $call->id }}">
                                </td>
                                <td>
                                    <span class="call-number">{{ $call->call_number }}</span>
                                </td>
                                <td>{{ $call->order_code ?? '-' }}</td>
                                <td>
                                    <div class="fw-semibold">{{ $call->customer_name }}</div>
                                    <div style="font-size: 11px; color: var(--mid);">{{ Str::limit($call->customer_address, 30) }}</div>
                                </td>
                                <td>
                                    <div>{{ $call->customer_phone_01 }}</div>
                                    @if($call->customer_phone_02)
                                        <small>{{ $call->customer_phone_02 }}</small>
                                    @endif
                                </td>
                                <td>
                                    <div>{{ $call->city ?? $call->district }}</div>
                                    @if($call->destination_city_id)
                                        <small class="text-success">✓ API City</small>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                            <span class="origin-badge {{ $call->hasCustomOrigin() ? 'custom' : '' }}"
                                                  id="origin_badge_{{ $call->id }}">
                                                {{ $call->effectiveOriginCityName() }}
                                                @if(!$call->hasCustomOrigin())
                                                    <small>(default)</small>
                                                @endif
                                            </span>
                                        <button class="origin-edit-btn" onclick="openOriginModal({{ $call->id }}, '{{ $call->effectiveOriginCityName() }}', '{{ $call->effectiveOriginStateName() }}', {{ $call->effectiveOriginCityId() ?? 'null' }})">
                                            <span class="material-icons-round" style="font-size: 16px;">edit</span>
                                        </button>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ Str::limit($call->item_name, 20) }}</div>
                                    <small class="text-muted">{{ $call->item_code }}</small>
                                </td>
                                <td class="fw-bold">{{ $call->qty }}</td>
                                <td class="fw-bold text-primary">Rs. {{ number_format($call->total, 2) }}</td>
                                <td>
                                    <div class="progress-step">
                                        <span class="dot"></span>
                                        <span>Packing</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('seller.delivery_calls.show', $call->id) }}"
                                           class="action-btn view" title="View Details">
                                            <span class="material-icons-round">visibility</span>
                                        </a>
                                        <form method="POST" action="{{ route('seller.delivery_calls.update_status', $call->id) }}"
                                              class="d-inline" onsubmit="return confirm('Mark as Packed?')">
                                            @csrf
                                            <input type="hidden" name="status" value="packed">
                                            <button type="submit" class="action-btn next" title="Move to Packed">
                                                <span class="material-icons-round">checklist</span>
                                            </button>
                                        </form>
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
                    <span class="material-icons-round" style="font-size: 64px; color: var(--border);">inventory</span>
                    <h5 class="mt-3">No Calls in Packing Stage</h5>
                    <p class="text-muted">Move calls from Pending to start packing</p>
                    <a href="{{ route('seller.delivery_calls.pending') }}" class="btn btn-primary mt-3">
                        <span class="material-icons-round me-1">pending_actions</span>
                        View Pending Calls
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Origin Edit Modal -->
    <div class="modal fade" id="originModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background: #3b82f6; color: white;">
                    <h5 class="modal-title">
                        <span class="material-icons-round me-2">location_on</span>
                        Edit Pickup Origin
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted mb-3">Set the pickup location for this delivery call.</p>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Search City</label>
                        <input type="text" id="modalOriginInput" class="form-control" placeholder="Type city name...">
                        <div class="city-dropdown" id="modalOriginDropdown" style="position: absolute; width: 90%;"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Selected City</label>
                        <input type="text" id="modalSelectedCity" class="form-control" readonly>
                    </div>

                    <input type="hidden" id="modalCallId">
                    <input type="hidden" id="modalCityId">
                    <input type="hidden" id="modalCityName">
                    <input type="hidden" id="modalStateName">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveOriginBtn" onclick="saveOrigin()">Save Origin</button>
                </div>
            </div>
        </div>
    </div>

    @push('script')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            const CURFOX_CITIES = @json($curfoxCities ?? []);
            const CSRF_TOKEN = '{{ csrf_token() }}';
            const BULK_ORIGIN_URL = '{{ route("seller.delivery_calls.bulk_origin") }}';

            // Checkbox handling
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.rowCheckbox');
            const selectedCount = document.getElementById('selectedCount');
            const bulkNextBtn = document.getElementById('bulkNextBtn');
            const bulkOriginBar = document.getElementById('bulkOriginBar');

            function updateSelection() {
                const checked = document.querySelectorAll('.rowCheckbox:checked').length;
                selectedCount.textContent = checked + ' selected';
                bulkNextBtn.disabled = checked === 0;

                if (checked > 0) {
                    bulkOriginBar.classList.add('show');
                } else {
                    bulkOriginBar.classList.remove('show');
                }

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

            // Bulk move to packed
            async function bulkNext() {
                const selected = Array.from(document.querySelectorAll('.rowCheckbox:checked')).map(cb => cb.value);
                if (selected.length === 0) return;

                const result = await Swal.fire({
                    title: 'Bulk Move to Packed?',
                    html: `Move <strong>${selected.length}</strong> call(s) to Packed stage?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Move',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#8b5cf6'
                });

                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route("seller.delivery_calls.bulk_status") }}';

                    const csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '_token';
                    csrf.value = CSRF_TOKEN;

                    const ids = document.createElement('input');
                    ids.type = 'hidden';
                    ids.name = 'ids';
                    ids.value = JSON.stringify(selected);

                    const status = document.createElement('input');
                    status.type = 'hidden';
                    status.name = 'status';
                    status.value = 'packed';

                    form.appendChild(csrf);
                    form.appendChild(ids);
                    form.appendChild(status);
                    document.body.appendChild(form);
                    form.submit();
                }
            }

            // Origin autocomplete
            let activeDropdown = null;

            function filterCities(term) {
                if (!term || term.length < 1) return [];
                const t = term.toLowerCase();
                return CURFOX_CITIES.filter(c =>
                    c.name.toLowerCase().includes(t) ||
                    (c.state_name && c.state_name.toLowerCase().includes(t))
                ).slice(0, 30);
            }

            function setupOriginAutocomplete(inputId, dropdownId, callback) {
                const input = document.getElementById(inputId);
                const dropdown = document.getElementById(dropdownId);

                input.addEventListener('input', function() {
                    const results = filterCities(this.value);
                    dropdown.innerHTML = '';

                    if (results.length === 0) {
                        dropdown.innerHTML = '<div class="city-item">No cities found</div>';
                    } else {
                        results.forEach(city => {
                            const div = document.createElement('div');
                            div.className = 'city-item';
                            div.innerHTML = `
                                <span class="city-name">${city.name}</span>
                                <span class="city-state">${city.state_name || ''}</span>
                            `;
                            div.addEventListener('click', () => {
                                callback(city);
                                dropdown.classList.remove('open');
                            });
                            dropdown.appendChild(div);
                        });
                    }
                    dropdown.classList.add('open');
                });

                input.addEventListener('blur', () => {
                    setTimeout(() => dropdown.classList.remove('open'), 200);
                });
            }

            // Bulk origin setup
            setupOriginAutocomplete('bulkOriginInput', 'bulkOriginDropdown', (city) => {
                document.getElementById('bulkOriginInput').value = city.name;
                document.getElementById('bulkOriginApplyBtn').disabled = false;
                window.selectedBulkCity = city;
            });

            async function applyBulkOrigin() {
                if (!window.selectedBulkCity) return;

                const selected = Array.from(document.querySelectorAll('.rowCheckbox:checked')).map(cb => cb.value);
                if (selected.length === 0) return;

                const result = await Swal.fire({
                    title: 'Update Pickup Origin?',
                    html: `Set pickup origin to <strong>${window.selectedBulkCity.name}</strong> for ${selected.length} call(s)?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Update',
                    cancelButtonText: 'Cancel'
                });

                if (result.isConfirmed) {
                    try {
                        const response = await fetch(BULK_ORIGIN_URL, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': CSRF_TOKEN
                            },
                            body: JSON.stringify({
                                ids: selected,
                                origin_city_id: window.selectedBulkCity.id,
                                origin_city_name: window.selectedBulkCity.name,
                                origin_state_name: window.selectedBulkCity.state_name || ''
                            })
                        });

                        const data = await response.json();
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Updated!',
                                text: data.message,
                                timer: 2000
                            }).then(() => location.reload());
                        } else {
                            throw new Error(data.message);
                        }
                    } catch (error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: error.message
                        });
                    }
                }
            }

            // Single origin modal
            function openOriginModal(callId, currentCity, currentState, cityId) {
                document.getElementById('modalCallId').value = callId;
                document.getElementById('modalSelectedCity').value = currentCity + (currentState ? ` (${currentState})` : '');
                document.getElementById('modalCityId').value = cityId || '';
                document.getElementById('modalCityName').value = currentCity;
                document.getElementById('modalStateName').value = currentState || '';

                setupOriginAutocomplete('modalOriginInput', 'modalOriginDropdown', (city) => {
                    document.getElementById('modalOriginInput').value = city.name;
                    document.getElementById('modalSelectedCity').value = `${city.name} (${city.state_name || ''})`;
                    document.getElementById('modalCityId').value = city.id;
                    document.getElementById('modalCityName').value = city.name;
                    document.getElementById('modalStateName').value = city.state_name || '';
                });

                new bootstrap.Modal(document.getElementById('originModal')).show();
            }

            async function saveOrigin() {
                const callId = document.getElementById('modalCallId').value;
                const cityId = document.getElementById('modalCityId').value;
                const cityName = document.getElementById('modalCityName').value;
                const stateName = document.getElementById('modalStateName').value;

                if (!cityId) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'No City Selected',
                        text: 'Please select a city from the dropdown'
                    });
                    return;
                }

                try {
                    const response = await fetch(`/seller/delivery-calls/${callId}/origin`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': CSRF_TOKEN
                        },
                        body: JSON.stringify({
                            origin_city_id: cityId,
                            origin_city_name: cityName,
                            origin_state_name: stateName
                        })
                    });

                    const data = await response.json();
                    if (data.success) {
                        // Update badge
                        const badge = document.getElementById(`origin_badge_${callId}`);
                        if (badge) {
                            badge.innerHTML = cityName + ' <small>(custom)</small>';
                            badge.classList.add('custom');
                        }

                        bootstrap.Modal.getInstance(document.getElementById('originModal')).hide();
                        Swal.fire({
                            icon: 'success',
                            title: 'Updated!',
                            text: 'Pickup origin updated successfully',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    } else {
                        throw new Error(data.message);
                    }
                } catch (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: error.message
                    });
                }
            }
        </script>
    @endpush
@endsection
