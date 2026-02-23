@extends('seller.layout.app')

@push('title')
    My Shop
@endpush

@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&family=DM+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        :root {
            --primary:       #4f46e5;
            --primary-hover: #4338ca;
            --primary-light: #eef2ff;
            --success:       #10b981;
            --danger:        #ef4444;
            --warning:       #f59e0b;
            --dark:          #0f172a;
            --mid:           #64748b;
            --border:        #e2e8f0;
            --surface:       #ffffff;
            --bg:            #f8fafc;
            --mono:          'DM Mono', monospace;
            --sans:          'DM Sans', sans-serif;
            --radius-xl:     16px;
            --radius-lg:     12px;
            --radius-md:     8px;
            --shadow-xs:     0 1px 2px rgba(0,0,0,.05);
            --shadow-sm:     0 2px 8px rgba(0,0,0,.07);
            --shadow-md:     0 6px 20px rgba(0,0,0,.09);
            --shadow-lg:     0 12px 40px rgba(0,0,0,.12);
        }

        *, *::before, *::after { box-sizing: border-box; }
        body { font-family: var(--sans); background: var(--bg); }

        /* ─── Layout ─── */
        .shop-wrapper { padding: 32px; max-width: 1600px; margin: 0 auto; }
        @media (max-width: 768px) { .shop-wrapper { padding: 16px; } }

        /* ─── Page Header ─── */
        .page-header {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 28px;
        }
        .page-title {
            font-size: 26px;
            font-weight: 800;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 0 0 4px;
        }
        .page-subtitle { font-size: 14px; color: var(--mid); margin: 0; }
        .header-actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            align-items: center;
        }

        /* ─── Search ─── */
        .search-wrap {
            position: relative;
            width: 320px;
        }
        @media (max-width: 576px) { .search-wrap { width: 100%; } }
        .search-input {
            width: 100%;
            padding: 11px 44px 11px 16px;
            border: 2px solid var(--border);
            border-radius: var(--radius-lg);
            font-family: var(--sans);
            font-size: 14px;
            background: var(--surface);
            transition: border-color .2s, box-shadow .2s;
        }
        .search-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79,70,229,.1);
        }
        .search-icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--mid);
            cursor: pointer;
            background: none;
            border: none;
            padding: 0;
        }

        /* ─── Search Hint ─── */
        .search-hint {
            font-size: 12px;
            color: var(--mid);
            margin-top: 6px;
            padding-left: 4px;
        }
        .search-hint i {
            font-size: 12px;
            margin-right: 4px;
            color: var(--primary);
        }

        /* ─── Next Code Banner ─── */
        .code-banner {
            display: flex;
            gap: 16px;
            align-items: center;
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border: 2px solid var(--border);
            border-left: 4px solid var(--primary);
            border-radius: var(--radius-lg);
            padding: 18px 22px;
            margin-bottom: 24px;
            flex-wrap: wrap;
            box-shadow: var(--shadow-sm);
        }
        .code-banner-label {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .6px;
            color: var(--mid);
            margin-bottom: 4px;
        }
        .code-banner-value {
            font-family: var(--mono);
            font-size: 22px;
            font-weight: 600;
            color: var(--primary);
            user-select: all;
        }
        .code-banner-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-left: auto;
        }
        .chip-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: var(--radius-md);
            font-size: 13px;
            font-weight: 600;
            border: 2px solid var(--border);
            cursor: pointer;
            transition: all .2s;
            background: var(--bg);
            color: var(--dark);
            font-family: var(--sans);
        }
        .chip-btn:hover {
            border-color: var(--primary);
            background: var(--primary-light);
            color: var(--primary);
            transform: translateY(-1px);
        }
        .chip-btn.primary {
            background: var(--primary);
            border-color: var(--primary);
            color: #fff;
        }
        .chip-btn.primary:hover {
            background: var(--primary-hover);
            border-color: var(--primary-hover);
            color: #fff;
        }

        /* ─── Stats ─── */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 24px;
        }
        @media (max-width: 900px) { .stats-row { grid-template-columns: repeat(2,1fr); } }
        @media (max-width: 480px) { .stats-row { grid-template-columns: repeat(2,1fr); } }

        .stat-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 14px;
            transition: all .2s;
            cursor: pointer;
        }
        .stat-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-md); }
        .stat-icon-wrap {
            width: 48px; height: 48px;
            border-radius: var(--radius-md);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .si-purple { background: #ede9fe; color: var(--primary); }
        .si-green  { background: #d1fae5; color: var(--success); }
        .si-amber  { background: #fef3c7; color: var(--warning); }
        .si-red    { background: #fee2e2; color: var(--danger); }
        .stat-num  { font-size: 28px; font-weight: 800; color: var(--dark); line-height: 1; }
        .stat-lbl  { font-size: 13px; color: var(--mid); margin-top: 2px; }

        /* ─── Quick Filters ─── */
        .filter-row {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 24px;
        }
        .filt-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border: 2px solid var(--border);
            border-radius: 999px;
            background: var(--surface);
            font-size: 13px;
            font-weight: 600;
            color: var(--dark);
            cursor: pointer;
            transition: all .2s;
            font-family: var(--sans);
        }
        .filt-btn:hover {
            border-color: var(--primary);
            color: var(--primary);
            transform: translateY(-1px);
        }
        .filt-btn.active {
            background: var(--primary);
            border-color: var(--primary);
            color: #fff;
        }

        /* ─── Sort Dropdown ─── */
        .sort-wrapper {
            display: flex;
            align-items: center;
            margin-left: auto;
        }
        .sort-select {
            padding: 8px 16px;
            border: 2px solid var(--border);
            border-radius: var(--radius-md);
            font-family: var(--sans);
            font-size: 13px;
            font-weight: 600;
            color: var(--dark);
            background: var(--surface);
            cursor: pointer;
            transition: all .2s;
        }
        .sort-select:hover, .sort-select:focus {
            border-color: var(--primary);
            outline: none;
        }

        /* ─── Product Grid ─── */
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 24px;
        }
        @media (max-width: 576px) {
            .product-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
        }

        /* ─── Product Card ─── */
        .p-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-xl);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: all .3s cubic-bezier(.4,0,.2,1);
            box-shadow: var(--shadow-xs);
            animation: fadeUp .45s ease-out both;
            position: relative;
        }
        .p-card:hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow-lg);
            border-color: var(--primary);
        }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* Image area */
        .p-img-wrap {
            height: 180px;
            background: linear-gradient(135deg, #f1f5f9 0%, #e8edf5 100%);
            display: flex; align-items: center; justify-content: center;
            position: relative;
            overflow: hidden;
        }
        .p-img { max-width: 100%; max-height: 100%; object-fit: contain; transition: transform .3s; }
        .p-card:hover .p-img { transform: scale(1.06); }
        .p-no-img { font-size: 52px; color: #cbd5e1; }

        /* Floating badges */
        .p-badges {
            position: absolute; top: 10px; left: 10px; right: 10px;
            display: flex; justify-content: space-between; pointer-events: none;
        }
        .p-badge {
            padding: 4px 10px; border-radius: 999px;
            font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .4px;
            box-shadow: var(--shadow-xs);
        }
        .pb-discount { background: var(--danger); color: #fff; }
        .pb-in    { background: #d1fae5; color: #065f46; }
        .pb-low   { background: #fef3c7; color: #92400e; }
        .pb-out   { background: #fee2e2; color: #991b1b; }

        /* Card body */
        .p-body {
            padding: 18px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .p-name {
            font-size: 16px;
            font-weight: 700;
            color: var(--dark);
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            min-height: 44px;
            margin-bottom: 12px;
        }

        /* Product Code Chip */
        .p-code-chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: var(--primary-light);
            border: 1.5px solid #c7d2fe;
            border-radius: 30px;
            font-family: var(--mono);
            font-size: 13px;
            font-weight: 600;
            color: var(--primary);
            cursor: pointer;
            transition: all .2s;
            user-select: none;
            margin-bottom: 12px;
            width: fit-content;
        }
        .p-code-chip:hover {
            background: #e0e7ff;
            border-color: var(--primary);
            color: var(--primary-hover);
            transform: translateY(-1px);
            box-shadow: var(--shadow-sm);
        }

        /* Qty pill */
        .p-qty {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 600;
            background: #f1f5f9;
            color: var(--mid);
            white-space: nowrap;
        }

        .p-meta-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 12px;
            flex-wrap: wrap;
        }

        /* Category badge */
        .p-category {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
            background: #f8fafc;
            color: var(--mid);
            border: 1px solid var(--border);
        }

        /* Price */
        .p-price {
            font-size: 22px;
            font-weight: 800;
            color: var(--danger);
            margin-bottom: 16px;
        }
        .p-price-original {
            font-size: 14px;
            font-weight: 400;
            color: #9ca3af;
            text-decoration: line-through;
            margin-left: 8px;
        }

        /* Actions - Only Copy and View buttons */
        .p-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            margin-top: auto;
        }
        .act-btn {
            display: flex; align-items: center; justify-content: center; gap: 5px;
            padding: 10px 8px;
            border-radius: var(--radius-md);
            font-size: 12px; font-weight: 600;
            border: none; cursor: pointer;
            transition: all .2s;
            font-family: var(--sans);
            text-decoration: none;
        }
        .act-copy {
            background: var(--dark); color: #fff;
        }
        .act-copy:hover { background: #1e293b; transform: translateY(-1px); }
        .act-view {
            background: var(--primary); color: #fff;
        }
        .act-view:hover { background: var(--primary-hover); transform: translateY(-1px); }

        /* ─── Empty State ─── */
        .empty-state {
            text-align: center;
            padding: 72px 24px;
            background: var(--surface);
            border: 2px dashed var(--border);
            border-radius: var(--radius-xl);
            margin-top: 24px;
        }
        .empty-icon { font-size: 64px; color: #cbd5e1; margin-bottom: 16px; }

        /* ─── Pagination ─── */
        .pag-wrap {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 18px;
            margin-top: 32px;
            display: flex;
            justify-content: center;
        }
        .custom-pagination .page-link {
            border: none; color: var(--dark);
            padding: 9px 16px; margin: 0 3px;
            border-radius: var(--radius-md); font-weight: 600;
            transition: all .2s;
        }
        .custom-pagination .page-link:hover { background: var(--primary); color: #fff; }
        .custom-pagination .page-item.active .page-link {
            background: var(--primary); color: #fff; border: none;
        }

        /* ─── Toast ─── */
        .toast-float {
            position: fixed; top: 22px; right: 22px; z-index: 9999;
            min-width: 290px;
            background: var(--surface);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-lg);
            border-left: 4px solid var(--success);
            padding: 14px 18px;
            display: flex; align-items: flex-start; gap: 12px;
            transform: translateX(360px); opacity: 0;
            transition: all .35s cubic-bezier(.68,-.55,.265,1.55);
        }
        .toast-float.show { transform: translateX(0); opacity: 1; }
        .toast-float .ti { font-size: 22px; }
        .toast-float .tc strong { display: block; font-size: 14px; color: var(--dark); }
        .toast-float .tc p { font-size: 13px; color: var(--mid); margin: 2px 0 0; }
        .toast-float .btn-close { margin-left: auto; align-self: flex-start; flex-shrink: 0; }

        /* ─── SweetAlert2 Custom Styles ─── */
        .swal2-popup {
            font-family: var(--sans);
            border-radius: var(--radius-lg);
            padding: 24px;
        }
        .swal2-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--dark);
        }
        .swal2-html-container {
            font-size: 14px;
            color: var(--mid);
            margin: 16px 0;
        }
        .product-code-highlight {
            background: var(--primary-light);
            border: 2px solid var(--primary);
            border-radius: var(--radius-md);
            padding: 12px 16px;
            font-family: var(--mono);
            font-size: 18px;
            font-weight: 600;
            color: var(--primary);
            display: inline-block;
            margin: 8px 0;
            letter-spacing: 1px;
        }
        .product-info-text {
            font-size: 14px;
            color: var(--mid);
            margin-bottom: 8px;
        }

        /* ─── Responsive tweaks ─── */
        @media (max-width: 576px) {
            .p-body { padding: 12px; }
            .p-name { font-size: 13px; }
            .p-price { font-size: 18px; }
            .p-code-chip { font-size: 11px; padding: 6px 12px; }
            .code-banner { flex-direction: column; gap: 10px; }
            .code-banner-actions { margin-left: 0; width: 100%; }
            .chip-btn { flex: 1; justify-content: center; }
            .act-btn { font-size: 11px; padding: 8px 4px; }
            .sort-wrapper { margin-left: 0; width: 100%; }
            .sort-select { width: 100%; }
        }
    </style>
@endpush

@section('content')
    <div class="shop-wrapper">

        {{-- Header --}}
        <div class="page-header">
            <div>
                <h1 class="page-title">
                    <span class="material-icons-round" style="color:var(--primary);font-size:30px">storefront</span>
                    My Shop
                </h1>
                <p class="page-subtitle">Manage your products and inventory</p>
            </div>
            <div class="header-actions">
                <div class="search-wrap">
                    <input type="text"
                           id="productSearch"
                           class="search-input"
                           placeholder="🔍 Search by Product Code, Name..."
                           value="{{ request()->get('search','') }}"
                           autocomplete="off">
                    <button class="search-icon" onclick="performSearch()">
                        <span class="material-icons-round" style="font-size:20px">search</span>
                    </button>
                    <div class="search-hint">
                        <i class="fas fa-barcode"></i> Try: DX/ITM/0001 or product name
                    </div>
                </div>
                <a href="{{ route('seller.product.create') }}"
                   class="chip-btn primary">
                    <span class="material-icons-round" style="font-size:18px">add</span>
                    Add Product
                </a>
            </div>
        </div>

        {{-- Next Code Banner --}}
        @if(isset($nextProductCode))
            <div class="code-banner" id="codeBanner">
                <div>
                    <div class="code-banner-label">
                        <span class="material-icons-round" style="font-size:13px;vertical-align:middle">qr_code</span>
                        Next Available Product Code
                    </div>
                    <div class="code-banner-value" id="nextCodeDisplay">{{ $nextProductCode }}</div>
                </div>
                <div class="code-banner-actions">
                    <button class="chip-btn primary" onclick="copyText('{{ $nextProductCode }}')">
                        <span class="material-icons-round" style="font-size:16px">content_copy</span>
                        Copy Code
                    </button>
                    <button class="chip-btn" onclick="copyText('DX/ITM/')">
                        <span class="material-icons-round" style="font-size:16px">tag</span>
                        Copy Prefix
                    </button>
                </div>
            </div>
        @endif

        {{-- Stats --}}
        @php
            $totalProducts = $products->total();
            $inStock  = $products->where('qty', '>', 0)->count();
            $lowStock = $products->where('qty', '>', 0)->where('qty', '<=', DB::raw('min_qty'))->count();
            $outStock = $products->where('qty', '<=', 0)->count();
        @endphp
        <div class="stats-row">
            <div class="stat-card" onclick="filterProducts('all')" title="View all products">
                <div class="stat-icon-wrap si-purple">
                    <span class="material-icons-round">inventory_2</span>
                </div>
                <div>
                    <div class="stat-num">{{ $totalProducts }}</div>
                    <div class="stat-lbl">Total Products</div>
                </div>
            </div>
            <div class="stat-card" onclick="filterProducts('in_stock')" title="View in-stock products">
                <div class="stat-icon-wrap si-green">
                    <span class="material-icons-round">check_circle</span>
                </div>
                <div>
                    <div class="stat-num">{{ $inStock }}</div>
                    <div class="stat-lbl">In Stock</div>
                </div>
            </div>
            <div class="stat-card" onclick="filterProducts('low_stock')" title="View low-stock products">
                <div class="stat-icon-wrap si-amber">
                    <span class="material-icons-round">warning</span>
                </div>
                <div>
                    <div class="stat-num">{{ $lowStock }}</div>
                    <div class="stat-lbl">Low Stock</div>
                </div>
            </div>
            <div class="stat-card" onclick="filterProducts('out_of_stock')" title="View out-of-stock products">
                <div class="stat-icon-wrap si-red">
                    <span class="material-icons-round">cancel</span>
                </div>
                <div>
                    <div class="stat-num">{{ $outStock }}</div>
                    <div class="stat-lbl">Out of Stock</div>
                </div>
            </div>
        </div>

        {{-- Filters & Sort --}}
        <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <div class="filter-row">
                <button class="filt-btn {{ !request()->has('filter') || request()->filter == 'all' ? 'active' : '' }}" onclick="filterProducts('all')">
                    <span class="material-icons-round" style="font-size:16px">grid_view</span>All
                </button>
                <button class="filt-btn {{ request()->filter == 'in_stock' ? 'active' : '' }}" onclick="filterProducts('in_stock')">
                    <span class="material-icons-round" style="font-size:16px">check_circle</span>In Stock
                </button>
                <button class="filt-btn {{ request()->filter == 'low_stock' ? 'active' : '' }}" onclick="filterProducts('low_stock')">
                    <span class="material-icons-round" style="font-size:16px">warning</span>Low Stock
                </button>
                <button class="filt-btn {{ request()->filter == 'out_of_stock' ? 'active' : '' }}" onclick="filterProducts('out_of_stock')">
                    <span class="material-icons-round" style="font-size:16px">cancel</span>Out of Stock
                </button>
            </div>
            <div class="sort-wrapper">
                <select id="sortSelect" class="sort-select" onchange="changeSort()">
                    <option value="newest" {{ request()->get('sort', 'newest') == 'newest' ? 'selected' : '' }}>🆕 Newest First</option>
                    <option value="code_asc" {{ request()->get('sort') == 'code_asc' ? 'selected' : '' }}>📦 Code: A to Z</option>
                    <option value="code_desc" {{ request()->get('sort') == 'code_desc' ? 'selected' : '' }}>📦 Code: Z to A</option>
                    <option value="price_low" {{ request()->get('sort') == 'price_low' ? 'selected' : '' }}>💰 Price: Low to High</option>
                    <option value="price_high" {{ request()->get('sort') == 'price_high' ? 'selected' : '' }}>💰 Price: High to Low</option>
                    <option value="name_asc" {{ request()->get('sort') == 'name_asc' ? 'selected' : '' }}>🔤 Name: A to Z</option>
                    <option value="name_desc" {{ request()->get('sort') == 'name_desc' ? 'selected' : '' }}>🔤 Name: Z to A</option>
                </select>
            </div>
        </div>

        {{-- Alerts --}}
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 mb-4" role="alert">
                <span class="material-icons-round">error</span>
                <div>{{ session('error') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-4" role="alert">
                <span class="material-icons-round">check_circle</span>
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Products Grid --}}
        @if($products->count() > 0)
            <div class="product-grid" id="productsContainer">
                @foreach($products as $product)
                    @php
                        $hasDiscount = $product->discount > 0;
                        $finalPrice  = $hasDiscount
                            ? $product->display_price * (1 - $product->discount / 100)
                            : $product->display_price;

                        if ($product->qty <= 0) {
                            $stockKey = 'pb-out';  $stockLabel = 'Out of Stock';
                        } elseif ($product->qty <= $product->min_qty) {
                            $stockKey = 'pb-low';  $stockLabel = 'Low Stock';
                        } else {
                            $stockKey = 'pb-in';   $stockLabel = 'In Stock';
                        }

                        $productImage = $product->image ? asset('storage/'.$product->image) : '';
                        $productDescription = $product->description ?? 'No description available';
                    @endphp

                    <div class="p-card"
                         data-product-code="{{ $product->code }}"
                         data-product-name="{{ $product->name }}"
                         data-product-price="{{ $finalPrice }}"
                         data-product-stock="{{ $product->qty }}"
                         data-product-image="{{ $productImage }}"
                         data-product-description="{{ $productDescription }}">

                        {{-- Image --}}
                        <div class="p-img-wrap">
                            @if($product->image)
                                <img src="{{ asset('storage/'.$product->image) }}"
                                     alt="{{ $product->name }}"
                                     class="p-img"
                                     loading="lazy"
                                     onerror="this.onerror=null;this.src='{{ asset('images/default-product.png') }}'">
                            @else
                                <span class="material-icons-round p-no-img">inventory_2</span>
                            @endif

                            <div class="p-badges">
                                @if($hasDiscount)
                                    <span class="p-badge pb-discount">-{{ $product->discount }}%</span>
                                @else
                                    <span></span>
                                @endif
                                <span class="p-badge {{ $stockKey }}">{{ $stockLabel }}</span>
                            </div>
                        </div>

                        {{-- Body --}}
                        <div class="p-body">
                            {{-- Product Code Chip --}}
                            <div class="p-code-chip"
                                 title="Click to show options: {{ $product->code }}"
                                 onclick="showProductCodePopup(this.closest('.p-card'))">
                                <span class="material-icons-round" style="font-size:16px">qr_code_scanner</span>
                                {{ $product->code }}
                            </div>

                            <h3 class="p-name" title="{{ $product->name }}">{{ $product->name }}</h3>

                            {{-- Meta row: Category + Qty --}}
                            <div class="p-meta-row">
                                <span class="p-category">
                                    <span class="material-icons-round" style="font-size:14px">folder</span>
                                    {{ $product->category->name ?? 'Uncategorized' }}
                                </span>
                                <span class="p-qty" style="margin-left:auto">
                                    <span class="material-icons-round" style="font-size:13px">inventory</span>
                                    {{ $product->qty }} units
                                </span>
                            </div>

                            {{-- Price --}}
                            <div class="p-price">
                                Rs. {{ number_format($finalPrice, 2) }}
                                @if($hasDiscount)
                                    <span class="p-price-original">Rs. {{ number_format($product->display_price, 2) }}</span>
                                @endif
                            </div>

                            {{-- Actions - Only Copy and View buttons --}}
                            <div class="p-actions">
                                <button class="act-btn act-copy"
                                        onclick="showProductCodePopup(this.closest('.p-card'))"
                                        title="Show product code options">
                                    <span class="material-icons-round" style="font-size:16px">content_copy</span>
                                    <span class="d-none d-sm-inline">Copy</span>
                                </button>

                                <a href="{{ route('seller.shop.product.show', $product->code) }}"
                                   class="act-btn act-view"
                                   title="View product details">
                                    <span class="material-icons-round" style="font-size:16px">visibility</span>
                                    <span class="d-none d-sm-inline">View</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <div class="empty-icon">
                    <span class="material-icons-round" style="font-size:72px">inventory</span>
                </div>
                <h3 class="h4 mb-2">No Products Found</h3>
                <p class="text-muted mb-4">
                    @if(request()->has('search'))
                        No products match your search criteria "{{ request()->get('search') }}"
                    @else
                        You haven't added any products to your shop yet.
                    @endif
                </p>
                <a href="{{ route('seller.product.create') }}"
                   class="btn btn-primary d-inline-flex align-items-center gap-2">
                    <span class="material-icons-round">add</span>
                    Add Your First Product
                </a>
            </div>
        @endif

        {{-- Pagination --}}
        @if($products->hasPages())
            <div class="pag-wrap">
                {{ $products->onEachSide(1)->links('vendor.pagination.bootstrap-5') }}
            </div>
        @endif

        {{-- Results Count --}}
        @if($products->count() > 0)
            <div class="text-end mt-3 text-muted small">
                Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }} products
            </div>
        @endif
    </div>

    {{-- Toast Notification --}}
    <div class="toast-float" id="appToast">
        <span class="material-icons-round ti" id="toastIcon">check_circle</span>
        <div class="tc">
            <strong id="toastTitle">Copied!</strong>
            <p id="toastMsg">Copied to clipboard.</p>
        </div>
        <button class="btn-close" onclick="hideToast()"></button>
    </div>
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // =============================================
        // TOAST NOTIFICATION SYSTEM
        // =============================================
        let toastTimer;
        function showToast(title, msg, type = 'success') {
            const t = document.getElementById('appToast');
            const colors = { success:'#10b981', error:'#ef4444', warning:'#f59e0b', info:'#3b82f6' };
            const icons  = { success:'check_circle', error:'error', warning:'warning', info:'info' };

            t.style.borderLeftColor = colors[type];
            document.getElementById('toastIcon').textContent = icons[type];
            document.getElementById('toastIcon').style.color = colors[type];
            document.getElementById('toastTitle').textContent = title;
            document.getElementById('toastMsg').textContent = msg;
            t.classList.add('show');

            clearTimeout(toastTimer);
            toastTimer = setTimeout(hideToast, 3000);
        }

        function hideToast() {
            document.getElementById('appToast').classList.remove('show');
        }

        // =============================================
        // CLIPBOARD COPY UTILITIES
        // =============================================
        async function copyToClipboard(text) {
            try {
                await navigator.clipboard.writeText(String(text));
                showToast('Copied!', 'Copied to clipboard.', 'success');
                return true;
            } catch (err) {
                showToast('Error', 'Could not copy to clipboard.', 'error');
                return false;
            }
        }

        function copyText(text) {
            copyToClipboard(text);
        }

        // =============================================
        // SHOW PRODUCT CODE POPUP WITH 3 BUTTONS
        // =============================================
        function showProductCodePopup(cardElement) {
            // Get product data from card element
            const productCode = cardElement.getAttribute('data-product-code');
            const productName = cardElement.getAttribute('data-product-name');
            const productPrice = cardElement.getAttribute('data-product-price');
            const productStock = cardElement.getAttribute('data-product-stock');
            const productImage = cardElement.getAttribute('data-product-image');
            const productDescription = cardElement.getAttribute('data-product-description');

            Swal.fire({
                title: '📋 Product Code',
                html: `
                    <div style="margin-bottom: 16px;">
                        <div class="product-info-text">Product: <strong>${productName}</strong></div>
                        <div class="product-code-highlight">
                            ${productCode}
                        </div>
                    </div>
                `,
                showCancelButton: true,
                showDenyButton: true,
                confirmButtonText: '📦 Order Now',
                denyButtonText: '📋 Copy Code',
                cancelButtonText: '✕ Cancel',
                confirmButtonColor: '#4f46e5',
                denyButtonColor: '#0f172a',
                cancelButtonColor: '#64748b',
                reverseButtons: true,
                allowOutsideClick: false,
                customClass: {
                    popup: 'swal2-popup',
                    title: 'swal2-title',
                    htmlContainer: 'swal2-html-container'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Order Now button clicked - redirect with product data
                    redirectToOrderPage(productCode, productName, productPrice, productStock, productImage, productDescription);
                } else if (result.isDenied) {
                    // Copy Code button clicked
                    copyProductCode(productCode);
                }
                // Cancel button - just close popup
            });
        }

        // =============================================
        // COPY PRODUCT CODE
        // =============================================
        async function copyProductCode(code) {
            try {
                await navigator.clipboard.writeText(code);
                showToast('Copied!', 'Product code: ' + code, 'success');
            } catch (err) {
                showToast('Error', 'Failed to copy product code.', 'error');
            }
        }

        // =============================================
        // REDIRECT TO ORDER PAGE WITH PRODUCT DATA
        // =============================================
        function redirectToOrderPage(productCode, productName, productPrice, productStock, productImage, productDescription) {
            // Show loading toast
            showToast('Redirecting', 'Taking you to order page...', 'info');

            // Build URL with all product data
            const params = new URLSearchParams({
                product_code: productCode,
                product_name: productName,
                product_price: productPrice,
                product_stock: productStock,
                product_image: productImage,
                product_description: productDescription,
                quantity: 1
            });

            // Redirect to order page
            window.location.href = "{{ route('seller.new-order') }}?" + params.toString();
        }

        // =============================================
        // FILTER & SEARCH FUNCTIONS
        // =============================================
        function filterProducts(filter) {
            const url = new URL(window.location.href);
            if (filter !== 'all') {
                url.searchParams.set('filter', filter);
            } else {
                url.searchParams.delete('filter');
            }
            const search = document.getElementById('productSearch')?.value;
            if (search) {
                url.searchParams.set('search', search);
            }
            window.location.href = url.toString();
        }

        function changeSort() {
            const sort = document.getElementById('sortSelect').value;
            const url = new URL(window.location.href);
            url.searchParams.set('sort', sort);
            window.location.href = url.toString();
        }

        function performSearch() {
            const query = document.getElementById('productSearch').value.trim();
            const url = new URL(window.location.href);
            if (query) {
                url.searchParams.set('search', query);
            } else {
                url.searchParams.delete('search');
            }
            url.searchParams.delete('page');
            window.location.href = url.toString();
        }

        // =============================================
        // INITIALIZATION
        // =============================================
        document.addEventListener('DOMContentLoaded', function() {
            // Search input with debounce
            const searchInput = document.getElementById('productSearch');
            if (searchInput) {
                let debounceTimer;
                searchInput.addEventListener('input', function() {
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(() => {
                        if (this.value.length >= 2 || this.value.length === 0) {
                            performSearch();
                        }
                    }, 500);
                });

                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        performSearch();
                    }
                });
            }

            // Auto-dismiss alerts
            setTimeout(() => {
                document.querySelectorAll('.alert').forEach(alert => {
                    alert.classList.remove('show');
                    setTimeout(() => alert.remove(), 300);
                });
            }, 5000);

            // Auto-fill product data on order page if present in URL
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('product_code')) {
                fillProductDataFromURL(urlParams);
            }
        });

        // =============================================
        // AUTO-FILL PRODUCT DATA ON ORDER PAGE
        // =============================================
        function fillProductDataFromURL(urlParams) {
            // This function will be used on the order page
            const productCode = urlParams.get('product_code');
            const productName = urlParams.get('product_name');
            const productPrice = urlParams.get('product_price');
            const productStock = urlParams.get('product_stock');
            const productImage = urlParams.get('product_image');
            const productDescription = urlParams.get('product_description');

            // Fill form fields if they exist
            if (document.getElementById('product_code')) {
                document.getElementById('product_code').value = productCode || '';
            }
            if (document.getElementById('product_name')) {
                document.getElementById('product_name').value = productName || '';
            }
            if (document.getElementById('product_price')) {
                document.getElementById('product_price').value = productPrice || '';
            }
            if (document.getElementById('product_stock')) {
                document.getElementById('product_stock').textContent = productStock || '0';
            }
            if (document.getElementById('product_image') && productImage) {
                document.getElementById('product_image').src = productImage;
            }
            if (document.getElementById('product_description')) {
                document.getElementById('product_description').value = productDescription || '';
            }
        }
    </script>
@endpush
