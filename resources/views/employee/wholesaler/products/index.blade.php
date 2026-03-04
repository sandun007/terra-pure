@extends('employee.layout.app')

@push('title')
    Wholesaler Products Management
@endpush

@push('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root{
            --bg:#0b1220;
            --card:#0f1a2e;
            --card2:#101c33;
            --text:#e7ecff;
            --muted:#9aa6c0;
            --line:rgba(255,255,255,.08);
            --primary:#6366f1;
            --primary2:#4f46e5;
            --success:#10b981;
            --warning:#f59e0b;
            --danger:#ef4444;
            --info:#06b6d4;
            --shadow: 0 20px 50px rgba(0,0,0,.35);
            --shadow2: 0 10px 30px rgba(0,0,0,.25);
            --radius:18px;
        }

        /* Page base */
        .page-wrap{
            padding: 12px 0 30px 0;
        }

        /* Glass header */
        .page-hero{
            border-radius: 24px;
            padding: 22px 22px;
            background:
                radial-gradient(1200px 300px at 10% 0%, rgba(99,102,241,.35), transparent 50%),
                radial-gradient(1000px 400px at 90% 20%, rgba(6,182,212,.25), transparent 55%),
                linear-gradient(135deg, rgba(255,255,255,.08), rgba(255,255,255,.03));
            border: 1px solid var(--line);
            box-shadow: var(--shadow);
            backdrop-filter: blur(10px);
        }
        .page-hero h3{
            font-weight: 800;
            letter-spacing: .2px;
            margin: 0;
        }
        .page-hero p{
            margin: 6px 0 0 0;
            color: var(--muted);
        }

        /* Action buttons */
        .btn-modern{
            border: 1px solid rgba(255,255,255,.10);
            border-radius: 14px;
            padding: 10px 14px;
            font-weight: 700;
            box-shadow: var(--shadow2);
            transition: all .25s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            white-space: nowrap;
        }
        .btn-modern:hover{ transform: translateY(-1px); }
        .btn-primary-modern{
            background: linear-gradient(135deg, var(--primary), var(--primary2));
            color: #fff;
        }
        .btn-warning-modern{
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: #141414;
        }
        .btn-secondary-modern{
            background: linear-gradient(135deg, rgba(255,255,255,.10), rgba(255,255,255,.04));
            color: #fff;
        }
        .btn-bulk{
            background: linear-gradient(135deg, rgba(16,185,129,.25), rgba(16,185,129,.10));
            color: #eafff6;
        }

        /* KPI cards */
        .kpi{
            border-radius: var(--radius);
            border: 1px solid var(--line);
            background: linear-gradient(135deg, rgba(255,255,255,.06), rgba(255,255,255,.02));
            box-shadow: var(--shadow2);
            overflow: hidden;
            position: relative;
        }
        .kpi:before{
            content:"";
            position:absolute;
            inset:-60px -60px auto auto;
            width:140px;height:140px;
            border-radius: 999px;
            background: rgba(255,255,255,.06);
            filter: blur(2px);
            transform: rotate(10deg);
        }
        .kpi .kpi-body{ padding: 18px 18px; }
        .kpi .kpi-label{ color: var(--muted); font-weight: 700; font-size: 13px; }
        .kpi .kpi-value{ font-size: 30px; font-weight: 900; line-height: 1.1; margin-top: 6px; }
        .kpi .kpi-icon{
            width: 46px; height: 46px;
            border-radius: 16px;
            display:flex; align-items:center; justify-content:center;
            border: 1px solid rgba(255,255,255,.10);
            background: rgba(255,255,255,.06);
        }
        .kpi.primary  .kpi-icon{ background: rgba(99,102,241,.18); }
        .kpi.success  .kpi-icon{ background: rgba(16,185,129,.18); }
        .kpi.warning  .kpi-icon{ background: rgba(245,158,11,.18); }
        .kpi.info     .kpi-icon{ background: rgba(6,182,212,.18); }

        /* Filter card */
        .card-modern{
            border-radius: 22px;
            border: 1px solid var(--line);
            background: linear-gradient(135deg, rgba(255,255,255,.06), rgba(255,255,255,.02));
            box-shadow: var(--shadow2);
        }
        .card-modern .card-body{ padding: 18px; }

        .form-control, .form-select{
            border-radius: 14px !important;
            border: 1px solid rgba(255,255,255,.10) !important;
            background: rgba(255,255,255,.06) !important;
            color: #111 !important;
        }
        .form-control::placeholder{ color: #6b7280; }
        .filter-btn{
            border-radius: 14px;
            font-weight: 800;
            box-shadow: var(--shadow2);
        }

        /* Table */
        .table-wrap{
            border-radius: 22px;
            border: 1px solid var(--line);
            overflow: hidden;
        }
        table.table{
            margin-bottom: 0;
        }
        .table thead th{
            font-size: 12px;
            letter-spacing: .35px;
            text-transform: uppercase;
            color: #6b7280;
            background: rgba(255,255,255,.08);
            border-bottom: 1px solid rgba(0,0,0,.05);
            padding: 14px 12px;
            vertical-align: middle;
        }
        .table tbody td{
            padding: 14px 12px;
            vertical-align: middle;
        }
        .table tbody tr:hover{
            background: rgba(99,102,241,.06);
        }

        /* Product cell */
        .product-img {
            width: 46px;
            height: 46px;
            object-fit: cover;
            border-radius: 14px;
            border: 1px solid rgba(0,0,0,.06);
            box-shadow: 0 8px 20px rgba(0,0,0,.10);
        }
        .img-fallback{
            width: 46px; height: 46px;
            border-radius: 14px;
            display:flex; align-items:center; justify-content:center;
            background: rgba(0,0,0,.04);
            border: 1px dashed rgba(0,0,0,.12);
            color: rgba(0,0,0,.45);
        }

        .code-pill{
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
            font-size: 12px;
            padding: 6px 10px;
            border-radius: 999px;
            border: 1px solid rgba(0,0,0,.08);
            background: rgba(0,0,0,.03);
            display: inline-block;
        }

        /* Badges */
        .badge-soft{
            border-radius: 999px;
            padding: 7px 10px;
            font-weight: 800;
            font-size: 12px;
            border: 1px solid rgba(0,0,0,.06);
        }
        .badge-price-change {
            background: linear-gradient(135deg, rgba(99,102,241,.20), rgba(99,102,241,.10));
            color: #2b2f9a;
        }
        .badge-exchange {
            background: linear-gradient(135deg, rgba(245,158,11,.22), rgba(245,158,11,.10));
            color: #7a4a00;
        }
        .badge-stock-success{ background: rgba(16,185,129,.15); color:#0f5f44; }
        .badge-stock-warning{ background: rgba(245,158,11,.18); color:#7a4a00; }
        .badge-stock-danger { background: rgba(239,68,68,.14); color:#7a1d1d; }

        /* Actions */
        .action-btn-group {
            display: flex;
            gap: 8px;
        }
        .btn-action {
            width: 36px;
            height: 36px;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.25s ease;
            border: 1px solid rgba(0,0,0,.06);
            box-shadow: 0 10px 22px rgba(0,0,0,.12);
            text-decoration: none;
        }
        .btn-action:hover{ transform: translateY(-1px); filter: brightness(1.03); }
        .btn-price { background: linear-gradient(135deg, rgba(99,102,241,.25), rgba(99,102,241,.12)); color: #2b2f9a; }
        .btn-exchange { background: linear-gradient(135deg, rgba(245,158,11,.25), rgba(245,158,11,.12)); color: #7a4a00; }
        .btn-view { background: linear-gradient(135deg, rgba(16,185,129,.22), rgba(16,185,129,.10)); color:#0f5f44; }

        /* Floating bulk button */
        .bulk-fab{
            position: fixed;
            right: 22px;
            bottom: 22px;
            z-index: 1050;
            display: none;
        }

        /* Pagination block */
        .pagination-wrap{
            border-top: 1px solid rgba(0,0,0,.06);
            padding: 14px 12px;
        }

        @media (max-width: 768px){
            .page-hero{ padding: 16px; }
            .btn-modern{ width: 100%; justify-content:center; }
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid page-wrap">

        <!-- HERO -->
        <div class="page-hero mb-4">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-3">
                <div>
                    <h3 class="fw-bold">Wholesaler Products</h3>
                    <p class="text-muted">Manage products, prices, and exchanges for all wholesalers</p>
                </div>

                <div class="d-flex flex-column flex-sm-row gap-2">
                    <a href="{{ route('employee.wholesaler.price-changes.create') }}" class="btn-modern btn-primary-modern">
                        <i class="fas fa-tag"></i> New Price Change
                    </a>
                    <a href="{{ route('employee.wholesaler.exchanges.create') }}" class="btn-modern btn-warning-modern">
                        <i class="fas fa-exchange-alt"></i> New Exchange
                    </a>
                    <a href="{{ route('employee.wholesaler.products.export') }}" class="btn-modern btn-secondary-modern">
                        <i class="fas fa-download"></i> Export
                    </a>

                    <button type="button" class="btn-modern btn-bulk" id="openBulkModalBtn" data-bs-toggle="modal" data-bs-target="#bulkActionsModal" style="display:none;">
                        <i class="fas fa-layer-group"></i> Bulk Actions
                    </button>
                </div>
            </div>
        </div>

        <!-- KPI -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="kpi primary">
                    <div class="kpi-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="kpi-label">Total Products</div>
                            <div class="kpi-value">{{ $stats['total_products'] }}</div>
                        </div>
                        <div class="kpi-icon"><i class="fas fa-box"></i></div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="kpi success">
                    <div class="kpi-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="kpi-label">Price Changes</div>
                            <div class="kpi-value">{{ $stats['total_price_changes'] }}</div>
                            <div class="small text-muted mt-1">{{ $stats['today_price_changes'] }} today</div>
                        </div>
                        <div class="kpi-icon"><i class="fas fa-chart-line"></i></div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="kpi warning">
                    <div class="kpi-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="kpi-label">Exchanges</div>
                            <div class="kpi-value">{{ $stats['total_exchanges'] }}</div>
                        </div>
                        <div class="kpi-icon"><i class="fas fa-exchange-alt"></i></div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="kpi info">
                    <div class="kpi-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="kpi-label">Wholesalers</div>
                            <div class="kpi-value">{{ $stats['total_wholesalers'] }}</div>
                        </div>
                        <div class="kpi-icon"><i class="fas fa-store"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FILTERS -->
        <div class="card-modern mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('employee.wholesaler.products.index') }}" id="filterForm">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="small text-muted fw-bold mb-1">Search</label>
                            <input type="text" name="search" class="form-control"
                                   placeholder="Search by name, code..." value="{{ request('search') }}">
                        </div>

                        <div class="col-md-2">
                            <label class="small text-muted fw-bold mb-1">Wholesaler</label>
                            <select name="wholesaler_id" class="form-select">
                                <option value="all">All Wholesalers</option>
                                @foreach($wholesalers as $wholesaler)
                                    <option value="{{ $wholesaler->id }}" {{ request('wholesaler_id') == $wholesaler->id ? 'selected' : '' }}>
                                        {{ $wholesaler->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="small text-muted fw-bold mb-1">Category</label>
                            <select name="category_id" class="form-select">
                                <option value="all">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="small text-muted fw-bold mb-1">Status</label>
                            <select name="status" class="form-select">
                                <option value="all">All Status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="small text-muted fw-bold mb-1">Stock</label>
                            <select name="stock_status" class="form-select">
                                <option value="">All Stock</option>
                                <option value="in_stock" {{ request('stock_status') == 'in_stock' ? 'selected' : '' }}>In Stock</option>
                                <option value="low_stock" {{ request('stock_status') == 'low_stock' ? 'selected' : '' }}>Low Stock</option>
                                <option value="out_of_stock" {{ request('stock_status') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                            </select>
                        </div>

                        <div class="col-md-1 d-grid">
                            <button type="submit" class="btn btn-primary filter-btn">
                                <i class="fas fa-filter me-1"></i>
                            </button>
                        </div>

                        <div class="col-md-12">
                            <div class="d-flex gap-2 flex-wrap mt-1">
                                <a href="{{ route('employee.wholesaler.products.index') }}" class="btn btn-sm btn-light">
                                    <i class="fas fa-rotate-left me-1"></i> Reset
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="document.getElementById('filterForm').submit()">
                                    <i class="fas fa-magnifying-glass me-1"></i> Apply
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- TABLE -->
        <div class="card-modern">
            <div class="card-body p-0">
                <div class="table-responsive table-wrap">
                    <table class="table table-hover align-middle" id="productsTable">
                        <thead>
                        <tr>
                            <th style="width:40px;"><input type="checkbox" id="selectAll"></th>
                            <th style="width:70px;">Image</th>
                            <th style="width:130px;">Code</th>
                            <th>Name</th>
                            <th style="width:180px;">Wholesaler</th>
                            <th style="width:180px;">Price</th>
                            <th style="width:110px;">Stock</th>
                            <th style="width:130px;">Changes</th>
                            <th style="width:160px;">Last Updated</th>
                            <th style="width:150px;">Actions</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($products as $product)
                            @php
                                $stockBadge = 'badge-stock-danger';
                                if ($product->qty > 0) {
                                    $stockBadge = ($product->qty <= ($product->min_stock_level ?? 5)) ? 'badge-stock-warning' : 'badge-stock-success';
                                }
                            @endphp

                            <tr>
                                <td><input type="checkbox" class="product-checkbox" value="{{ $product->id }}"></td>

                                <td>
                                    @if($product->image)
                                        <img src="{{ asset('storage/'.$product->image) }}" class="product-img" alt="img">
                                    @else
                                        <div class="img-fallback">
                                            <i class="fas fa-image"></i>
                                        </div>
                                    @endif
                                </td>

                                <td><span class="code-pill">{{ $product->code }}</span></td>

                                <td>
                                    <div class="fw-bold">{{ $product->name }}</div>
                                    <div class="d-flex gap-2 flex-wrap mt-1">
                                        @if($product->discount > 0)
                                            <span class="badge-soft badge-stock-success">
                                            <i class="fas fa-bolt me-1"></i>{{ $product->discount }}% off
                                        </span>
                                        @endif

                                        @if(($product->exchange_count ?? 0) > 0)
                                            <span class="badge-soft badge-exchange" title="Exchanged {{ $product->exchange_count }} times">
                                            <i class="fas fa-exchange-alt me-1"></i>{{ $product->exchange_count }}
                                        </span>
                                        @endif
                                    </div>
                                </td>

                                <td>
                                    <div class="fw-semibold">{{ $product->wholesaler->business_name ?? 'N/A' }}</div>
                                    <div class="small text-muted">{{ $product->wholesaler->name ?? '' }}</div>
                                </td>

                                <td>
                                    <div class="fw-bold">Rs. {{ number_format($product->selling_price, 2) }}</div>
                                    @if($product->display_price != $product->selling_price)
                                        <div class="small text-muted">Display: Rs. {{ number_format($product->display_price, 2) }}</div>
                                    @endif
                                </td>

                                <td>
                                <span class="badge-soft {{ $stockBadge }}">
                                    <i class="fas fa-cubes me-1"></i>{{ $product->qty }}
                                </span>
                                </td>

                                <td>
                                    <div class="d-flex gap-2 flex-wrap">
                                        @if(($product->price_changes_count ?? 0) > 0)
                                            <span class="badge-soft badge-price-change">
                                            <i class="fas fa-chart-line me-1"></i>{{ $product->price_changes_count }}
                                        </span>
                                        @endif
                                        @if(($product->exchange_count ?? 0) > 0)
                                            <span class="badge-soft badge-exchange">
                                            <i class="fas fa-exchange-alt me-1"></i>{{ $product->exchange_count }}
                                        </span>
                                        @endif
                                        @if(($product->price_changes_count ?? 0) == 0 && ($product->exchange_count ?? 0) == 0)
                                            <span class="badge-soft" style="background: rgba(0,0,0,.03); color:#6b7280;">
                                            <i class="fas fa-minus me-1"></i>None
                                        </span>
                                        @endif
                                    </div>
                                </td>

                                <td>
                                    @if($product->last_price_updated_at)
                                        <div class="small fw-bold">{{ $product->last_price_updated_at->diffForHumans() }}</div>
                                        <div class="small text-muted">by {{ $product->lastPriceUpdatedBy->name ?? 'System' }}</div>
                                    @else
                                        <span class="small text-muted">Never</span>
                                    @endif
                                </td>

                                <td>
                                    <div class="action-btn-group">
                                        <a href="{{ route('employee.wholesaler.price-changes.create', ['product_id' => $product->id]) }}"
                                           class="btn-action btn-price" title="Change Price">
                                            <i class="fas fa-tag"></i>
                                        </a>

                                        <a href="{{ route('employee.wholesaler.exchanges.create', ['source_product_id' => $product->id]) }}"
                                           class="btn-action btn-exchange" title="Exchange Product">
                                            <i class="fas fa-exchange-alt"></i>
                                        </a>

                                        <!-- FIXED: Show button with correct route name -->
                                        <a href="{{ route('employee.wholesaler.products.show', $product->id) }}"
                                           class="btn-action btn-view" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <div class="pagination-wrap d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div class="small text-muted">
                            Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }} products
                        </div>
                        <div>
                            {{ $products->links() }}
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- FLOATING BULK FAB -->
        <button class="btn-modern btn-bulk bulk-fab" id="bulkFab" data-bs-toggle="modal" data-bs-target="#bulkActionsModal">
            <i class="fas fa-layer-group"></i> Bulk Actions
        </button>

    </div>

    <!-- Bulk Actions Modal -->
    <div class="modal fade" id="bulkActionsModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius:20px; overflow:hidden;">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Bulk Actions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="alert alert-info py-2">
                        <i class="fas fa-info-circle me-2"></i>
                        Selected products: <strong id="selectedCount">0</strong>
                    </div>

                    <div class="d-grid gap-2">
                        <button class="btn btn-primary" onclick="bulkPriceChange()">
                            <i class="fas fa-tag me-2"></i>Bulk Price Change
                        </button>
                        <button class="btn btn-warning" onclick="bulkExchange()">
                            <i class="fas fa-exchange-alt me-2"></i>Bulk Exchange (Select 2)
                        </button>
                        <button class="btn btn-success" onclick="bulkActivate()">
                            <i class="fas fa-check-circle me-2"></i>Activate Selected
                        </button>
                        <button class="btn btn-secondary" onclick="bulkDeactivate()">
                            <i class="fas fa-times-circle me-2"></i>Deactivate Selected
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            // Optional: Initialize DataTable if you want client-side sorting without pagination conflict
            // $('#productsTable').DataTable({
            //     paging: false,
            //     info: false,
            //     searching: false,
            //     ordering: true
            // });

            const $selectAll = $('#selectAll');
            const $bulkFab = $('#bulkFab');
            const $openBulkModalBtn = $('#openBulkModalBtn');

            function updateBulkUI(){
                const count = $('.product-checkbox:checked').length;
                $('#selectedCount').text(count);

                if(count > 0){
                    $bulkFab.fadeIn(120);
                    $openBulkModalBtn.show();
                }else{
                    $bulkFab.fadeOut(120);
                    $openBulkModalBtn.hide();
                }

                // If all checked, keep selectAll checked
                const total = $('.product-checkbox').length;
                $selectAll.prop('checked', total > 0 && count === total);
            }

            $selectAll.on('change', function() {
                $('.product-checkbox').prop('checked', this.checked);
                updateBulkUI();
            });

            $(document).on('change', '.product-checkbox', function() {
                updateBulkUI();
            });

            updateBulkUI();
        });

        function getSelectedIds() {
            return $('.product-checkbox:checked').map(function() {
                return $(this).val();
            }).get();
        }

        function bulkPriceChange() {
            const selectedIds = getSelectedIds();
            if (selectedIds.length === 0) {
                alert('Please select at least one product');
                return;
            }
            window.location.href = '{{ route("employee.wholesaler.price-changes.bulk.create") }}?product_ids=' + selectedIds.join(',');
        }

        function bulkExchange() {
            const selectedIds = getSelectedIds();
            if (selectedIds.length === 0) {
                alert('Please select at least one product');
                return;
            }
            if (selectedIds.length !== 2) {
                alert('Please select exactly 2 products for exchange');
                return;
            }
            window.location.href = '{{ route("employee.wholesaler.exchanges.create") }}?source_product_id=' + selectedIds[0] + '&destination_product_id=' + selectedIds[1];
        }

        function bulkActivate() {
            const selectedIds = getSelectedIds();
            if (selectedIds.length === 0) return;
            if (confirm('Activate ' + selectedIds.length + ' products?')) {
                bulkUpdate(selectedIds, 'activate');
            }
        }

        function bulkDeactivate() {
            const selectedIds = getSelectedIds();
            if (selectedIds.length === 0) return;
            if (confirm('Deactivate ' + selectedIds.length + ' products?')) {
                bulkUpdate(selectedIds, 'deactivate');
            }
        }

        function bulkUpdate(productIds, action) {
            $.ajax({
                url: '{{ route("employee.wholesaler.products.bulk-update") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    product_ids: productIds,
                    action: action
                },
                success: function(response) {
                    if (response.success) {
                        alert(response.message);
                        location.reload();
                    } else {
                        alert(response.message || 'Error updating products');
                    }
                },
                error: function(xhr) {
                    alert('Error: ' + (xhr.responseJSON?.message || 'Unknown error'));
                }
            });
        }
    </script>
@endpush
