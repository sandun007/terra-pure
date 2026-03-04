@extends('seller.layout.app')

@push('title')
    Create New Order
@endpush

@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&family=DM+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        :root {
            --primary: #4f46e5; --primary-hover: #4338ca; --primary-light: #eef2ff;
            --success: #10b981; --danger: #ef4444; --warning: #f59e0b;
            --dark: #0f172a; --mid: #64748b; --border: #e2e8f0;
            --surface: #ffffff; --bg: #f8fafc;
            --mono: 'DM Mono', monospace; --sans: 'DM Sans', sans-serif;
            --radius-xl: 16px; --radius-lg: 12px; --radius-md: 8px;
            --shadow-sm: 0 2px 8px rgba(0,0,0,.07); --shadow-md: 0 6px 20px rgba(0,0,0,.09);
        }
        * { box-sizing: border-box; }
        body { font-family: var(--sans); background: var(--bg); }
        .order-wrapper { padding: 32px; max-width: 1400px; margin: 0 auto; }
        @media (max-width: 768px) { .order-wrapper { padding: 16px; } }

        .page-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:28px; flex-wrap:wrap; gap:16px; }
        .page-title  { font-size:26px; font-weight:800; color:var(--dark); display:flex; align-items:center; gap:10px; margin:0; }
        .header-actions { display:flex; gap:12px; flex-wrap:wrap; }

        .form-container { background:var(--surface); border:1px solid var(--border); border-radius:var(--radius-xl); padding:32px; box-shadow:var(--shadow-sm); }
        @media (max-width: 768px) { .form-container { padding:20px; } }

        .section-title { font-size:15px; font-weight:700; color:var(--dark); margin:0 0 18px; display:flex; align-items:center; gap:8px; padding-bottom:10px; border-bottom:2px solid var(--border); }
        .section-divider { height:2px; background:linear-gradient(to right, var(--primary-light), transparent); border-radius:999px; margin:28px 0; }

        .form-grid { display:grid; grid-template-columns:1fr 1fr; gap:24px; margin-bottom:24px; }
        @media (max-width: 768px) { .form-grid { grid-template-columns:1fr; gap:16px; } }

        .form-group { margin-bottom:0; }
        .form-label { display:block; font-size:14px; font-weight:600; color:var(--dark); margin-bottom:8px; }
        .form-label .req { color:var(--danger); margin-left:2px; }

        .form-control { width:100%; padding:12px 16px; border:2px solid var(--border); border-radius:var(--radius-md); font-family:var(--sans); font-size:14px; transition:all .2s; background:var(--surface); }
        .form-control:focus { outline:none; border-color:var(--primary); box-shadow:0 0 0 3px rgba(79,70,229,.1); }
        .form-control:disabled { background:#f1f5f9; cursor:not-allowed; }
        textarea.form-control { resize:vertical; min-height:88px; }

        .phone-pair { display:grid; grid-template-columns:1fr 1fr; gap:10px; }
        @media (max-width: 480px) { .phone-pair { grid-template-columns:1fr; } }
        .sub-label { font-size:11px; color:var(--mid); font-weight:600; margin-bottom:4px; display:block; }

        /* Product search */
        .search-section { background:linear-gradient(135deg,#fff 0%,#f8fafc 100%); border:2px solid var(--border); border-radius:var(--radius-lg); padding:24px; margin-bottom:24px; }
        .search-header { font-size:16px; font-weight:700; color:var(--dark); margin-bottom:16px; display:flex; align-items:center; gap:8px; }
        .input-row { display:flex; gap:12px; align-items:flex-start; flex-wrap:wrap; }
        .input-row .form-control { flex:1; min-width:240px; }

        /* Buttons */
        .btn { display:inline-flex; align-items:center; gap:8px; padding:12px 24px; border-radius:var(--radius-md); font-size:14px; font-weight:600; border:none; cursor:pointer; transition:all .2s; font-family:var(--sans); text-decoration:none; white-space:nowrap; }
        .btn-primary   { background:var(--primary); color:#fff; }
        .btn-primary:hover   { background:var(--primary-hover); transform:translateY(-1px); }
        .btn-secondary { background:var(--dark); color:#fff; }
        .btn-secondary:hover { background:#1e293b; transform:translateY(-1px); }
        .btn-success   { background:var(--success); color:#fff; }
        .btn-success:hover   { background:#059669; transform:translateY(-1px); }
        .btn-warning   { background:var(--warning); color:#fff; }
        .btn-warning:hover   { background:#d97706; transform:translateY(-1px); }
        .btn-danger    { background:var(--danger); color:#fff; }
        .btn-danger:hover    { background:#dc2626; }
        .btn:disabled  { opacity:.5; cursor:not-allowed; transform:none !important; }

        /* Product info card */
        .product-card { background:var(--surface); border:2px solid var(--border); border-radius:var(--radius-lg); padding:20px; margin-bottom:24px; display:none; }
        .product-card.show { display:block; animation:slideDown .3s ease-out; }
        @keyframes slideDown { from{opacity:0;transform:translateY(-10px)} to{opacity:1;transform:translateY(0)} }
        .product-card-header { display:flex; align-items:center; gap:16px; margin-bottom:16px; padding-bottom:16px; border-bottom:2px solid var(--border); }
        .product-img { width:80px; height:80px; border-radius:var(--radius-md); object-fit:cover; border:2px solid var(--border); }
        .product-img-ph { width:80px; height:80px; border-radius:var(--radius-md); border:2px dashed var(--border); background:#f1f5f9; display:flex; align-items:center; justify-content:center; color:var(--mid); }
        .product-card-header h4 { font-size:18px; font-weight:700; color:var(--dark); margin:0 0 4px; }
        .code-badge { display:inline-flex; align-items:center; gap:6px; padding:4px 12px; background:var(--primary-light); color:var(--primary); border-radius:999px; font-family:var(--mono); font-size:12px; font-weight:600; }
        .product-details-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:16px; }
        @media (max-width:768px) { .product-details-grid { grid-template-columns:1fr; } }
        .detail-box { padding:12px; background:var(--bg); border-radius:var(--radius-md); }
        .detail-box-label { font-size:12px; color:var(--mid); margin-bottom:4px; }
        .detail-box-value { font-size:16px; font-weight:700; color:var(--dark); }
        .stock-badge { display:inline-flex; align-items:center; gap:4px; padding:4px 8px; border-radius:999px; font-size:11px; font-weight:600; }
        .stock-ok  { background:#d1fae5; color:#065f46; }
        .stock-low { background:#fef3c7; color:#92400e; }
        .stock-out { background:#fee2e2; color:#991b1b; }

        /* Price summary box */
        .price-summary { background:linear-gradient(135deg,#f0fdf4 0%,#dcfce7 100%); border:2px solid #86efac; border-radius:var(--radius-lg); padding:20px; margin-bottom:24px; display:none; }
        .price-summary.show { display:block; animation:slideDown .3s ease-out; }
        .price-summary-title { font-size:14px; font-weight:700; color:#15803d; margin-bottom:14px; display:flex; align-items:center; gap:8px; }
        .price-summary-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:12px; }
        @media (max-width:768px) { .price-summary-grid { grid-template-columns:repeat(2,1fr); } }
        .ps-item { background:#fff; border-radius:var(--radius-md); padding:12px; border:1px solid #bbf7d0; }
        .ps-label { font-size:11px; color:#16a34a; font-weight:600; margin-bottom:4px; text-transform:uppercase; letter-spacing:.5px; }
        .ps-value { font-size:18px; font-weight:800; color:var(--dark); }
        .ps-value.red { color:#dc2626; }
        .ps-value.green { color:#15803d; font-size:22px; }
        .ps-total { background:linear-gradient(135deg,#f0fdf4,#dcfce7); border-color:#86efac; }

        /* Price input group */
        .price-group { position:relative; }
        .price-prefix { position:absolute; left:14px; top:50%; transform:translateY(-50%); font-weight:700; color:var(--mid); font-size:14px; pointer-events:none; z-index:1; }
        .price-group .form-control { padding-left:44px; }

        /* Auto-confirm badge */
        .auto-confirm-notice { display:flex; align-items:center; flex-wrap:wrap; gap:10px; background:#f0fdf4; border:1px solid #86efac; border-radius:var(--radius-md); padding:12px 16px; margin-bottom:24px; font-size:13px; }
        .ac-badge { display:inline-flex; align-items:center; gap:5px; background:#d1fae5; color:#065f46; border-radius:999px; padding:3px 12px; font-weight:700; font-size:12px; }
        .ac-bulk  { display:inline-flex; align-items:center; gap:5px; background:#fef3c7; color:#92400e; border-radius:999px; padding:3px 12px; font-weight:700; font-size:12px; }

        /* Remark cards */
        .remark-grid { display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:24px; }
        @media (max-width:768px) { .remark-grid { grid-template-columns:1fr; } }
        .remark-card { border:2px solid var(--border); border-radius:var(--radius-md); padding:16px; }
        .remark-card-title { font-size:13px; font-weight:700; color:var(--dark); margin-bottom:10px; display:flex; align-items:center; gap:6px; }
        .remark-card textarea { border:1px solid #e2e8f0; background:#f8fafc; min-height:80px; }
        .helper-text { font-size:12px; color:var(--mid); margin-top:4px; }

        /* Form actions */
        .form-actions { display:flex; gap:12px; justify-content:flex-end; padding-top:24px; border-top:2px solid var(--border); }
        @media (max-width:576px) { .form-actions { flex-direction:column-reverse; } .btn { width:100%; justify-content:center; } }

        /* Alerts */
        .alert { padding:14px 18px; border-radius:var(--radius-md); margin-bottom:20px; display:flex; align-items:center; gap:10px; }
        .alert-success { background:#d1fae5; border:1px solid #10b981; color:#065f46; }
        .alert-danger  { background:#fee2e2; border:1px solid #ef4444; color:#991b1b; }
        .alert-warning { background:#fef3c7; border:1px solid #f59e0b; color:#92400e; }

        .spinner { display:inline-block; width:16px; height:16px; border:2px solid rgba(255,255,255,.3); border-radius:50%; border-top-color:#fff; animation:spin .6s linear infinite; }
        @keyframes spin { to{transform:rotate(360deg)} }

        /* City autocomplete */
        .city-wrap { position:relative; }
        .city-wrap .form-control { padding-right:40px; }
        .city-clear { position:absolute; right:12px; top:50%; transform:translateY(-50%); background:none; border:none; cursor:pointer; color:var(--mid); padding:0; display:none; align-items:center; justify-content:center; width:20px; height:20px; border-radius:50%; transition:all .2s; z-index:2; }
        .city-clear:hover { background:#fee2e2; color:var(--danger); }
        .city-clear.on { display:flex; }
        .city-drop { position:absolute; top:calc(100% + 4px); left:0; right:0; background:var(--surface); border:2px solid var(--primary); border-radius:var(--radius-md); max-height:220px; overflow-y:auto; z-index:9999; box-shadow:var(--shadow-md); display:none; }
        .city-drop.open { display:block; animation:slideDown .15s ease-out; }
        .city-item { padding:10px 14px; font-size:14px; color:var(--dark); cursor:pointer; border-bottom:1px solid var(--border); display:flex; align-items:center; gap:8px; transition:background .1s; }
        .city-item:last-child { border-bottom:none; }
        .city-item:hover,.city-item.active { background:var(--primary-light); color:var(--primary); }
        .city-item .st { font-size:11px; color:var(--mid); margin-left:auto; }
        .city-item mark { background:none; color:var(--primary); font-weight:700; padding:0; }
        .city-empty { padding:14px; font-size:13px; color:var(--mid); text-align:center; }
        .city-selected-badge { display:inline-flex; align-items:center; gap:6px; padding:4px 10px; background:var(--primary-light); color:var(--primary); border-radius:999px; font-size:12px; font-weight:600; margin-top:6px; }
        .api-badge { display:inline-flex; align-items:center; gap:6px; font-size:11px; font-weight:600; padding:3px 10px; border-radius:999px; }
        .api-badge.ok  { background:#d1fae5; color:#065f46; }
        .api-badge.err { background:#fee2e2; color:#991b1b; }
        .api-badge.loading { background:#f1f5f9; color:var(--mid); }

        /* Bulk CSV section */
        .bulk-section { border:2px solid #fcd34d; border-radius:var(--radius-lg); padding:24px; margin-top:8px; background:linear-gradient(135deg,#fff 0%,#fffbeb 100%); }
        .bulk-header { font-size:16px; font-weight:700; color:var(--dark); margin-bottom:20px; display:flex; align-items:center; gap:8px; flex-wrap:wrap; }
        .bulk-product-box { background:#fffbeb; border:2px solid #fcd34d; border-radius:var(--radius-md); padding:18px; margin-bottom:16px; }
        .bulk-product-verified { background:#fff; border:2px solid #10b981; border-radius:var(--radius-md); padding:14px; margin-top:14px; display:none; }
        .bulk-csv-box { background:#f8fafc; border:2px solid var(--border); border-radius:var(--radius-md); padding:18px; margin-bottom:16px; }
        .step-label { font-size:13px; font-weight:700; color:var(--dark); margin-bottom:12px; display:flex; align-items:center; gap:6px; }

        /* Validation summary */
        .val-summary { display:flex; gap:12px; flex-wrap:wrap; margin:10px 0; }
        .val-badge { padding:4px 12px; border-radius:999px; font-size:12px; font-weight:700; }
        .vb-ok  { background:#d1fae5; color:#065f46; }
        .vb-err { background:#fee2e2; color:#991b1b; }
        .vb-warn { background:#fef3c7; color:#92400e; }
        .val-note { font-size:12px; color:var(--mid); margin-top:4px; }
        .cs-ok  { color:#065f46; font-weight:600; font-size:12px; }
        .cs-bad { color:#991b1b; font-weight:600; font-size:12px; }
        .cs-miss { color:#92400e; font-weight:600; font-size:12px; }

        /* Preview table */
        .preview-table { width:100%; border-collapse:collapse; font-size:13px; }
        .preview-table th { text-align:left; padding:10px; border-bottom:1px solid var(--border); background:var(--bg); }
        .preview-table td { padding:10px; border-bottom:1px solid var(--border); }
    </style>
@endpush

@section('content')
    <div class="order-wrapper">

        {{-- Page header --}}
        <div class="page-header">
            <h1 class="page-title">
                <span class="material-icons-round" style="color:var(--primary);font-size:30px">add_shopping_cart</span>
                Create New Order
            </h1>
            <div class="header-actions">
                <a href="{{ route('seller.order.pending') }}" class="btn btn-secondary">
                    <span class="material-icons-round" style="font-size:18px">list_alt</span>View Orders
                </a>
            </div>
        </div>

        {{-- Session alerts --}}
        @if(session('error'))
            <div class="alert alert-danger"><span class="material-icons-round">error</span>{{ session('error') }}</div>
        @endif
        @if(session('success'))
            <div class="alert alert-success"><span class="material-icons-round">check_circle</span>{{ session('success') }}</div>
        @endif
        @if(isset($apiError))
            <div class="alert alert-warning"><span class="material-icons-round">warning</span>{{ $apiError }}</div>
        @endif

        <form id="orderForm" action="{{ route('seller.order.store') }}" method="POST">
            @csrf

            <div class="form-container">

                {{-- Auto-confirm notice --}}
                <div class="auto-confirm-notice">
                    <span class="material-icons-round" style="color:#15803d;font-size:20px">verified</span>
                    <span style="font-weight:600;color:#15803d">Single orders are auto-confirmed:</span>
                    <span class="ac-badge"><span class="material-icons-round" style="font-size:14px">check_circle</span>call_status = Confirmed</span>
                    <span class="ac-badge"><span class="material-icons-round" style="font-size:14px">local_shipping</span>Delivery call created instantly</span>
                    <span style="color:var(--mid);font-size:12px">vs.</span>
                    <span class="ac-bulk"><span class="material-icons-round" style="font-size:14px">pending</span>Bulk CSV → Pending Call</span>
                </div>

                {{-- ═══════════════════════════════════════════════════════════
                     STEP 1 — Product Search
                ═══════════════════════════════════════════════════════════ --}}
                <div class="search-section">
                    <div class="search-header">
                        <span class="material-icons-round" style="color:var(--primary)">qr_code_scanner</span>
                        Step 1 — Search Product by Code
                    </div>
                    <div class="input-row">
                        <div style="flex:1">
                            <input type="text" id="searchProductCode" class="form-control"
                                   placeholder="Enter product code e.g. DX/ITM/0001"
                                   autocomplete="off" value="{{ $productCode ?? '' }}">
                            <div class="helper-text"><i class="fas fa-info-circle"></i> Enter code then click Fetch Product</div>
                        </div>
                        <button type="button" id="fetchProductBtn" class="btn btn-primary">
                            <span class="material-icons-round" style="font-size:18px">search</span>Fetch Product
                        </button>
                        <button type="button" id="resetBtn" class="btn btn-danger"
                                style="display:{{ ($productId ?? false) ? 'inline-flex' : 'none' }}">
                            <span class="material-icons-round" style="font-size:18px">refresh</span>Reset
                        </button>
                    </div>
                </div>

                {{-- Product info card --}}
                <div id="productCard" class="product-card {{ ($productId ?? false) ? 'show' : '' }}">
                    <div class="product-card-header">
                        <img id="productImg" src="{{ $productImage ?? '' }}" alt=""
                             class="product-img" style="display:{{ ($productImage ?? false) ? 'block' : 'none' }}">
                        <div id="productImgPh" class="product-img-ph"
                             style="display:{{ ($productImage ?? false) ? 'none' : 'flex' }}">
                            <span class="material-icons-round" style="font-size:32px">inventory_2</span>
                        </div>
                        <div style="flex:1">
                            <h4 id="dpName">{{ $productName ?? '-' }}</h4>
                            <span class="code-badge">
                            <span class="material-icons-round" style="font-size:14px">tag</span>
                            <span id="dpCode">{{ $productCode ?? '-' }}</span>
                        </span>
                        </div>
                    </div>
                    <div class="product-details-grid">
                        <div class="detail-box">
                            <div class="detail-box-label">Unit Price</div>
                            <div class="detail-box-value" style="color:#dc2626">Rs. <span id="dpPrice">{{ number_format($productPrice ?? 0, 2) }}</span></div>
                        </div>
                        <div class="detail-box">
                            <div class="detail-box-label">Available Stock</div>
                            <div class="detail-box-value"><span id="dpStock">{{ $productStock ?? '0' }}</span> units</div>
                        </div>
                        <div class="detail-box">
                            <div class="detail-box-label">Stock Status</div>
                            <div class="detail-box-value">
                            <span id="stockBadge" class="stock-badge
                                @if(($productStock ?? 0) <= 0) stock-out
                                @elseif(($productStock ?? 0) <= 10) stock-low
                                @else stock-ok @endif">
                                @if(($productStock ?? 0) <= 0)
                                    <span class="material-icons-round" style="font-size:14px">cancel</span> Out of Stock
                                @elseif(($productStock ?? 0) <= 10)
                                    <span class="material-icons-round" style="font-size:14px">warning</span> Low Stock
                                @else
                                    <span class="material-icons-round" style="font-size:14px">check_circle</span> Available
                                @endif
                            </span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Hidden fields --}}
                <input type="hidden" name="product_code"  id="product_code"  value="{{ $productCode ?? '' }}">
                <input type="hidden" name="product_id"    id="product_id"    value="{{ $productId ?? '' }}">
                <input type="hidden" name="product_name"  id="product_name"  value="{{ $productName ?? '' }}">
                <input type="hidden" name="product_price" id="product_price" value="{{ $productPrice ?? '0' }}">
                <input type="hidden" name="product_image" id="product_image" value="{{ $productImage ?? '' }}">
                <input type="hidden" name="date"          id="order_date"    value="{{ date('Y-m-d') }}">
                <input type="hidden" name="auto_confirm"  value="1">
                <input type="hidden" name="destination_city_id"    id="destination_city_id">
                <input type="hidden" name="destination_city_name"  id="destination_city_name">
                <input type="hidden" name="destination_state_name" id="destination_state_name">

                <div class="section-divider"></div>

                {{-- ═══════════════════════════════════════════════════════════
                     STEP 2 — Customer Details
                ═══════════════════════════════════════════════════════════ --}}
                <div class="section-title">
                    <span class="material-icons-round" style="color:var(--primary)">person</span>
                    Step 2 — Customer & Order Details
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Customer Name <span class="req">*</span></label>
                        <input type="text" name="customer_name" id="customer_name" class="form-control"
                               placeholder="Enter customer full name" value="{{ old('customer_name') }}" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Customer Phone <span class="req">*</span></label>
                        <div class="phone-pair">
                            <div>
                                <span class="sub-label">Primary</span>
                                <input type="text" name="customer_phone" id="customer_phone" class="form-control"
                                       placeholder="077 123 4567" value="{{ old('customer_phone') }}" required>
                            </div>
                            <div>
                                <span class="sub-label">Secondary <small>(optional)</small></span>
                                <input type="text" name="customer_phone_2" id="customer_phone_2" class="form-control"
                                       placeholder="071 765 4321" value="{{ old('customer_phone_2') }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Quantity <span class="req">*</span></label>
                        <input type="number" name="quantity" id="quantity" class="form-control"
                               placeholder="1" min="1" value="{{ $quantity ?? 1 }}"
                               {{ !($productId ?? false) ? 'disabled' : '' }} required>
                        <div class="helper-text">Available: <span id="availableStock">{{ $productStock ?? '0' }}</span> units</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <span class="material-icons-round" style="font-size:16px;vertical-align:middle;color:var(--primary)">local_shipping</span>
                            Delivery City <span class="req">*</span>
                            <span id="apiStatusBadge" class="api-badge loading" style="margin-left:6px">
                            <span class="spinner" style="width:10px;height:10px;border-top-color:currentColor;border-color:rgba(0,0,0,.15)"></span> Loading…
                        </span>
                        </label>
                        <div class="city-wrap">
                            <input type="text" name="district" id="district" class="form-control"
                                   placeholder="Type to search delivery city…"
                                   value="{{ old('district') }}" autocomplete="off" required>
                            <button type="button" class="city-clear" id="cityClearBtn">
                                <span class="material-icons-round" style="font-size:16px">close</span>
                            </button>
                            <div class="city-drop" id="cityDrop"></div>
                        </div>
                        <div class="helper-text" id="cityHelper">Cities loaded from Curfox delivery API</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Platform</label>
                        <select name="platform" id="platform" class="form-control">
                            <option value="">Select Platform</option>
                            <option value="fb"      {{ old('platform')=='fb'      ? 'selected':'' }}>Facebook</option>
                            <option value="ig"      {{ old('platform')=='ig'      ? 'selected':'' }}>Instagram</option>
                            <option value="tiktok"  {{ old('platform')=='tiktok'  ? 'selected':'' }}>TikTok</option>
                            <option value="website" {{ old('platform')=='website' ? 'selected':'' }}>Website</option>
                            <option value="phone"   {{ old('platform')=='phone'   ? 'selected':'' }}>Phone</option>
                            <option value="manual"  {{ old('platform')=='manual'  ? 'selected':'' }}>Manual</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Payment Method <span class="req">*</span></label>
                        <select name="payment_method" id="payment_method" class="form-control" required>
                            <option value="">Select Payment Method</option>
                            <option value="cash"          {{ old('payment_method')=='cash'          ? 'selected':'' }}>Cash on Delivery</option>
                            <option value="card"          {{ old('payment_method')=='card'          ? 'selected':'' }}>Card Payment</option>
                            <option value="bank_transfer" {{ old('payment_method')=='bank_transfer' ? 'selected':'' }}>Bank Transfer</option>
                            <option value="online"        {{ old('payment_method')=='online'        ? 'selected':'' }}>Online Payment</option>
                        </select>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom:24px">
                    <label class="form-label">Delivery Address <span class="req">*</span></label>
                    <textarea name="customer_address" id="customer_address" class="form-control"
                              placeholder="Full delivery address — street, area, city…">{{ old('customer_address') }}</textarea>
                </div>

                <div class="section-divider"></div>

                {{-- ═══════════════════════════════════════════════════════════
                     STEP 3 — Price
                ═══════════════════════════════════════════════════════════ --}}
                <div class="section-title">
                    <span class="material-icons-round" style="color:#15803d">receipt_long</span>
                    Step 3 — Price
                </div>

                {{-- Live price summary --}}
                <div id="priceSummary" class="price-summary {{ ($productId ?? false) ? 'show' : '' }}">
                    <div class="price-summary-title">
                        <span class="material-icons-round" style="font-size:16px">calculate</span>
                        Live Order Summary
                    </div>
                    <div class="price-summary-grid">
                        <div class="ps-item">
                            <div class="ps-label">Unit Price</div>
                            <div class="ps-value red">Rs. <span id="psUnitPrice">{{ number_format($productPrice ?? 0, 2) }}</span></div>
                        </div>
                        <div class="ps-item">
                            <div class="ps-label">Quantity</div>
                            <div class="ps-value"><span id="psQty">{{ $quantity ?? 1 }}</span> units</div>
                        </div>
                        <div class="ps-item">
                            <div class="ps-label">Subtotal</div>
                            <div class="ps-value">Rs. <span id="psSubtotal">{{ number_format(($productPrice ?? 0) * ($quantity ?? 1), 2) }}</span></div>
                        </div>
                        <div class="ps-item ps-total">
                            <div class="ps-label">COD Total</div>
                            <div class="ps-value green">Rs. <span id="psTotal">{{ number_format(($productPrice ?? 0) * ($quantity ?? 1), 2) }}</span></div>
                        </div>
                    </div>
                </div>

                <div class="form-grid" style="margin-bottom:0">
                    <div class="form-group">
                        <label class="form-label">
                            <span class="material-icons-round" style="font-size:15px;vertical-align:middle;color:#dc2626">sell</span>
                            Selling Price (per unit) <span class="req">*</span>
                        </label>
                        <div class="price-group">
                            <span class="price-prefix">Rs.</span>
                            <input type="number" name="selling_price" id="selling_price" class="form-control"
                                   placeholder="0.00" min="0" step="0.01"
                                   value="{{ $productPrice ?? '' }}"
                                {{ !($productId ?? false) ? 'disabled' : '' }}>
                        </div>
                        <div class="helper-text">Editable — overrides default product price</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">
                            <span class="material-icons-round" style="font-size:15px;vertical-align:middle;color:#059669">payments</span>
                            COD Amount (auto-calculated)
                        </label>
                        <div class="price-group">
                            <span class="price-prefix">Rs.</span>
                            <input type="text" id="cod_display" class="form-control"
                                   value="{{ number_format(($productPrice ?? 0) * ($quantity ?? 1), 2) }}"
                                   readonly style="background:#f0fdf4;font-weight:700;color:#15803d;font-size:16px;">
                        </div>
                        <div class="helper-text">Price × Quantity — amount collected on delivery</div>
                    </div>
                </div>

                <div class="section-divider"></div>

                {{-- ═══════════════════════════════════════════════════════════
                     STEP 4 — Remarks
                ═══════════════════════════════════════════════════════════ --}}
                <div class="section-title">
                    <span class="material-icons-round" style="color:var(--warning)">edit_note</span>
                    Step 4 — Remarks
                </div>

                <div class="remark-grid">
                    {{-- Remark 1 --}}
                    <div class="remark-card">
                        <div class="remark-card-title">
                            <span class="material-icons-round" style="font-size:16px;color:var(--primary)">assignment</span>
                            Remark 1
                            <span style="font-size:11px;color:var(--mid);font-weight:400">(internal / order note)</span>
                        </div>
                        <textarea name="remark" id="remark" class="form-control"
                                  placeholder="Internal notes, agent remarks, follow-up info…">{{ old('remark') }}</textarea>
                        <div class="helper-text">Visible to seller team only</div>
                    </div>

                    {{-- Remark 2 --}}
                    <div class="remark-card" style="border-color:#fde68a;">
                        <div class="remark-card-title">
                            <span class="material-icons-round" style="font-size:16px;color:var(--warning)">local_shipping</span>
                            Remark 2
                            <span style="font-size:11px;color:var(--mid);font-weight:400">(delivery / driver note)</span>
                        </div>
                        <textarea name="delivery_remark" id="delivery_remark" class="form-control"
                                  placeholder="Delivery instructions, landmark, fragile, special handling…">{{ old('delivery_remark') }}</textarea>
                        <div class="helper-text">Passed to delivery call & courier</div>
                    </div>
                </div>

                {{-- Form actions --}}
                <div class="form-actions">
                    <button type="button" id="printBtn" class="btn btn-secondary" {{ !($productId ?? false) ? 'disabled' : '' }}>
                        <span class="material-icons-round" style="font-size:18px">print</span>Print Order
                    </button>
                    <button type="button" id="submitBtn" class="btn btn-success" {{ !($productId ?? false) ? 'disabled' : '' }}>
                        <span class="material-icons-round" style="font-size:18px">check_circle</span>
                        Create &amp; Confirm Order
                    </button>
                </div>

                <div class="section-divider"></div>

                {{-- ═══════════════════════════════════════════════════════════
                     BULK CSV IMPORT
                ═══════════════════════════════════════════════════════════ --}}
                <div class="bulk-section">
                    <div class="bulk-header">
                        <span class="material-icons-round" style="color:var(--warning)">upload_file</span>
                        Bulk Import Orders (CSV)
                        <span class="ac-bulk" style="font-size:11px;font-weight:600">Goes to Pending Call &amp; Pending Orders</span>
                    </div>

                    {{-- Bulk Step 1 — product --}}
                    <div class="bulk-product-box">
                        <div class="step-label">
                            <span class="material-icons-round" style="font-size:16px;color:var(--warning)">looks_one</span>
                            Step 1 — Select Product for Bulk Import
                        </div>
                        <div class="input-row">
                            <div style="flex:1;min-width:240px">
                                <label class="form-label" style="font-size:13px">Product Code <span class="req">*</span></label>
                                <input type="text" id="bulkProductCode" class="form-control" placeholder="e.g. DX/ITM/0001" autocomplete="off">
                            </div>
                            <button type="button" id="bulkFetchBtn" class="btn btn-warning" style="height:48px">
                                <span class="material-icons-round" style="font-size:18px">search</span>Verify Product
                            </button>
                            <button type="button" id="bulkResetBtn" class="btn btn-danger" style="height:48px;display:none">
                                <span class="material-icons-round" style="font-size:18px">close</span>Clear
                            </button>
                        </div>

                        <div id="bulkProductVerified" class="bulk-product-verified">
                            <div style="display:flex;align-items:center;gap:14px">
                                <img id="bpImg" src="" alt="" style="width:60px;height:60px;border-radius:8px;object-fit:cover;border:2px solid var(--border);display:none">
                                <div id="bpImgPh" style="width:60px;height:60px;border-radius:8px;border:2px dashed var(--border);background:#f1f5f9;display:flex;align-items:center;justify-content:center;color:var(--mid)">
                                    <span class="material-icons-round" style="font-size:26px">inventory_2</span>
                                </div>
                                <div style="flex:1">
                                    <div style="font-size:16px;font-weight:800;color:var(--dark)" id="bpName">—</div>
                                    <div style="display:flex;align-items:center;gap:8px;margin-top:4px;flex-wrap:wrap">
                                        <span class="code-badge"><span class="material-icons-round" style="font-size:13px">tag</span><span id="bpCode">—</span></span>
                                        <span style="font-size:13px;color:#dc2626;font-weight:700">Rs. <span id="bpPrice">0.00</span></span>
                                        <span id="bpStockBadge" class="stock-badge stock-ok">
                                        <span class="material-icons-round" style="font-size:13px">inventory</span>
                                        <span id="bpStock">0</span> in stock
                                    </span>
                                    </div>
                                </div>
                                <div style="text-align:right">
                                    <div style="font-size:11px;color:var(--mid)">CSV will use this product</div>
                                    <div style="font-size:13px;font-weight:700;color:#10b981">✓ Verified</div>
                                </div>
                            </div>
                        </div>

                        <div id="bpStockWarn" style="display:none;margin-top:10px;padding:10px 14px;background:#fef3c7;border:1px solid #f59e0b;border-radius:8px;font-size:13px;color:#92400e">
                            <span class="material-icons-round" style="font-size:15px;vertical-align:middle">warning</span>
                            <span id="bpStockWarnText"></span>
                        </div>
                    </div>

                    {{-- Bulk Step 2 — CSV --}}
                    <div class="bulk-csv-box">
                        <div class="step-label">
                            <span class="material-icons-round" style="font-size:16px;color:var(--warning)">looks_two</span>
                            Step 2 — Upload CSV &amp; Set Defaults
                        </div>
                        <div class="form-grid" style="margin-bottom:0">
                            <div class="form-group">
                                <label class="form-label" style="font-size:13px">CSV File <span class="req">*</span></label>
                                <input type="file" id="csvFile" class="form-control" accept=".csv">
                                <div class="helper-text">
                                    Required columns: <b>customer_name</b>, <b>customer_phone</b><br>
                                    Optional: customer_address, city, platform, remark, delivery_remark
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label" style="font-size:13px">Default Platform</label>
                                <select id="bulkDefaultPlatform" class="form-control">
                                    <option value="fb">Facebook</option>
                                    <option value="ig">Instagram</option>
                                    <option value="tiktok">TikTok</option>
                                    <option value="website">Website</option>
                                    <option value="phone">Phone</option>
                                    <option value="manual">Manual</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Bulk Step 3 — actions --}}
                    <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;margin-bottom:16px">
                        <button type="button" id="parseCsvBtn" class="btn btn-warning" disabled>
                            <span class="material-icons-round" style="font-size:18px">preview</span>Preview &amp; Validate
                        </button>
                        <button type="button" id="bulkImportBtn" class="btn btn-success" disabled>
                            <span class="material-icons-round" style="font-size:18px">cloud_upload</span>Import Now
                        </button>
                        <label style="display:inline-flex;align-items:center;gap:6px;font-size:13px;font-weight:600;color:var(--mid);cursor:pointer">
                            <input type="checkbox" id="allowNonCurfox" style="width:16px;height:16px;accent-color:var(--warning)">
                            Allow non-Curfox cities
                        </label>
                    </div>

                    {{-- Validation summary --}}
                    <div id="valSummaryWrap" style="display:none;margin-bottom:12px">
                        <div class="val-summary" id="valBadges"></div>
                        <div id="valErrList" style="display:none;margin-top:8px;padding:12px;background:#fff5f5;border:1px solid #fecaca;border-radius:8px;max-height:180px;overflow-y:auto;font-size:12px"></div>
                        <p class="val-note" id="valNote" style="margin-top:6px"></p>
                    </div>

                    {{-- Preview table --}}
                    <div id="csvPreviewWrap" style="display:none">
                        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;flex-wrap:wrap;gap:8px">
                            <div class="helper-text" style="margin:0">Preview (first 20 rows) — <b><span id="csvRowCount">0</span> rows</b> total</div>
                            <div id="bpPreviewBadge" style="display:none;padding:4px 12px;background:#d1fae5;color:#065f46;border-radius:999px;font-size:12px;font-weight:700"></div>
                        </div>
                        <div style="overflow:auto;border:1px solid var(--border);border-radius:12px">
                            <table class="preview-table">
                                <thead>
                                <tr>
                                    <th>#</th><th>Customer</th><th>Phone</th>
                                    <th>City (Input)</th><th>Resolved City</th>
                                    <th>State</th><th>Platform</th>
                                </tr>
                                </thead>
                                <tbody id="csvPreviewBody"></tbody>
                            </table>
                        </div>
                    </div>
                </div>{{-- /bulk-section --}}

            </div>{{-- /form-container --}}
        </form>
    </div>
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // ─── Config ──────────────────────────────────────────────────────────────────
        const CITIES_URL      = '{{ route("seller.order.cities.json") }}';
        const BULK_STORE_URL  = '{{ route("seller.order.bulk-store") }}';
        const BULK_VAL_URL    = '{{ route("seller.order.bulk-validate") }}';
        const PRODUCT_URL     = '{{ route("seller.order.product.details", ["code" => "PLACEHOLDER"]) }}';
        const CSRF            = '{{ csrf_token() }}';
        const PENDING_URL     = '{{ route("seller.order.pending") }}';
        const CONFIRMED_URL   = '{{ route("seller.order.confirmed") }}';

        // ─── City data ────────────────────────────────────────────────────────────────
        let CITIES = [], citiesLoaded = false;

        @php
            $sc = [];
            if (!empty($statesWithCities)) {
                foreach ($statesWithCities as $state) {
                    foreach ($state['cities'] ?? [] as $city) {
                        if ($city['is_active'] ?? true) {
                            $sc[] = ['id' => $city['id'], 'name' => $city['name'], 'state_name' => $state['name']];
                        }
                    }
                }
            }
        @endphp
        const SERVER_CITIES = @json($sc);

        if (SERVER_CITIES.length > 0) {
            CITIES = SERVER_CITIES; citiesLoaded = true;
            setApiBadge('ok', CITIES.length + ' cities ready');
        } else {
            loadCities();
        }

        async function loadCities() {
            setApiBadge('loading', 'Loading…');
            try {
                const r = await fetch(CITIES_URL, { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF } });
                const j = await r.json();
                if (j.success && Array.isArray(j.data)) {
                    CITIES = j.data; citiesLoaded = true;
                    setApiBadge('ok', CITIES.length + ' cities ready');
                } else throw new Error();
            } catch { setApiBadge('err', 'Load failed'); }
        }

        function setApiBadge(type, text) {
            const b = document.getElementById('apiStatusBadge'); if (!b) return;
            b.className = 'api-badge ' + type;
            b.innerHTML = (type === 'loading'
                ? '<span class="spinner" style="width:10px;height:10px;border-top-color:currentColor;border-color:rgba(0,0,0,.15)"></span> '
                : (type === 'ok' ? '✓ ' : '✕ ')) + text;
        }

        // ─── Price summary live update ────────────────────────────────────────────────
        function updateSummary() {
            const price = parseFloat(document.getElementById('selling_price').value) || 0;
            const qty   = parseInt(document.getElementById('quantity').value) || 0;
            const total = price * qty;
            document.getElementById('psUnitPrice').textContent = price.toFixed(2);
            document.getElementById('psQty').textContent       = qty;
            document.getElementById('psSubtotal').textContent  = total.toFixed(2);
            document.getElementById('psTotal').textContent     = total.toFixed(2);
            document.getElementById('cod_display').value       = total.toFixed(2);
            document.getElementById('product_price').value     = price;
            if (price > 0 || qty > 0) document.getElementById('priceSummary').classList.add('show');
        }
        document.getElementById('selling_price').addEventListener('input', updateSummary);
        document.getElementById('quantity').addEventListener('input', updateSummary);

        // ─── City autocomplete ────────────────────────────────────────────────────────
        (function() {
            const inp = document.getElementById('district');
            const drop = document.getElementById('cityDrop');
            const clr  = document.getElementById('cityClearBtn');
            const hlp  = document.getElementById('cityHelper');
            let idx = -1, timer, matches = [];

            const esc = s => String(s ?? '').replace(/[&<>"']/g, m =>
                ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m]));
            const hi = (t, q) => q ? esc(t).replace(new RegExp('(' + q.replace(/[.*+?^${}()|[\]\\]/g,'\\$&') + ')','gi'), '<mark>$1</mark>') : esc(t);

            function open(m, q) {
                matches = m; idx = -1; drop.innerHTML = '';
                if (!citiesLoaded) { drop.innerHTML = '<div style="padding:14px;font-size:13px;color:var(--mid)">Loading cities…</div>'; drop.classList.add('open'); return; }
                if (!m.length) { drop.innerHTML = '<div class="city-empty">No cities found for "' + esc(q) + '"</div>'; }
                else {
                    const f = document.createDocumentFragment();
                    m.slice(0, 80).forEach((c, i) => {
                        const el = document.createElement('div');
                        el.className = 'city-item'; el.dataset.i = i;
                        el.innerHTML = '<span class="material-icons-round" style="font-size:14px;color:var(--mid)">location_on</span>' + hi(c.name, q) + '<span class="st">' + esc(c.state_name) + '</span>';
                        el.addEventListener('mousedown', e => { e.preventDefault(); pick(m[+el.dataset.i]); });
                        f.appendChild(el);
                    });
                    drop.appendChild(f);
                }
                drop.classList.add('open');
            }
            function close() { drop.classList.remove('open'); drop.innerHTML = ''; idx = -1; matches = []; }
            function pick(c) {
                inp.value = c.name;
                document.getElementById('destination_city_id').value    = c.id;
                document.getElementById('destination_city_name').value  = c.name;
                document.getElementById('destination_state_name').value = c.state_name;
                clr.classList.add('on');
                hlp.innerHTML = '<span class="city-selected-badge"><span class="material-icons-round" style="font-size:12px">check_circle</span>' + esc(c.name) + ' · ' + esc(c.state_name) + '</span>';
                close();
            }
            function clear() {
                inp.value = '';
                document.getElementById('destination_city_id').value = '';
                document.getElementById('destination_city_name').value = '';
                document.getElementById('destination_state_name').value = '';
                clr.classList.remove('on');
                hlp.textContent = 'Cities loaded from Curfox delivery API';
                close(); inp.focus();
            }
            function search(q) {
                if (!q) { close(); return; }
                const lq = q.toLowerCase().trim();
                const ex=[], st=[], co=[];
                for (const c of CITIES) {
                    const l = (c.name || '').toLowerCase();
                    if (l === lq) ex.push(c);
                    else if (l.startsWith(lq)) st.push(c);
                    else if (l.includes(lq)) co.push(c);
                }
                open([...ex, ...st, ...co], q);
            }
            inp.addEventListener('input', function() {
                clr.classList.toggle('on', this.value.length > 0);
                document.getElementById('destination_city_id').value = '';
                document.getElementById('destination_city_name').value = '';
                document.getElementById('destination_state_name').value = '';
                clearTimeout(timer); timer = setTimeout(() => search(this.value), 120);
            });
            inp.addEventListener('keydown', function(e) {
                const items = drop.querySelectorAll('.city-item');
                if (!drop.classList.contains('open')) return;
                if (e.key === 'ArrowDown') { e.preventDefault(); idx = Math.min(idx+1, items.length-1); items.forEach((el,i) => el.classList.toggle('active', i===idx)); }
                if (e.key === 'ArrowUp')   { e.preventDefault(); idx = Math.max(idx-1, -1); items.forEach((el,i) => el.classList.toggle('active', i===idx)); }
                if (e.key === 'Enter')     { e.preventDefault(); if (idx >= 0 && matches[idx]) pick(matches[idx]); }
                if (e.key === 'Escape')    close();
            });
            inp.addEventListener('blur', () => setTimeout(close, 200));
            clr.addEventListener('click', clear);
        })();

        // ─── Product fetch ────────────────────────────────────────────────────────────
        let currentProduct = null;

        document.getElementById('fetchProductBtn').addEventListener('click', () => {
            const code = document.getElementById('searchProductCode').value.trim();
            if (!code) { Swal.fire({ icon:'error', title:'Missing Code', text:'Please enter a product code.', confirmButtonColor:'#4f46e5' }); return; }
            fetchProduct(code);
        });
        document.getElementById('searchProductCode').addEventListener('keypress', e => {
            if (e.key === 'Enter') { e.preventDefault(); document.getElementById('fetchProductBtn').click(); }
        });

        async function fetchProduct(code) {
            const btn = document.getElementById('fetchProductBtn'), orig = btn.innerHTML;
            btn.disabled = true; btn.innerHTML = '<span class="spinner"></span> Fetching…';
            try {
                const url = PRODUCT_URL.replace('PLACEHOLDER', encodeURIComponent(code));
                const res = await fetch(url, { headers: { 'X-Requested-With':'XMLHttpRequest', 'Accept':'application/json', 'X-CSRF-TOKEN':CSRF } });
                const ct = res.headers.get('content-type');
                if (!ct || !ct.includes('application/json')) throw new Error('Server returned non-JSON response.');
                const data = await res.json();
                if (!res.ok) throw new Error(data.message || `HTTP ${res.status}`);
                if (data.success && data.product) {
                    renderProduct(data.product);
                    Swal.fire({ icon:'success', title:'Product Found!', text:data.product.name, timer:1800, showConfirmButton:false });
                } else throw new Error(data.message || 'Product not found');
            } catch (err) {
                Swal.fire({ icon:'error', title:'Not Found', text:err.message, confirmButtonColor:'#4f46e5' });
                resetForm();
            } finally { btn.disabled = false; btn.innerHTML = orig; }
        }

        function renderProduct(p) {
            currentProduct = p;
            const card = document.getElementById('productCard');
            card.style.display = 'block'; card.classList.add('show');
            document.getElementById('resetBtn').style.display = 'inline-flex';

            document.getElementById('product_code').value  = p.code;
            document.getElementById('product_id').value    = p.id;
            document.getElementById('product_name').value  = p.name;
            document.getElementById('product_price').value = p.display_price || p.price;
            document.getElementById('product_image').value = p.image || '';
            document.getElementById('dpName').textContent  = p.name;
            document.getElementById('dpCode').textContent  = p.code;
            document.getElementById('dpPrice').textContent = (parseFloat(p.display_price)||0).toFixed(2);

            const stock = parseInt(p.qty) || 0;
            document.getElementById('dpStock').textContent       = stock;
            document.getElementById('availableStock').textContent = stock;

            const badge = document.getElementById('stockBadge');
            if (stock <= 0)              { badge.className = 'stock-badge stock-out'; badge.innerHTML = '<span class="material-icons-round" style="font-size:14px">cancel</span> Out of Stock'; }
            else if (stock <= (p.min_qty || 10)) { badge.className = 'stock-badge stock-low'; badge.innerHTML = '<span class="material-icons-round" style="font-size:14px">warning</span> Low Stock'; }
            else                         { badge.className = 'stock-badge stock-ok';  badge.innerHTML = '<span class="material-icons-round" style="font-size:14px">check_circle</span> Available'; }

            const img = document.getElementById('productImg'), ph = document.getElementById('productImgPh');
            if (p.image) { img.src = p.image; img.style.display = 'block'; ph.style.display = 'none'; img.onerror = () => { img.style.display='none'; ph.style.display='flex'; }; }
            else { img.style.display = 'none'; ph.style.display = 'flex'; }

            const qty = document.getElementById('quantity');
            if (stock > 0) { qty.max = stock; qty.disabled = false; if (parseInt(qty.value) > stock) qty.value = 1; }
            else { qty.disabled = true; qty.value = 0; }

            const sp = document.getElementById('selling_price');
            sp.value = p.display_price || p.price || 0; sp.disabled = false;

            document.getElementById('submitBtn').disabled = false;
            document.getElementById('printBtn').disabled  = false;
            document.getElementById('priceSummary').classList.add('show');
            updateSummary();
        }

        document.getElementById('resetBtn').addEventListener('click', () => {
            Swal.fire({ title:'Reset Form?', text:'All product and order info will be cleared.', icon:'warning',
                showCancelButton:true, confirmButtonColor:'#ef4444', cancelButtonColor:'#64748b', confirmButtonText:'Yes, Reset'
            }).then(r => { if (r.isConfirmed) resetForm(); });
        });

        function resetForm() {
            currentProduct = null;
            document.getElementById('productCard').style.display = 'none';
            document.getElementById('productCard').classList.remove('show');
            document.getElementById('priceSummary').classList.remove('show');
            document.getElementById('resetBtn').style.display = 'none';
            document.getElementById('searchProductCode').value = '';
            ['product_code','product_id','product_name','product_price','product_image',
                'customer_name','customer_phone','customer_phone_2','customer_address',
                'quantity','remark','delivery_remark',
                'destination_city_id','destination_city_name','destination_state_name'
            ].forEach(id => { const el = document.getElementById(id); if (el) el.value = (id === 'quantity') ? '1' : ''; });
            document.getElementById('district').value        = '';
            document.getElementById('platform').value        = '';
            document.getElementById('payment_method').value  = '';
            document.getElementById('selling_price').value   = '';
            document.getElementById('selling_price').disabled = true;
            document.getElementById('cod_display').value     = '0.00';
            document.getElementById('submitBtn').disabled    = true;
            document.getElementById('printBtn').disabled     = true;
            document.getElementById('dpName').textContent    = '-';
            document.getElementById('dpCode').textContent    = '-';
            document.getElementById('dpPrice').textContent   = '0.00';
            document.getElementById('dpStock').textContent   = '0';
            document.getElementById('availableStock').textContent = '0';
            document.getElementById('productImg').style.display  = 'none';
            document.getElementById('productImgPh').style.display = 'flex';
            document.getElementById('quantity').disabled = true;
            updateSummary();
        }

        // ─── Single order submit (AUTO-CONFIRM) ──────────────────────────────────────
        document.getElementById('submitBtn').addEventListener('click', e => {
            e.preventDefault();
            if (!currentProduct || !document.getElementById('product_id').value) {
                Swal.fire({ icon:'error', title:'No Product', text:'Please fetch a product first.', confirmButtonColor:'#4f46e5' }); return;
            }

            const name    = document.getElementById('customer_name').value.trim();
            const phone   = document.getElementById('customer_phone').value.trim();
            const address = document.getElementById('customer_address').value.trim();
            const payment = document.getElementById('payment_method').value;
            const city    = document.getElementById('district').value.trim();
            const cityId  = document.getElementById('destination_city_id').value;
            const qty     = parseInt(document.getElementById('quantity').value) || 0;
            const price   = parseFloat(document.getElementById('selling_price').value) || 0;
            const stock   = parseInt(currentProduct.qty) || 0;

            if (!name)    { Swal.fire({ icon:'error', title:'Missing', text:'Enter customer name.' }); return; }
            if (!phone)   { Swal.fire({ icon:'error', title:'Missing', text:'Enter customer phone.' }); return; }
            if (!address) { Swal.fire({ icon:'error', title:'Missing', text:'Enter delivery address.' }); return; }
            if (!payment) { Swal.fire({ icon:'error', title:'Missing', text:'Select payment method.' }); return; }
            if (!city)    { Swal.fire({ icon:'error', title:'Missing', text:'Select a delivery city.' }); return; }
            if (!cityId)  { Swal.fire({ icon:'error', title:'Invalid City', text:'Please select a city from the dropdown list.' }); return; }
            if (qty <= 0) { Swal.fire({ icon:'error', title:'Invalid Qty', text:'Enter a valid quantity.' }); return; }
            if (qty > stock) { Swal.fire({ icon:'error', title:'Exceeds Stock', text:`Only ${stock} units available.` }); return; }
            if (price <= 0) { Swal.fire({ icon:'error', title:'Invalid Price', text:'Enter a valid selling price.' }); return; }

            const total = (price * qty).toFixed(2);
            const r1 = document.getElementById('remark').value.trim();
            const r2 = document.getElementById('delivery_remark').value.trim();

            Swal.fire({
                title: 'Create & Confirm Order?',
                html: `<div style="text-align:left;font-size:14px;line-height:2">
            <div style="background:#f0fdf4;border:1px solid #86efac;border-radius:8px;padding:12px;margin-bottom:12px">
                <div style="font-weight:700;color:#15803d">✓ This order will be auto-confirmed</div>
                <div style="font-size:12px;color:#64748b">Delivery call created immediately · call_status = Confirmed</div>
            </div>
            <p><b>Customer:</b> ${name}</p>
            <p><b>Product:</b> ${currentProduct.name}</p>
            <p><b>Qty:</b> ${qty} × Rs.${price.toFixed(2)} = <b style="color:#15803d">Rs.${total}</b></p>
            <p><b>City:</b> ${city}</p>
            ${r1 ? `<p><b>Remark 1:</b> ${r1}</p>` : ''}
            ${r2 ? `<p><b>Remark 2:</b> ${r2}</p>` : ''}
        </div>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#64748b',
                confirmButtonText: '✓ Confirm & Create',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    const form = document.getElementById('orderForm');
                    const fd   = new FormData(form);
                    fd.set('product_price', price);
                    fd.set('selling_price', price);
                    return fetch(form.action, {
                        method: 'POST', body: fd,
                        headers: { 'X-Requested-With':'XMLHttpRequest', 'X-CSRF-TOKEN':CSRF, 'Accept':'application/json' }
                    }).then(r => { if (!r.ok) return r.json().then(e => { throw new Error(e.message || 'Error'); }); return r.json(); })
                        .catch(err => { Swal.showValidationMessage('Error: ' + err.message); });
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then(result => {
                if (result.isConfirmed && result.value?.success) {
                    Swal.fire({
                        icon: 'success', title: 'Order Confirmed!',
                        html: `<p>${result.value.message}</p><p style="font-size:13px;color:#64748b">Delivery call created automatically</p>`,
                        timer: 2200, showConfirmButton: false
                    }).then(() => window.location.href = result.value.redirect_url || CONFIRMED_URL);
                }
            });
        });

        // ─── Print ────────────────────────────────────────────────────────────────────
        document.getElementById('printBtn').addEventListener('click', () => {
            if (!currentProduct) return;
            const n = document.getElementById('customer_name').value;
            const p = document.getElementById('customer_phone').value;
            if (!n || !p) { Swal.fire({ icon:'error', title:'Missing Info', text:'Fill customer name and phone.' }); return; }
            const city    = document.getElementById('district').value;
            const qty     = document.getElementById('quantity').value;
            const state   = document.getElementById('destination_state_name').value;
            const price   = document.getElementById('selling_price').value || currentProduct.display_price;
            const address = document.getElementById('customer_address').value;
            const r1      = document.getElementById('remark').value;
            const r2      = document.getElementById('delivery_remark').value;
            const total   = (parseFloat(price) * parseInt(qty||1)).toFixed(2);

            const pw = window.open('', '', 'height=750,width=850');
            pw.document.write(`<!DOCTYPE html><html><head><title>Order Print</title>
    <style>body{font-family:Arial,sans-serif;padding:28px;color:#0f172a}h1{color:#4f46e5;margin-bottom:4px}
    .badge{display:inline-block;background:#d1fae5;color:#065f46;padding:3px 12px;border-radius:999px;font-size:12px;font-weight:700;margin-bottom:18px}
    table{width:100%;border-collapse:collapse}td{padding:10px;border-bottom:1px solid #e2e8f0}
    td:first-child{font-weight:bold;width:38%;color:#64748b}
    .total{font-size:22px;font-weight:800;color:#15803d}
    .rbox{background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:14px;margin-top:16px}
    .rtitle{font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;margin-bottom:6px}
    </style></head><body>`);
            pw.document.write(`<h1>Order Receipt</h1><div class="badge">✓ CONFIRMED</div>`);
            pw.document.write(`<p style="color:#64748b;font-size:13px">Date: ${new Date().toLocaleDateString('en-GB')}</p><table>`);
            pw.document.write(`<tr><td>Product</td><td>${currentProduct.name}</td></tr>`);
            pw.document.write(`<tr><td>Code</td><td>${currentProduct.code}</td></tr>`);
            pw.document.write(`<tr><td>Unit Price</td><td>Rs. ${parseFloat(price).toFixed(2)}</td></tr>`);
            pw.document.write(`<tr><td>Quantity</td><td>${qty}</td></tr>`);
            pw.document.write(`<tr><td>COD Total</td><td class="total">Rs. ${total}</td></tr>`);
            pw.document.write(`<tr><td>Customer</td><td>${n}</td></tr>`);
            pw.document.write(`<tr><td>Phone</td><td>${p}</td></tr>`);
            if (address) pw.document.write(`<tr><td>Address</td><td>${address}</td></tr>`);
            if (city)    pw.document.write(`<tr><td>City</td><td>${city}${state ? ', '+state : ''}</td></tr>`);
            pw.document.write('</table>');
            if (r1) pw.document.write(`<div class="rbox"><div class="rtitle">Remark 1 (Internal)</div>${r1}</div>`);
            if (r2) pw.document.write(`<div class="rbox" style="border-color:#fde68a"><div class="rtitle">Remark 2 (Delivery)</div>${r2}</div>`);
            pw.document.write('</body></html>');
            pw.document.close(); pw.focus(); setTimeout(() => { pw.print(); pw.close(); }, 300);
        });

        // ─── CSV Bulk Import ──────────────────────────────────────────────────────────
        let BULK_LEADS = [], bulkProduct = null;

        const escH = s => String(s ?? '').replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m]));

        // Bulk product fetch
        document.getElementById('bulkFetchBtn').addEventListener('click', bulkFetch);
        document.getElementById('bulkProductCode').addEventListener('keypress', e => { if (e.key==='Enter') { e.preventDefault(); bulkFetch(); } });
        document.getElementById('bulkResetBtn').addEventListener('click', bulkReset);

        async function bulkFetch() {
            const code = document.getElementById('bulkProductCode').value.trim();
            if (!code) { Swal.fire({ icon:'error', title:'Missing Code', text:'Enter a product code.', confirmButtonColor:'#f59e0b' }); return; }
            const btn = document.getElementById('bulkFetchBtn'), orig = btn.innerHTML;
            btn.disabled = true; btn.innerHTML = '<span class="spinner" style="border-top-color:#fff"></span> Verifying…';
            try {
                const res = await fetch(PRODUCT_URL.replace('PLACEHOLDER', encodeURIComponent(code)), {
                    headers: { 'X-Requested-With':'XMLHttpRequest', 'Accept':'application/json', 'X-CSRF-TOKEN':CSRF }
                });
                const data = await res.json();
                if (!data.success || !data.product) throw new Error(data.message || 'Not found');
                bulkProduct = data.product;
                renderBulkProduct(data.product);
                document.getElementById('parseCsvBtn').disabled = false;
                Swal.fire({ icon:'success', title:'Product Verified!', html:`<b>${escH(data.product.name)}</b><br>Stock: <b>${data.product.qty}</b>`, timer:2000, showConfirmButton:false });
            } catch (err) {
                bulkProduct = null; hideBulkProduct();
                document.getElementById('parseCsvBtn').disabled = true;
                document.getElementById('bulkImportBtn').disabled = true;
                Swal.fire({ icon:'error', title:'Not Found', text:err.message, confirmButtonColor:'#f59e0b' });
            } finally { btn.disabled = false; btn.innerHTML = orig; }
        }

        function renderBulkProduct(p) {
            const card = document.getElementById('bulkProductVerified'); card.style.display = 'block';
            document.getElementById('bpName').textContent  = p.name;
            document.getElementById('bpCode').textContent  = p.code;
            document.getElementById('bpPrice').textContent = (parseFloat(p.display_price)||0).toFixed(2);
            document.getElementById('bpStock').textContent = p.qty;
            document.getElementById('bulkResetBtn').style.display = 'inline-flex';
            const badge = document.getElementById('bpStockBadge'), qty = parseInt(p.qty)||0;
            badge.className = 'stock-badge ' + (qty<=0 ? 'stock-out' : qty<=10 ? 'stock-low' : 'stock-ok');
            const img = document.getElementById('bpImg'), ph = document.getElementById('bpImgPh');
            if (p.image) { img.src=p.image; img.style.display='block'; ph.style.display='none'; img.onerror=()=>{img.style.display='none';ph.style.display='flex';}; }
            else { img.style.display='none'; ph.style.display='flex'; }
            checkBulkStock();
        }

        function hideBulkProduct() {
            document.getElementById('bulkProductVerified').style.display = 'none';
            document.getElementById('bulkResetBtn').style.display = 'none';
            document.getElementById('bpStockWarn').style.display = 'none';
        }

        function bulkReset() {
            bulkProduct = null; hideBulkProduct();
            document.getElementById('bulkProductCode').value = '';
            document.getElementById('parseCsvBtn').disabled = true;
            document.getElementById('bulkImportBtn').disabled = true;
            document.getElementById('csvPreviewWrap').style.display = 'none';
            document.getElementById('valSummaryWrap').style.display = 'none';
            BULK_LEADS = [];
        }

        function checkBulkStock() {
            if (!bulkProduct) return;
            const stock = parseInt(bulkProduct.qty)||0, n = BULK_LEADS.length;
            const warn = document.getElementById('bpStockWarn'), txt = document.getElementById('bpStockWarnText');
            if (n > 0 && n > stock) { warn.style.display='block'; txt.textContent=`Only ${stock} in stock but CSV has ${n} rows.`; }
            else warn.style.display = 'none';
        }

        // CSV parse & validate
        document.getElementById('parseCsvBtn').addEventListener('click', async () => {
            if (!bulkProduct) { Swal.fire({ icon:'error', title:'No Product', text:'Verify a product first.' }); return; }
            const file = document.getElementById('csvFile').files[0];
            if (!file) { Swal.fire({ icon:'error', title:'No File', text:'Please select a CSV file.' }); return; }

            BULK_LEADS = parseCsv(await file.text());
            document.getElementById('bulkImportBtn').disabled = true;
            if (!BULK_LEADS.length) { Swal.fire({ icon:'error', title:'Empty CSV', text:'No valid rows. Ensure customer_name and customer_phone columns exist.' }); return; }
            checkBulkStock();

            const badge = document.getElementById('bpPreviewBadge');
            badge.style.display = 'inline-flex';
            badge.textContent = `✓ ${bulkProduct.name} (${bulkProduct.code}) — Stock: ${bulkProduct.qty}`;

            const btn = document.getElementById('parseCsvBtn'), orig = btn.innerHTML;
            btn.disabled = true; btn.innerHTML = '<span class="spinner" style="border-top-color:#fff"></span> Validating…';

            try {
                const fd = new FormData(); fd.append('leads', JSON.stringify(BULK_LEADS));
                const res = await fetch(BULK_VAL_URL, { method:'POST', headers:{'X-Requested-With':'XMLHttpRequest','X-CSRF-TOKEN':CSRF,'Accept':'application/json'}, body:fd });
                if (!res.ok) throw new Error(`HTTP ${res.status}`);
                const json = await res.json();
                if (json.success) {
                    renderPreview(BULK_LEADS, json.results);
                    renderValSummary(json);
                    json.results.forEach((r,i) => {
                        if (r.status==='ok' && r.resolved && BULK_LEADS[i]) {
                            BULK_LEADS[i]._city_id    = r.resolved.id;
                            BULK_LEADS[i]._city_name  = r.resolved.city_name;
                            BULK_LEADS[i]._state_name = r.resolved.state_name;
                        }
                    });
                    document.getElementById('bulkImportBtn').disabled = false;
                } else { Swal.fire({ icon:'error', title:'Validation Error', text:json.message }); renderPreviewBasic(BULK_LEADS); document.getElementById('bulkImportBtn').disabled = false; }
            } catch (err) { renderPreviewBasic(BULK_LEADS); document.getElementById('bulkImportBtn').disabled = false; }
            finally { btn.disabled = false; btn.innerHTML = orig; }
        });

        function parseCsvLine(l) {
            const r=[]; let cur='', inQ=false;
            for (let i=0;i<l.length;i++) { const c=l[i],n=l[i+1];
                if (inQ) { if(c==='"'&&n==='"'){cur+='"';i++;}else if(c==='"'){inQ=false;}else{cur+=c;} }
                else { if(c==='"')inQ=true; else if(c===','){r.push(cur.trim());cur='';}else{cur+=c;} }
            } r.push(cur.trim()); return r;
        }

        function parseCsv(text) {
            const lines = text.replace(/\r\n/g,'\n').replace(/\r/g,'\n').split('\n').filter(l=>l.trim());
            if (lines.length < 2) return [];
            const hdr  = parseCsvLine(lines[0].replace(/^\uFEFF/,'')).map(h=>h.toLowerCase().trim().replace(/\s+/g,'_').replace(/[^a-z0-9_]/g,''));
            const idx  = n => hdr.indexOf(n);
            const iN=idx('customer_name'), iP=idx('customer_phone'), iA=idx('customer_address');
            const iC=idx('city'), iCN=idx('destination_city_name'), iPl=idx('platform');
            const iR1=idx('remark'), iR2=idx('delivery_remark');
            if (iN===-1||iP===-1) return [];
            const leads=[];
            for (let r=1;r<lines.length;r++) {
                const c=parseCsvLine(lines[r]);
                const name=(c[iN]??'').trim(), phone=(c[iP]??'').trim();
                if (!name||!phone) continue;
                const city=((iCN!==-1?c[iCN]:'')||(iC!==-1?c[iC]:'')||'').trim();
                leads.push({
                    customer_name:name, customer_phone:phone,
                    customer_address:(iA!==-1?(c[iA]??'').trim():'')||null,
                    district:city||null, destination_city_name:city||null, destination_state_name:null,
                    platform:(iPl!==-1?(c[iPl]??'').trim():'')||null,
                    remark:(iR1!==-1?(c[iR1]??'').trim():'')||null,
                    delivery_remark:(iR2!==-1?(c[iR2]??'').trim():'')||null,
                });
            }
            return leads;
        }

        function renderValSummary(json) {
            const wrap=document.getElementById('valSummaryWrap'),badges=document.getElementById('valBadges');
            const errList=document.getElementById('valErrList'),note=document.getElementById('valNote');
            wrap.style.display='block';
            const ok=json.results.filter(r=>r.status==='ok').length, bad=json.invalid_count||0, miss=json.missing_count||0;
            badges.innerHTML=`<span class="val-badge vb-ok">✓ ${ok} matched</span>`
                +(bad>0?`<span class="val-badge vb-err">✕ ${bad} invalid</span>`:'')
                +(miss>0?`<span class="val-badge vb-warn">⚠ ${miss} missing</span>`:'');
            if (bad>0) {
                errList.style.display='block';
                errList.innerHTML='<strong style="color:#991b1b">Invalid cities:</strong><br>'+json.results.filter(r=>r.status==='invalid').map(r=>`Row ${r.row}: "<b>${escH(r.input)}</b>"`).join('<br>');
            } else errList.style.display='none';
            note.textContent = json.all_valid ? '✓ All cities valid — ready to import.' : '';
            if (!json.all_valid) {
                const allow=document.getElementById('allowNonCurfox')?.checked??false;
                note.innerHTML = allow
                    ? '<span style="color:var(--warning)">⚠ Non-Curfox cities saved as plain text.</span>'
                    : '<span style="color:#991b1b">Invalid rows will be skipped. Enable "Allow non-Curfox cities" to import them.</span>';
            }
        }

        document.getElementById('allowNonCurfox')?.addEventListener('change', () => { applyNonCurfoxToggle(); });

        function renderPreview(leads, vr) {
            document.getElementById('csvPreviewWrap').style.display='block';
            document.getElementById('csvRowCount').textContent=leads.length;
            const tbody=document.getElementById('csvPreviewBody'); tbody.innerHTML='';
            const lim=Math.min(leads.length,20);
            for (let i=0;i<lim;i++) {
                const row=leads[i],v=vr[i],st=v?v.status:'?',res=v?v.resolved:null;
                let sh='',rs='-';
                if (st==='ok'&&res)  { sh=`<span class="cs-ok">✓ ${escH(res.city_name)}</span>`; rs=res.state_name; }
                else if (st==='invalid') sh=`<span class="cs-bad" id="ci-${i}">✕ Not in Curfox</span><span class="cs-ok" id="ca-${i}" style="display:none">⚠ As-is: ${escH(row.district||'')}</span>`;
                else if (st==='missing') sh=`<span class="cs-miss">⚠ Missing</span>`;
                else sh=`<span style="color:var(--mid)">—</span>`;
                const tr=document.createElement('tr'); tr.dataset.cs=st;
                tr.innerHTML=`<td>${i+1}</td><td>${escH(row.customer_name)}</td><td>${escH(row.customer_phone)}</td><td>${escH(row.district||'-')}</td><td>${sh}</td><td>${escH(rs)}</td><td>${escH(row.platform||'-')}</td>`;
                tbody.appendChild(tr);
            }
            applyNonCurfoxToggle();
        }

        function renderPreviewBasic(leads) {
            document.getElementById('csvPreviewWrap').style.display='block';
            document.getElementById('csvRowCount').textContent=leads.length;
            const tbody=document.getElementById('csvPreviewBody'); tbody.innerHTML='';
            const lim=Math.min(leads.length,20);
            for (let i=0;i<lim;i++) {
                const row=leads[i],tr=document.createElement('tr');
                tr.innerHTML=`<td>${i+1}</td><td>${escH(row.customer_name)}</td><td>${escH(row.customer_phone)}</td><td>${escH(row.district||'-')}</td><td>—</td><td>—</td><td>${escH(row.platform||'-')}</td>`;
                tbody.appendChild(tr);
            }
        }

        function applyNonCurfoxToggle() {
            const allow=document.getElementById('allowNonCurfox')?.checked??false;
            document.querySelectorAll('#csvPreviewBody tr[data-cs="invalid"]').forEach(tr => {
                const ci=tr.querySelector('[id^="ci-"]'),ca=tr.querySelector('[id^="ca-"]');
                if(ci) ci.style.display=allow?'none':'inline';
                if(ca) ca.style.display=allow?'inline':'none';
            });
        }

        // Bulk import submit
        document.getElementById('bulkImportBtn').addEventListener('click', async () => {
            if (!bulkProduct) { Swal.fire({ icon:'error', title:'No Product', text:'Verify product first.' }); return; }
            if (!BULK_LEADS.length) { Swal.fire({ icon:'error', title:'No Data', text:'Click Preview & Validate first.' }); return; }

            const stock = parseInt(bulkProduct.qty)||0;
            if (stock < BULK_LEADS.length) {
                const go = await Swal.fire({
                    icon:'warning', title:'Insufficient Stock',
                    html:`Stock: <b>${stock}</b> | CSV rows: <b>${BULK_LEADS.length}</b><br>Only first <b>${stock}</b> orders will succeed.`,
                    showCancelButton:true, confirmButtonText:'Import Anyway', confirmButtonColor:'#f59e0b', cancelButtonColor:'#64748b'
                });
                if (!go.isConfirmed) return;
            }

            const leadsToSend = BULK_LEADS.map(l => {
                const o={...l};
                if (l._city_id) { o.destination_city_id=l._city_id; o.destination_city_name=l._city_name; o.destination_state_name=l._state_name; o.district=l._city_name; }
                delete o._city_id; delete o._city_name; delete o._state_name;
                return o;
            });

            const plat = document.getElementById('bulkDefaultPlatform').value;
            const date = document.getElementById('order_date').value;
            const anc  = document.getElementById('allowNonCurfox')?.checked ? '1' : '0';

            Swal.fire({
                title:'Confirm Bulk Import',
                html:`<div style="text-align:left;font-size:14px;line-height:2">
            <div style="background:#fef3c7;border:1px solid #fde68a;border-radius:8px;padding:10px;margin-bottom:10px;font-size:12px;color:#92400e">
                ⚠ Bulk orders go to <b>Pending Call</b> — requires manual confirmation
            </div>
            <p><b>Orders:</b> ${leadsToSend.length}</p>
            <p><b>Product:</b> ${escH(bulkProduct.name)} (${escH(bulkProduct.code)})</p>
            <p><b>Default Platform:</b> ${escH(plat)}</p>
            ${anc==='1'?'<p style="color:#f59e0b;font-size:12px">⚠ Non-Curfox cities saved as-is</p>':''}
        </div>`,
                icon:'question', showCancelButton:true, confirmButtonText:'Yes, Import All',
                confirmButtonColor:'#10b981', cancelButtonColor:'#64748b', showLoaderOnConfirm:true,
                preConfirm: async () => {
                    try {
                        const fd=new FormData();
                        fd.append('product_code', bulkProduct.code);
                        fd.append('default_platform', plat);
                        fd.append('date', date);
                        fd.append('leads', JSON.stringify(leadsToSend));
                        fd.append('allow_non_curfox', anc);
                        const res=await fetch(BULK_STORE_URL, { method:'POST', headers:{'X-Requested-With':'XMLHttpRequest','X-CSRF-TOKEN':CSRF,'Accept':'application/json'}, body:fd });
                        const json=await res.json();
                        if (!res.ok||!json.success) throw new Error(json.message||'Import failed');
                        return json;
                    } catch(e) { Swal.showValidationMessage(e.message); return false; }
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then(r => {
                if (r.isConfirmed && r.value?.success) {
                    Swal.fire({
                        icon:'success', title:'Import Complete!',
                        html:`<p>${escH(r.value.message)}</p>`+(r.value.errors?.length?`<div style="text-align:left;color:#ef4444;font-size:12px;max-height:120px;overflow-y:auto">${r.value.errors.slice(0,10).map(x=>escH(x)).join('<br>')}</div>`:''),
                        confirmButtonColor:'#4f46e5'
                    }).then(() => window.location.href = r.value.redirect_url || PENDING_URL);
                }
            });
        });
    </script>
@endpush
