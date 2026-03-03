@extends('seller.layout.app')

@push('title')
    My Shop
@endpush

@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&family=DM+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
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
            --r-xl: 16px;
            --r-lg: 12px;
            --r-md: 8px;
            --shadow-xs: 0 1px 2px rgba(0,0,0,.05);
            --shadow-sm: 0 2px 8px rgba(0,0,0,.07);
            --shadow-md: 0 6px 20px rgba(0,0,0,.09);
            --shadow-lg: 0 12px 40px rgba(0,0,0,.12);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; }
        body { font-family: var(--sans); background: var(--bg); }

        /* ── Layout ── */
        .shop-wrapper { padding: 28px; max-width: 1600px; margin: 0 auto; }

        /* ── Page Header ── */
        .page-header {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 24px;
        }
        .page-title {
            font-size: 24px;
            font-weight: 800;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 0 0 4px;
            line-height: 1.2;
        }
        .page-subtitle { font-size: 14px; color: var(--mid); }
        .header-actions { display: flex; gap: 10px; flex-wrap: wrap; align-items: center; }

        /* ── Search — single icon only ── */
        .search-wrap { position: relative; width: 300px; }
        .search-input {
            width: 100%;
            padding: 11px 48px 11px 14px;
            border: 2px solid var(--border);
            border-radius: var(--r-lg);
            font-family: var(--sans);
            font-size: 14px;
            background: var(--surface);
            transition: border-color .2s, box-shadow .2s;
            -webkit-appearance: none;
            appearance: none;
        }
        .search-input::-webkit-search-cancel-button,
        .search-input::-webkit-search-decoration,
        .search-input::-ms-clear { display: none; }
        .search-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79,70,229,.1);
        }
        .search-btn {
            position: absolute;
            right: 5px; top: 50%;
            transform: translateY(-50%);
            background: var(--primary);
            border: none;
            color: #fff;
            width: 34px; height: 34px;
            border-radius: var(--r-md);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            transition: background .2s;
        }
        .search-btn:hover { background: var(--primary-hover); }

        /* ── Next Code Banner ── */
        .code-banner {
            display: flex;
            gap: 16px;
            align-items: center;
            background: #fff;
            border: 2px solid var(--border);
            border-left: 4px solid var(--primary);
            border-radius: var(--r-lg);
            padding: 16px 20px;
            margin-bottom: 22px;
            flex-wrap: wrap;
            box-shadow: var(--shadow-sm);
        }
        .code-banner-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .6px; color: var(--mid); margin-bottom: 3px; }
        .code-banner-value { font-family: var(--mono); font-size: 20px; font-weight: 600; color: var(--primary); user-select: all; }
        .code-banner-actions { display: flex; gap: 8px; flex-wrap: wrap; margin-left: auto; }
        .chip-btn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 8px 14px; border-radius: var(--r-md);
            font-size: 13px; font-weight: 600;
            border: 2px solid var(--border);
            cursor: pointer; transition: all .2s;
            background: var(--bg); color: var(--dark);
            font-family: var(--sans); text-decoration: none;
        }
        .chip-btn:hover { border-color: var(--primary); background: var(--primary-light); color: var(--primary); transform: translateY(-1px); }
        .chip-btn.primary { background: var(--primary); border-color: var(--primary); color: #fff; }
        .chip-btn.primary:hover { background: var(--primary-hover); border-color: var(--primary-hover); color: #fff; }

        /* ── Stats ── */
        .stats-row { display: grid; grid-template-columns: repeat(4,1fr); gap: 14px; margin-bottom: 22px; }
        .stat-card {
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: var(--r-lg);
            padding: 18px;
            display: flex; align-items: center; gap: 14px;
            cursor: pointer; transition: all .2s;
        }
        .stat-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); border-color: var(--primary); }
        .stat-icon { width: 46px; height: 46px; border-radius: var(--r-md); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .si-purple { background:#ede9fe; color:var(--primary); }
        .si-green  { background:#d1fae5; color:var(--success); }
        .si-amber  { background:#fef3c7; color:var(--warning); }
        .si-red    { background:#fee2e2; color:var(--danger); }
        .stat-num { font-size: 26px; font-weight: 800; color: var(--dark); line-height: 1; }
        .stat-lbl { font-size: 12px; color: var(--mid); margin-top: 2px; }

        /* ── Filters ── */
        .toolbar {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            align-items: center;
            margin-bottom: 22px;
        }
        .filt-btn {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 8px 14px;
            border: 2px solid var(--border);
            border-radius: 999px;
            background: var(--surface);
            font-size: 13px; font-weight: 600; color: var(--dark);
            cursor: pointer; transition: all .2s;
            font-family: var(--sans);
        }
        .filt-btn:hover { border-color: var(--primary); color: var(--primary); }
        .filt-btn.active { background: var(--primary); border-color: var(--primary); color: #fff; }
        .sort-select {
            margin-left: auto;
            padding: 8px 14px;
            border: 2px solid var(--border);
            border-radius: var(--r-md);
            font-family: var(--sans);
            font-size: 13px; font-weight: 600; color: var(--dark);
            background: var(--surface); cursor: pointer; transition: border-color .2s;
        }
        .sort-select:focus { border-color: var(--primary); outline: none; }

        /* ── Product Grid ── */
        .product-grid { display: grid; grid-template-columns: repeat(auto-fill,minmax(270px,1fr)); gap: 22px; }

        /* ── Product Card ── */
        .p-card {
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: var(--r-xl);
            overflow: hidden;
            display: flex; flex-direction: column;
            transition: transform .28s ease, box-shadow .28s ease, border-color .28s ease;
            box-shadow: var(--shadow-xs);
            animation: fadeUp .4s ease-out both;
        }
        .p-card:hover { transform: translateY(-5px); box-shadow: var(--shadow-lg); border-color: var(--primary); }
        @keyframes fadeUp { from{opacity:0;transform:translateY(14px)} to{opacity:1;transform:translateY(0)} }

        .p-img-wrap {
            height: 175px;
            background: linear-gradient(135deg,#f1f5f9,#e8edf5);
            display: flex; align-items: center; justify-content: center;
            position: relative; overflow: hidden;
        }
        .p-img { max-width:100%; max-height:100%; object-fit:contain; transition:transform .3s; }
        .p-card:hover .p-img { transform:scale(1.05); }
        .p-no-img { font-size:48px; color:#cbd5e1; }

        .p-badges { position:absolute; top:10px; left:10px; right:10px; display:flex; justify-content:space-between; pointer-events:none; }
        .p-badge { padding:3px 9px; border-radius:999px; font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.4px; }
        .pb-discount { background:var(--danger); color:#fff; }
        .pb-in  { background:#d1fae5; color:#065f46; }
        .pb-low { background:#fef3c7; color:#92400e; }
        .pb-out { background:#fee2e2; color:#991b1b; }

        .p-body { padding:16px; flex:1; display:flex; flex-direction:column; }
        .p-name {
            font-size:15px; font-weight:700; color:var(--dark); line-height:1.4;
            display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical;
            overflow:hidden; min-height:42px; margin-bottom:10px;
        }
        .p-code-chip {
            display:inline-flex; align-items:center; gap:6px;
            padding:6px 13px;
            background:var(--primary-light);
            border:1.5px solid #c7d2fe;
            border-radius:999px;
            font-family:var(--mono); font-size:12px; font-weight:600; color:var(--primary);
            cursor:pointer; transition:all .2s; user-select:none;
            margin-bottom:10px; width:fit-content;
        }
        .p-code-chip:hover { background:#e0e7ff; border-color:var(--primary); transform:translateY(-1px); }
        .p-meta-row { display:flex; align-items:center; gap:8px; margin-bottom:10px; flex-wrap:wrap; }
        .p-category {
            display:inline-flex; align-items:center; gap:4px;
            padding:3px 9px; border-radius:6px;
            font-size:11px; font-weight:600;
            background:#f8fafc; color:var(--mid); border:1px solid var(--border);
        }
        .p-qty {
            display:inline-flex; align-items:center; gap:3px;
            padding:3px 9px; border-radius:999px;
            font-size:11px; font-weight:600;
            background:#f1f5f9; color:var(--mid);
            margin-left:auto;
        }
        .p-price { font-size:20px; font-weight:800; color:var(--danger); margin-bottom:14px; }
        .p-price-orig { font-size:13px; font-weight:400; color:#9ca3af; text-decoration:line-through; margin-left:6px; }

        /* ── Card Actions ── */
        .p-actions { display:grid; grid-template-columns:1fr 1fr; gap:8px; margin-top:auto; }
        .act-btn {
            display:flex; align-items:center; justify-content:center; gap:5px;
            padding:10px 6px;
            border-radius:var(--r-md);
            font-size:12px; font-weight:700;
            border:none; cursor:pointer;
            transition:all .2s;
            font-family:var(--sans);
            text-decoration:none !important;
            position:relative; z-index:1;
        }
        .act-copy { background:var(--dark); color:#fff !important; }
        .act-copy:hover { background:#1e293b; transform:translateY(-1px); color:#fff !important; }
        .act-view { background:var(--primary); color:#fff !important; }
        .act-view:hover { background:var(--primary-hover); transform:translateY(-1px); color:#fff !important; }

        /* ── Empty State ── */
        .empty-state {
            text-align:center; padding:64px 24px;
            background:var(--surface);
            border:2px dashed var(--border);
            border-radius:var(--r-xl); margin-top:20px;
        }

        /* ── Pagination ── */
        .custom-pagination .page-link { border:none; color:var(--dark); padding:8px 14px; margin:0 2px; border-radius:var(--r-md); font-weight:600; transition:all .2s; }
        .custom-pagination .page-link:hover { background:var(--primary); color:#fff; }
        .custom-pagination .page-item.active .page-link { background:var(--primary); color:#fff; border:none; }

        /* ── Toast ── */
        .toast-float {
            position:fixed; top:20px; right:20px; z-index:9999;
            min-width:280px;
            background:var(--surface);
            border-radius:var(--r-lg);
            box-shadow:var(--shadow-lg);
            border-left:4px solid var(--success);
            padding:13px 16px;
            display:flex; align-items:center; gap:10px;
            transform:translateX(360px); opacity:0;
            transition:all .32s cubic-bezier(.68,-.55,.265,1.55);
        }
        .toast-float.show { transform:translateX(0); opacity:1; }

        /* ── Responsive ── */
        @media (max-width:1100px) { .stats-row { grid-template-columns:repeat(2,1fr); } }
        @media (max-width:768px) {
            .shop-wrapper { padding:16px; }
            .product-grid { grid-template-columns:repeat(2,1fr); gap:12px; }
            .search-wrap { width:100%; }
            .header-actions { width:100%; }
            .page-header { flex-direction:column; }
            .code-banner { flex-direction:column; align-items:stretch; }
            .code-banner-actions { margin-left:0; }
            .chip-btn { flex:1; justify-content:center; }
            .sort-select { width:100%; margin-left:0; }
        }
        @media (max-width:480px) {
            .stats-row { grid-template-columns:repeat(2,1fr); }
            .product-grid { grid-template-columns:repeat(2,1fr); gap:10px; }
            .p-body { padding:12px; }
            .p-name { font-size:13px; min-height:36px; }
            .p-price { font-size:17px; }
            .p-code-chip { font-size:11px; padding:5px 10px; }
            .act-btn { font-size:11px; padding:8px 4px; }
        }

        /* SweetAlert2 overrides */
        .swal2-popup { font-family:var(--sans) !important; border-radius:var(--r-xl) !important; }
        .swal2-title { font-size:19px !important; font-weight:700 !important; color:var(--dark) !important; }
        .product-code-highlight {
            background:var(--primary-light); border:2px solid var(--primary);
            border-radius:var(--r-md); padding:10px 16px;
            font-family:var(--mono); font-size:17px; font-weight:600; color:var(--primary);
            display:inline-block; margin:6px 0; letter-spacing:.5px;
        }
    </style>
@endpush

@section('content')
    <div class="shop-wrapper">

        {{-- ── Page Header ── --}}
        <div class="page-header">
            <div>
                <h1 class="page-title">
                    <span class="material-icons-round" style="color:var(--primary);font-size:28px">storefront</span>
                    My Shop
                </h1>
                <p class="page-subtitle">Manage your products and inventory</p>
            </div>

            <div class="header-actions">
                <div class="search-wrap">
                    <input type="text"
                           id="productSearch"
                           class="search-input"
                           placeholder="Search by code or product name…"
                           value="{{ request()->get('search','') }}"
                           autocomplete="off">
                    <button class="search-btn" onclick="performSearch()" title="Search" type="button">
                        <span class="material-icons-round" style="font-size:19px">search</span>
                    </button>
                </div>

                <a href="{{ route('seller.product.create') }}" class="chip-btn primary">
                    <span class="material-icons-round" style="font-size:17px">add</span>
                    Add Product
                </a>
            </div>
        </div>

        {{-- ── Next Code Banner ── --}}
        @if(isset($nextProductCode))
            <div class="code-banner">
                <div>
                    <div class="code-banner-label">
                        <span class="material-icons-round" style="font-size:13px;vertical-align:middle">qr_code</span>
                        Next Available Product Code
                    </div>
                    <div class="code-banner-value">{{ $nextProductCode }}</div>
                </div>
                <div class="code-banner-actions">
                    <button class="chip-btn primary" onclick="copyText('{{ $nextProductCode }}')" type="button">
                        <span class="material-icons-round" style="font-size:15px">content_copy</span>
                        Copy Code
                    </button>
                    <button class="chip-btn" onclick="copyText('DX/ITM/')" type="button">
                        <span class="material-icons-round" style="font-size:15px">tag</span>
                        Copy Prefix
                    </button>
                </div>
            </div>
        @endif

        {{-- ── Stats ── --}}
        @php
            $totalProducts = $products->total();
            $inStock  = $products->where('qty', '>', 0)->count();
            $lowStock = $products->where('qty', '>', 0)->filter(fn($p) => $p->qty <= ($p->min_qty ?? 1))->count();
            $outStock = $products->where('qty', '<=', 0)->count();
        @endphp

        <div class="stats-row">
            <div class="stat-card" onclick="filterProducts('all')">
                <div class="stat-icon si-purple"><span class="material-icons-round">inventory_2</span></div>
                <div><div class="stat-num">{{ $totalProducts }}</div><div class="stat-lbl">Total Products</div></div>
            </div>
            <div class="stat-card" onclick="filterProducts('in_stock')">
                <div class="stat-icon si-green"><span class="material-icons-round">check_circle</span></div>
                <div><div class="stat-num">{{ $inStock }}</div><div class="stat-lbl">In Stock</div></div>
            </div>
            <div class="stat-card" onclick="filterProducts('low_stock')">
                <div class="stat-icon si-amber"><span class="material-icons-round">warning</span></div>
                <div><div class="stat-num">{{ $lowStock }}</div><div class="stat-lbl">Low Stock</div></div>
            </div>
            <div class="stat-card" onclick="filterProducts('out_of_stock')">
                <div class="stat-icon si-red"><span class="material-icons-round">cancel</span></div>
                <div><div class="stat-num">{{ $outStock }}</div><div class="stat-lbl">Out of Stock</div></div>
            </div>
        </div>

        {{-- ── Toolbar: filters + sort ── --}}
        <div class="toolbar">
            <button class="filt-btn {{ !request('filter') || request('filter') == 'all' ? 'active' : '' }}" onclick="filterProducts('all')" type="button">
                <span class="material-icons-round" style="font-size:15px">grid_view</span> All
            </button>
            <button class="filt-btn {{ request('filter') == 'in_stock' ? 'active' : '' }}" onclick="filterProducts('in_stock')" type="button">
                <span class="material-icons-round" style="font-size:15px">check_circle</span> In Stock
            </button>
            <button class="filt-btn {{ request('filter') == 'low_stock' ? 'active' : '' }}" onclick="filterProducts('low_stock')" type="button">
                <span class="material-icons-round" style="font-size:15px">warning</span> Low Stock
            </button>
            <button class="filt-btn {{ request('filter') == 'out_of_stock' ? 'active' : '' }}" onclick="filterProducts('out_of_stock')" type="button">
                <span class="material-icons-round" style="font-size:15px">cancel</span> Out of Stock
            </button>

            <select id="sortSelect" class="sort-select" onchange="changeSort()">
                <option value="newest"    {{ request('sort','newest') == 'newest'    ? 'selected':'' }}>🆕 Newest First</option>
                <option value="code_asc"  {{ request('sort') == 'code_asc'           ? 'selected':'' }}>📦 Code: A–Z</option>
                <option value="code_desc" {{ request('sort') == 'code_desc'          ? 'selected':'' }}>📦 Code: Z–A</option>
                <option value="price_low" {{ request('sort') == 'price_low'          ? 'selected':'' }}>💰 Price: Low–High</option>
                <option value="price_high"{{ request('sort') == 'price_high'         ? 'selected':'' }}>💰 Price: High–Low</option>
                <option value="name_asc"  {{ request('sort') == 'name_asc'           ? 'selected':'' }}>🔤 Name: A–Z</option>
                <option value="name_desc" {{ request('sort') == 'name_desc'          ? 'selected':'' }}>🔤 Name: Z–A</option>
            </select>
        </div>

        {{-- ── Alerts ── --}}
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 mb-4" role="alert">
                <span class="material-icons-round">error</span>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-4" role="alert">
                <span class="material-icons-round">check_circle</span>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- ── Products Grid ── --}}
        @if($products->count() > 0)
            <div class="product-grid">
                @foreach($products as $product)
                    @php
                        $hasDiscount = $product->discount > 0;
                        $finalPrice  = $hasDiscount
                            ? $product->display_price * (1 - $product->discount / 100)
                            : $product->display_price;

                        if ($product->qty <= 0) {
                            $stockKey   = 'pb-out';
                            $stockLabel = 'Out of Stock';
                        } elseif ($product->qty <= ($product->min_qty ?? 1)) {
                            $stockKey   = 'pb-low';
                            $stockLabel = 'Low Stock';
                        } else {
                            $stockKey   = 'pb-in';
                            $stockLabel = 'In Stock';
                        }
                    @endphp

                    <div class="p-card"
                         data-code="{{ $product->code }}"
                         data-name="{{ e($product->name) }}"
                         data-price="{{ $finalPrice }}"
                         data-stock="{{ $product->qty }}"
                         data-image="{{ $product->image ? asset('storage/'.$product->image) : '' }}">

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

                        <div class="p-body">
                            <div class="p-code-chip"
                                 title="Click to copy / order: {{ $product->code }}"
                                 onclick="showProductCodePopup(this.closest('.p-card'))">
                                <span class="material-icons-round" style="font-size:15px">qr_code_scanner</span>
                                {{ $product->code }}
                            </div>

                            <h3 class="p-name" title="{{ $product->name }}">{{ $product->name }}</h3>

                            <div class="p-meta-row">
                                <span class="p-category">
                                    <span class="material-icons-round" style="font-size:13px">folder</span>
                                    {{ $product->category->name ?? 'Uncategorized' }}
                                </span>

                                <span class="p-qty" style="margin-left:auto">
                                    <span class="material-icons-round" style="font-size:12px">inventory</span>
                                    {{ $product->qty }} units
                                </span>
                            </div>

                            <div class="p-price">
                                Rs. {{ number_format($finalPrice, 2) }}
                                @if($hasDiscount)
                                    <span class="p-price-orig">Rs. {{ number_format($product->display_price, 2) }}</span>
                                @endif
                            </div>

                            <div class="p-actions">
                                <button class="act-btn act-copy"
                                        onclick="showProductCodePopup(this.closest('.p-card'))"
                                        title="Copy / order"
                                        type="button">
                                    <span class="material-icons-round" style="font-size:15px">content_copy</span>
                                    Copy
                                </button>

                                <a href="{{ route('seller.shop.product.details', $product->code) }}"
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
                <span class="material-icons-round" style="font-size:64px;color:#cbd5e1;display:block;margin-bottom:12px">inventory</span>
                <h3 class="h5 mb-2">No Products Found</h3>
                <p class="text-muted mb-4">
                    @if(request('search'))
                        No products match "{{ request('search') }}"
                    @else
                        You haven't added any products yet.
                    @endif
                </p>
                <a href="{{ route('seller.product.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-2">
                    <span class="material-icons-round">add</span> Add Your First Product
                </a>
            </div>
        @endif

        {{-- ── Pagination (SAFE) ── --}}
        @if ($products->hasPages())
            <div class="d-flex justify-content-center mt-4">
                <div class="custom-pagination">
                    {{ $products->withQueryString()->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif

        @if($products->count() > 0)
            <div class="text-end mt-3 text-muted" style="font-size:13px">
                Showing {{ $products->firstItem() }}–{{ $products->lastItem() }} of {{ $products->total() }} products
            </div>
        @endif

    </div>

    {{-- Toast --}}
    <div class="toast-float" id="appToast">
        <span class="material-icons-round" id="toastIcon" style="flex-shrink:0">check_circle</span>
        <div style="flex:1">
            <strong id="toastTitle">Done</strong>
            <p id="toastMsg" class="mb-0" style="font-size:13px"></p>
        </div>
        <button class="btn-close" onclick="hideToast()" type="button"></button>
    </div>
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        /* ── Toast ── */
        let _toastTimer;
        function showToast(title, msg, type = 'success') {
            const colors = { success:'#10b981', error:'#ef4444', warning:'#f59e0b', info:'#3b82f6' };
            const icons  = { success:'check_circle', error:'error', warning:'warning', info:'info' };
            const t = document.getElementById('appToast');
            t.style.borderLeftColor = colors[type];
            document.getElementById('toastIcon').textContent = icons[type];
            document.getElementById('toastIcon').style.color = colors[type];
            document.getElementById('toastTitle').textContent = title;
            document.getElementById('toastMsg').textContent = msg;
            t.classList.add('show');
            clearTimeout(_toastTimer);
            _toastTimer = setTimeout(hideToast, 3000);
        }
        function hideToast() { document.getElementById('appToast').classList.remove('show'); }

        /* ── Clipboard ── */
        async function copyText(text) {
            try {
                await navigator.clipboard.writeText(String(text));
                showToast('Copied!', String(text), 'success');
            } catch {
                showToast('Error', 'Could not copy.', 'error');
            }
        }

        /* ── Product code popup ── */
        function showProductCodePopup(card) {
            const code  = card.dataset.code;
            const name  = card.dataset.name;
            const price = parseFloat(card.dataset.price).toFixed(2);
            const stock = card.dataset.stock;

            Swal.fire({
                title: '📋 Product Code',
                html: `
                    <div style="margin-bottom:14px">
                        <div style="margin-bottom:8px;font-weight:600">${name}</div>
                        <div class="product-code-highlight">${code}</div>
                        <div style="margin-top:12px;text-align:left;font-size:14px">
                            <div>💰 Price: <strong>Rs. ${price}</strong></div>
                            <div>📦 Stock: <strong>${stock} units</strong></div>
                        </div>
                    </div>`,
                showCancelButton: true,
                showDenyButton: true,
                confirmButtonText: '📦 Order Now',
                denyButtonText: '📋 Copy Code',
                cancelButtonText: '✕ Cancel',
                confirmButtonColor: '#4f46e5',
                denyButtonColor: '#0f172a',
                cancelButtonColor: '#64748b',
                reverseButtons: true
            }).then(result => {
                if (result.isConfirmed) {
                    const params = new URLSearchParams({
                        product_code: code,
                        product_name: name,
                        product_price: price,
                        product_stock: stock,
                        quantity: 1
                    });
                    window.location.href = "{{ route('seller.create.new-order') }}?" + params.toString();
                } else if (result.isDenied) {
                    copyText(code);
                }
            });
        }

        /* ── Filter / Sort / Search ── */
        function filterProducts(filter) {
            const url = new URL(window.location.href);
            filter !== 'all' ? url.searchParams.set('filter', filter) : url.searchParams.delete('filter');
            url.searchParams.delete('page');
            window.location.href = url.toString();
        }
        function changeSort() {
            const url = new URL(window.location.href);
            url.searchParams.set('sort', document.getElementById('sortSelect').value);
            url.searchParams.delete('page');
            window.location.href = url.toString();
        }
        function performSearch() {
            const q = document.getElementById('productSearch').value.trim();
            const url = new URL(window.location.href);
            q ? url.searchParams.set('search', q) : url.searchParams.delete('search');
            url.searchParams.delete('page');
            window.location.href = url.toString();
        }

        /* ── Init ── */
        document.addEventListener('DOMContentLoaded', () => {
            const si = document.getElementById('productSearch');
            if (si) {
                let timer;
                si.addEventListener('input', () => {
                    clearTimeout(timer);
                    timer = setTimeout(() => {
                        if (si.value.length >= 2 || si.value.length === 0) performSearch();
                    }, 500);
                });
                si.addEventListener('keypress', e => {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        performSearch();
                    }
                });
            }

            setTimeout(() => {
                document.querySelectorAll('.alert').forEach(a => {
                    a.classList.remove('show');
                    setTimeout(() => a.remove(), 300);
                });
            }, 5000);
        });
    </script>
@endpush
