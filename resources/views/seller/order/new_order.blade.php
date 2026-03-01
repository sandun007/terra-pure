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
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --primary-light: #eef2ff;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --dark: #0f172a;
            --mid: #64748b;
            --border: #e2e8f0;
            --surface: #ffffff;
            --bg: #f8fafc;
            --mono: 'DM Mono', monospace;
            --sans: 'DM Sans', sans-serif;
            --radius-xl: 16px;
            --radius-lg: 12px;
            --radius-md: 8px;
            --shadow-sm: 0 2px 8px rgba(0,0,0,.07);
            --shadow-md: 0 6px 20px rgba(0,0,0,.09);
        }

        body { font-family: var(--sans); background: var(--bg); }

        .order-wrapper { padding: 32px; max-width: 1400px; margin: 0 auto; }
        @media (max-width: 768px) { .order-wrapper { padding: 16px; } }

        .page-header {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 28px; flex-wrap: wrap; gap: 16px;
        }
        .page-title {
            font-size: 26px; font-weight: 800; color: var(--dark);
            display: flex; align-items: center; gap: 10px; margin: 0;
        }
        .header-actions { display: flex; gap: 12px; flex-wrap: wrap; }

        .form-container {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: var(--radius-xl); padding: 32px; box-shadow: var(--shadow-sm);
        }
        @media (max-width: 768px) { .form-container { padding: 20px; } }

        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px; }
        @media (max-width: 768px) { .form-grid { grid-template-columns: 1fr; gap: 16px; } }

        .form-group { margin-bottom: 0; }
        .form-label { display: block; font-size: 14px; font-weight: 600; color: var(--dark); margin-bottom: 8px; }
        .form-label .required { color: var(--danger); margin-left: 2px; }

        .form-control {
            width: 100%; padding: 12px 16px; border: 2px solid var(--border);
            border-radius: var(--radius-md); font-family: var(--sans); font-size: 14px;
            transition: all .2s; background: var(--surface); box-sizing: border-box;
        }
        .form-control:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(79,70,229,.1); }
        .form-control:disabled { background: #f1f5f9; cursor: not-allowed; }
        textarea.form-control { resize: vertical; min-height: 100px; }

        .phone-pair { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
        @media (max-width: 480px) { .phone-pair { grid-template-columns: 1fr; } }

        .product-search-section {
            background: linear-gradient(135deg,#ffffff 0%,#f8fafc 100%);
            border: 2px solid var(--border); border-radius: var(--radius-lg);
            padding: 24px; margin-bottom: 24px;
        }
        .search-header { font-size: 16px; font-weight: 700; color: var(--dark); margin-bottom: 16px; display: flex; align-items: center; gap: 8px; }
        .input-with-button { display: flex; gap: 12px; align-items: flex-start; }
        .input-with-button .form-control { flex: 1; }

        .btn {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 12px 24px; border-radius: var(--radius-md);
            font-size: 14px; font-weight: 600; border: none; cursor: pointer;
            transition: all .2s; font-family: var(--sans); text-decoration: none; white-space: nowrap;
        }
        .btn-primary   { background: var(--primary); color: #fff; }
        .btn-primary:hover   { background: var(--primary-hover); transform: translateY(-1px); }
        .btn-secondary { background: var(--dark); color: #fff; }
        .btn-secondary:hover { background: #1e293b; transform: translateY(-1px); }
        .btn-success   { background: var(--success); color: #fff; }
        .btn-success:hover   { background: #059669; transform: translateY(-1px); }
        .btn-warning   { background: var(--warning); color: #fff; }
        .btn-warning:hover   { background: #d97706; transform: translateY(-1px); }
        .btn-danger    { background: var(--danger); color: #fff; }
        .btn-danger:hover    { background: #dc2626; }
        .btn:disabled  { opacity: .5; cursor: not-allowed; transform: none !important; }

        .product-info-card {
            background: var(--surface); border: 2px solid var(--border);
            border-radius: var(--radius-lg); padding: 20px; margin-bottom: 24px; display: none;
        }
        .product-info-card.show { display: block; animation: slideDown .3s ease-out; }
        @keyframes slideDown { from { opacity:0; transform:translateY(-10px); } to { opacity:1; transform:translateY(0); } }

        .product-info-header { display: flex; align-items: center; gap: 16px; margin-bottom: 16px; padding-bottom: 16px; border-bottom: 2px solid var(--border); }
        .product-image-preview  { width:80px; height:80px; border-radius:var(--radius-md); object-fit:cover; border:2px solid var(--border); background:#f1f5f9; }
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
        .alert-info    { background:#dbeafe; border:1px solid #3b82f6; color:#1e40af; }

        .spinner { display:inline-block; width:16px; height:16px; border:2px solid rgba(255,255,255,.3); border-radius:50%; border-top-color:#fff; animation:spin .6s linear infinite; }
        @keyframes spin { to { transform:rotate(360deg); } }

        .helper-text { font-size:12px; color:var(--mid); margin-top:4px; }
        .phone-sub-label { font-size:11px; color:var(--mid); font-weight:600; margin-bottom:4px; display:block; }

        /* ── City / District Autocomplete ── */
        .city-autocomplete-wrapper { position: relative; }

        .city-autocomplete-wrapper .form-control {
            padding-right: 40px;
        }

        .city-clear-btn {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: var(--mid);
            padding: 0;
            display: none;
            align-items: center;
            justify-content: center;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            transition: all .2s;
            z-index: 2;
        }
        .city-clear-btn:hover { background: #fee2e2; color: var(--danger); }
        .city-clear-btn.visible { display: flex; }

        .city-dropdown {
            position: absolute;
            top: calc(100% + 4px);
            left: 0;
            right: 0;
            background: var(--surface);
            border: 2px solid var(--primary);
            border-radius: var(--radius-md);
            max-height: 220px;
            overflow-y: auto;
            z-index: 9999;
            box-shadow: var(--shadow-md);
            display: none;
        }
        .city-dropdown.open { display: block; animation: slideDown .15s ease-out; }

        .city-dropdown-item {
            padding: 10px 14px;
            font-size: 14px;
            color: var(--dark);
            cursor: pointer;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 8px;
            transition: background .1s;
        }
        .city-dropdown-item:last-child { border-bottom: none; }
        .city-dropdown-item:hover,
        .city-dropdown-item.active { background: var(--primary-light); color: var(--primary); }

        .city-dropdown-item mark {
            background: none;
            color: var(--primary);
            font-weight: 700;
            padding: 0;
        }

        .city-dropdown-empty {
            padding: 14px;
            font-size: 13px;
            color: var(--mid);
            text-align: center;
        }

        .city-badge-selected {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            background: var(--primary-light);
            color: var(--primary);
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            margin-top: 6px;
        }
        .city-count-badge {
            display: inline-block;
            padding: 2px 7px;
            background: var(--primary);
            color: #fff;
            border-radius: 999px;
            font-size: 10px;
            font-weight: 700;
            margin-left: 6px;
        }

        /* scrollbar for dropdown */
        .city-dropdown::-webkit-scrollbar { width: 6px; }
        .city-dropdown::-webkit-scrollbar-track { background: var(--bg); }
        .city-dropdown::-webkit-scrollbar-thumb { background: var(--border); border-radius: 3px; }
        .city-dropdown::-webkit-scrollbar-thumb:hover { background: var(--primary); }
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
                    <span class="material-icons-round" style="font-size:18px">upload_file</span>
                    Upload CSV Leads
                </a>
                <a href="{{ route('seller.pending-order') }}" class="btn btn-secondary">
                    <span class="material-icons-round" style="font-size:18px">list_alt</span>
                    View Orders
                </a>
            </div>
        </div>

        {{-- Session Alerts --}}
        @if(session('error'))
            <div class="alert alert-danger">
                <span class="material-icons-round">error</span>
                <div>{{ session('error') }}</div>
            </div>
        @endif
        @if(session('success'))
            <div class="alert alert-success">
                <span class="material-icons-round">check_circle</span>
                <div>{{ session('success') }}</div>
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
                                   autocomplete="off"
                                   value="{{ $productCode ?? '' }}">
                            <div class="helper-text">
                                <i class="fas fa-info-circle"></i>
                                Enter the product code and click Fetch to load product details
                            </div>
                        </div>
                        <button type="button" id="fetchProductBtn" class="btn btn-primary">
                            <span class="material-icons-round" style="font-size:18px">search</span>
                            Fetch Product
                        </button>
                        <button type="button" id="resetBtn" class="btn btn-danger"
                                style="display:{{ $productId ? 'inline-flex' : 'none' }}">
                            <span class="material-icons-round" style="font-size:18px">refresh</span>
                            Reset
                        </button>
                    </div>
                </div>

                {{-- Product Info Card --}}
                <div id="productInfoCard" class="product-info-card"
                     style="display:{{ $productId ? 'block' : 'none' }}">
                    <div class="product-info-header">
                        <img id="productImagePreview" src="{{ $productImage ?? '' }}" alt="Product"
                             class="product-image-preview"
                             style="display:{{ $productImage ? 'block' : 'none' }}">
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
                            <div class="detail-value" style="color:var(--danger)">
                                Rs. <span id="displayProductPrice">{{ $productPrice ?? '0.00' }}</span>
                            </div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Available Stock</div>
                            <div class="detail-value">
                                <span id="displayProductStock">{{ $productStock ?? '0' }}</span> units
                            </div>
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

                {{-- Order Details Grid --}}
                <div class="form-grid">

                    {{-- Customer Name --}}
                    <div class="form-group">
                        <label class="form-label">Customer Name <span class="required">*</span></label>
                        <input type="text" name="customer_name" id="customer_name" class="form-control"
                               placeholder="Enter customer name"
                               value="{{ old('customer_name') }}" required>
                    </div>

                    {{-- Customer Phone --}}
                    <div class="form-group">
                        <label class="form-label">Customer Phone <span class="required">*</span></label>
                        <div class="phone-pair">
                            <div>
                                <span class="phone-sub-label">Primary Number</span>
                                <input type="text" name="customer_phone" id="customer_phone"
                                       class="form-control"
                                       placeholder="e.g. 077 123 4567"
                                       value="{{ old('customer_phone') }}" required>
                            </div>
                            <div>
                                <span class="phone-sub-label">Secondary Number <small style="font-weight:400">(optional)</small></span>
                                <input type="text" name="customer_phone_2" id="customer_phone_2"
                                       class="form-control"
                                       placeholder="e.g. 071 765 4321"
                                       value="{{ old('customer_phone_2') }}">
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

                    {{-- District / City with Autocomplete --}}
                    <div class="form-group">
                        <label class="form-label">
                            <span class="material-icons-round" style="font-size:16px;vertical-align:middle;color:var(--primary)">location_on</span>
                            District / City
                            <span class="city-count-badge">2835</span>
                        </label>
                        <div class="city-autocomplete-wrapper">
                            <input type="text"
                                   name="district"
                                   id="district"
                                   class="form-control"
                                   placeholder="Type to search city or district..."
                                   value="{{ old('district') }}"
                                   autocomplete="off"
                                   role="combobox"
                                   aria-autocomplete="list"
                                   aria-expanded="false"
                                   aria-haspopup="listbox">
                            <button type="button" class="city-clear-btn" id="cityClearBtn" title="Clear selection" aria-label="Clear city">
                                <span class="material-icons-round" style="font-size:16px">close</span>
                            </button>
                            <div class="city-dropdown" id="cityDropdown" role="listbox" aria-label="City suggestions"></div>
                        </div>
                        <div class="helper-text" id="cityHelperText">
                            <span class="material-icons-round" style="font-size:12px;vertical-align:middle">search</span>
                            Start typing to search from 2,835 Sri Lanka cities/districts
                        </div>
                    </div>

                    {{-- Platform --}}
                    <div class="form-group">
                        <label class="form-label">Platform</label>
                        <select name="platform" id="platform" class="form-control">
                            <option value="">Select Platform</option>
                            <option value="fb"       {{ old('platform') == 'fb'       ? 'selected' : '' }}>Facebook</option>
                            <option value="ig"       {{ old('platform') == 'ig'       ? 'selected' : '' }}>Instagram</option>
                            <option value="tiktok"   {{ old('platform') == 'tiktok'   ? 'selected' : '' }}>TikTok</option>
                            <option value="website"  {{ old('platform') == 'website'  ? 'selected' : '' }}>Website</option>
                            <option value="phone"    {{ old('platform') == 'phone'    ? 'selected' : '' }}>Phone</option>
                            <option value="other"    {{ old('platform') == 'other'    ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    {{-- Payment Method --}}
                    <div class="form-group">
                        <label class="form-label">Payment Method <span class="required">*</span></label>
                        <select name="payment_method" id="payment_method" class="form-control" required>
                            <option value="">Select Payment Method</option>
                            <option value="cash"          {{ old('payment_method') == 'cash'          ? 'selected' : '' }}>Cash on Delivery</option>
                            <option value="card"          {{ old('payment_method') == 'card'          ? 'selected' : '' }}>Card Payment</option>
                            <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="online"        {{ old('payment_method') == 'online'        ? 'selected' : '' }}>Online Payment</option>
                        </select>
                    </div>
                </div>

                {{-- Delivery Address --}}
                <div class="form-group" style="margin-bottom:24px">
                    <label class="form-label">Delivery Address</label>
                    <textarea name="customer_address" id="customer_address" class="form-control"
                              placeholder="Enter delivery address (optional)">{{ old('customer_address') }}</textarea>
                </div>

                {{-- Order Notes --}}
                <div class="form-group" style="margin-bottom:24px">
                    <label class="form-label">Order Notes / Remarks</label>
                    <textarea name="remark" id="remark" class="form-control"
                              placeholder="Add any special instructions or notes (optional)">{{ old('remark') }}</textarea>
                </div>

                {{-- Form Actions --}}
                <div class="form-actions">
                    <button type="button" id="printBtn" class="btn btn-secondary" {{ !$productId ? 'disabled' : '' }}>
                        <span class="material-icons-round" style="font-size:18px">print</span>
                        Print Order
                    </button>
                    <a href="{{ route('seller.complete-order') }}" class="btn btn-secondary">
                        <span class="material-icons-round" style="font-size:18px">list_alt</span>
                        View Orders
                    </a>
                    <button type="button" id="submitBtn" class="btn btn-success" {{ !$productId ? 'disabled' : '' }}>
                        <span class="material-icons-round" style="font-size:18px">shopping_cart</span>
                        Create Order
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // ─── Sri Lanka Cities Data ───────────────────────────────────────────
        const SRI_LANKA_CITIES = ["Aaliyavalai", "Achuveli", "Adampan", "Addalaichenai", "Adiambalama ( Negombo )", "Adikarigama", "Adippala", "Agalawatta", "Agaliya", "Agarapathana", "Agbopura", "Aggona", "Aguruwella", "Ahangama", "Ahatugaswewa Walioya", "Ahungalla", "Aiwela (bibila monaragala)", "Akaragama", "Akarawita", "Akarella", "Akkaraipattu", "Akkarasiyaya", "Akkaravan Kulam", "Akkarayan Kulam", "Akmeemana", "Akuramboda", "Akurana", "Akuranboda", "Akuressa", "Aladeniya", "Alahengama", "Alahitiyawa", "Alampil", "Alapaladeniya", "Alaveddi", "Alawala", "Alawatugoda", "Alawatura", "Alawatuwala", "Alawwa", "Algama", "Algama (Gampaha)", "Algama (Kegalle)", "Allappity", "Allawewa Kebithigollawa", "Allepola", "Alokapura", "Alpitiya ( Ratnapura )", "Alubomulla", "Aludeniya", "Aluketiyawa", "Alutgama", "Aluthgama ( Galle )", "Aluthgama ( Kalutara )", "Aluthwala", "Alutnuwara", "Aluttaramma", "Alutwewa", "Aluvihara", "Alwatta", "Amalagoda", "Ambadandegama", "Ambagahapelessa", "Ambagahawatta", "Ambagamuwa Udabulathgama", "Ambagasdowa", "Ambagaspitiya", "Ambagaswewa", "Ambakandawila", "Ambakote", "Ambalakanda", "Ambalangoda", "Ambalantota", "Ambalkulam", "Ambana", "Ambanpola", "Ambatalawa", "Ambatenna", "Ambepussa", "Ambewela", "Ambulugala", "Amitirigala", "Ampagala", "Ampalaperumal Kulam", "Ampan", "Ampara", "Ampegama", "Ampilanthurai", "Ampitiya", "Amugoda", "Amuna", "Amunumulla", "Anaikottai", "Anairavu", "Anaivilnthan", "Analaitivu", "Analathivu", "Anamaduwa", "Anangoda", "Ananthampannai", "Andana", "Andiambalama", "Andigama", "Andiyagala", "Angampitiya ( Padukka )", "Angamuwa", "Angoda", "Angulana", "Angulugaha", "Angunakolapelessa", "Angunakolawewa", "Angunawila", "Anguruwatota", "Anhandiya", "Anhettigama", "Ankokkawala", "Ankumbura", "Anukkane", "Anuradapura City", "Anuradhapura", "Aparekka", "Arachchikattuwa", "Aragoda", "Araipattai", "Aralaganwila", "Araly", "Aranayaka", "Arangala", "Aranthalawa", "Arapola", "Arasadiththivu", "Arasady", "Arawa", "Arawakumbura", "Arawatta", "Arejalai", "Arippu", "Arugam Bay", "Aruggammana", "Arukgoda", "Arumugaththankudiyiruppu", "Aruppola", "Aselapura", "Asikkulam", "Atabage", "Atakalanpanna", "Atakiriya", "Atale", "Ataragalla", "Ataragallewa", "Atchuveli", "Athimottai", "Athu Aela", "Athuraliya", "Athurugiriya", "Atigala", "Attakada", "Attanagalla", "Attanakadawala", "Attawilluwa", "Attidiya", "Atulugama", "Atulugama ( Karawanella )", "Ausadapitya Vavuniya", "Avarangal", "Avissawella", "Awkana", "Awlegama", "Awukana", "Awulegama", "Ayagama", "Ayithiyamalai", "Ayiththiyamalai", "Ayiwela", "Ayyathigewewa Kabithigollewa", "Badalgama", "Badalkumbura", "Baddegama", "Badulla", "Baduluoya", "Baduluwela", "Baduraliya", "Baduwatta", "Bakamuna", "Bakinigahawela", "Bakinikawela (Monaragala)", "Bakkiella", "Bakmeegama", "Bakmitiyawa", "Baladora", "Balaharuwa", "Balalla", "Balana", "Balangoda", "Balangoda City", "Balapitiya", "Balayawewa Padaviya", "Ballaketuwa", "Bambaragahaela", "Bambaragaswewa", "Bambarapana", "Bammanna", "Bamunugama", "Bamunukotuwa", "Banagala", "Bandagiriya Colony", "Bandara Koswatta", "Bandaragama", "Bandarawatta", "Bandarawela", "Bandawa", "Banduragoda", "Bangadeniya", "Bangama", "Bankiyawatta", "Barampola", "Baranankattuwa", "Barathipuram", "Barawakumbuka", "Barawardhana Oya", "Batagoda", "Batagolladeniya", "Batapola", "Batatota", "Batewela", "Bathalayaya", "Battaramulla", "Battaramulla Koswatta", "Batticaloa", "Battuluoya", "Batugampola", "Batugoda", "Batumulla", "Batuwatta", "Batuwita", "Bawlana", "Baykiella", "Bekkegama", "Beliatta", "Beligala(Sab)", "Beligamuwa", "Belihuloya", "Bellana", "Bellantara", "Belummahara", "Bemmulla", "Bengamuwa", "Benthota", "Bentota", "Beragala", "Beragama", "Beralapanathara", "Beraligala", "Beralihela", "Beramada", "Berannawa", "Beruwala", "Bibila", "Bibila-Oya", "Bibile", "Bibilegama", "Bingiriya", "Biyagama", "Biyagama Ipz", "Biyagama Town", "Bogahakumbura", "Bogahawewa", "Bogahawewa Padaviya", "Bogamulla", "Bogaswawa", "Bogawantalawa", "Bokalagama", "Bokundara", "Bolgoda Bandaragama", "Bollete (Wp)", "Bolossagama", "Bolthumbe", "Bombuwala", "Bomluwageaina", "Boossa", "Bopagama", "Bopagoda", "Bopana", "Bopattalawa", "Bope", "Bopeththa", "Bopitiya (Je-ela)", "Bopitiya (Kegalle)", "Bopitiya (Kurunegala)", "Boragas", "Boralanda", "Boralankada", "Boralankada ( Avissawella )", "Boralesgamuwa", "Boralugoda", "Boraluwewa", "Borapola", "Borupana Rathmalana", "Bossella", "Bowalagama", "Bowaththa", "Bowela", "Boyagane", "Braluwewa", "Buckmigama", "Buddama", "Bujjampola", "Bujjomuwa", "Bulathkohupitiya", "Bulathsinhala", "Bulnewa", "Bulugahapitiya", "Bulutota", "Buluwala", "Bundala", "Bungiriya", "Buthpitiya", "Buttala", "Central Camp", "Champianpattu", "Changanai", "Chavakachcheri", "Chavakacheri", "Cheddipalayam", "Chempianpattu", "Chenkaladi", "Chenkalady", "Chilavathurai", "Chilaw", "Chinabay", "Chulipuram.", "Chundikulam", "Chundukuli", "Chunnagam", "Chunnakam", "Colombo 01", "Colombo 02", "Colombo 03", "Colombo 04", "Colombo 05", "Colombo 06", "Colombo 07", "Colombo 08", "Colombo 09", "Colombo 10", "Colombo 11", "Colombo 12", "Colombo 13", "Colombo 14", "Colombo 15", "D-5 Unit", "D5", "D6", "D7", "Dadayamtalawa", "Dagampitiya", "Dagonna", "Daladagama", "Dalukana", "Damana", "Damanewela", "Dambadeniya", "Dambagalla", "Dambana", "Dambulla", "Dambulla City", "Dambuluwana", "Damminna", "Dampahala", "Dampe", "Dampella", "Damunupola", "Danawala Thiniyawala", "Dangolla", "Dankanda", "Dankotuwa", "Danowita", "Danthure", "Daraluwa", "Daugala", "Daulagala", "Dayagama Bazaar", "Debahera", "Debarawewa", "Debathgama", "Dedduwala", "Dedigamuwa", "Dediwala Waskaduwa (Kaluthara)", "Dedugala", "Dedunupitiya", "Deegala Junction", "Deegala Lenama", "Deegalla", "Deegawapiya", "Deekirikewa", "Deewala Pallegama", "Deheragoda", "Dehiattakandiya", "Dehigaspe", "Dehigasthalawa", "Dehiowita", "Dehiwala", "Dehiwatte", "Deiyandara", "Dekatana", "Dekinda", "Dekirikewa", "Del-Oluva Junction", "Dela", "Deldeniya", "Delf Island", "Delgoda", "Delkanda", "Dellawa", "Delmella", "Deloluwa", "Deltara", "Deltota", "Delwagura", "Delwala", "Delwita", "Delwite", "Demalagama", "Demanhandiya", "Demataluwa", "Dematawewa", "Demodara", "Demuwatha", "Denagama", "Denipitiya", "Deniyaya", "Derangala", "Deraniyagala", "Detagamuwa", "Detawa,Narammala", "Devagiriya", "Devalahinda", "Devanampiyatissapura", "Devinuwara", "Dewagala", "Dewahuwa", "Dewalapola", "Dewalegama", "Dewanagala", "Dharga Town", "Diddeniya", "Digamadulla Weeragoda", "Digana", "Diganatenna", "Digannewa", "Digbedda", "Dikkapitiya", "Dikkovita", "Dikkumbura", "Dikoya", "Dikwella", "Dimbulagala", "Dimbulana", "Divulankadawala", "Divulapelessa", "Divulapitiya", "Divuldamana", "Divuldeniya", "Divullegoda", "Diwalakada", "Diyabeduma", "Diyadawa", "Diyagaha", "Diyagama ( Horana )", "Diyagama (Homagama)", "Diyakada", "Diyakobala", "Diyalape", "Diyasenpura", "Diyatalawa", "Dobagoda", "Dodampe", "Dodanduwa", "Dodangaslanda", "Dodangoda", "Dolapihilla", "Dolosbage", "Doloswalakanda", "Doluwa", "Dombagahawela", "Dombagoda", "Dombemada", "Dompe", "Doragala", "Doragamuwa", "Dorakumbura", "Doratiyawa", "Dorawaka", "Dulgolla", "Dullawa", "Dullewa", "Dumbara Manana", "Dummaladeniya", "Dummalasuriya", "Dunagaha", "Dunkannawa", "Dunkolawatta", "Dunukedeniya", "Dunumadalawa", "Dunumala", "Dunuwila", "Dunuwilapitiya", "Dutuwewa", "Echchantheevu", "Echchilampattai", "Edagala", "Egodawela", "Eheliyagoda", "Ehetuwewa", "Eirupalai", "Ekala", "Ekamutugama", "Ekiriya", "Ekiriyankumbura", "Elabadagama", "Elahera", "Elamalpotha", "Elamulla", "Elapatha", "Elayapattuwa", "Elephantpass", "Elgiriya", "Elibichchiya", "Elkaduwa", "Ella", "Ella Tanabaddegama", "Ellagala", "Ellagawa", "Ellakkala", "Ellaulla", "Ellawala", "Ellewewa", "Elpitiya", "Elupakadavi", "Eluwankulama", "Eluwila", "Elwala", "Embilipitiya", "Embilipitiya New Town", "Embogama", "Embuldeniya", "Embulgama (Panagoda)", "Enderamulla", "Eppawala", "Eranaimadu", "Eratna", "Eravur", "Erawula Junction", "Erepola", "Erukalampitty", "Erukkalampiddy", "Eruvil", "Essella", "Etanawala", "Etawatunuwewa", "Etaweeragollawa Medawachchiya", "Etaweeragollewa", "Ethimalewewa", "Ethkandura", "Ethnawala", "Ethul Kotte", "Ettale", "Ettampitiya", "Ettiliwewa", "Etulgama", "Etungahakotuwa", "Gabbela", "Galabedda", "Galaboda", "Galadivulwewa", "Galagama", "Galagedara (Kandy)", "Galagedara (Padukka)", "Galaha", "Galamuna", "Galapitagala", "Galapitamada", "Galatara", "Galauda", "Galedanda", "Galenbindunuwewa", "Galewela", "Galgamuwa", "Galgoda", "Galhemulla", "Galhinna", "Galigamuwa Town", "Galkadawala", "Galkiriyagama", "Galkulama", "Galle", "Gallella", "Gallellagama", "Gallewa", "Galmaththa", "Galmetiyawa", "Galmuruwa", "Galnewa", "Galoya", "Galoya Junction", "Galpatha (Kalutara)", "Galpatha(Kegalle)", "Galporuyaya", "Galtenwewa", "Galwela", "Gamagoda", "Gambirigaswewa", "Gamewela", "Gammaduwa", "Gampaha", "Gampola", "Gandara", "Ganegoda", "Ganemulla", "Ganethenna", "Ganewalpola", "Ganewatta", "Gangala Puwakpitiya", "Gangeyaya", "Gangodawila", "Gangulandeniya", "Ganthuna", "Gantuna", "Garaagoda ( Avissawella )", "Gatahetta", "Gatulawa", "Gawaragiriya", "Gawarawela", "Gelioya", "Gemunupura", "Getahetta", "Getalawa", "Getamanna", "Gillimale", "Ginigathena", "Ginimellagaha", "Gintota", "Girandurukotte", "Giratalana", "Girathalana", "Giribawa", "Giritale", "Giriulla", "Gnanikulama", "Goda Koggalla", "Godagama (Kottawa)", "Godagama (Matara)", "Godagampola", "Godahena", "Godakawela", "Godamunna", "Godapiti tenna", "Godapitiya", "Godawela", "Godigamuwa", "Godigamuwa [Ratnapura]", "Godunna", "Gokaralla", "Gokeralla", "Gomaduwa", "Gomagoda", "Gomarankadawala", "Gomilamawarala", "Gonagala", "Gonagaldeniya", "Gonagalpura", "Gonagamuwa Uduwila", "Gonagantenna", "Gonagolla", "Gonahaddenawa", "Gonakele", "Gonamulla Junction", "Gonapathirawa", "Gonapinuwala", "Gonapola Junction", "Gonawala", "Gonawalapatana", "Gonawila", "Gonawila ( Makandura )", "Gongodawila", "Gonnoruwa", "Gonuhaddenawewa Kebithigollawa", "Gorakana", "Gorakawela", "Gothatuwa", "Govinna", "Grandpass", "Gunnepana", "Gurubavilagama", "Gurubewilagama", "Gurudeniya", "Gurulubadda", "Gurutalawa", "Habaraduwa", "Habarakada", "Habarana", "Haburugala", "Hadapangoda", "Hadungamuwa", "Haggala", "Hakahinna", "Hakbellawaka", "Hakmana", "Hakuruwela", "Haldummulla", "Halgranoya", "Hali Ela", "Halkandawila", "Halloluwa", "Halmillawetiya", "Halmillawetiya Kebithigollewa", "Halmillawewa", "Halpe Balangoda", "Halpe Mirgama", "Halpita ( Kesbewa )", "Haltota", "Halvinna", "Halvitigala Colony", "Halwala", "Halwatura", "Halwinna", "Hambantota", "Hambantota Town", "Hambegamuwa", "Hamillakotuwa", "Handaganawa", "Handagiriya", "Handapangoda", "Handawalapitiya", "Handessa", "Handugala", "Handungamuwa", "Hangarapitiya", "Hangunnawa", "Hanguranketha", "Hansayapalama", "Hanwella", "Hapugastalawa", "Hapugastenna", "Haputale", "Harangalagama", "Harankahawa", "Harasbedda", "Harasgala", "Harispattuwa", "Hasalaka", "Hatangala", "Hatarabage", "Hataraliyadda", "Hatares Kotuwa", "Hatton", "Hattota Amuna", "Haupe", "Hawpe", "Hebarawa", "Hedigalla Colony", "Hedunuwewa", "Heelogama", "Heeloya", "Heiyanthuduwa", "Hekitta", "Hela Halpe Bandarawela", "Helahalpe", "Helamada", "Helapupula", "Hemmatagama", "Henagamuwa", "Henamulla", "Henegama", "Hengamuwa", "Herathhalmillawa Kebithigollawa", "Hettimulla", "Hettipola (Kurunegala)", "Hettipola (Matale)", "Hettiyawala", "Hewadiwela", "Hewaheta", "Hewainna", "Hewana,Welipennagahamula", "Hewanakumbura", "Hidellana", "Hidogama", "Hikkaduwa", "Hilogama", "Himbutana", "Hinatiyana Madawala", "Hindagala", "Hindagolla", "Hingula", "Hingula ganethenna", "Hingurakdamana", "Hingurakgoda", "Hinguralakanda", "Hingurana", "Hingurukaduwa", "Hiniduma", "Hiramadagama", "Hirana", "Hiripitiya (Homagama )", "Hiripitiya (Kottawa)", "Hiriwadunna", "Hiriyala", "Hiriyala Lenawa", "Hiruwalpola", "Hiswella", "Hitigegama", "Hiyare", "Hlmillawewa", "Hodiyadeniya", "Hokandara", "Homagama", "Hondiyadeniya", "Hopton", "Horambawa", "Horampella", "Horana", "Horapawita", "Horathapola", "Horawpatana", "Horethuduwa", "Horewelagoda", "Horiwila", "Horombawa", "Hulandawa", "Hulandola", "Hulannuge", "Hulogedara", "Hulugalla", "Humbuluwa", "Hungama", "Hunnasgiriya", "Hunumulla", "Hunupitiya", "Hunupola", "Hunuwala", "Hurigaswewa", "Hurulunikawewa", "Ibbagamuwa", "Idalgashinna", "Ihala Beligalla", "Ihala Bomiriya", "Ihala Bope", "Ihala Gomugomuwa", "Ihala Halmillewa", "Ihala Kadigamuwa", "Ihala Katugampala", "Ihala Kobbekaduwa", "Ihala Kottaramulla", "Ihala Madampella", "Ihala Puliyankulama", "Ihala Walpola", "Ihalagama", "Ihalahewessa", "Ikkapallama", "Ilippadeniya", "Ilippugamuwa", "Ilukhena", "Iluppakadawai", "Imaduwa", "Imbulana", "Imbulgasdeniya", "Imbulgoda", "Imbulgolla", "Imbulpe", "Inamaluwa", "Indulgodakanda", "Induruwa", "Inginimitiya", "Inginiyagala", "Ingiriya", "Inguruwatta", "Inuvil", "Ipalogama", "Ipologama", "Iranaimadu", "Iranavila", "Iriyagolla", "Irukalmpity", "Isinbassagala Medawachchiya", "Ismailpuram", "Ithanawatta", "Iththademaliya", "Ittakanda", "Ittapana", "Iyakachchi", "Iyyakachchi", "Ja-Ela", "Jaffna", "Jaffna Town", "Jalthara ( Hanwella )", "Jambugahapitiya", "Janakapura Welioya Mullaitivu", "Jangulla", "Jayanthipura", "Jayanthiyawewa Sripura Padaviya", "Jayasiripura", "Je - Ela ( Gampaha District )", "Jenthinagar", "Jepuram", "Jeyanthinagar North", "Jeyanthinagar South", "Julampitiya", "Julpallama", "Kabagamuwa", "Kachcheriyady", "Kadahapola", "Kadaiadampan", "Kadawatha", "Kaddaiparichchan", "Kaddaykadu", "Kaddukkulam", "Kadigamuwa", "Kadigawa", "Kadugannawa", "Kaduruwela", "Kaduwela", "Kagama", "Kahaduwa", "Kahanavita Junction", "Kahandamodara", "Kahangama", "Kahapathwala", "Kahapola", "Kahatagasdigiliya", "Kahatagastenna ( Kandy )", "Kahatagastenna ( Kegalle )", "Kahatagollewa", "Kahataliyadda", "Kahataruppa", "Kahathuduwa", "Kahatowita", "Kahawa", "Kahawala", "Kahawatta", "Kahtagollewa Kebitgollewa", "Kaikawala", "Kaithadi", "Kaithady", "Kajugaswatta", "Kakkapalliya", "Kakunugoolla", "Kalabulanda", "Kalaganwatta", "Kalagedihena", "Kalakarambewa", "Kalani Mulla", "Kalankuttiya", "Kalaoya", "Kalapaluwawa Rajagiriya", "Kalatuwawa", "Kalawana", "Kalawedi Ulpotha", "Kalawila Kiranthidiya", "Kaldemulla ( Rathmalana )", "Kaleliya", "Kalingaela", "Kalkudah", "Kalladiya", "Kallanchiya", "Kallar", "Kalliady", "Kalmunai", "Kalpe Morakwa Horowpothana", "Kalpitiya", "Kalthota", "Kaltota", "Kaluaggala", "Kalubowila", "Kalubowitiyana", "Kalubululanda", "Kaludawela", "Kaludewala", "Kalugahakandura", "Kalugala", "Kalugamuwa", "Kalukele Badanagala", "Kalundawa", "Kalupahana", "Kalutara", "Kalutara South", "Kaluthavalai", "Kaluwanchikudi", "Kaluwankemy", "Kalveyankadu", "Kalvilankulam", "Kalyanipura Welioya", "Kamburugamuwa", "Kamburugoda", "Kamburupitiya", "Kanadeniyawala", "Kanagampigaikulam", "Kanagampikaikulam", "Kanagapuram", "Kanakarajankulam", "Kananke", "Kananke Bazaar", "Kananwila", "Kanattewewa", "Kanchipuram", "Kandaketiya", "Kandakuliya", "Kandalama", "Kandana", "Kandanagama", "Kandapola", "Kandaudapanguwa", "Kandavalai", "Kandawinna", "Kande Nuwara", "Kandegedara", "Kandepuhulpola", "Kanduruwana", "Kandy", "Kaneshapuram", "Kankeshanthurai", "Kannakipuram", "Kannan Kovilady", "Kannankudah", "Kannankudha", "Kannattota", "Kanniya", "Kantalai", "Kantalai Sugar Factory", "Kanthale", "Kantharmadam", "Kapugallawa", "Kapugama", "Kapugoda", "Kapugollawa Horowpothana", "Kapuliyadde", "Karadippokku", "Karadiyanaru", "Karagahagedara", "Karagahagedara,Narammala", "Karagahawewa", "Karagahinna", "Karagampitiya", "Karagoda", "Karagoda Uyangoda", "Karainagar", "Karaiyakkanthivu", "Karambe", "Karandagolla", "Karandana", "Karandeniya", "Karangoda", "Karapitiya", "Karaputugala", "Karativponparappi", "Karativu", "Karatota", "Karaveddi", "Karawanella", "Karawitagara", "Karipaddamurippu", "Karisal", "Kariyamaditta", "Karuwalagaswewa", "Kashyapapura", "Katana", "Kataragama", "Kathiraveli", "Katiyawa", "Katpakapuram", "Kattaikadu", "Kattankudi", "Kattankudy", "Kattapitiya", "Katudeniya", "Katugastota", "Katukitula", "Katukurunda (Kalutara)", "Katukurunda (Panadura)", "Katukurunda (Ratmalana)", "Katunayake", "Katunayake Air Force Camp", "Katunayake(Ftz)", "Katuneriya", "Katupota", "Katuwana", "Katuwellegama", "Kavudupelella", "Kawantissapura", "Kawatayamuna", "Kawdumunna", "Kawudulla", "Kawuduluwewa Stagell", "Kayankeny", "Kayts", "Kebillawela", "Kebithigollewa", "Keerthi Bandarapura", "Kegalle", "Kehelpannala", "Kehelwatta", "Kekanadurra", "Kekirawa", "Kekirihena", "Kekunagolla", "Kelanigama", "Kelaniya", "Kelaniyamulla", "Kelinkanda", "Kella Junction", "Kendagolla", "Kendewa", "Kengalla", "Keppetipola", "Keppitiwalana", "Keramakkodu", "Kerawalapitiya", "Kesbewa", "Keselpotha", "Keselwatta", "Ketaboola", "Ketakumbura", "Ketanvila", "Ketawala Leula", "Ketawatta", "Kibissa", "Kilinochchi Town", "Kiliveddy", "Killuvilankulam", "Kimbulapitiya", "Kimbulwanaoya", "Kindelpitiya", "Kinniya", "Kiralogama", "Kirama", "Kiramiya Vistharipputhiddam", "Kiran", "Kirankulam", "Kiribathgoda", "Kiribathkumbura", "Kiribbanwewa Welioya", "Kiriberiya", "Kiriella", "Kirigalwewa", "Kirigalwewa Medawachchiya", "Kiriibbanwewa", "Kirillawala", "Kirimetiyawa", "Kirimtithanna", "Kirimundalama", "Kirinda", "Kirindawa", "Kirindigalla", "Kirindiwela", "Kirioruwa (Dambulla)", "Kirishnapuram", "Kiriwaldola", "Kiriwanagama", "Kiriwattuduwa", "Kiriweldola", "Kiriwelkele", "Kitalawalana", "Kithalawa", "Kiththul", "Kitulgala", "Kitulgoda", "Kitulhitiyawa", "Kitulwala", "Kiulekada Kebithgollewa", "Kiwula", "Kiyaduwa", "Kks", "Knnnankudha", "Kobbawahara", "Kobeigane", "Kobonila", "Kochchikade", "Kochchikade ( Gampaha District )", "Koddadi", "Koddaikallar", "Kodikamam", "Kodikaragoda", "Kogala", "Kohilagedara", "Kohilawatta ( Angoda )", "Koholana", "Koholanwela", "Kohuwala", "Kokkaddichcholai", "Kokkavil", "Kokkuvil", "Koklai", "Koknahara", "Kolabissa", "Kolamanthalawa", "Kolambageara", "Kolawenigama", "Kolombugama", "Kolongoda", "Kolonna", "Kolonnawa", "Kolumputhurai", "Komari", "Kommathurai", "Kondavil", "Kondeniya", "Kongahawela", "Konwewa", "Kooddakaddikulam", "Koongahawela", "Kopai", "Kopay", "Korakkalliyamadu", "Korakkankadhu", "Koralaima", "Koralawella", "Koramoddai", "Korathota", "Kosdeniya", "Kosgama", "Kosgoda", "Kosgolla", "Koslanda", "Kosnara", "Koswatta Malabe", "Koswatta North ( Wennappuwa )", "Kotadeniyawa", "Kotagala", "Kotagama", "Kotahena", "Kotamuduna", "Kotapola", "Kotawehera", "Kotawehera Mankada", "Kotaweheramankada", "Kothalawala", "Kotikawatta", "Kotiyagala", "Kotiyakumbura", "Kotmale", "Kottantivu", "Kottapitiya", "Kottawa", "Kottawagama", "Kotte", "Kottegoda", "Kottellena", "Kottukachchiya", "Kottuwe Gedara", "Kotugoda", "Kovavil", "Kovilkulam", "Ku Bopitiya", "Kuchchaveli", "Kuda Katnoruwa", "Kuda Uduwa", "Kudagalgamuwa", "Kudakatnoruwa", "Kudaoya ( Buttala )", "Kudaoya ( Tanamalvila )", "Kudawa", "Kudawella", "Kudawewa", "Kudimaththi", "Kula", "Kuleegoda", "Kuliyapitiya", "Kulugammana", "Kumaragama", "Kumarakattuwa", "Kumarapuram", "Kumbalgamuwa", "Kumbaloluwa", "Kumbiyangoda", "Kumbukgeta", "Kumbukkana", "Kumbukkandura", "Kumbukwela", "Kumbukwewa", "Kumburegama", "Kumburupiddy", "Kundasale", "Kunsupparanthan", "Kuratihena", "Kurinchakemy", "Kurinchamunai", "Kurinjanpitiya", "Kuruketiyawa", "Kurukkalmadam", "Kurulugama Kabathigollawa", "Kurumankadu", "Kurumanvely", "Kurunagar", "Kurundankulama", "Kurunegala", "Kurupanawela", "Kuruwita", "Kuruwitenna", "Kuttiyagolla", "Labbala", "Labukele", "Labunoruwa", "Laggala", "Laggala Pallegama", "Lahugala", "Lakshauyana", "Laliamba", "Landewela", "Lankagama", "Lankapatuna", "Lathpandura", "Latpadura", "Lauambay", "Laxapana", "Lbbagamuwa", "Leemagahakotuwa", "Left Bank", "Leliambe", "Lellopitiya", "Lenadora", "Lengala", "Lewangama", "Leyland handiya", "Lihiriyagama", "Lindula", "Liyanekkanda", "Liyangahawela", "Lllagolla", "Lllukkumbura", "Llluppaikadavai", "Llukhena", "Lmbulpe", "Lmkkamam", "Loluwagoda", "Lonahettiya", "Lukhena", "Lunama", "Lunu Watulewa Kebithigollewa", "Lunugala", "Lunugama", "Lunugamwehera", "Lunuketiya Maditta", "Lunuwatta", "Lunuwila", "Ma Oya Kalugaga", "Mabima", "Mabodale", "Mabola", "Madabadda", "Madagalla", "Madahapola", "Madakumburumulla", "Madalagama", "Madampe (Ratnapura)", "Madampe(Puttalam)", "Madapatha", "Madatugama", "Madawala Bazaar", "Madawala Ulpotha", "Madawalalanda", "Madelgamuwa", "Madhu Church", "Madipila", "Madipola", "Madiwela", "Madovita", "Madugalla", "Madulkele", "Madulla", "Madulsima", "Madupitiya", "Maduragoda", "Madurankuliya", "Maduruoya", "Maeliya", "Magalirthiddam", "Magama", "Magammana", "Magedara", "Maggona", "Magulagama", "Maha Ambagaswewa", "Maha Elagamuwa", "Mahabage", "Mahabulankulama", "Mahadivulwewa", "Mahadoraliyadda", "Mahagalkadawala", "Mahagalwewa", "Mahagama", "Mahagama Colony", "Mahagirilla", "Mahailluppallama", "Mahailuppallama", "Mahakachchakodiya Vavuniya", "Mahakalupahana", "Mahakanadarawa", "Mahakanda", "Mahakumbukkadawala", "Mahamedagama", "Mahamukalanyaya", "Mahamulgama", "Mahanagapura", "Mahananneriya Palugolla", "Mahananneriya Town ( Maho )", "Mahanetiyawa Kebithigollewa", "Mahaoya", "Mahapallegama", "Mahapothana", "Mahara (kadawatha)", "Maharachchimulla", "Maharagama", "Maharambaikulam", "Maharangalla", "Maharugiramam", "Mahasenpura (Anuradhapura)", "Mahasenpura (Hambantota)", "Mahatalakolawewa", "Mahauswewa", "Mahawa", "Mahawalatenna", "Mahawela", "Mahawela Sinhapura", "Mahawelathanna", "Mahawewa", "Mahawilachchiya", "Mahilavedduvan", "Mahiyanganaya", "Maho", "Mailagaswewa", "Mailapitiya", "Makadura (Kurunegala)", "Makandura (Kurunegala)", "Makandura (Matara)", "Makandura (Ratnapura)", "Makehelwala", "Makewita", "Makiladiththivu", "Makiloor", "Makkanigama", "Makola", "Makuldeniya", "Makulella", "Makulewa", "Makulpotha", "Makulwewa", "Malabe", "Malagane", "Malalpola", "Malamulla", "Malayalapuram", "Maldeniya (Nuwara Eliya)", "Maldeniya (Polonnaruwa)", "Malgalla Talangalla", "Malgoda", "Maliboda", "Maliduwa", "Maliyadda", "Mallagam", "Mallavi", "Mallawapitiya", "Mallikativu", "Malliyakadu", "Malmaduwa", "Malpradahnalla", "Malwala Junction", "Malwana", "Malwanagama", "Malwatta", "Mamadala", "Mamailkulam", "Mamangam", "Mampitiya", "Mampuri", "Mananwatta", "Manatkadu", "Manchanthoduwai", "Mandapola", "Mandaram Nuwara", "Mandawala", "Mandur", "Maneruwa", "Mangadu", "Mangalaeliya", "Mangalagama ( Maha oya Ampara )", "Manipai", "Manipay", "Maniyanthoddam", "Mankerni", "Mankulam", "Mankumpan", "Manmunai", "Mannar", "Mannarr", "Manthikai", "Maoya", "Mapakadawewa", "Mapakanda", "Mapalagama", "Mapalagama Central", "Maraba", "Maradankadawala", "Maradankalla", "Maradankawadawala Horowpothana", "Maraka", "Maramba", "Marandagahamula", "Marapana", "Marappalam", "Marassana", "Marathamunai", "Marawa", "Marawila", "Mariarawa", "Marichchi Kaddi", "Marudankeny", "Maruthamunai", "Maruthanagar", "Maruthanarmadam", "Marymount Colony", "Maskeliya", "Maspanna", "Maspotha", "Maswela", "Matalapitiya", "Matale", "Matara", "Mathagal", "Matikubura", "Mattaka", "Mattegoda", "Mattihakka", "Matugama", "Maturata", "Matuwagalagama", "Maussagolla", "Mavadivempu", "Mawadichenai", "Mawanagama", "Mawanella", "Mawaramandiya", "Mawathagama", "Mawathgama ( Padukka )", "Mawatura", "Mawela", "Mayeliti", "Mayurapura", "Meda-Keembiya", "Medagalatur", "Medagama (Monaragala)", "Medagama Bibila (Badulla District)", "Medamahanuwara", "Medamulana", "Medawachchiya", "Medawala Harispattuwa", "Medawela Udukinda", "Medawelagama", "Meddekanda", "Medirigiriya", "Mediripitiya", "Medivawa", "Meegahakiula", "Meegahatenna", "Meegakaiula", "Meegalawa", "Meegalewa", "Meegama", "Meegaswewa", "Meegoda", "Meemure", "Meepe", "Meeravodai", "Meesalai", "Meetalawa", "Meetanwala", "Meethalawa", "Meetiyagoda", "Meewellawa", "Megoda Kalugamuwa", "Megodawewa", "Melipitiya", "Mellawagedara", "Melsiripura", "Menikdiwela", "Menikhinna", "Metigahatenna", "Metihakka", "Metikumbura", "Metiyagane", "Middeniya", "Midigama", "Miella", "Migahajandura", "Migastenna Sabara", "Mihintale", "Millaniya", "Millawana", "Millewa", "Mimure", "Minhettiya", "Minigamuwa", "Minipe", "Minipura Dumbara", "Minnana", "Minneriya", "Minumthalawa", "Minuwangete", "Minuwangoda", "Mipanawa", "Mipilimana", "Mirahawatta", "Mirigama", "Mirihana", "Mirihanagama", "Mirihanegama", "Mirijjawila", "Miris Waththa", "Mirissa", "Miriswatta ( Galle )", "Miriswatta ( Gampaha )", "Miriswatta ( Piliyandala )", "Miriyabedda", "Mirusuvil", "Mithirigala", "Mitipola", "Miwanapalana", "Miyanakandura", "Miyanawita", "Modarawana", "Molagoda", "Molkawa", "Molligoda (Kadugannawa)", "Mollipothana", "Monaragala", "Monnekulama", "Moonamaldeniya", "Moragahahena", "Moragahapallama", "Moragala", "Moragala Kirillapone", "Moragane", "Moragollagama", "Morahela", "Morahenagama (Nuwaraeliya)", "Morahenagama (Polonnaruwa)", "Morakewa", "Morakewa Horowpothana", "Morakkoddanchenai", "Moraliya", "Morapitiya", "Morathiha", "Moratumulla", "Moratuwa", "Moravinna", "Morawaka", "Morenthuduwa", "Moretuwegama", "Morontota", "Morontuduwa", "Mount Lavinia", "Mudalakkuliya", "Muddaragama", "Mudungoda", "Mugunuwatawana", "Muhudukatuwa", "Mukamalai", "Mukaththuvaram", "Mukirigala", "Mukkutoduwawa", "Mulankavil", "Mulatiyana Junction", "Mulendiyawala", "Mulgama", "Mulkirigala", "Mulkiriyawa", "Mullaitivu", "Mullativu", "Mulleriyawa", "Mulleriyawa New Town", "Mullijan", "Mullijavalai", "Mullipothana", "Mulpothanai", "Munaiththivu", "Munamaldeniya", "Mundalama", "Mundel", "Munwatta", "Murakottanchanai", "Murasumoddai", "Murippu", "Muriyakadawala", "Murukandy", "Murunkan", "Murutalawa", "Muruthagahamulla", "Muruthalawa", "Muruthenge", "Muslim Attaweerawe Kebithigollewa", "Muttibandivila", "Mutugala", "Mutur", "Muwandeniya", "Mylampavely", "Myliddy", "Nabadewa", "Naboda", "Nachchaduwa", "Naddankandal", "Naddnkandal", "Nadugala", "Nagahpola", "Nagarkovil", "Nagoda (Galle)", "Nagoda (Gampaha)", "Nagoda (Kalutara)", "Nagollagama", "Nagollagoda", "Naimana", "Nainamadama", "Nainathivu", "Nakiyadeniya", "Nakkala", "Nakkawatta", "Nakulugamuwa", "Nalanda", "Nalladarankattuwa", "Nallathanniya", "Nallur", "Nalluruwa", "Namaloya", "Namalpura", "Nambakada Prangiyawadiya Horowpothana", "Namunukula", "Nanattan", "Nannapurawa", "Nanuoya", "Napawela", "Narahenpita", "Narammala", "Narangala ( Badulla )", "Narangala ( Kuliyapitiya )", "Narangoda", "Naranpanawa", "Naranpitiya", "Naranwala", "Narawelpita", "Naththandiya", "Nattandiya", "Nattarampotha", "Naula", "Navagirinagar", "Navaladi", "Navalady", "Navanthurai", "Navatkadu", "Navatkoddiyan", "Navithanveli", "Nawadagala", "Nawagampura", "Nawagamuwa ( Kaduwela )", "Nawagattegama", "Nawagiriyawa", "Nawala", "Nawalakanda", "Nawalapitiya", "Nawamedagama", "Nawana", "Nawasenapura", "Nawasigawatha", "Nawatalwatta", "Nawathispane", "Nawattuduwa", "Nawimana", "Nawinnapinnakanda", "Nayapana Janapadaya", "Nayaru", "Neboda", "Nedigamvila", "Nedungamuwa", "Nedunkeny", "Neduntheevu", "Neelapola", "Neervely", "Negampaha", "Negombo", "Nelliady", "Nelligahamulla (Dunagaha)", "Nelliya", "Nelliyadda", "Nelliyadi", "Nelsonpura", "Nelukkulam", "Nelumdeniya", "Nelumgama", "Nelumwewa", "Nelundeniya", "Neluwa ( Morawaka )", "Netolpitiya", "Nihiluwa", "Nika Wewa Dakuna Welioya", "Nikadalupotha", "Nikahetikanda", "Nikapotha", "Nikaweratiya", "Nikawewa Horowpothana", "Nilaveli", "Nildandahinna", "Nilgala", "Nillambe", "Nilpanagoda", "Nindana", "Nintavur", "Niralagama", "Nissanka Uyana", "Nithiyapuri", "Nittambuwa", "Nivil", "Nivithigala", "Niwandama", "Niwdawa", "Niyadurupola", "Nochchimunai", "Nochchiyagama", "Noori", "Norachcholai", "North Matale", "Norton", "Norwood", "Nugagolla", "Nugaliyadda", "Nugatalawa", "Nugawela", "Nugegoda", "Nugelanda", "Nunavil", "Nupe", "Nuwara Eliya", "Nuwaragala", "Nuwaragalathenna", "Nuwaragam Palatha", "Obbegoda", "Oddamavadi", "Oddisuddan", "Ohiya", "Okewela", "Okkampitiya", "Olimadu", "Olivil", "Oluvil", "Omalpe", "Omanthai", "Omanthi", "Onegama", "Onthachchimadam", "Opalgala", "Opanayaka", "Opatha (Galle)", "Opatha Katugoda", "Orrs Hill", "Orubendi Siyambalawa", "Orugodawatta", "Ovilikanda", "Owitigala", "Paandiruppu", "Pachchipalai", "Padagoda", "Padalangala", "Padavi Maithripura", "Padavi Parakramapura ( Anuradhapura )", "Padavi Parakramapura ( Vavuniya )", "Padavi Sri Tissapura ( Trincomalee )", "Padavi Sri Tissapura ( Vavuniya )", "Padavi Sripura ( Trincomalee Branch )", "Padavi Sripura ( Vavuniya )", "Padavi Sritissapura", "Padaviya", "Padaviya Sri Thissapura", "Padawgama", "Paddippalai", "Paddiruppu", "Padeniya", "Padikaramaduwa", "Padiwela", "Padiyapelella", "Padiyatalawa", "Padiyattalawa", "Padukka", "Pahala Andarawewa", "Pahala Bomiriya", "Pahala Bope", "Pahala Halmillewa", "Pahala Kadugannawa", "Pahala Maragahawe", "Pahala Millawa", "Pahalagama (Anuradhapura)", "Pahalagama (Badulla)", "Pahalagiribawa", "Pahalahewessa", "Pahalalanda", "Pahalarathkinda", "Pahamune", "Paiyagala", "Palagala", "Palai ( Jaffna )", "Palai ( Kilinochchi )", "Palaiyoothu", "Palali", "Palameenmadu", "Palampiddy", "Palamunai (Ampara)", "Palamunai (Batticoloa)", "Palapathwela", "Palathuruwella", "Palatuwa", "Palaviya", "Paleegala", "Pallama", "Pallaththara", "Pallebedda", "Pallebowala", "Pallegama", "Pallegama Matara", "Pallekale", "Pallekanda", "Pallekiruwa", "Pallekotuwa", "Pallemadawara", "Pallepola", "Pallewela", "Pallimulla", "Palliwasalturai", "Palugamam", "Palugasdamana", "Palugaswewa (Habarana)", "Palukadawala", "Pambagolla", "Pambaimadu", "Pambegama", "Pamburana", "Pamunugama", "Pamunuwatta", "Panadaragama", "Panadura", "Panagamuwa", "Panagoda ( Homagama )", "Panagoda ( Horana )", "Panaliya", "Panama", "Panamura", "Panangala", "Panapitiya", "Panapola", "Panawala", "Panawita,Narammala", "Panchikawatta", "Pandarikulam", "Pandariyavely", "Pandatharippu", "Pandiankulam", "Pandukabayapura", "Pandulagama", "Panduwasnuwara", "Pangura", "Panichankerny", "Panideniya", "Panirendawa", "Pankudaveli", "Pankulam", "Panliyadda", "Pannala", "Pannalagama", "Pannampitiya", "Pannankandy", "Pannegamuwa", "Pannila", "Pannimulla Panagoda", "Pannipitiya", "Panrimoddai", "Pansiyagama", "Panvila", "Panwewa", "Panwilatenna", "Paradeka", "Paraduwa", "Paragahakele", "Paragala", "Paragastota", "Paragoda", "Paraigama", "Parakaduwa", "Parakrama samudraya", "Parakumpura", "Parana Thanayamgoda", "Parangiyawadiya", "Paranthan", "Paranthan Town", "Paranthankuda Kumarikuda", "Parapamulla", "Parape", "Parasangahawewa", "Paraththa", "Paravippanchan", "Parussella", "Pasbage", "Pasgoda", "Pashayoor", "Passara", "Pasyala", "Patana", "Pathakada", "Pathana", "Pathanewatta", "Pattampitiya", "Pattitalawa", "Pattiya Watta", "Pattiyagedara", "Pattiyawatta", "Payagala", "Pebotuwa", "Peelawattha(Negombo)", "Pelagahatenna", "Pelanda", "Pelatiyawa", "Pelawatta ( Battaramulla )", "Pelawatta ( Matugama )", "Peliyagoda", "Pellawatta (Minuwangoda) Negombo", "Pelmadulla", "Pemaduwa", "Penetiyana", "Pepiliyawala", "Peradeniya", "Perakanatta", "Perakumpura", "Peralanda", "Perawella", "Perimiyankulama", "Periyakadneluwa", "Periyakunchikulam", "Periyamadhu", "Periyamadu", "Periyaneelavanai", "Periyaporativu", "Periyapullumalai", "Periyathivu", "Pesalai", "Pethiyagoda Gampaha", "Pethiyagoda Kelaniya", "Pettalai", "Pettigala", "Pihimbiya Ratmale", "Pihimbiyagolewa", "Pihimbiyagollawa Madawachchiya", "Pihimbuwa", "Pilawala", "Pilessa", "Pilimathalawa", "Piliyandala", "Pillaimadam", "Pillaiyaradi", "Pimbura", "Pimburattewa", "Pinnaduwa", "Pinnawala", "Pinnawala ( Rambukkana )", "Pinwatta", "Pita Kotte", "Pitabeddara", "Pitagaldeniya", "Pitakumbura", "Pitamaruwa", "Pitapola", "Pitawala (Nuwaraeliya)", "Pitawala (Polonnaruwa)", "Pitigala", "Pitipana Homagama", "Pitipana Negombo", "Piyangala", "Poddala", "Pohaddaramulla", "Poholiyadda", "Point Pedro", "Pokunuwita", "Polgahawela", "Polgampola", "Polgasowita", "Polgolla", "Polhengoda", "Polikandi", "Pollebedda", "Polommaruwa (Matara)", "Polonnaruwa", "Polpithigama", "Polpithimukulana", "Polpitigama", "Polwaga Janapadaya", "Poonagala", "Poonagary", "Poonakary", "Poraba", "Porathivu", "Porathota", "Porawagama", "Porikkadavai", "Poruwadanda", "Poruwedanda", "Pothdeniya", "Poththapitiya", "Pothuhera", "Pothukoladeniya", "Pothupitiya ( Ratnapura )", "Pothupitiya ( Wadduwa )", "Pothuwatawana", "Pothuwila", "Pottuvil", "Priya Kallar", "Pubbiliya", "Pubbogama", "Pudukudiruppu (Mannar)", "Pugoda", "Puhulpola", "Puhulwella", "Pujapitiya", "Pulastigama", "Puliyankulam", "Puliyanthivu", "Pulmoddai", "Pulmuda Road Mahasenapura Padaviya", "Puloly", "Pulukunawa", "Pulungasmulla", "Punanai", "Punarin", "Punawa", "Pundaluoya", "Punewa", "Pungavanam Junction", "Punkudutheevu", "Punkudutivu", "Punnalaikadduvan", "Pupuressa", "Purijala", "Pussalamankada", "Pussellawa", "Puswelitenna", "Puthukkudigiruppu", "Puthukkudigiruppu Valaichchenai", "Puthukudiruppu", "Puthukudiyiruppu", "Puthur", "Puttalam", "Puttalam Cement Factory", "Puttur", "Putuhapuwa", "Puvasankulam", "Puwakpitiya", "Radavana", "Radawadunna", "Radawana", "Radawela", "Raddolugama", "Raddolugama ( Gampaha District )", "Radella", "Ragala (nuwara eliya)", "Ragama", "Raigama", "Rajagalatenna", "Rajagalathenna", "Rajagiriya", "Rajakadaluwa", "Rajanganaya", "Rajanganaya 5 Mail Post", "Rajawaka", "Rajawella", "Rakwana", "Ralapanawa Horowpothana", "Ramanathankamam", "Rambe", "Rambewa", "Ramboda", "Rambuka", "Rambukkana", "Rambukkana Bandaragama", "Rambukpitiya", "Rambukwella", "Rammalawarapitiya", "Ranakeliya", "Ranala", "Ranamuregama", "Ranawarunawa", "Randenigala", "Randeniya", "Rangala", "Ranminitenna", "Ranmuduwewa", "Ranna", "Ranorawa", "Ranpathwila", "Ransegoda", "Rantembe", "Rantotuvila", "Rantotuwila", "Ranwala", "Rasayanapuram", "Rassagala", "Ratgama", "Rathmal gahawewa", "Rathmalawinna", "Rathmalgahawewa", "Raththinapuram", "Raththnapuram", "Rathukohodigala", "Ratkarawwa", "Ratmalana", "Ratmale (Matara)", "Ratmale (Vavuniya)", "Ratmalwala", "Ratna Hangamuwa", "Ratnampannai", "Ratnapura", "Ratnapuram ( kilinochchi )", "Rattota", "Rawathawatta", "Remunagoda", "Rideemaliyadda", "Ridibendiella", "Ridigama", "Ridimaliyadda", "Ridiyagama (Kurunegala)", "Rikillagaskada", "Rilpola", "Rotawewa Horowpothana", "Rottawewa", "Rotumba", "Rozella", "Ru/Ridiyagama", "Ruggahawila", "Rukmale", "Rukmalgama", "Rupaha", "Ruwalwela", "Ruwaneliya", "Ruwanwella", "Sadalankawa", "Sadathenna", "Sainthamaruthu", "Saithamarthu", "Sakthipuram (Ellukkadu)", "Salambailulam", "Saliya Asokapura", "Saliyapura", "Saliyawewa Junction", "Samanalawewa", "Samanturai", "Sammanthurai", "Sampaltivu", "Sampathnuwara Walioya", "Sampur", "Samudragama", "Sandalankawa", "Sandilipai", "Sangarajapura", "Sangilikandarawa Madawachchiya", "Santhapuram", "Santhipura", "Santhively", "Sapugaskanda", "Scheme I", "Scheme Ii", "Seaforth Colony", "Seeduwa", "Seepock (Avissawella)", "Seeppukulama", "Selagama", "Sella Kataragama", "Selvanagar", "Senapura", "Senarathwela", "Serankada", "Serugan", "Serukele", "Serunuwara", "Serupita", "Seruwila", "Sethukkudha", "Settekkulam", "Sevanapitiya", "Sevis Road", "Sewanagala", "Sialavathurai", "Siddamulla", "Sigiriya", "Silavathurai", "Sillalai", "Silmiyapura", "Silvapuram", "Singala Anganochchjaya Medawachchiya", "Singhagama", "Sinhagama", "Sirajnagar", "Sirambiadiya", "Siribopura", "Sirigala", "Sirimalgoda", "Siripura", "Sirisetagama", "Sirisethagama", "Sirrupity", "Sithamparapuram", "Siththandi", "Siththankeny", "Situlpawwa", "Sivapuram (Kilinochchi)", "Sivapuram (Vavuniya)", "Siwalakulama", "Siyabalagaskotuwa", "Siyabalagoda", "Siyambalagamuwa (Anurdhapura)", "Siyambalagashene", "Siyambalagoda", "Siyambalagune", "Siyambalanduwa", "Siyambalangamuwa", "Siyambalape", "Siyambalawewa", "Siyambalewa", "Skanthapuram", "Slave Island", "Solepura", "Solewewa", "Somapura", "Sooriyawewa Town", "Sorabora Colony", "Soragune", "Soranathota", "Soranathota", "Spring Valley", "Sravasthipura", "Sri Jayawardenepura", "Sri Palabaddala", "Sudagala", "Sultanagoda", "Sunandapura", "Sungavila", "Suriara", "Suriyakanda", "Tabbowa", "Talagampola", "Talagoda Junction", "Talahena", "Talaimannar", "Talakiriyawa", "Talakolahinna", "Talawa", "Talawakele", "Talawattegedara", "Talawatugoda", "Talawila Church", "Taldena", "Talduwa", "Talgaspe", "Talgaspitiya", "Talgaswela", "Talpe", "Talpotha", "Tamankaduwa", "Tambala", "Tambutta", "Tammennawa", "Tampalakamam", "Tangalla", "Tangalle", "Tanjantenna", "Tantirimale", "Tawalantenna", "Teldeniya", "Telhiriyawa", "Teligama", "Telwatta", "Tempitiya", "Tennekumbura", "Tennepanguwa", "Teppanawa", "Teripeha", "Thabbowa", "Thadduvankoddy", "Thaiyeddy", "Thalahena ( Malabe )", "Thalahena ( Negombo )", "Thalahitimulla", "Thalaiady", "Thalaimanna", "Thalaimannar", "Thalakiriyagama", "Thalakolawewa", "Thalankudha", "Thalathuoya", "Thalavitiya", "Thalayadi", "Thalgaswala", "Thalikulam", "Thalpitiya", "Thalpotha", "Thalwita", "Thambagalla", "Thambiluvil", "Thambuttegama", "Thampalagamam", "Thampiluvil", "Thanabela", "Thanamalwila", "Thandiyadi", "Thaneeroottu", "Thannamunai", "Thanthirimale", "Thanthirimulla", "Tharana Udawela", "Tharmapuram", "Thawalama", "Thebuwana", "Thelawala ( Rathmalana )", "Thelijjawila", "Thellipali", "Thelwatte", "Theniyankulam", "Thennampillai Farm", "Thepelpola", "Therunelveeli", "Thettathivu", "Thettativu", "Thevipuram", "Thihagoda", "Thihariya", "Thikkam", "Thikkodai", "Thimbirigaskatuwa", "Thimbiriyawa", "Thimelamadam", "Thimilatheevu", "Thiraimadu", "Thirukkovil", "Thirunagar", "Thirunagar North", "Thirunagar South", "Thirunelvely", "Thirupalugamam", "Thirupperumthurai", "Thiruvaiyaru 1St Unit", "Thiruvaiyaru 3", "Thiruvaiyaru West", "Thiruyaijar", "Thiruyaru", "Thisogama", "Thiththagonewe Kabithigollewa", "Tholangamuwa", "Thondaimanaru", "Thondamanagar", "Thonigala", "Thoranakada", "Thorayaya", "Thotawatta", "Thotawella", "Thudawa", "Thudugala", "Thumbowila", "Thumpalai", "Thunukkai", "Thuraineelavanai", "Timbirigaspitiya", "Tiranagama", "Tirappane", "Tiriyayi", "Tirukovil", "Tisogama (kuliyapitiya)", "Tissamaharama", "Tittagonewa", "Tittapattara", "Toduwawa", "Toppur", "Trincomalee", "Tulhiriya", "Tummodara", "Tunkama", "Tuntota", "Tuttiripitigama", "Uda Gomadiya", "Uda Maliboda", "Uda Peradeniya", "Udabage", "Udagaldeniya", "Udaha Hawupe", "Udahaupe", "Udahentenna", "Udahingulwala", "Udaiyarkaddu", "Udakarawita", "Udalamatta", "Udamadura", "Udamalala", "Udamattala", "Udaniriella", "Udapotha", "Udappuwa", "Udapussallawa", "Udasgiriya", "Udatalawinna", "Udatenna", "Udathuthiripitiya", "Udawalawe", "Udawatta", "Udawela", "Udawela Opanayaka", "Udispattuwa", "Udubaddawa", "Ududumbara", "Udugama", "Udugampola", "Udugoda", "Uduhawara", "Udumulla", "Udunuwara Colony", "Uduppity", "Udupussellawa", "Uduthurai", "Uduvila (Hambantota)", "Uduwa", "Uduwahinna", "Uduwaka", "Uduwara", "Uduwela", "Uggalboda", "Uhana", "Uhumiya", "Ukuwela", "Ulakkaiyanthivu", "Ulapane", "Ullinduwawa", "Ulpath Madawela", "Ulpotha Pallekele", "Ulpothagama", "Uluvitike", "Umaiyalpuram", "Unagalavehera", "Unawatuna", "Undugoda", "Unenwitiya", "Unnichai", "Unnichchai", "Unuwinna", "Uppalam", "Upparu", "Uppaveli", "Uppuvali", "Upuldeniya", "Uragaha", "Uragasmanhandiya", "Uraniya", "Urapola", "Uriyan West", "Uriyawa", "Urubokka", "Urugamuwa", "Urumpirai", "Urumutta", "Uruthirapuram D-8", "Uruthirapurisvaram", "Usgala Siyabmalangamuwa", "Ussapitiya", "Uswetakeiyawa", "Uswewa", "Uthayanagar", "Uttimaduwa", "Uttupulam", "Utumgama", "Uva Deegalla", "Uva Gangodagama", "Uva Karandagolla", "Uva Kudaoya", "Uva Mawelagama", "Uva Pelwatta", "Uva Tenna", "Uva Tissapura", "Uva Uduwara", "Uvaparanagama", "Uyanwatta", "Uyilankulam", "Vadamarachchi east vettilaikkerni", "Vaddakachchi", "Vaddukoddai", "Vaichchenai", "Vakaneri", "Vakarai", "Valaichenai", "Vallipunam", "Valvettithurai", "Vanathawilluwa", "Vangalai", "Vankalai", "Vannery", "Vantharumoolai", "Vattakandal", "Vattapalai", "Vavunatheevu", "Vavunikulam", "Vavuniya", "Veddagala", "Veddukkaddai", "Vee-Oya", "Veechukalmunai", "Veeramunai 2", "Velamboda", "Velanai", "Vellamanal", "Vellankulam", "Vellavely", "Velvettithurai", "Veppankulam", "Veyangalla", "Veyangoda", "Vidalthativu", "Vidiyagoda", "Viharahena", "Viharapalugama", "Vijayapura", "Vijeriya", "Vijithapura", "Vilanagama", "Vilavodai", "Vilsan Road", "Vinajagapuram", "Vipattuwa", "Visuvamadu", "Visvamadu", "Vitharandeniya", "Vivwgananthanagar", "Wadakada", "Waddakachchi", "Wadduwa", "Wadinagala", "Wadiyamankada", "Wadumunnegedara", "Waga", "Wahacotte", "Wahakotte", "Wahakula", "Wahalkada", "Wahalkada Kebithigollawa", "Wahamalgollawa Madawachchiya", "Wahamalgollewa", "Wahara", "Waharaka", "Waikkal", "Wakwella", "Walagambahuwa", "Walagedara", "Walahanduwa", "Walahaviddawewa", "Walakanda", "Walakumbura", "Walakumburumulla", "Walala", "Walallawita", "Walana", "Walapala", "Walapane", "Walasgala", "Walasmulla", "Walaswewa", "Walawela", "Waleboda", "Walgama", "Walgama ( Kaduwela )", "Walgammulla", "Waligepola", "Walpita", "Walpita Horana", "Walpola (Wp)", "Wanagamuwa", "Wanaluwewa", "Wanathawilluwa", "Wanchawela", "Wanduramba", "Wanduranmulla", "Wanela", "Wannigama", "Wannikudawewa", "Wannilhalagama", "Wannirasnayakapura", "Warakagoda", "Warakamura", "Warakapola", "Waralla", "Warawewa", "Wariyapola", "Warukandeniya", "Warunagama", "Waskaduwa", "Watadeniya", "Watagoda", "Watagoda Harispattuwa", "Watapotha", "Watareka (Padukka)", "Watawala", "Wathugahamulla", "Wathupitiwala", "Wathurakumbura", "Wathurugama", "Watinapaha", "Wattala", "Wattalpola (Kalutara)", "Wattappola", "Wattappola (Kandy)", "Wattegama", "Watugahamulla", "Watugedara", "Watura", "Waturawa", "Watuwatta", "Weboda", "Wedikumbura", "Weeoya", "Weeraketiya", "Weerakodiyana", "Weerapokuna", "Weerawila", "Weerawila Newtown", "Wegalla", "Wegowwa", "Weherayaya Handapanagala", "Wehigala", "Weihena", "Wekada", "Wekandawala", "Wekandawela", "Welampoda", "Welangahawatte", "Welawa Juncton", "Weligalla", "Weligama", "Weligampola", "Weligatta", "Weligepola", "Welihelatenna", "Welihinda", "Welikanda", "Welimada", "Welimupothana Horowpothana", "Welimuwapotana", "Welioya Project", "Welioya [ Ratnapura ]", "Welipanagahamulla", "Welipathayaya", "Welipenna", "Welipennagahamulla", "Welipothayaya", "Welisara", "Weliwa", "Weliweriya", "Wellagala", "Wellampitiya", "Wellarawa", "Wellawa", "Wellawa Juncton", "Wellawaya", "Welmilla", "Welmilla Junction", "Welpalla", "Wendala", "Wendaruwa", "Wennappuwa", "Wennoruwa", "Weragala", "Weragantota", "Werapitya", "Werawatta", "Werellagama", "Werunketagoda", "Wethara", "Wettawa", "Weuda", "Wewagama", "Wewala ( Piliyandala )", "Wewalawewa", "Wewatta", "Weweldeniya", "Wewelwatta", "Widulipura", "Wijayabapura", "Wijebahukanda", "Wijerama", "Wijeyakatupotha", "Wikiliya", "Wilanagama", "Wilaoya", "Wilgamuwa", "Wilpita", "Wilpotha", "Withikuliya", "Xxx Not Function", "Yagirala", "Yahalatenna", "Yakadapotha", "Yakawewa Madawachchiya", "Yakkala", "Yakkalamulla", "Yakwila", "Yanthampalawa", "Yapahuwa", "Yatadolawatta", "Yatagama", "Yatalamatta", "Yatapana", "Yatawara Junction", "Yatawatta", "Yatigala", "Yatigaloluwa", "Yatihalagala", "Yatihena ( Kaduwela )", "Yatiyana (Gampaha)", "Yatiyana (Matara)", "Yatiyantota", "Yattogoda", "Yodaela", "Yodhakanndiya", "Yogiyana", "Yudaganawa", "aluthoya (Habarana)", "aluthoya (aralaganwila)", "kallady", "kothalawala (  Kaduwela )", "mabodala Minuwangoda (Mirigama Branch)", "manampitiya", "millawa ( Kurunegala )", "neluwa ( Galle )", "pallekele"];

        // ─── Globals ─────────────────────────────────────────────────────────
        let currentProductData = null;
        const COMPLETE_ORDER_URL = '{{ route("seller.complete-order") }}';

        // ─── City Autocomplete ────────────────────────────────────────────────
        (function() {
            const input      = document.getElementById('district');
            const dropdown   = document.getElementById('cityDropdown');
            const clearBtn   = document.getElementById('cityClearBtn');
            const helperText = document.getElementById('cityHelperText');

            let activeIndex  = -1;
            let debounceTimer;
            let currentMatches = [];

            function escapeHtml(str) {
                return str.replace(/[&<>"']/g, m => ({"&":"&amp;","<":"&lt;",">":"&gt;",'"':"&quot;","'":"&#39;"}[m]));
            }

            function highlightMatch(text, query) {
                if (!query) return escapeHtml(text);
                const escaped = query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
                const regex = new RegExp('(' + escaped + ')', 'gi');
                return escapeHtml(text).replace(regex, '<mark>$1</mark>');
            }

            function openDropdown(matches, query) {
                currentMatches = matches;
                activeIndex = -1;
                dropdown.innerHTML = '';

                if (matches.length === 0) {
                    dropdown.innerHTML = '<div class="city-dropdown-empty"><span class="material-icons-round" style="font-size:16px;vertical-align:middle">search_off</span> No cities found for &ldquo;' + escapeHtml(query) + '&rdquo;</div>';
                } else {
                    const fragment = document.createDocumentFragment();
                    const limit = Math.min(matches.length, 80);
                    for (let i = 0; i < limit; i++) {
                        const item = document.createElement('div');
                        item.className = 'city-dropdown-item';
                        item.setAttribute('role', 'option');
                        item.setAttribute('data-index', i);
                        item.innerHTML = '<span class="material-icons-round" style="font-size:14px;color:var(--mid)">location_on</span>' + highlightMatch(matches[i], query);
                        item.addEventListener('mousedown', function(e) {
                            e.preventDefault();
                            selectCity(matches[parseInt(this.dataset.index)]);
                        });
                        fragment.appendChild(item);
                    }
                    if (matches.length > 80) {
                        const more = document.createElement('div');
                        more.className = 'city-dropdown-empty';
                        more.innerHTML = '<span class="material-icons-round" style="font-size:14px;vertical-align:middle">more_horiz</span> ' + (matches.length - 80) + ' more — keep typing to narrow down';
                        fragment.appendChild(more);
                    }
                    dropdown.appendChild(fragment);
                }

                dropdown.classList.add('open');
                input.setAttribute('aria-expanded', 'true');
            }

            function closeDropdown() {
                dropdown.classList.remove('open');
                dropdown.innerHTML = '';
                input.setAttribute('aria-expanded', 'false');
                activeIndex = -1;
                currentMatches = [];
            }

            function selectCity(city) {
                input.value = city;
                clearBtn.classList.add('visible');
                helperText.innerHTML = '<span class="city-badge-selected"><span class="material-icons-round" style="font-size:12px">check_circle</span>' + escapeHtml(city) + '</span>';
                closeDropdown();
                input.focus();
            }

            function clearCity() {
                input.value = '';
                clearBtn.classList.remove('visible');
                helperText.innerHTML = '<span class="material-icons-round" style="font-size:12px;vertical-align:middle">search</span> Start typing to search from 2,835 Sri Lanka cities/districts';
                closeDropdown();
                input.focus();
            }

            function search(query) {
                if (!query || query.length < 1) { closeDropdown(); return; }
                const q = query.toLowerCase().trim();
                const exact = [];
                const starts = [];
                const contains = [];
                for (let i = 0; i < SRI_LANKA_CITIES.length; i++) {
                    const city = SRI_LANKA_CITIES[i];
                    const lower = city.toLowerCase();
                    if (lower === q)              { exact.push(city); }
                    else if (lower.startsWith(q)) { starts.push(city); }
                    else if (lower.includes(q))   { contains.push(city); }
                }
                const matches = [...exact, ...starts, ...contains];
                openDropdown(matches, query);
            }

            function setActiveItem(index) {
                const items = dropdown.querySelectorAll('.city-dropdown-item');
                items.forEach((el, i) => el.classList.toggle('active', i === index));
                if (items[index]) items[index].scrollIntoView({ block: 'nearest' });
            }

            input.addEventListener('input', function() {
                const val = this.value;
                clearBtn.classList.toggle('visible', val.length > 0);
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => search(val), 120);
            });

            input.addEventListener('keydown', function(e) {
                const items = dropdown.querySelectorAll('.city-dropdown-item');
                if (!dropdown.classList.contains('open')) {
                    if (e.key === 'ArrowDown' && this.value) { search(this.value); return; }
                    return;
                }
                switch(e.key) {
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
                        if (activeIndex >= 0 && currentMatches[activeIndex]) {
                            selectCity(currentMatches[activeIndex]);
                        }
                        break;
                    case 'Escape':
                        closeDropdown();
                        break;
                    case 'Tab':
                        if (activeIndex >= 0 && currentMatches[activeIndex]) {
                            selectCity(currentMatches[activeIndex]);
                        } else {
                            closeDropdown();
                        }
                        break;
                }
            });

            input.addEventListener('focus', function() {
                if (this.value.length > 0) search(this.value);
            });

            input.addEventListener('blur', function() {
                setTimeout(closeDropdown, 180);
            });

            clearBtn.addEventListener('click', clearCity);

            // Init clear button visibility on page load (old() value)
            if (input.value) {
                clearBtn.classList.add('visible');
                helperText.innerHTML = '<span class="city-badge-selected"><span class="material-icons-round" style="font-size:12px">check_circle</span>' + escapeHtml(input.value) + '</span>';
            }
        })();

        // ─── Init ─────────────────────────────────────────────────────────────
        document.addEventListener('DOMContentLoaded', function () {
            @if($productId && $productCode && $productName && $productPrice)
                currentProductData = {
                id:            '{{ $productId }}',
                code:          '{{ $productCode }}',
                name:          '{{ $productName }}',
                display_price: parseFloat('{{ $productPrice }}') || 0,
                qty:           parseInt('{{ $productStock }}') || 0,
                image:         '{{ $productImage }}',
                description:   '{{ $productDescription ?? "" }}'
            };
            document.getElementById('submitBtn').disabled  = false;
            document.getElementById('printBtn').disabled   = false;
            document.getElementById('resetBtn').style.display = 'inline-flex';
            document.getElementById('quantity').disabled   = false;
            @endif

            // Auto-dismiss session alerts
            setTimeout(() => {
                document.querySelectorAll('.alert').forEach(el => {
                    el.style.transition = 'opacity .5s';
                    el.style.opacity    = '0';
                    setTimeout(() => el.remove(), 500);
                });
            }, 5000);
        });

        // ─── Fetch Product ────────────────────────────────────────────────────
        document.getElementById('fetchProductBtn').addEventListener('click', function () {
            const code = document.getElementById('searchProductCode').value.trim();
            if (!code) {
                Swal.fire({ icon: 'error', title: 'Missing Product Code', text: 'Please enter a product code', confirmButtonColor: '#4f46e5' });
                return;
            }
            fetchProductByCode(code);
        });

        document.getElementById('searchProductCode').addEventListener('keypress', function (e) {
            if (e.key === 'Enter') { e.preventDefault(); document.getElementById('fetchProductBtn').click(); }
        });

        async function fetchProductByCode(productCode) {
            const btn          = document.getElementById('fetchProductBtn');
            const originalHTML = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner"></span> Fetching...';

            try {
                const url      = `/seller/order/product/${encodeURIComponent(productCode)}/details`;
                const response = await fetch(url, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                });

                if (!response.ok) {
                    const err = await response.json().catch(() => ({}));
                    throw new Error(err.message || `HTTP error ${response.status}`);
                }

                const data = await response.json();

                if (data.success && data.product) {
                    displayProductInfo(data.product);
                    Swal.fire({ icon: 'success', title: 'Product Found!', text: `${data.product.name} loaded successfully`, timer: 2000, showConfirmButton: false });
                } else {
                    throw new Error(data.message || 'Invalid response from server');
                }
            } catch (error) {
                console.error('Fetch error:', error);
                Swal.fire({ icon: 'error', title: 'Product Not Found', text: error.message || 'Could not find product with this code.', confirmButtonColor: '#4f46e5' });
                resetForm();
            } finally {
                btn.disabled  = false;
                btn.innerHTML = originalHTML;
            }
        }

        // ─── Display Product Info ─────────────────────────────────────────────
        function displayProductInfo(product) {
            currentProductData = product;

            const infoCard = document.getElementById('productInfoCard');
            infoCard.style.display = 'block';
            infoCard.classList.add('show');
            document.getElementById('resetBtn').style.display = 'inline-flex';

            document.getElementById('product_code').value  = product.code;
            document.getElementById('product_id').value    = product.id;
            document.getElementById('product_name').value  = product.name;
            document.getElementById('product_price').value = product.display_price || product.price;
            document.getElementById('product_image').value = product.image || '';

            document.getElementById('displayProductName').textContent = product.name;
            document.getElementById('displayProductCode').textContent = product.code;

            const price = parseFloat(product.display_price) || parseFloat(product.price) || 0;
            document.getElementById('displayProductPrice').textContent = price.toFixed(2);

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
            if (product.image && product.image.trim() !== '') {
                imgEl.src           = product.image;
                imgEl.style.display = 'block';
                phEl.style.display  = 'none';
                imgEl.onerror = function () { this.style.display = 'none'; phEl.style.display = 'flex'; };
            } else {
                imgEl.style.display = 'none';
                phEl.style.display  = 'flex';
            }

            const qtyInput = document.getElementById('quantity');
            if (stock > 0) {
                qtyInput.max      = stock;
                qtyInput.disabled = false;
                if (parseInt(qtyInput.value) > stock) qtyInput.value = stock;
            } else {
                qtyInput.disabled = true;
                qtyInput.value    = 0;
            }

            document.getElementById('submitBtn').disabled = false;
            document.getElementById('printBtn').disabled  = false;
        }

        // ─── Reset Form ───────────────────────────────────────────────────────
        document.getElementById('resetBtn').addEventListener('click', function () {
            Swal.fire({
                title: 'Reset Form?', text: 'This will clear all product and order information',
                icon: 'warning', showCancelButton: true,
                confirmButtonColor: '#ef4444', cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, Reset', cancelButtonText: 'Cancel'
            }).then(result => {
                if (result.isConfirmed) {
                    resetForm();
                    Swal.fire({ icon: 'success', title: 'Form Reset', text: 'All fields have been cleared', timer: 1500, showConfirmButton: false });
                }
            });
        });

        function resetForm() {
            currentProductData = null;

            document.getElementById('productInfoCard').style.display = 'none';
            document.getElementById('productInfoCard').classList.remove('show');
            document.getElementById('resetBtn').style.display  = 'none';
            document.getElementById('searchProductCode').value = '';

            const ids = ['product_code','product_id','product_name','product_price','product_image',
                'customer_name','customer_phone','customer_phone_2','customer_address','quantity','remark'];
            ids.forEach(id => { document.getElementById(id).value = id === 'quantity' ? '1' : ''; });

            // Reset district/city
            document.getElementById('district').value = '';
            document.getElementById('cityClearBtn').classList.remove('visible');
            document.getElementById('cityHelperText').innerHTML = '<span class="material-icons-round" style="font-size:12px;vertical-align:middle">search</span> Start typing to search from 2,835 Sri Lanka cities/districts';

            document.getElementById('platform').value       = '';
            document.getElementById('payment_method').value = '';

            document.getElementById('displayProductName').textContent  = '-';
            document.getElementById('displayProductCode').textContent  = '-';
            document.getElementById('displayProductPrice').textContent = '0.00';
            document.getElementById('displayProductStock').textContent = '0';
            document.getElementById('availableStock').textContent      = '0';

            document.getElementById('productImagePreview').style.display    = 'none';
            document.getElementById('productImagePlaceholder').style.display = 'flex';

            document.getElementById('quantity').disabled  = true;
            document.getElementById('submitBtn').disabled = true;
            document.getElementById('printBtn').disabled  = true;
        }

        // ─── Quantity Validation ──────────────────────────────────────────────
        document.getElementById('quantity').addEventListener('input', function () {
            if (!currentProductData) return;
            const max = parseInt(currentProductData.qty) || 0;
            const qty = parseInt(this.value) || 0;
            if (qty > max) {
                this.value = max;
                Swal.fire({ icon: 'warning', title: 'Quantity Exceeds Stock', text: `Maximum available quantity is ${max} units`, timer: 2000, showConfirmButton: false });
            }
        });

        // ─── Print ────────────────────────────────────────────────────────────
        document.getElementById('printBtn').addEventListener('click', function () {
            if (!currentProductData) {
                Swal.fire({ icon: 'error', title: 'No Product Selected', text: 'Please select a product first', confirmButtonColor: '#4f46e5' });
                return;
            }

            const customerName    = document.getElementById('customer_name').value;
            const customerPhone   = document.getElementById('customer_phone').value;
            const customerPhone2  = document.getElementById('customer_phone_2').value;
            const customerAddress = document.getElementById('customer_address').value;
            const quantity        = document.getElementById('quantity').value;
            const district        = document.getElementById('district').value;

            if (!customerName || !customerPhone) {
                Swal.fire({ icon: 'error', title: 'Missing Information', text: 'Please fill in customer name and primary phone number', confirmButtonColor: '#4f46e5' });
                return;
            }

            const pw = window.open('', '', 'height=600,width=800');
            pw.document.write('<html><head><title>Order Print</title><style>');
            pw.document.write('body{font-family:Arial,sans-serif;padding:20px}');
            pw.document.write('h1{color:#4f46e5;margin-bottom:20px}');
            pw.document.write('table{width:100%;border-collapse:collapse;margin-top:20px}');
            pw.document.write('td{padding:10px;border-bottom:1px solid #e2e8f0}');
            pw.document.write('td:first-child{font-weight:bold;width:40%}');
            pw.document.write('.header{background:#f8fafc;padding:20px;border-radius:8px;margin-bottom:20px}');
            pw.document.write('</style></head><body>');
            pw.document.write('<div class="header"><h1>Order Details</h1><p>Date: ' + new Date().toLocaleDateString() + '</p></div>');
            pw.document.write('<table>');
            pw.document.write('<tr><td>Product Code:</td><td>' + currentProductData.code + '</td></tr>');
            pw.document.write('<tr><td>Product Name:</td><td>' + currentProductData.name + '</td></tr>');
            pw.document.write('<tr><td>Price:</td><td>Rs. ' + currentProductData.display_price + '</td></tr>');
            pw.document.write('<tr><td>Quantity:</td><td>' + quantity + '</td></tr>');
            pw.document.write('<tr><td>Total Amount:</td><td>Rs. ' + (currentProductData.display_price * quantity).toFixed(2) + '</td></tr>');
            pw.document.write('<tr><td>Customer Name:</td><td>' + customerName + '</td></tr>');
            pw.document.write('<tr><td>Primary Phone:</td><td>' + customerPhone + '</td></tr>');
            if (customerPhone2) pw.document.write('<tr><td>Secondary Phone:</td><td>' + customerPhone2 + '</td></tr>');
            if (customerAddress) pw.document.write('<tr><td>Delivery Address:</td><td>' + customerAddress + '</td></tr>');
            if (district) pw.document.write('<tr><td>District / City:</td><td>' + district + '</td></tr>');
            pw.document.write('</table></body></html>');
            pw.document.close();
            pw.focus();
            setTimeout(() => { pw.print(); pw.close(); }, 250);
        });

        // ─── Submit / Create Order ────────────────────────────────────────────
        document.getElementById('submitBtn').addEventListener('click', function (e) {
            e.preventDefault();

            if (!currentProductData || !document.getElementById('product_id').value) {
                Swal.fire({ icon: 'error', title: 'No Product Selected', text: 'Please fetch a product before creating an order', confirmButtonColor: '#4f46e5' });
                return;
            }

            const customerName   = document.getElementById('customer_name').value.trim();
            const customerPhone  = document.getElementById('customer_phone').value.trim();
            const paymentMethod  = document.getElementById('payment_method').value;
            const quantity       = parseInt(document.getElementById('quantity').value) || 0;
            const stock          = parseInt(currentProductData.qty) || 0;

            if (!customerName) {
                Swal.fire({ icon: 'error', title: 'Missing Information', text: 'Please enter customer name', confirmButtonColor: '#4f46e5' }); return;
            }
            if (!customerPhone) {
                Swal.fire({ icon: 'error', title: 'Missing Information', text: 'Please enter primary customer phone number', confirmButtonColor: '#4f46e5' }); return;
            }
            if (!paymentMethod) {
                Swal.fire({ icon: 'error', title: 'Missing Information', text: 'Please select a payment method', confirmButtonColor: '#4f46e5' }); return;
            }
            if (quantity <= 0) {
                Swal.fire({ icon: 'error', title: 'Invalid Quantity', text: 'Please enter a valid quantity', confirmButtonColor: '#4f46e5' }); return;
            }
            if (quantity > stock) {
                Swal.fire({ icon: 'error', title: 'Insufficient Stock', text: `Only ${stock} units available`, confirmButtonColor: '#4f46e5' }); return;
            }

            const customerPhone2 = document.getElementById('customer_phone_2').value.trim();
            const phoneDisplay   = customerPhone2 ? `${customerPhone} / ${customerPhone2}` : customerPhone;
            const district       = document.getElementById('district').value.trim();

            Swal.fire({
                title: 'Create Order?',
                html: `
                    <div style="text-align:left;margin-top:16px;line-height:1.8">
                        <p><strong>Product:</strong> ${currentProductData.name}</p>
                        <p><strong>Code:</strong> ${currentProductData.code}</p>
                        <p><strong>Quantity:</strong> ${quantity} units</p>
                        <p><strong>Customer:</strong> ${customerName}</p>
                        <p><strong>Phone:</strong> ${phoneDisplay}</p>
                        ${district ? '<p><strong>City / District:</strong> ' + district + '</p>' : ''}
                        <p><strong>Total:</strong> Rs. ${(currentProductData.display_price * quantity).toFixed(2)}</p>
                    </div>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, Create Order',
                cancelButtonText: 'Cancel',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    Swal.showLoading();
                    const form     = document.getElementById('orderForm');
                    const formData = new FormData(form);

                    return fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                        .then(response => {
                            if (!response.ok) {
                                return response.json().then(err => { throw new Error(err.message || 'Something went wrong'); });
                            }
                            return response.json();
                        })
                        .catch(error => {
                            Swal.showValidationMessage(`Request failed: ${error.message}`);
                        });
                },
                allowOutsideClick: () => !Swal.isLoading()

            }).then(result => {
                if (result.isConfirmed && result.value && result.value.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Order Created!',
                        html: `<p>${result.value.message || 'Order has been successfully created.'}</p>
                               <p style="margin-top:8px;color:#64748b;font-size:13px">Redirecting to complete orders…</p>`,
                        timer: 2000,
                        timerProgressBar: true,
                        showConfirmButton: false,
                        willClose: () => { window.location.href = COMPLETE_ORDER_URL; }
                    });
                } else if (result.value && !result.value.success) {
                    Swal.fire({ icon: 'error', title: 'Error', text: result.value.message || 'Failed to create order', confirmButtonColor: '#4f46e5' });
                }
            });
        });

        // Fallback: prevent accidental native submit
        document.getElementById('orderForm').addEventListener('submit', function (e) {
            if (e.submitter && e.submitter.id === 'submitBtn') e.preventDefault();
        });
    </script>
@endpush
