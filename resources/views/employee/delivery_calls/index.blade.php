@extends('employee.layout.app')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

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
            --text-dim:   #cbd5e1;

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
            --r-xl:  18px;
        }

        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}

        .dc-page{background:var(--bg);font-family:'Plus Jakarta Sans',sans-serif;min-height:100vh;padding:28px 24px 56px}
        .dc-wrap{max-width:1600px;animation:up .35s cubic-bezier(.16,1,.3,1) both}
        @keyframes up{from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:translateY(0)}}

        /* ── HEADER ── */
        .dc-hdr{display:flex;align-items:flex-start;justify-content:space-between;gap:20px;flex-wrap:wrap;margin-bottom:24px}
        .dc-crumb{display:flex;align-items:center;gap:5px;font-size:12px;color:var(--text-muted);margin-bottom:7px;font-weight:500}
        .dc-crumb svg{width:11px;height:11px;flex-shrink:0}
        .dc-title{font-size:22px;font-weight:800;color:var(--text);letter-spacing:-.5px;display:flex;align-items:center;gap:10px}
        .dc-tbadge{font-family:'JetBrains Mono',monospace;font-size:11px;font-weight:500;padding:3px 10px;border-radius:20px;background:var(--indigo-lt);color:var(--indigo);border:1px solid var(--indigo-bd)}
        .dc-sub{display:flex;align-items:center;gap:6px;margin-top:5px;font-size:13px;color:var(--text-sub)}
        .dc-sub .arr{color:var(--indigo);font-weight:700}
        .dc-sub .nt{font-weight:700;color:var(--indigo);background:var(--indigo-lt);padding:1px 8px;border-radius:4px;font-size:12px}
        .dc-sub .ft{font-weight:700;color:var(--emerald);background:var(--emerald-lt);padding:1px 8px;border-radius:4px;font-size:12px}

        /* ── TABS ── */
        .dc-tabs{display:flex;align-items:center;gap:4px;background:var(--surface);border:1px solid var(--border);border-radius:var(--r);padding:4px;box-shadow:var(--sh-sm)}
        .dc-tab{font-size:12.5px;font-weight:600;padding:7px 18px;border-radius:7px;border:1px solid transparent;color:var(--text-sub);text-decoration:none;transition:all .16s;white-space:nowrap}
        .dc-tab:hover{background:var(--surface-3);color:var(--text)}
        .dc-tab.active{background:var(--indigo);color:#fff;box-shadow:0 2px 8px rgba(79,70,229,.28)}

        /* ── ALERTS ── */
        .dc-alert{display:flex;align-items:flex-start;gap:10px;padding:12px 16px;border-radius:var(--r);font-size:13.5px;margin-bottom:14px;border:1px solid;font-weight:500}
        .dc-alert-ok{background:var(--emerald-lt);border-color:var(--emerald-bd);color:var(--emerald)}
        .dc-alert-err{background:var(--rose-lt);border-color:var(--rose-bd);color:var(--rose)}
        .dc-alert ul{list-style:none;padding:0}

        /* ── STATS ── */
        .dc-stats{display:flex;gap:12px;flex-wrap:wrap;margin-bottom:20px}
        .dc-stat{background:var(--surface);border:1px solid var(--border);border-radius:var(--r);padding:14px 18px;box-shadow:var(--sh-sm);flex:1;min-width:130px;display:flex;align-items:center;gap:12px}
        .dc-sico{width:38px;height:38px;border-radius:9px;display:flex;align-items:center;justify-content:center;font-size:17px;flex-shrink:0}
        .ic-ind{background:var(--indigo-lt)} .ic-em{background:var(--emerald-lt)} .ic-am{background:var(--amber-lt)} .ic-vi{background:var(--violet-lt)}
        .dc-slbl{font-size:11px;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.7px}
        .dc-sval{font-size:20px;font-weight:800;color:var(--text);line-height:1;margin-top:2px;font-family:'JetBrains Mono',monospace}
        .dc-sval.em{color:var(--emerald);font-size:15px}

        /* ── CARD ── */
        .dc-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r-xl);box-shadow:var(--sh);overflow:hidden}
        .dc-chead{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;padding:16px 22px;border-bottom:1px solid var(--border-s);background:var(--surface-2)}
        .dc-cbody{padding:20px 22px}

        /* ── SEARCH ── */
        .dc-sf{display:flex;gap:8px;flex-wrap:wrap;align-items:center}
        .dc-sw{position:relative}
        .dc-sw svg{position:absolute;left:11px;top:50%;transform:translateY(-50%);color:var(--text-muted);pointer-events:none}
        .dc-si{background:var(--surface);border:1px solid var(--border);border-radius:8px;color:var(--text);font-family:'Plus Jakarta Sans',sans-serif;font-size:13px;padding:8px 12px 8px 34px;width:300px;outline:none;transition:border-color .16s,box-shadow .16s;box-shadow:var(--sh-sm)}
        .dc-si::placeholder{color:var(--text-muted)}
        .dc-si:focus{border-color:var(--indigo);box-shadow:0 0 0 3px rgba(79,70,229,.12)}

        /* ── BUTTONS ── */
        .btn-dc{font-family:'Plus Jakarta Sans',sans-serif;font-size:13px;font-weight:600;padding:8px 18px;border-radius:8px;border:1px solid transparent;cursor:pointer;transition:all .16s;text-decoration:none;display:inline-flex;align-items:center;gap:6px;white-space:nowrap;line-height:1}
        .btn-ind{background:var(--indigo);color:#fff;box-shadow:0 1px 4px rgba(79,70,229,.3)}
        .btn-ind:hover{background:#4338ca;color:#fff;box-shadow:0 4px 12px rgba(79,70,229,.3)}
        .btn-ghost{background:var(--surface);border-color:var(--border);color:var(--text-sub);box-shadow:var(--sh-sm)}
        .btn-ghost:hover{background:var(--surface-3);color:var(--text)}
        .btn-bulk{background:var(--emerald-lt);border-color:var(--emerald-bd);color:var(--emerald)}
        .btn-bulk:hover{background:var(--emerald);color:#fff;border-color:var(--emerald);box-shadow:0 4px 12px rgba(5,150,105,.25)}
        .btn-bulk:disabled{opacity:.45;cursor:not-allowed;box-shadow:none}

        .btn-sm-view{font-family:'Plus Jakarta Sans',sans-serif;font-size:12px;font-weight:600;padding:5px 12px;border-radius:7px;border:1px solid var(--sky-bd);background:var(--sky-lt);color:var(--sky);cursor:pointer;transition:all .15s;text-decoration:none;display:inline-flex;align-items:center;white-space:nowrap}
        .btn-sm-view:hover{background:var(--sky);color:#fff;border-color:var(--sky)}
        .btn-sm-next{font-family:'Plus Jakarta Sans',sans-serif;font-size:12px;font-weight:600;padding:5px 12px;border-radius:7px;border:1px solid var(--indigo-bd);background:var(--indigo-lt);color:var(--indigo);cursor:pointer;transition:all .15s;display:inline-flex;align-items:center;white-space:nowrap}
        .btn-sm-next:hover{background:var(--indigo);color:#fff;border-color:var(--indigo);box-shadow:0 3px 8px rgba(79,70,229,.25)}
        .btn-sm-final{font-family:'Plus Jakarta Sans',sans-serif;font-size:12px;font-weight:600;padding:5px 12px;border-radius:7px;border:1px solid var(--border);background:var(--surface-3);color:var(--text-muted);pointer-events:none;display:inline-flex;align-items:center}

        /* ── BULK BAR ── */
        .dc-bulk{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;padding:11px 16px;background:var(--indigo-lt);border:1px solid var(--indigo-bd);border-radius:var(--r);margin-bottom:16px}
        .dc-bulk-l{display:flex;align-items:center;gap:12px;flex-wrap:wrap}
        .dc-chk-lbl{display:flex;align-items:center;gap:8px;font-size:13px;font-weight:600;color:var(--indigo);cursor:pointer;user-select:none}
        input[type=checkbox].chk-main,input[type=checkbox].rowCheck{appearance:none;-webkit-appearance:none;width:16px;height:16px;border-radius:5px;border:1.5px solid var(--border);background:var(--surface);cursor:pointer;position:relative;transition:all .14s;flex-shrink:0;vertical-align:middle}
        input[type=checkbox].chk-main{border-color:var(--indigo-bd)}
        input[type=checkbox].chk-main:checked,input[type=checkbox].rowCheck:checked{background:var(--indigo);border-color:var(--indigo)}
        input[type=checkbox].chk-main:checked::after,input[type=checkbox].rowCheck:checked::after{content:'';position:absolute;left:4px;top:1.5px;width:5px;height:9px;border:2px solid #fff;border-top:none;border-left:none;transform:rotate(45deg)}
        .dc-prog{font-size:12px;font-weight:600;color:var(--indigo);background:#fff;padding:4px 12px;border-radius:20px;border:1px solid var(--indigo-bd);font-family:'JetBrains Mono',monospace;display:none}
        .dc-tpill{font-size:12px;font-weight:600;color:var(--text-sub);background:var(--surface);padding:4px 14px;border-radius:20px;border:1px solid var(--border);font-family:'JetBrains Mono',monospace}

        /* ── TABLE ── */
        .dc-twrap{border-radius:var(--r);overflow:hidden;border:1px solid var(--border)}
        table.dc-t{width:100%;border-collapse:collapse;font-size:13px}
        table.dc-t thead{background:var(--surface-2);border-bottom:2px solid var(--border)}
        table.dc-t thead th{font-size:11px;font-weight:700;letter-spacing:.7px;text-transform:uppercase;color:var(--text-muted);padding:11px 13px;white-space:nowrap;text-align:left;border-right:1px solid var(--border-s)}
        table.dc-t thead th:last-child{border-right:none}
        table.dc-t tbody tr{border-bottom:1px solid var(--border-s);transition:background .1s}
        table.dc-t tbody tr:last-child{border-bottom:none}
        table.dc-t tbody tr:hover{background:#f8f9ff}
        table.dc-t tbody td{padding:11px 13px;color:var(--text);vertical-align:middle;border-right:1px solid var(--border-s)}
        table.dc-t tbody td:last-child{border-right:none}

        /* table tfoot */
        table.dc-t tfoot tr{background:var(--surface-2);border-top:2px solid var(--border)}
        table.dc-t tfoot td{padding:10px 13px;font-family:'JetBrains Mono',monospace}

        /* cell styles */
        .c-id{font-family:'JetBrains Mono',monospace;font-size:11px;color:var(--text-muted)}
        .c-call{font-family:'JetBrains Mono',monospace;font-size:12.5px;font-weight:500;color:var(--indigo)}
        .c-oc{font-family:'JetBrains Mono',monospace;font-size:11px;font-weight:500;color:var(--amber);background:var(--amber-lt);padding:2px 7px;border-radius:4px;border:1px solid var(--amber-bd);display:inline-block;white-space:nowrap}
        .c-date{font-family:'JetBrains Mono',monospace;font-size:11.5px;color:var(--text-sub);white-space:nowrap}
        .c-name{font-weight:700;font-size:13px;color:var(--text)}
        .c-addr{font-size:11px;color:var(--text-muted);margin-top:2px;line-height:1.3}
        .c-ph{font-family:'JetBrains Mono',monospace;font-size:12px;color:var(--text)}
        .c-ph2{font-family:'JetBrains Mono',monospace;font-size:10.5px;color:var(--text-muted);margin-top:2px}
        .c-loc{font-size:12px;color:var(--text-sub);white-space:nowrap}
        .c-item{font-size:13px;color:var(--text);max-width:160px;line-height:1.3}
        .c-qty{font-family:'JetBrains Mono',monospace;font-size:14px;font-weight:700;color:var(--text);text-align:center}
        .c-price{font-family:'JetBrains Mono',monospace;font-size:12px;color:var(--text-sub);text-align:right;white-space:nowrap}
        .c-total{font-family:'JetBrains Mono',monospace;font-size:13px;font-weight:700;color:var(--emerald);white-space:nowrap;display:block;text-align:right}
        .c-total-calc{font-family:'JetBrains Mono',monospace;font-size:10px;color:var(--text-muted);margin-top:2px;white-space:nowrap;text-align:right}

        /* badges */
        .dc-badge{font-size:10.5px;font-weight:700;letter-spacing:.5px;text-transform:uppercase;padding:3px 9px;border-radius:20px;display:inline-flex;align-items:center;gap:5px;border:1px solid;white-space:nowrap}
        .dc-badge::before{content:'';width:5px;height:5px;border-radius:50%;background:currentColor;opacity:.7;flex-shrink:0}
        .badge-pending,.dc-badge-pending{background:var(--amber-lt);border-color:var(--amber-bd);color:var(--amber)}
        .badge-packing,.dc-badge-packing{background:var(--sky-lt);border-color:var(--sky-bd);color:var(--sky)}
        .badge-packed,.dc-badge-packed{background:var(--violet-lt);border-color:var(--violet-bd);color:var(--violet)}
        .badge-give-driver,.dc-badge-givedriver,.badge-success,.dc-badge-success{background:var(--emerald-lt);border-color:var(--emerald-bd);color:var(--emerald)}
        .dc-badge-final{background:var(--rose-lt);border-color:var(--rose-bd);color:var(--rose)}

        /* row states */
        tbody tr.row-ok td{background:#f0fdf4!important}
        tbody tr.row-fail td{background:#fff1f2!important}

        /* empty */
        .dc-empty{text-align:center;padding:56px 0}
        .dc-empty-ico{width:52px;height:52px;background:var(--surface-3);border-radius:13px;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;font-size:21px;border:1px solid var(--border)}
        .dc-empty-t{font-size:15px;font-weight:700;color:var(--text);margin-bottom:5px}
        .dc-empty-s{font-size:13px;color:var(--text-muted)}

        /* ── PAGINATION FIX ── */
        nav[role=navigation],nav[aria-label]{display:flex;align-items:center}
        nav svg,nav[aria-label] svg,.pagination svg{width:14px!important;height:14px!important;display:inline-block!important}
        .pagination{display:flex;gap:4px;flex-wrap:wrap;list-style:none;padding:0;margin:0;align-items:center}
        .page-item .page-link{background:var(--surface)!important;border:1px solid var(--border)!important;color:var(--text-sub)!important;border-radius:7px!important;font-family:'JetBrains Mono',monospace;font-size:12px;padding:5px 12px;transition:all .14s;box-shadow:var(--sh-sm);line-height:1.5;display:inline-flex!important;align-items:center!important;justify-content:center!important}
        .page-item.active .page-link,.page-item .page-link:hover{background:var(--indigo)!important;border-color:var(--indigo)!important;color:#fff!important;box-shadow:0 3px 8px rgba(79,70,229,.25)!important}
        nav[aria-label] span[aria-current=page]>span,nav[aria-label] a,nav[aria-label] button,nav[aria-label] span:not([aria-current])>span{background:var(--surface)!important;border:1px solid var(--border)!important;color:var(--text-sub)!important;border-radius:7px!important;font-family:'JetBrains Mono',monospace!important;font-size:12px!important;padding:5px 12px!important;transition:all .14s;box-shadow:var(--sh-sm);display:inline-flex!important;align-items:center!important;justify-content:center!important;min-width:34px;height:32px;text-decoration:none;line-height:1!important}
        nav[aria-label] a:hover{background:var(--indigo)!important;border-color:var(--indigo)!important;color:#fff!important;box-shadow:0 3px 8px rgba(79,70,229,.25)!important}
        nav[aria-label] span[aria-current=page]>span{background:var(--indigo)!important;border-color:var(--indigo)!important;color:#fff!important;box-shadow:0 3px 8px rgba(79,70,229,.25)!important}
        nav[aria-label] span:not([aria-current]):not([aria-label])>span{opacity:.4;cursor:not-allowed}
        nav[aria-label]>div:first-child{display:none}
        nav[aria-label] .flex{gap:4px;flex-wrap:wrap;align-items:center}
    </style>

    <div class="dc-page">
        <div class="dc-wrap">

            {{-- HEADER --}}
            <div class="dc-hdr">
                <div>
                    <div class="dc-crumb">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                        Employee Portal
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                        Delivery Calls
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                        <span style="color:var(--indigo);font-weight:700">{{ $status }}</span>
                    </div>
                    <div class="dc-title">
                        Delivery Calls <span class="dc-tbadge">{{ $status }}</span>
                    </div>
                    <div class="dc-sub">
                        Showing <strong style="color:var(--text)">{{ $status }}</strong> records
                        @if($nextStatus)
                            <span class="arr">→</span> Next: <span class="nt">{{ $nextStatus }}</span>
                        @else
                            <span class="ft">✓ Final Stage</span>
                        @endif
                    </div>
                </div>

                <div class="dc-tabs">
                    <a class="dc-tab {{ request()->routeIs('employee.delivery_calls.pending')    ? 'active':'' }}" href="{{ route('employee.delivery_calls.pending') }}">Pending</a>
                    <a class="dc-tab {{ request()->routeIs('employee.delivery_calls.packing')    ? 'active':'' }}" href="{{ route('employee.delivery_calls.packing') }}">Packing</a>
                    <a class="dc-tab {{ request()->routeIs('employee.delivery_calls.packed')     ? 'active':'' }}" href="{{ route('employee.delivery_calls.packed') }}">Packed</a>
                    <a class="dc-tab {{ request()->routeIs('employee.delivery_calls.givedriver') ? 'active':'' }}" href="{{ route('employee.delivery_calls.givedriver') }}">Give Driver</a>
                </div>
            </div>

            {{-- ALERTS --}}
            @if(session('success'))<div class="dc-alert dc-alert-ok">✓ &nbsp;{{ session('success') }}</div>@endif
            @if(session('error'))<div class="dc-alert dc-alert-err">✕ &nbsp;{{ session('error') }}</div>@endif
            @if($errors->any())
                <div class="dc-alert dc-alert-err"><ul>@foreach($errors->all() as $e)<li>✕ {{ $e }}</li>@endforeach</ul></div>
            @endif

            {{-- PAGE SUBTOTAL CALCULATION --}}
            @php
                $pageSubtotal = $calls->sum(fn($c) => $c->qty * $c->price);
            @endphp

            {{-- STATS --}}
            <div class="dc-stats">
                <div class="dc-stat">
                    <div class="dc-sico ic-ind">📦</div>
                    <div><div class="dc-slbl">Total Records</div><div class="dc-sval">{{ $calls->total() }}</div></div>
                </div>
                <div class="dc-stat">
                    <div class="dc-sico ic-am">📄</div>
                    <div><div class="dc-slbl">This Page</div><div class="dc-sval">{{ $calls->count() }}</div></div>
                </div>
                <div class="dc-stat">
                    <div class="dc-sico ic-vi">🗂</div>
                    <div><div class="dc-slbl">Current Stage</div><div class="dc-sval" style="font-size:14px;margin-top:3px">{{ $status }}</div></div>
                </div>
                <div class="dc-stat">
                    <div class="dc-sico ic-em">💰</div>
                    <div><div class="dc-slbl">Page Subtotal</div><div class="dc-sval em">Rs. {{ number_format($pageSubtotal, 2) }}</div></div>
                </div>
            </div>

            {{-- CARD --}}
            <div class="dc-card">

                <div class="dc-chead">
                    <form class="dc-sf" method="GET" action="{{ url()->current() }}">
                        <div class="dc-sw">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                            <input type="text" name="q" class="dc-si" value="{{ request('q') }}" placeholder="Search order code, customer, phone, item…">
                        </div>
                        <button class="btn-dc btn-ind" type="submit">Search</button>
                        <a class="btn-dc btn-ghost" href="{{ url()->current() }}">Reset</a>
                    </form>
                    <div style="font-size:12px;color:var(--text-muted);font-weight:500">
                        Page {{ $calls->currentPage() }} of {{ $calls->lastPage() }}
                    </div>
                </div>

                <div class="dc-cbody">

                    {{-- BULK BAR --}}
                    <div class="dc-bulk">
                        <div class="dc-bulk-l">
                            <label class="dc-chk-lbl">
                                <input type="checkbox" class="chk-main" id="selectAll"> Select All
                            </label>
                            @if($nextStatus)
                                <button type="button" id="bulkUpdateBtn" class="btn-dc btn-bulk">
                                    Bulk Update → {{ $nextStatus }}
                                </button>
                                <span class="dc-prog" id="bulkProgress"></span>
                            @else
                                <span class="btn-sm-final">Final Status — No Next Stage</span>
                            @endif
                        </div>
                        <span class="dc-tpill">{{ $calls->total() }} total</span>
                    </div>

                    {{-- TABLE --}}
                    <div class="dc-twrap">
                        <table class="dc-t">
                            <thead>
                            <tr>
                                <th style="width:44px;text-align:center"></th>
                                <th>ID</th>
                                <th>Call No</th>
                                <th>Order Code</th>
                                <th>Date</th>
                                <th>Customer</th>
                                <th>Phone</th>
                                <th>City / District</th>
                                <th>Item</th>
                                <th style="text-align:center">Qty</th>
                                <th style="text-align:right">Unit Price</th>
                                <th style="text-align:right">Total</th>
                                <th>Status</th>
                                <th style="width:185px">Actions</th>
                            </tr>
                            </thead>

                            <tbody>
                            @forelse($calls as $c)
                                @php
                                    $lineTotal = $c->qty * $c->price;
                                @endphp
                                <tr id="row_tr_{{ $c->id }}">
                                    <td style="text-align:center">
                                        <input type="checkbox" class="rowCheck" value="{{ $c->id }}" id="row_{{ $c->id }}">
                                    </td>
                                    <td><span class="c-id">{{ $c->id }}</span></td>
                                    <td><span class="c-call">{{ $c->call_number }}</span></td>
                                    <td>
                                        @if($c->order_code)
                                            <span class="c-oc">{{ $c->order_code }}</span>
                                        @else
                                            <span style="color:var(--text-dim)">—</span>
                                        @endif
                                    </td>
                                    <td><span class="c-date">{{ optional($c->date)->format('Y-m-d') }}</span></td>
                                    <td>
                                        <div class="c-name">{{ $c->customer_name }}</div>
                                        <div class="c-addr">{{ $c->customer_address }}</div>
                                    </td>
                                    <td>
                                        <div class="c-ph">{{ $c->customer_phone_01 }}</div>
                                        @if($c->customer_phone_02)<div class="c-ph2">{{ $c->customer_phone_02 }}</div>@endif
                                    </td>
                                    <td><span class="c-loc">{{ $c->city }} / {{ $c->district }}</span></td>
                                    <td><span class="c-item">{{ $c->item_name }}</span></td>
                                    <td><span class="c-qty">{{ $c->qty }}</span></td>
                                    <td>
                                        <span class="c-price">Rs. {{ number_format($c->price, 2) }}</span>
                                    </td>
                                    <td>
                                        <span class="c-total">Rs. {{ number_format($lineTotal, 2) }}</span>
                                        @if($c->qty > 1)
                                            <span class="c-total-calc">{{ $c->qty }} × {{ number_format($c->price, 2) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                <span class="dc-badge {{ $c->status_badge_class }}" id="badge_{{ $c->id }}">
                                    {{ $c->status }}
                                </span>
                                    </td>
                                    <td>
                                        <div style="display:flex;gap:6px;align-items:center">
                                            <a class="btn-sm-view" href="{{ route('employee.delivery_calls.view', $c->id) }}">View</a>
                                            @if($nextStatus)
                                                <form method="POST"
                                                      action="{{ route('employee.delivery_calls.status.update', $c->id) }}"
                                                      onsubmit="return confirm('Update to {{ $nextStatus }}?')"
                                                      style="display:contents">
                                                    @csrf @method('PUT')
                                                    <button class="btn-sm-next" type="submit">→ {{ $nextStatus }}</button>
                                                </form>
                                            @else
                                                <span class="btn-sm-final">Final</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="14">
                                        <div class="dc-empty">
                                            <div class="dc-empty-ico">📭</div>
                                            <div class="dc-empty-t">No records found</div>
                                            <div class="dc-empty-s">No {{ $status }} records match your search.</div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>

                            @if($calls->count())
                                <tfoot>
                                <tr>
                                    <td colspan="11" style="text-align:right;padding:11px 13px">
                                <span style="font-size:11px;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.7px">
                                    Page Subtotal
                                </span>
                                    </td>
                                    <td style="text-align:right;padding:11px 13px">
                                <span style="font-size:14px;font-weight:800;color:var(--emerald)">
                                    Rs. {{ number_format($pageSubtotal, 2) }}
                                </span>
                                    </td>
                                    <td colspan="2" style="padding:11px 13px"></td>
                                </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>

                    {{-- PAGINATION --}}
                    <div style="margin-top:18px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px">
                        <div style="font-size:12.5px;color:var(--text-muted);font-weight:500">
                            @if($calls->total())
                                Showing {{ $calls->firstItem() }}–{{ $calls->lastItem() }} of {{ $calls->total() }} results
                            @endif
                        </div>
                        {{ $calls->links() }}
                    </div>

                </div>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const selectAll  = document.getElementById('selectAll');
            const bulkBtn    = document.getElementById('bulkUpdateBtn');
            const progressEl = document.getElementById('bulkProgress');
            const csrf       = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const nextStatus = @json($nextStatus);

            const rowChecks = () => Array.from(document.querySelectorAll('.rowCheck'));

            if (selectAll) {
                selectAll.addEventListener('change', () => rowChecks().forEach(ch => ch.checked = selectAll.checked));
            }

            rowChecks().forEach(ch => {
                ch.addEventListener('change', () => {
                    const all = rowChecks();
                    if (selectAll) selectAll.checked = all.length > 0 && all.every(x => x.checked);
                });
            });

            async function updateOne(id) {
                const res = await fetch(`/employee/delivery-calls/${id}/status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                        'X-CSRF-TOKEN': csrf,
                        'Accept': 'application/json'
                    },
                    body: new URLSearchParams({ _method: 'PUT' })
                });
                if (res.headers.get('content-type')?.includes('application/json')) {
                    const data = await res.json();
                    if (!res.ok) throw new Error(data?.message || 'Failed');
                    return data;
                }
                if (!res.ok) throw new Error('HTTP ' + res.status);
                return true;
            }

            async function bulkRun(ids) {
                let ok = 0, fail = 0;
                if (progressEl) { progressEl.style.display = 'inline'; progressEl.textContent = `0/${ids.length}`; }
                for (let i = 0; i < ids.length; i++) {
                    const id = ids[i];
                    try {
                        await updateOne(id);
                        ok++;
                        document.getElementById('row_tr_' + id)?.classList.add('row-ok');
                        const badge = document.getElementById('badge_' + id);
                        if (badge && nextStatus) badge.textContent = nextStatus;
                    } catch {
                        fail++;
                        document.getElementById('row_tr_' + id)?.classList.add('row-fail');
                    }
                    if (progressEl) progressEl.textContent = `${i+1}/${ids.length} · ✓${ok} · ✕${fail}`;
                }
                alert(`Done!\n✓ Success: ${ok}\n✕ Failed: ${fail}`);
            }

            if (bulkBtn) {
                bulkBtn.addEventListener('click', async () => {
                    const selected = rowChecks().filter(ch => ch.checked).map(ch => ch.value);
                    if (!selected.length) return alert('Please select at least one row.');
                    if (!confirm(`Update ${selected.length} row(s) → ${nextStatus}?`)) return;
                    bulkBtn.disabled = true;
                    try { await bulkRun(selected); window.location.reload(); }
                    finally { bulkBtn.disabled = false; }
                });
            }
        });
    </script>
@endsection
