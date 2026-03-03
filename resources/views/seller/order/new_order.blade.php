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
        body { font-family: var(--sans); background: var(--bg); }
        .order-wrapper { padding: 32px; max-width: 1400px; margin: 0 auto; }
        @media (max-width: 768px) { .order-wrapper { padding: 16px; } }

        .page-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:28px; flex-wrap:wrap; gap:16px; }
        .page-title  { font-size:26px; font-weight:800; color:var(--dark); display:flex; align-items:center; gap:10px; margin:0; }
        .header-actions { display:flex; gap:12px; flex-wrap:wrap; }

        .form-container { background:var(--surface); border:1px solid var(--border); border-radius:var(--radius-xl); padding:32px; box-shadow:var(--shadow-sm); }
        @media (max-width: 768px) { .form-container { padding:20px; } }

        .form-grid { display:grid; grid-template-columns:1fr 1fr; gap:24px; margin-bottom:24px; }
        @media (max-width: 768px) { .form-grid { grid-template-columns:1fr; gap:16px; } }

        .form-group  { margin-bottom:0; }
        .form-label  { display:block; font-size:14px; font-weight:600; color:var(--dark); margin-bottom:8px; }
        .form-label .required { color:var(--danger); margin-left:2px; }

        .form-control { width:100%; padding:12px 16px; border:2px solid var(--border); border-radius:var(--radius-md); font-family:var(--sans); font-size:14px; transition:all .2s; background:var(--surface); box-sizing:border-box; }
        .form-control:focus { outline:none; border-color:var(--primary); box-shadow:0 0 0 3px rgba(79,70,229,.1); }
        .form-control:disabled { background:#f1f5f9; cursor:not-allowed; }
        textarea.form-control { resize:vertical; min-height:100px; }

        .phone-pair { display:grid; grid-template-columns:1fr 1fr; gap:10px; }
        @media (max-width: 480px) { .phone-pair { grid-template-columns:1fr; } }

        .product-search-section { background:linear-gradient(135deg,#fff 0%,#f8fafc 100%); border:2px solid var(--border); border-radius:var(--radius-lg); padding:24px; margin-bottom:24px; }
        .search-header { font-size:16px; font-weight:700; color:var(--dark); margin-bottom:16px; display:flex; align-items:center; gap:8px; }
        .input-with-button { display:flex; gap:12px; align-items:flex-start; flex-wrap:wrap; }
        .input-with-button .form-control { flex:1; min-width:240px; }

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

        .product-info-card { background:var(--surface); border:2px solid var(--border); border-radius:var(--radius-lg); padding:20px; margin-bottom:24px; display:none; }
        .product-info-card.show { display:block; animation:slideDown .3s ease-out; }
        @keyframes slideDown { from{opacity:0;transform:translateY(-10px)} to{opacity:1;transform:translateY(0)} }

        .product-info-header { display:flex; align-items:center; gap:16px; margin-bottom:16px; padding-bottom:16px; border-bottom:2px solid var(--border); }
        .product-image-preview    { width:80px; height:80px; border-radius:var(--radius-md); object-fit:cover; border:2px solid var(--border); }
        .product-image-placeholder { width:80px; height:80px; border-radius:var(--radius-md); border:2px dashed var(--border); background:#f1f5f9; display:flex; align-items:center; justify-content:center; color:var(--mid); }
        .product-info-text h4 { font-size:18px; font-weight:700; color:var(--dark); margin:0 0 4px; }
        .product-code-badge { display:inline-flex; align-items:center; gap:6px; padding:4px 12px; background:var(--primary-light); color:var(--primary); border-radius:999px; font-family:var(--mono); font-size:12px; font-weight:600; }
        .product-details-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:16px; }
        @media (max-width:768px) { .product-details-grid { grid-template-columns:1fr; } }
        .detail-item { padding:12px; background:var(--bg); border-radius:var(--radius-md); }
        .detail-label { font-size:12px; color:var(--mid); margin-bottom:4px; }
        .detail-value { font-size:16px; font-weight:700; color:var(--dark); }
        .stock-badge { display:inline-flex; align-items:center; gap:4px; padding:4px 8px; border-radius:999px; font-size:11px; font-weight:600; }
        .stock-available { background:#d1fae5; color:#065f46; }
        .stock-low  { background:#fef3c7; color:#92400e; }
        .stock-out  { background:#fee2e2; color:#991b1b; }

        .form-actions { display:flex; gap:12px; justify-content:flex-end; padding-top:24px; border-top:2px solid var(--border); }
        @media (max-width:576px) { .form-actions { flex-direction:column-reverse; } .btn { width:100%; justify-content:center; } }

        .alert { padding:14px 18px; border-radius:var(--radius-md); margin-bottom:20px; display:flex; align-items:center; gap:10px; }
        .alert-success { background:#d1fae5; border:1px solid #10b981; color:#065f46; }
        .alert-danger  { background:#fee2e2; border:1px solid #ef4444; color:#991b1b; }
        .alert-warning { background:#fef3c7; border:1px solid #f59e0b; color:#92400e; }
        .alert-info    { background:#dbeafe; border:1px solid #3b82f6; color:#1e40af; }
        .spinner { display:inline-block; width:16px; height:16px; border:2px solid rgba(255,255,255,.3); border-radius:50%; border-top-color:#fff; animation:spin .6s linear infinite; }
        @keyframes spin { to { transform:rotate(360deg); } }
        .helper-text { font-size:12px; color:var(--mid); margin-top:4px; }
        .phone-sub-label { font-size:11px; color:var(--mid); font-weight:600; margin-bottom:4px; display:block; }

        /* City Autocomplete */
        .city-autocomplete-wrapper { position:relative; }
        .city-autocomplete-wrapper .form-control { padding-right:40px; }
        .city-clear-btn { position:absolute; right:12px; top:50%; transform:translateY(-50%); background:none; border:none; cursor:pointer; color:var(--mid); padding:0; display:none; align-items:center; justify-content:center; width:20px; height:20px; border-radius:50%; transition:all .2s; z-index:2; }
        .city-clear-btn:hover { background:#fee2e2; color:var(--danger); }
        .city-clear-btn.visible { display:flex; }
        .city-dropdown { position:absolute; top:calc(100% + 4px); left:0; right:0; background:var(--surface); border:2px solid var(--primary); border-radius:var(--radius-md); max-height:220px; overflow-y:auto; z-index:9999; box-shadow:var(--shadow-md); display:none; }
        .city-dropdown.open { display:block; animation:slideDown .15s ease-out; }
        .city-dropdown-item { padding:10px 14px; font-size:14px; color:var(--dark); cursor:pointer; border-bottom:1px solid var(--border); display:flex; align-items:center; gap:8px; transition:background .1s; }
        .city-dropdown-item:last-child { border-bottom:none; }
        .city-dropdown-item:hover,.city-dropdown-item.active { background:var(--primary-light); color:var(--primary); }
        .city-dropdown-item .city-state { font-size:11px; color:var(--mid); margin-left:auto; }
        .city-dropdown-item mark { background:none; color:var(--primary); font-weight:700; padding:0; }
        .city-dropdown-empty { padding:14px; font-size:13px; color:var(--mid); text-align:center; }
        .city-badge-selected { display:inline-flex; align-items:center; gap:6px; padding:4px 10px; background:var(--primary-light); color:var(--primary); border-radius:999px; font-size:12px; font-weight:600; margin-top:6px; }
        .city-dropdown::-webkit-scrollbar { width:6px; }
        .city-dropdown::-webkit-scrollbar-track { background:var(--bg); }
        .city-dropdown::-webkit-scrollbar-thumb { background:var(--border); border-radius:3px; }
        .city-loading { display:flex; align-items:center; gap:8px; padding:14px; font-size:13px; color:var(--mid); }
        .city-loading .spinner { border-top-color:var(--primary); border-color:rgba(79,70,229,.2); }

        /* API badge */
        .api-status { display:inline-flex; align-items:center; gap:6px; font-size:11px; font-weight:600; padding:3px 10px; border-radius:999px; }
        .api-status.ok  { background:#d1fae5; color:#065f46; }
        .api-status.err { background:#fee2e2; color:#991b1b; }
        .api-status.loading { background:#f1f5f9; color:var(--mid); }

        /* CSV Validation */
        .city-status-ok      { color:#065f46; font-weight:600; font-size:12px; }
        .city-status-invalid { color:#991b1b; font-weight:600; font-size:12px; }
        .city-status-missing { color:#92400e; font-weight:600; font-size:12px; }
        .validation-summary  { display:flex; gap:12px; flex-wrap:wrap; margin:10px 0; }
        .val-badge           { padding:4px 12px; border-radius:999px; font-size:12px; font-weight:700; }
        .val-badge-ok        { background:#d1fae5; color:#065f46; }
        .val-badge-warn      { background:#fef3c7; color:#92400e; }
        .val-badge-err       { background:#fee2e2; color:#991b1b; }
        .val-note            { font-size:12px; color:var(--mid); margin-top:4px; }
        .city-resolved-name  { font-size:11px; color:#065f46; }
    </style>
@endpush

@section('content')
    <div class="order-wrapper">

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

        @if(session('error'))
            <div class="alert alert-danger"><span class="material-icons-round">error</span><div>{{ session('error') }}</div></div>
        @endif
        @if(session('success'))
            <div class="alert alert-success"><span class="material-icons-round">check_circle</span><div>{{ session('success') }}</div></div>
        @endif
        @if(isset($apiError))
            <div class="alert alert-warning">
                <span class="material-icons-round">warning</span>
                <div>{{ $apiError }}</div>
            </div>
        @endif

        <form id="orderForm" action="{{ route('seller.order.store') }}" method="POST">
            @csrf

            <div class="form-container">

                {{-- Product Code Search --}}
                <div class="product-search-section">
                    <div class="search-header">
                        <span class="material-icons-round" style="color:var(--primary)">qr_code_scanner</span>
                        Search Product by Code
                    </div>
                    <div class="input-with-button">
                        <div style="flex:1">
                            <input type="text" id="searchProductCode" class="form-control"
                                   placeholder="Enter product code (e.g., DX/ITM/0001)"
                                   autocomplete="off" value="{{ $productCode ?? '' }}">
                            <div class="helper-text"><i class="fas fa-info-circle"></i> Enter the product code and click Fetch</div>
                        </div>
                        <button type="button" id="fetchProductBtn" class="btn btn-primary">
                            <span class="material-icons-round" style="font-size:18px">search</span>Fetch Product
                        </button>
                        <button type="button" id="resetBtn" class="btn btn-danger"
                                style="display:{{ $productId ? 'inline-flex' : 'none' }}">
                            <span class="material-icons-round" style="font-size:18px">refresh</span>Reset
                        </button>
                    </div>
                </div>

                {{-- Product Info --}}
                <div id="productInfoCard" class="product-info-card" style="display:{{ $productId ? 'block' : 'none' }}">
                    <div class="product-info-header">
                        <img id="productImagePreview" src="{{ $productImage ?? '' }}" alt="Product"
                             class="product-image-preview" style="display:{{ $productImage ? 'block' : 'none' }}">
                        <div id="productImagePlaceholder" class="product-image-placeholder"
                             style="display:{{ $productImage ? 'none' : 'flex' }}">
                            <span class="material-icons-round" style="font-size:32px">inventory_2</span>
                        </div>
                        <div class="product-info-text" style="flex:1">
                            <h4 id="displayProductName">{{ $productName ?? '-' }}</h4>
                            <span class="product-code-badge">
                                <span class="material-icons-round" style="font-size:14px">tag</span>
                                <span id="displayProductCode">{{ $productCode ?? '-' }}</span>
                            </span>
                        </div>
                    </div>
                    <div class="product-details-grid">
                        <div class="detail-item">
                            <div class="detail-label">Price</div>
                            <div class="detail-value" style="color:var(--danger)">Rs. <span id="displayProductPrice">{{ $productPrice ?? '0.00' }}</span></div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Available Stock</div>
                            <div class="detail-value"><span id="displayProductStock">{{ $productStock ?? '0' }}</span> units</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Status</div>
                            <div class="detail-value">
                                <span id="stockStatusBadge" class="stock-badge
                                    @if(($productStock ?? 0) <= 0) stock-out
                                    @elseif(($productStock ?? 0) <= 10) stock-low
                                    @else stock-available @endif">
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
                <input type="hidden" name="product_code"  id="product_code"  value="{{ $productCode ?? '' }}" required>
                <input type="hidden" name="product_id"    id="product_id"    value="{{ $productId ?? '' }}"   required>
                <input type="hidden" name="product_name"  id="product_name"  value="{{ $productName ?? '' }}" required>
                <input type="hidden" name="product_price" id="product_price" value="{{ $productPrice ?? '0' }}" required>
                <input type="hidden" name="product_image" id="product_image" value="{{ $productImage ?? '' }}">
                <input type="hidden" name="date"          id="order_date"    value="{{ date('Y-m-d') }}">

                <input type="hidden" name="destination_city_id"    id="destination_city_id">
                <input type="hidden" name="destination_city_name"  id="destination_city_name">
                <input type="hidden" name="destination_state_name" id="destination_state_name">

                {{-- Order details --}}
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Customer Name <span class="required">*</span></label>
                        <input type="text" name="customer_name" id="customer_name" class="form-control"
                               placeholder="Enter customer name" value="{{ old('customer_name') }}" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Customer Phone <span class="required">*</span></label>
                        <div class="phone-pair">
                            <div>
                                <span class="phone-sub-label">Primary Number</span>
                                <input type="text" name="customer_phone" id="customer_phone" class="form-control"
                                       placeholder="077 123 4567" value="{{ old('customer_phone') }}" required>
                            </div>
                            <div>
                                <span class="phone-sub-label">Secondary <small>(optional)</small></span>
                                <input type="text" name="customer_phone_2" id="customer_phone_2" class="form-control"
                                       placeholder="071 765 4321" value="{{ old('customer_phone_2') }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Quantity <span class="required">*</span></label>
                        <input type="number" name="quantity" id="quantity" class="form-control"
                               placeholder="Enter quantity" min="1"
                               value="{{ $quantity ?? 1 }}"
                               {{ !$productId ? 'disabled' : '' }} required>
                        <div class="helper-text">Available: <span id="availableStock">{{ $productStock ?? '0' }}</span> units</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <span class="material-icons-round" style="font-size:16px;vertical-align:middle;color:var(--primary)">local_shipping</span>
                            Delivery City / District <span class="required">*</span>
                            <span id="apiStatusBadge" class="api-status loading" style="margin-left:6px">
                                <span class="spinner" style="width:10px;height:10px;border-top-color:currentColor;border-color:rgba(0,0,0,.15)"></span> Loading…
                            </span>
                        </label>
                        <div class="city-autocomplete-wrapper">
                            <input type="text" name="district" id="district" class="form-control"
                                   placeholder="Type to search delivery city…"
                                   value="{{ old('district') }}"
                                   autocomplete="off" required>
                            <button type="button" class="city-clear-btn" id="cityClearBtn" title="Clear">
                                <span class="material-icons-round" style="font-size:16px">close</span>
                            </button>
                            <div class="city-dropdown" id="cityDropdown"></div>
                        </div>
                        <div class="helper-text" id="cityHelperText">Cities loaded from Curfox delivery API</div>
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
                        <label class="form-label">Payment Method <span class="required">*</span></label>
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
                    <label class="form-label">Delivery Address</label>
                    <textarea name="customer_address" id="customer_address" class="form-control"
                              placeholder="Enter delivery address">{{ old('customer_address') }}</textarea>
                </div>

                <div class="form-group" style="margin-bottom:24px">
                    <label class="form-label">Order Notes / Remarks</label>
                    <textarea name="remark" id="remark" class="form-control"
                              placeholder="Add any special instructions (optional)">{{ old('remark') }}</textarea>
                </div>

                <div class="form-actions">
                    <button type="button" id="printBtn" class="btn btn-secondary" {{ !$productId ? 'disabled' : '' }}>
                        <span class="material-icons-round" style="font-size:18px">print</span>Print Order
                    </button>
                    <button type="button" id="submitBtn" class="btn btn-success" {{ !$productId ? 'disabled' : '' }}>
                        <span class="material-icons-round" style="font-size:18px">shopping_cart</span>Create Order
                    </button>
                </div>

                {{-- CSV Bulk Import --}}
                <div class="product-search-section" style="margin-top:32px;border-color:#f59e0b">
                    <div class="search-header">
                        <span class="material-icons-round" style="color:var(--warning)">upload_file</span>
                        Bulk Import Orders (CSV)
                    </div>

                    {{-- Step 1 — product search for bulk --}}
                    <div style="background:#fffbeb;border:2px solid #fcd34d;border-radius:var(--radius-md);padding:18px;margin-bottom:20px">
                        <div style="font-size:13px;font-weight:700;color:#92400e;margin-bottom:12px;display:flex;align-items:center;gap:6px">
                            <span class="material-icons-round" style="font-size:16px">looks_one</span>
                            Step 1 — Select Product for Bulk Import
                        </div>

                        <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end">
                            <div style="flex:1;min-width:240px">
                                <label class="form-label" style="font-size:13px">Product Code <span class="required">*</span></label>
                                <input type="text" id="bulkProductCode" class="form-control"
                                       placeholder="e.g. DX/ITM/0001" autocomplete="off">
                            </div>
                            <button type="button" id="bulkFetchProductBtn" class="btn btn-warning" style="height:48px">
                                <span class="material-icons-round" style="font-size:18px">search</span>Verify Product
                            </button>
                            <button type="button" id="bulkResetProductBtn" class="btn btn-danger" style="height:48px;display:none">
                                <span class="material-icons-round" style="font-size:18px">close</span>Clear
                            </button>
                        </div>

                        {{-- Product verified card --}}
                        <div id="bulkProductCard" style="display:none;margin-top:14px;background:#fff;border:2px solid #10b981;border-radius:var(--radius-md);padding:14px">
                            <div style="display:flex;align-items:center;gap:14px">
                                <img id="bulkProductImg" src="" alt="" style="width:60px;height:60px;border-radius:8px;object-fit:cover;border:2px solid var(--border);display:none">
                                <div id="bulkProductImgPh" style="width:60px;height:60px;border-radius:8px;border:2px dashed var(--border);background:#f1f5f9;display:flex;align-items:center;justify-content:center;color:var(--mid)">
                                    <span class="material-icons-round" style="font-size:26px">inventory_2</span>
                                </div>
                                <div style="flex:1">
                                    <div style="font-size:16px;font-weight:800;color:var(--dark)" id="bulkProductName">—</div>
                                    <div style="display:flex;align-items:center;gap:8px;margin-top:4px;flex-wrap:wrap">
                                        <span class="product-code-badge"><span class="material-icons-round" style="font-size:13px">tag</span><span id="bulkProductCodeDisplay">—</span></span>
                                        <span style="font-size:13px;color:var(--danger);font-weight:700">Rs. <span id="bulkProductPrice">0.00</span></span>
                                        <span id="bulkStockBadge" class="stock-badge stock-available">
                                            <span class="material-icons-round" style="font-size:13px">inventory</span>
                                            <span id="bulkProductStock">0</span> in stock
                                        </span>
                                    </div>
                                </div>
                                <div style="text-align:right">
                                    <div style="font-size:11px;color:var(--mid)">CSV rows will use</div>
                                    <div style="font-size:13px;font-weight:700;color:#10b981">✓ Product verified</div>
                                </div>
                            </div>
                        </div>

                        {{-- Stock warning for bulk --}}
                        <div id="bulkStockWarning" style="display:none;margin-top:10px;padding:10px 14px;background:#fef3c7;border:1px solid #f59e0b;border-radius:8px;font-size:13px;color:#92400e">
                            <span class="material-icons-round" style="font-size:15px;vertical-align:middle">warning</span>
                            <span id="bulkStockWarningText"></span>
                        </div>
                    </div>

                    {{-- Step 2 — CSV upload + options --}}
                    <div style="background:#f8fafc;border:2px solid var(--border);border-radius:var(--radius-md);padding:18px;margin-bottom:16px">
                        <div style="font-size:13px;font-weight:700;color:var(--dark);margin-bottom:12px;display:flex;align-items:center;gap:6px">
                            <span class="material-icons-round" style="font-size:16px;color:var(--warning)">looks_two</span>
                            Step 2 — Upload CSV & Set Defaults
                        </div>

                        <div class="form-grid" style="margin-bottom:0">
                            <div class="form-group">
                                <label class="form-label" style="font-size:13px">CSV File <span class="required">*</span></label>
                                <input type="file" id="csvFile" class="form-control" accept=".csv">
                                <div class="helper-text">
                                    Required: <b>customer_name</b>, <b>customer_phone</b> |
                                    Optional: customer_address, <b>city</b>, platform, remark
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label" style="font-size:13px">Default Options</label>
                                <div style="display:flex;gap:8px;flex-wrap:wrap">
                                    <select id="bulkDefaultPlatform" class="form-control" style="flex:1;min-width:150px">
                                        <option value="fb">Platform: Facebook</option>
                                        <option value="ig">Instagram</option>
                                        <option value="tiktok">TikTok</option>
                                        <option value="website">Website</option>
                                        <option value="phone">Phone</option>
                                        <option value="manual">Manual</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Step 3 — action buttons --}}
                    <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;margin-bottom:16px">
                        <button type="button" id="parseCsvBtn" class="btn btn-warning" disabled>
                            <span class="material-icons-round" style="font-size:18px">preview</span>Preview & Validate Cities
                        </button>
                        <button type="button" id="bulkImportBtn" class="btn btn-success" disabled>
                            <span class="material-icons-round" style="font-size:18px">cloud_upload</span>Import Now
                        </button>
                        <label style="display:inline-flex;align-items:center;gap:6px;font-size:13px;font-weight:600;color:var(--mid);cursor:pointer;white-space:nowrap"
                               title="Allow rows whose city is not in the Curfox delivery network — city name saved as-is, no waybill available">
                            <input type="checkbox" id="allowNonCurfoxCity" style="width:16px;height:16px;accent-color:var(--warning)">
                            Allow non-Curfox cities
                        </label>
                    </div>

                    {{-- Validation summary --}}
                    <div id="cityValidationSummary" style="display:none;margin-bottom:12px">
                        <div class="validation-summary" id="valBadges"></div>
                        <div id="cityErrorList" style="display:none;margin-top:8px;padding:12px;background:#fff5f5;border:1px solid #fecaca;border-radius:8px;max-height:180px;overflow-y:auto"></div>
                        <p class="val-note" id="valNote" style="margin-top:6px"></p>
                    </div>

                    {{-- Preview table --}}
                    <div id="csvPreviewWrap" style="display:none">
                        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;flex-wrap:wrap;gap:8px">
                            <div class="helper-text" style="margin:0">
                                Preview (first 20 rows) — <b><span id="csvRowCount">0</span> rows</b> loaded
                            </div>
                            <div id="bulkPreviewProductBadge" style="display:none;padding:4px 12px;background:#d1fae5;color:#065f46;border-radius:999px;font-size:12px;font-weight:700"></div>
                        </div>
                        <div style="overflow:auto;border:1px solid var(--border);border-radius:12px">
                            <table style="width:100%;border-collapse:collapse;font-size:13px">
                                <thead style="background:var(--bg)">
                                <tr>
                                    <th style="text-align:left;padding:10px;border-bottom:1px solid var(--border)">#</th>
                                    <th style="text-align:left;padding:10px;border-bottom:1px solid var(--border)">Customer</th>
                                    <th style="text-align:left;padding:10px;border-bottom:1px solid var(--border)">Phone</th>
                                    <th style="text-align:left;padding:10px;border-bottom:1px solid var(--border)">City (Input)</th>
                                    <th style="text-align:left;padding:10px;border-bottom:1px solid var(--border)">Resolved City</th>
                                    <th style="text-align:left;padding:10px;border-bottom:1px solid var(--border)">State</th>
                                    <th style="text-align:left;padding:10px;border-bottom:1px solid var(--border)">Platform</th>
                                </tr>
                                </thead>
                                <tbody id="csvPreviewBody"></tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>{{-- /form-container --}}
        </form>
    </div>
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // ─── URLs and Constants ──────────────────────────────────────────────────
        const CITIES_API_URL = '{{ route("seller.order.cities.json") }}';
        const BULK_STORE_URL = '{{ route("seller.order.bulk-store") }}';
        const BULK_VALIDATE_URL = '{{ route("seller.order.bulk-validate") }}';
        const PRODUCT_DETAILS_URL = '{{ route("seller.order.product.details", ["code" => "PLACEHOLDER"]) }}';
        const CSRF_TOKEN = '{{ csrf_token() }}';
        const PENDING_ORDERS_URL = '{{ route("seller.order.pending") }}';

        // ─── City data (server-preloaded) ────────────────────────────────────────
        let CITIES = [];
        let citiesLoaded = false;

        @php
            $serverCities = [];
            if (!empty($statesWithCities)) {
                foreach ($statesWithCities as $state) {
                    foreach ($state['cities'] ?? [] as $city) {
                        if ($city['is_active'] ?? true) {
                            $serverCities[] = [
                                'id'         => $city['id'],
                                'name'       => $city['name'],
                                'state_id'   => $state['id'],
                                'state_name' => $state['name'],
                            ];
                        }
                    }
                }
            }
        @endphp

        const SERVER_CITIES = @json($serverCities);

        if (SERVER_CITIES.length > 0) {
            CITIES = SERVER_CITIES;
            citiesLoaded = true;
            updateApiStatusBadge('ok', CITIES.length + ' cities ready');
        } else {
            loadCitiesFromApi();
        }

        async function loadCitiesFromApi() {
            updateApiStatusBadge('loading', 'Loading cities…');
            try {
                const res = await fetch(CITIES_API_URL, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    }
                });

                if (!res.ok) {
                    throw new Error(`HTTP ${res.status}: ${res.statusText}`);
                }

                const json = await res.json();
                if (json.success && Array.isArray(json.data)) {
                    CITIES = json.data;
                    citiesLoaded = true;
                    updateApiStatusBadge('ok', CITIES.length + ' cities ready');
                } else {
                    throw new Error(json.message || 'Failed to load cities');
                }
            } catch (err) {
                console.error('City load error:', err);
                citiesLoaded = false;
                updateApiStatusBadge('err', 'City load failed');
            }
        }

        function updateApiStatusBadge(type, text) {
            const badge = document.getElementById('apiStatusBadge');
            if (!badge) return;
            badge.className = 'api-status ' + type;
            badge.innerHTML = (type === 'loading'
                ? '<span class="spinner" style="width:10px;height:10px;border-top-color:currentColor;border-color:rgba(0,0,0,.15)"></span> '
                : (type === 'ok' ? '✓ ' : '✕ ')) + text;
        }

        // ─── City Autocomplete ────────────────────────────────────────────────────
        (function () {
            const input      = document.getElementById('district');
            const dropdown   = document.getElementById('cityDropdown');
            const clearBtn   = document.getElementById('cityClearBtn');
            const helperText = document.getElementById('cityHelperText');

            let activeIndex    = -1;
            let debounceTimer;
            let currentMatches = [];

            function esc(s) {
                return String(s ?? '').replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m]));
            }

            function highlight(text, q) {
                if (!q) return esc(text);
                const re = new RegExp('(' + q.replace(/[.*+?^${}()|[\]\\]/g,'\\$&') + ')','gi');
                return esc(text).replace(re,'<mark>$1</mark>');
            }

            function openDropdown(matches, query) {
                currentMatches = matches;
                activeIndex = -1;
                dropdown.innerHTML = '';

                if (!citiesLoaded) {
                    dropdown.innerHTML = '<div class="city-loading"><span class="spinner"></span> Loading cities…</div>';
                    dropdown.classList.add('open');
                    return;
                }

                if (matches.length === 0) {
                    dropdown.innerHTML = '<div class="city-dropdown-empty">No cities found for "' + esc(query) + '"</div>';
                } else {
                    const frag = document.createDocumentFragment();
                    const limit = Math.min(matches.length, 80);
                    for (let i = 0; i < limit; i++) {
                        const m = matches[i];
                        const item = document.createElement('div');
                        item.className = 'city-dropdown-item';
                        item.dataset.index = i;
                        item.innerHTML =
                            '<span class="material-icons-round" style="font-size:14px;color:var(--mid)">location_on</span>' +
                            highlight(m.name, query) +
                            '<span class="city-state">' + esc(m.state_name) + '</span>';
                        item.addEventListener('mousedown', function (e) {
                            e.preventDefault();
                            selectCity(matches[parseInt(this.dataset.index)]);
                        });
                        frag.appendChild(item);
                    }
                    dropdown.appendChild(frag);
                }
                dropdown.classList.add('open');
            }

            function closeDropdown() {
                dropdown.classList.remove('open');
                dropdown.innerHTML = '';
                activeIndex = -1;
                currentMatches = [];
            }

            function selectCity(city) {
                input.value = city.name;
                document.getElementById('destination_city_id').value    = city.id;
                document.getElementById('destination_city_name').value  = city.name;
                document.getElementById('destination_state_name').value = city.state_name;
                clearBtn.classList.add('visible');
                helperText.innerHTML = '<span class="city-badge-selected"><span class="material-icons-round" style="font-size:12px">check_circle</span>' + esc(city.name) + ' · ' + esc(city.state_name) + '</span>';
                closeDropdown();
            }

            function clearCity() {
                input.value = '';
                document.getElementById('destination_city_id').value    = '';
                document.getElementById('destination_city_name').value  = '';
                document.getElementById('destination_state_name').value = '';
                clearBtn.classList.remove('visible');
                helperText.textContent = 'Cities loaded from Curfox delivery API';
                closeDropdown();
                input.focus();
            }

            function search(query) {
                if (!query || query.length < 1) {
                    closeDropdown();
                    return;
                }
                const q = query.toLowerCase().trim();
                const exact = [], starts = [], contains = [];
                for (const city of CITIES) {
                    const l = String(city.name || '').toLowerCase();
                    if (l === q) exact.push(city);
                    else if (l.startsWith(q)) starts.push(city);
                    else if (l.includes(q)) contains.push(city);
                }
                openDropdown([...exact, ...starts, ...contains], query);
            }

            input.addEventListener('input', function () {
                const v = this.value;
                clearBtn.classList.toggle('visible', v.length > 0);
                document.getElementById('destination_city_id').value    = '';
                document.getElementById('destination_city_name').value  = '';
                document.getElementById('destination_state_name').value = '';
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => search(v), 120);
            });

            input.addEventListener('keydown', function (e) {
                const items = dropdown.querySelectorAll('.city-dropdown-item');
                if (!dropdown.classList.contains('open')) return;
                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    activeIndex = Math.min(activeIndex + 1, items.length - 1);
                    items.forEach((el, i) => el.classList.toggle('active', i === activeIndex));
                }
                if (e.key === 'ArrowUp')   {
                    e.preventDefault();
                    activeIndex = Math.max(activeIndex - 1, -1);
                    items.forEach((el, i) => el.classList.toggle('active', i === activeIndex));
                }
                if (e.key === 'Enter')     {
                    e.preventDefault();
                    if (activeIndex >= 0 && currentMatches[activeIndex]) selectCity(currentMatches[activeIndex]);
                }
                if (e.key === 'Escape')    closeDropdown();
            });

            input.addEventListener('blur',  () => setTimeout(closeDropdown, 200));
            clearBtn.addEventListener('click', clearCity);
        })();

        // ─── Product fetch + UI ───────────────────────────────────────────────────
        let currentProductData = null;

        document.getElementById('fetchProductBtn').addEventListener('click', function () {
            const code = document.getElementById('searchProductCode').value.trim();
            if (!code) {
                Swal.fire({ icon: 'error', title: 'Missing Code', text: 'Please enter a product code', confirmButtonColor: '#4f46e5' });
                return;
            }
            fetchProductByCode(code);
        });

        document.getElementById('searchProductCode').addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                document.getElementById('fetchProductBtn').click();
            }
        });

        async function fetchProductByCode(productCode) {
            const btn = document.getElementById('fetchProductBtn');
            const orig = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner"></span> Fetching…';

            try {
                const url = PRODUCT_DETAILS_URL.replace('PLACEHOLDER', encodeURIComponent(productCode));
                console.log('Fetching product from:', url);

                const res = await fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    }
                });

                // Check if response is JSON
                const contentType = res.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    const text = await res.text();
                    console.error('Non-JSON response:', text.substring(0, 200));
                    throw new Error('Server returned HTML instead of JSON. Please check your route and controller.');
                }

                const data = await res.json();

                if (!res.ok) {
                    throw new Error(data.message || `HTTP ${res.status}: ${res.statusText}`);
                }

                if (data.success && data.product) {
                    displayProductInfo(data.product);
                    Swal.fire({
                        icon: 'success',
                        title: 'Product Found!',
                        text: data.product.name + ' loaded',
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    throw new Error(data.message || 'Invalid response');
                }
            } catch (err) {
                console.error('Fetch error:', err);
                Swal.fire({
                    icon: 'error',
                    title: 'Not Found',
                    text: err.message,
                    confirmButtonColor: '#4f46e5'
                });
                resetForm();
            } finally {
                btn.disabled = false;
                btn.innerHTML = orig;
            }
        }

        function displayProductInfo(product) {
            currentProductData = product;
            const card = document.getElementById('productInfoCard');
            card.style.display = 'block';
            card.classList.add('show');
            document.getElementById('resetBtn').style.display = 'inline-flex';

            document.getElementById('product_code').value  = product.code;
            document.getElementById('product_id').value    = product.id;
            document.getElementById('product_name').value  = product.name;
            document.getElementById('product_price').value = product.display_price || product.price;
            document.getElementById('product_image').value = product.image || '';

            document.getElementById('displayProductName').textContent  = product.name;
            document.getElementById('displayProductCode').textContent  = product.code;
            document.getElementById('displayProductPrice').textContent = (parseFloat(product.display_price) || 0).toFixed(2);

            const stock = parseInt(product.qty) || 0;
            document.getElementById('displayProductStock').textContent = stock;
            document.getElementById('availableStock').textContent      = stock;

            const badge = document.getElementById('stockStatusBadge');
            if (stock <= 0) {
                badge.className = 'stock-badge stock-out';
                badge.innerHTML = '<span class="material-icons-round" style="font-size:14px">cancel</span> Out of Stock';
            } else if (stock <= (product.min_qty || 10)) {
                badge.className = 'stock-badge stock-low';
                badge.innerHTML = '<span class="material-icons-round" style="font-size:14px">warning</span> Low Stock';
            } else {
                badge.className = 'stock-badge stock-available';
                badge.innerHTML = '<span class="material-icons-round" style="font-size:14px">check_circle</span> Available';
            }

            const imgEl = document.getElementById('productImagePreview');
            const phEl  = document.getElementById('productImagePlaceholder');
            if (product.image) {
                imgEl.src = product.image;
                imgEl.style.display = 'block';
                phEl.style.display = 'none';
                imgEl.onerror = () => {
                    imgEl.style.display = 'none';
                    phEl.style.display = 'flex';
                };
            } else {
                imgEl.style.display = 'none';
                phEl.style.display = 'flex';
            }

            const qty = document.getElementById('quantity');
            if (stock > 0) {
                qty.max = stock;
                qty.disabled = false;
                if (parseInt(qty.value) > stock) qty.value = stock;
            } else {
                qty.disabled = true;
                qty.value = 0;
            }

            document.getElementById('submitBtn').disabled = false;
            document.getElementById('printBtn').disabled  = false;
        }

        document.getElementById('resetBtn').addEventListener('click', function () {
            Swal.fire({
                title: 'Reset Form?',
                text: 'All product and order info will be cleared.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, Reset'
            }).then(r => {
                if (r.isConfirmed) resetForm();
            });
        });

        function resetForm() {
            currentProductData = null;
            document.getElementById('productInfoCard').style.display = 'none';
            document.getElementById('productInfoCard').classList.remove('show');
            document.getElementById('resetBtn').style.display = 'none';
            document.getElementById('searchProductCode').value = '';

            ['product_code','product_id','product_name','product_price','product_image',
                'customer_name','customer_phone','customer_phone_2','customer_address','quantity','remark',
                'destination_city_id','destination_city_name','destination_state_name'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.value = (id === 'quantity') ? '1' : '';
            });

            document.getElementById('district').value = '';
            document.getElementById('platform').value = '';
            document.getElementById('payment_method').value = '';
            document.getElementById('displayProductName').textContent = '-';
            document.getElementById('displayProductCode').textContent = '-';
            document.getElementById('displayProductPrice').textContent = '0.00';
            document.getElementById('displayProductStock').textContent = '0';
            document.getElementById('availableStock').textContent = '0';
            document.getElementById('productImagePreview').style.display = 'none';
            document.getElementById('productImagePlaceholder').style.display = 'flex';
            document.getElementById('quantity').disabled  = true;
            document.getElementById('submitBtn').disabled = true;
            document.getElementById('printBtn').disabled  = true;
        }

        // ─── Single Order submit ──────────────────────────────────────────────────
        document.getElementById('submitBtn').addEventListener('click', function (e) {
            e.preventDefault();
            if (!currentProductData || !document.getElementById('product_id').value) {
                Swal.fire({ icon: 'error', title: 'No Product', text: 'Please fetch a product first.', confirmButtonColor: '#4f46e5' });
                return;
            }

            const customerName  = document.getElementById('customer_name').value.trim();
            const customerPhone = document.getElementById('customer_phone').value.trim();
            const paymentMethod = document.getElementById('payment_method').value;
            const districtVal   = document.getElementById('district').value.trim();
            const cityId        = document.getElementById('destination_city_id').value;
            const quantity      = parseInt(document.getElementById('quantity').value) || 0;
            const stock         = parseInt(currentProductData.qty) || 0;

            if (!customerName)  { Swal.fire({ icon: 'error', title: 'Missing', text: 'Enter customer name.' }); return; }
            if (!customerPhone) { Swal.fire({ icon: 'error', title: 'Missing', text: 'Enter customer phone.' }); return; }
            if (!paymentMethod) { Swal.fire({ icon: 'error', title: 'Missing', text: 'Select payment method.' }); return; }
            if (!districtVal)   { Swal.fire({ icon: 'error', title: 'Missing', text: 'Select a delivery city.' }); return; }
            if (!cityId)        { Swal.fire({ icon: 'error', title: 'Invalid City', text: 'Please select a city from the dropdown to confirm it is valid.' }); return; }
            if (quantity <= 0)  { Swal.fire({ icon: 'error', title: 'Invalid Qty', text: 'Enter a valid quantity.' }); return; }
            if (quantity > stock) { Swal.fire({ icon: 'error', title: 'Exceeds Stock', text: 'Only ' + stock + ' units available.' }); return; }

            Swal.fire({
                title: 'Create Order?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, Create',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    const form = document.getElementById('orderForm');
                    return fetch(form.action, {
                        method: 'POST',
                        body: new FormData(form),
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': CSRF_TOKEN,
                            'Accept': 'application/json'
                        }
                    }).then(r => {
                        if (!r.ok) {
                            return r.json().then(e => { throw new Error(e.message || 'Error'); });
                        }
                        return r.json();
                    }).catch(err => {
                        Swal.showValidationMessage('Error: ' + err.message);
                    });
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then(result => {
                if (result.isConfirmed && result.value && result.value.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Order Created!',
                        text: result.value.message,
                        timer: 1800,
                        showConfirmButton: false
                    }).then(() => window.location.href = result.value.redirect_url || PENDING_ORDERS_URL);
                }
            });
        });

        // ─── Print ────────────────────────────────────────────────────────────────
        document.getElementById('printBtn').addEventListener('click', function () {
            if (!currentProductData) return;
            const n = document.getElementById('customer_name').value;
            const p = document.getElementById('customer_phone').value;
            if (!n || !p) {
                Swal.fire({ icon: 'error', title: 'Missing Info', text: 'Fill customer name and phone.' });
                return;
            }

            const city  = document.getElementById('district').value;
            const qty   = document.getElementById('quantity').value;
            const state = document.getElementById('destination_state_name').value;

            const pw = window.open('', '', 'height=600,width=800');
            pw.document.write('<html><head><title>Order Print</title><style>body{font-family:Arial;padding:20px}h1{color:#4f46e5}table{width:100%;border-collapse:collapse}td{padding:10px;border-bottom:1px solid #eee}td:first-child{font-weight:bold;width:40%}</style></head><body>');
            pw.document.write('<h1>Order Details</h1><p>Date: ' + new Date().toLocaleDateString() + '</p><table>');
            pw.document.write('<tr><td>Product:</td><td>' + currentProductData.name + '</td></tr>');
            pw.document.write('<tr><td>Code:</td><td>' + currentProductData.code + '</td></tr>');
            pw.document.write('<tr><td>Price:</td><td>Rs. ' + currentProductData.display_price + '</td></tr>');
            pw.document.write('<tr><td>Qty:</td><td>' + qty + '</td></tr>');
            pw.document.write('<tr><td>Total:</td><td>Rs. ' + (currentProductData.display_price * qty).toFixed(2) + '</td></tr>');
            pw.document.write('<tr><td>Customer:</td><td>' + n + '</td></tr>');
            pw.document.write('<tr><td>Phone:</td><td>' + p + '</td></tr>');
            if (city) pw.document.write('<tr><td>City:</td><td>' + city + (state ? ', ' + state : '') + '</td></tr>');
            pw.document.write('</table></body></html>');
            pw.document.close();
            pw.focus();
            setTimeout(() => { pw.print(); pw.close(); }, 250);
        });

        // ─── CSV Bulk Import ─────────────────────────────────────────────────────
        let BULK_LEADS = [];
        let BULK_VALIDATED = false;
        let bulkProductData = null;

        function escH(s) {
            return String(s ?? '').replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m]));
        }

        // Bulk product fetch
        document.getElementById('bulkFetchProductBtn').addEventListener('click', () => bulkFetchProduct());
        document.getElementById('bulkProductCode').addEventListener('keypress', e => {
            if (e.key === 'Enter') {
                e.preventDefault();
                bulkFetchProduct();
            }
        });
        document.getElementById('bulkResetProductBtn').addEventListener('click', bulkResetProduct);

        async function bulkFetchProduct() {
            const code = document.getElementById('bulkProductCode').value.trim();
            if (!code) {
                Swal.fire({ icon: 'error', title: 'Missing Code', text: 'Enter a product code first.', confirmButtonColor: '#f59e0b' });
                return;
            }

            const btn = document.getElementById('bulkFetchProductBtn');
            const orig = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner" style="border-top-color:#fff"></span> Verifying…';

            try {
                const url = PRODUCT_DETAILS_URL.replace('PLACEHOLDER', encodeURIComponent(code));
                const res = await fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    }
                });

                if (!res.ok) {
                    const text = await res.text();
                    console.error('Non-JSON response:', text.substring(0, 200));
                    throw new Error('Server returned HTML instead of JSON');
                }

                const data = await res.json();

                if (!data.success || !data.product) {
                    throw new Error(data.message || 'Product not found');
                }

                bulkProductData = data.product;
                showBulkProductCard(data.product);
                document.getElementById('parseCsvBtn').disabled = false;

                Swal.fire({
                    icon: 'success',
                    title: 'Product Verified!',
                    html: `<b>${escH(data.product.name)}</b><br>Code: ${escH(data.product.code)}<br>Stock: <b>${data.product.qty}</b> units`,
                    timer: 2200,
                    showConfirmButton: false
                });
            } catch (err) {
                bulkProductData = null;
                hideBulkProductCard();
                document.getElementById('parseCsvBtn').disabled = true;
                document.getElementById('bulkImportBtn').disabled = true;
                Swal.fire({ icon: 'error', title: 'Not Found', text: err.message, confirmButtonColor: '#f59e0b' });
            } finally {
                btn.disabled = false;
                btn.innerHTML = orig;
            }
        }

        function showBulkProductCard(p) {
            const card = document.getElementById('bulkProductCard');
            card.style.display = 'block';

            document.getElementById('bulkProductName').textContent        = p.name;
            document.getElementById('bulkProductCodeDisplay').textContent = p.code;
            document.getElementById('bulkProductPrice').textContent       = (parseFloat(p.display_price)||0).toFixed(2);
            document.getElementById('bulkProductStock').textContent       = p.qty;
            document.getElementById('bulkResetProductBtn').style.display  = 'inline-flex';

            const badge = document.getElementById('bulkStockBadge');
            const qty   = parseInt(p.qty) || 0;
            badge.className = 'stock-badge ' + (qty <= 0 ? 'stock-out' : qty <= 10 ? 'stock-low' : 'stock-available');

            const img = document.getElementById('bulkProductImg');
            const ph  = document.getElementById('bulkProductImgPh');
            if (p.image) {
                img.src = p.image;
                img.style.display = 'block';
                ph.style.display = 'none';
                img.onerror = () => {
                    img.style.display='none';
                    ph.style.display='flex';
                };
            } else {
                img.style.display = 'none';
                ph.style.display = 'flex';
            }

            checkBulkStockVsLeads();
        }

        function hideBulkProductCard() {
            document.getElementById('bulkProductCard').style.display      = 'none';
            document.getElementById('bulkResetProductBtn').style.display  = 'none';
            document.getElementById('bulkStockWarning').style.display     = 'none';
        }

        function bulkResetProduct() {
            bulkProductData = null;
            hideBulkProductCard();
            document.getElementById('bulkProductCode').value = '';
            document.getElementById('parseCsvBtn').disabled  = true;
            document.getElementById('bulkImportBtn').disabled = true;
            document.getElementById('csvPreviewWrap').style.display        = 'none';
            document.getElementById('cityValidationSummary').style.display = 'none';
            BULK_LEADS = [];
            BULK_VALIDATED = false;
        }

        function checkBulkStockVsLeads() {
            if (!bulkProductData) return;
            const stock     = parseInt(bulkProductData.qty) || 0;
            const leadCount = BULK_LEADS.length;
            const warn      = document.getElementById('bulkStockWarning');
            const warnTxt   = document.getElementById('bulkStockWarningText');

            if (leadCount > 0 && leadCount > stock) {
                warn.style.display  = 'block';
                warnTxt.textContent = `Only ${stock} units in stock but CSV has ${leadCount} rows. Import will fail if stock runs out during processing.`;
            } else {
                warn.style.display = 'none';
            }
        }

        // CSV Parse & Validate
        document.getElementById('parseCsvBtn').addEventListener('click', async () => {
            if (!bulkProductData) {
                Swal.fire({ icon: 'error', title: 'No Product', text: 'Verify a product first (Step 1).' });
                return;
            }

            const file = document.getElementById('csvFile').files[0];
            if (!file) {
                Swal.fire({ icon: 'error', title: 'No file', text: 'Please select a CSV file.' });
                return;
            }

            const text = await file.text();
            BULK_LEADS = parseCsvToLeads(text);
            BULK_VALIDATED = false;
            document.getElementById('bulkImportBtn').disabled = true;

            if (!BULK_LEADS.length) {
                Swal.fire({ icon: 'error', title: 'Empty CSV', text: 'No valid rows found. Ensure headers include customer_name and customer_phone.' });
                return;
            }

            checkBulkStockVsLeads();

            const badge = document.getElementById('bulkPreviewProductBadge');
            badge.style.display = 'inline-flex';
            badge.textContent   = `✓ Product: ${bulkProductData.name} (${bulkProductData.code}) — Stock: ${bulkProductData.qty}`;

            const btn = document.getElementById('parseCsvBtn');
            const orig = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner" style="border-top-color:#fff"></span> Validating cities…';

            try {
                const fd = new FormData();
                fd.append('leads', JSON.stringify(BULK_LEADS));

                const res = await fetch(BULK_VALIDATE_URL, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'Accept': 'application/json'
                    },
                    body: fd
                });

                if (!res.ok) {
                    throw new Error(`HTTP ${res.status}`);
                }

                const json = await res.json();

                if (json.success) {
                    renderCsvPreviewWithValidation(BULK_LEADS, json.results);
                    renderValidationSummary(json);

                    json.results.forEach((r, i) => {
                        if (r.status === 'ok' && r.resolved && BULK_LEADS[i]) {
                            BULK_LEADS[i]._resolved_city_id    = r.resolved.id;
                            BULK_LEADS[i]._resolved_city_name  = r.resolved.city_name;
                            BULK_LEADS[i]._resolved_state_name = r.resolved.state_name;
                        }
                    });

                    BULK_VALIDATED = true;
                    document.getElementById('bulkImportBtn').disabled = false;
                } else {
                    Swal.fire({ icon: 'error', title: 'Validation Error', text: json.message });
                    renderCsvPreview(BULK_LEADS);
                    document.getElementById('bulkImportBtn').disabled = false;
                }
            } catch (err) {
                console.error('Validation error:', err);
                renderCsvPreview(BULK_LEADS);
                document.getElementById('bulkImportBtn').disabled = false;
            } finally {
                btn.disabled = false;
                btn.innerHTML = orig;
            }
        });

        // RFC-4180 compliant CSV parser
        function parseCSVLine(line) {
            const result = [];
            let cur = '';
            let inQuotes = false;
            for (let i = 0; i < line.length; i++) {
                const ch   = line[i];
                const next = line[i + 1];
                if (inQuotes) {
                    if (ch === '"' && next === '"') { cur += '"'; i++; }
                    else if (ch === '"')            { inQuotes = false; }
                    else                            { cur += ch; }
                } else {
                    if      (ch === '"') { inQuotes = true; }
                    else if (ch === ',') { result.push(cur.trim()); cur = ''; }
                    else                 { cur += ch; }
                }
            }
            result.push(cur.trim());
            return result;
        }

        function parseCsvToLeads(csvText) {
            const lines = csvText.replace(/\r\n/g, '\n').replace(/\r/g, '\n').split('\n').filter(l => l.trim().length);
            if (lines.length < 2) return [];

            const rawHeader = parseCSVLine(lines[0].replace(/^\uFEFF/, ''));
            const header    = rawHeader.map(h => h.toLowerCase().trim().replace(/\s+/g, '_').replace(/[^a-z0-9_]/g, ''));
            const idx       = name => header.indexOf(name);

            const iName     = idx('customer_name');
            const iPhone    = idx('customer_phone');
            const iAddr     = idx('customer_address');
            const iCity     = idx('city');
            const iCityName = idx('destination_city_name');
            const iPlatform = idx('platform');
            const iRemark   = idx('remark');

            if (iName === -1 || iPhone === -1) return [];

            const leads = [];
            for (let r = 1; r < lines.length; r++) {
                const cols = parseCSVLine(lines[r]);
                const customer_name  = (cols[iName]  ?? '').trim();
                const customer_phone = (cols[iPhone] ?? '').trim();
                if (!customer_name || !customer_phone) continue;

                const cityValue = ((iCityName !== -1 ? cols[iCityName] : '') ||
                    (iCity     !== -1 ? cols[iCity]     : '') || '').trim();

                leads.push({
                    customer_name,
                    customer_phone,
                    customer_address: (iAddr !== -1 ? (cols[iAddr] ?? '').trim() : '') || null,
                    district: cityValue || null,
                    destination_city_name: cityValue || null,
                    destination_state_name: null,
                    platform: (iPlatform !== -1 ? (cols[iPlatform] ?? '').trim() : '') || null,
                    remark: (iRemark !== -1 ? (cols[iRemark] ?? '').trim() : '') || null,
                });
            }
            return leads;
        }

        // Validation summary
        function renderValidationSummary(json) {
            const wrap    = document.getElementById('cityValidationSummary');
            const badges  = document.getElementById('valBadges');
            const errList = document.getElementById('cityErrorList');
            const note    = document.getElementById('valNote');

            wrap.style.display = 'block';

            const validCount   = json.results.filter(r => r.status === 'ok').length;
            const invalidCount = json.invalid_count || 0;
            const missingCount = json.missing_count || 0;

            badges.innerHTML = `
                <span class="val-badge val-badge-ok">✓ ${validCount} cities matched</span>
                ${invalidCount > 0 ? `<span class="val-badge val-badge-err">✕ ${invalidCount} invalid cities</span>` : ''}
                ${missingCount > 0 ? `<span class="val-badge val-badge-warn">⚠ ${missingCount} missing city</span>` : ''}
            `;

            if (invalidCount > 0) {
                const invalidRows = json.results.filter(r => r.status === 'invalid');
                errList.style.display = 'block';
                errList.innerHTML = '<strong style="font-size:12px;color:#991b1b">Invalid city names (not in Curfox network):</strong><br>' +
                    invalidRows.map(r => `<span style="font-size:12px">Row ${r.row}: "<b>${escH(r.input)}</b>" — not found</span>`).join('<br>');
            } else {
                errList.style.display = 'none';
            }

            if (!json.all_valid) {
                renderValidationNote();
            } else {
                note.textContent = 'All cities validated ✓ Ready to import.';
            }
        }

        function renderValidationNote() {
            const allow = document.getElementById('allowNonCurfoxCity')?.checked ?? false;
            const note  = document.getElementById('valNote');
            if (!note) return;
            if (allow) {
                note.innerHTML = '<span style="color:var(--warning)">⚠ Non-Curfox cities will be saved as plain text without a delivery city ID. Curfox dispatch will not be available for those orders.</span>';
            } else {
                note.innerHTML = '<span style="color:#991b1b">Rows with invalid cities will be skipped. Enable "Allow non-Curfox cities" to import them as-is.</span>';
            }
        }

        document.getElementById('allowNonCurfoxCity')?.addEventListener('change', function () {
            applyNonCurfoxToggleState();
            renderValidationNote();
        });

        // Preview table renderers
        function renderCsvPreviewWithValidation(leads, validationResults) {
            document.getElementById('csvPreviewWrap').style.display = 'block';
            document.getElementById('csvRowCount').textContent = leads.length;

            const tbody = document.getElementById('csvPreviewBody');
            tbody.innerHTML = '';

            const limit = Math.min(leads.length, 20);
            for (let i = 0; i < limit; i++) {
                const row      = leads[i];
                const val      = validationResults[i];
                const status   = val ? val.status   : 'unknown';
                const resolved = val ? val.resolved : null;

                let statusHtml = '';
                let resolvedState = '-';

                if (status === 'ok' && resolved) {
                    statusHtml    = `<span class="city-status-ok">✓ ${escH(resolved.city_name)}</span>`;
                    resolvedState = resolved.state_name;
                } else if (status === 'invalid') {
                    const rawCity = escH(row.district || '');
                    statusHtml = `
                        <span class="city-status-invalid" id="city-invalid-${i}">✕ Not in Curfox</span>
                        <span class="city-status-ok" id="city-asis-${i}" style="display:none">⚠ Save as-is: ${rawCity}</span>`;
                } else if (status === 'missing') {
                    statusHtml = `<span class="city-status-missing">⚠ City missing</span>`;
                } else {
                    statusHtml = `<span style="color:var(--mid)">—</span>`;
                }

                const tr = document.createElement('tr');
                tr.dataset.cityStatus = status;
                tr.innerHTML = `
                    <td style="padding:10px;border-bottom:1px solid var(--border)">${i+1}</td>
                    <td style="padding:10px;border-bottom:1px solid var(--border)">${escH(row.customer_name)}</td>
                    <td style="padding:10px;border-bottom:1px solid var(--border)">${escH(row.customer_phone)}</td>
                    <td style="padding:10px;border-bottom:1px solid var(--border)">${escH(row.district || '-')}</td>
                    <td style="padding:10px;border-bottom:1px solid var(--border)">${statusHtml}</td>
                    <td style="padding:10px;border-bottom:1px solid var(--border)">${escH(resolvedState)}</td>
                    <td style="padding:10px;border-bottom:1px solid var(--border)">${escH(row.platform || '-')}</td>
                `;
                tbody.appendChild(tr);
            }

            applyNonCurfoxToggleState();
        }

        function applyNonCurfoxToggleState() {
            const allow = document.getElementById('allowNonCurfoxCity')?.checked ?? false;
            document.querySelectorAll('#csvPreviewBody tr[data-city-status="invalid"]').forEach(tr => {
                const invalidSpan = tr.querySelector('[id^="city-invalid-"]');
                const asisSpan    = tr.querySelector('[id^="city-asis-"]');
                if (invalidSpan) invalidSpan.style.display = allow ? 'none'   : 'inline';
                if (asisSpan)    asisSpan.style.display    = allow ? 'inline' : 'none';
            });
        }

        function renderCsvPreview(leads) {
            document.getElementById('csvPreviewWrap').style.display = 'block';
            document.getElementById('csvRowCount').textContent = leads.length;

            const tbody = document.getElementById('csvPreviewBody');
            tbody.innerHTML = '';

            const limit = Math.min(leads.length, 20);
            for (let i = 0; i < limit; i++) {
                const row = leads[i];
                const tr  = document.createElement('tr');
                tr.innerHTML = `
                    <td style="padding:10px;border-bottom:1px solid var(--border)">${i+1}</td>
                    <td style="padding:10px;border-bottom:1px solid var(--border)">${escH(row.customer_name)}</td>
                    <td style="padding:10px;border-bottom:1px solid var(--border)">${escH(row.customer_phone)}</td>
                    <td style="padding:10px;border-bottom:1px solid var(--border)">${escH(row.district || '-')}</td>
                    <td style="padding:10px;border-bottom:1px solid var(--border)"><span style="color:var(--mid)">—</span></td>
                    <td style="padding:10px;border-bottom:1px solid var(--border)"><span style="color:var(--mid)">—</span></td>
                    <td style="padding:10px;border-bottom:1px solid var(--border)">${escH(row.platform || '-')}</td>
                `;
                tbody.appendChild(tr);
            }
        }

        // Bulk Import Submit
        document.getElementById('bulkImportBtn').addEventListener('click', async () => {
            if (!bulkProductData) {
                Swal.fire({ icon: 'error', title: 'No Product', text: 'Verify a product in Step 1 first.' });
                return;
            }
            if (!BULK_LEADS.length) {
                Swal.fire({ icon: 'error', title: 'No Data', text: 'Click Preview & Validate first.' });
                return;
            }

            const productCode      = bulkProductData.code;
            const productName      = bulkProductData.name;
            const productStock     = parseInt(bulkProductData.qty) || 0;
            const default_platform = document.getElementById('bulkDefaultPlatform').value;
            const date             = document.getElementById('order_date').value;
            const allowNonCurfox   = document.getElementById('allowNonCurfoxCity')?.checked ? '1' : '0';

            // Final stock check
            if (productStock < BULK_LEADS.length) {
                const proceed = await Swal.fire({
                    icon: 'warning',
                    title: 'Insufficient Stock',
                    html: `Stock available: <b>${productStock}</b><br>CSV rows: <b>${BULK_LEADS.length}</b><br><br>Only the first <b>${productStock}</b> orders will succeed. Continue?`,
                    showCancelButton: true,
                    confirmButtonText: 'Import Anyway',
                    confirmButtonColor: '#f59e0b',
                    cancelButtonColor: '#64748b',
                });
                if (!proceed.isConfirmed) return;
            }

            // Merge resolved city data into leads
            const leadsToSend = BULK_LEADS.map(lead => {
                const out = { ...lead };
                if (lead._resolved_city_id) {
                    out.destination_city_id    = lead._resolved_city_id;
                    out.destination_city_name  = lead._resolved_city_name;
                    out.destination_state_name = lead._resolved_state_name;
                    out.district               = lead._resolved_city_name;
                }
                delete out._resolved_city_id;
                delete out._resolved_city_name;
                delete out._resolved_state_name;
                return out;
            });

            Swal.fire({
                title: 'Confirm Bulk Import',
                html: `<div style="text-align:left;line-height:2;font-size:14px">
                    <div style="background:#f0fdf4;border:1px solid #86efac;border-radius:8px;padding:12px;margin-bottom:12px">
                        <div style="font-weight:700;color:#15803d;margin-bottom:4px">📦 Product</div>
                        <div>${escH(productName)}</div>
                        <div style="font-size:12px;color:#64748b">Code: ${escH(productCode)} &nbsp;|&nbsp; Stock: ${productStock} units</div>
                    </div>
                    <p><strong>Orders to create:</strong> ${leadsToSend.length}</p>
                    <p><strong>Default Platform:</strong> ${escH(default_platform)}</p>
                    ${allowNonCurfox === '1' ? '<p style="color:#f59e0b;font-size:12px">⚠ Non-Curfox cities will be saved as-is</p>' : ''}
                </div>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, Import All',
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#64748b',
                showLoaderOnConfirm: true,
                preConfirm: async () => {
                    try {
                        const fd = new FormData();
                        fd.append('product_code', productCode);
                        fd.append('default_platform', default_platform);
                        fd.append('date', date);
                        fd.append('leads', JSON.stringify(leadsToSend));
                        fd.append('allow_non_curfox', allowNonCurfox);

                        const res = await fetch(BULK_STORE_URL, {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': CSRF_TOKEN,
                                'Accept': 'application/json'
                            },
                            body: fd
                        });

                        const json = await res.json();

                        if (!res.ok || !json.success) {
                            throw new Error(json.message || 'Bulk import failed');
                        }
                        return json;
                    } catch (e) {
                        Swal.showValidationMessage(e.message);
                        return false;
                    }
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then(r => {
                if (r.isConfirmed && r.value && r.value.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Import Complete!',
                        html: `<p>${escH(r.value.message)}</p>` +
                            (r.value.errors && r.value.errors.length
                                ? `<div style="text-align:left;margin-top:10px;color:#ef4444;font-size:12px;max-height:120px;overflow-y:auto">${r.value.errors.slice(0,10).map(x => escH(x)).join('<br>')}</div>`
                                : ''),
                        confirmButtonColor: '#4f46e5'
                    }).then(() => window.location.href = r.value.redirect_url || PENDING_ORDERS_URL);
                }
            });
        });
    </script>
@endpush
