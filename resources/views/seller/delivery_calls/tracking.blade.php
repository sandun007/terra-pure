@extends('seller.layout.app')

@push('title')
    Delivery Tracking — {{ ucfirst($category) }} Orders
@endpush

@push('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --confirmed:  #3b82f6; --confirmed-bg:  #eff6ff; --confirmed-text:  #1e40af;
            --completed:  #10b981; --completed-bg:  #ecfdf5; --completed-text:  #065f46;
            --rescheduled:#f59e0b; --rescheduled-bg:#fffbeb; --rescheduled-text:#92400e;
            --cancelled:  #ef4444; --cancelled-bg:  #fef2f2; --cancelled-text:  #991b1b;
            --dark:#0f172a; --mid:#64748b; --border:#e2e8f0; --bg:#f8fafc;
        }
        body { font-family:'DM Sans',sans-serif; background:var(--bg); }

        /* Page Header */
        .page-header {
            padding:2rem; border-radius:16px; margin-bottom:1.5rem; color:white;
            box-shadow:0 8px 20px rgba(0,0,0,.12);
        }
        .page-header.confirmed  { background:linear-gradient(135deg,#3b82f6,#2563eb); }
        .page-header.completed  { background:linear-gradient(135deg,#10b981,#059669); }
        .page-header.rescheduled{ background:linear-gradient(135deg,#f59e0b,#d97706); }
        .page-header.cancelled  { background:linear-gradient(135deg,#ef4444,#dc2626); }
        .page-header h1 { font-size:26px; font-weight:800; margin-bottom:.3rem; }
        .count-pill {
            background:rgba(255,255,255,.25); padding:6px 18px; border-radius:999px;
            display:inline-block; font-weight:700; font-size:14px;
        }

        /* Tab Navigation */
        .tracking-tabs {
            display:flex; gap:8px; flex-wrap:wrap; margin-bottom:1.5rem;
            background:white; padding:10px; border-radius:12px;
            box-shadow:0 2px 8px rgba(0,0,0,.05);
        }
        .tracking-tab {
            display:flex; align-items:center; gap:8px; padding:10px 20px;
            border-radius:8px; font-size:14px; font-weight:600;
            text-decoration:none; color:var(--mid); transition:all .2s; border:none;
            background:transparent; cursor:pointer;
        }
        .tracking-tab:hover { background:rgba(0,0,0,.05); color:var(--dark); text-decoration:none; }
        .tracking-tab.active.confirmed  { background:var(--confirmed-bg); color:var(--confirmed-text); }
        .tracking-tab.active.completed  { background:var(--completed-bg); color:var(--completed-text); }
        .tracking-tab.active.rescheduled{ background:var(--rescheduled-bg); color:var(--rescheduled-text); }
        .tracking-tab.active.cancelled  { background:var(--cancelled-bg); color:var(--cancelled-text); }
        .tab-badge {
            min-width:22px; height:22px; border-radius:11px; padding:0 7px;
            display:inline-flex; align-items:center; justify-content:center;
            font-size:11px; font-weight:700;
        }
        .tab-badge.confirmed  { background:var(--confirmed);  color:white; }
        .tab-badge.completed  { background:var(--completed);  color:white; }
        .tab-badge.rescheduled{ background:var(--rescheduled);color:white; }
        .tab-badge.cancelled  { background:var(--cancelled);  color:white; }

        /* Also show pipeline nav */
        .pipeline-tabs {
            display:flex; gap:6px; flex-wrap:wrap; margin-bottom:1.5rem;
        }
        .pipeline-tab {
            padding:8px 16px; border-radius:8px; font-size:13px; font-weight:600;
            text-decoration:none; color:var(--mid); background:white;
            border:1px solid var(--border); transition:all .2s;
        }
        .pipeline-tab:hover { border-color:#94a3b8; color:var(--dark); text-decoration:none; }

        /* Sync Controls */
        .sync-bar {
            background:white; border-radius:12px; padding:1rem 1.25rem;
            margin-bottom:1.25rem; display:flex; align-items:center; justify-content:space-between;
            flex-wrap:wrap; gap:.75rem; box-shadow:0 2px 8px rgba(0,0,0,.05);
        }
        .sync-info { font-size:13px; color:var(--mid); }
        .sync-info strong { color:var(--dark); }
        .btn-sync {
            display:inline-flex; align-items:center; gap:6px; padding:8px 18px;
            background:#4f46e5; color:white; border:none; border-radius:8px;
            font-size:13px; font-weight:600; cursor:pointer; transition:all .2s;
        }
        .btn-sync:hover { background:#4338ca; transform:translateY(-1px); }
        .btn-sync:disabled { opacity:.6; cursor:not-allowed; transform:none; }
        .btn-sync-single {
            display:inline-flex; align-items:center; gap:4px; padding:5px 10px;
            background:#f1f5f9; color:var(--mid); border:none; border-radius:6px;
            font-size:11px; font-weight:600; cursor:pointer; transition:all .2s;
        }
        .btn-sync-single:hover { background:#e2e8f0; color:var(--dark); }

        /* Search */
        .search-box {
            display:flex; align-items:center; gap:.75rem; flex-wrap:wrap;
            margin-bottom:1.25rem;
        }
        .search-input {
            flex:1; min-width:240px; max-width:400px; padding:10px 16px 10px 40px;
            border:2px solid var(--border); border-radius:8px; font-size:14px;
            background:white; outline:none; transition:border-color .2s;
        }
        .search-input:focus { border-color:#4f46e5; }
        .search-wrapper { position:relative; flex:1; min-width:240px; max-width:400px; }
        .search-wrapper .search-icon {
            position:absolute; left:12px; top:50%; transform:translateY(-50%);
            color:var(--mid); font-size:18px;
        }

        /* Table */
        .card { border:none; border-radius:16px; box-shadow:0 2px 8px rgba(0,0,0,.05); }
        .card-header { background:white; border-bottom:1px solid var(--border); padding:1.25rem 1.5rem; border-radius:16px 16px 0 0; }
        .table-responsive { border-radius:0 0 16px 16px; overflow-x:auto; }
        .table { margin:0; border-collapse:collapse; width:100%; }
        .table thead th {
            background:var(--bg); border-bottom:2px solid var(--border);
            padding:.875rem 1rem; font-weight:700; color:var(--dark);
            font-size:12px; text-transform:uppercase; letter-spacing:.4px; white-space:nowrap;
        }
        .table tbody td { padding:.875rem 1rem; vertical-align:middle; border-bottom:1px solid var(--border); }
        .table tbody tr:hover { background:rgba(79,70,229,.02); }

        /* Status Badges */
        .delivery-badge {
            display:inline-flex; align-items:center; gap:5px; padding:5px 12px;
            border-radius:999px; font-size:11px; font-weight:700; white-space:nowrap;
        }
        .delivery-badge.confirmed   { background:var(--confirmed-bg);   color:var(--confirmed-text); }
        .delivery-badge.completed   { background:var(--completed-bg);   color:var(--completed-text); }
        .delivery-badge.rescheduled { background:var(--rescheduled-bg); color:var(--rescheduled-text); }
        .delivery-badge.cancelled   { background:var(--cancelled-bg);   color:var(--cancelled-text); }
        .delivery-badge.unknown     { background:#f1f5f9; color:var(--mid); }

        .waybill-code {
            font-family:monospace; font-size:12px; background:#f8fafc;
            border:1px solid var(--border); padding:3px 8px; border-radius:4px;
            display:inline-flex; align-items:center; gap:4px;
        }
        .waybill-code .copy-btn {
            background:none; border:none; cursor:pointer; color:var(--mid); padding:0; font-size:12px;
        }
        .waybill-code .copy-btn:hover { color:#4f46e5; }

        .action-btn {
            width:34px; height:34px; border-radius:8px; border:none;
            display:inline-flex; align-items:center; justify-content:center;
            cursor:pointer; transition:all .2s;
        }
        .action-btn:hover { transform:translateY(-1px); box-shadow:0 4px 12px rgba(0,0,0,.1); }
        .action-btn.view    { background:#4f46e5; color:white; }
        .action-btn.track   { background:#3b82f6; color:white; }
        .action-btn.refresh { background:#f1f5f9; color:var(--mid); }
        .action-btn.refresh:hover { background:#e2e8f0; }

        /* Tracking Details Modal */
        .modal-content { border:none; border-radius:16px; }
        .modal-header  { border-radius:16px 16px 0 0; padding:1.5rem; }
        .modal-header.confirmed   { background:var(--confirmed);   color:white; }
        .modal-header.completed   { background:var(--completed);   color:white; }
        .modal-header.rescheduled { background:var(--rescheduled); color:white; }
        .modal-header.cancelled   { background:var(--cancelled);   color:white; }
        .timeline { position:relative; padding-left:24px; }
        .timeline::before {
            content:''; position:absolute; left:8px; top:0; bottom:0;
            width:2px; background:var(--border);
        }
        .timeline-item { position:relative; margin-bottom:1.25rem; }
        .timeline-item::before {
            content:''; position:absolute; left:-20px; top:4px; width:10px; height:10px;
            background:#4f46e5; border-radius:50%; border:2px solid white;
            box-shadow:0 0 0 2px #4f46e5;
        }
        .timeline-item:first-child::before { background:#10b981; box-shadow:0 0 0 2px #10b981; }
        .timeline-status { font-weight:700; color:var(--dark); font-size:14px; }
        .timeline-meta { font-size:12px; color:var(--mid); margin-top:2px; }

        /* Empty State */
        .empty-state { text-align:center; padding:60px 20px; color:var(--mid); }
        .empty-state .icon { font-size:64px; margin-bottom:16px; opacity:.3; }

        /* Toast */
        .toast-msg {
            position:fixed; bottom:24px; right:24px; background:var(--dark); color:white;
            padding:12px 20px; border-radius:10px; box-shadow:0 8px 24px rgba(0,0,0,.2);
            z-index:9999; display:none; align-items:center; gap:10px; font-size:14px;
        }

        /* Spinning animation */
        @keyframes spin { to { transform:rotate(360deg); } }
        .spinning { animation:spin .8s linear infinite; }

        @media(max-width:768px){
            .page-header { padding:1.5rem; }
            .page-header h1 { font-size:20px; }
            .tracking-tabs { gap:4px; }
            .tracking-tab { padding:8px 12px; font-size:13px; }
        }
    </style>
@endpush

@section('content')
<div class="container-fluid px-3 px-md-4 px-lg-5 py-4">

    {{-- Page Header --}}
    <div class="page-header {{ $category }}">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <h1>
                    @if($category === 'confirmed')
                        <span class="material-icons-round me-2" style="font-size:30px;vertical-align:middle;">local_shipping</span>
                        Confirmed / In-Transit Orders
                    @elseif($category === 'completed')
                        <span class="material-icons-round me-2" style="font-size:30px;vertical-align:middle;">check_circle</span>
                        Delivered / Completed Orders
                    @elseif($category === 'rescheduled')
                        <span class="material-icons-round me-2" style="font-size:30px;vertical-align:middle;">event_repeat</span>
                        Rescheduled Orders
                    @elseif($category === 'cancelled')
                        <span class="material-icons-round me-2" style="font-size:30px;vertical-align:middle;">cancel</span>
                        Cancelled / Failed Orders
                    @endif
                </h1>
                <p class="mb-0 opacity-80" style="font-size:14px;">
                    Orders tracked via Curfox delivery API · Status cached for 30 minutes
                </p>
            </div>
            <div class="count-pill">
                {{ $calls->count() }} {{ $calls->count() === 1 ? 'Order' : 'Orders' }}
            </div>
        </div>
    </div>

    {{-- Pipeline Quick Links --}}
    <div class="pipeline-tabs">
        <a href="{{ route('seller.delivery_calls.pending') }}" class="pipeline-tab">⏳ Pending ({{ $pipelineCounts['pending'] ?? 0 }})</a>
        <a href="{{ route('seller.delivery_calls.packing') }}" class="pipeline-tab">📦 Packing ({{ $pipelineCounts['packing'] ?? 0 }})</a>
        <a href="{{ route('seller.delivery_calls.packed') }}" class="pipeline-tab">📋 Packed ({{ $pipelineCounts['packed'] ?? 0 }})</a>
        <a href="{{ route('seller.delivery_calls.givedriver') }}" class="pipeline-tab">🚚 Give Driver ({{ $pipelineCounts['givedriver'] ?? 0 }})</a>
    </div>

    {{-- Tracking Category Tabs --}}
    <div class="tracking-tabs">
        <a href="{{ route('seller.delivery_calls.tracking.confirmed') }}"
           class="tracking-tab {{ $category === 'confirmed' ? 'active confirmed' : '' }}">
            <span class="material-icons-round" style="font-size:18px;">local_shipping</span>
            Confirmed
            <span class="tab-badge confirmed">{{ $trackingCounts['confirmed'] }}</span>
        </a>
        <a href="{{ route('seller.delivery_calls.tracking.completed') }}"
           class="tracking-tab {{ $category === 'completed' ? 'active completed' : '' }}">
            <span class="material-icons-round" style="font-size:18px;">check_circle</span>
            Completed
            <span class="tab-badge completed">{{ $trackingCounts['completed'] }}</span>
        </a>
        <a href="{{ route('seller.delivery_calls.tracking.rescheduled') }}"
           class="tracking-tab {{ $category === 'rescheduled' ? 'active rescheduled' : '' }}">
            <span class="material-icons-round" style="font-size:18px;">event_repeat</span>
            Rescheduled
            <span class="tab-badge rescheduled">{{ $trackingCounts['rescheduled'] }}</span>
        </a>
        <a href="{{ route('seller.delivery_calls.tracking.cancelled') }}"
           class="tracking-tab {{ $category === 'cancelled' ? 'active cancelled' : '' }}">
            <span class="material-icons-round" style="font-size:18px;">cancel</span>
            Cancelled
            <span class="tab-badge cancelled">{{ $trackingCounts['cancelled'] }}</span>
        </a>
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4">
            <span class="material-icons-round me-2">check_circle</span> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Sync Bar --}}
    <div class="sync-bar">
        <div class="sync-info">
            <span class="material-icons-round me-1" style="font-size:16px;vertical-align:middle;color:#94a3b8;">info</span>
            Status cached for <strong>30 minutes</strong>. Click sync to get latest from Curfox API.
        </div>
        <div class="d-flex gap-2">
            <form method="GET" style="display:inline;">
                <div class="search-wrapper" style="min-width:240px;">
                    <span class="material-icons-round search-icon">search</span>
                    <input type="text" name="q" class="search-input"
                           placeholder="Search waybill, name, order..."
                           value="{{ $q }}">
                </div>
            </form>
            <button class="btn-sync" id="syncAllBtn" onclick="syncAll()">
                <span class="material-icons-round" id="syncIcon">sync</span>
                Sync All Status
            </button>
        </div>
    </div>

    {{-- Main Table --}}
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="mb-0 fw-bold">
                <span class="material-icons-round me-2" style="vertical-align:middle;font-size:20px;">list_alt</span>
                {{ ucfirst($category) }} Orders
                <span class="badge ms-2
                    {{ $category === 'confirmed' ? 'bg-primary' : '' }}
                    {{ $category === 'completed' ? 'bg-success' : '' }}
                    {{ $category === 'rescheduled' ? 'bg-warning' : '' }}
                    {{ $category === 'cancelled' ? 'bg-danger' : '' }}
                ">{{ $calls->count() }}</span>
            </h5>
        </div>

        @if($calls->isNotEmpty())
            <div class="table-responsive">
                <table class="table" id="trackingTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Call / Order</th>
                            <th>Customer</th>
                            <th>Item</th>
                            <th>COD</th>
                            <th>Dispatched</th>
                            <th>Waybill</th>
                            <th>Curfox Status</th>
                            <th>Checked At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($calls as $i => $call)
                        <tr id="row-{{ $call->id }}">
                            <td class="text-muted" style="font-size:13px;">{{ $i + 1 }}</td>
                            <td>
                                <div class="fw-bold" style="font-family:monospace;font-size:13px;">{{ $call->call_number }}</div>
                                @if($call->order_code)
                                    <small class="text-muted">{{ $call->order_code }}</small>
                                @endif
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $call->customer_name }}</div>
                                <small class="text-muted">{{ $call->customer_phone_01 }}</small>
                                @if($call->city)
                                    <br><small class="text-muted">{{ $call->city }}</small>
                                @endif
                            </td>
                            <td>
                                <div>{{ Str::limit($call->item_name, 30) }}</div>
                                <small class="text-muted">Qty: {{ $call->qty }}</small>
                            </td>
                            <td>
                                <strong class="text-danger">
                                    Rs. {{ number_format(($call->price ?? 0) * ($call->qty ?? 1), 2) }}
                                </strong>
                            </td>
                            <td>
                                <small>
                                    {{ $call->curfox_dispatched_at
                                        ? \Carbon\Carbon::parse($call->curfox_dispatched_at)->format('M d, Y')
                                        : 'N/A' }}
                                </small>
                            </td>
                            <td>
                                <div class="waybill-code" id="waybill-{{ $call->id }}">
                                    {{ $call->curfox_waybill }}
                                    <button class="copy-btn" onclick="copyWaybill('{{ $call->curfox_waybill }}')" title="Copy">
                                        <span class="material-icons-round" style="font-size:14px;">content_copy</span>
                                    </button>
                                </div>
                            </td>
                            <td>
                                <span class="delivery-badge {{ $call->tracking_category ?? 'unknown' }}"
                                      id="status-badge-{{ $call->id }}">
                                    @if($call->tracking_category === 'confirmed')
                                        <span class="material-icons-round" style="font-size:13px;">local_shipping</span>
                                    @elseif($call->tracking_category === 'completed')
                                        <span class="material-icons-round" style="font-size:13px;">check_circle</span>
                                    @elseif($call->tracking_category === 'rescheduled')
                                        <span class="material-icons-round" style="font-size:13px;">event_repeat</span>
                                    @elseif($call->tracking_category === 'cancelled')
                                        <span class="material-icons-round" style="font-size:13px;">cancel</span>
                                    @else
                                        <span class="material-icons-round" style="font-size:13px;">help_outline</span>
                                    @endif
                                    <span id="status-name-{{ $call->id }}">
                                        {{ $call->tracking_status_name ?? 'Unknown' }}
                                    </span>
                                </span>
                            </td>
                            <td>
                                <small class="text-muted" id="checked-at-{{ $call->id }}">
                                    {{ $call->tracking_checked_at
                                        ? \Carbon\Carbon::parse($call->tracking_checked_at)->diffForHumans()
                                        : '–' }}
                                </small>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('seller.delivery_calls.show', $call->id) }}"
                                       class="action-btn view" title="View Details">
                                        <span class="material-icons-round" style="font-size:16px;">visibility</span>
                                    </a>
                                    <button class="action-btn track" title="View Full Tracking"
                                            onclick="showTracking('{{ $call->curfox_waybill }}', '{{ $call->customer_name }}')">
                                        <span class="material-icons-round" style="font-size:16px;">timeline</span>
                                    </button>
                                    <button class="action-btn refresh" title="Refresh Status"
                                            id="refresh-btn-{{ $call->id }}"
                                            onclick="syncSingle({{ $call->id }})">
                                        <span class="material-icons-round" style="font-size:16px;">refresh</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="card-body">
                <div class="empty-state">
                    <div class="icon">
                        @if($category === 'confirmed')  🚚
                        @elseif($category === 'completed') ✅
                        @elseif($category === 'rescheduled') 📅
                        @else ❌
                        @endif
                    </div>
                    <h5 class="fw-bold">No {{ ucfirst($category) }} Orders</h5>
                    <p class="text-muted">
                        @if($category === 'confirmed')
                            No orders are currently in transit with Curfox.
                        @elseif($category === 'completed')
                            No orders have been delivered yet.
                        @elseif($category === 'rescheduled')
                            No orders have been rescheduled.
                        @else
                            No cancelled or failed deliveries.
                        @endif
                    </p>
                    <p class="text-muted" style="font-size:13px;">
                        Try clicking <strong>Sync All Status</strong> to refresh from Curfox API.
                    </p>
                </div>
            </div>
        @endif
    </div>
</div>

{{-- Tracking Timeline Modal --}}
<div class="modal fade" id="trackingModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header {{ $category }}" id="trackingModalHeader">
                <h5 class="modal-title fw-bold">
                    <span class="material-icons-round me-2">timeline</span>
                    Tracking History — <span id="modalWaybill"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <p class="text-muted mb-3">Customer: <strong id="modalCustomer"></strong></p>
                <div id="trackingTimeline">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p class="mt-2 text-muted">Loading tracking details...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Toast --}}
<div class="toast-msg" id="toastMsg">
    <span class="material-icons-round" style="color:#10b981;">check_circle</span>
    <span id="toastText"></span>
</div>
@endsection

@push('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
const CSRF   = '{{ csrf_token() }}';
const ROUTES = {
    syncSingle : (id) => `/seller/delivery-calls/${id}/sync-waybill`,
    syncAll    : '/seller/delivery-calls/sync-all-waybills',
    tracking   : '/seller/delivery-calls/tracking',
};

// ── Sync single call's waybill status ─────────────────────────────────────
async function syncSingle(id) {
    const btn  = document.getElementById(`refresh-btn-${id}`);
    const icon = btn?.querySelector('.material-icons-round');
    if (icon) icon.classList.add('spinning');
    btn && (btn.disabled = true);

    try {
        const res  = await fetch(ROUTES.syncSingle(id), {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json', 'Content-Type': 'application/json' },
        });
        const data = await res.json();

        if (data.success) {
            updateRowBadge(id, data.category, data.status_name, data.checked_at);
            showToast(`Status updated: ${data.status_name}`);
        } else {
            showToast('Could not refresh: ' + (data.message || 'Unknown error'), true);
        }
    } catch (e) {
        showToast('Network error. Please try again.', true);
    } finally {
        if (icon) icon.classList.remove('spinning');
        btn && (btn.disabled = false);
    }
}

// ── Sync ALL dispatched waybills ───────────────────────────────────────────
async function syncAll() {
    const btn  = document.getElementById('syncAllBtn');
    const icon = document.getElementById('syncIcon');

    btn.disabled = true;
    icon.classList.add('spinning');
    btn.textContent = ' Syncing...';
    btn.prepend(icon);

    try {
        const res  = await fetch(ROUTES.syncAll, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json', 'Content-Type': 'application/json' },
        });
        const data = await res.json();

        if (data.success) {
            // Update any rows visible on the page
            (data.results || []).forEach(r => {
                updateRowBadge(r.id, r.category, r.status_name, null);
            });

            showToast(`Synced ${data.synced} waybill(s). Reload to see new categories.`);

            // Reload after 1.5s to re-categorize
            setTimeout(() => location.reload(), 1500);
        } else {
            showToast('Sync failed.', true);
        }
    } catch (e) {
        showToast('Network error during sync.', true);
    } finally {
        btn.disabled = false;
        icon.classList.remove('spinning');
        btn.innerHTML = '<span class="material-icons-round" id="syncIcon">sync</span> Sync All Status';
    }
}

// ── Update a row's status badge in place ───────────────────────────────────
function updateRowBadge(id, category, statusName, checkedAt) {
    const badge     = document.getElementById(`status-badge-${id}`);
    const nameEl    = document.getElementById(`status-name-${id}`);
    const checkedEl = document.getElementById(`checked-at-${id}`);

    if (!badge) return;

    badge.className = `delivery-badge ${category}`;
    if (nameEl) nameEl.textContent = statusName;
    if (checkedEl) checkedEl.textContent = checkedAt ? 'just now' : checkedEl.textContent;
}

// ── Show tracking timeline modal ───────────────────────────────────────────
async function showTracking(waybill, customerName) {
    document.getElementById('modalWaybill').textContent  = waybill;
    document.getElementById('modalCustomer').textContent = customerName;
    document.getElementById('trackingTimeline').innerHTML =
        '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div><p class="mt-2 text-muted">Loading tracking...</p></div>';

    const modal = new bootstrap.Modal(document.getElementById('trackingModal'));
    modal.show();

    try {
        const res  = await fetch(ROUTES.tracking, {
            method : 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json', 'Content-Type': 'application/json' },
            body   : JSON.stringify({ waybill_number: waybill }),
        });
        const data = await res.json();

        const trackings = data.data || [];
        if (!trackings.length) {
            document.getElementById('trackingTimeline').innerHTML =
                '<p class="text-center text-muted py-4">No tracking events found for this waybill.</p>';
            return;
        }

        let html = '<div class="timeline">';
        trackings.forEach(t => {
            const statusName = t.status?.name || 'Unknown';
            const dateTime   = t.date_time ? new Date(t.date_time).toLocaleString() : '';
            const timeAgo    = t.date_time_ago || '';
            const user       = t.user ? `${t.user.first_name} ${t.user.last_name}` : '';
            html += `
                <div class="timeline-item">
                    <div class="timeline-status">${statusName}</div>
                    <div class="timeline-meta">${dateTime} · ${timeAgo}${user ? ' · ' + user : ''}</div>
                </div>`;
        });
        html += '</div>';
        document.getElementById('trackingTimeline').innerHTML = html;

    } catch (e) {
        document.getElementById('trackingTimeline').innerHTML =
            `<p class="text-center text-danger py-4">Failed to load tracking: ${e.message}</p>`;
    }
}

// ── Copy waybill to clipboard ──────────────────────────────────────────────
function copyWaybill(waybill) {
    navigator.clipboard.writeText(waybill).then(() => showToast(`Copied: ${waybill}`));
}

// ── Toast helper ───────────────────────────────────────────────────────────
function showToast(msg, isError = false) {
    const el   = document.getElementById('toastMsg');
    const icon = el.querySelector('.material-icons-round');
    document.getElementById('toastText').textContent = msg;
    icon.textContent  = isError ? 'error' : 'check_circle';
    icon.style.color  = isError ? '#ef4444' : '#10b981';
    el.style.display  = 'flex';
    setTimeout(() => {
        el.style.opacity = '0';
        setTimeout(() => { el.style.display = 'none'; el.style.opacity = '1'; }, 300);
    }, 3000);
}
</script>
@endpush
