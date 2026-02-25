@extends('seller.layout.app')

@push('title')
    {{ $product->name }} — Product Details
@endpush

@push('css')
    <link href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&family=DM+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />

    <style>
        :root {
            --primary:      #4f46e5;
            --primary-dark: #4338ca;
            --secondary:    #7c3aed;
            --success:      #10b981;
            --warning:      #f59e0b;
            --danger:       #ef4444;
            --surface:      #ffffff;
            --bg:           #f8fafc;
            --border:       #e2e8f0;
            --text:         #0f172a;
            --muted:        #64748b;
            --sans:         'DM Sans', sans-serif;
            --mono:         'DM Mono', monospace;
            --r-lg:         14px;
            --r-xl:         18px;
            --shadow-sm:    0 2px 8px rgba(0,0,0,.07);
            --shadow-md:    0 6px 20px rgba(0,0,0,.09);
        }

        *, *::before, *::after { box-sizing: border-box; }
        body { font-family: var(--sans); background: var(--bg); }

        .wrap { max-width: 1380px; margin: 0 auto; padding: 22px; }

        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--r-xl);
            box-shadow: var(--shadow-sm);
        }

        .breadcrumb { background: transparent; margin-bottom: 14px; }
        .breadcrumb a { color: var(--primary); text-decoration: none; }
        .breadcrumb .active { color: var(--muted); }

        .product-header {
            padding: 22px;
            position: relative;
            overflow: hidden;
            margin-bottom: 16px;
        }
        .product-header::before {
            content: '';
            position: absolute; left: 0; top: 0; right: 0; height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
        }
        .header-row { display: flex; gap: 16px; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; }
        .prod-title { font-size: 24px; font-weight: 800; color: var(--text); margin: 6px 0 4px; line-height: 1.25; }
        .prod-subtitle { color: var(--muted); margin: 0; font-size: 14px; }
        .meta-chips { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 8px; }
        .chip {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 5px 10px; border-radius: 999px;
            background: var(--bg); border: 1px solid var(--border);
            color: var(--muted); font-size: 12px; font-weight: 600;
        }
        .chip i { color: var(--primary); }

        .status-badge {
            position: absolute; top: 18px; right: 18px;
            padding: 5px 10px; border-radius: 999px;
            font-size: 11px; font-weight: 800;
            border: 2px solid;
            display: inline-flex; gap: 5px; align-items: center;
            text-transform: uppercase; letter-spacing: .4px;
        }
        .status-badge.active  { color: var(--success); background: rgba(16,185,129,.1);  border-color: rgba(16,185,129,.3); }
        .status-badge.inactive{ color: var(--danger);  background: rgba(239,68,68,.1);   border-color: rgba(239,68,68,.3);  }

        .header-actions { display: flex; gap: 8px; flex-wrap: wrap; }
        .hdr-btn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 9px 14px; border-radius: var(--r-lg);
            border: 1.5px solid var(--border);
            font-weight: 700; font-size: 13px;
            text-decoration: none; cursor: pointer;
            transition: all .2s; background: var(--surface); color: var(--text);
            font-family: var(--sans);
        }
        .hdr-btn:hover { background: var(--bg); transform: translateY(-1px); color: var(--text); }
        .hdr-btn.primary { background: var(--primary); border-color: var(--primary); color: #fff; }
        .hdr-btn.primary:hover { background: var(--primary-dark); color: #fff; }

        .main-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 16px;
        }

        .gallery { overflow: hidden; }
        .main-img-wrap {
            position: relative;
            height: 400px;
            background: linear-gradient(135deg, #f1f5f9, #e8edf5);
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: center;
        }
        .main-img { max-width: 100%; max-height: 100%; object-fit: contain; padding: 18px; transition: opacity .15s; }
        .img-actions { position: absolute; top: 12px; right: 12px; display: flex; gap: 8px; }
        .icon-btn {
            width: 38px; height: 38px; border-radius: 999px;
            border: 1px solid var(--border);
            background: rgba(255,255,255,.9);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; transition: all .2s;
        }
        .icon-btn:hover { box-shadow: var(--shadow-sm); transform: translateY(-1px); }

        .thumbs {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 8px; padding: 12px;
            background: var(--surface);
        }
        .thumb {
            aspect-ratio: 1; border-radius: 10px;
            border: 2px solid transparent; overflow: hidden;
            cursor: pointer; transition: all .2s;
        }
        .thumb.active { border-color: var(--primary); }
        .thumb:hover  { border-color: var(--secondary); transform: translateY(-1px); }
        .thumb img    { width: 100%; height: 100%; object-fit: cover; }

        .side { display: flex; flex-direction: column; gap: 16px; }

        .box { padding: 18px; }
        .price-header { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px; }
        .box-title { font-weight: 800; color: var(--text); font-size: 15px; display: flex; align-items: center; gap: 7px; }
        .badge-off {
            background: rgba(16,185,129,.12); border: 1px solid rgba(16,185,129,.3);
            color: var(--success); font-weight: 800;
            padding: 3px 10px; border-radius: 999px; font-size: 12px;
        }
        .price-line  { display: flex; align-items: baseline; gap: 10px; flex-wrap: wrap; margin-top: 12px; }
        .price-big   { font-size: 28px; font-weight: 900; color: var(--primary); }
        .price-strike{ color: #94a3b8; text-decoration: line-through; font-weight: 600; }

        .info-grid { display: grid; grid-template-columns: repeat(2,1fr); gap: 10px; margin-top: 12px; }
        .info-mini {
            background: var(--bg); border: 1px solid var(--border);
            border-radius: var(--r-lg); padding: 12px;
        }
        .info-mini .lbl { color: var(--muted); font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .4px; }
        .info-mini .val { font-weight: 800; color: var(--text); margin-top: 3px; font-size: 15px; }

        .stock-note {
            margin-top: 12px; padding: 10px 12px;
            border-radius: var(--r-lg);
            background: var(--bg); border: 1px solid var(--border);
            color: var(--muted); font-weight: 700;
            display: flex; gap: 10px; align-items: center;
        }
        .pulse-dot {
            width: 9px; height: 9px; border-radius: 999px;
            background: {{ ($product->qty ?? 0) > 50 ? '#10b981' : (($product->qty ?? 0) > 10 ? '#f59e0b' : '#ef4444') }};
            animation: pulse 2s infinite; flex-shrink: 0;
        }
        @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.4} }

        .tabs { margin-top: 0; }
        .tabs-head {
            display: flex; gap: 6px; padding: 10px;
            background: var(--bg); border-bottom: 1px solid var(--border);
            overflow-x: auto;
        }
        .tab-btn {
            border: none; background: transparent;
            padding: 9px 12px; border-radius: var(--r-lg);
            font-weight: 800; font-size: 13px; color: var(--muted);
            cursor: pointer; white-space: nowrap;
            transition: all .2s; display: flex; gap: 6px; align-items: center;
            font-family: var(--sans);
        }
        .tab-btn.active { background: var(--surface); border: 1px solid var(--border); color: var(--primary); }
        .tab-body { padding: 20px; }
        .pane { display: none; }
        .pane.active { display: block; }

        .spec-table { width: 100%; border-collapse: collapse; }
        .spec-table th, .spec-table td { padding: 11px 14px; border-bottom: 1px solid var(--border); text-align: left; font-size: 14px; }
        .spec-table th { width: 32%; background: var(--bg); color: var(--text); font-weight: 800; }
        .spec-table td { color: var(--text); }

        .fab-wrap { position: fixed; right: 18px; bottom: 18px; z-index: 1000; }
        .fab {
            width: 54px; height: 54px; border-radius: 999px;
            background: var(--primary); border: none; color: #fff;
            box-shadow: var(--shadow-md);
            cursor: pointer; display: flex; align-items: center; justify-content: center;
            transition: all .2s;
        }
        .fab:hover { transform: translateY(-2px) scale(1.05); background: var(--primary-dark); }
        .fab-menu {
            position: absolute; bottom: 64px; right: 0;
            background: var(--surface); border: 1px solid var(--border);
            border-radius: var(--r-xl); box-shadow: var(--shadow-md);
            padding: 8px; min-width: 180px;
            display: none; flex-direction: column; gap: 4px;
        }
        .fab-wrap:hover .fab-menu { display: flex; }
        .fab-item {
            border: none; background: transparent;
            padding: 9px 12px; border-radius: var(--r-lg);
            cursor: pointer; font-weight: 700; font-size: 13px;
            color: var(--text); font-family: var(--sans);
            display: flex; gap: 8px; align-items: center;
            transition: all .2s; text-align: left;
        }
        .fab-item:hover       { background: var(--bg); color: var(--primary); }
        .fab-item.danger      { color: var(--danger); }
        .fab-item.danger:hover{ background: rgba(239,68,68,.08); }

        @media (max-width:1050px) {
            .main-grid { grid-template-columns: 1fr; }
            .main-img-wrap { height: 320px; }
        }
        @media (max-width:600px) {
            .wrap { padding: 12px; }
            .product-header { padding: 16px; }
            .prod-title { font-size: 19px; }
            .price-big  { font-size: 22px; }
            .main-img-wrap { height: 240px; }
            .thumbs { grid-template-columns: repeat(4,1fr); }
            .info-grid { grid-template-columns: 1fr 1fr; }
            .status-badge { position: static; margin-bottom: 8px; display: inline-flex; }
        }
        @media (max-width:420px) {
            .info-grid { grid-template-columns: 1fr; }
        }

        /* Print: hide floating button & menus */
        @media print {
            .fab-wrap, .img-actions { display:none !important; }
            body { background: #fff !important; }
            .card { box-shadow: none !important; }
        }
    </style>
@endpush

@section('content')
    @php
        $origPrice   = (float)($product->display_price ?? 0);
        $costPrice   = (float)($product->stock_price ?? $product->cost_price ?? 0);
        $discount    = (float)($product->discount ?? 0);
        $finalPrice  = $discount > 0 ? $origPrice * (1 - $discount / 100) : $origPrice;
        $profit      = $finalPrice - $costPrice;
        $margin      = $costPrice > 0 ? ($profit / $costPrice) * 100 : 0;
        $qty         = (int)($product->qty ?? 0);
        $minQty      = (int)($product->min_qty ?? 0);

        $imgs = [];
        if (!empty($product->image)) $imgs[] = $product->image;
        if (!empty($product->additional_images)) {
            $extra = json_decode($product->additional_images, true);
            if (is_array($extra)) $imgs = array_merge($imgs, $extra);
        }
        $imgs = array_values(array_filter($imgs));
    @endphp

    <div class="wrap">

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('seller.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('seller.shop.seller.index') }}">Shop</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
            </ol>
        </nav>

        <div class="card product-header">
            <span class="status-badge {{ $product->status }}">
                <span class="material-symbols-outlined" style="font-size:16px">
                    {{ $product->status === 'active' ? 'check_circle' : 'block' }}
                </span>
                {{ ucfirst($product->status) }}
            </span>

            <div class="header-row">
                <div style="flex:1;min-width:240px">
                    <div class="meta-chips">
                        <span class="chip"><i class="fa-solid fa-tag"></i> {{ $product->category->name ?? 'Uncategorized' }}</span>
                        <span class="chip"><i class="fa-solid fa-barcode"></i> {{ $product->code }}</span>
                        <span class="chip"><i class="fa-regular fa-calendar"></i> {{ optional($product->created_at)->format('M d, Y') }}</span>
                    </div>
                    <h1 class="prod-title">{{ $product->name }}</h1>
                    <p class="prod-subtitle">{{ $product->short_description ?? 'No short description available.' }}</p>
                </div>

                <div class="header-actions">
                    <a href="{{ route('seller.shop.seller.index') }}" class="hdr-btn">
                        <span class="material-symbols-outlined">arrow_back</span> Back
                    </a>
                    @if(Route::has('seller.product.edit'))
                        <a href="{{ route('seller.product.edit', $product->id) }}" class="hdr-btn primary">
                            <span class="material-symbols-outlined">edit</span> Edit
                        </a>
                    @endif
                    <button class="hdr-btn" type="button" onclick="window.print()">
                        <span class="material-symbols-outlined">print</span> Print
                    </button>
                </div>
            </div>
        </div>

        <div class="main-grid">

            {{-- Gallery --}}
            <div class="card gallery">
                <div class="main-img-wrap">
                    <img id="mainImage"
                         class="main-img"
                         src="{{ count($imgs) ? asset('storage/'.$imgs[0]) : asset('images/default-product.png') }}"
                         alt="{{ $product->name }}"
                         onerror="this.onerror=null;this.src='{{ asset('images/default-product.png') }}'">

                    <div class="img-actions">
                        <button class="icon-btn" type="button" onclick="zoomImage()" title="Zoom">
                            <span class="material-symbols-outlined">zoom_in</span>
                        </button>
                        <button class="icon-btn" type="button" onclick="downloadImage()" title="Download">
                            <span class="material-symbols-outlined">download</span>
                        </button>
                    </div>
                </div>

                <div class="thumbs">
                    @forelse($imgs as $i => $img)
                        <div class="thumb {{ $i === 0 ? 'active' : '' }}"
                             data-image="{{ asset('storage/'.$img) }}"
                             onclick="changeImage(this)">
                            <img src="{{ asset('storage/'.$img) }}"
                                 alt="Image {{ $i+1 }}"
                                 onerror="this.onerror=null;this.src='{{ asset('images/default-product.png') }}'">
                        </div>
                    @empty
                        <div class="thumb active" data-image="{{ asset('images/default-product.png') }}" onclick="changeImage(this)">
                            <img src="{{ asset('images/default-product.png') }}" alt="Default">
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Right side --}}
            <div class="side">

                {{-- Pricing --}}
                <div class="card box">
                    <div class="price-header">
                        <div class="box-title">
                            <i class="fa-solid fa-money-bill-wave" style="color:var(--primary)"></i>
                            Pricing Summary
                        </div>
                        @if($discount > 0)
                            <span class="badge-off">-{{ number_format($discount,0) }}% OFF</span>
                        @endif
                    </div>

                    <div class="price-line">
                        <div class="price-big">Rs. {{ number_format($finalPrice, 2) }}</div>
                        @if($discount > 0)
                            <div class="price-strike">Rs. {{ number_format($origPrice, 2) }}</div>
                        @endif
                    </div>

                    <div class="info-grid">
                        <div class="info-mini">
                            <div class="lbl">Cost Price</div>
                            <div class="val">Rs. {{ number_format($costPrice, 2) }}</div>
                        </div>
                        <div class="info-mini">
                            <div class="lbl">Profit</div>
                            <div class="val" style="color:{{ $profit >= 0 ? 'var(--success)' : 'var(--danger)' }}">
                                {{ $profit >= 0 ? '+' : '' }}Rs. {{ number_format($profit, 2) }}
                            </div>
                        </div>
                        <div class="info-mini">
                            <div class="lbl">Profit Margin</div>
                            <div class="val" style="color:{{ $margin >= 0 ? 'var(--success)' : 'var(--danger)' }}">
                                {{ number_format($margin, 1) }}%
                            </div>
                        </div>
                        <div class="info-mini">
                            <div class="lbl">Available Stock</div>
                            <div class="val">{{ $qty }} units</div>
                        </div>
                    </div>

                    <div class="stock-note">
                        <span class="pulse-dot"></span>
                        <div>
                            @if($qty <= 0)
                                Out of Stock
                            @elseif($qty < $minQty)
                                Low Stock — Only {{ $qty }} left
                            @else
                                {{ $qty }} items available
                            @endif
                            <div style="font-size:11px;color:var(--muted);margin-top:2px">
                                Minimum Order: {{ $minQty }}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Quick Info --}}
                <div class="card box">
                    <div class="box-title">
                        <i class="fa-solid fa-circle-info" style="color:var(--secondary)"></i>
                        Quick Info
                    </div>
                    <div class="info-grid" style="margin-top:12px">
                        <div class="info-mini">
                            <div class="lbl">Brand</div>
                            <div class="val">{{ $product->brand->name ?? 'Not specified' }}</div>
                        </div>
                        <div class="info-mini">
                            <div class="lbl">Type</div>
                            <div class="val">{{ ucfirst($product->type ?? 'N/A') }}</div>
                        </div>
                        <div class="info-mini">
                            <div class="lbl">Barcode</div>
                            <div class="val" style="font-family:var(--mono);font-size:13px">{{ $product->barcode ?? 'N/A' }}</div>
                        </div>
                        <div class="info-mini">
                            <div class="lbl">Status</div>
                            <div class="val">
                                <span class="badge bg-{{ $product->status === 'active' ? 'success' : 'danger' }}">
                                    {{ ucfirst($product->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- Tabs --}}
        <div class="card tabs" style="margin-bottom:80px">
            <div class="tabs-head">
                <button class="tab-btn active" data-tab="desc">
                    <span class="material-symbols-outlined">description</span> Description
                </button>
                <button class="tab-btn" data-tab="spec">
                    <span class="material-symbols-outlined">tune</span> Specifications
                </button>
            </div>
            <div class="tab-body">
                <div class="pane active" id="desc">
                    @if($product->description)
                        <div style="line-height:1.7;color:var(--text)">{!! nl2br(e($product->description)) !!}</div>
                    @else
                        <p class="text-muted">No description available.</p>
                    @endif
                </div>

                <div class="pane" id="spec">
                    <table class="spec-table">
                        <tbody>
                        <tr><th>Product Code</th><td><span style="font-family:var(--mono)">{{ $product->code }}</span></td></tr>
                        <tr><th>Category</th><td>{{ $product->category->name ?? 'Not specified' }}</td></tr>
                        <tr><th>Brand</th><td>{{ $product->brand->name ?? 'Not specified' }}</td></tr>
                        <tr><th>Type</th><td>{{ ucfirst($product->type ?? 'N/A') }}</td></tr>
                        <tr><th>Display Price</th><td>Rs. {{ number_format($origPrice, 2) }}</td></tr>
                        <tr><th>Discount</th><td>{{ $discount > 0 ? $discount.'%' : 'None' }}</td></tr>
                        <tr><th>Final Price</th><td><strong>Rs. {{ number_format($finalPrice, 2) }}</strong></td></tr>
                        <tr><th>Cost Price</th><td>Rs. {{ number_format($costPrice, 2) }}</td></tr>
                        <tr><th>Profit</th><td style="color:{{ $profit >= 0 ? 'var(--success)' : 'var(--danger)' }}">{{ $profit >= 0 ? '+' : '' }}Rs. {{ number_format($profit, 2) }}</td></tr>
                        <tr><th>Stock</th><td>{{ $qty }} units</td></tr>
                        <tr><th>Minimum Order</th><td>{{ $minQty }} units</td></tr>
                        @if($product->barcode)
                            <tr><th>Barcode</th><td><span style="font-family:var(--mono)">{{ $product->barcode }}</span></td></tr>
                        @endif
                        <tr>
                            <th>Status</th>
                            <td><span class="badge bg-{{ $product->status === 'active' ? 'success' : 'danger' }}">{{ ucfirst($product->status) }}</span></td>
                        </tr>
                        <tr><th>Added</th><td>{{ optional($product->created_at)->format('F d, Y') }}</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    {{-- FAB --}}
    <div class="fab-wrap">
        <button class="fab" type="button" title="More actions">
            <span class="material-symbols-outlined">more_vert</span>
        </button>
        <div class="fab-menu">
            <button class="fab-item" type="button" onclick="copyLink()">
                <span class="material-symbols-outlined">link</span> Copy Link
            </button>
            <button class="fab-item" type="button" onclick="downloadInfo()">
                <span class="material-symbols-outlined">download</span> Download Info
            </button>
            @if(Route::has('seller.product.destroy'))
                <button class="fab-item danger" type="button" onclick="confirmDelete()">
                    <span class="material-symbols-outlined">delete</span> Delete
                </button>
            @endif
        </div>
    </div>
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
    <script>
        /* Tabs */
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
                document.querySelectorAll('.pane').forEach(p => p.classList.remove('active'));
                btn.classList.add('active');
                document.getElementById(btn.dataset.tab).classList.add('active');
            });
        });

        /* Gallery */
        function changeImage(el) {
            const src = el.dataset.image;
            const img = document.getElementById('mainImage');
            img.style.opacity = '0.15';
            setTimeout(() => { img.src = src; img.style.opacity = '1'; }, 140);
            document.querySelectorAll('.thumb').forEach(t => t.classList.remove('active'));
            el.classList.add('active');
        }

        function zoomImage() {
            const src = document.getElementById('mainImage').src;
            Fancybox.show([{ src, type: 'image' }]);
        }

        function downloadImage() {
            const a = Object.assign(document.createElement('a'), {
                href: document.getElementById('mainImage').src,
                download: '{{ \Illuminate\Support\Str::slug($product->name) }}.jpg'
            });
            document.body.appendChild(a); a.click(); document.body.removeChild(a);
        }

        /* FAB helpers */
        function copyLink() {
            navigator.clipboard.writeText(window.location.href)
                .then(() => alert('Link copied!'))
                .catch(() => alert('Copy failed.'));
        }

        function downloadInfo() {
            const txt = [
                'PRODUCT INFO',
                '============',
                'Name:       {{ $product->name }}',
                'Code:       {{ $product->code }}',
                'Category:   {{ $product->category->name ?? "Uncategorized" }}',
                'Brand:      {{ $product->brand->name ?? "Not specified" }}',
                '',
                'Sale Price: Rs. {{ number_format($finalPrice, 2) }}',
                'Display:    Rs. {{ number_format($origPrice, 2) }}',
                'Discount:   {{ $discount > 0 ? $discount."%" : "None" }}',
                'Cost Price: Rs. {{ number_format($costPrice, 2) }}',
                'Profit:     Rs. {{ number_format($profit, 2) }}',
                'Margin:     {{ number_format($margin, 1) }}%',
                '',
                'Stock:      {{ $qty }} units',
                'Min Order:  {{ $minQty }} units',
                '',
                'Description:',
                '{{ trim(preg_replace("/\s+/", " ", strip_tags($product->description ?? "No description"))) }}'
            ].join('\n');

            const url = URL.createObjectURL(new Blob([txt], { type:'text/plain' }));
            const a = Object.assign(document.createElement('a'), {
                href: url,
                download: '{{ \Illuminate\Support\Str::slug($product->name) }}-info.txt'
            });
            document.body.appendChild(a); a.click(); document.body.removeChild(a);
            URL.revokeObjectURL(url);
        }

        @if(Route::has('seller.product.destroy'))
        async function confirmDelete() {
            if (!confirm('Delete this product permanently?')) return;
            try {
                const res = await fetch('{{ route("seller.product.destroy", $product->id) }}', {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN':'{{ csrf_token() }}', 'Accept':'application/json' }
                });
                if (!res.ok) throw new Error();
                window.location.href = '{{ route("seller.shop.seller.index") }}';
            } catch {
                alert('Delete failed.');
            }
        }
        @endif
    </script>
@endpush
