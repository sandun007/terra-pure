@extends('seller.layout.app')

@push('title')
    Create New Order
@endpush

@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        :root {
            --primary: #4f46e5; --primary-hover: #4338ca; --primary-light: #eef2ff;
            --success: #10b981; --danger: #ef4444; --warning: #f59e0b;
            --dark: #0f172a; --mid: #64748b; --border: #e2e8f0;
            --surface: #ffffff; --bg: #f8fafc;
            --radius-xl: 16px; --radius-lg: 12px; --radius-md: 8px;
        }
        body { font-family: 'DM Sans', sans-serif; background: var(--bg); }

        .order-wrapper { padding: 32px; max-width: 1400px; margin: 0 auto; }
        @media (max-width: 768px) { .order-wrapper { padding: 16px; } }

        .page-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:28px; flex-wrap:wrap; gap:16px; }
        .page-title  { font-size:26px; font-weight:800; color:var(--dark); display:flex; align-items:center; gap:10px; margin:0; }
        .header-actions { display:flex; gap:12px; flex-wrap:wrap; }

        .tab-navigation {
            display: flex;
            gap: 10px;
            margin-bottom: 24px;
            border-bottom: 2px solid var(--border);
            padding-bottom: 10px;
        }
        .tab-button {
            padding: 12px 24px;
            border: none;
            background: var(--surface);
            border-radius: 30px;
            font-weight: 600;
            color: var(--mid);
            cursor: pointer;
            transition: all .2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .tab-button.active {
            background: var(--primary);
            color: white;
            box-shadow: 0 4px 12px rgba(79,70,229,.3);
        }
        .tab-button i { font-size: 18px; }

        .tab-content { display: none; }
        .tab-content.active { display: block; }

        .form-container { background:var(--surface); border:1px solid var(--border); border-radius:var(--radius-xl); padding:32px; box-shadow:0 2px 8px rgba(0,0,0,.07); }

        .form-grid { display:grid; grid-template-columns:1fr 1fr; gap:24px; margin-bottom:24px; }
        @media (max-width: 768px) { .form-grid { grid-template-columns:1fr; } }

        .form-group  { margin-bottom:0; }
        .form-label  { display:block; font-size:14px; font-weight:600; color:var(--dark); margin-bottom:8px; }
        .form-label .required { color:var(--danger); margin-left:2px; }

        .form-control { width:100%; padding:12px 16px; border:2px solid var(--border); border-radius:var(--radius-md); font-size:14px; transition:all .2s; }
        .form-control:focus { outline:none; border-color:var(--primary); box-shadow:0 0 0 3px rgba(79,70,229,.1); }
        .form-control:disabled { background:#f1f5f9; cursor:not-allowed; }

        .phone-pair { display:grid; grid-template-columns:1fr 1fr; gap:10px; }
        @media (max-width: 480px) { .phone-pair { grid-template-columns:1fr; } }

        .product-search-section { background:linear-gradient(135deg,#fff 0%,#f8fafc 100%); border:2px solid var(--border); border-radius:var(--radius-lg); padding:24px; margin-bottom:24px; }
        .search-header { font-size:16px; font-weight:700; color:var(--dark); margin-bottom:16px; display:flex; align-items:center; gap:8px; }
        .input-with-button { display:flex; gap:12px; align-items:flex-start; flex-wrap:wrap; }
        .input-with-button .form-control { flex:1; min-width:240px; }

        .btn { display:inline-flex; align-items:center; gap:8px; padding:12px 24px; border-radius:var(--radius-md); font-size:14px; font-weight:600; border:none; cursor:pointer; transition:all .2s; white-space:nowrap; }
        .btn-primary   { background:var(--primary); color:#fff; }
        .btn-primary:hover   { background:var(--primary-hover); transform:translateY(-1px); }
        .btn-secondary { background:var(--dark); color:#fff; }
        .btn-success   { background:var(--success); color:#fff; }
        .btn-warning   { background:var(--warning); color:#fff; }
        .btn-danger    { background:var(--danger); color:#fff; }
        .btn:disabled  { opacity:.5; cursor:not-allowed; }

        .product-info-card { background:var(--surface); border:2px solid var(--border); border-radius:var(--radius-lg); padding:20px; margin-bottom:24px; display:none; animation:slideDown .3s ease-out; }
        @keyframes slideDown { from{opacity:0;transform:translateY(-10px)} to{opacity:1;transform:translateY(0)} }

        .product-info-header { display:flex; align-items:center; gap:16px; margin-bottom:16px; padding-bottom:16px; border-bottom:2px solid var(--border); }
        .product-image-preview { width:80px; height:80px; border-radius:var(--radius-md); object-fit:cover; border:2px solid var(--border); }
        .product-image-placeholder { width:80px; height:80px; border-radius:var(--radius-md); border:2px dashed var(--border); background:#f1f5f9; display:flex; align-items:center; justify-content:center; color:var(--mid); }
        .product-code-badge { display:inline-flex; align-items:center; gap:6px; padding:4px 12px; background:var(--primary-light); color:var(--primary); border-radius:999px; font-family:monospace; font-size:12px; font-weight:600; }
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

        .spinner { display:inline-block; width:16px; height:16px; border:2px solid rgba(255,255,255,.3); border-radius:50%; border-top-color:#fff; animation:spin .6s linear infinite; }
        @keyframes spin { to { transform:rotate(360deg); } }

        .city-autocomplete-wrapper { position:relative; }
        .city-dropdown { position:absolute; top:calc(100% + 4px); left:0; right:0; background:var(--surface); border:2px solid var(--primary); border-radius:var(--radius-md); max-height:220px; overflow-y:auto; z-index:9999; display:none; }
        .city-dropdown.open { display:block; }
        .city-dropdown-item { padding:10px 14px; font-size:14px; cursor:pointer; border-bottom:1px solid var(--border); display:flex; align-items:center; gap:8px; }
        .city-dropdown-item:hover { background:var(--primary-light); }
        .city-dropdown-item .city-state { font-size:11px; color:var(--mid); margin-left:auto; }
        .city-dropdown-item mark { background:none; color:var(--primary); font-weight:700; }

        /* CSV Preview */
        .csv-preview-table { width:100%; border-collapse:collapse; font-size:13px; }
        .csv-preview-table th { background:var(--bg); padding:10px; border-bottom:2px solid var(--border); text-align:left; font-weight:700; }
        .csv-preview-table td { padding:8px 10px; border-bottom:1px solid var(--border); }
        .csv-preview-table .city-ok      { color:#065f46; font-weight:600; }
        .csv-preview-table .city-invalid { color:#991b1b; font-weight:600; }
        .csv-preview-table .city-missing { color:#92400e; font-weight:600; }

        .validation-badge { padding:4px 12px; border-radius:999px; font-size:12px; font-weight:600; }
        .badge-ok   { background:#d1fae5; color:#065f46; }
        .badge-warn { background:#fef3c7; color:#92400e; }
        .badge-err  { background:#fee2e2; color:#991b1b; }

        .empty-state { text-align:center; padding:60px 20px; }
        .empty-icon { font-size:64px; color:var(--border); margin-bottom:16px; }
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

        <!-- Tab Navigation -->
        <div class="tab-navigation">
            <button class="tab-button {{ request()->get('bulk') ? '' : 'active' }}" onclick="switchTab('single')">
                <i class="fas fa-cart-plus"></i> Single Order
            </button>
            <button class="tab-button {{ request()->get('bulk') ? 'active' : '' }}" onclick="switchTab('bulk')">
                <i class="fas fa-cloud-upload-alt"></i> Bulk Upload CSV
            </button>
        </div>

        <!-- Single Order Tab -->
        <div id="singleTab" class="tab-content {{ request()->get('bulk') ? '' : 'active' }}">
            @include('seller.partials.single-order')
        </div>

        <!-- Bulk Upload Tab -->
        <div id="bulkTab" class="tab-content {{ request()->get('bulk') ? 'active' : '' }}">
            @include('seller.partials.bulk-upload')
        </div>
    </div>
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function switchTab(tab) {
            const url = new URL(window.location.href);
            if (tab === 'bulk') {
                url.searchParams.set('bulk', '1');
            } else {
                url.searchParams.delete('bulk');
            }
            window.location.href = url.toString();
        }
    </script>
@endpush
