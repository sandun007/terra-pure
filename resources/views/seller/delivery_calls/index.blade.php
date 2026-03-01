@extends('seller.layout.app')

@section('content')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap');

        :root {
            --bg:         #f4f6f9;
            --surface:    #ffffff;
            --surface-2:  #f8fafc;
            --surface-3:  #f1f4f8;
            --border:     #e2e8f0;
            --border-s:   #edf0f5;
            --text:       #0f172a;
            --text-sub:   #475569;
            --text-muted: #94a3b8;

            --indigo:     #4f46e5;
            --indigo-lt:  #eef2ff;
            --indigo-bd:  rgba(79,70,229,0.25);

            --emerald:    #059669;
            --emerald-lt: #ecfdf5;
            --emerald-bd: rgba(5,150,105,0.25);

            --amber:      #d97706;
            --amber-lt:   #fffbeb;
            --amber-bd:   rgba(217,119,6,0.25);

            --sky:        #0284c7;
            --sky-lt:     #f0f9ff;
            --sky-bd:     rgba(2,132,199,0.25);

            --violet:     #7c3aed;
            --violet-lt:  #f5f3ff;
            --violet-bd:  rgba(124,58,237,0.25);

            --rose:       #e11d48;
            --rose-lt:    #fff1f2;
            --rose-bd:    rgba(225,29,72,0.25);

            --sh-sm: 0 1px 3px rgba(15,23,42,.06),0 1px 2px rgba(15,23,42,.04);
            --sh:    0 4px 12px rgba(15,23,42,.08),0 1px 3px rgba(15,23,42,.05);
            --r:     10px;
            --r-lg:  14px;
            --r-xl:  18px;
        }

        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}

        .dc-page   { background:var(--bg); font-family:'Plus Jakarta Sans',sans-serif; min-height:100vh; padding:24px 20px 56px }
        .dc-wrap   { max-width:1280px; margin:0 auto; animation:up .3s cubic-bezier(.16,1,.3,1) both }
        @keyframes up { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:translateY(0)} }

        /* ── HEADER ── */
        .dc-header { display:flex; align-items:flex-start; justify-content:space-between; flex-wrap:wrap; gap:12px; margin-bottom:20px }
        .dc-title  { font-size:22px; font-weight:800; color:var(--text); letter-spacing:-.4px; display:flex; align-items:center; gap:10px }
        .dc-crumb  { font-size:12px; color:var(--text-muted); margin-top:4px; display:flex; align-items:center; gap:4px }
        .dc-crumb svg { width:11px; height:11px }
        .dc-actions { display:flex; gap:8px; align-items:center; flex-wrap:wrap }

        /* ── BUSINESS BANNER ── */
        .biz-banner { display:flex; align-items:center; gap:12px; padding:12px 18px; background:var(--emerald-lt); border:1px solid var(--emerald-bd); border-radius:var(--r-lg); margin-bottom:16px; cursor:pointer; transition:.15s }
        .biz-banner:hover { background:#d1fae5 }
        .biz-banner-ico { width:36px; height:36px; background:var(--emerald); border-radius:9px; display:flex; align-items:center; justify-content:center; font-size:16px; flex-shrink:0 }
        .biz-banner-txt { flex:1; min-width:0 }
        .biz-banner-label { font-size:10px; font-weight:700; color:var(--emerald); text-transform:uppercase; letter-spacing:.7px }
        .biz-banner-name  { font-size:14px; font-weight:700; color:var(--text) }
        .biz-banner-arrow { color:var(--emerald); font-size:18px; font-weight:700 }

        /* No business warning */
        .no-biz-banner { display:flex; align-items:center; gap:12px; padding:12px 18px; background:var(--rose-lt); border:1px solid var(--rose-bd); border-radius:var(--r-lg); margin-bottom:16px }

        /* ── TABS ── */
        .dc-tabs { display:flex; gap:4px; flex-wrap:wrap; background:var(--surface); border:1px solid var(--border); border-radius:var(--r-lg); padding:5px; margin-bottom:16px; box-shadow:var(--sh-sm) }
        .dc-tab   { font-family:'Plus Jakarta Sans',sans-serif; font-size:13px; font-weight:600; padding:8px 20px; border-radius:8px; border:none; background:transparent; color:var(--text-muted); cursor:pointer; text-decoration:none; transition:.15s; display:flex; align-items:center; gap:6px }
        .dc-tab:hover  { background:var(--surface-2); color:var(--text) }
        .dc-tab.active { background:var(--emerald); color:#fff; box-shadow:0 2px 8px rgba(5,150,105,.3) }
        .dc-tab-count  { font-size:10px; font-weight:700; padding:2px 6px; border-radius:4px; background:rgba(0,0,0,.12); color:inherit }

        /* ── TOOLBAR ── */
        .dc-toolbar { display:flex; align-items:center; gap:10px; flex-wrap:wrap; margin-bottom:14px }
        .dc-search  { flex:1; min-width:200px; display:flex; align-items:center; gap:8px; background:var(--surface); border:1px solid var(--border); border-radius:var(--r); padding:0 14px; box-shadow:var(--sh-sm) }
        .dc-search input { flex:1; border:none; background:transparent; font-family:'Plus Jakarta Sans',sans-serif; font-size:13px; color:var(--text); padding:10px 0; outline:none }
        .dc-search svg   { color:var(--text-muted); flex-shrink:0 }

        /* ── BUTTONS ── */
        .btn { font-family:'Plus Jakarta Sans',sans-serif; font-size:13px; font-weight:600; padding:9px 18px; border-radius:var(--r); border:1px solid var(--border); background:var(--surface); color:var(--text-sub); cursor:pointer; transition:.15s; display:inline-flex; align-items:center; gap:7px; text-decoration:none; white-space:nowrap }
        .btn:hover { background:var(--surface-3); color:var(--text) }
        .btn-em { background:var(--emerald); color:#fff; border-color:transparent; box-shadow:0 2px 8px rgba(5,150,105,.25) }
        .btn-em:hover { background:#047857; color:#fff }
        .btn-ind { background:var(--indigo-lt); color:var(--indigo); border-color:var(--indigo-bd) }
        .btn-ind:hover { background:var(--indigo); color:#fff }
        .btn-sm { padding:6px 12px; font-size:12px }
        .btn-rose { background:var(--rose-lt); color:var(--rose); border-color:var(--rose-bd) }
        .btn-rose:hover { background:var(--rose); color:#fff }

        /* ── TABLE ── */
        .dc-card  { background:var(--surface); border:1px solid var(--border); border-radius:var(--r-xl); box-shadow:var(--sh); overflow:hidden }
        .dc-table { width:100%; border-collapse:collapse; font-family:'Plus Jakarta Sans',sans-serif }
        .dc-table thead th { font-size:11px; font-weight:700; color:var(--text-muted); text-transform:uppercase; letter-spacing:.7px; padding:12px 14px; border-bottom:1px solid var(--border); background:var(--surface-2); white-space:nowrap }
        .dc-table tbody tr { border-bottom:1px solid var(--border-s); transition:.12s }
        .dc-table tbody tr:last-child { border-bottom:none }
        .dc-table tbody tr:hover { background:var(--surface-2) }
        .dc-table tbody td { padding:12px 14px; font-size:13px; color:var(--text) }
        .dc-table tbody tr.row-fail { background:#fff1f2 }

        .dc-table-wrap { overflow-x:auto }

        .dc-cb { width:16px; height:16px; accent-color:var(--emerald); cursor:pointer }

        /* ── BADGES ── */
        .dc-badge { font-size:10px; font-weight:700; letter-spacing:.5px; text-transform:uppercase; padding:3px 10px; border-radius:20px; display:inline-flex; align-items:center; gap:4px; border:1px solid; white-space:nowrap }
        .dc-badge::before { content:''; width:5px; height:5px; border-radius:50%; background:currentColor; opacity:.7; flex-shrink:0 }
        .badge-Pending,.dc-badge-Pending     { background:var(--amber-lt); border-color:var(--amber-bd); color:var(--amber) }
        .badge-packing,.dc-badge-packing     { background:var(--sky-lt); border-color:var(--sky-bd); color:var(--sky) }
        .badge-packed,.dc-badge-packed       { background:var(--violet-lt); border-color:var(--violet-bd); color:var(--violet) }
        .badge-givedriver,.dc-badge-givedriver { background:var(--emerald-lt); border-color:var(--emerald-bd); color:var(--emerald) }

        /* ── ORIGIN TAG ── */
        .origin-tag { display:inline-flex; align-items:center; gap:4px; font-family:'JetBrains Mono',monospace; font-size:10px; font-weight:700; padding:3px 8px; border-radius:5px; border:1px solid; cursor:pointer }
        .origin-tag.custom  { background:var(--sky-lt); color:var(--sky); border-color:var(--sky-bd) }
        .origin-tag.default { background:var(--amber-lt); color:var(--amber); border-color:var(--amber-bd) }

        /* Error tag */
        .err-tag { font-size:10px; color:var(--rose); font-weight:600; display:flex; align-items:center; gap:3px; margin-top:3px; max-width:160px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap }

        /* ── BULK BAR ── */
        .bulk-bar { display:none; align-items:center; gap:10px; flex-wrap:wrap; padding:10px 16px; background:var(--indigo-lt); border:1px solid var(--indigo-bd); border-radius:var(--r-lg); margin-bottom:12px }
        .bulk-bar.show { display:flex }
        .bulk-count { font-size:13px; font-weight:700; color:var(--indigo) }

        /* ── PROGRESS ── */
        .progress-pill { font-family:'JetBrains Mono',monospace; font-size:12px; font-weight:700; padding:4px 10px; border-radius:6px; background:var(--surface-3); color:var(--text-muted); display:none }
        .progress-pill.show { display:inline-flex }

        /* ── EMPTY ── */
        .dc-empty { text-align:center; padding:60px 20px }
        .dc-empty-ico { font-size:48px; margin-bottom:12px }
        .dc-empty-txt { font-size:15px; font-weight:700; color:var(--text); margin-bottom:6px }
        .dc-empty-sub { font-size:13px; color:var(--text-muted) }

        /* ── MODAL ── */
        .modal-overlay { position:fixed; inset:0; background:rgba(15,23,42,.45); backdrop-filter:blur(4px); z-index:1000; display:flex; align-items:center; justify-content:center; padding:20px; opacity:0; pointer-events:none; transition:.2s }
        .modal-overlay.open { opacity:1; pointer-events:all }
        .modal-box { background:var(--surface); border-radius:var(--r-xl); box-shadow:0 24px 64px rgba(15,23,42,.18); width:100%; max-width:520px; max-height:88vh; overflow:auto; transform:scale(.96); transition:.2s }
        .modal-overlay.open .modal-box { transform:scale(1) }
        .modal-head { padding:18px 22px; border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between }
        .modal-title { font-size:16px; font-weight:800; color:var(--text) }
        .modal-close { width:32px; height:32px; border-radius:8px; border:1px solid var(--border); background:var(--surface-2); cursor:pointer; display:flex; align-items:center; justify-content:center; font-size:18px; color:var(--text-muted) }
        .modal-close:hover { background:var(--surface-3) }
        .modal-body { padding:20px 22px }
        .modal-foot { padding:14px 22px; border-top:1px solid var(--border); display:flex; gap:8px; justify-content:flex-end }

        /* ── FORM ── */
        .form-group { margin-bottom:14px }
        .form-label { font-size:11px; font-weight:700; color:var(--text-muted); text-transform:uppercase; letter-spacing:.6px; margin-bottom:5px; display:block }
        .form-control { width:100%; font-family:'Plus Jakarta Sans',sans-serif; font-size:13px; color:var(--text); border:1px solid var(--border); border-radius:var(--r); padding:9px 13px; background:var(--surface); outline:none; transition:.15s }
        .form-control:focus { border-color:var(--emerald); box-shadow:0 0 0 3px var(--emerald-bd) }

        /* ── BIZ CARDS ── */
        .biz-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(220px,1fr)); gap:12px; margin-top:14px }
        .biz-card { border:2px solid var(--border); border-radius:var(--r-lg); padding:14px 16px; cursor:pointer; transition:.15s; background:var(--surface-2) }
        .biz-card:hover { border-color:var(--emerald-bd); background:var(--emerald-lt) }
        .biz-card.selected { border-color:var(--emerald); background:var(--emerald-lt) }
        .biz-card-name { font-size:14px; font-weight:700; color:var(--text); margin-bottom:4px }
        .biz-card-meta { font-size:11px; color:var(--text-muted) }

        /* PAGINATION */
        .dc-pager { display:flex; justify-content:center; padding:16px; gap:4px }
        .dc-pager a, .dc-pager span { font-size:13px; font-weight:600; padding:6px 12px; border-radius:7px; border:1px solid var(--border); background:var(--surface); color:var(--text-sub); text-decoration:none; transition:.12s }
        .dc-pager a:hover { background:var(--surface-3) }
        .dc-pager span.active { background:var(--emerald); color:#fff; border-color:var(--emerald) }
    </style>

    <div class="dc-page">
        <div class="dc-wrap">

            {{-- HEADER --}}
            <div class="dc-header">
                <div>
                    <div class="dc-crumb">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                        Seller Portal
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:11px;height:11px"><polyline points="9 18 15 12 9 6"/></svg>
                        My Delivery Calls
                    </div>
                    <div class="dc-title">
                        🚚 My Delivery Calls
                        <span class="dc-badge dc-badge-{{ $status }}" style="font-size:11px">{{ ucfirst($status) }}</span>
                    </div>
                </div>
                <div class="dc-actions">
                    <a href="{{ route('seller.delivery_calls.create') }}" class="btn btn-em">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Add New Call
                    </a>
                    <a href="{{ route('seller.delivery_calls.businesses') }}" class="btn btn-ind">
                        🏢 My Businesses
                    </a>
                </div>
            </div>

            {{-- BUSINESS BANNER --}}
            @if($myBusinesses->isEmpty())
                <div class="no-biz-banner">
                    <div style="font-size:22px">⚠️</div>
                    <div>
                        <div style="font-size:13px;font-weight:700;color:var(--rose)">No Curfox Business Configured</div>
                        <div style="font-size:12px;color:var(--text-sub);margin-top:2px">You need to create a Curfox business before you can dispatch orders.</div>
                    </div>
                    <a href="{{ route('seller.delivery_calls.businesses') }}" class="btn btn-rose btn-sm" style="margin-left:auto">Create Business</a>
                </div>
            @else
                <div class="biz-banner" onclick="openBizModal()" id="bizBanner">
                    <div class="biz-banner-ico">🏢</div>
                    <div class="biz-banner-txt">
                        <div class="biz-banner-label">Active Curfox Business</div>
                        <div class="biz-banner-name" id="activeBizName">
                            {{ $activeBusiness?->business_name ?? 'Click to select a business' }}
                        </div>
                    </div>
                    <div class="biz-banner-arrow">⟶</div>
                </div>
            @endif

            {{-- STATUS TABS --}}
            @php
                $tabs = [
                    'Pending'    => ['route' => 'seller.delivery_calls.pending',    'icon' => '⏳', 'label' => 'Pending'],
                    'packing'    => ['route' => 'seller.delivery_calls.packing',    'icon' => '📦', 'label' => 'Packing'],
                    'packed'     => ['route' => 'seller.delivery_calls.packed',     'icon' => '✅', 'label' => 'Packed'],
                    'givedriver' => ['route' => 'seller.delivery_calls.givedriver', 'icon' => '🚚', 'label' => 'Give Driver'],
                ];
            @endphp
            <div class="dc-tabs">
                @foreach($tabs as $s => $tab)
                    @php $count = \App\Models\SelfSellerDeliveryCall::where('seller_id', $seller->id)->where('status', $s)->count() @endphp
                    <a href="{{ route($tab['route']) }}"
                       class="dc-tab {{ $status === $s ? 'active' : '' }}">
                        {{ $tab['icon'] }} {{ $tab['label'] }}
                        <span class="dc-tab-count">{{ $count }}</span>
                    </a>
                @endforeach
            </div>

            {{-- FLASH --}}
            @if(session('success'))
                <div style="padding:12px 16px;background:var(--emerald-lt);border:1px solid var(--emerald-bd);border-radius:var(--r-lg);color:var(--emerald);font-size:13px;font-weight:600;margin-bottom:14px;display:flex;align-items:center;gap:8px">
                    ✅ {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div style="padding:12px 16px;background:var(--rose-lt);border:1px solid var(--rose-bd);border-radius:var(--r-lg);color:var(--rose);font-size:13px;font-weight:600;margin-bottom:14px;display:flex;align-items:center;gap:8px">
                    ⚠️ {{ session('error') }}
                </div>
            @endif

            {{-- TOOLBAR --}}
            <div class="dc-toolbar">
                <form class="dc-search" method="GET">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input name="q" value="{{ request('q') }}" placeholder="Search name, phone, city, order code…">
                    @if(request('q'))
                        <a href="{{ request()->url() }}" style="color:var(--text-muted);text-decoration:none;font-size:14px">✕</a>
                    @endif
                </form>

                @if($calls->total() > 0)
                    <button class="btn btn-sm" onclick="selectAll()">☑ All</button>
                    <button class="btn btn-sm" onclick="clearAll()">☐ None</button>
                @endif

                <span class="progress-pill" id="progressPill"></span>
            </div>

            {{-- BULK ACTION BAR --}}
            <div class="bulk-bar" id="bulkBar">
                <span class="bulk-count" id="bulkCount">0 selected</span>
                @if($nextStatus)
                    <button class="btn btn-em btn-sm" onclick="bulkAdvanceStatus()">
                        @if($nextStatus === 'givedriver')
                            🚚 Bulk Dispatch to Curfox
                        @else
                            ⚡ Bulk → {{ ucfirst($nextStatus) }}
                        @endif
                    </button>
                @endif
                <button class="btn btn-sm" onclick="openBulkOriginModal()">📍 Set Origin</button>
            </div>

            {{-- TABLE --}}
            <div class="dc-card">
                @if($calls->isEmpty())
                    <div class="dc-empty">
                        <div class="dc-empty-ico">📭</div>
                        <div class="dc-empty-txt">No {{ ucfirst($status) }} delivery calls</div>
                        <div class="dc-empty-sub">
                            @if(request('q'))
                                No results for "{{ request('q') }}". <a href="{{ request()->url() }}">Clear search</a>
                            @else
                                Create your first delivery call to get started.
                            @endif
                        </div>
                    </div>
                @else
                    <div class="dc-table-wrap">
                        <table class="dc-table">
                            <thead>
                            <tr>
                                <th style="width:36px"><input type="checkbox" class="dc-cb" id="masterCb" onchange="toggleAll(this)"></th>
                                <th>Call #</th>
                                <th>Customer</th>
                                <th>City</th>
                                <th>Item</th>
                                <th>COD</th>
                                <th>Origin</th>
                                @if($nextStatus === 'givedriver')
                                    <th>Waybill</th>
                                @endif
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($calls as $call)
                                <tr id="row_tr_{{ $call->id }}">
                                    <td><input type="checkbox" class="dc-cb row-cb" value="{{ $call->id }}" onchange="onRowCheck()"></td>
                                    <td>
                                        <a href="{{ route('seller.delivery_calls.show', $call->id) }}"
                                           style="font-family:'JetBrains Mono',monospace;font-size:12px;font-weight:700;color:var(--indigo);text-decoration:none">
                                            {{ $call->call_number }}
                                        </a>
                                        @if($call->order_code)
                                            <div style="font-size:11px;color:var(--text-muted)">{{ $call->order_code }}</div>
                                        @endif
                                        @if($call->curfox_error)
                                            <div class="err-tag" title="{{ $call->curfox_error }}">⚠ {{ $call->curfox_error }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        <div style="font-weight:600">{{ $call->customer_name }}</div>
                                        <div style="font-family:'JetBrains Mono',monospace;font-size:11px;color:var(--text-muted)">{{ $call->customer_phone_01 }}</div>
                                    </td>
                                    <td>
                                        <div>{{ $call->city ?? '—' }}</div>
                                        @if($call->destination_city_id)
                                            <div style="font-size:10px;font-weight:700;color:var(--emerald)">✓ ID:{{ $call->destination_city_id }}</div>
                                        @else
                                            <div style="font-size:10px;color:var(--amber);font-weight:600">⚠ no city ID</div>
                                        @endif
                                    </td>
                                    <td>
                                        <div style="font-weight:600;max-width:140px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $call->item_name }}</div>
                                        <div style="font-size:11px;color:var(--text-muted)">× {{ $call->qty }}</div>
                                    </td>
                                    <td>
                                        <div style="font-family:'JetBrains Mono',monospace;font-weight:700;color:var(--emerald)">
                                            Rs. {{ number_format($call->price * $call->qty, 0) }}
                                        </div>
                                    </td>
                                    <td>
                                    <span class="origin-tag {{ $call->hasCustomOrigin() ? 'custom' : 'default' }}"
                                          onclick="openOriginModal({{ $call->id }}, '{{ addslashes($call->effectiveOriginCityName()) }}', '{{ addslashes($call->effectiveOriginStateName()) }}')">
                                        📍 {{ $call->effectiveOriginCityName() }}
                                        @if(!$call->hasCustomOrigin()) <span style="font-size:9px;opacity:.7">(default)</span> @endif
                                    </span>
                                    </td>
                                    @if($nextStatus === 'givedriver')
                                        <td>
                                            @if($call->curfox_waybill)
                                                <span style="font-family:'JetBrains Mono',monospace;font-size:12px;font-weight:700;color:var(--emerald)">{{ $call->curfox_waybill }}</span>
                                            @else
                                                <span style="font-size:11px;color:var(--text-muted)">—</span>
                                            @endif
                                        </td>
                                    @endif
                                    <td style="font-size:11px;color:var(--text-muted);font-family:'JetBrains Mono',monospace">
                                        {{ optional($call->date)->format('Y-m-d') ?? '—' }}
                                    </td>
                                    <td>
                                        <div style="display:flex;gap:6px;flex-wrap:wrap">
                                            <a href="{{ route('seller.delivery_calls.show', $call->id) }}" class="btn btn-sm">View</a>
                                            @if($nextStatus)
                                                <button class="btn btn-em btn-sm dispatch-single-btn"
                                                        onclick="singleAdvance({{ $call->id }}, this)"
                                                        id="btn_{{ $call->id }}">
                                                    @if($nextStatus === 'givedriver') 🚚 @else → @endif
                                                    {{ ucfirst($nextStatus) }}
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- PAGINATION --}}
                    @if($calls->hasPages())
                        <div class="dc-pager">
                            {{ $calls->links('pagination::simple-tailwind') }}
                        </div>
                    @endif
                @endif
            </div>

        </div>
    </div>

    {{-- ════════════════════════════════
         BUSINESS SELECTOR MODAL
         ════════════════════════════════ --}}
    <div class="modal-overlay" id="bizModal">
        <div class="modal-box">
            <div class="modal-head">
                <span class="modal-title">🏢 Select Active Curfox Business</span>
                <button class="modal-close" onclick="closeBizModal()">×</button>
            </div>
            <div class="modal-body">
                <p style="font-size:13px;color:var(--text-sub);margin-bottom:10px">
                    The selected business will be used as the pickup origin for all dispatch calls.
                </p>
                <div class="biz-grid" id="bizGrid">
                    @foreach($myBusinesses as $biz)
                        <div class="biz-card {{ $biz->curfox_id == $activeBusinessId ? 'selected' : '' }}"
                             data-biz-id="{{ $biz->curfox_id }}"
                             onclick="selectBiz('{{ $biz->curfox_id }}', '{{ addslashes($biz->business_name) }}', this)">
                            <div class="biz-card-name">{{ $biz->business_name }}</div>
                            <div class="biz-card-meta">
                                @if($biz->city_name) 📍 {{ $biz->city_name }}<br>@endif
                                @if($biz->ref_no) Ref: {{ $biz->ref_no }}<br>@endif
                                ID: {{ $biz->curfox_id }}
                            </div>
                            @if($biz->is_default)
                                <span style="font-size:10px;background:var(--emerald-lt);color:var(--emerald);border:1px solid var(--emerald-bd);padding:2px 6px;border-radius:4px;margin-top:6px;display:inline-block">Default</span>
                            @endif
                        </div>
                    @endforeach
                </div>
                <div style="margin-top:14px">
                    <a href="{{ route('seller.delivery_calls.businesses') }}" class="btn btn-ind btn-sm">
                        + Create New Business
                    </a>
                </div>
            </div>
            <div class="modal-foot">
                <button class="btn" onclick="closeBizModal()">Close</button>
            </div>
        </div>
    </div>

    {{-- ════════════════════════════════
         ORIGIN PICKER MODAL
         ════════════════════════════════ --}}
    <div class="modal-overlay" id="originModal">
        <div class="modal-box">
            <div class="modal-head">
                <span class="modal-title">📍 Set Pickup Origin</span>
                <button class="modal-close" onclick="document.getElementById('originModal').classList.remove('open')">×</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Search City</label>
                    <input class="form-control" id="citySearch" placeholder="Type to search cities…" oninput="filterCities()">
                </div>
                <div id="cityList" style="max-height:280px;overflow-y:auto;border:1px solid var(--border);border-radius:var(--r-lg);margin-top:4px"></div>
                <input type="hidden" id="originCallId">
                <input type="hidden" id="selectedCityId">
                <input type="hidden" id="selectedCityName">
                <input type="hidden" id="selectedStateName">
            </div>
            <div class="modal-foot">
                <button class="btn" onclick="document.getElementById('originModal').classList.remove('open')">Cancel</button>
                <button class="btn btn-em" onclick="saveOrigin()">Save Origin</button>
            </div>
        </div>
    </div>

    {{-- BULK ORIGIN MODAL --}}
    <div class="modal-overlay" id="bulkOriginModal">
        <div class="modal-box">
            <div class="modal-head">
                <span class="modal-title">📍 Set Origin for Selected</span>
                <button class="modal-close" onclick="document.getElementById('bulkOriginModal').classList.remove('open')">×</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Search City</label>
                    <input class="form-control" id="bulkCitySearch" placeholder="Type to search cities…" oninput="filterBulkCities()">
                </div>
                <div id="bulkCityList" style="max-height:280px;overflow-y:auto;border:1px solid var(--border);border-radius:var(--r-lg);margin-top:4px"></div>
                <input type="hidden" id="bulkSelectedCityId">
                <input type="hidden" id="bulkSelectedCityName">
                <input type="hidden" id="bulkSelectedStateName">
            </div>
            <div class="modal-foot">
                <button class="btn" onclick="document.getElementById('bulkOriginModal').classList.remove('open')">Cancel</button>
                <button class="btn btn-em" onclick="saveBulkOrigin()">Apply to Selected</button>
            </div>
        </div>
    </div>

    <script>
        const CSRF        = '{{ csrf_token() }}';
        const BASE_URL    = '{{ rtrim(route("seller.delivery_calls.index"), "/") }}';
        const STATUS      = '{{ $status }}';
        const NEXT_STATUS = '{{ $nextStatus }}';

        // ── Curfox cities ─────────────────────────────────────────────────────
        const ALL_CITIES = @json($curfoxCities);

        // ── Business selector ─────────────────────────────────────────────────
        let activeBizId   = '{{ $activeBusinessId }}';
        let activeBizName = '{{ $activeBusiness?->business_name ?? "" }}';

        function openBizModal()  { document.getElementById('bizModal').classList.add('open') }
        function closeBizModal() { document.getElementById('bizModal').classList.remove('open') }

        function selectBiz(id, name, el) {
            document.querySelectorAll('.biz-card').forEach(c => c.classList.remove('selected'));
            el.classList.add('selected');

            fetch(`${BASE_URL}/business/set-active`, {
                method: 'POST',
                headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': CSRF },
                body: JSON.stringify({ business_id: id }),
            })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        activeBizId   = id;
                        activeBizName = name;
                        document.getElementById('activeBizName').textContent = name;
                        closeBizModal();
                    }
                })
                .catch(console.error);
        }

        // ── Checkbox helpers ──────────────────────────────────────────────────
        function toggleAll(cb) {
            document.querySelectorAll('.row-cb').forEach(c => c.checked = cb.checked);
            onRowCheck();
        }

        function selectAll() {
            document.querySelectorAll('.row-cb').forEach(c => c.checked = true);
            document.getElementById('masterCb').checked = true;
            onRowCheck();
        }

        function clearAll() {
            document.querySelectorAll('.row-cb').forEach(c => c.checked = false);
            document.getElementById('masterCb').checked = false;
            onRowCheck();
        }

        function getSelected() {
            return [...document.querySelectorAll('.row-cb:checked')].map(c => parseInt(c.value));
        }

        function onRowCheck() {
            const sel   = getSelected();
            const bar   = document.getElementById('bulkBar');
            const count = document.getElementById('bulkCount');
            if (sel.length > 0) {
                bar.classList.add('show');
                count.textContent = sel.length + ' selected';
            } else {
                bar.classList.remove('show');
            }
        }

        // ── Single advance ────────────────────────────────────────────────────
        async function singleAdvance(id, btn) {
            if (!confirm(NEXT_STATUS === 'givedriver'
                ? `Dispatch call #${id} to Curfox now?`
                : `Advance call #${id} to "${NEXT_STATUS}"?`)) return;

            btn.disabled = true;
            btn.textContent = '…';

            try {
                const res  = await fetch(`${BASE_URL}/${id}/status`, {
                    method: 'POST',
                    headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': CSRF },
                    body: JSON.stringify({}),
                });
                const data = await res.json();

                if (data.success) {
                    if (data.waybill) {
                        // Find waybill cell and update
                        const tr = document.getElementById('row_tr_' + id);
                        const waybillCells = tr?.querySelectorAll('td');
                        btn.textContent = '✓ Done';
                        btn.style.background = '#d1fae5';
                        btn.style.color = '#065f46';
                    } else {
                        btn.textContent = '✓ Done';
                        btn.disabled = false;
                    }
                    showToast(data.message, 'success');
                } else {
                    throw new Error(data.message || 'Failed');
                }
            } catch (e) {
                btn.disabled = false;
                btn.textContent = '→ Retry';
                const tr = document.getElementById('row_tr_' + id);
                tr?.classList.add('row-fail');
                showToast(e.message, 'error');
            }
        }

        // ── Bulk advance ──────────────────────────────────────────────────────
        async function bulkAdvanceStatus() {
            const ids = getSelected();
            if (!ids.length) return;

            const label = NEXT_STATUS === 'givedriver' ? `Bulk dispatch ${ids.length} to Curfox?` : `Advance ${ids.length} rows to "${NEXT_STATUS}"?`;
            if (!confirm(label)) return;

            const pill = document.getElementById('progressPill');
            pill.classList.add('show');
            pill.textContent = `0/${ids.length} · ✓0 · ✕0`;

            let ok = 0, fail = 0;

            for (let i = 0; i < ids.length; i++) {
                const id = ids[i];
                try {
                    const res = await fetch(`${BASE_URL}/${id}/status`, {
                        method: 'POST',
                        headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': CSRF },
                        body: JSON.stringify({}),
                    });
                    if (!res.ok) {
                        let em = `HTTP ${res.status}`;
                        try { const j = await res.json(); em = j.message || em; } catch(e) {}
                        throw new Error(em);
                    }
                    const data = await res.json();
                    if (data.success) {
                        ok++;
                        const btn = document.getElementById('btn_' + id);
                        if (btn) { btn.textContent = '✓ Done'; btn.disabled = true; }
                    } else {
                        throw new Error(data.message || 'Failed');
                    }
                } catch (e) {
                    fail++;
                    document.getElementById('row_tr_' + id)?.classList.add('row-fail');
                }
                pill.textContent = `${i+1}/${ids.length} · ✓${ok} · ✕${fail}`;
            }

            setTimeout(() => { pill.classList.remove('show'); }, 4000);
            showToast(`Done — ✓ ${ok} succeeded · ✕ ${fail} failed`, fail > 0 ? 'error' : 'success');
            clearAll();
        }

        // ── Origin modal ──────────────────────────────────────────────────────
        let activeCallId = null;

        function openOriginModal(callId, currentCity, currentState) {
            activeCallId = callId;
            document.getElementById('originCallId').value = callId;
            document.getElementById('citySearch').value = '';
            renderCityList('cityList', 'citySearch', 'selectedCityId', 'selectedCityName', 'selectedStateName');
            document.getElementById('originModal').classList.add('open');
        }

        function openBulkOriginModal() {
            if (!getSelected().length) { alert('Select at least one row.'); return; }
            document.getElementById('bulkCitySearch').value = '';
            renderCityList('bulkCityList', 'bulkCitySearch', 'bulkSelectedCityId', 'bulkSelectedCityName', 'bulkSelectedStateName');
            document.getElementById('bulkOriginModal').classList.add('open');
        }

        function renderCityList(listId, searchId, cityIdField, cityNameField, stateNameField) {
            const q    = document.getElementById(searchId).value.toLowerCase();
            const list = document.getElementById(listId);
            const filtered = ALL_CITIES.filter(c => !q || c.name.toLowerCase().includes(q) || c.state_name.toLowerCase().includes(q));
            const slice = filtered.slice(0, 80);

            if (!slice.length) {
                list.innerHTML = '<div style="padding:16px;text-align:center;color:var(--text-muted);font-size:13px">No cities found</div>';
                return;
            }

            list.innerHTML = slice.map(c => `
        <div onclick="selectCity(${c.id},'${c.name.replace(/'/g,"\\'")}','${c.state_name.replace(/'/g,"\\'")}','${listId}','${cityIdField}','${cityNameField}','${stateNameField}')"
             style="padding:10px 14px;cursor:pointer;border-bottom:1px solid var(--border-s);font-size:13px;transition:.1s"
             onmouseover="this.style.background='var(--surface-2)'" onmouseout="this.style.background=''"
             id="city_opt_${listId}_${c.id}">
            <span style="font-weight:600">${c.name}</span>
            <span style="font-size:11px;color:var(--text-muted);margin-left:6px">${c.state_name}</span>
        </div>
    `).join('');
        }

        function selectCity(id, name, state, listId, cityIdField, cityNameField, stateNameField) {
            document.getElementById(cityIdField).value = id;
            document.getElementById(cityNameField).value = name;
            document.getElementById(stateNameField).value = state;
            document.querySelectorAll(`[id^="city_opt_${listId}_"]`).forEach(el => el.style.background = '');
            const el = document.getElementById(`city_opt_${listId}_${id}`);
            if (el) el.style.background = 'var(--emerald-lt)';
        }

        function filterCities() {
            renderCityList('cityList','citySearch','selectedCityId','selectedCityName','selectedStateName');
        }

        function filterBulkCities() {
            renderCityList('bulkCityList','bulkCitySearch','bulkSelectedCityId','bulkSelectedCityName','bulkSelectedStateName');
        }

        async function saveOrigin() {
            const callId    = document.getElementById('originCallId').value;
            const cityId    = document.getElementById('selectedCityId').value;
            const cityName  = document.getElementById('selectedCityName').value;
            const stateName = document.getElementById('selectedStateName').value;

            if (!cityId) { alert('Please select a city.'); return; }

            try {
                const res  = await fetch(`${BASE_URL}/${callId}/origin`, {
                    method: 'PATCH',
                    headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': CSRF },
                    body: JSON.stringify({ origin_city_id: parseInt(cityId), origin_city_name: cityName, origin_state_name: stateName }),
                });
                const data = await res.json();
                if (data.success) {
                    showToast(data.message, 'success');
                    document.getElementById('originModal').classList.remove('open');
                    setTimeout(() => location.reload(), 800);
                } else {
                    showToast(data.message || 'Failed', 'error');
                }
            } catch (e) {
                showToast(e.message, 'error');
            }
        }

        async function saveBulkOrigin() {
            const ids       = getSelected();
            const cityId    = document.getElementById('bulkSelectedCityId').value;
            const cityName  = document.getElementById('bulkSelectedCityName').value;
            const stateName = document.getElementById('bulkSelectedStateName').value;

            if (!cityId) { alert('Please select a city first.'); return; }
            if (!ids.length) { alert('No rows selected.'); return; }

            try {
                const res  = await fetch(`${BASE_URL.replace(/\/[^/]+$/, '')}/bulk-origin`, {
                    method: 'PATCH',
                    headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': CSRF },
                    body: JSON.stringify({ ids, origin_city_id: parseInt(cityId), origin_city_name: cityName, origin_state_name: stateName }),
                });
                const data = await res.json();
                showToast(data.message, data.success ? 'success' : 'error');
                document.getElementById('bulkOriginModal').classList.remove('open');
                if (data.success) setTimeout(() => location.reload(), 800);
            } catch(e) {
                showToast(e.message, 'error');
            }
        }

        // ── Toast ─────────────────────────────────────────────────────────────
        function showToast(msg, type = 'success') {
            const t = document.createElement('div');
            t.style.cssText = `position:fixed;bottom:24px;right:24px;z-index:9999;padding:13px 20px;border-radius:10px;font-family:'Plus Jakarta Sans',sans-serif;font-size:13px;font-weight:600;box-shadow:0 8px 24px rgba(0,0,0,.15);max-width:340px;transition:.3s;background:${type === 'success' ? '#ecfdf5' : '#fff1f2'};color:${type === 'success' ? '#065f46' : '#9f1239'};border:1px solid ${type === 'success' ? 'rgba(5,150,105,.3)' : 'rgba(225,29,72,.3)'}`;
            t.textContent = (type === 'success' ? '✅ ' : '⚠️ ') + msg;
            document.body.appendChild(t);
            setTimeout(() => t.remove(), 4500);
        }

        // Close modals on backdrop click
        ['bizModal','originModal','bulkOriginModal'].forEach(id => {
            document.getElementById(id)?.addEventListener('click', function(e) {
                if (e.target === this) this.classList.remove('open');
            });
        });
    </script>
@endsection
