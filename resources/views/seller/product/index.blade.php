@extends('seller.layout.app')

@push('title')
    My Products - Dashboard
@endpush

@push('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <style>
        * { margin:0; padding:0; box-sizing:border-box; }

        :root{
            --primary:#4f46e5; --primary-light:#6366f1; --primary-dark:#4338ca;
            --secondary:#06b6d4; --success:#10b981; --warning:#f59e0b; --danger:#ef4444; --info:#3b82f6;
            --gray-50:#f9fafb; --gray-100:#f3f4f6; --gray-200:#e5e7eb; --gray-300:#d1d5db; --gray-400:#9ca3af;
            --gray-500:#6b7280; --gray-600:#4b5563; --gray-700:#374151; --gray-800:#1f2937; --gray-900:#111827;
            --shadow:0 4px 6px -1px rgb(0 0 0 / 0.1); --shadow-md:0 10px 15px -3px rgb(0 0 0 / 0.1);
            --shadow-lg:0 20px 25px -5px rgb(0 0 0 / 0.1);
            --radius:.75rem; --radius-sm:.5rem; --radius-lg:1rem;
            --transition:all .25s cubic-bezier(.4,0,.2,1);
        }

        /* FULL SCREEN / FULL WIDTH */
        body{
            font-family:'Inter',system-ui,-apple-system,'Segoe UI',Roboto,sans-serif;
            background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);
            background-attachment:fixed;
            min-height:100vh;
            padding: 0.75rem;
            font-size:14px;
            color:var(--gray-900);
        }
        .container{
            max-width: 100% !important;
            width: 100%;
            margin: 0 auto;
        }

        .page-header{
            background:rgba(255,255,255,.95);
            backdrop-filter:blur(20px);
            border-radius:var(--radius-lg);
            padding:1.25rem;
            margin-bottom:1rem;
            box-shadow:var(--shadow-lg);
            border:1px solid rgba(255,255,255,.5);
        }
        .header-content{display:flex; justify-content:space-between; align-items:center; gap:1rem; flex-wrap:wrap;}
        .header-title{flex:1; min-width:220px;}
        .header-title h1{font-size:clamp(1.25rem,3vw,1.75rem); font-weight:800; color:var(--gray-900); display:flex; align-items:center; gap:.5rem;}
        .header-title h1 i{color:var(--primary);}
        .header-title p{color:var(--gray-600); font-size:.9rem; margin-top:.25rem;}
        .header-actions{display:flex; gap:.75rem; flex-wrap:wrap;}

        .btn{
            padding:.625rem 1.1rem;
            border-radius:var(--radius-sm);
            font-weight:700;
            font-size:.875rem;
            transition:var(--transition);
            display:inline-flex; align-items:center; gap:.5rem;
            border:none; cursor:pointer; text-decoration:none; white-space:nowrap;
        }
        .btn-primary{background:var(--primary); color:#fff; box-shadow:var(--shadow);}
        .btn-primary:hover{background:var(--primary-dark); transform:translateY(-2px); box-shadow:var(--shadow-md);}
        .btn-secondary{background:#fff; color:var(--gray-700); border:2px solid var(--gray-200);}
        .btn-secondary:hover{background:var(--gray-50); border-color:var(--primary); color:var(--primary);}

        .stats-grid{display:grid; grid-template-columns:repeat(auto-fit,minmax(240px,1fr)); gap:1rem; margin-bottom:1rem;}
        .stat-card{background:#fff; border-radius:var(--radius); padding:1.25rem; box-shadow:var(--shadow); transition:var(--transition); border-left:4px solid; position:relative; overflow:hidden;}
        .stat-card:hover{transform:translateY(-4px); box-shadow:var(--shadow-lg);}
        .stat-card.primary{border-left-color:var(--primary);}
        .stat-card.success{border-left-color:var(--success);}
        .stat-card.warning{border-left-color:var(--warning);}
        .stat-card.danger{border-left-color:var(--danger);}
        .stat-header{display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:.75rem;}
        .stat-icon{width:48px; height:48px; border-radius:var(--radius-sm); display:flex; align-items:center; justify-content:center; font-size:1.35rem; color:#fff;}
        .stat-card.primary .stat-icon{background:linear-gradient(135deg,var(--primary),var(--primary-light));}
        .stat-card.success .stat-icon{background:linear-gradient(135deg,var(--success),#059669);}
        .stat-card.warning .stat-icon{background:linear-gradient(135deg,var(--warning),#d97706);}
        .stat-card.danger .stat-icon{background:linear-gradient(135deg,var(--danger),#dc2626);}
        .stat-value{font-size:2rem; font-weight:900; color:var(--gray-900); line-height:1;}
        .stat-label{color:var(--gray-600); font-size:.8rem; font-weight:700; text-transform:uppercase; letter-spacing:.6px; margin:.5rem 0 .75rem;}
        .stat-trend{display:inline-flex; align-items:center; gap:.35rem; padding:.25rem .6rem; border-radius:1rem; font-size:.75rem; font-weight:800;}
        .stat-trend.up{background:rgba(16,185,129,.1); color:var(--success);}
        .stat-trend.down{background:rgba(239,68,68,.1); color:var(--danger);}
        .stat-trend.neutral{background:rgba(59,130,246,.1); color:var(--info);}

        .filters-card{background:#fff; border-radius:var(--radius); padding:1.25rem; margin-bottom:1rem; box-shadow:var(--shadow);}
        .filters-header{display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem; flex-wrap:wrap; gap:1rem;}
        .filters-title{font-size:1.05rem; font-weight:900; color:var(--gray-900); display:flex; align-items:center; gap:.5rem;}
        .filters-title i{color:var(--primary);}
        .filter-actions{display:flex; gap:.75rem; flex-wrap:wrap;}
        .filters-grid{display:grid; grid-template-columns:repeat(auto-fit,minmax(200px,1fr)); gap:1rem; margin-bottom:1rem;}
        .filter-group label{display:block; font-size:.75rem; font-weight:800; color:var(--gray-700); margin-bottom:.45rem; text-transform:uppercase; letter-spacing:.5px;}
        .filter-select{width:100%; padding:.625rem .875rem; border:2px solid var(--gray-200); border-radius:var(--radius-sm); font-size:.875rem;}
        .filter-select:focus{outline:none; border-color:var(--primary); box-shadow:0 0 0 3px rgba(79,70,229,.1);}

        .quick-filters{display:flex; gap:.6rem; flex-wrap:wrap; padding-top:1rem; border-top:1px solid var(--gray-200); align-items:center;}
        .quick-filter-label{font-size:.75rem; font-weight:900; color:var(--gray-700); text-transform:uppercase; letter-spacing:.5px;}
        .quick-filter-btn{padding:.5rem 1rem; border:2px solid var(--gray-200); background:#fff; border-radius:1.5rem; font-size:.8rem; font-weight:800; color:var(--gray-700); cursor:pointer; transition:var(--transition); display:inline-flex; align-items:center; gap:.375rem;}
        .quick-filter-btn:hover{border-color:var(--primary); color:var(--primary); background:rgba(79,70,229,.05);}
        .quick-filter-btn.active{background:var(--primary); color:#fff; border-color:var(--primary);}

        .table-card{background:#fff; border-radius:var(--radius); overflow:hidden; box-shadow:var(--shadow);}
        .table-card-header{background:var(--gray-50); padding:1rem 1.25rem; border-bottom:1px solid var(--gray-200);}
        .table-card-title{display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem;}
        .table-title-text h2{font-size:1.1rem; font-weight:900; color:var(--gray-900); display:flex; align-items:center; gap:.5rem;}
        .table-title-text h2 i{color:var(--primary);}
        .table-title-text p{color:var(--gray-600); font-size:.82rem; margin-top:.15rem;}

        /* ✅ UPDATED TOOLBAR: buttons fit content nicely */
        .table-card-actions{
            display:flex;
            align-items:center;
            justify-content:flex-end;
            gap:.55rem;
            flex-wrap:wrap;
        }

        /* ✅ UPDATED BUTTON: auto width (text-based), same height, neat */
        .btn-icon{
            padding:.58rem .9rem;
            border:2px solid var(--gray-200);
            background:#fff;
            border-radius:var(--radius-sm);
            font-size:.85rem;
            font-weight:900;
            color:var(--gray-700);
            cursor:pointer;
            transition:var(--transition);
            display:inline-flex;
            align-items:center;
            gap:.45rem;
            line-height:1;
            width:auto;              /* ✅ fit to text */
            min-width:unset;         /* ✅ no forced width */
            white-space:nowrap;      /* ✅ keep label one line */
        }
        .btn-icon i{font-size:.95rem;}
        .btn-icon:hover{background:var(--primary); color:#fff; border-color:var(--primary); transform:translateY(-1px); box-shadow:var(--shadow);}

        /* BULK BAR */
        .bulk-actions-bar{
            background:linear-gradient(135deg,#fef3c7,#fed7aa);
            padding:.9rem 1.25rem;
            border-bottom:1px solid var(--gray-200);
            display:none;
            align-items:center;
            gap:1rem;
            flex-wrap:wrap;
        }
        .bulk-actions-bar.active{display:flex;}
        .bulk-info{font-weight:900; font-size:.9rem; color:var(--gray-800); display:flex; align-items:center; gap:.5rem;}
        .bulk-info i{color:var(--warning);}
        .bulk-actions{display:flex; gap:.5rem; flex-wrap:wrap;}
        .bulk-btn{
            padding:.5rem 1rem;
            border:none;
            border-radius:var(--radius-sm);
            font-weight:900;
            font-size:.82rem;
            cursor:pointer;
            transition:var(--transition);
            display:inline-flex;
            align-items:center;
            gap:.35rem;
            color:#fff;
        }
        .bulk-btn:hover{transform:translateY(-2px); box-shadow:var(--shadow);}
        .bulk-btn.activate{background:var(--success);}
        .bulk-btn.deactivate{background:var(--warning);}
        .bulk-btn.delete{background:var(--danger);}

        /* DATATABLE WRAPPER */
        .dataTables_wrapper{padding:1rem 1.25rem;}
        .dataTables_filter{margin-bottom:.75rem;}
        .dataTables_filter label{display:flex; align-items:center; gap:.5rem; font-weight:900; color:var(--gray-700);}
        .dataTables_filter input{
            padding:.6rem 1rem;
            border:2px solid var(--gray-200);
            border-radius:var(--radius-sm);
            font-size:.9rem;
            transition:var(--transition);
            width:min(380px, 100%);
        }
        .dataTables_filter input:focus{outline:none; border-color:var(--primary); box-shadow:0 0 0 3px rgba(79,70,229,.1);}

        /* TABLE FIXES */
        .table-responsive{
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        #product-table{width:100% !important; border-collapse:separate; border-spacing:0; min-width: 980px;}
        #product-table thead th{
            background:linear-gradient(135deg,var(--gray-50),#fff);
            font-weight:900;
            color:var(--gray-900);
            padding:.95rem .7rem;
            text-transform:uppercase;
            font-size:.73rem;
            letter-spacing:.6px;
            border-bottom:2px solid var(--primary);
            white-space:nowrap;
        }
        #product-table tbody td{
            padding:.9rem .7rem;
            vertical-align:middle;
            border-bottom:1px solid var(--gray-100);
            font-size:.875rem;
            white-space:nowrap;
        }
        #product-table tbody tr{transition:var(--transition);}
        #product-table tbody tr:hover{background:rgba(79,70,229,.02);}

        /* PRODUCT CELL */
        .product-cell{display:flex; align-items:center; gap:.875rem; min-width: 320px;}
        .product-image{width:56px; height:56px; border-radius:var(--radius-sm); overflow:hidden; border:2px solid var(--gray-200); flex-shrink:0; transition:var(--transition);}
        .product-image:hover{transform:scale(1.05); border-color:var(--primary); box-shadow:var(--shadow);}
        .product-image img{width:100%; height:100%; object-fit:cover;}
        .product-info{min-width:0;}
        .product-name{font-size:.9rem; font-weight:900; color:var(--gray-900); margin-bottom:.2rem; overflow:hidden; text-overflow:ellipsis; max-width: 420px;}
        .product-desc{color:var(--gray-600); font-size:.75rem; overflow:hidden; text-overflow:ellipsis; max-width: 420px;}

        /* BADGES */
        .badge{display:inline-flex; align-items:center; padding:.35rem .7rem; border-radius:.375rem; font-size:.75rem; font-weight:900; gap:.35rem; white-space:nowrap;}
        .badge-code{background:var(--gray-100); color:var(--gray-700); font-family:ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace; font-size:.7rem;}
        .badge-category{background:rgba(79,70,229,.1); color:var(--primary);}
        .badge-brand{background:rgba(6,182,212,.1); color:var(--secondary);}
        .badge-na{background:var(--gray-100); color:var(--gray-500); font-style:italic;}

        /* PRICE */
        .price-wrapper{display:flex; flex-direction:column; gap:.2rem;}
        .price-current{font-size:1rem; font-weight:900; color:var(--gray-900);}
        .price-old{display:flex; align-items:center; gap:.5rem;}
        .price-original{font-size:.8rem; color:var(--gray-500); text-decoration:line-through;}
        .badge-discount{background:linear-gradient(135deg,var(--danger),#dc2626); color:#fff; padding:.22rem .5rem; border-radius:.25rem; font-size:.7rem; font-weight:900;}

        /* STOCK */
        .stock-wrapper{display:flex; flex-direction:column; gap:.4rem;}
        .stock-badge{padding:.35rem .7rem; border-radius:.375rem; font-size:.75rem; font-weight:900; display:inline-flex; align-items:center; gap:.35rem; width:fit-content; text-transform:uppercase; letter-spacing:.3px;}
        .stock-badge.in-stock{background:rgba(16,185,129,.1); color:var(--success);}
        .stock-badge.low-stock{background:rgba(245,158,11,.1); color:var(--warning);}
        .stock-badge.out-stock{background:rgba(239,68,68,.1); color:var(--danger);}
        .stock-count{font-size:.75rem; color:var(--gray-600); font-weight:700;}

        /* STATUS */
        .status-badge{padding:.35rem .85rem; border-radius:1.5rem; font-size:.75rem; font-weight:900; display:inline-flex; align-items:center; gap:.5rem; text-transform:uppercase; letter-spacing:.3px;}
        .status-badge.active{background:rgba(16,185,129,.1); color:var(--success);}
        .status-badge.inactive{background:rgba(239,68,68,.1); color:var(--danger);}
        .status-dot{width:8px; height:8px; border-radius:50%; animation:pulse 2s ease-in-out infinite;}
        .status-badge.active .status-dot{background:var(--success);}
        .status-badge.inactive .status-dot{background:var(--danger);}
        @keyframes pulse{0%,100%{opacity:1}50%{opacity:.5}}

        /* ACTION BUTTONS - ALWAYS VISIBLE */
        .action-buttons{display:flex; gap:.4rem; justify-content:center; align-items:center; flex-wrap:nowrap;}
        .action-btn{
            width:34px; height:34px;
            border-radius:8px;
            border:none;
            color:#fff;
            cursor:pointer;
            transition:var(--transition);
            display:inline-flex; align-items:center; justify-content:center;
            font-size:.8rem;
            flex-shrink:0;
            text-decoration:none;
            line-height:1;
        }
        .action-btn:hover{transform:translateY(-2px); box-shadow:var(--shadow-md); color:#fff;}
        .action-btn.view{background:var(--info);}
        .action-btn.view:hover{background:#2563eb;}
        .action-btn.edit{background:var(--warning);}
        .action-btn.edit:hover{background:#d97706;}
        .action-btn.delete{background:var(--danger);}
        .action-btn.delete:hover{background:#dc2626;}

        /* CHECKBOX: hidden until bulk mode ON */
        .custom-checkbox{width:18px; height:18px; cursor:pointer; accent-color:var(--primary);}
        #selectAll, .row-checkbox{display:none;}

        /* LOADING */
        .loading-overlay{position:fixed; inset:0; background:rgba(0,0,0,.7); backdrop-filter:blur(5px); display:none; justify-content:center; align-items:center; z-index:9999;}
        .loading-overlay.active{display:flex;}
        .spinner{width:50px; height:50px; border:4px solid rgba(255,255,255,.3); border-top-color:#fff; border-radius:50%; animation:spin 1s linear infinite;}
        @keyframes spin{to{transform:rotate(360deg)}}

        /* RESPONSIVE */
        .mobile-toggle{display:none;}
        @media(max-width:768px){
            body{padding:.5rem;}
            .header-actions{flex-direction:column; width:100%;}
            .btn{width:100%; justify-content:center;}
            .mobile-toggle{display:block;}
            .filters-grid{display:none; grid-template-columns:1fr;}
            .filters-grid.show{display:grid;}
            .dataTables_wrapper{padding:1rem;}
            .table-card-actions{justify-content:flex-start;}
        }
        /* ✅ Tiny screens: keep toolbar neat */
        @media(max-width:420px){
            .btn-icon{padding:.55rem .75rem; font-size:.82rem;}
        }

        /* SCROLLBAR */
        ::-webkit-scrollbar{width:10px; height:10px;}
        ::-webkit-scrollbar-track{background:var(--gray-100);}
        ::-webkit-scrollbar-thumb{background:var(--primary); border-radius:5px;}
        ::-webkit-scrollbar-thumb:hover{background:var(--primary-dark);}

        /* SELECT2 */
        .select2-container--classic .select2-selection--single{border:2px solid var(--gray-200); border-radius:var(--radius-sm); height:auto;}
        .select2-container--classic .select2-selection--single .select2-selection__rendered{padding:.625rem .875rem;}
    </style>
@endpush

@section('content')
    <div class="container">
        <div class="loading-overlay" id="loadingOverlay"><div class="spinner"></div></div>

        <div class="page-header" data-aos="fade-down">
            <div class="header-content">
                <div class="header-title">
                    <h1><i class="fas fa-cube"></i> Product Management</h1>
                    <p>Manage your complete product inventory</p>
                </div>
                <div class="header-actions">
                    <a href="{{ route('seller.product.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus-circle"></i> Add Product
                    </a>
                    <button class="btn btn-secondary" onclick="toggleBulkMode()">
                        <i class="fas fa-tasks"></i> Bulk Actions
                    </button>
                </div>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card primary" data-aos="fade-up">
                <div class="stat-header">
                    <div class="stat-icon"><i class="fas fa-boxes"></i></div>
                </div>
                <div class="stat-body">
                    <div class="stat-value">{{ $products->count() }}</div>
                    <div class="stat-label">Total Products</div>
                    <span class="stat-trend neutral"><i class="fas fa-globe"></i> All Items</span>
                </div>
            </div>

            <div class="stat-card success" data-aos="fade-up" data-aos-delay="100">
                <div class="stat-header">
                    <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                </div>
                <div class="stat-body">
                    <div class="stat-value">{{ $products->where('status', 'active')->count() }}</div>
                    <div class="stat-label">Active Products</div>
                    <span class="stat-trend up">
                        <i class="fas fa-arrow-up"></i>
                        {{ $products->count() > 0 ? number_format(($products->where('status', 'active')->count() / $products->count()) * 100, 1) : 0 }}%
                    </span>
                </div>
            </div>

            <div class="stat-card warning" data-aos="fade-up" data-aos-delay="200">
                <div class="stat-header">
                    <div class="stat-icon"><i class="fas fa-exclamation-triangle"></i></div>
                </div>
                @php
                    $lowStockCount = $products->filter(function($product) {
                        return $product->qty <= $product->min_qty && $product->qty > 0;
                    })->count();
                @endphp
                <div class="stat-body">
                    <div class="stat-value">{{ $lowStockCount }}</div>
                    <div class="stat-label">Low Stock Alerts</div>
                    <span class="stat-trend {{ $lowStockCount > 0 ? 'down' : 'up' }}">
                        <i class="fas fa-{{ $lowStockCount > 0 ? 'exclamation' : 'check' }}-circle"></i>
                        {{ $lowStockCount > 0 ? 'Attention' : 'All Good' }}
                    </span>
                </div>
            </div>

            <div class="stat-card danger" data-aos="fade-up" data-aos-delay="300">
                <div class="stat-header">
                    <div class="stat-icon"><i class="fas fa-ban"></i></div>
                </div>
                <div class="stat-body">
                    <div class="stat-value">{{ $products->where('qty', 0)->count() }}</div>
                    <div class="stat-label">Out of Stock</div>
                    <span class="stat-trend down"><i class="fas fa-arrow-down"></i> Restock</span>
                </div>
            </div>
        </div>

        <div class="filters-card" data-aos="fade-up">
            <div class="filters-header">
                <h3 class="filters-title"><i class="fas fa-filter"></i> Filters</h3>
                <div class="filter-actions">
                    <button class="btn btn-secondary mobile-toggle" onclick="toggleFilters()">
                        <i class="fas fa-sliders-h"></i> Toggle
                    </button>
                    <button class="btn btn-secondary" onclick="resetFilters()">
                        <i class="fas fa-redo"></i> Reset
                    </button>
                </div>
            </div>

            <div class="filters-grid" id="filtersGrid">
                <div class="filter-group">
                    <label for="filterCategory"><i class="fas fa-tag"></i> Category</label>
                    <select id="filterCategory" class="filter-select">
                        <option value="">All Categories</option>
                        @foreach($products->pluck('category')->unique('id')->filter() as $category)
                            <option value="{{ $category->name }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-group">
                    <label for="filterBrand"><i class="fas fa-copyright"></i> Brand</label>
                    <select id="filterBrand" class="filter-select">
                        <option value="">All Brands</option>
                        @foreach($products->pluck('brand')->unique('id')->filter() as $brand)
                            <option value="{{ $brand->name }}">{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-group">
                    <label for="filterStatus"><i class="fas fa-toggle-on"></i> Status</label>
                    <select id="filterStatus" class="filter-select">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="filterStock"><i class="fas fa-warehouse"></i> Stock</label>
                    <select id="filterStock" class="filter-select">
                        <option value="">All Stock</option>
                        <option value="in">In Stock</option>
                        <option value="low">Low Stock</option>
                        <option value="out">Out of Stock</option>
                    </select>
                </div>
            </div>

            <div class="quick-filters">
                <span class="quick-filter-label">Quick:</span>
                <button class="quick-filter-btn active" data-filter="all"><i class="fas fa-globe"></i> All</button>
                <button class="quick-filter-btn" data-filter="active"><i class="fas fa-check-circle"></i> Active</button>
                <button class="quick-filter-btn" data-filter="low-stock"><i class="fas fa-exclamation-triangle"></i> Low Stock</button>
                <button class="quick-filter-btn" data-filter="discounted"><i class="fas fa-percent"></i> Discounted</button>
                <button class="quick-filter-btn" data-filter="out-stock"><i class="fas fa-ban"></i> Out of Stock</button>
            </div>
        </div>

        <div class="table-card" data-aos="fade-up">
            <div class="table-card-header">
                <div class="table-card-title">
                    <div class="table-title-text">
                        <h2><i class="fas fa-table"></i> Product Inventory</h2>
                        <p>Complete product catalog overview</p>
                    </div>
                    <div class="table-card-actions">
                        <button class="btn-icon" onclick="exportExcel()"><i class="fas fa-file-excel"></i> Excel</button>
                        <button class="btn-icon" onclick="exportPDF()"><i class="fas fa-file-pdf"></i> PDF</button>
                        <button class="btn-icon" onclick="printTable()"><i class="fas fa-print"></i> Print</button>
                        <button class="btn-icon" onclick="location.reload()"><i class="fas fa-sync-alt"></i> Refresh</button>
                    </div>
                </div>
            </div>

            <div class="bulk-actions-bar" id="bulkActionsBar">
                <div class="bulk-info">
                    <i class="fas fa-check-square"></i>
                    <span id="selectedCount">0</span> selected
                </div>
                <div class="bulk-actions">
                    <button class="bulk-btn activate" onclick="bulkActivate()"><i class="fas fa-check-circle"></i> Activate</button>
                    <button class="bulk-btn deactivate" onclick="bulkDeactivate()"><i class="fas fa-ban"></i> Deactivate</button>
                    <button class="bulk-btn delete" onclick="bulkDelete()"><i class="fas fa-trash-alt"></i> Delete</button>
                </div>
            </div>

            <div class="table-responsive">
                <table id="product-table" class="display nowrap">
                    <thead>
                    <tr>
                        <th style="width:40px;">
                            <input type="checkbox" class="custom-checkbox" id="selectAll">
                        </th>
                        <th><i class="fas fa-box"></i> Product</th>
                        <th><i class="fas fa-barcode"></i> Code</th>
                        <th><i class="fas fa-tag"></i> Category</th>
                        <th><i class="fas fa-copyright"></i> Brand</th>
                        <th><i class="fas fa-dollar-sign"></i> Price</th>
                        <th><i class="fas fa-warehouse"></i> Stock</th>
                        <th><i class="fas fa-toggle-on"></i> Status</th>
                        <th style="width:140px;"><i class="fas fa-cog"></i> Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($products as $product)
                        @php
                            $categoryName = $product->category->name ?? 'N/A';
                            $brandName    = $product->brand->name ?? 'N/A';
                            $stockStatus  = $product->qty == 0 ? 'out-stock' : ($product->qty <= $product->min_qty ? 'low-stock' : 'in-stock');
                            $stockText    = $product->qty == 0 ? 'Out' : ($product->qty <= $product->min_qty ? 'Low' : 'In Stock');
                            $stockIcon    = $product->qty == 0 ? 'fa-times-circle' : ($product->qty <= $product->min_qty ? 'fa-exclamation-circle' : 'fa-check-circle');
                        @endphp
                        <tr data-id="{{ $product->id }}"
                            data-status="{{ $product->status }}"
                            data-stock="{{ $stockStatus }}"
                            data-category="{{ $categoryName }}"
                            data-brand="{{ $brandName }}"
                            data-discount="{{ $product->hasDiscount() ? 'yes' : 'no' }}">
                            <td>
                                <input type="checkbox" class="custom-checkbox row-checkbox" value="{{ $product->id }}">
                            </td>
                            <td>
                                <div class="product-cell">
                                    <div class="product-image">
                                        <img src="{{ $product->image_url }}"
                                             alt="{{ $product->name }}"
                                             onerror="this.src='{{ asset('images/default-product.png') }}'">
                                    </div>
                                    <div class="product-info">
                                        <div class="product-name">{{ Str::limit($product->name, 40) }}</div>
                                        <div class="product-desc">{{ Str::limit($product->description, 50) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge badge-code">{{ $product->code }}</span></td>

                            <td>
                                @if($categoryName !== 'N/A')
                                    <span class="badge badge-category"><i class="fas fa-tag"></i> {{ $categoryName }}</span>
                                @else
                                    <span class="badge badge-na"><i class="fas fa-minus"></i> N/A</span>
                                @endif
                            </td>

                            <td>
                                @if($brandName !== 'N/A')
                                    <span class="badge badge-brand"><i class="fas fa-copyright"></i> {{ $brandName }}</span>
                                @else
                                    <span class="badge badge-na"><i class="fas fa-minus"></i> N/A</span>
                                @endif
                            </td>

                            <td>
                                <div class="price-wrapper">
                                    <div class="price-current">Rs. {{ number_format($product->final_price, 2) }}</div>
                                    @if($product->hasDiscount())
                                        <div class="price-old">
                                            <span class="price-original">Rs. {{ number_format($product->display_price, 2) }}</span>
                                            <span class="badge-discount">-{{ $product->discount }}%</span>
                                        </div>
                                    @endif
                                </div>
                            </td>

                            <td>
                                <div class="stock-wrapper">
                                    <span class="stock-badge {{ $stockStatus }}">
                                        <i class="fas {{ $stockIcon }}"></i> {{ $stockText }}
                                    </span>
                                    <span class="stock-count"><i class="fas fa-cube"></i> {{ $product->qty }} units</span>
                                </div>
                            </td>

                            <td>
                                <span class="status-badge {{ $product->status }}">
                                    <span class="status-dot"></span>
                                    {{ ucfirst($product->status) }}
                                </span>
                            </td>

                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('seller.product.show', $product->id) }}" class="action-btn view" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('seller.product.edit', $product->id) }}" class="action-btn edit" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button class="action-btn delete"
                                            onclick="deleteProduct({{ $product->id }}, '{{ addslashes($product->name) }}')"
                                            title="Delete">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="white-space:normal;">
                                <div style="text-align:center; padding:3rem 1rem;">
                                    <div style="font-size:3.5rem; color:var(--gray-300); margin-bottom:1rem;">
                                        <i class="fas fa-box-open"></i>
                                    </div>
                                    <h3 style="font-size:1.35rem; font-weight:900; color:var(--gray-900); margin-bottom:.5rem;">
                                        No Products Found
                                    </h3>
                                    <p style="color:var(--gray-600); margin-bottom:1.25rem;">
                                        Start building your inventory by adding your first product
                                    </p>
                                    <a href="{{ route('seller.product.create') }}" class="btn btn-primary" style="width:auto;">
                                        <i class="fas fa-plus-circle"></i> Add Your First Product
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        AOS.init({ duration: 600, once: true, easing: 'ease-in-out' });

        toastr.options = {
            closeButton: true, progressBar: true,
            positionClass: 'toast-top-right',
            timeOut: 2500, extendedTimeOut: 900,
            showMethod: 'fadeIn', hideMethod: 'fadeOut'
        };

        let dataTable = null;
        let selectedProducts = [];
        let bulkModeActive = false;

        // Keep reference to base filter so quick-filters won’t break it
        let baseFilterFn = null;

        $(document).ready(function () {
            initializeDataTable();
            initializeFilters();
            initializeSelect2();
            initializeBulkActions();
        });

        function initializeDataTable() {
            @if($products->count() > 0)
                dataTable = $('#product-table').DataTable({
                responsive: { details: false },
                scrollX: true,
                autoWidth: false,
                pageLength: 10,
                order: [[1, 'asc']],
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search products...",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ products",
                    infoEmpty: "No products found",
                    infoFiltered: "(filtered from _MAX_ total products)",
                    paginate: { next: "Next →", previous: "← Previous" },
                    zeroRecords: "No matching products found"
                },
                dom: 'Blfrtip',
                buttons: [
                    { extend: 'excel', text: '<i class="fas fa-file-excel"></i> Excel', className: 'd-none buttons-excel', exportOptions: { columns: [1,2,3,4,5,6,7] } },
                    { extend: 'pdf',   text: '<i class="fas fa-file-pdf"></i> PDF',   className: 'd-none buttons-pdf',   exportOptions: { columns: [1,2,3,4,5,6,7] } },
                    { extend: 'print', text: '<i class="fas fa-print"></i> Print', className: 'd-none buttons-print', exportOptions: { columns: [1,2,3,4,5,6,7] } }
                ],
                columnDefs: [
                    { orderable: false, targets: [0, 8] },
                    { responsivePriority: 1, targets: 8 },
                    { responsivePriority: 2, targets: 1 },
                    { responsivePriority: 3, targets: 5 },
                    { width: "40px", targets: 0 },
                    { width: "140px", targets: 8 },
                ],
                drawCallback: function () {
                    updateSelectedProducts();
                }
            });

            // Base filter (saved)
            baseFilterFn = function (settings, data, dataIndex) {
                const row = dataTable.row(dataIndex).node();

                const categoryFilter = $('#filterCategory').val();
                const category = $(row).data('category');
                if (categoryFilter && category !== categoryFilter) return false;

                const brandFilter = $('#filterBrand').val();
                const brand = $(row).data('brand');
                if (brandFilter && brand !== brandFilter) return false;

                const statusFilter = $('#filterStatus').val();
                const status = $(row).data('status');
                if (statusFilter && status !== statusFilter) return false;

                const stockFilter = $('#filterStock').val();
                const stock = $(row).data('stock');
                if (stockFilter) {
                    if (stockFilter === 'in' && stock !== 'in-stock') return false;
                    if (stockFilter === 'low' && stock !== 'low-stock') return false;
                    if (stockFilter === 'out' && stock !== 'out-stock') return false;
                }
                return true;
            };

            $.fn.dataTable.ext.search.push(baseFilterFn);
            @endif
        }

        function initializeSelect2() {
            $('.filter-select').select2({ theme: 'classic', width: '100%', minimumResultsForSearch: 5 });
        }

        function initializeFilters() {
            $('#filterCategory, #filterBrand, #filterStatus, #filterStock').on('change', function () {
                if (!dataTable) return;
                dataTable.draw();
            });

            $('.quick-filter-btn').on('click', function () {
                $('.quick-filter-btn').removeClass('active');
                $(this).addClass('active');

                const filter = $(this).data('filter');

                $('#filterCategory, #filterBrand, #filterStatus, #filterStock').val('').trigger('change.select2');

                $.fn.dataTable.ext.search = [];
                if (baseFilterFn) $.fn.dataTable.ext.search.push(baseFilterFn);

                if (filter === 'active') {
                    $('#filterStatus').val('active').trigger('change');
                } else if (filter === 'low-stock') {
                    $('#filterStock').val('low').trigger('change');
                } else if (filter === 'out-stock') {
                    $('#filterStock').val('out').trigger('change');
                } else if (filter === 'discounted') {
                    $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
                        const row = dataTable.row(dataIndex).node();
                        return $(row).data('discount') === 'yes';
                    });
                }

                if (dataTable) dataTable.draw();
            });
        }

        function initializeBulkActions() {
            $('#selectAll').on('change', function () {
                const isChecked = $(this).prop('checked');
                $('.row-checkbox:visible').prop('checked', isChecked);
                updateSelectedProducts();
            });

            $(document).on('change', '.row-checkbox', function () {
                updateSelectedProducts();
            });
        }

        function toggleFilters() {
            $('#filtersGrid').toggleClass('show');
        }

        function resetFilters() {
            $('#filterCategory, #filterBrand, #filterStatus, #filterStock').val('').trigger('change');
            $('.quick-filter-btn').removeClass('active');
            $('.quick-filter-btn[data-filter="all"]').addClass('active');

            $.fn.dataTable.ext.search = [];
            if (baseFilterFn) $.fn.dataTable.ext.search.push(baseFilterFn);

            if (dataTable) dataTable.draw();
            toastr.success('All filters reset');
        }

        function toggleBulkMode() {
            bulkModeActive = !bulkModeActive;

            if (bulkModeActive) {
                $('#selectAll, .row-checkbox').show();
                toastr.info('Bulk mode activated');
            } else {
                $('#selectAll').prop('checked', false).hide();
                $('.row-checkbox').prop('checked', false).hide();
                $('#bulkActionsBar').removeClass('active');
                selectedProducts = [];
                $('#selectedCount').text('0');
                toastr.info('Bulk mode deactivated');
            }
        }

        function updateSelectedProducts() {
            if (!bulkModeActive) return;

            selectedProducts = $('.row-checkbox:checked').map(function () {
                return $(this).val();
            }).get();

            $('#selectedCount').text(selectedProducts.length);

            if (selectedProducts.length > 0) $('#bulkActionsBar').addClass('active');
            else $('#bulkActionsBar').removeClass('active');

            const totalVisible = $('.row-checkbox:visible').length;
            const totalChecked = $('.row-checkbox:visible:checked').length;
            $('#selectAll').prop('checked', totalVisible > 0 && totalVisible === totalChecked);
        }

        function bulkActivate() {
            if (selectedProducts.length === 0) return toastr.warning('Please select products first');
            Swal.fire({
                title: 'Activate Products?',
                html: `You are about to activate <strong>${selectedProducts.length}</strong> product(s).`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, Activate!',
                cancelButtonText: 'Cancel'
            }).then((result) => { if (result.isConfirmed) performBulkAction('activate'); });
        }

        function bulkDeactivate() {
            if (selectedProducts.length === 0) return toastr.warning('Please select products first');
            Swal.fire({
                title: 'Deactivate Products?',
                html: `You are about to deactivate <strong>${selectedProducts.length}</strong> product(s).`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f59e0b',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, Deactivate!',
                cancelButtonText: 'Cancel'
            }).then((result) => { if (result.isConfirmed) performBulkAction('deactivate'); });
        }

        function bulkDelete() {
            if (selectedProducts.length === 0) return toastr.warning('Please select products first');
            Swal.fire({
                title: 'Delete Products?',
                html: `You are about to permanently delete <strong>${selectedProducts.length}</strong> product(s).<br><small class="text-danger">This action cannot be undone!</small>`,
                icon: 'error',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, Delete!',
                cancelButtonText: 'Cancel'
            }).then((result) => { if (result.isConfirmed) performBulkAction('delete'); });
        }

        function performBulkAction(action) {
            showLoading();
            setTimeout(() => {
                hideLoading();
                Swal.fire({
                    title: 'Success!',
                    html: `Successfully ${action}d <strong>${selectedProducts.length}</strong> product(s)`,
                    icon: 'success',
                    timer: 1700,
                    showConfirmButton: false
                });
                setTimeout(() => location.reload(), 1700);
            }, 900);
        }

        function deleteProduct(productId, productName) {
            Swal.fire({
                title: 'Delete Product?',
                html: `Are you sure you want to delete "<strong>${productName}</strong>"?<br><small class="text-danger">This action cannot be undone!</small>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, Delete!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (!result.isConfirmed) return;

                showLoading();
                $.ajax({
                    url: `/seller/product/${productId}`,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function (response) {
                        hideLoading();
                        toastr.success(response.message || 'Product deleted successfully');
                        setTimeout(() => location.reload(), 800);
                    },
                    error: function (xhr) {
                        hideLoading();
                        toastr.error(xhr.responseJSON?.message || 'Failed to delete product');
                    }
                });
            });
        }

        function exportExcel() {
            if (!dataTable) return;
            toastr.info('Preparing Excel export...');
            showLoading();
            setTimeout(() => {
                hideLoading();
                $('.buttons-excel').click();
            }, 450);
        }

        function exportPDF() {
            if (!dataTable) return;
            toastr.info('Preparing PDF export...');
            showLoading();
            setTimeout(() => {
                hideLoading();
                $('.buttons-pdf').click();
            }, 450);
        }

        function printTable() {
            if (!dataTable) return;
            $('.buttons-print').click();
        }

        function showLoading() { $('#loadingOverlay').addClass('active'); }
        function hideLoading() { $('#loadingOverlay').removeClass('active'); }

        $(window).on('resize', function () {
            if (dataTable) dataTable.columns.adjust().draw(false);
        });
    </script>
@endpush
