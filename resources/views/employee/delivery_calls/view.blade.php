@extends('employee.layout.app')

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

            --slate:      #64748b;
            --slate-lt:   #f8fafc;

            --sh-sm: 0 1px 3px rgba(15,23,42,.06),0 1px 2px rgba(15,23,42,.04);
            --sh:    0 4px 12px rgba(15,23,42,.08),0 1px 3px rgba(15,23,42,.05);
            --sh-md: 0 8px 24px rgba(15,23,42,.1),0 2px 6px rgba(15,23,42,.06);
            --r:     10px;
            --r-lg:  14px;
            --r-xl:  18px;
        }

        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}

        .vw-page{background:var(--bg);font-family:'Plus Jakarta Sans',sans-serif;min-height:100vh;padding:28px 24px 56px}
        .vw-wrap{max-width:1100px;animation:up .35s cubic-bezier(.16,1,.3,1) both}
        @keyframes up{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}

        /* ── TOP BAR ── */
        .vw-topbar{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:24px}
        .vw-crumb{display:flex;align-items:center;gap:5px;font-size:12px;color:var(--text-muted);font-weight:500}
        .vw-crumb svg{width:11px;height:11px;flex-shrink:0}
        .vw-crumb a{color:var(--text-muted);text-decoration:none}
        .vw-crumb a:hover{color:var(--indigo)}
        .vw-title{font-size:20px;font-weight:800;color:var(--text);letter-spacing:-.4px;margin-top:5px;display:flex;align-items:center;gap:10px}

        .btn-back{font-family:'Plus Jakarta Sans',sans-serif;font-size:13px;font-weight:600;padding:8px 18px;border-radius:8px;border:1px solid var(--border);background:var(--surface);color:var(--text-sub);text-decoration:none;display:inline-flex;align-items:center;gap:6px;transition:all .15s;box-shadow:var(--sh-sm)}
        .btn-back:hover{background:var(--surface-3);color:var(--text)}
        .btn-status{font-family:'Plus Jakarta Sans',sans-serif;font-size:13px;font-weight:600;padding:8px 18px;border-radius:8px;border:1px solid var(--indigo-bd);background:var(--indigo-lt);color:var(--indigo);cursor:pointer;transition:all .15s;display:inline-flex;align-items:center;gap:6px}
        .btn-status:hover{background:var(--indigo);color:#fff;border-color:var(--indigo);box-shadow:0 4px 12px rgba(79,70,229,.25)}

        /* ── CARD ── */
        .vw-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r-xl);box-shadow:var(--sh);overflow:hidden;margin-bottom:16px}
        .vw-card-head{padding:16px 22px;border-bottom:1px solid var(--border-s);background:var(--surface-2);display:flex;align-items:center;gap:10px}
        .vw-card-head-ico{width:32px;height:32px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:14px;flex-shrink:0}
        .ic-ind{background:var(--indigo-lt)} .ic-em{background:var(--emerald-lt)} .ic-am{background:var(--amber-lt)} .ic-sky{background:var(--sky-lt)} .ic-vi{background:var(--violet-lt)} .ic-sl{background:var(--slate-lt)}
        .vw-card-title{font-size:13px;font-weight:700;color:var(--text);letter-spacing:.1px}
        .vw-card-body{padding:20px 22px}

        /* ── FIELD GRID ── */
        .vw-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:16px}
        .vw-grid-2{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px}

        .vw-field{}
        .vw-flbl{font-size:11px;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.7px;margin-bottom:4px}
        .vw-fval{font-size:13.5px;color:var(--text);font-weight:500}
        .vw-fval.mono{font-family:'JetBrains Mono',monospace;font-size:13px}
        .vw-fval.muted{color:var(--text-muted);font-style:italic}
        .vw-fval.em{color:var(--emerald);font-weight:700;font-family:'JetBrains Mono',monospace;font-size:15px}
        .vw-fval.ind{color:var(--indigo);font-weight:700;font-family:'JetBrains Mono',monospace}
        .vw-fval.am{color:var(--amber);font-weight:600;font-family:'JetBrains Mono',monospace}

        /* ── PRICE BREAKDOWN BOX ── */
        .price-box{background:var(--surface-2);border:1px solid var(--border);border-radius:var(--r-lg);padding:18px 20px;margin-top:4px}
        .price-row{display:flex;align-items:center;justify-content:space-between;padding:7px 0;border-bottom:1px solid var(--border-s)}
        .price-row:last-child{border-bottom:none}
        .price-lbl{font-size:12.5px;color:var(--text-sub);font-weight:500}
        .price-val{font-family:'JetBrains Mono',monospace;font-size:13px;font-weight:600;color:var(--text)}
        .price-row.total-row{margin-top:4px;padding-top:12px;border-top:2px solid var(--border)}
        .price-row.total-row .price-lbl{font-size:13px;font-weight:700;color:var(--text)}
        .price-row.total-row .price-val{font-size:16px;font-weight:800;color:var(--emerald)}

        /* ── PRODUCT CARD ── */
        .prod-box{display:flex;gap:16px;align-items:flex-start;background:var(--surface-2);border:1px solid var(--border);border-radius:var(--r-lg);padding:16px 18px}
        .prod-img{width:72px;height:72px;border-radius:10px;object-fit:cover;border:1px solid var(--border);background:var(--surface-3);flex-shrink:0}
        .prod-img-ph{width:72px;height:72px;border-radius:10px;border:1px solid var(--border);background:var(--surface-3);flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:26px}
        .prod-name{font-size:14px;font-weight:700;color:var(--text);margin-bottom:3px}
        .prod-code{font-family:'JetBrains Mono',monospace;font-size:11px;color:var(--text-muted);margin-bottom:8px}
        .prod-pills{display:flex;gap:6px;flex-wrap:wrap}
        .prod-pill{font-size:11px;font-weight:600;padding:2px 8px;border-radius:4px;border:1px solid}

        /* ── BADGES ── */
        .dc-badge{font-size:11px;font-weight:700;letter-spacing:.5px;text-transform:uppercase;padding:4px 11px;border-radius:20px;display:inline-flex;align-items:center;gap:5px;border:1px solid;white-space:nowrap}
        .dc-badge::before{content:'';width:5px;height:5px;border-radius:50%;background:currentColor;opacity:.7;flex-shrink:0}
        .badge-pending,.dc-badge-pending{background:var(--amber-lt);border-color:var(--amber-bd);color:var(--amber)}
        .badge-packing,.dc-badge-packing{background:var(--sky-lt);border-color:var(--sky-bd);color:var(--sky)}
        .badge-packed,.dc-badge-packed{background:var(--violet-lt);border-color:var(--violet-bd);color:var(--violet)}
        .badge-give-driver,.dc-badge-givedriver,.badge-success,.dc-badge-success{background:var(--emerald-lt);border-color:var(--emerald-bd);color:var(--emerald)}

        /* ── DIVIDER ── */
        .vw-divider{height:1px;background:var(--border-s);margin:4px 0 16px}

        /* ── TIMELINE (status) ── */
        .status-track{display:flex;align-items:center;gap:0;flex-wrap:wrap;margin-top:4px}
        .st-step{display:flex;align-items:center;gap:0}
        .st-dot{width:28px;height:28px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;border:2px solid;flex-shrink:0;z-index:1}
        .st-dot.done{background:var(--indigo);border-color:var(--indigo);color:#fff}
        .st-dot.active{background:var(--emerald);border-color:var(--emerald);color:#fff;box-shadow:0 0 0 3px var(--emerald-bd)}
        .st-dot.pending{background:var(--surface);border-color:var(--border);color:var(--text-muted)}
        .st-label{font-size:10.5px;font-weight:600;color:var(--text-muted);margin-left:6px;white-space:nowrap}
        .st-label.done{color:var(--indigo)}
        .st-label.active{color:var(--emerald);font-weight:700}
        .st-line{width:28px;height:2px;background:var(--border);margin:0 2px;flex-shrink:0}
        .st-line.done{background:var(--indigo)}
    </style>

    <div class="vw-page">
        <div class="vw-wrap">

            {{-- TOP BAR --}}
            <div class="vw-topbar">
                <div>
                    <div class="vw-crumb">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                        <a href="#">Employee Portal</a>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                        <a href="{{ url()->previous() }}">Delivery Calls</a>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                        <span style="color:var(--indigo);font-weight:700">#{{ $call->id }}</span>
                    </div>
                    <div class="vw-title">
                        Delivery Call
                        <span style="font-family:'JetBrains Mono',monospace;font-size:14px;font-weight:500;color:var(--text-muted)">#{{ $call->id }}</span>
                    </div>
                </div>
                <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap">
                    <a href="{{ url()->previous() }}" class="btn-back">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
                        Back
                    </a>
                </div>
            </div>

            {{-- STATUS TRACKER --}}
            @php
                $flow   = ['Pending','packing','packed','givedriver'];
                $labels = ['Pending','Packing','Packed','Give Driver'];
                $curIdx = array_search($call->status, $flow);
            @endphp
            <div class="vw-card" style="margin-bottom:16px">
                <div class="vw-card-body" style="padding:16px 22px">
                    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
                        <div class="status-track">
                            @foreach($flow as $i => $s)
                                <div class="st-step">
                                    <div class="st-dot {{ $i < $curIdx ? 'done' : ($i == $curIdx ? 'active' : 'pending') }}">
                                        @if($i < $curIdx) ✓ @else {{ $i+1 }} @endif
                                    </div>
                                    <span class="st-label {{ $i < $curIdx ? 'done' : ($i == $curIdx ? 'active' : '') }}">
                                {{ $labels[$i] }}
                            </span>
                                </div>
                                @if(!$loop->last)
                                    <div class="st-line {{ $i < $curIdx ? 'done' : '' }}"></div>
                                @endif
                            @endforeach
                        </div>
                        <div style="display:flex;align-items:center;gap:10px">
                            <span class="dc-badge {{ $call->status_badge_class }}">{{ $call->status }}</span>
                            @if(optional($call->status_date))
                                <span style="font-size:12px;color:var(--text-muted);font-family:'JetBrains Mono',monospace">
                            {{ $call->status_date->format('Y-m-d H:i') }}
                        </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">

                {{-- ── ORDER INFO ── --}}
                <div class="vw-card">
                    <div class="vw-card-head">
                        <div class="vw-card-head-ico ic-ind">📋</div>
                        <span class="vw-card-title">Order Information</span>
                    </div>
                    <div class="vw-card-body">
                        <div class="vw-grid">
                            <div class="vw-field">
                                <div class="vw-flbl">Call Number</div>
                                <div class="vw-fval ind">{{ $call->call_number }}</div>
                            </div>
                            <div class="vw-field">
                                <div class="vw-flbl">Order Code</div>
                                @if($call->order_code)
                                    <div class="vw-fval am">{{ $call->order_code }}</div>
                                @else
                                    <div class="vw-fval muted">—</div>
                                @endif
                            </div>
                            <div class="vw-field">
                                <div class="vw-flbl">Date</div>
                                <div class="vw-fval mono">{{ optional($call->date)->format('Y-m-d') ?? '—' }}</div>
                            </div>
                            <div class="vw-field">
                                <div class="vw-flbl">Platform</div>
                                <div class="vw-fval">{{ $call->platform ?? '—' }}</div>
                            </div>
                            <div class="vw-field">
                                <div class="vw-flbl">Campaign</div>
                                <div class="vw-fval">{{ $call->campaign_name ?? '—' }}</div>
                            </div>
                            <div class="vw-field">
                                <div class="vw-flbl">Ad Name</div>
                                <div class="vw-fval">{{ $call->ad_name ?? '—' }}</div>
                            </div>
                            <div class="vw-field">
                                <div class="vw-flbl">Lead Status</div>
                                <div class="vw-fval">{{ $call->lead_status ?? '—' }}</div>
                            </div>
                            <div class="vw-field">
                                <div class="vw-flbl">Remark</div>
                                <div class="vw-fval">{{ $call->remark ?? '—' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── CUSTOMER INFO ── --}}
                <div class="vw-card">
                    <div class="vw-card-head">
                        <div class="vw-card-head-ico ic-sky">👤</div>
                        <span class="vw-card-title">Customer Information</span>
                    </div>
                    <div class="vw-card-body">
                        <div class="vw-grid">
                            <div class="vw-field" style="grid-column:1/-1">
                                <div class="vw-flbl">Full Name</div>
                                <div class="vw-fval" style="font-size:15px;font-weight:800">{{ $call->customer_name }}</div>
                            </div>
                            <div class="vw-field">
                                <div class="vw-flbl">Phone 1</div>
                                <div class="vw-fval mono">{{ $call->customer_phone_01 }}</div>
                            </div>
                            <div class="vw-field">
                                <div class="vw-flbl">Phone 2</div>
                                @if($call->customer_phone_02)
                                    <div class="vw-fval mono">{{ $call->customer_phone_02 }}</div>
                                @else
                                    <div class="vw-fval muted">—</div>
                                @endif
                            </div>
                            <div class="vw-field" style="grid-column:1/-1">
                                <div class="vw-flbl">Address</div>
                                <div class="vw-fval">{{ $call->customer_address ?? '—' }}</div>
                            </div>
                            <div class="vw-field">
                                <div class="vw-flbl">City</div>
                                <div class="vw-fval">{{ $call->city ?? '—' }}</div>
                            </div>
                            <div class="vw-field">
                                <div class="vw-flbl">District</div>
                                <div class="vw-fval">{{ $call->district ?? '—' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- ── PRODUCT & PRICING ── --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-top:16px">

                {{-- Product Details --}}
                <div class="vw-card">
                    <div class="vw-card-head">
                        <div class="vw-card-head-ico ic-am">📦</div>
                        <span class="vw-card-title">Product Details</span>
                    </div>
                    <div class="vw-card-body">

                        {{-- Product from relation --}}
                        @if($call->product)
                            @php $prod = $call->product; @endphp
                            <div class="prod-box" style="margin-bottom:16px">
                                @if($prod->image)
                                    <img src="{{ asset('storage/'.$prod->image) }}" alt="{{ $prod->name }}" class="prod-img">
                                @else
                                    <div class="prod-img-ph">🛍</div>
                                @endif
                                <div style="flex:1;min-width:0">
                                    <div class="prod-name">{{ $prod->name }}</div>
                                    <div class="prod-code">SKU: {{ $prod->sku ?? '—' }} &nbsp;|&nbsp; Code: {{ $prod->code }}</div>
                                    <div class="prod-pills">
                                <span class="prod-pill" style="background:var(--emerald-lt);border-color:var(--emerald-bd);color:var(--emerald)">
                                    {{ $prod->status }}
                                </span>
                                        <span class="prod-pill" style="background:var(--sky-lt);border-color:var(--sky-bd);color:var(--sky)">
                                    {{ $prod->type }}
                                </span>
                                        @if($prod->qty !== null)
                                            <span class="prod-pill" style="background:var(--amber-lt);border-color:var(--amber-bd);color:var(--amber)">
                                    Stock: {{ $prod->qty }}
                                </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="vw-grid-2">
                                <div class="vw-field">
                                    <div class="vw-flbl">Display Price</div>
                                    <div class="vw-fval mono">Rs. {{ number_format($prod->display_price, 2) }}</div>
                                </div>
                                <div class="vw-field">
                                    <div class="vw-flbl">Stock Price</div>
                                    <div class="vw-fval mono">Rs. {{ number_format($prod->stock_price, 2) }}</div>
                                </div>
                                <div class="vw-field">
                                    <div class="vw-flbl">Packaging Cost</div>
                                    <div class="vw-fval mono">Rs. {{ number_format($prod->packaging_cost, 2) }}</div>
                                </div>
                                <div class="vw-field">
                                    <div class="vw-flbl">Discount</div>
                                    <div class="vw-fval mono">{{ $prod->discount }}%</div>
                                </div>
                                @if($prod->profit_margin)
                                    <div class="vw-field">
                                        <div class="vw-flbl">Profit Margin</div>
                                        <div class="vw-fval mono">{{ $prod->profit_margin }}%</div>
                                    </div>
                                @endif
                                <div class="vw-field">
                                    <div class="vw-flbl">Total Sold</div>
                                    <div class="vw-fval mono">{{ $prod->total_sold }}</div>
                                </div>
                            </div>

                        @else
                            {{-- Fallback: show item fields from delivery call --}}
                            <div class="vw-grid">
                                <div class="vw-field" style="grid-column:1/-1">
                                    <div class="vw-flbl">Item Name</div>
                                    <div class="vw-fval" style="font-size:15px;font-weight:700">{{ $call->item_name }}</div>
                                </div>
                                @if($call->item_code)
                                    <div class="vw-field">
                                        <div class="vw-flbl">Item Code</div>
                                        <div class="vw-fval mono">{{ $call->item_code }}</div>
                                    </div>
                                @endif
                            </div>
                        @endif

                    </div>
                </div>

                {{-- Pricing Breakdown --}}
                <div class="vw-card">
                    <div class="vw-card-head">
                        <div class="vw-card-head-ico ic-em">💰</div>
                        <span class="vw-card-title">Pricing Breakdown</span>
                    </div>
                    <div class="vw-card-body">

                        @php
                            $unitPrice = $call->price;
                            $qty       = $call->qty;
                            $lineTotal = $qty * $unitPrice;
                        @endphp

                        <div class="price-box">
                            <div class="price-row">
                                <span class="price-lbl">Item</span>
                                <span class="price-val" style="font-weight:600;color:var(--text)">{{ $call->item_name }}</span>
                            </div>
                            <div class="price-row">
                                <span class="price-lbl">Unit Price</span>
                                <span class="price-val">Rs. {{ number_format($unitPrice, 2) }}</span>
                            </div>
                            <div class="price-row">
                                <span class="price-lbl">Quantity</span>
                                <span class="price-val">× {{ $qty }}</span>
                            </div>
                            <div class="price-row total-row">
                                <span class="price-lbl">Total</span>
                                <span class="price-val" style="font-size:18px;color:var(--emerald)">
                            Rs. {{ number_format($lineTotal, 2) }}
                        </span>
                            </div>
                        </div>

                        @if($call->total && abs($call->total - $lineTotal) > 0.01)
                            <div style="margin-top:10px;padding:10px 14px;background:var(--amber-lt);border:1px solid var(--amber-bd);border-radius:8px;font-size:12px;color:var(--amber);font-weight:600">
                                ⚠ Stored total (Rs. {{ number_format($call->total, 2) }}) differs from calculated total.
                                Calculated: Rs. {{ number_format($lineTotal, 2) }}
                            </div>
                        @endif

                        <div class="vw-divider" style="margin-top:16px"></div>

                        <div class="vw-grid-2" style="margin-top:8px">
                            @if($call->product)
                                <div class="vw-field">
                                    <div class="vw-flbl">Stock Price (each)</div>
                                    <div class="vw-fval mono">Rs. {{ number_format($call->product->stock_price, 2) }}</div>
                                </div>
                                <div class="vw-field">
                                    <div class="vw-flbl">Est. Gross Profit</div>
                                    @php $gp = ($unitPrice - $call->product->stock_price) * $qty; @endphp
                                    <div class="vw-fval mono" style="color:{{ $gp >= 0 ? 'var(--emerald)' : 'var(--rose)' }}">
                                        Rs. {{ number_format($gp, 2) }}
                                    </div>
                                </div>
                            @endif
                            <div class="vw-field">
                                <div class="vw-flbl">Item Code</div>
                                <div class="vw-fval mono">{{ $call->item_code ?? '—' }}</div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            {{-- ── CALL / RECORDING ── --}}
            @if($call->call_duration || $call->call_recording_url)
                <div class="vw-card" style="margin-top:16px">
                    <div class="vw-card-head">
                        <div class="vw-card-head-ico ic-vi">📞</div>
                        <span class="vw-card-title">Call Details</span>
                    </div>
                    <div class="vw-card-body">
                        <div class="vw-grid">
                            @if($call->call_duration)
                                <div class="vw-field">
                                    <div class="vw-flbl">Call Duration</div>
                                    <div class="vw-fval mono">{{ $call->call_duration }}</div>
                                </div>
                            @endif
                            @if($call->call_recording_url)
                                <div class="vw-field" style="grid-column:1/-1">
                                    <div class="vw-flbl">Recording</div>
                                    <div style="margin-top:6px">
                                        <audio controls style="width:100%;border-radius:8px;outline:none">
                                            <source src="{{ $call->call_recording_url }}">
                                            Your browser does not support audio.
                                        </audio>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            {{-- ── META ── --}}
            <div class="vw-card" style="margin-top:16px">
                <div class="vw-card-head">
                    <div class="vw-card-head-ico ic-sl">🕐</div>
                    <span class="vw-card-title">Record Metadata</span>
                </div>
                <div class="vw-card-body">
                    <div class="vw-grid">
                        <div class="vw-field">
                            <div class="vw-flbl">Created At</div>
                            <div class="vw-fval mono">{{ optional($call->created_at)->format('Y-m-d H:i:s') }}</div>
                        </div>
                        <div class="vw-field">
                            <div class="vw-flbl">Updated At</div>
                            <div class="vw-fval mono">{{ optional($call->updated_at)->format('Y-m-d H:i:s') }}</div>
                        </div>
                        <div class="vw-field">
                            <div class="vw-flbl">Download Status</div>
                            <div class="vw-fval">
                                @if($call->download_status)
                                    <span style="color:var(--emerald);font-weight:700">✓ Downloaded</span>
                                @else
                                    <span style="color:var(--text-muted)">Pending</span>
                                @endif
                            </div>
                        </div>
                        <div class="vw-field">
                            <div class="vw-flbl">Seller ID</div>
                            <div class="vw-fval mono">{{ $call->seller_id ?? '—' }}</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
