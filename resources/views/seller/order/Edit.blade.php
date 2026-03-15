@extends('seller.layout.app')

@push('title')
    Edit Order — {{ $order->order_number }}
@endpush

@push('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary:       #2563eb;
            --primary-light: #eff6ff;
            --primary-dark:  #1d4ed8;
            --success:       #10b981;
            --danger:        #ef4444;
            --warning:       #f59e0b;
            --info:          #06b6d4;
            --dark:          #0f172a;
            --text:          #1e293b;
            --muted:         #64748b;
            --border:        #e2e8f0;
            --surface:       #ffffff;
            --bg:            #f1f5f9;
        }

        *, *::before, *::after { box-sizing: border-box; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
        }

        /* ── Page wrapper ── */
        .ep-wrap {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1.75rem 1.5rem 5rem;
        }

        /* ── Breadcrumb ── */
        .ep-breadcrumb {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            color: var(--muted);
            margin-bottom: 1.25rem;
            flex-wrap: wrap;
        }
        .ep-breadcrumb a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }
        .ep-breadcrumb a:hover { text-decoration: underline; }
        .ep-breadcrumb .sep { color: #cbd5e1; }
        .ep-breadcrumb .current {
            font-family: 'JetBrains Mono', monospace;
            font-weight: 700;
            color: var(--primary);
            font-size: 12px;
            background: var(--primary-light);
            padding: 2px 10px;
            border-radius: 999px;
        }

        /* ── Hero Header ── */
        .ep-hero {
            background: linear-gradient(135deg, #0f2167 0%, #1a3faa 40%, #2563eb 70%, #0ea5e9 100%);
            border-radius: 20px;
            padding: 2rem 2.5rem;
            margin-bottom: 2rem;
            color: white;
            position: relative;
            overflow: hidden;
            box-shadow: 0 16px 48px rgba(37,99,235,.28);
        }
        .ep-hero::before {
            content: '';
            position: absolute;
            top: -70px; right: -50px;
            width: 250px; height: 250px;
            background: rgba(255,255,255,.06);
            border-radius: 50%;
        }
        .ep-hero::after {
            content: '';
            position: absolute;
            bottom: -100px; left: 25%;
            width: 360px; height: 360px;
            background: rgba(255,255,255,.04);
            border-radius: 50%;
        }
        .ep-hero-inner {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1.25rem;
        }
        .ep-hero-left {}
        .ep-hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
            font-weight: 500;
            background: rgba(255,255,255,.18);
            backdrop-filter: blur(4px);
            padding: 5px 14px;
            border-radius: 999px;
            margin-bottom: .75rem;
            letter-spacing: .3px;
            border: 1px solid rgba(255,255,255,.2);
        }
        .ep-hero h1 {
            font-size: 28px;
            font-weight: 800;
            margin: 0 0 .4rem;
            letter-spacing: -.4px;
            line-height: 1.15;
        }
        .ep-hero p {
            opacity: .72;
            font-size: 14px;
            margin: 0;
            font-weight: 400;
        }
        .ep-hero-right {
            display: flex;
            gap: 2rem;
            flex-wrap: wrap;
        }
        .ep-hero-stat { text-align: right; }
        .ep-hero-stat label {
            display: block;
            font-size: 10px;
            opacity: .55;
            text-transform: uppercase;
            letter-spacing: .7px;
            margin-bottom: 3px;
            font-weight: 600;
        }
        .ep-hero-stat .val {
            font-size: 22px;
            font-weight: 800;
            line-height: 1;
        }
        .ep-hero-stat .val.sm { font-size: 15px; font-weight: 700; }

        /* ── Flash Alerts ── */
        .ep-alert {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 1rem;
        }
        .ep-alert .material-icons-round { font-size: 18px; flex-shrink: 0; margin-top: 1px; }
        .ep-alert.success { background: #f0fdf4; border: 1.5px solid #86efac; color: #065f46; }
        .ep-alert.danger  { background: #fff1f2; border: 1.5px solid #fca5a5; color: #991b1b; }
        .ep-alert ul { margin: .4rem 0 0; padding-left: 1.25rem; }

        /* ── Grid ── */
        .ep-grid {
            display: grid;
            grid-template-columns: 1fr 340px;
            gap: 1.5rem;
            align-items: start;
        }

        /* ── Cards ── */
        .ep-card {
            background: white;
            border-radius: 16px;
            border: 1.5px solid var(--border);
            box-shadow: 0 2px 10px rgba(0,0,0,.05);
            overflow: hidden;
            margin-bottom: 1.5rem;
        }
        .ep-card:last-child { margin-bottom: 0; }

        .ep-card-head {
            padding: 1.1rem 1.5rem;
            border-bottom: 1.5px solid var(--border);
            display: flex;
            align-items: center;
            gap: 12px;
            background: #fafafa;
        }
        .ep-card-icon {
            width: 38px; height: 38px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
        }
        .ep-card-icon .material-icons-round { font-size: 20px; }
        .ep-card-icon.blue   { background: #eff6ff; color: #2563eb; }
        .ep-card-icon.green  { background: #f0fdf4; color: #16a34a; }
        .ep-card-icon.amber  { background: #fffbeb; color: #d97706; }
        .ep-card-icon.purple { background: #f5f3ff; color: #7c3aed; }
        .ep-card-icon.rose   { background: #fff1f2; color: #e11d48; }
        .ep-card-icon.cyan   { background: #ecfeff; color: #0891b2; }
        .ep-card-icon.slate  { background: #f8fafc; color: #475569; }

        .ep-card-head-text h6 {
            font-size: 14px;
            font-weight: 700;
            margin: 0 0 1px;
            color: var(--dark);
        }
        .ep-card-head-text span {
            font-size: 11px;
            color: var(--muted);
        }
        .ep-card-body { padding: 1.5rem; }

        /* ── Form Rows ── */
        .ep-row       { display: grid; grid-template-columns: 1fr 1fr;     gap: 1rem; margin-bottom: 1rem; }
        .ep-row.three { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem; margin-bottom: 1rem; }
        .ep-row.full  { display: grid; grid-template-columns: 1fr;         gap: 1rem; margin-bottom: 1rem; }
        .ep-row:last-child { margin-bottom: 0; }
        .ep-group { display: flex; flex-direction: column; }

        /* ── Labels ── */
        .ep-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .6px;
            color: var(--muted);
            margin-bottom: 6px;
        }
        .ep-label .req { color: var(--danger); margin-left: 2px; }

        /* ── Inputs ── */
        .ep-input, .ep-select, .ep-textarea {
            width: 100%;
            padding: 10px 13px;
            border: 2px solid var(--border);
            border-radius: 10px;
            font-size: 13.5px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--text);
            background: white;
            transition: border-color .18s, box-shadow .18s;
            outline: none;
            appearance: none;
        }
        .ep-input:focus, .ep-select:focus, .ep-textarea:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37,99,235,.11);
        }
        .ep-input::placeholder, .ep-textarea::placeholder { color: #cbd5e1; }
        .ep-input[readonly] { background: #f8fafc; color: var(--muted); cursor: default; }
        .ep-textarea { resize: vertical; min-height: 88px; line-height: 1.6; }
        .ep-input.mono, .ep-select.mono { font-family: 'JetBrains Mono', monospace; font-size: 13px; }

        /* Input group prefix */
        .ep-input-group { display: flex; }
        .ep-input-prefix {
            background: var(--bg);
            border: 2px solid var(--border);
            border-right: none;
            border-radius: 10px 0 0 10px;
            padding: 10px 12px;
            font-size: 12px;
            font-weight: 700;
            color: var(--muted);
            white-space: nowrap;
            display: flex; align-items: center;
        }
        .ep-input-group .ep-input { border-radius: 0 10px 10px 0; }

        /* Help text */
        .ep-help {
            font-size: 11px;
            color: var(--muted);
            margin-top: 4px;
            display: flex;
            align-items: center;
            gap: 3px;
        }
        .ep-help .material-icons-round { font-size: 12px; }

        /* ── Product Preview ── */
        .ep-product-preview {
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border: 2px solid var(--border);
            border-radius: 12px;
            padding: 1rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 1.25rem;
        }
        .ep-product-img {
            width: 62px; height: 62px;
            border-radius: 10px;
            object-fit: cover;
            border: 2px solid var(--border);
            flex-shrink: 0;
        }
        .ep-product-placeholder {
            width: 62px; height: 62px;
            border-radius: 10px;
            background: linear-gradient(135deg, #e2e8f0, #cbd5e1);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            color: #94a3b8;
        }
        .ep-product-info { flex: 1; min-width: 0; }
        .ep-product-name {
            font-weight: 700;
            font-size: 14px;
            color: var(--dark);
            margin-bottom: 4px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .ep-product-code {
            font-family: 'JetBrains Mono', monospace;
            font-size: 11px;
            color: var(--muted);
            background: white;
            padding: 2px 8px;
            border-radius: 5px;
            border: 1px solid var(--border);
            display: inline-block;
            margin-bottom: 4px;
        }
        .ep-product-price {
            font-size: 16px;
            font-weight: 800;
            color: var(--primary);
        }

        /* ── Status Pills ── */
        .ep-pills { display: flex; flex-wrap: wrap; gap: 8px; }
        .ep-pill {
            padding: 7px 15px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
            border: 2px solid transparent;
            cursor: pointer;
            transition: all .18s;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            user-select: none;
        }
        .ep-pill input[type=radio] { display: none; }
        .ep-pill:hover { transform: translateY(-1px); box-shadow: 0 4px 10px rgba(0,0,0,.1); }
        .ep-pill.s-pending      { background: #fef3c7; color: #92400e; border-color: #fde68a; }
        .ep-pill.s-complete     { background: #d1fae5; color: #065f46; border-color: #6ee7b7; }
        .ep-pill.s-return       { background: #fee2e2; color: #991b1b; border-color: #fca5a5; }
        .ep-pill.s-rescheduling { background: #fef9c3; color: #854d0e; border-color: #fde047; }
        .ep-pill.s-cancelled    { background: #f1f5f9; color: #475569; border-color: #cbd5e1; }
        .ep-pill.ep-active      { box-shadow: 0 0 0 3px rgba(37,99,235,.3) !important; border-color: var(--primary) !important; transform: translateY(-1px); }

        /* Call status pills */
        .ep-call-pill {
            padding: 7px 14px;
            border-radius: 999px;
            font-size: 11.5px;
            font-weight: 700;
            border: 2px solid transparent;
            cursor: pointer;
            transition: all .18s;
            user-select: none;
        }
        .ep-call-pill input[type=radio] { display: none; }
        .ep-call-pill:hover { transform: translateY(-1px); }
        .ep-call-pill.cs-pending-call { background: #fef3c7; color: #92400e; border-color: #fde68a; }
        .ep-call-pill.cs-called       { background: #dbeafe; color: #1e40af; border-color: #93c5fd; }
        .ep-call-pill.cs-confirmed    { background: #d1fae5; color: #065f46; border-color: #6ee7b7; }
        .ep-call-pill.cs-not-answered { background: #f1f5f9; color: #475569; border-color: #cbd5e1; }
        .ep-call-pill.cs-cancelled    { background: #fee2e2; color: #991b1b; border-color: #fca5a5; }
        .ep-call-pill.ep-active       { outline: 3px solid var(--primary); outline-offset: 2px; }

        /* ── Conditional Date boxes ── */
        .ep-date-box {
            display: none;
            margin-top: .75rem;
        }
        .ep-date-box.show { display: block; }
        .ep-date-inner {
            border-radius: 12px;
            padding: 1rem 1.25rem;
            border: 2px solid;
        }
        .ep-date-inner.green  { background: #f0fdf4; border-color: #86efac; }
        .ep-date-inner.amber  { background: #fffbeb; border-color: #fde68a; }
        .ep-date-inner.red    { background: #fff1f2; border-color: #fca5a5; }
        .ep-date-inner .ep-label { margin-bottom: 8px; }

        /* ── Summary Table ── */
        .ep-summary-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 9px 0;
            border-bottom: 1px dashed var(--border);
            font-size: 13px;
        }
        .ep-summary-row:last-child { border-bottom: none; }
        .ep-summary-row .lbl { color: var(--muted); font-weight: 500; }
        .ep-summary-row .val { font-weight: 700; color: var(--dark); text-align: right; max-width: 160px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .ep-summary-row .val.primary { color: var(--primary); font-size: 17px; }
        .ep-summary-row .val.mono    { font-family: 'JetBrains Mono', monospace; font-size: 11px; }

        /* ── Timeline ── */
        .ep-timeline { list-style: none; margin: 0; padding: 0; }
        .ep-tl-item {
            display: flex;
            gap: 12px;
            padding-bottom: 1rem;
            position: relative;
        }
        .ep-tl-item:not(:last-child)::before {
            content: '';
            position: absolute;
            left: 13px; top: 28px; bottom: 0;
            width: 2px;
            background: var(--border);
        }
        .ep-tl-dot {
            width: 28px; height: 28px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .ep-tl-dot .material-icons-round { font-size: 14px; }
        .ep-tl-dot.created { background: #dbeafe; color: #2563eb; }
        .ep-tl-dot.updated { background: #d1fae5; color: #059669; }
        .ep-tl-dot.now     { background: #fef3c7; color: #d97706; }
        .ep-tl-title { font-size: 13px; font-weight: 600; color: var(--dark); }
        .ep-tl-time  { font-size: 11px; color: var(--muted); margin-top: 2px; }

        /* ── Buttons ── */
        .ep-btn-row {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 1.5rem;
        }
        .ep-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            padding: 11px 22px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 700;
            font-family: 'Plus Jakarta Sans', sans-serif;
            border: none;
            cursor: pointer;
            text-decoration: none;
            transition: all .2s;
            white-space: nowrap;
        }
        .ep-btn:hover { transform: translateY(-2px); }
        .ep-btn.primary {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white;
            box-shadow: 0 4px 14px rgba(37,99,235,.28);
            flex: 1;
        }
        .ep-btn.primary:hover { box-shadow: 0 8px 22px rgba(37,99,235,.38); color: white; }
        .ep-btn.secondary {
            background: white;
            color: var(--muted);
            border: 2px solid var(--border);
        }
        .ep-btn.secondary:hover { background: var(--bg); color: var(--dark); }
        .ep-btn.danger-outline {
            background: white;
            color: var(--danger);
            border: 2px solid #fca5a5;
            width: 100%;
            margin-top: .5rem;
        }
        .ep-btn.danger-outline:hover { background: #fff1f2; }
        .ep-btn[disabled] { opacity: .55; cursor: not-allowed; transform: none !important; pointer-events: none; }

        /* Loading state */
        .ep-spinner {
            width: 17px; height: 17px;
            border: 2.5px solid rgba(255,255,255,.3);
            border-top-color: white;
            border-radius: 50%;
            animation: ep-spin .65s linear infinite;
            display: none;
        }
        .ep-btn.loading .ep-spinner { display: block; }
        .ep-btn.loading .ep-btn-label { display: none; }
        @keyframes ep-spin { to { transform: rotate(360deg); } }

        /* ── Divider ── */
        .ep-divider {
            height: 1px;
            background: var(--border);
            margin: 1.25rem 0;
        }

        /* ── Danger Zone ── */
        .ep-danger-zone {
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1.5px dashed #fca5a5;
        }
        .ep-danger-label {
            text-align: center;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .7px;
            color: #f87171;
            margin-bottom: .5rem;
        }

        /* ── Sticky sidebar ── */
        .ep-sidebar { position: sticky; top: 20px; }

        /* ── Status badge (top of sidebar) ── */
        .ep-status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 14px;
            border-radius: 999px;
            font-size: 11.5px;
            font-weight: 700;
        }
        .ep-status-badge.pending      { background: #fef3c7; color: #92400e; }
        .ep-status-badge.complete     { background: #d1fae5; color: #065f46; }
        .ep-status-badge.return       { background: #fee2e2; color: #991b1b; }
        .ep-status-badge.rescheduling { background: #fef9c3; color: #854d0e; }
        .ep-status-badge.cancelled    { background: #f1f5f9; color: #475569; }

        /* ── Responsive ── */
        @media (max-width: 960px) {
            .ep-grid { grid-template-columns: 1fr; }
            .ep-sidebar { position: static; }
            .ep-row.three { grid-template-columns: 1fr 1fr; }
        }
        @media (max-width: 600px) {
            .ep-wrap { padding: 1rem 1rem 3rem; }
            .ep-hero { padding: 1.5rem; }
            .ep-hero h1 { font-size: 22px; }
            .ep-hero-right { gap: 1.25rem; }
            .ep-hero-stat { text-align: left; }
            .ep-row, .ep-row.three { grid-template-columns: 1fr; }
        }
    </style>
@endpush

@section('content')
    <div class="ep-wrap">

        {{-- ── Breadcrumb ── --}}
        <div class="ep-breadcrumb">
            <a href="{{ route('seller.order.pending') }}">
                <span class="material-icons-round" style="font-size:15px;vertical-align:middle;">home</span>
            </a>
            <span class="sep">›</span>
            <a href="{{ route('seller.order.pending') }}">Orders</a>
            <span class="sep">›</span>
            <a href="{{ route('seller.order.pending') }}">Pending</a>
            <span class="sep">›</span>
            <span class="current">{{ $order->order_number }}</span>
        </div>

        {{-- ── Hero ── --}}
        <div class="ep-hero">
            <div class="ep-hero-inner">
                <div class="ep-hero-left">
                    <div class="ep-hero-badge">
                        <span class="material-icons-round" style="font-size:13px;">receipt_long</span>
                        {{ $order->order_number }}
                    </div>
                    <h1>Edit Order</h1>
                    <p>Update product, customer, delivery & status details</p>
                </div>
                <div class="ep-hero-right">
                    <div class="ep-hero-stat">
                        <label>Order Value</label>
                        <div class="val">Rs. {{ number_format($order->total_amount, 0) }}</div>
                    </div>
                    <div class="ep-hero-stat">
                        <label>Order Date</label>
                        <div class="val sm">{{ \Carbon\Carbon::parse($order->order_date)->format('M d, Y') }}</div>
                    </div>
                    <div class="ep-hero-stat">
                        <label>Current Status</label>
                        <div class="val sm">
                        <span class="ep-status-badge {{ $order->status ?? 'pending' }}">
                            {{ ucfirst($order->status ?? 'pending') }}
                        </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Flash messages ── --}}
        @if(session('success'))
            <div class="ep-alert success">
                <span class="material-icons-round">check_circle</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="ep-alert danger">
                <span class="material-icons-round">error</span>
                <span>{{ session('error') }}</span>
            </div>
        @endif
        @if($errors->any())
            <div class="ep-alert danger">
                <span class="material-icons-round">error</span>
                <div>
                    <strong>Please fix the errors below:</strong>
                    <ul>
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        {{-- ── Main Form ── --}}
        <form id="epForm"
              method="POST"
              action="{{ route('seller.order.update', $order->id) }}"
              autocomplete="off">
            @csrf
            @method('PUT')

            <div class="ep-grid">

                {{-- ══════════════════════════════════════════ LEFT COLUMN ══ --}}
                <div class="ep-left">

                    {{-- ┌─ Product Info ─┐ --}}
                    <div class="ep-card">
                        <div class="ep-card-head">
                            <div class="ep-card-icon blue">
                                <span class="material-icons-round">inventory_2</span>
                            </div>
                            <div class="ep-card-head-text">
                                <h6>Product Information</h6>
                                <span>Quantity, price and order date</span>
                            </div>
                        </div>
                        <div class="ep-card-body">

                            {{-- Product preview --}}
                            <div class="ep-product-preview">
                                @if($order->product_image)
                                    <img src="{{ asset('storage/' . $order->product_image) }}"
                                         alt="{{ $order->product_name }}"
                                         class="ep-product-img"
                                         onerror="this.style.display='none'">
                                @else
                                    <div class="ep-product-placeholder">
                                        <span class="material-icons-round">image_not_supported</span>
                                    </div>
                                @endif
                                <div class="ep-product-info">
                                    <div class="ep-product-name">{{ $order->product_name }}</div>
                                    <div class="ep-product-code">{{ $order->product_code }}</div>
                                    <div class="ep-product-price">Rs. {{ number_format($order->product_price, 2) }}</div>
                                </div>
                            </div>

                            <div class="ep-row three">
                                <div class="ep-group">
                                    <label class="ep-label">Quantity <span class="req">*</span></label>
                                    <input type="number"
                                           name="quantity"
                                           id="epQty"
                                           class="ep-input mono"
                                           value="{{ old('quantity', $order->quantity) }}"
                                           min="1" required>
                                </div>
                                <div class="ep-group">
                                    <label class="ep-label">Unit Price (Rs.) <span class="req">*</span></label>
                                    <input type="number"
                                           name="product_price"
                                           id="epPrice"
                                           class="ep-input mono"
                                           step="0.01"
                                           value="{{ old('product_price', $order->product_price) }}"
                                           required>
                                </div>
                                <div class="ep-group">
                                    <label class="ep-label">Total Amount</label>
                                    <div class="ep-input-group">
                                        <span class="ep-input-prefix">Rs.</span>
                                        <input type="text"
                                               id="epTotalDisplay"
                                               class="ep-input mono"
                                               value="{{ number_format($order->total_amount, 2) }}"
                                               readonly>
                                    </div>
                                    <input type="hidden" name="total_amount" id="epTotalHidden" value="{{ $order->total_amount }}">
                                </div>
                            </div>

                            <div class="ep-row">
                                <div class="ep-group">
                                    <label class="ep-label">Order Date</label>
                                    <input type="date"
                                           name="order_date"
                                           class="ep-input"
                                           value="{{ old('order_date', \Carbon\Carbon::parse($order->order_date)->format('Y-m-d')) }}">
                                </div>
                                <div class="ep-group">
                                    <label class="ep-label">Platform</label>
                                    <select name="platform" class="ep-select">
                                        @foreach(['fb' => 'Facebook', 'ig' => 'Instagram', 'tiktok' => 'TikTok', 'website' => 'Website', 'manual' => 'Manual'] as $v => $l)
                                            <option value="{{ $v }}" {{ ($order->platform ?? 'manual') === $v ? 'selected' : '' }}>{{ $l }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>{{-- /Product Info --}}

                    {{-- ┌─ Customer Information ─┐ --}}
                    <div class="ep-card">
                        <div class="ep-card-head">
                            <div class="ep-card-icon green">
                                <span class="material-icons-round">person</span>
                            </div>
                            <div class="ep-card-head-text">
                                <h6>Customer Information</h6>
                                <span>Contact details and delivery address</span>
                            </div>
                        </div>
                        <div class="ep-card-body">

                            <div class="ep-row">
                                <div class="ep-group">
                                    <label class="ep-label">Customer Name <span class="req">*</span></label>
                                    <input type="text"
                                           name="customer_name"
                                           class="ep-input"
                                           placeholder="Full name"
                                           value="{{ old('customer_name', $order->customer_name) }}"
                                           required>
                                </div>
                                <div class="ep-group">
                                    <label class="ep-label">Primary Phone <span class="req">*</span></label>
                                    <input type="text"
                                           name="customer_phone"
                                           class="ep-input mono"
                                           placeholder="07X XXX XXXX"
                                           value="{{ old('customer_phone', $order->customer_phone) }}"
                                           required>
                                </div>
                            </div>

                            <div class="ep-row">
                                <div class="ep-group">
                                    <label class="ep-label">Alternate Phone</label>
                                    <input type="text"
                                           name="customer_phone_2"
                                           class="ep-input mono"
                                           placeholder="Optional second number"
                                           value="{{ old('customer_phone_2', $order->customer_phone_2) }}">
                                </div>
                                <div class="ep-group">
                                    <label class="ep-label">Payment Method</label>
                                    <select name="payment_method" class="ep-select">
                                        <option value="cash"   {{ ($order->payment_method ?? 'cash') === 'cash'   ? 'selected' : '' }}>💵 Cash on Delivery</option>
                                        <option value="online" {{ ($order->payment_method ?? '') === 'online'     ? 'selected' : '' }}>🏦 Online Transfer</option>
                                        <option value="card"   {{ ($order->payment_method ?? '') === 'card'       ? 'selected' : '' }}>💳 Card Payment</option>
                                    </select>
                                </div>
                            </div>

                            <div class="ep-row full">
                                <div class="ep-group">
                                    <label class="ep-label">Delivery Address <span class="req">*</span></label>
                                    <textarea name="customer_address"
                                              class="ep-textarea"
                                              placeholder="Full delivery address including house number, street, area..."
                                              required>{{ old('customer_address', $order->customer_address) }}</textarea>
                                </div>
                            </div>

                        </div>
                    </div>{{-- /Customer --}}

                    {{-- ┌─ Delivery Location ─┐ --}}
                    <div class="ep-card">
                        <div class="ep-card-head">
                            <div class="ep-card-icon purple">
                                <span class="material-icons-round">location_on</span>
                            </div>
                            <div class="ep-card-head-text">
                                <h6>Delivery Location</h6>
                                <span>City, district and Curfox routing details</span>
                            </div>
                        </div>
                        <div class="ep-card-body">

                            @if(isset($apiError))
                                <div class="ep-alert danger" style="margin-bottom:1rem;">
                                    <span class="material-icons-round">wifi_off</span>
                                    <span>{{ $apiError }} — you can enter the city manually.</span>
                                </div>
                            @endif

                            <div class="ep-row">
                                <div class="ep-group">
                                    <label class="ep-label">Destination City <span class="req">*</span></label>
                                    @if(isset($statesWithCities) && count($statesWithCities) > 0)
                                        <select name="destination_city_name"
                                                id="epCitySelect"
                                                class="ep-select"
                                                onchange="epHandleCity(this)">
                                            <option value="">— Select City —</option>
                                            @foreach($statesWithCities as $state)
                                                <optgroup label="{{ $state['name'] }}">
                                                    @foreach($state['cities'] ?? [] as $city)
                                                        @if($city['is_active'] ?? true)
                                                            <option value="{{ $city['name'] }}"
                                                                    data-city-id="{{ $city['id'] }}"
                                                                    data-state="{{ $state['name'] }}"
                                                                {{ ($order->destination_city_name ?? $order->district) === $city['name'] ? 'selected' : '' }}>
                                                                {{ $city['name'] }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        </select>
                                    @else
                                        <input type="text"
                                               name="destination_city_name"
                                               class="ep-input"
                                               placeholder="Enter city name"
                                               value="{{ old('destination_city_name', $order->destination_city_name ?? $order->district) }}">
                                    @endif
                                    <p class="ep-help">
                                        <span class="material-icons-round">info</span>
                                        Select from Curfox delivery network
                                    </p>
                                </div>
                                <div class="ep-group">
                                    <label class="ep-label">District / Zone</label>
                                    <input type="text"
                                           name="district"
                                           id="epDistrict"
                                           class="ep-input"
                                           placeholder="Auto-filled on city select"
                                           value="{{ old('district', $order->district) }}">
                                </div>
                            </div>

                            <div class="ep-row">
                                <div class="ep-group">
                                    <label class="ep-label">State / Province</label>
                                    <input type="text"
                                           name="destination_state_name"
                                           id="epState"
                                           class="ep-input"
                                           placeholder="Auto-filled on city select"
                                           value="{{ old('destination_state_name', $order->destination_state_name) }}">
                                </div>
                                <div class="ep-group">
                                    <label class="ep-label">City ID (Curfox)</label>
                                    <input type="number"
                                           name="destination_city_id"
                                           id="epCityId"
                                           class="ep-input mono"
                                           placeholder="Auto-filled"
                                           value="{{ old('destination_city_id', $order->destination_city_id) }}">
                                </div>
                            </div>

                        </div>
                    </div>{{-- /Delivery Location --}}

                    {{-- ┌─ Remarks ─┐ --}}
                    <div class="ep-card">
                        <div class="ep-card-head">
                            <div class="ep-card-icon amber">
                                <span class="material-icons-round">comment</span>
                            </div>
                            <div class="ep-card-head-text">
                                <h6>Notes &amp; Remarks</h6>
                                <span>Internal notes and delivery instructions</span>
                            </div>
                        </div>
                        <div class="ep-card-body">
                            <div class="ep-row">
                                <div class="ep-group">
                                    <label class="ep-label">Order Remark</label>
                                    <textarea name="remark"
                                              class="ep-textarea"
                                              placeholder="Internal notes about this order...">{{ old('remark', $order->remark) }}</textarea>
                                </div>
                                <div class="ep-group">
                                    <label class="ep-label">Delivery Remark</label>
                                    <textarea name="delivery_remark"
                                              class="ep-textarea"
                                              placeholder="Special instructions for delivery personnel...">{{ old('delivery_remark', $order->delivery_remark) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>{{-- /Remarks --}}

                </div>{{-- /left --}}

                {{-- ══════════════════════════════════════════ SIDEBAR ══════ --}}
                <div class="ep-sidebar">

                    {{-- ┌─ Order Status ─┐ --}}
                    <div class="ep-card">
                        <div class="ep-card-head">
                            <div class="ep-card-icon rose">
                                <span class="material-icons-round">tune</span>
                            </div>
                            <div class="ep-card-head-text">
                                <h6>Order Status</h6>
                                <span>Select the current state</span>
                            </div>
                        </div>
                        <div class="ep-card-body">
                            @php $curStatus = old('status', $order->status ?? 'pending'); @endphp

                            <div class="ep-pills" id="epStatusPills">
                                @foreach([
                                    'pending'      => ['⏳', 'Pending',     's-pending'],
                                    'complete'     => ['✅', 'Complete',    's-complete'],
                                    'return'       => ['↩️', 'Return',      's-return'],
                                    'rescheduling' => ['📅', 'Reschedule',  's-rescheduling'],
                                    'cancelled'    => ['❌', 'Cancelled',   's-cancelled'],
                                ] as $val => [$ico, $lbl, $cls])
                                    <label class="ep-pill {{ $cls }} {{ $curStatus === $val ? 'ep-active' : '' }}">
                                        <input type="radio" name="status" value="{{ $val }}"
                                               {{ $curStatus === $val ? 'checked' : '' }}
                                               onchange="epStatusChange('{{ $val }}')">
                                        {{ $ico }} {{ $lbl }}
                                    </label>
                                @endforeach
                            </div>

                            {{-- Conditional date fields --}}
                            <div id="epDateComplete"      class="ep-date-box {{ $curStatus === 'complete'     ? 'show' : '' }}">
                                <div class="ep-date-inner green">
                                    <label class="ep-label">
                                        <span class="material-icons-round" style="font-size:13px;vertical-align:middle;color:#16a34a;margin-right:3px;">local_shipping</span>
                                        Delivery Date
                                    </label>
                                    <input type="date" name="delivery_date" class="ep-input"
                                           value="{{ old('delivery_date', $order->delivery_date ? \Carbon\Carbon::parse($order->delivery_date)->format('Y-m-d') : date('Y-m-d')) }}">
                                </div>
                            </div>
                            <div id="epDateRescheduling"  class="ep-date-box {{ $curStatus === 'rescheduling' ? 'show' : '' }}">
                                <div class="ep-date-inner amber">
                                    <label class="ep-label">
                                        <span class="material-icons-round" style="font-size:13px;vertical-align:middle;color:#d97706;margin-right:3px;">event_repeat</span>
                                        Rescheduling Date
                                    </label>
                                    <input type="date" name="rescheduling_date" class="ep-input"
                                           value="{{ old('rescheduling_date', $order->rescheduling_date ? \Carbon\Carbon::parse($order->rescheduling_date)->format('Y-m-d') : date('Y-m-d')) }}">
                                </div>
                            </div>
                            <div id="epDateReturn"        class="ep-date-box {{ $curStatus === 'return'       ? 'show' : '' }}">
                                <div class="ep-date-inner red">
                                    <label class="ep-label">
                                        <span class="material-icons-round" style="font-size:13px;vertical-align:middle;color:#dc2626;margin-right:3px;">assignment_return</span>
                                        Return Date
                                    </label>
                                    <input type="date" name="return_date" class="ep-input"
                                           value="{{ old('return_date', $order->return_date ? \Carbon\Carbon::parse($order->return_date)->format('Y-m-d') : date('Y-m-d')) }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ┌─ Call Status ─┐ --}}
                    <div class="ep-card">
                        <div class="ep-card-head">
                            <div class="ep-card-icon cyan">
                                <span class="material-icons-round">phone_in_talk</span>
                            </div>
                            <div class="ep-card-head-text">
                                <h6>Call Status</h6>
                                <span>Customer confirmation state</span>
                            </div>
                        </div>
                        <div class="ep-card-body">
                            @php $curCall = old('call_status', $order->call_status ?? 'Pending Call'); @endphp
                            <div class="ep-pills" id="epCallPills">
                                @foreach([
                                    'Pending Call' => 'cs-pending-call',
                                    'Called'       => 'cs-called',
                                    'Confirmed'    => 'cs-confirmed',
                                    'Not Answered' => 'cs-not-answered',
                                    'Cancelled'    => 'cs-cancelled',
                                ] as $val => $cls)
                                    <label class="ep-call-pill {{ $cls }} {{ $curCall === $val ? 'ep-active' : '' }}">
                                        <input type="radio" name="call_status" value="{{ $val }}"
                                               {{ $curCall === $val ? 'checked' : '' }}
                                               onchange="epCallChange('{{ $val }}')">
                                        {{ $val }}
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- ┌─ Order Summary ─┐ --}}
                    <div class="ep-card">
                        <div class="ep-card-head">
                            <div class="ep-card-icon green">
                                <span class="material-icons-round">summarize</span>
                            </div>
                            <div class="ep-card-head-text">
                                <h6>Live Summary</h6>
                                <span>Updates as you edit</span>
                            </div>
                        </div>
                        <div class="ep-card-body">
                            <div class="ep-summary-row">
                                <span class="lbl">Order #</span>
                                <span class="val mono">{{ $order->order_number }}</span>
                            </div>
                            <div class="ep-summary-row">
                                <span class="lbl">Product</span>
                                <span class="val" style="font-size:12px;">{{ Str::limit($order->product_name, 20) }}</span>
                            </div>
                            <div class="ep-summary-row">
                                <span class="lbl">Quantity</span>
                                <span class="val" id="epSumQty">{{ $order->quantity }}</span>
                            </div>
                            <div class="ep-summary-row">
                                <span class="lbl">Unit Price</span>
                                <span class="val" id="epSumPrice">Rs. {{ number_format($order->product_price, 2) }}</span>
                            </div>
                            <div class="ep-summary-row">
                                <span class="lbl">Total</span>
                                <span class="val primary" id="epSumTotal">Rs. {{ number_format($order->total_amount, 2) }}</span>
                            </div>
                            <div class="ep-summary-row">
                                <span class="lbl">Customer</span>
                                <span class="val" style="font-size:12px;">{{ $order->customer_name }}</span>
                            </div>
                            <div class="ep-summary-row">
                                <span class="lbl">Platform</span>
                                <span class="val">{{ ucfirst($order->platform ?? 'Manual') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- ┌─ Activity ─┐ --}}
                    <div class="ep-card">
                        <div class="ep-card-head">
                            <div class="ep-card-icon slate">
                                <span class="material-icons-round">history</span>
                            </div>
                            <div class="ep-card-head-text">
                                <h6>Activity Timeline</h6>
                            </div>
                        </div>
                        <div class="ep-card-body">
                            <ul class="ep-timeline">
                                <li class="ep-tl-item">
                                    <div class="ep-tl-dot created">
                                        <span class="material-icons-round">add_circle</span>
                                    </div>
                                    <div>
                                        <div class="ep-tl-title">Order Created</div>
                                        <div class="ep-tl-time">{{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y · h:i A') }}</div>
                                    </div>
                                </li>
                                @if($order->updated_at && $order->updated_at->ne($order->created_at))
                                    <li class="ep-tl-item">
                                        <div class="ep-tl-dot updated">
                                            <span class="material-icons-round">edit</span>
                                        </div>
                                        <div>
                                            <div class="ep-tl-title">Last Updated</div>
                                            <div class="ep-tl-time">{{ \Carbon\Carbon::parse($order->updated_at)->format('M d, Y · h:i A') }}</div>
                                        </div>
                                    </li>
                                @endif
                                <li class="ep-tl-item">
                                    <div class="ep-tl-dot now">
                                        <span class="material-icons-round">pending</span>
                                    </div>
                                    <div>
                                        <div class="ep-tl-title">Editing Now</div>
                                        <div class="ep-tl-time">{{ now()->format('M d, Y · h:i A') }}</div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>

                    {{-- ── Action Buttons ── --}}
                    <div class="ep-btn-row">
                        <a href="{{ route('seller.order.pending') }}" class="ep-btn secondary">
                            <span class="material-icons-round">arrow_back</span>
                            Back
                        </a>
                        <button type="submit" class="ep-btn primary" id="epSaveBtn">
                            <div class="ep-spinner"></div>
                            <span class="ep-btn-label">
                            <span class="material-icons-round">save</span>
                            Save Changes
                        </span>
                        </button>
                    </div>

                    {{-- Danger Zone --}}
                    @if(($order->status ?? 'pending') === 'pending')
                        <div class="ep-danger-zone">
                            <div class="ep-danger-label">⚠ Danger Zone</div>
                            <button type="button"
                                    class="ep-btn danger-outline"
                                    onclick="epDeleteOrder({{ $order->id }}, '{{ $order->order_number }}')">
                                <span class="material-icons-round">delete_forever</span>
                                Delete This Order
                            </button>
                        </div>
                    @endif

                </div>{{-- /sidebar --}}

            </div>{{-- /grid --}}
        </form>

    </div>
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const CSRF_TOKEN = '{{ csrf_token() }}';

        // ── City select handler ──────────────────────────────────────────────────────
        function epHandleCity(sel) {
            const opt = sel.options[sel.selectedIndex];
            document.getElementById('epCityId').value   = opt.dataset.cityId  || '';
            document.getElementById('epState').value    = opt.dataset.state   || '';
            document.getElementById('epDistrict').value = opt.value || '';
        }

        // ── Status change ────────────────────────────────────────────────────────────
        function epStatusChange(status) {
            document.querySelectorAll('#epStatusPills .ep-pill').forEach(p => {
                p.classList.toggle('ep-active', p.querySelector('input').value === status);
            });
            document.getElementById('epDateComplete').classList.toggle('show',      status === 'complete');
            document.getElementById('epDateRescheduling').classList.toggle('show',  status === 'rescheduling');
            document.getElementById('epDateReturn').classList.toggle('show',        status === 'return');
        }

        // ── Call status change ───────────────────────────────────────────────────────
        function epCallChange(val) {
            document.querySelectorAll('#epCallPills .ep-call-pill').forEach(p => {
                p.classList.toggle('ep-active', p.querySelector('input').value === val);
            });
        }

        // ── Live total recalc ────────────────────────────────────────────────────────
        function epRecalc() {
            const qty   = parseFloat(document.getElementById('epQty').value)   || 0;
            const price = parseFloat(document.getElementById('epPrice').value) || 0;
            const total = qty * price;
            const fmt   = total.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');

            document.getElementById('epTotalDisplay').value = fmt;
            document.getElementById('epTotalHidden').value  = total.toFixed(2);
            document.getElementById('epSumQty').textContent    = qty;
            document.getElementById('epSumPrice').textContent  = 'Rs. ' + price.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            document.getElementById('epSumTotal').textContent  = 'Rs. ' + fmt;
        }
        document.getElementById('epQty').addEventListener('input',   epRecalc);
        document.getElementById('epPrice').addEventListener('input', epRecalc);

        // ── Form submit – loading state ──────────────────────────────────────────────
        document.getElementById('epForm').addEventListener('submit', function () {
            const btn = document.getElementById('epSaveBtn');
            btn.classList.add('loading');
            btn.setAttribute('disabled', true);
        });

        // ── Delete ────────────────────────────────────────────────────────────────────
        function epDeleteOrder(id, orderNumber) {
            Swal.fire({
                title: 'Delete Order?',
                html: `Permanently delete <strong>${orderNumber}</strong>?<br>
               <small style="color:#64748b;">Stock and deposit will be restored automatically.</small>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Delete',
                confirmButtonColor: '#ef4444',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
            }).then(result => {
                if (!result.isConfirmed) return;
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/seller/order/destroy/${id}`;
                form.innerHTML = `
            <input type="hidden" name="_token"  value="${CSRF_TOKEN}">
            <input type="hidden" name="_method" value="DELETE">
        `;
                document.body.appendChild(form);
                form.submit();
            });
        }

        // ── Init on DOM ready ─────────────────────────────────────────────────────────
        document.addEventListener('DOMContentLoaded', function () {
            // Sync city fields from pre-selected city
            const citySelect = document.getElementById('epCitySelect');
            if (citySelect && citySelect.value) epHandleCity(citySelect);

            // Sync status pill highlights (belt-and-braces for any pre-checked radio)
            const checkedStatus = document.querySelector('#epStatusPills input[type=radio]:checked');
            if (checkedStatus) epStatusChange(checkedStatus.value);

            const checkedCall = document.querySelector('#epCallPills input[type=radio]:checked');
            if (checkedCall) epCallChange(checkedCall.value);
        });
    </script>
@endpush
