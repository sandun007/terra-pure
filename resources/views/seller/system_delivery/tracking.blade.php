@extends('seller.layout.app')

@section('title', 'Delivery Tracking')

@section('content')
    <div class="page-header">
        <div class="page-header-left">
            <h1 class="page-title">Delivery Tracking</h1>
            <p class="page-subtitle">Real-time Curfox status for your dispatched orders</p>
        </div>
        <div class="page-header-right">
            <button onclick="syncAll()" id="syncAllBtn" class="btn-sync-all">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/>
                    <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/>
                </svg>
                <span id="syncAllText">Sync All Status</span>
            </button>
        </div>
    </div>

    {{-- ── Pipeline Quick Links ──────────────────────────────────────────── --}}
    <div class="pipeline-bar">
        <span class="pipeline-label">Pipeline:</span>
        <a href="{{ route('seller.system_delivery.pipeline') }}" class="pipeline-chip chip-gray">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            Pending <span class="chip-count">{{ $pipelineCounts['Pending'] }}</span>
        </a>
        <a href="{{ route('seller.system_delivery.pipeline') }}?status=packing" class="pipeline-chip chip-blue">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
            Packing <span class="chip-count">{{ $pipelineCounts['packing'] }}</span>
        </a>
        <a href="{{ route('seller.system_delivery.pipeline') }}?status=packed" class="pipeline-chip chip-purple">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
            Packed <span class="chip-count">{{ $pipelineCounts['packed'] }}</span>
        </a>
        <a href="{{ route('seller.system_delivery.pipeline') }}?status=givedriver" class="pipeline-chip chip-emerald">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"/><path d="M16 8h4l3 3v5h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
            Give Driver <span class="chip-count">{{ $pipelineCounts['givedriver'] }}</span>
        </a>
        <span class="pipeline-note">
        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        Managed by employee
    </span>
    </div>

    {{-- ── Tracking Category Tabs ───────────────────────────────────────── --}}
    <div class="tracking-tabs">
        <a href="{{ route('seller.system_delivery.tracking.confirmed') }}"
           class="tracking-tab {{ $category === 'confirmed' ? 'tab-active tab-blue' : '' }}">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="1" y="3" width="15" height="13"/><path d="M16 8h4l3 3v5h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>
            </svg>
            Confirmed / In Transit
            <span class="tab-count tab-count-blue">{{ $trackingCounts['confirmed'] }}</span>
        </a>
        <a href="{{ route('seller.system_delivery.tracking.completed') }}"
           class="tracking-tab {{ $category === 'completed' ? 'tab-active tab-green' : '' }}">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/><polyline points="8 12 11 15 16 9"/>
            </svg>
            Delivered
            <span class="tab-count tab-count-green">{{ $trackingCounts['completed'] }}</span>
        </a>
        <a href="{{ route('seller.system_delivery.tracking.rescheduled') }}"
           class="tracking-tab {{ $category === 'rescheduled' ? 'tab-active tab-amber' : '' }}">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
            </svg>
            Rescheduled
            <span class="tab-count tab-count-amber">{{ $trackingCounts['rescheduled'] }}</span>
        </a>
        <a href="{{ route('seller.system_delivery.tracking.cancelled') }}"
           class="tracking-tab {{ $category === 'cancelled' ? 'tab-active tab-red' : '' }}">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>
            </svg>
            Cancelled / Failed
            <span class="tab-count tab-count-red">{{ $trackingCounts['cancelled'] }}</span>
        </a>
    </div>

    {{-- ── Search ───────────────────────────────────────────────────────── --}}
    <div class="search-bar-row">
        <form method="GET" action="{{ request()->url() }}" class="search-form">
            <div class="search-input-wrap">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
                <input type="text" name="q" value="{{ $q }}" placeholder="Search order, customer, waybill…" class="search-input">
            </div>
            <button type="submit" class="btn-search">Search</button>
            @if($q)
                <a href="{{ request()->url() }}" class="btn-clear">Clear</a>
            @endif
        </form>
        <div class="result-info">
            <span class="result-count">{{ $calls->count() }} result{{ $calls->count() !== 1 ? 's' : '' }}</span>
            <span class="sync-note">Status cached 30 min · <a href="#" onclick="syncAll(); return false;">force sync</a></span>
        </div>
    </div>

    {{-- ── Table ────────────────────────────────────────────────────────── --}}
    <div class="card-table">
        @if($calls->isEmpty())
            <div class="empty-state">
                <svg width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="1.5">
                    <rect x="1" y="3" width="15" height="13"/><path d="M16 8h4l3 3v5h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>
                </svg>
                <p>No {{ $category }} deliveries found.</p>
                @if($q)
                    <a href="{{ request()->url() }}" class="btn-outline-sm">Clear Search</a>
                @endif
            </div>
        @else
            <div class="table-responsive">
                <table class="data-table">
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
                        <th>Checked</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($calls as $i => $call)
                        <tr id="row-{{ $call->id }}">
                            <td class="td-index">{{ $i + 1 }}</td>
                            <td class="td-codes">
                                <span class="code-call">{{ $call->call_number ?? '—' }}</span>
                                @if($call->order_code)
                                    <span class="code-order">{{ $call->order_code }}</span>
                                @endif
                            </td>
                            <td class="td-customer">
                                <div class="customer-name">{{ $call->customer_name }}</div>
                                <div class="customer-phone">{{ $call->customer_phone_01 }}</div>
                                <div class="customer-city">{{ $call->city }}</div>
                            </td>
                            <td class="td-item">{{ Str::limit($call->item_name, 30) }}</td>
                            <td class="td-cod">Rs. {{ number_format($call->cod_amount ?? 0) }}</td>
                            <td class="td-date">{{ $call->dispatched_at ? \Carbon\Carbon::parse($call->dispatched_at)->format('d M Y') : '—' }}</td>
                            <td class="td-waybill">
                                <div class="waybill-wrap">
                                    <span class="waybill-number" id="waybill-{{ $call->id }}">{{ $call->curfox_waybill }}</span>
                                    <button onclick="copyWaybill('{{ $call->curfox_waybill }}')" class="btn-copy" title="Copy">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                            <td class="td-status">
                                <span class="status-badge" id="badge-{{ $call->id }}" data-category="{{ $call->tracking_category }}">
                                    {{ $call->tracking_status_name }}
                                </span>
                            </td>
                            <td class="td-checked">
                                <span id="checked-{{ $call->id }}" class="checked-at">
                                    {{ $call->tracking_checked_at ? \Carbon\Carbon::parse($call->tracking_checked_at)->diffForHumans() : '—' }}
                                </span>
                            </td>
                            <td class="td-actions">
                                {{-- View full detail page --}}
                                <a href="{{ route('seller.system_delivery.show', $call->id) }}"
                                   class="btn-action btn-view" title="View Details">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                </a>
                                {{-- Tracking timeline modal --}}
                                <button onclick="showTracking('{{ $call->curfox_waybill }}', '{{ $call->call_number }}')"
                                        class="btn-action btn-timeline" title="Timeline">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
                                    </svg>
                                </button>
                                {{-- Refresh waybill status --}}
                                <button onclick="syncSingle({{ $call->id }})"
                                        class="btn-action btn-refresh" title="Refresh Status" id="refresh-{{ $call->id }}">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/>
                                        <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- ── Tracking Timeline Modal ──────────────────────────────────────── --}}
    <div id="trackingModal" class="modal-overlay" style="display:none;">
        <div class="modal-box">
            <div class="modal-header">
                <div>
                    <h3 class="modal-title" id="modalTitle">Tracking Timeline</h3>
                    <p class="modal-sub" id="modalWaybill"></p>
                </div>
                <button onclick="closeModal()" class="modal-close">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>
            <div class="modal-body" id="modalBody">
                <div class="modal-loading">
                    <div class="spinner"></div>
                    <span>Fetching tracking info…</span>
                </div>
            </div>
        </div>
    </div>

    <div id="toast" class="toast" style="display:none;"></div>

    <style>
        /* ── Page Header ── */
        .page-header { display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:20px; gap:16px; flex-wrap:wrap; }
        .page-title  { font-size:22px; font-weight:700; color:#0f172a; margin:0; }
        .page-subtitle { font-size:13px; color:#94a3b8; margin:4px 0 0; }

        /* ── Sync All Button ── */
        .btn-sync-all { display:inline-flex; align-items:center; gap:8px; background:linear-gradient(135deg,#3b82f6,#2563eb); color:#fff; border:none; border-radius:10px; padding:10px 18px; font-size:13px; font-weight:600; cursor:pointer; box-shadow:0 4px 12px rgba(59,130,246,.3); transition:all .2s; }
        .btn-sync-all:hover { transform:translateY(-1px); box-shadow:0 6px 16px rgba(59,130,246,.35); }
        .btn-sync-all:disabled { opacity:.6; cursor:not-allowed; transform:none; }

        /* ── Pipeline Bar ── */
        .pipeline-bar { display:flex; align-items:center; gap:8px; flex-wrap:wrap; background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:10px 16px; margin-bottom:20px; }
        .pipeline-label { font-size:12px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:.5px; margin-right:4px; }
        .pipeline-chip { display:inline-flex; align-items:center; gap:5px; padding:5px 12px; border-radius:20px; font-size:12px; font-weight:600; text-decoration:none; transition:all .2s; }
        .chip-gray   { background:#f1f5f9; color:#475569; }
        .chip-blue   { background:#dbeafe; color:#1e40af; }
        .chip-purple { background:#ede9fe; color:#5b21b6; }
        .chip-emerald{ background:#d1fae5; color:#065f46; }
        .chip-count  { background:rgba(0,0,0,.08); border-radius:10px; padding:1px 6px; font-size:11px; }
        .pipeline-note { margin-left:auto; font-size:11px; color:#94a3b8; display:flex; align-items:center; gap:4px; }

        /* ── Tabs ── */
        .tracking-tabs { display:flex; gap:8px; flex-wrap:wrap; margin-bottom:20px; }
        .tracking-tab { display:inline-flex; align-items:center; gap:7px; padding:10px 18px; border-radius:10px; font-size:13px; font-weight:500; color:#64748b; text-decoration:none; background:#f8fafc; border:1.5px solid #e2e8f0; transition:all .2s; }
        .tracking-tab:hover { border-color:#cbd5e1; color:#334155; }
        .tab-active { font-weight:600; }
        .tab-blue   { background:#eff6ff; border-color:#bfdbfe; color:#1d4ed8; }
        .tab-green  { background:#f0fdf4; border-color:#bbf7d0; color:#15803d; }
        .tab-amber  { background:#fffbeb; border-color:#fde68a; color:#b45309; }
        .tab-red    { background:#fff1f2; border-color:#fecdd3; color:#be123c; }
        .tab-count  { font-size:11px; font-weight:700; padding:2px 7px; border-radius:9px; }
        .tab-count-blue   { background:#dbeafe; color:#1e40af; }
        .tab-count-green  { background:#d1fae5; color:#065f46; }
        .tab-count-amber  { background:#fef3c7; color:#92400e; }
        .tab-count-red    { background:#fee2e2; color:#991b1b; }

        /* ── Search Bar ── */
        .search-bar-row { display:flex; align-items:center; gap:12px; flex-wrap:wrap; margin-bottom:16px; }
        .search-form    { display:flex; align-items:center; gap:8px; }
        .search-input-wrap { position:relative; display:flex; align-items:center; }
        .search-input-wrap svg { position:absolute; left:10px; color:#94a3b8; }
        .search-input { padding:9px 12px 9px 34px; border:1.5px solid #e2e8f0; border-radius:9px; font-size:13px; width:260px; outline:none; transition:border-color .2s; }
        .search-input:focus { border-color:#3b82f6; }
        .btn-search { padding:9px 16px; background:#3b82f6; color:#fff; border:none; border-radius:9px; font-size:13px; font-weight:600; cursor:pointer; }
        .btn-clear  { padding:9px 14px; background:#f1f5f9; color:#64748b; border:1.5px solid #e2e8f0; border-radius:9px; font-size:13px; text-decoration:none; }
        .result-info { margin-left:auto; display:flex; align-items:center; gap:12px; font-size:12px; color:#94a3b8; }
        .result-count { font-weight:600; color:#475569; }
        .sync-note a { color:#3b82f6; text-decoration:none; }

        /* ── Table Card ── */
        .card-table { background:#fff; border:1px solid #e2e8f0; border-radius:14px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,.04); }
        .table-responsive { overflow-x:auto; }
        .data-table { width:100%; border-collapse:collapse; }
        .data-table thead tr { background:#f8fafc; border-bottom:2px solid #e2e8f0; }
        .data-table th { padding:12px 14px; text-align:left; font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:.5px; white-space:nowrap; }
        .data-table tbody tr { border-bottom:1px solid #f1f5f9; transition:background .15s; }
        .data-table tbody tr:hover { background:#fafbff; }
        .data-table tbody tr:last-child { border-bottom:none; }
        .data-table td { padding:12px 14px; font-size:13px; color:#334155; vertical-align:middle; }
        .td-index { color:#94a3b8; font-size:12px; width:36px; text-align:center; }

        /* Cell styles */
        .td-codes { min-width:130px; }
        .code-call  { display:block; font-weight:600; color:#0f172a; font-size:13px; }
        .code-order { display:block; font-size:11px; color:#64748b; margin-top:2px; }
        .customer-name  { font-weight:600; color:#0f172a; }
        .customer-phone { font-size:12px; color:#64748b; }
        .customer-city  { font-size:11px; color:#94a3b8; margin-top:2px; }
        .td-cod { font-weight:600; color:#059669; white-space:nowrap; }
        .td-date { font-size:12px; color:#64748b; white-space:nowrap; }
        .waybill-wrap   { display:flex; align-items:center; gap:6px; }
        .waybill-number { font-family:monospace; font-size:12px; font-weight:600; color:#1e40af; background:#eff6ff; padding:3px 8px; border-radius:6px; }
        .btn-copy { background:none; border:none; cursor:pointer; color:#94a3b8; padding:2px; display:flex; align-items:center; transition:color .2s; }
        .btn-copy:hover { color:#3b82f6; }
        .checked-at { font-size:11px; color:#94a3b8; }

        /* ── Status Badges ── */
        .status-badge { display:inline-block; padding:4px 10px; border-radius:20px; font-size:11px; font-weight:600; white-space:nowrap; }
        .status-badge[data-category="confirmed"]   { background:#dbeafe; color:#1e40af; }
        .status-badge[data-category="completed"]   { background:#d1fae5; color:#065f46; }
        .status-badge[data-category="rescheduled"] { background:#fef3c7; color:#92400e; }
        .status-badge[data-category="cancelled"]   { background:#fee2e2; color:#991b1b; }

        /* ── Action Buttons ── */
        .td-actions { white-space:nowrap; }
        .btn-action { background:none; border:1.5px solid #e2e8f0; border-radius:7px; padding:6px 8px; cursor:pointer; display:inline-flex; align-items:center; transition:all .2s; margin-right:4px; }
        .btn-view:hover     { background:#eff6ff; border-color:#93c5fd; color:#2563eb; }
        .btn-timeline:hover { background:#f5f3ff; border-color:#c4b5fd; color:#7c3aed; }
        .btn-refresh:hover  { background:#f0fdf4; border-color:#6ee7b7; color:#059669; }
        .btn-refresh.spinning svg { animation:spin .7s linear infinite; }
        @keyframes spin { from{transform:rotate(0deg)} to{transform:rotate(360deg)} }

        /* ── Empty State ── */
        .empty-state { text-align:center; padding:60px 20px; color:#94a3b8; }
        .empty-state svg { margin-bottom:16px; display:block; margin-left:auto; margin-right:auto; }
        .empty-state p { font-size:15px; margin:0 0 16px; }
        .btn-outline-sm { padding:8px 18px; border:1.5px solid #e2e8f0; border-radius:8px; color:#475569; font-size:13px; text-decoration:none; }

        /* ── Modal ── */
        .modal-overlay { position:fixed; inset:0; background:rgba(0,0,0,.45); z-index:1000; display:flex; align-items:center; justify-content:center; padding:20px; }
        .modal-box { background:#fff; border-radius:16px; width:100%; max-width:560px; max-height:80vh; display:flex; flex-direction:column; box-shadow:0 20px 60px rgba(0,0,0,.2); }
        .modal-header { display:flex; align-items:flex-start; justify-content:space-between; padding:20px 24px 14px; border-bottom:1px solid #f1f5f9; }
        .modal-title  { font-size:16px; font-weight:700; color:#0f172a; margin:0; }
        .modal-sub    { font-size:12px; color:#64748b; margin:4px 0 0; font-family:monospace; }
        .modal-close  { background:none; border:none; cursor:pointer; color:#94a3b8; padding:4px; border-radius:6px; }
        .modal-close:hover { background:#f1f5f9; color:#475569; }
        .modal-body   { padding:20px 24px; overflow-y:auto; flex:1; }
        .modal-loading { display:flex; align-items:center; gap:12px; color:#64748b; font-size:14px; padding:20px 0; }
        .spinner { width:22px; height:22px; border:3px solid #e2e8f0; border-top-color:#3b82f6; border-radius:50%; animation:spin .8s linear infinite; }

        /* Timeline */
        .timeline { position:relative; padding-left:24px; }
        .timeline::before { content:''; position:absolute; left:7px; top:8px; bottom:8px; width:2px; background:#e2e8f0; }
        .timeline-item { position:relative; margin-bottom:18px; }
        .timeline-item:last-child { margin-bottom:0; }
        .timeline-dot { position:absolute; left:-21px; top:4px; width:14px; height:14px; border-radius:50%; border:2.5px solid #fff; box-shadow:0 0 0 2px #e2e8f0; background:#94a3b8; }
        .timeline-dot.dot-delivered   { background:#10b981; box-shadow:0 0 0 2px #d1fae5; }
        .timeline-dot.dot-rescheduled { background:#f59e0b; box-shadow:0 0 0 2px #fef3c7; }
        .timeline-dot.dot-cancelled   { background:#ef4444; box-shadow:0 0 0 2px #fee2e2; }
        .timeline-dot.dot-active      { background:#3b82f6; box-shadow:0 0 0 2px #dbeafe; }
        .tl-status { font-size:13px; font-weight:600; color:#1e293b; }
        .tl-time   { font-size:11px; color:#94a3b8; margin-top:2px; }
        .tl-remarks { font-size:12px; color:#64748b; margin-top:3px; font-style:italic; }

        /* ── Toast ── */
        .toast { position:fixed; bottom:28px; right:28px; background:#1e293b; color:#fff; padding:12px 20px; border-radius:10px; font-size:13px; font-weight:500; z-index:9999; box-shadow:0 8px 24px rgba(0,0,0,.2); animation:slideUp .3s ease; }
        @keyframes slideUp { from{transform:translateY(20px);opacity:0} to{transform:translateY(0);opacity:1} }
    </style>

    <script>
        const SYNC_SINGLE_URL = "{{ route('seller.system_delivery.sync_waybill', '__ID__') }}";
        const SYNC_ALL_URL    = "{{ route('seller.system_delivery.sync_all') }}";
        const TRACKING_URL    = "{{ route('seller.system_delivery.tracking_info') }}";
        const CSRF            = "{{ csrf_token() }}";

        // ── Single Sync ─────────────────────────────────────────────────────────────
        async function syncSingle(id) {
            const btn = document.getElementById('refresh-' + id);
            btn.classList.add('spinning');
            btn.disabled = true;

            try {
                const res  = await fetch(SYNC_SINGLE_URL.replace('__ID__', id), {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF }
                });
                const data = await res.json();

                if (data.success) {
                    updateRowBadge(id, data.category, data.status_name, data.checked_at);
                    showToast('✓ Status updated');
                } else {
                    showToast('⚠ ' + (data.message || 'Update failed'), true);
                }
            } catch (e) {
                showToast('⚠ Network error', true);
            } finally {
                btn.classList.remove('spinning');
                btn.disabled = false;
            }
        }

        // ── Sync All ─────────────────────────────────────────────────────────────────
        async function syncAll() {
            const btn  = document.getElementById('syncAllBtn');
            const text = document.getElementById('syncAllText');
            btn.disabled = true;
            text.textContent = 'Syncing…';

            try {
                const res  = await fetch(SYNC_ALL_URL, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF }
                });
                const data = await res.json();

                if (data.success) {
                    showToast(`✓ Synced ${data.synced} waybills — reloading…`);
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showToast('⚠ Sync failed', true);
                    btn.disabled = false;
                    text.textContent = 'Sync All Status';
                }
            } catch (e) {
                showToast('⚠ Network error', true);
                btn.disabled = false;
                text.textContent = 'Sync All Status';
            }
        }

        // ── Update Row Badge ─────────────────────────────────────────────────────────
        function updateRowBadge(id, category, statusName, checkedAt) {
            const badge   = document.getElementById('badge-' + id);
            const checked = document.getElementById('checked-' + id);

            if (badge) {
                badge.setAttribute('data-category', category);
                badge.textContent = statusName;
            }
            if (checked) {
                checked.textContent = checkedAt ? 'just now' : '—';
            }
        }

        // ── Tracking Timeline Modal ───────────────────────────────────────────────────
        async function showTracking(waybill, callNumber) {
            document.getElementById('trackingModal').style.display = 'flex';
            document.getElementById('modalTitle').textContent = 'Tracking: ' + callNumber;
            document.getElementById('modalWaybill').textContent = 'Waybill: ' + waybill;
            document.getElementById('modalBody').innerHTML =
                '<div class="modal-loading"><div class="spinner"></div><span>Fetching tracking info…</span></div>';

            try {
                const res  = await fetch(TRACKING_URL, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
                    body: JSON.stringify({ waybill_number: waybill })
                });
                const data = await res.json();

                const items = data?.data ?? [];
                if (!items.length) {
                    document.getElementById('modalBody').innerHTML =
                        '<p style="color:#94a3b8;text-align:center;padding:24px 0;">No tracking records yet.</p>';
                    return;
                }

                let html = '<div class="timeline">';
                items.forEach((item, idx) => {
                    const status  = item?.status?.name ?? 'Unknown';
                    const time    = item?.created_at ?? '';
                    const remarks = item?.remarks ?? '';
                    const dotClass = idx === 0 ? 'dot-active' :
                        status.toLowerCase().includes('deliver') ? 'dot-delivered' :
                            status.toLowerCase().includes('reschedul') ? 'dot-rescheduled' :
                                (status.toLowerCase().includes('cancel') || status.toLowerCase().includes('fail')) ? 'dot-cancelled' : '';

                    html += `<div class="timeline-item">
                <div class="timeline-dot ${dotClass}"></div>
                <div class="tl-status">${status}</div>
                ${time    ? `<div class="tl-time">${time}</div>` : ''}
                ${remarks ? `<div class="tl-remarks">${remarks}</div>` : ''}
            </div>`;
                });
                html += '</div>';
                document.getElementById('modalBody').innerHTML = html;
            } catch (e) {
                document.getElementById('modalBody').innerHTML =
                    '<p style="color:#ef4444;text-align:center;padding:24px 0;">Failed to load tracking info.</p>';
            }
        }

        function closeModal() {
            document.getElementById('trackingModal').style.display = 'none';
        }
        document.getElementById('trackingModal').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });
        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });

        // ── Copy Waybill ─────────────────────────────────────────────────────────────
        function copyWaybill(waybill) {
            navigator.clipboard.writeText(waybill).then(() => showToast('Waybill copied!'));
        }

        // ── Toast ─────────────────────────────────────────────────────────────────────
        let toastTimer;
        function showToast(msg, isError = false) {
            const t = document.getElementById('toast');
            t.textContent = msg;
            t.style.background = isError ? '#dc2626' : '#1e293b';
            t.style.display = 'block';
            clearTimeout(toastTimer);
            toastTimer = setTimeout(() => t.style.display = 'none', 3000);
        }
    </script>
@endsection
