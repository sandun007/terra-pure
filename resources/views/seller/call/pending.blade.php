@extends('seller.layout.app')

@push('title')
    Pending Calls
@endpush

@push('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <style>
        :root {
            --primary: #8b5cf6; --primary-dark: #7c3aed;
            --success: #10b981; --danger: #ef4444;
            --warning: #f59e0b; --info: #3b82f6;
            --dark: #0f172a; --mid: #64748b;
            --border: #e2e8f0; --surface: #ffffff; --bg: #f8fafc;
        }
        body { font-family: 'DM Sans', sans-serif; background: var(--bg); }

        .page-header {
            background: linear-gradient(135deg,#8b5cf6 0%,#7c3aed 100%);
            color: white;
            padding: 2rem;
            border-radius: 16px;
            margin-bottom: 2rem;
            box-shadow: 0 8px 20px rgba(139,92,246,.2);
        }
        .page-header h1 { font-size: 28px; font-weight: 800; margin-bottom: .5rem; }
        .call-count {
            background: rgba(255,255,255,.2);
            padding: 8px 20px;
            border-radius: 999px;
            display: inline-block;
            font-weight: 600;
        }

        .filter-section {
            background: white;
            border-radius: 12px;
            padding: 1.25rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,.05);
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            align-items: flex-end;
        }
        .filter-group { flex: 1; min-width: 180px; }
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
            outline: none;
            transition: border-color .2s;
        }
        .filter-select:focus { border-color: var(--primary); }

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
        .clear-filters:hover { background: var(--border); }

        .card { border: none; border-radius: 16px; box-shadow: 0 2px 8px rgba(0,0,0,.05); }
        .card-header { background: white; border-bottom: 1px solid var(--border); padding: 1.5rem; }
        .table-responsive { border-radius: 0 0 16px 16px; overflow: auto; }
        .table { margin: 0; border-collapse: collapse; width: 100%; }
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
        .table tbody tr:hover { background: rgba(139,92,246,.03); }

        .product-cell { display: flex; flex-direction: column; gap: 4px; }
        .product-name { font-weight: 600; color: var(--dark); margin: 0; font-size: 14px; }
        .product-code {
            font-size: 12px;
            color: var(--mid);
            font-family: monospace;
            background: var(--bg);
            padding: 2px 8px;
            border-radius: 4px;
            display: inline-block;
        }

        .badge-platform {
            background: rgba(139,92,246,.1);
            color: var(--primary);
            padding: 4px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            white-space: nowrap;
        }

        .call-select {
            width: 160px;
            padding: 6px 10px;
            border: 2px solid var(--border);
            border-radius: 30px;
            font-size: 11px;
            font-weight: 600;
            background: white;
            cursor: pointer;
            transition: all .2s;
            outline: none;
        }
        .call-select:hover { border-color: var(--primary); }
        .call-select.pending-call  { border-color: #f59e0b; background: #fef3c7; color: #92400e; }
        .call-select.called        { border-color: #3b82f6; background: #dbeafe; color: #1e40af; }
        .call-select.confirmed     { border-color: #10b981; background: #d1fae5; color: #065f46; }
        .call-select.not-answered  { border-color: #64748b; background: #f1f5f9; color: #475569; }
        .call-select.cancelled     { border-color: #ef4444; background: #fee2e2; color: #991b1b; }

        .action-btn {
            width: 36px; height: 36px;
            border-radius: 8px;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all .2s;
            cursor: pointer;
            text-decoration: none;
            flex-shrink: 0;
        }
        .action-btn .material-icons-round { font-size: 18px; }
        .action-btn.view { background: var(--primary); color: white; }
        .action-btn.edit { background: var(--warning);  color: white; }
        .action-btn.call { background: var(--success);  color: white; }
        .action-btn:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,.15); }

        .search-wrapper { position: relative; display: inline-block; width: 100%; max-width: 400px; }
        .search-input {
            width: 100%;
            padding: 10px 16px 10px 40px;
            border: 2px solid var(--border);
            border-radius: 8px;
            font-size: 14px;
            outline: none;
            transition: border-color .2s;
        }
        .search-input:focus { border-color: var(--primary); }
        .search-icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--mid); }

        .empty-state { text-align: center; padding: 60px 20px; }
        .empty-icon  { font-size: 64px; color: var(--border); margin-bottom: 16px; }

        .status-toast {
            position: fixed;
            bottom: 24px; right: 24px;
            background: var(--dark); color: white;
            padding: 12px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,.15);
            z-index: 9999;
            display: flex;
            align-items: center;
            gap: 12px;
            animation: slideIn 0.3s ease-out;
        }
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to   { transform: translateX(0);    opacity: 1; }
        }

        @media(max-width: 768px) {
            .page-header { padding: 1.5rem; }
            .page-header h1 { font-size: 22px; }
            .filter-section { flex-direction: column; }
            .filter-group { width: 100%; }
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid px-3 px-md-4 px-lg-5 py-4">

        {{-- ── Page Header ── --}}
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1>
                        <span class="material-icons-round me-2" style="vertical-align: middle; font-size: 32px;">phone_in_talk</span>
                        Pending Calls
                    </h1>
                    <p class="mb-0 opacity-75">Orders that need to be called</p>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <div class="call-count">
                        <span class="material-icons-round me-2" style="vertical-align: middle;">phone</span>
                        <span id="totalCallsCount">{{ $count }}</span> {{ $count === 1 ? 'Call' : 'Calls' }}
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Flash Messages ── --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <span class="material-icons-round me-2">check_circle</span>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <span class="material-icons-round me-2">error</span>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- ── Filter Section ── --}}
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
                <button class="clear-filters" onclick="clearFilter()">
                    <span class="material-icons-round" style="font-size: 16px;">clear</span>
                    Clear Filter
                </button>
            </div>
        </div>

        {{-- ── Calls Table ── --}}
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="mb-0 fw-bold">
                            <span class="material-icons-round me-2" style="vertical-align: middle;">phone</span>
                            Calls List
                            <span id="filteredCount" class="badge bg-secondary ms-2">{{ $count }}</span>
                        </h5>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <a href="{{ route('seller.create.new-order') }}" class="btn btn-primary">
                            <span class="material-icons-round me-2" style="font-size: 18px;">add</span>
                            New Order
                        </a>
                    </div>
                </div>
            </div>

            @if($count > 0)

                {{-- Search --}}
                <div class="px-4 pt-3">
                    <div class="search-wrapper">
                        <span class="material-icons-round search-icon">search</span>
                        <input type="text"
                               id="searchInput"
                               class="search-input"
                               placeholder="Search by order ID, name, phone, product...">
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover" id="callsTable">
                            <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Product</th>
                                <th>Customer</th>
                                <th>Phone</th>
                                <th>City</th>
                                <th>Platform</th>
                                <th>Call Status</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody id="callsTableBody">
                            @foreach($calls as $call)
                                <tr data-call-id="{{ $call->id }}"
                                    data-platform="{{ $call->platform ?? 'manual' }}"
                                    data-call-status="{{ $call->call_status ?? 'Pending Call' }}">

                                    {{-- Order ID --}}
                                    <td>
                                        <strong style="font-family: monospace; font-size: 13px;">
                                            {{ $call->order_number }}
                                        </strong>
                                    </td>

                                    {{-- Product --}}
                                    <td>
                                        <div class="product-cell">
                                                <span class="product-name">
                                                    {{ \Illuminate\Support\Str::limit($call->product_name, 25) }}
                                                </span>
                                            <span class="product-code">{{ $call->product_code }}</span>
                                        </div>
                                    </td>

                                    {{-- Customer --}}
                                    <td>
                                        <div style="font-weight: 600;">{{ $call->customer_name }}</div>
                                        @if($call->district ?? $call->destination_city_name)
                                            <small class="text-muted">
                                                {{ $call->district ?? $call->destination_city_name }}
                                            </small>
                                        @endif
                                    </td>

                                    {{-- Phone --}}
                                    <td>
                                        <a href="tel:{{ $call->customer_phone }}"
                                           style="font-family: monospace; color: var(--dark); text-decoration: none; font-weight: 600;">
                                            {{ $call->customer_phone }}
                                        </a>
                                        @if($call->customer_phone_2)
                                            <br>
                                            <a href="tel:{{ $call->customer_phone_2 }}"
                                               style="font-family: monospace; color: var(--mid); text-decoration: none; font-size: 12px;">
                                                {{ $call->customer_phone_2 }}
                                            </a>
                                        @endif
                                    </td>

                                    {{-- City --}}
                                    <td>
                                        {{ $call->district ?? $call->destination_city_name ?? '—' }}
                                    </td>

                                    {{-- Platform --}}
                                    <td>
                                            <span class="badge-platform">
                                                {{ ucfirst($call->platform ?? 'Manual') }}
                                            </span>
                                    </td>

                                    {{-- Call Status --}}
                                    <td>
                                        <select class="call-select {{ strtolower(str_replace(' ', '-', $call->call_status ?? 'Pending Call')) }}"
                                                onchange="updateCallStatus({{ $call->id }}, this.value, this)">
                                            <option value="Pending Call" {{ ($call->call_status ?? 'Pending Call') === 'Pending Call' ? 'selected' : '' }}>⏳ Pending</option>
                                            <option value="Called"       {{ ($call->call_status ?? '') === 'Called'       ? 'selected' : '' }}>📞 Called</option>
                                            <option value="Confirmed"    {{ ($call->call_status ?? '') === 'Confirmed'    ? 'selected' : '' }}>✅ Confirmed</option>
                                            <option value="Not Answered" {{ ($call->call_status ?? '') === 'Not Answered' ? 'selected' : '' }}>❓ Not Answered</option>
                                            <option value="Cancelled"    {{ ($call->call_status ?? '') === 'Cancelled'    ? 'selected' : '' }}>❌ Cancelled</option>
                                        </select>
                                    </td>

                                    {{-- Actions --}}
                                    <td>
                                        <div class="d-flex gap-2 flex-nowrap">

                                            {{-- View Order --}}
                                            <a href="{{ route('seller.order.show', $call->id) }}"
                                               class="action-btn view"
                                               title="View Order Details">
                                                <span class="material-icons-round">visibility</span>
                                            </a>

                                            {{-- Edit Order (full edit page) --}}
                                            <a href="{{ route('seller.order.edit', $call->id) }}"
                                               class="action-btn edit"
                                               title="Edit Order">
                                                <span class="material-icons-round">edit</span>
                                            </a>

                                            {{-- Call Now --}}
                                            <a href="tel:{{ $call->customer_phone }}"
                                               class="action-btn call"
                                               title="Call Customer Now">
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
                            <span class="material-icons-round" style="font-size: inherit;">phone_missed</span>
                        </div>
                        <h5>No Pending Calls</h5>
                        <p class="text-muted">All caught up! No orders need calls at the moment.</p>
                        <a href="{{ route('seller.create.new-order') }}" class="btn btn-primary mt-3">
                            <span class="material-icons-round me-2">add</span>
                            Create New Order
                        </a>
                    </div>
                </div>
            @endif
        </div>

    </div>

    {{-- ── Toast ── --}}
    <div id="statusToast" class="status-toast" style="display: none;">
        <span class="material-icons-round" style="color: var(--success);">check_circle</span>
        <span id="toastMessage">Status updated</span>
    </div>
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const CSRF_TOKEN = '{{ csrf_token() }}';
        const UPDATE_URL = (id) => `/seller/order/update/${id}`;

        // Build calls data array for client-side filtering
        let calls = [];
        @foreach($calls as $call)
        calls.push({
            id:         {{ $call->id }},
            row:        document.querySelector(`tr[data-call-id="{{ $call->id }}"]`),
            platform:   '{{ $call->platform ?? "manual" }}',
            callStatus: '{{ $call->call_status ?? "Pending Call" }}',
            text:       `{{ strtolower($call->order_number . ' ' . $call->customer_name . ' ' . $call->customer_phone . ' ' . $call->product_name . ' ' . ($call->product_code ?? '')) }}`
        });
        @endforeach

        // ── Search & Filter ──────────────────────────────────────────────────
        document.getElementById('searchInput')?.addEventListener('keyup', filterTable);
        document.getElementById('platformFilter')?.addEventListener('change', filterTable);

        function filterTable() {
            const searchTerm     = document.getElementById('searchInput')?.value.toLowerCase() || '';
            const platformFilter = document.getElementById('platformFilter')?.value || 'all';

            let visibleCount = 0;

            calls.forEach(call => {
                const row = call.row;
                if (!row) return;

                const matchesSearch   = searchTerm === '' || call.text.includes(searchTerm);
                const matchesPlatform = platformFilter === 'all' || call.platform === platformFilter;

                if (matchesSearch && matchesPlatform) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            document.getElementById('filteredCount').textContent   = visibleCount;
            document.getElementById('totalCallsCount').textContent = visibleCount;
        }

        function clearFilter() {
            document.getElementById('platformFilter').value = 'all';
            document.getElementById('searchInput').value   = '';
            filterTable();
        }

        // ── Update Call Status (single row) ──────────────────────────────────
        async function updateCallStatus(callId, newValue, selectEl) {
            const row = document.querySelector(`tr[data-call-id="${callId}"]`);
            if (!row) return;

            const oldValue = row.dataset.callStatus || 'Pending Call';

            try {
                selectEl.disabled = true;

                const form = new FormData();
                form.append('_token',      CSRF_TOKEN);
                form.append('_method',     'PUT');
                form.append('call_status', newValue);

                const res = await fetch(UPDATE_URL(callId), {
                    method:  'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    body:    form
                });

                if (!res.ok) {
                    const data = await res.json().catch(() => ({}));
                    throw new Error(data.message || 'Update failed');
                }

                // Sync row data & dropdown colour
                row.dataset.callStatus = newValue;
                selectEl.className     = 'call-select ' + cssClass(newValue);

                const c = calls.find(x => x.id === callId);
                if (c) c.callStatus = newValue;

                showToast(`Call status updated to "${newValue}"`);

            } catch (e) {
                selectEl.value     = oldValue;
                selectEl.className = 'call-select ' + cssClass(oldValue);
                Swal.fire({ icon: 'error', title: 'Update Failed', text: e.message });
            } finally {
                selectEl.disabled = false;
            }
        }

        // ── CSS class helper ─────────────────────────────────────────────────
        function cssClass(status) {
            return status.toLowerCase().replace(/ /g, '-');
        }

        // ── Toast ─────────────────────────────────────────────────────────────
        function showToast(message) {
            const toast = document.getElementById('statusToast');
            document.getElementById('toastMessage').textContent = message;
            toast.style.display    = 'flex';
            toast.style.opacity    = '1';
            toast.style.transition = '';
            setTimeout(() => {
                toast.style.transition = 'opacity .3s';
                toast.style.opacity    = '0';
                setTimeout(() => {
                    toast.style.display    = 'none';
                    toast.style.transition = '';
                    toast.style.opacity    = '1';
                }, 300);
            }, 3000);
        }
    </script>
@endpush
