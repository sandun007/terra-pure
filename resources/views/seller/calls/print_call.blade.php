<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Waybill / Call Print - {{ $call->call_number ?? $call->order_code ?? 'N/A' }}</title>

    <style>
        /* ===== PRINT PAGE SETUP ===== */
        @page { size: A4; margin: 12mm; }

        :root{
            --ink:#0f172a;
            --muted:#475569;
            --line:#e2e8f0;
            --soft:#f8fafc;
            --brand:#1d4ed8;
            --brand2:#0ea5e9;
            --success:#16a34a;
            --warn:#f59e0b;
            --danger:#ef4444;
        }

        *{ box-sizing:border-box; }
        body{
            margin:0;
            padding:0;
            font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Arial, "Noto Sans", "Helvetica Neue", sans-serif;
            color:var(--ink);
            background:#fff;
        }

        /* Outer frame (looks like courier waybill blocks) */
        .sheet{
            width: 100%;
            border: 1px solid var(--line);
            border-radius: 14px;
            overflow: hidden;
        }

        /* Top header like courier PDF */
        .topbar{
            display:flex;
            align-items:flex-start;
            justify-content:space-between;
            gap: 14px;
            padding: 14px 16px;
            background: linear-gradient(135deg, rgba(29,78,216,.08), rgba(14,165,233,.08));
            border-bottom: 1px solid var(--line);
        }

        .brand{
            display:flex;
            gap: 12px;
            align-items:center;
        }

        .logo{
            width:42px;
            height:42px;
            border-radius:12px;
            background: linear-gradient(135deg, var(--brand), var(--brand2));
            display:flex;
            align-items:center;
            justify-content:center;
            color:#fff;
            font-weight:900;
            letter-spacing:.5px;
            user-select:none;
        }

        .brand h1{
            font-size: 14px;
            margin:0;
            line-height:1.2;
            letter-spacing:.2px;
        }
        .brand p{
            margin:3px 0 0;
            font-size: 12px;
            color: var(--muted);
        }

        .meta{
            text-align:right;
            min-width: 240px;
        }
        .meta .waybill{
            font-size: 12px;
            color: var(--muted);
            margin: 0 0 4px;
        }
        .meta .waybill strong{
            color: var(--ink);
            font-size: 13px;
        }
        .meta .date{
            font-size: 12px;
            color: var(--muted);
        }

        /* Body grid (Merchant / Customer / Order like PDF blocks) */
        .body{
            padding: 14px 16px 16px;
        }

        .grid2{
            display:grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 12px;
        }

        .grid1{
            display:grid;
            grid-template-columns: 1fr;
            gap: 12px;
        }

        .card{
            border: 1px solid var(--line);
            border-radius: 12px;
            overflow:hidden;
            background:#fff;
        }

        .card-h{
            padding: 10px 12px;
            font-weight: 800;
            font-size: 12px;
            letter-spacing:.3px;
            color: var(--brand);
            background: #fff;
            border-bottom: 1px solid var(--line);
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap: 8px;
        }

        .card-b{
            padding: 10px 12px 12px;
        }

        .rows{
            display:grid;
            gap: 8px;
        }

        .row{
            display:grid;
            grid-template-columns: 120px 1fr;
            gap: 10px;
            align-items:start;
        }

        .k{
            font-size: 11px;
            color: var(--muted);
            font-weight: 700;
        }

        .v{
            font-size: 12px;
            color: var(--ink);
            font-weight: 600;
            word-break: break-word;
        }

        .badge{
            display:inline-flex;
            align-items:center;
            gap: 6px;
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 800;
            border: 1px solid var(--line);
            background: var(--soft);
            color: var(--muted);
        }
        .b-pending{ background: rgba(245,158,11,.12); color:#92400e; border-color: rgba(245,158,11,.25); }
        .b-conform{ background: rgba(22,163,74,.12); color:#166534; border-color: rgba(22,163,74,.25); }
        .b-reject{ background: rgba(239,68,68,.12); color:#991b1b; border-color: rgba(239,68,68,.25); }
        .b-not-answer{ background: rgba(99,102,241,.12); color:#3730a3; border-color: rgba(99,102,241,.25); }

        .money{
            display:flex;
            justify-content:space-between;
            gap: 12px;
            padding: 12px;
            border-top: 1px dashed var(--line);
            background: #fff;
        }

        .money .left{
            font-size: 12px;
            color: var(--muted);
            font-weight: 700;
            line-height:1.5;
        }

        .money .right{
            text-align:right;
        }

        .money .total{
            font-size: 16px;
            font-weight: 900;
            color: var(--brand);
        }

        .barcode{
            margin-top: 8px;
            height: 52px;
            border-radius: 10px;
            border: 1px dashed var(--line);
            background:
                repeating-linear-gradient(
                    90deg,
                    rgba(15,23,42,.75),
                    rgba(15,23,42,.75) 2px,
                    rgba(255,255,255,0) 2px,
                    rgba(255,255,255,0) 6px
                );
            opacity: .25;
        }

        .footer{
            padding: 10px 16px;
            border-top: 1px solid var(--line);
            background: var(--soft);
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap: 12px;
            font-size: 11px;
            color: var(--muted);
        }

        .actions{
            padding: 14px 0 0;
            display:flex;
            gap: 10px;
            justify-content:center;
        }

        .btn{
            border: none;
            border-radius: 10px;
            padding: 10px 16px;
            font-weight: 800;
            cursor:pointer;
            font-size: 14px;
        }
        .btn-primary{ background: var(--brand); color:#fff; }
        .btn-secondary{ background: #0f172a; color:#fff; }
        .btn-primary:hover{ filter: brightness(.95); }
        .btn-secondary:hover{ filter: brightness(.95); }

        /* Responsive */
        @media (max-width: 700px){
            .grid2{ grid-template-columns: 1fr; }
            .meta{ min-width: auto; }
            .row{ grid-template-columns: 110px 1fr; }
        }

        /* Print rules */
        @media print{
            body{ -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .actions{ display:none !important; }
            .sheet{ border-radius: 0; }
        }
    </style>
</head>
<body>

@php
    $callNo   = $call->call_number ?? $call->order_code ?? 'N/A';
    $orderNo  = $call->order_code ?? $call->call_number ?? 'N/A';
    $orderDt  = !empty($call->date) ? \Carbon\Carbon::parse($call->date)->format('d/m/Y H:i') : null;

    $qty      = (float)($call->qty ?? 0);
    $price    = (float)($call->price ?? 0);
    $subTotal = $qty * $price;

    // COD / Total like PDF "Total COD"
    $total    = (float)($call->total ?? $subTotal);

    $stRaw = strtolower(str_replace([' ', '-'], '_', (string)($call->status ?? 'other')));
    $statusBadge = match($stRaw) {
        'pending'     => 'b-pending',
        'conform', 'confirmed' => 'b-conform',
        'reject', 'rejected'   => 'b-reject',
        'not_answer', 'no_answer' => 'b-not-answer',
        default => ''
    };
@endphp

<div class="sheet">
    <!-- HEADER (Waybill ID + Courier name style) -->
    <div class="topbar">
        <div class="brand">
            <div class="logo">RX</div>
            <div>
                <h1>{{ config('app.name') }} — Call / Waybill</h1>
                <p>Merchant + Customer + Order Details</p>
            </div>
        </div>

        <div class="meta">
            <p class="waybill">
                Waybill / Call ID :
                <strong>{{ $callNo }}</strong>
            </p>
            <div class="date">
                Order Date :
                <strong style="color:var(--ink)">{{ $orderDt ?? '-' }}</strong>
            </div>

            <div class="barcode" title="Barcode placeholder"></div>
        </div>
    </div>

    <div class="body">
        <!-- Merchant + Customer like PDF blocks :contentReference[oaicite:2]{index=2} -->
        <div class="grid2">
            <div class="card">
                <div class="card-h">
                    <span>Merchant Details</span>
                    <span class="badge">Seller</span>
                </div>
                <div class="card-b">
                    <div class="rows">
                        <div class="row">
                            <div class="k">Name</div>
                            <div class="v">{{ $call->seller_name ?? (auth('seller')->check() ? auth('seller')->user()->name : '-') }}</div>
                        </div>
                        <div class="row">
                            <div class="k">Telephone</div>
                            <div class="v">{{ $call->seller_phone ?? '-' }}</div>
                        </div>
                        <div class="row">
                            <div class="k">Platform</div>
                            <div class="v">{{ ucfirst($call->platform ?? 'N/A') }}</div>
                        </div>
                        <div class="row">
                            <div class="k">Campaign</div>
                            <div class="v">{{ $call->campaign_name ?? '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-h">
                    <span>Customer Details</span>
                    <span class="badge">Receiver</span>
                </div>
                <div class="card-b">
                    <div class="rows">
                        <div class="row">
                            <div class="k">Name</div>
                            <div class="v">{{ $call->customer_name ?? '-' }}</div>
                        </div>
                        <div class="row">
                            <div class="k">Telephone</div>
                            <div class="v">
                                {{ $call->customer_phone_01 ?? '-' }}
                                @if(!empty($call->customer_phone_02))
                                    <span style="color:var(--muted); font-weight:700;">,</span> {{ $call->customer_phone_02 }}
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="k">Address</div>
                            <div class="v">{{ $call->customer_address ?? '-' }}</div>
                        </div>
                        <div class="row">
                            <div class="k">City</div>
                            <div class="v">{{ $call->city ?? '-' }}</div>
                        </div>
                        <div class="row">
                            <div class="k">Postal / Zip</div>
                            <div class="v">{{ $call->postal_code ?? '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Details like PDF :contentReference[oaicite:3]{index=3} -->
        <div class="grid1">
            <div class="card">
                <div class="card-h">
                    <span>Order Details</span>
                    <span class="badge {{ $statusBadge }}">{{ $call->status ?? 'N/A' }}</span>
                </div>
                <div class="card-b">
                    <div class="grid2">
                        <div class="rows">
                            <div class="row">
                                <div class="k">Order Number</div>
                                <div class="v">{{ $orderNo }}</div>
                            </div>
                            <div class="row">
                                <div class="k">Item Code</div>
                                <div class="v">{{ $call->item_code ?? '-' }}</div>
                            </div>
                            <div class="row">
                                <div class="k">Item Name</div>
                                <div class="v">{{ $call->item_name ?? '-' }}</div>
                            </div>
                            <div class="row">
                                <div class="k">Weight</div>
                                <div class="v">{{ $call->weight ?? '1' }}</div>
                            </div>
                            <div class="row">
                                <div class="k">Lead Status</div>
                                <div class="v">{{ $call->lead_status ?? '-' }}</div>
                            </div>
                        </div>

                        <div class="rows">
                            <div class="row">
                                <div class="k">Quantity</div>
                                <div class="v">{{ $qty }}</div>
                            </div>
                            <div class="row">
                                <div class="k">Price (Unit)</div>
                                <div class="v">Rs. {{ number_format($price, 2) }}</div>
                            </div>
                            <div class="row">
                                <div class="k">Sub Total</div>
                                <div class="v">Rs. {{ number_format($subTotal, 2) }}</div>
                            </div>
                            <div class="row">
                                <div class="k">Status Date</div>
                                <div class="v">
                                    {{ !empty($call->status_date) ? \Carbon\Carbon::parse($call->status_date)->format('d/m/Y h:i A') : '-' }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="k">Description</div>
                                <div class="v">{{ $call->description ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </div>

                    @if(!empty($call->remark))
                        <div style="margin-top:10px; padding:10px; border:1px dashed var(--line); border-radius:12px; background:var(--soft);">
                            <div class="k" style="margin-bottom:4px;">Remark</div>
                            <div class="v" style="font-weight:700;">{{ $call->remark }}</div>
                        </div>
                    @endif
                </div>

                <!-- Total COD section like PDF :contentReference[oaicite:4]{index=4} -->
                <div class="money">
                    <div class="left">
                        <div>Courier / Waybill Layout</div>
                        <div>System generated document</div>
                    </div>
                    <div class="right">
                        <div style="font-size:12px; color:var(--muted); font-weight:800;">Total COD / Total Amount</div>
                        <div class="total">Rs. {{ number_format($total, 2) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer like courier PDF bottom line -->
    <div class="footer">
        <div>Generated on: {{ now()->format('d/m/Y h:i A') }}</div>
        <div>{{ config('app.name') }} — Print Copy</div>
    </div>
</div>

<div class="actions">
    <button class="btn btn-primary" onclick="window.print()">Print</button>
    <button class="btn btn-secondary" onclick="window.close()">Close</button>
</div>

</body>
</html>
