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
        .input-with-button { display:flex; gap:12px; align-items:flex-start; }
        .input-with-button .form-control { flex:1; }

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

        /* ── City Autocomplete ── */
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
        .city-count-badge { display:inline-block; padding:2px 7px; background:var(--primary); color:#fff; border-radius:999px; font-size:10px; font-weight:700; margin-left:6px; }
        .city-dropdown::-webkit-scrollbar { width:6px; }
        .city-dropdown::-webkit-scrollbar-track { background:var(--bg); }
        .city-dropdown::-webkit-scrollbar-thumb { background:var(--border); border-radius:3px; }
        .city-loading { display:flex; align-items:center; gap:8px; padding:14px; font-size:13px; color:var(--mid); }
        .city-loading .spinner { border-top-color:var(--primary); border-color:rgba(79,70,229,.2); }

        /* ── API Status Badge ── */
        .api-status { display:inline-flex; align-items:center; gap:6px; font-size:11px; font-weight:600; padding:3px 10px; border-radius:999px; }
        .api-status.ok  { background:#d1fae5; color:#065f46; }
        .api-status.err { background:#fee2e2; color:#991b1b; }
        .api-status.loading { background:#f1f5f9; color:var(--mid); }
    </style>
@endpush

@section('content')
    <div class="order-wrapper">

        {{-- Page Header --}}
        <div class="page-header">
            <h1 class="page-title">
                <span class="material-icons-round" style="color:var(--primary);font-size:30px">add_shopping_cart</span>
                Create New Order
            </h1>
            <div class="header-actions">
                <a href="{{ route('seller.order.csv-upload') }}" class="btn btn-warning">
                    <span class="material-icons-round" style="font-size:18px">upload_file</span>Upload CSV
                </a>
                <a href="{{ route('seller.pending-order') }}" class="btn btn-secondary">
                    <span class="material-icons-round" style="font-size:18px">list_alt</span>View Orders
                </a>
            </div>
        </div>

        {{-- Alerts --}}
        @if(session('error'))
            <div class="alert alert-danger"><span class="material-icons-round">error</span><div>{{ session('error') }}</div></div>
        @endif
        @if(session('success'))
            <div class="alert alert-success"><span class="material-icons-round">check_circle</span><div>{{ session('success') }}</div></div>
        @endif
        @if(isset($apiError))
            <div class="alert alert-warning">
                <span class="material-icons-round">warning</span>
                <div>{{ $apiError }} — <button type="button" onclick="reloadCities()" style="background:none;border:none;cursor:pointer;color:inherit;font-weight:700;text-decoration:underline">Retry</button></div>
            </div>
        @endif

        {{-- Order Form --}}
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
                            <div class="helper-text">
                                <i class="fas fa-info-circle"></i> Enter the product code and click Fetch
                            </div>
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

                {{-- Product Info Card --}}
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

                {{-- Hidden Fields --}}
                <input type="hidden" name="product_code"  id="product_code"  value="{{ $productCode ?? '' }}" required>
                <input type="hidden" name="product_id"    id="product_id"    value="{{ $productId ?? '' }}"   required>
                <input type="hidden" name="product_name"  id="product_name"  value="{{ $productName ?? '' }}" required>
                <input type="hidden" name="product_price" id="product_price" value="{{ $productPrice ?? '0' }}" required>
                <input type="hidden" name="product_image" id="product_image" value="{{ $productImage ?? '' }}">
                <input type="hidden" name="date"          id="order_date"    value="{{ date('Y-m-d') }}">
                {{-- Curfox city fields (populated by JS) --}}
                <input type="hidden" name="destination_city_id"    id="destination_city_id">
                <input type="hidden" name="destination_city_name"  id="destination_city_name">
                <input type="hidden" name="destination_state_name" id="destination_state_name">

                {{-- Order Details Grid --}}
                <div class="form-grid">
                    {{-- Customer Name --}}
                    <div class="form-group">
                        <label class="form-label">Customer Name <span class="required">*</span></label>
                        <input type="text" name="customer_name" id="customer_name" class="form-control"
                               placeholder="Enter customer name" value="{{ old('customer_name') }}" required>
                    </div>

                    {{-- Customer Phone --}}
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

                    {{-- Quantity --}}
                    <div class="form-group">
                        <label class="form-label">Quantity <span class="required">*</span></label>
                        <input type="number" name="quantity" id="quantity" class="form-control"
                               placeholder="Enter quantity" min="1"
                               value="{{ $quantity ?? 1 }}"
                               {{ !$productId ? 'disabled' : '' }} required>
                        <div class="helper-text">
                            Available: <span id="availableStock">{{ $productStock ?? '0' }}</span> units
                        </div>
                    </div>

                    {{-- District / City — Curfox API powered ── --}}
                    <div class="form-group">
                        <label class="form-label">
                            <span class="material-icons-round" style="font-size:16px;vertical-align:middle;color:var(--primary)">local_shipping</span>
                            Delivery City / District
                            <span class="required">*</span>
                            <span id="apiStatusBadge" class="api-status loading" style="margin-left:6px">
                                <span class="spinner" style="width:10px;height:10px;border-top-color:currentColor;border-color:rgba(0,0,0,.15)"></span> Loading…
                            </span>
                        </label>
                        <div class="city-autocomplete-wrapper">
                            <input type="text" name="district" id="district" class="form-control"
                                   placeholder="Type to search delivery city…"
                                   value="{{ old('district') }}"
                                   autocomplete="off"
                                   role="combobox" aria-autocomplete="list"
                                   aria-expanded="false" aria-haspopup="listbox"
                                   required>
                            <button type="button" class="city-clear-btn" id="cityClearBtn" title="Clear">
                                <span class="material-icons-round" style="font-size:16px">close</span>
                            </button>
                            <div class="city-dropdown" id="cityDropdown" role="listbox"></div>
                        </div>
                        <div class="helper-text" id="cityHelperText">
                            <span class="material-icons-round" style="font-size:12px;vertical-align:middle">search</span>
                            Cities loaded from Curfox delivery API
                        </div>
                    </div>

                    {{-- Platform --}}
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

                    {{-- Payment Method --}}
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

                {{-- Delivery Address --}}
                <div class="form-group" style="margin-bottom:24px">
                    <label class="form-label">Delivery Address</label>
                    <textarea name="customer_address" id="customer_address" class="form-control"
                              placeholder="Enter delivery address">{{ old('customer_address') }}</textarea>
                </div>

                {{-- Order Notes --}}
                <div class="form-group" style="margin-bottom:24px">
                    <label class="form-label">Order Notes / Remarks</label>
                    <textarea name="remark" id="remark" class="form-control"
                              placeholder="Add any special instructions (optional)">{{ old('remark') }}</textarea>
                </div>

                {{-- Form Actions --}}
                <div class="form-actions">
                    <button type="button" id="printBtn" class="btn btn-secondary" {{ !$productId ? 'disabled' : '' }}>
                        <span class="material-icons-round" style="font-size:18px">print</span>Print Order
                    </button>
                    <a href="{{ route('seller.complete-order') }}" class="btn btn-secondary">
                        <span class="material-icons-round" style="font-size:18px">list_alt</span>View Orders
                    </a>
                    <button type="button" id="submitBtn" class="btn btn-success" {{ !$productId ? 'disabled' : '' }}>
                        <span class="material-icons-round" style="font-size:18px">shopping_cart</span>Create Order
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // ─── City data from Curfox API ─────────────────────────────────────────
        let CITIES = [];         // [{id, name, state_id, state_name}, …]
        let citiesLoaded = false;

        const CITIES_API_URL = '{{ route("seller.order.cities.json") }}';
        const COMPLETE_ORDER_URL = '{{ route("seller.complete-order") }}';

        // Pre-load from server-side data if available (avoids an extra AJAX round-trip)
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
            // Fetch via AJAX
            loadCitiesFromApi();
        }

        async function loadCitiesFromApi() {
            updateApiStatusBadge('loading', 'Loading cities…');
            try {
                const res  = await fetch(CITIES_API_URL, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } });
                const json = await res.json();
                if (json.success && Array.isArray(json.data)) {
                    CITIES = json.data;
                    citiesLoaded = true;
                    updateApiStatusBadge('ok', CITIES.length + ' cities ready');
                    document.getElementById('cityHelperText').innerHTML =
                        '<span class="material-icons-round" style="font-size:12px;vertical-align:middle">check_circle</span> ' +
                        CITIES.length + ' cities loaded from Curfox API';
                } else {
                    throw new Error(json.message || 'Failed to load cities');
                }
            } catch (err) {
                citiesLoaded = false;
                updateApiStatusBadge('err', 'City load failed');
                document.getElementById('cityHelperText').innerHTML =
                    '<span style="color:#ef4444">⚠ Could not load cities. <button onclick="reloadCities()" style="background:none;border:none;cursor:pointer;color:#4f46e5;font-weight:700;text-decoration:underline">Retry</button></span>';
                console.error('City load error:', err);
            }
        }

        function reloadCities() {
            CITIES = [];
            citiesLoaded = false;
            loadCitiesFromApi();
        }

        function updateApiStatusBadge(type, text) {
            const badge = document.getElementById('apiStatusBadge');
            badge.className = 'api-status ' + type;
            badge.innerHTML = (type === 'loading'
                ? '<span class="spinner" style="width:10px;height:10px;border-top-color:currentColor;border-color:rgba(0,0,0,.15)"></span> '
                : (type === 'ok' ? '✓ ' : '✕ ')) + text;
        }

        // ─── City Autocomplete ─────────────────────────────────────────────────
        (function () {
            const input      = document.getElementById('district');
            const dropdown   = document.getElementById('cityDropdown');
            const clearBtn   = document.getElementById('cityClearBtn');
            const helperText = document.getElementById('cityHelperText');

            let activeIndex    = -1;
            let debounceTimer;
            let currentMatches = [];

            function esc(s) {
                return s.replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m]));
            }

            function highlight(text, q) {
                if (!q) return esc(text);
                const re = new RegExp('(' + q.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + ')', 'gi');
                return esc(text).replace(re, '<mark>$1</mark>');
            }

            function openDropdown(matches, query) {
                currentMatches = matches;
                activeIndex    = -1;
                dropdown.innerHTML = '';

                if (!citiesLoaded) {
                    dropdown.innerHTML = '<div class="city-loading"><span class="spinner"></span> Loading cities from API…</div>';
                    dropdown.classList.add('open');
                    return;
                }

                if (matches.length === 0) {
                    dropdown.innerHTML = '<div class="city-dropdown-empty">No cities found for "' + esc(query) + '"</div>';
                } else {
                    const frag  = document.createDocumentFragment();
                    const limit = Math.min(matches.length, 80);
                    for (let i = 0; i < limit; i++) {
                        const m    = matches[i];
                        const item = document.createElement('div');
                        item.className = 'city-dropdown-item';
                        item.setAttribute('role', 'option');
                        item.setAttribute('data-index', i);
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
                    if (matches.length > 80) {
                        const more = document.createElement('div');
                        more.className = 'city-dropdown-empty';
                        more.textContent = (matches.length - 80) + ' more — keep typing to narrow down';
                        frag.appendChild(more);
                    }
                    dropdown.appendChild(frag);
                }
                dropdown.classList.add('open');
                input.setAttribute('aria-expanded', 'true');
            }

            function closeDropdown() {
                dropdown.classList.remove('open');
                dropdown.innerHTML = '';
                input.setAttribute('aria-expanded', 'false');
                activeIndex    = -1;
                currentMatches = [];
            }

            function selectCity(city) {
                input.value = city.name;
                // Populate hidden fields for Curfox API
                document.getElementById('destination_city_id').value    = city.id;
                document.getElementById('destination_city_name').value  = city.name;
                document.getElementById('destination_state_name').value = city.state_name;

                clearBtn.classList.add('visible');
                helperText.innerHTML =
                    '<span class="city-badge-selected">' +
                    '<span class="material-icons-round" style="font-size:12px">check_circle</span>' +
                    esc(city.name) + ' · ' + esc(city.state_name) + '</span>';
                closeDropdown();
                input.focus();
            }

            function clearCity() {
                input.value = '';
                document.getElementById('destination_city_id').value    = '';
                document.getElementById('destination_city_name').value  = '';
                document.getElementById('destination_state_name').value = '';
                clearBtn.classList.remove('visible');
                helperText.innerHTML =
                    '<span class="material-icons-round" style="font-size:12px;vertical-align:middle">search</span> ' +
                    'Type to search delivery cities';
                closeDropdown();
                input.focus();
            }

            function search(query) {
                if (!query || query.length < 1) { closeDropdown(); return; }
                const q       = query.toLowerCase().trim();
                const exact   = [], starts = [], contains = [];
                for (const city of CITIES) {
                    const l = city.name.toLowerCase();
                    if (l === q)           exact.push(city);
                    else if (l.startsWith(q)) starts.push(city);
                    else if (l.includes(q))   contains.push(city);
                }
                openDropdown([...exact, ...starts, ...contains], query);
            }

            function setActiveItem(index) {
                const items = dropdown.querySelectorAll('.city-dropdown-item');
                items.forEach((el, i) => el.classList.toggle('active', i === index));
                if (items[index]) items[index].scrollIntoView({ block: 'nearest' });
            }

            input.addEventListener('input', function () {
                const v = this.value;
                clearBtn.classList.toggle('visible', v.length > 0);
                // Clear hidden fields when user types again
                document.getElementById('destination_city_id').value    = '';
                document.getElementById('destination_city_name').value  = '';
                document.getElementById('destination_state_name').value = '';
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => search(v), 120);
            });

            input.addEventListener('keydown', function (e) {
                const items = dropdown.querySelectorAll('.city-dropdown-item');
                if (!dropdown.classList.contains('open')) {
                    if (e.key === 'ArrowDown' && this.value) search(this.value);
                    return;
                }
                switch (e.key) {
                    case 'ArrowDown':
                        e.preventDefault();
                        activeIndex = Math.min(activeIndex + 1, items.length - 1);
                        setActiveItem(activeIndex);
                        break;
                    case 'ArrowUp':
                        e.preventDefault();
                        activeIndex = Math.max(activeIndex - 1, -1);
                        setActiveItem(activeIndex);
                        break;
                    case 'Enter':
                        e.preventDefault();
                        if (activeIndex >= 0 && currentMatches[activeIndex]) selectCity(currentMatches[activeIndex]);
                        break;
                    case 'Escape': closeDropdown(); break;
                    case 'Tab':
                        if (activeIndex >= 0 && currentMatches[activeIndex]) selectCity(currentMatches[activeIndex]);
                        else closeDropdown();
                        break;
                }
            });

            input.addEventListener('focus', function () { if (this.value.length > 0) search(this.value); });
            input.addEventListener('blur', function () { setTimeout(closeDropdown, 200); });
            clearBtn.addEventListener('click', clearCity);

            // If old() value exists, show the badge
            if (input.value) {
                clearBtn.classList.add('visible');
                helperText.innerHTML = '<span class="city-badge-selected"><span class="material-icons-round" style="font-size:12px">check_circle</span>' + esc(input.value) + '</span>';
            }
        })();

        // ─── Globals ───────────────────────────────────────────────────────────
        let currentProductData = null;

        document.addEventListener('DOMContentLoaded', function () {
            @if($productId && $productCode && $productName && $productPrice)
                currentProductData = {
                id: '{{ $productId }}', code: '{{ $productCode }}',
                name: '{{ $productName }}', display_price: parseFloat('{{ $productPrice }}') || 0,
                qty: parseInt('{{ $productStock }}') || 0, image: '{{ $productImage }}'
            };
            document.getElementById('submitBtn').disabled  = false;
            document.getElementById('printBtn').disabled   = false;
            document.getElementById('resetBtn').style.display = 'inline-flex';
            document.getElementById('quantity').disabled   = false;
            @endif

            setTimeout(() => {
                document.querySelectorAll('.alert').forEach(el => {
                    el.style.transition = 'opacity .5s';
                    el.style.opacity = '0';
                    setTimeout(() => el.remove(), 500);
                });
            }, 5000);
        });

        // ─── Fetch Product ─────────────────────────────────────────────────────
        document.getElementById('fetchProductBtn').addEventListener('click', function () {
            const code = document.getElementById('searchProductCode').value.trim();
            if (!code) {
                Swal.fire({ icon:'error', title:'Missing Code', text:'Please enter a product code', confirmButtonColor:'#4f46e5' });
                return;
            }
            fetchProductByCode(code);
        });

        document.getElementById('searchProductCode').addEventListener('keypress', function (e) {
            if (e.key === 'Enter') { e.preventDefault(); document.getElementById('fetchProductBtn').click(); }
        });

        async function fetchProductByCode(productCode) {
            const btn = document.getElementById('fetchProductBtn');
            const orig = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner"></span> Fetching…';
            try {
                const url  = '/seller/order/product/' + encodeURIComponent(productCode) + '/details';
                const res  = await fetch(url, { headers: { 'X-Requested-With':'XMLHttpRequest', 'Accept':'application/json' } });
                const data = await res.json();
                if (!res.ok) throw new Error(data.message || 'HTTP ' + res.status);
                if (data.success && data.product) {
                    displayProductInfo(data.product);
                    Swal.fire({ icon:'success', title:'Product Found!', text: data.product.name + ' loaded', timer:2000, showConfirmButton:false });
                } else throw new Error(data.message || 'Invalid response');
            } catch (err) {
                Swal.fire({ icon:'error', title:'Not Found', text: err.message, confirmButtonColor:'#4f46e5' });
                resetForm();
            } finally {
                btn.disabled = false; btn.innerHTML = orig;
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
            document.getElementById('displayProductName').textContent = product.name;
            document.getElementById('displayProductCode').textContent = product.code;
            document.getElementById('displayProductPrice').textContent = (parseFloat(product.display_price) || 0).toFixed(2);
            const stock = parseInt(product.qty) || 0;
            document.getElementById('displayProductStock').textContent = stock;
            document.getElementById('availableStock').textContent = stock;
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
                imgEl.src = product.image; imgEl.style.display = 'block'; phEl.style.display = 'none';
                imgEl.onerror = () => { imgEl.style.display='none'; phEl.style.display='flex'; };
            } else {
                imgEl.style.display = 'none'; phEl.style.display = 'flex';
            }
            const qty = document.getElementById('quantity');
            if (stock > 0) { qty.max = stock; qty.disabled = false; if (parseInt(qty.value) > stock) qty.value = stock; }
            else { qty.disabled = true; qty.value = 0; }
            document.getElementById('submitBtn').disabled = false;
            document.getElementById('printBtn').disabled  = false;
        }

        // ─── Reset ─────────────────────────────────────────────────────────────
        document.getElementById('resetBtn').addEventListener('click', function () {
            Swal.fire({
                title:'Reset Form?', text:'All product and order info will be cleared.',
                icon:'warning', showCancelButton:true,
                confirmButtonColor:'#ef4444', cancelButtonColor:'#64748b',
                confirmButtonText:'Yes, Reset'
            }).then(r => { if (r.isConfirmed) { resetForm(); Swal.fire({ icon:'success', title:'Cleared', timer:1500, showConfirmButton:false }); } });
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
                if (el) el.value = id === 'quantity' ? '1' : '';
            });
            document.getElementById('district').value = '';
            document.getElementById('cityClearBtn').classList.remove('visible');
            document.getElementById('cityHelperText').innerHTML =
                '<span class="material-icons-round" style="font-size:12px;vertical-align:middle">search</span> Type to search delivery cities';
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

        // ─── Quantity Validation ───────────────────────────────────────────────
        document.getElementById('quantity').addEventListener('input', function () {
            if (!currentProductData) return;
            const max = parseInt(currentProductData.qty) || 0;
            if (parseInt(this.value) > max) {
                this.value = max;
                Swal.fire({ icon:'warning', title:'Exceeds Stock', text:'Max available: ' + max + ' units', timer:2000, showConfirmButton:false });
            }
        });

        // ─── Print ─────────────────────────────────────────────────────────────
        document.getElementById('printBtn').addEventListener('click', function () {
            if (!currentProductData) {
                Swal.fire({ icon:'error', title:'No Product', text:'Please fetch a product first.', confirmButtonColor:'#4f46e5' });
                return;
            }
            const n = document.getElementById('customer_name').value;
            const p = document.getElementById('customer_phone').value;
            if (!n || !p) {
                Swal.fire({ icon:'error', title:'Missing Info', text:'Fill customer name and phone.', confirmButtonColor:'#4f46e5' });
                return;
            }
            const p2   = document.getElementById('customer_phone_2').value;
            const addr = document.getElementById('customer_address').value;
            const city = document.getElementById('district').value;
            const qty  = document.getElementById('quantity').value;
            const state = document.getElementById('destination_state_name').value;
            const pw = window.open('','','height=600,width=800');
            pw.document.write('<html><head><title>Order Print</title><style>body{font-family:Arial;padding:20px}h1{color:#4f46e5}table{width:100%;border-collapse:collapse}td{padding:10px;border-bottom:1px solid #eee}td:first-child{font-weight:bold;width:40%}</style></head><body>');
            pw.document.write('<h1>Order Details</h1><p>Date: ' + new Date().toLocaleDateString() + '</p><table>');
            pw.document.write('<tr><td>Product:</td><td>' + currentProductData.name + '</td></tr>');
            pw.document.write('<tr><td>Code:</td><td>' + currentProductData.code + '</td></tr>');
            pw.document.write('<tr><td>Price:</td><td>Rs. ' + currentProductData.display_price + '</td></tr>');
            pw.document.write('<tr><td>Qty:</td><td>' + qty + '</td></tr>');
            pw.document.write('<tr><td>Total:</td><td>Rs. ' + (currentProductData.display_price * qty).toFixed(2) + '</td></tr>');
            pw.document.write('<tr><td>Customer:</td><td>' + n + '</td></tr>');
            pw.document.write('<tr><td>Phone:</td><td>' + p + (p2 ? ' / '+p2 : '') + '</td></tr>');
            if (addr) pw.document.write('<tr><td>Address:</td><td>' + addr + '</td></tr>');
            if (city) pw.document.write('<tr><td>City:</td><td>' + city + (state ? ', '+state : '') + '</td></tr>');
            pw.document.write('</table></body></html>');
            pw.document.close(); pw.focus();
            setTimeout(() => { pw.print(); pw.close(); }, 250);
        });

        // ─── Submit ────────────────────────────────────────────────────────────
        document.getElementById('submitBtn').addEventListener('click', function (e) {
            e.preventDefault();

            if (!currentProductData || !document.getElementById('product_id').value) {
                Swal.fire({ icon:'error', title:'No Product', text:'Please fetch a product first.', confirmButtonColor:'#4f46e5' });
                return;
            }

            const customerName  = document.getElementById('customer_name').value.trim();
            const customerPhone = document.getElementById('customer_phone').value.trim();
            const paymentMethod = document.getElementById('payment_method').value;
            const districtVal   = document.getElementById('district').value.trim();
            const cityId        = document.getElementById('destination_city_id').value;
            const quantity      = parseInt(document.getElementById('quantity').value) || 0;
            const stock         = parseInt(currentProductData.qty) || 0;

            if (!customerName)  { Swal.fire({ icon:'error', title:'Missing', text:'Enter customer name.', confirmButtonColor:'#4f46e5' }); return; }
            if (!customerPhone) { Swal.fire({ icon:'error', title:'Missing', text:'Enter customer phone.', confirmButtonColor:'#4f46e5' }); return; }
            if (!paymentMethod) { Swal.fire({ icon:'error', title:'Missing', text:'Select payment method.', confirmButtonColor:'#4f46e5' }); return; }
            if (!districtVal)   { Swal.fire({ icon:'error', title:'Missing', text:'Select a delivery city from the dropdown.', confirmButtonColor:'#4f46e5' }); return; }
            if (!cityId)        { Swal.fire({ icon:'error', title:'Invalid City', text:'Please select a city from the suggestions list to validate it with the delivery API.', confirmButtonColor:'#4f46e5' }); return; }
            if (quantity <= 0)  { Swal.fire({ icon:'error', title:'Invalid Qty', text:'Enter a valid quantity.', confirmButtonColor:'#4f46e5' }); return; }
            if (quantity > stock) { Swal.fire({ icon:'error', title:'Exceeds Stock', text:'Only ' + stock + ' units available.', confirmButtonColor:'#4f46e5' }); return; }

            const p2    = document.getElementById('customer_phone_2').value.trim();
            const state = document.getElementById('destination_state_name').value;

            Swal.fire({
                title: 'Create Order?',
                html: `<div style="text-align:left;margin-top:16px;line-height:1.8">
                    <p><strong>Product:</strong> ${currentProductData.name}</p>
                    <p><strong>Qty:</strong> ${quantity}</p>
                    <p><strong>Customer:</strong> ${customerName}</p>
                    <p><strong>Phone:</strong> ${customerPhone}${p2 ? ' / '+p2 : ''}</p>
                    <p><strong>City:</strong> ${districtVal}${state ? ', '+state : ''}</p>
                    <p><strong>Total:</strong> Rs. ${(currentProductData.display_price * quantity).toFixed(2)}</p>
                    </div>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981', cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, Create Order',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    Swal.showLoading();
                    const form = document.getElementById('orderForm');
                    return fetch(form.action, {
                        method: 'POST',
                        body: new FormData(form),
                        headers: { 'X-Requested-With':'XMLHttpRequest', 'X-CSRF-TOKEN':'{{ csrf_token() }}' }
                    })
                        .then(r => r.ok ? r.json() : r.json().then(e => { throw new Error(e.message || 'Error'); }))
                        .catch(err => Swal.showValidationMessage('Error: ' + err.message));
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then(result => {
                if (result.isConfirmed && result.value && result.value.success) {
                    Swal.fire({
                        icon:'success', title:'Order Created!',
                        html:'<p>' + result.value.message + '</p><p style="color:#64748b;font-size:13px">Redirecting to pending orders…</p>',
                        timer:2500, timerProgressBar:true, showConfirmButton:false,
                        willClose: () => { window.location.href = '{{ route("seller.pending-order") }}'; }
                    });
                } else if (result.value && !result.value.success) {
                    Swal.fire({ icon:'error', title:'Error', text:result.value.message || 'Failed.', confirmButtonColor:'#4f46e5' });
                }
            });
        });

        document.getElementById('orderForm').addEventListener('submit', e => { if (e.submitter?.id === 'submitBtn') e.preventDefault(); });
    </script>
@endpush
