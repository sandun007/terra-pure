@extends('employee.layout.app')

@push('title')
    Wholesaler Products Management
@endpush

@push('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

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

        body {
            background-color: #f8fafc;
        }

        /* Page base */
        .page-wrap{
            padding: 12px 0 30px 0;
        }

        /* Glass header */
        .page-hero{
            border-radius: 24px;
            padding: 22px 22px;
            background: linear-gradient(135deg, #ffffff, #f8fafc);
            border: 1px solid rgba(0,0,0,.08);
            box-shadow: 0 10px 30px rgba(0,0,0,.05);
        }
        .page-hero h3{
            font-weight: 800;
            letter-spacing: .2px;
            margin: 0;
            color: #1e293b;
        }
        .page-hero p{
            margin: 6px 0 0 0;
            color: #64748b;
        }

        /* Action buttons */
        .btn-modern{
            border-radius: 14px;
            padding: 10px 18px;
            font-weight: 600;
            transition: all .25s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
            border: none;
            cursor: pointer;
            text-decoration: none;
        }
        .btn-modern:hover{ transform: translateY(-2px); box-shadow: 0 10px 25px rgba(0,0,0,.1); }

        .btn-primary-modern{
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            color: #fff;
        }
        .btn-warning-modern{
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: #fff;
        }
        .btn-success-modern{
            background: linear-gradient(135deg, #10b981, #059669);
            color: #fff;
        }
        .btn-secondary-modern{
            background: linear-gradient(135deg, #64748b, #475569);
            color: #fff;
        }
        .btn-info-modern{
            background: linear-gradient(135deg, #06b6d4, #0891b2);
            color: #fff;
        }
        .btn-bulk{
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
            color: #fff;
        }

        /* KPI cards */
        .kpi{
            border-radius: var(--radius);
            border: 1px solid rgba(0,0,0,.05);
            background: #fff;
            box-shadow: 0 10px 25px rgba(0,0,0,.03);
            overflow: hidden;
            position: relative;
        }
        .kpi .kpi-body{ padding: 20px; }
        .kpi .kpi-label{ color: #64748b; font-weight: 600; font-size: 13px; text-transform: uppercase; letter-spacing: .5px; }
        .kpi .kpi-value{ font-size: 32px; font-weight: 800; line-height: 1.2; margin-top: 8px; color: #1e293b; }
        .kpi .kpi-icon{
            width: 48px; height: 48px;
            border-radius: 16px;
            display:flex; align-items:center; justify-content:center;
            background: #f1f5f9;
            color: #475569;
        }
        .kpi.primary .kpi-icon{ background: #eef2ff; color: #4f46e5; }
        .kpi.success .kpi-icon{ background: #d1fae5; color: #059669; }
        .kpi.warning .kpi-icon{ background: #fed7aa; color: #b45309; }
        .kpi.info .kpi-icon{ background: #cffafe; color: #0891b2; }

        /* Filter card */
        .card-modern{
            border-radius: 22px;
            border: 1px solid rgba(0,0,0,.05);
            background: #fff;
            box-shadow: 0 10px 25px rgba(0,0,0,.03);
        }
        .card-modern .card-body{ padding: 20px; }

        .form-control, .form-select{
            border-radius: 12px !important;
            border: 1px solid #e2e8f0 !important;
            background: #fff !important;
            color: #1e293b !important;
            padding: 10px 14px;
        }
        .form-control:focus, .form-select:focus{
            border-color: #6366f1 !important;
            box-shadow: 0 0 0 3px rgba(99,102,241,.1) !important;
        }
        .form-control::placeholder{ color: #94a3b8; }
        .filter-btn{
            border-radius: 12px;
            font-weight: 600;
            padding: 10px 20px;
            background: #6366f1;
            border: none;
            color: #fff;
        }
        .filter-btn:hover{
            background: #4f46e5;
        }

        /* Table */
        .table-wrap{
            border-radius: 22px;
            border: 1px solid rgba(0,0,0,.05);
            overflow: hidden;
            background: #fff;
        }
        table.table{
            margin-bottom: 0;
        }
        .table thead th{
            font-size: 12px;
            letter-spacing: .5px;
            text-transform: uppercase;
            color: #64748b;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            padding: 16px 12px;
            vertical-align: middle;
            font-weight: 600;
        }
        .table tbody td{
            padding: 16px 12px;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
        }
        .table tbody tr:hover{
            background: #f8fafc;
        }
        .table tbody tr.table-warning{
            background: #fffbeb;
        }

        /* Product cell */
        .product-img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 6px rgba(0,0,0,.02);
        }
        .img-fallback{
            width: 50px; height: 50px;
            border-radius: 12px;
            display:flex; align-items:center; justify-content:center;
            background: #f1f5f9;
            border: 1px dashed #cbd5e1;
            color: #94a3b8;
        }

        .code-pill{
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
            font-size: 12px;
            padding: 6px 12px;
            border-radius: 20px;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            display: inline-block;
            color: #475569;
        }

        /* Badges */
        .badge-soft{
            border-radius: 20px;
            padding: 6px 12px;
            font-weight: 600;
            font-size: 12px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .badge-price-change {
            background: #eef2ff;
            color: #4f46e5;
        }
        .badge-exchange {
            background: #ffedd5;
            color: #b45309;
        }
        .badge-stock-success{ background: #d1fae5; color:#059669; }
        .badge-stock-warning{ background: #fed7aa; color:#b45309; }
        .badge-stock-danger { background: #fee2e2; color:#b91c1c; }

        /* Approval badges */
        .badge-approval-pending { background: #ffedd5; color: #b45309; }
        .badge-approval-approved { background: #d1fae5; color: #059669; }
        .badge-approval-rejected { background: #fee2e2; color: #b91c1c; }

        /* Actions */
        .action-btn-group {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }
        .btn-action {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            border: 1px solid #e2e8f0;
            background: #fff;
            color: #475569;
            text-decoration: none;
            cursor: pointer;
        }
        .btn-action:hover{
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,.05);
            border-color: #cbd5e1;
        }
        .btn-action[disabled], .btn-action.disabled {
            opacity: 0.5;
            cursor: not-allowed;
            pointer-events: none;
        }
        .btn-price:hover { background: #eef2ff; color: #4f46e5; border-color: #a5b4fc; }
        .btn-exchange:hover { background: #ffedd5; color: #b45309; border-color: #fdba74; }
        .btn-view:hover { background: #d1fae5; color: #059669; border-color: #6ee7b7; }
        .btn-approve:hover { background: #d1fae5; color: #059669; border-color: #6ee7b7; }
        .btn-reject:hover { background: #fee2e2; color: #b91c1c; border-color: #fca5a5; }

        /* Floating bulk button */
        .bulk-fab{
            position: fixed;
            right: 24px;
            bottom: 24px;
            z-index: 1050;
            display: none;
            box-shadow: 0 10px 30px rgba(124, 58, 237, .3);
        }

        /* Pagination block */
        .pagination-wrap{
            border-top: 1px solid #e2e8f0;
            padding: 16px 20px;
            background: #fff;
        }

        /* Modal styles */
        .modal-content {
            border-radius: 20px;
            border: none;
            box-shadow: 0 25px 50px rgba(0,0,0,.15);
        }
        .modal-header {
            border-bottom: 1px solid #e2e8f0;
            padding: 20px 24px;
        }
        .modal-body {
            padding: 24px;
        }
        .modal-footer {
            border-top: 1px solid #e2e8f0;
            padding: 16px 24px;
        }

        @media (max-width: 768px){
            .page-hero{ padding: 16px; }
            .btn-modern{ width: 100%; justify-content:center; }
            .action-btn-group { justify-content: flex-start; }
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid page-wrap">

        <!-- HERO SECTION -->
        <div class="page-hero mb-4">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-3">
                <div>
                    <h3 class="fw-bold">Wholesaler Products</h3>
                    <p class="text-muted">Manage products, approvals, prices, and exchanges for all wholesalers</p>
                </div>

                <div class="d-flex flex-column flex-sm-row gap-2">
                    <a href="{{ route('employee.wholesaler.price-changes.create') }}" class="btn-modern btn-primary-modern">
                        <i class="fas fa-tag"></i> New Price Change
                    </a>
                    <a href="{{ route('employee.wholesaler.exchanges.create') }}" class="btn-modern btn-warning-modern">
                        <i class="fas fa-exchange-alt"></i> New Exchange
                    </a>
                    <a href="{{ route('employee.wholesaler.approvals.pending') }}" class="btn-modern btn-info-modern">
                        <i class="fas fa-check-circle"></i> Pending Approvals
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

        <!-- KPI CARDS -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="kpi primary">
                    <div class="kpi-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="kpi-label">Total Products</div>
                            <div class="kpi-value">{{ $stats['total_products'] ?? 0 }}</div>
                        </div>
                        <div class="kpi-icon"><i class="fas fa-box"></i></div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="kpi warning">
                    <div class="kpi-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="kpi-label">Pending Approval</div>
                            <div class="kpi-value">{{ $stats['pending_approval'] ?? 0 }}</div>
                        </div>
                        <div class="kpi-icon"><i class="fas fa-clock"></i></div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="kpi success">
                    <div class="kpi-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="kpi-label">Price Changes</div>
                            <div class="kpi-value">{{ $stats['total_price_changes'] ?? 0 }}</div>
                            <div class="small text-muted mt-1">{{ $stats['today_price_changes'] ?? 0 }} today</div>
                        </div>
                        <div class="kpi-icon"><i class="fas fa-chart-line"></i></div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="kpi info">
                    <div class="kpi-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="kpi-label">Wholesalers</div>
                            <div class="kpi-value">{{ $stats['total_wholesalers'] ?? 0 }}</div>
                        </div>
                        <div class="kpi-icon"><i class="fas fa-store"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FILTERS SECTION -->
        <div class="card-modern mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('employee.wholesaler.products.index') }}" id="filterForm">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-2">
                            <label class="small fw-bold mb-1">Search</label>
                            <input type="text" name="search" class="form-control"
                                   placeholder="Name, code..." value="{{ request('search') }}">
                        </div>

                        <div class="col-md-2">
                            <label class="small fw-bold mb-1">Wholesaler</label>
                            <select name="wholesaler_id" class="form-select">
                                <option value="all">All Wholesalers</option>
                                @foreach($wholesalers ?? [] as $wholesaler)
                                    <option value="{{ $wholesaler->id }}" {{ request('wholesaler_id') == $wholesaler->id ? 'selected' : '' }}>
                                        {{ $wholesaler->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="small fw-bold mb-1">Category</label>
                            <select name="category_id" class="form-select">
                                <option value="all">All Categories</option>
                                @foreach($categories ?? [] as $category)
                                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="small fw-bold mb-1">Approval Status</label>
                            <select name="approval_status" class="form-select">
                                <option value="all">All Status</option>
                                <option value="pending" {{ request('approval_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ request('approval_status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ request('approval_status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="small fw-bold mb-1">Stock</label>
                            <select name="stock_status" class="form-select">
                                <option value="">All Stock</option>
                                <option value="in_stock" {{ request('stock_status') == 'in_stock' ? 'selected' : '' }}>In Stock</option>
                                <option value="low_stock" {{ request('stock_status') == 'low_stock' ? 'selected' : '' }}>Low Stock</option>
                                <option value="out_of_stock" {{ request('stock_status') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                            </select>
                        </div>

                        <div class="col-md-2 d-grid">
                            <button type="submit" class="btn filter-btn">
                                <i class="fas fa-filter me-1"></i> Apply
                            </button>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <a href="{{ route('employee.wholesaler.products.index') }}" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-times me-1"></i> Clear Filters
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- PRODUCTS TABLE -->
        <div class="card-modern">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="productsTable">
                        <thead>
                        <tr>
                            <th style="width:40px;">
                                <input type="checkbox" id="selectAll" class="form-check-input">
                            </th>
                            <th style="width:70px;">Image</th>
                            <th style="width:120px;">Code</th>
                            <th>Name</th>
                            <th style="width:150px;">Wholesaler</th>
                            <th style="width:100px;">Approval</th>
                            <th style="width:120px;">Price</th>
                            <th style="width:80px;">Stock</th>
                            <th style="width:100px;">Changes</th>
                            <th style="width:140px;">Last Updated</th>
                            <th style="width:200px;">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($products ?? [] as $product)
                            @php
                                $stockBadge = 'badge-stock-success';
                                if ($product->qty <= 0) {
                                    $stockBadge = 'badge-stock-danger';
                                } elseif ($product->qty <= ($product->min_stock_level ?? 5)) {
                                    $stockBadge = 'badge-stock-warning';
                                }

                                $approvalBadge = 'badge-approval-pending';
                                $approvalText = 'Pending';
                                if ($product->employee_approval_status == 'approved') {
                                    $approvalBadge = 'badge-approval-approved';
                                    $approvalText = 'Approved';
                                } elseif ($product->employee_approval_status == 'rejected') {
                                    $approvalBadge = 'badge-approval-rejected';
                                    $approvalText = 'Rejected';
                                }

                                $isApproved = ($product->employee_approval_status == 'approved');
                                $isPending = ($product->employee_approval_status == 'pending');
                            @endphp

                            <tr class="{{ $isPending ? 'table-warning' : '' }}">
                                <td>
                                    <input type="checkbox" class="product-checkbox form-check-input" value="{{ $product->id }}">
                                </td>

                                <td>
                                    @if($product->image)
                                        <img src="{{ asset('storage/'.$product->image) }}" class="product-img" alt="{{ $product->name }}">
                                    @else
                                        <div class="img-fallback">
                                            <i class="fas fa-image"></i>
                                        </div>
                                    @endif
                                </td>

                                <td>
                                    <span class="code-pill">{{ $product->code }}</span>
                                </td>

                                <td>
                                    <div class="fw-semibold">{{ $product->name }}</div>
                                    @if($product->discount > 0)
                                        <span class="badge-soft badge-stock-success mt-1">
                                                <i class="fas fa-tag me-1"></i>{{ $product->discount }}% off
                                            </span>
                                    @endif
                                </td>

                                <td>
                                    <div class="fw-semibold">{{ $product->wholesaler->business_name ?? 'N/A' }}</div>
                                    <small class="text-muted">{{ $product->wholesaler->name ?? '' }}</small>
                                </td>

                                <td>
                                        <span class="badge-soft {{ $approvalBadge }}" title="{{ $product->rejection_reason ?? '' }}">
                                            <i class="fas fa-{{ $isApproved ? 'check-circle' : ($isPending ? 'clock' : 'times-circle') }} me-1"></i>
                                            {{ $approvalText }}
                                        </span>
                                    @if($product->approved_at)
                                        <div class="small text-muted mt-1">{{ $product->approved_at->format('d M Y') }}</div>
                                    @endif
                                </td>

                                <td>
                                    <div class="fw-bold">Rs. {{ number_format($product->selling_price, 2) }}</div>
                                    @if($product->display_price != $product->selling_price)
                                        <small class="text-muted">MRP: Rs. {{ number_format($product->display_price, 2) }}</small>
                                    @endif
                                </td>

                                <td>
                                        <span class="badge-soft {{ $stockBadge }}">
                                            <i class="fas fa-cubes me-1"></i>{{ $product->qty }}
                                        </span>
                                </td>

                                <td>
                                    <div class="d-flex flex-column gap-1">
                                        @if($product->price_changes_count > 0)
                                            <span class="badge-soft badge-price-change">
                                                    <i class="fas fa-chart-line me-1"></i>{{ $product->price_changes_count }}
                                                </span>
                                        @endif
                                        @if($product->exchange_count > 0)
                                            <span class="badge-soft badge-exchange">
                                                    <i class="fas fa-exchange-alt me-1"></i>{{ $product->exchange_count }}
                                                </span>
                                        @endif
                                    </div>
                                </td>

                                <td>
                                    @if($product->last_price_updated_at)
                                        <div class="small">{{ $product->last_price_updated_at->diffForHumans() }}</div>
                                        <small class="text-muted">by {{ $product->lastPriceUpdatedBy->name ?? 'System' }}</small>
                                    @else
                                        <span class="small text-muted">Never</span>
                                    @endif
                                </td>

                                <td>
                                    <div class="action-btn-group">
                                        @if($isPending)
                                            <button class="btn-action btn-approve" onclick="approveProduct({{ $product->id }})" title="Approve Product">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button class="btn-action btn-reject" onclick="rejectProduct({{ $product->id }})" title="Reject Product">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        @endif

                                        @if($isApproved)
                                            <a href="{{ route('employee.wholesaler.price-changes.create', ['product_id' => $product->id]) }}"
                                               class="btn-action btn-price" title="Change Price">
                                                <i class="fas fa-tag"></i>
                                            </a>
                                            <a href="{{ route('employee.wholesaler.exchanges.create', ['source_product_id' => $product->id]) }}"
                                               class="btn-action btn-exchange" title="Exchange Product">
                                                <i class="fas fa-exchange-alt"></i>
                                            </a>
                                        @else
                                            <span class="btn-action btn-price disabled" title="Product must be approved first">
                                                    <i class="fas fa-tag"></i>
                                                </span>
                                            <span class="btn-action btn-exchange disabled" title="Product must be approved first">
                                                    <i class="fas fa-exchange-alt"></i>
                                                </span>
                                        @endif

                                        <a href="{{ route('employee.wholesaler.products.show', $product->id) }}"
                                           class="btn-action btn-view" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center py-5">
                                    <i class="fas fa-box-open fa-3x mb-3" style="color: #cbd5e1;"></i>
                                    <h5 class="text-muted">No products found</h5>
                                    <p class="text-muted">Try adjusting your filters</p>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    @if(($products ?? null) && $products->hasPages())
                        <div class="pagination-wrap d-flex justify-content-between align-items-center flex-wrap">
                            <div class="small text-muted">
                                Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }} products
                            </div>
                            <div>
                                {{ $products->withQueryString()->links() }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- FLOATING BULK ACTION BUTTON -->
        <button class="btn-modern btn-bulk bulk-fab" id="bulkFab" data-bs-toggle="modal" data-bs-target="#bulkActionsModal">
            <i class="fas fa-layer-group"></i> Bulk Actions ({{ $products->total() ?? 0 }})
        </button>
    </div>

    <!-- BULK ACTIONS MODAL -->
    <div class="modal fade" id="bulkActionsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Bulk Actions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info d-flex align-items-center">
                        <i class="fas fa-info-circle me-2"></i>
                        <span>Selected products: <strong id="selectedCount">0</strong></span>
                    </div>

                    <div class="d-grid gap-2">
                        <button class="btn btn-warning" onclick="bulkApprove()">
                            <i class="fas fa-check-circle me-2"></i>Approve Selected
                        </button>
                        <button class="btn btn-danger" onclick="bulkReject()">
                            <i class="fas fa-times-circle me-2"></i>Reject Selected
                        </button>
                        <hr class="my-2">
                        <button class="btn btn-primary" onclick="bulkPriceChange()">
                            <i class="fas fa-tag me-2"></i>Bulk Price Change
                        </button>
                        <button class="btn btn-success" onclick="bulkActivate()">
                            <i class="fas fa-power-off me-2"></i>Activate Selected
                        </button>
                        <button class="btn btn-secondary" onclick="bulkDeactivate()">
                            <i class="fas fa-ban me-2"></i>Deactivate Selected
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- REJECTION REASON MODAL -->
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Reject Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="rejectForm">
                        <div class="mb-3">
                            <label for="rejection_reason" class="form-label fw-semibold">
                                Rejection Reason <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control" id="rejection_reason" rows="4"
                                      placeholder="Please provide a detailed reason for rejection..."
                                      required minlength="10"></textarea>
                            <div class="form-text">Minimum 10 characters required</div>
                        </div>
                        <input type="hidden" id="reject_product_id">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" onclick="submitRejection()">
                        <i class="fas fa-times me-2"></i>Reject Product
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // Initialize select all checkbox
            const $selectAll = $('#selectAll');
            const $bulkFab = $('#bulkFab');
            const $openBulkModalBtn = $('#openBulkModalBtn');

            function updateBulkUI() {
                const count = $('.product-checkbox:checked').length;
                $('#selectedCount').text(count);

                if (count > 0) {
                    $bulkFab.fadeIn(120);
                    $openBulkModalBtn.show();
                } else {
                    $bulkFab.fadeOut(120);
                    $openBulkModalBtn.hide();
                }

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

        function approveProduct(productId) {
            Swal.fire({
                title: 'Approve Product?',
                text: 'This product will be approved and become available in seller shop.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, approve it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Processing...',
                        text: 'Please wait',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Use the correct route with proper URL
                    $.ajax({
                        url: '/employee/wholesaler/approvals/' + productId + '/approve',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            notes: 'Approved from product list'
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Approved!',
                                    text: response.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire('Error!', response.message, 'error');
                            }
                        },
                        error: function(xhr) {
                            let errorMsg = 'Error approving product';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMsg = xhr.responseJSON.message;
                            }
                            Swal.fire('Error!', errorMsg, 'error');
                        }
                    });
                }
            });
        }

        function rejectProduct(productId) {
            $('#reject_product_id').val(productId);
            $('#rejection_reason').val('');

            // Show reject modal
            const rejectModal = new bootstrap.Modal(document.getElementById('rejectModal'));
            rejectModal.show();
        }

        function submitRejection() {
            const productId = $('#reject_product_id').val();
            const reason = $('#rejection_reason').val().trim();

            if (!reason || reason.length < 10) {
                Swal.fire('Error!', 'Please provide a rejection reason (minimum 10 characters)', 'error');
                return;
            }

            // Hide modal
            const rejectModal = bootstrap.Modal.getInstance(document.getElementById('rejectModal'));
            rejectModal.hide();

            // Show loading
            Swal.fire({
                title: 'Processing...',
                text: 'Please wait',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Use the correct route with proper URL
            $.ajax({
                url: '/employee/wholesaler/approvals/' + productId + '/reject',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    rejection_reason: reason
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Rejected!',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Error!', response.message, 'error');
                    }
                },
                error: function(xhr) {
                    let errorMsg = 'Error rejecting product';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    Swal.fire('Error!', errorMsg, 'error');
                }
            });
        }

        function bulkApprove() {
            const selectedIds = getSelectedIds();
            if (selectedIds.length === 0) {
                Swal.fire('Error!', 'Please select at least one product', 'error');
                return;
            }

            Swal.fire({
                title: 'Approve Selected Products?',
                text: 'This will approve ' + selectedIds.length + ' products.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, approve them!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Processing...',
                        text: 'Please wait',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    $.ajax({
                        url: '/employee/wholesaler/approvals/bulk-approve',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            product_ids: selectedIds,
                            notes: 'Bulk approved from product list'
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Approved!',
                                    text: response.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire('Error!', response.message, 'error');
                            }
                        },
                        error: function(xhr) {
                            let errorMsg = 'Error approving products';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMsg = xhr.responseJSON.message;
                            }
                            Swal.fire('Error!', errorMsg, 'error');
                        }
                    });
                }
            });
        }

        function bulkReject() {
            const selectedIds = getSelectedIds();
            if (selectedIds.length === 0) {
                Swal.fire('Error!', 'Please select at least one product', 'error');
                return;
            }

            Swal.fire({
                title: 'Reject Selected Products?',
                text: 'This will reject ' + selectedIds.length + ' products.',
                icon: 'warning',
                input: 'textarea',
                inputLabel: 'Rejection Reason',
                inputPlaceholder: 'Please provide a reason for rejection...',
                inputAttributes: {
                    'aria-label': 'Type your reason here',
                    'minlength': 10
                },
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                confirmButtonText: 'Yes, reject them!',
                cancelButtonText: 'Cancel',
                inputValidator: (value) => {
                    if (!value) {
                        return 'Rejection reason is required!';
                    }
                    if (value.length < 10) {
                        return 'Reason must be at least 10 characters long';
                    }
                }
            }).then((result) => {
                if (result.isConfirmed && result.value) {
                    // Show loading
                    Swal.fire({
                        title: 'Processing...',
                        text: 'Please wait',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    $.ajax({
                        url: '/employee/wholesaler/approvals/bulk-reject',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            product_ids: selectedIds,
                            rejection_reason: result.value
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Rejected!',
                                    text: response.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire('Error!', response.message, 'error');
                            }
                        },
                        error: function(xhr) {
                            let errorMsg = 'Error rejecting products';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMsg = xhr.responseJSON.message;
                            }
                            Swal.fire('Error!', errorMsg, 'error');
                        }
                    });
                }
            });
        }

        function bulkPriceChange() {
            const selectedIds = getSelectedIds();
            if (selectedIds.length === 0) {
                Swal.fire('Error!', 'Please select at least one product', 'error');
                return;
            }
            window.location.href = '{{ route("employee.wholesaler.price-changes.bulk.create") }}?product_ids=' + selectedIds.join(',');
        }

        function bulkActivate() {
            const selectedIds = getSelectedIds();
            if (selectedIds.length === 0) {
                Swal.fire('Error!', 'Please select at least one product', 'error');
                return;
            }

            Swal.fire({
                title: 'Activate Products?',
                text: 'Activate ' + selectedIds.length + ' products?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                confirmButtonText: 'Yes, activate'
            }).then((result) => {
                if (result.isConfirmed) {
                    bulkUpdate(selectedIds, 'activate');
                }
            });
        }

        function bulkDeactivate() {
            const selectedIds = getSelectedIds();
            if (selectedIds.length === 0) {
                Swal.fire('Error!', 'Please select at least one product', 'error');
                return;
            }

            Swal.fire({
                title: 'Deactivate Products?',
                text: 'Deactivate ' + selectedIds.length + ' products?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                confirmButtonText: 'Yes, deactivate'
            }).then((result) => {
                if (result.isConfirmed) {
                    bulkUpdate(selectedIds, 'deactivate');
                }
            });
        }

        function bulkUpdate(productIds, action) {
            Swal.fire({
                title: 'Processing...',
                text: 'Please wait',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

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
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Error!', response.message || 'Error updating products', 'error');
                    }
                },
                error: function(xhr) {
                    let errorMsg = 'Unknown error occurred';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    Swal.fire('Error!', errorMsg, 'error');
                }
            });
        }
    </script>
@endpush
