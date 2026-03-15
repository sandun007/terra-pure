@extends('seller.layout.app')

@section('title', 'Delivery Pipeline')

@section('content')

    <style>
        /* ── Page Header ── */
        .page-header { display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:20px; gap:16px; flex-wrap:wrap; }
        .page-title  { font-size:22px; font-weight:700; color:#0f172a; margin:0; }
        .page-subtitle { font-size:13px; color:#94a3b8; margin:4px 0 0; }

        /* ── Read-only notice ── */
        .readonly-notice { display:flex; align-items:center; gap:10px; background:#eff6ff; border:1px solid #bfdbfe; border-radius:12px; padding:12px 18px; margin-bottom:20px; font-size:13px; color:#1e40af; font-weight:500; }

        /* ── Status tabs ── */
        .pipeline-tabs { display:flex; gap:8px; flex-wrap:wrap; margin-bottom:20px; }
        .pipeline-tab  { display:inline-flex; align-items:center; gap:7px; padding:9px 16px; border-radius:10px; font-size:13px; font-weight:500; text-decoration:none; background:#f8fafc; border:1.5px solid #e2e8f0; color:#64748b; transition:all .2s; }
        .pipeline-tab:hover { border-color:#cbd5e1; color:#334155; }
        .pipeline-tab.active { font-weight:700; background:#0f172a; border-color:#0f172a; color:#fff; }
        .tab-count { font-size:11px; font-weight:700; padding:2px 7px; border-radius:9px; background:rgba(255,255,255,.15); }
        .pipeline-tab:not(.active) .tab-count { background:#f1f5f9; color:#475569; }

        /* ── Search bar ── */
        .search-bar-row { display:flex; align-items:center; gap:10px; flex-wrap:wrap; margin-bottom:16px; }
        .search-input-wrap { position:relative; display:flex; align-items:center; }
        .search-input-wrap svg { position:absolute; left:10px; color:#94a3b8; pointer-events:none; }
        .search-input { padding:9px 12px 9px 34px; border:1.5px solid #e2e8f0; border-radius:9px; font-size:13px; width:260px; outline:none; transition:border-color .2s; }
        .search-input:focus { border-color:#3b82f6; }
        .btn-search { padding:9px 16px; background:#3b82f6; color:#fff; border:none; border-radius:9px; font-size:13px; font-weight:600; cursor:pointer; }
        .btn-clear  { padding:9px 14px; background:#f1f5f9; color:#64748b; border:1.5px solid #e2e8f0; border-radius:9px; font-size:13px; text-decoration:none; }

        /* ── Tracking link ── */
        .btn-tracking-link { display:inline-flex; align-items:center; gap:6px; padding:7px 14px; background:#eff6ff; border:1px solid #bfdbfe; border-radius:8px; color:#2563eb; font-size:12px; font-weight:600; text-decoration:none; transition:all .2s; }
        .btn-tracking-link:hover { background:#dbeafe; border-color:#93c5fd; }

        /* ── Table ── */
        .card-table { background:#fff; border:1px solid #e2e8f0; border-radius:14px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,.04); }
        .table-responsive { overflow-x:auto; }
        .data-table { width:100%; border-collapse:collapse; }
        .data-table thead tr { background:#f8fafc; border-bottom:2px solid #e2e8f0; }
        .data-table th { padding:11px 14px; text-align:left; font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:.5px; white-space:nowrap; }
        .data-table tbody tr { border-bottom:1px solid #f1f5f9; transition:background .15s; }
        .data-table tbody tr:hover { background:#fafbff; }
        .data-table tbody tr:last-child { border-bottom:none; }
        .data-table td { padding:12px 14px; font-size:13px; color:#334155; vertical-align:middle; }
        .td-index { color:#94a3b8; font-size:12px; width:36px; text-align:center; }

        /* Status badges */
        .status-badge { display:inline-flex; align-items:center; gap:5px; padding:4px 10px; border-radius:20px; font-size:11px; font-weight:700; white-space:nowrap; }
        .badge-Pending    { background:#fef3c7; color:#92400e; }
        .badge-packing    { background:#dbeafe; color:#1e40af; }
        .badge-packed     { background:#ede9fe; color:#5b21b6; }
        .badge-givedriver { background:#d1fae5; color:#065f46; }

        /* Waybill chip */
        .waybill-chip { display:inline-flex; align-items:center; gap:5px; font-family:monospace; font-size:12px; font-weight:600; color:#1e40af; background:#eff6ff; padding:3px 8px; border-radius:6px; }
        .no-waybill   { font-size:12px; color:#94a3b8; }

        /* Customer cell */
        .customer-name  { font-weight:600; color:#0f172a; }
        .customer-phone { font-size:12px; color:#64748b; }
        .customer-city  { font-size:11px; color:#94a3b8; margin-top:2px; }

        /* Code chips */
        .code-call  { display:block; font-weight:600; color:#0f172a; font-size:13px; font-family:monospace; }
        .code-order { display:block; font-size:11px; color:#64748b; margin-top:2px; font-family:monospace; }
        .td-cod { font-weight:600; color:#059669; white-space:nowrap; }
        .td-date { font-size:12px; color:#64748b; white-space:nowrap; }

        /* Action button */
        .btn-view-detail { display:inline-flex; align-items:center; gap:5px; padding:6px 12px; background:#eff6ff; border:1.5px solid #bfdbfe; border-radius:8px; color:#2563eb; font-size:12px; font-weight:600; text-decoration:none; transition:all .2s; }
        .btn-view-detail:hover { background:#dbeafe; border-color:#93c5fd; }

        /* Empty state */
        .empty-state { text-align:center; padding:60px 20px; color:#94a3b8; }
        .empty-state svg { margin:0 auto 16px; display:block; }
        .empty-state p { font-size:15px; margin:0; }

        /* Pagination */
        .pag-wrap { padding:16px 20px; border-top:1px solid #f1f5f9; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px; font-size:13px; color:#64748b; }
        .pag-links a, .pag-links span { display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; border-radius:7px; border:1.5px solid #e2e8f0; font-size:13px; font-weight:500; text-decoration:none; color:#475569; transition:all .2s; margin:0 2px; }
        .pag-links a:hover { background:#eff6ff; border-color:#93c5fd; color:#2563eb; }
        .pag-links span[aria-current] { background:#0f172a; border-color:#0f172a; color:#fff; font-weight:700; }
    </style>

    {{-- ── Header ── --}}
    <div class="page-header">
        <div>
            <h1 class="page-title">Delivery Pipeline</h1>
            <p class="page-subtitle">Your orders currently being processed by our team</p>
        </div>
        <a href="{{ route('seller.system_delivery.tracking.confirmed') }}" class="btn-tracking-link">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
            </svg>
            Curfox Tracking
        </a>
    </div>

    {{-- ── Read-only notice ── --}}
    <div class="readonly-notice">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>
        These deliveries are managed by our employee team. Status updates are made by staff only.
    </div>

    {{-- ── Pipeline Status Tabs ── --}}
    <div class="pipeline-tabs">
        <a href="{{ route('seller.system_delivery.pipeline') }}"
           class="pipeline-tab {{ $statusFilter === 'all' ? 'active' : '' }}">
            All
            <span class="tab-count">{{ $pipelineCounts['all'] }}</span>
        </a>
        <a href="{{ route('seller.system_delivery.pipeline') }}?status=Pending"
           class="pipeline-tab {{ $statusFilter === 'Pending' ? 'active' : '' }}"
           style="{{ $statusFilter === 'Pending' ? '' : 'border-color:#fde68a;color:#92400e;background:#fffbeb;' }}">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
            </svg>
            Pending
            <span class="tab-count" style="{{ $statusFilter !== 'Pending' ? 'background:#fef3c7;color:#92400e;' : '' }}">{{ $pipelineCounts['Pending'] }}</span>
        </a>
        <a href="{{ route('seller.system_delivery.pipeline') }}?status=packing"
           class="pipeline-tab {{ $statusFilter === 'packing' ? 'active' : '' }}"
           style="{{ $statusFilter === 'packing' ? '' : 'border-color:#bfdbfe;color:#1e40af;background:#eff6ff;' }}">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
            </svg>
            Packing
            <span class="tab-count" style="{{ $statusFilter !== 'packing' ? 'background:#dbeafe;color:#1e40af;' : '' }}">{{ $pipelineCounts['packing'] }}</span>
        </a>
        <a href="{{ route('seller.system_delivery.pipeline') }}?status=packed"
           class="pipeline-tab {{ $statusFilter === 'packed' ? 'active' : '' }}"
           style="{{ $statusFilter === 'packed' ? '' : 'border-color:#c4b5fd;color:#5b21b6;background:#faf5ff;' }}">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="20 6 9 17 4 12"/>
            </svg>
            Packed
            <span class="tab-count" style="{{ $statusFilter !== 'packed' ? 'background:#ede9fe;color:#5b21b6;' : '' }}">{{ $pipelineCounts['packed'] }}</span>
        </a>
        <a href="{{ route('seller.system_delivery.pipeline') }}?status=givedriver"
           class="pipeline-tab {{ $statusFilter === 'givedriver' ? 'active' : '' }}"
           style="{{ $statusFilter === 'givedriver' ? '' : 'border-color:#6ee7b7;color:#065f46;background:#f0fdf4;' }}">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="1" y="3" width="15" height="13"/><path d="M16 8h4l3 3v5h-7V8z"/>
                <circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>
            </svg>
            Give Driver
            <span class="tab-count" style="{{ $statusFilter !== 'givedriver' ? 'background:#d1fae5;color:#065f46;' : '' }}">{{ $pipelineCounts['givedriver'] }}</span>
        </a>
    </div>

    {{-- ── Tracking counts quick bar ── --}}
    @if(array_sum($trackingCounts) > 0)
        <div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:16px;">
            @if($trackingCounts['confirmed'] > 0)
                <a href="{{ route('seller.system_delivery.tracking.confirmed') }}" style="display:inline-flex;align-items:center;gap:5px;padding:5px 12px;background:#eff6ff;border:1px solid #bfdbfe;border-radius:20px;font-size:12px;font-weight:600;color:#1e40af;text-decoration:none;">
                    🚚 In Transit <span style="background:#dbeafe;border-radius:9px;padding:1px 6px;">{{ $trackingCounts['confirmed'] }}</span>
                </a>
            @endif
            @if($trackingCounts['completed'] > 0)
                <a href="{{ route('seller.system_delivery.tracking.completed') }}" style="display:inline-flex;align-items:center;gap:5px;padding:5px 12px;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:20px;font-size:12px;font-weight:600;color:#15803d;text-decoration:none;">
                    ✅ Delivered <span style="background:#d1fae5;border-radius:9px;padding:1px 6px;">{{ $trackingCounts['completed'] }}</span>
                </a>
            @endif
            @if($trackingCounts['rescheduled'] > 0)
                <a href="{{ route('seller.system_delivery.tracking.rescheduled') }}" style="display:inline-flex;align-items:center;gap:5px;padding:5px 12px;background:#fffbeb;border:1px solid #fde68a;border-radius:20px;font-size:12px;font-weight:600;color:#b45309;text-decoration:none;">
                    📅 Rescheduled <span style="background:#fef3c7;border-radius:9px;padding:1px 6px;">{{ $trackingCounts['rescheduled'] }}</span>
                </a>
            @endif
            @if($trackingCounts['cancelled'] > 0)
                <a href="{{ route('seller.system_delivery.tracking.cancelled') }}" style="display:inline-flex;align-items:center;gap:5px;padding:5px 12px;background:#fff1f2;border:1px solid #fecdd3;border-radius:20px;font-size:12px;font-weight:600;color:#be123c;text-decoration:none;">
                    ❌ Cancelled <span style="background:#fee2e2;border-radius:9px;padding:1px 6px;">{{ $trackingCounts['cancelled'] }}</span>
                </a>
            @endif
        </div>
    @endif

    {{-- ── Search ── --}}
    <div class="search-bar-row">
        <form method="GET" action="{{ route('seller.system_delivery.pipeline') }}" class="search-bar-row" style="margin:0;">
            @if($statusFilter !== 'all')
                <input type="hidden" name="status" value="{{ $statusFilter }}">
            @endif
            <div class="search-input-wrap">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
                <input type="text" name="q" value="{{ $q }}" placeholder="Search order, customer, city…" class="search-input">
            </div>
            <button type="submit" class="btn-search">Search</button>
            @if($q)
                <a href="{{ route('seller.system_delivery.pipeline') }}{{ $statusFilter !== 'all' ? '?status='.$statusFilter : '' }}" class="btn-clear">Clear</a>
            @endif
        </form>
        <span style="margin-left:auto;font-size:12px;color:#94a3b8;">
        {{ $calls->total() }} record{{ $calls->total() !== 1 ? 's' : '' }}
    </span>
    </div>

    {{-- ── Table ── --}}
    <div class="card-table">
        @if($calls->isEmpty())
            <div class="empty-state">
                <svg width="52" height="52" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="1.5">
                    <rect x="1" y="3" width="15" height="13" rx="2"/><path d="M16 8h4l3 3v5h-7V8z"/>
                    <circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>
                </svg>
                <p>No deliveries found{{ $statusFilter !== 'all' ? ' in this status' : '' }}{{ $q ? ' for "'.$q.'"' : '' }}.</p>
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
                        <th>Status</th>
                        <th>Waybill</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($calls as $i => $call)
                        <tr>
                            <td class="td-index">{{ $calls->firstItem() + $i }}</td>
                            <td>
                                <span class="code-call">{{ $call->call_number ?? '—' }}</span>
                                @if($call->order_code)
                                    <span class="code-order">{{ $call->order_code }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="customer-name">{{ $call->customer_name }}</div>
                                <div class="customer-phone">{{ $call->customer_phone_01 }}</div>
                                <div class="customer-city">{{ $call->city }}{{ $call->district ? ', '.$call->district : '' }}</div>
                            </td>
                            <td style="max-width:160px;">{{ Str::limit($call->item_name, 28) }}</td>
                            <td class="td-cod">Rs. {{ number_format(($call->price ?? 0) * ($call->qty ?? 1)) }}</td>
                            <td>
                                <span class="status-badge badge-{{ $call->status }}">
                                    <span>●</span> {{ ucfirst($call->status) }}
                                </span>
                            </td>
                            <td>
                                @if($call->curfox_waybill)
                                    <span class="waybill-chip">
                                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <rect x="1" y="3" width="15" height="13"/><path d="M16 8h4l3 3v5h-7V8z"/>
                                            <circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>
                                        </svg>
                                        {{ $call->curfox_waybill }}
                                    </span>
                                @else
                                    <span class="no-waybill">Not dispatched</span>
                                @endif
                            </td>
                            <td class="td-date">
                                {{ optional($call->created_at)->format('d M Y') }}
                            </td>
                            <td>
                                <a href="{{ route('seller.system_delivery.show', $call->id) }}"
                                   class="btn-view-detail">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                    View
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($calls->hasPages())
                <div class="pag-wrap">
                    <span>Showing {{ $calls->firstItem() }}–{{ $calls->lastItem() }} of {{ $calls->total() }}</span>
                    <div class="pag-links">{{ $calls->withQueryString()->links() }}</div>
                </div>
            @endif
        @endif
    </div>

@endsection
