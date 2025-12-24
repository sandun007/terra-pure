@extends('employee.layout.app')

@section('title', 'Wholesaler Products')
@section('subtitle', 'Manage wholesaler product prices')

@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --success-gradient: linear-gradient(135deg, #10b981 0%, #059669 100%);
            --warning-gradient: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            --danger-gradient: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            --info-gradient: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .page-header-enhanced {
            background: var(--primary-gradient);
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            color: white;
            box-shadow: var(--card-shadow);
        }

        .page-header-enhanced h1 {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .page-header-enhanced p {
            opacity: 0.9;
            margin-bottom: 0;
        }

        .card-enhanced {
            border: none;
            border-radius: 15px;
            box-shadow: var(--card-shadow);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .card-enhanced:hover {
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        }

        .card-header-enhanced {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            border-bottom: 2px solid #e2e8f0;
            padding: 1.25rem 1.5rem;
        }

        .product-img-enhanced {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 12px;
            border: 2px solid #e2e8f0;
            transition: all 0.3s ease;
            background: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .product-img-enhanced:hover {
            transform: scale(1.15);
            border-color: #667eea;
            box-shadow: 0 4px 16px rgba(102, 126, 234, 0.3);
        }

        .status-badge-enhanced {
            padding: 6px 16px;
            border-radius: 25px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .status-active-enhanced {
            background: var(--success-gradient);
            color: white;
        }

        .status-inactive-enhanced {
            background: var(--danger-gradient);
            color: white;
        }

        .price-display-enhanced {
            font-weight: 700;
            font-size: 1rem;
            color: #667eea;
            transition: all 0.3s ease;
        }

        .price-display-enhanced:hover {
            transform: scale(1.05);
        }

        .wholesale-price-enhanced {
            font-weight: 600;
            color: #10b981;
            font-size: 0.9rem;
        }

        .selling-price-enhanced {
            font-weight: 600;
            color: #f59e0b;
            font-size: 0.9rem;
        }

        .action-buttons-enhanced {
            display: flex;
            gap: 8px;
            justify-content: center;
        }

        .btn-action-enhanced {
            width: 38px;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            transition: all 0.3s ease;
            border: none;
            text-decoration: none;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .btn-view-enhanced {
            background: linear-gradient(135deg, #334155 0%, #1e293b 100%);
            color: white;
        }

        .btn-edit-enhanced {
            background: var(--warning-gradient);
            color: white;
        }

        .btn-action-enhanced:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
        }

        .form-control-enhanced {
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }

        .form-control-enhanced:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            outline: none;
        }

        .form-select-enhanced {
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .form-select-enhanced:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .btn-enhanced-primary {
            background: var(--primary-gradient);
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .btn-enhanced-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .btn-enhanced-success {
            background: var(--success-gradient);
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .btn-enhanced-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
            color: white;
        }

        .btn-enhanced-secondary {
            border: 2px solid #e2e8f0;
            background: white;
            color: #475569;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .btn-enhanced-secondary:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            color: #1e293b;
        }

        .dataTables_wrapper {
            padding: 1.5rem;
        }

        .dataTables_filter input {
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 10px 15px 10px 40px;
            width: 300px !important;
            transition: all 0.3s ease;
        }

        .dataTables_filter {
            position: relative;
        }

        .dataTables_filter::before {
            content: '\f002';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }

        .dataTables_filter input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .dataTables_length select {
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 8px 12px;
        }

        .dataTables_paginate .paginate_button {
            border: 2px solid #e2e8f0 !important;
            border-radius: 10px !important;
            margin: 0 3px !important;
            padding: 8px 15px !important;
            transition: all 0.3s ease !important;
            background: white !important;
            color: #475569 !important;
        }

        .dataTables_paginate .paginate_button:hover {
            background: var(--primary-gradient) !important;
            color: white !important;
            border-color: transparent !important;
        }

        .dataTables_paginate .paginate_button.current {
            background: var(--primary-gradient) !important;
            color: white !important;
            border-color: transparent !important;
        }

        table.dataTable tbody tr {
            transition: all 0.3s ease;
        }

        table.dataTable tbody tr:hover {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .modal-content-enhanced {
            border: none;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }

        .modal-header-enhanced {
            background: var(--primary-gradient);
            color: white;
            border-bottom: none;
            padding: 1.5rem;
        }

        .modal-header-enhanced .btn-close {
            filter: brightness(0) invert(1);
        }

        .modal-body-enhanced {
            padding: 2rem;
        }

        .modal-footer-enhanced {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-top: none;
            padding: 1.25rem 2rem;
        }

        .badge-enhanced {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .badge-category-enhanced {
            background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%);
            color: #4338ca;
            border: 1px solid #a5b4fc;
        }

        .badge-count-enhanced {
            background: var(--primary-gradient);
            color: white;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
        }

        .alert-enhanced {
            border: none;
            border-radius: 12px;
            padding: 1.25rem 1.5rem;
            box-shadow: var(--card-shadow);
            border-left: 4px solid;
        }

        .alert-info-enhanced {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            border-left-color: #3b82f6;
            color: #1e40af;
        }

        .alert-warning-enhanced {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border-left-color: #f59e0b;
            color: #92400e;
        }

        .form-check-input-enhanced {
            width: 1.25rem;
            height: 1.25rem;
            border: 2px solid #cbd5e1;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .form-check-input-enhanced:checked {
            background-color: #667eea;
            border-color: #667eea;
        }

        .form-check-input-enhanced:focus {
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
        }

        .no-products-enhanced {
            text-align: center;
            padding: 4rem 2rem;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-radius: 15px;
            border: 2px dashed #cbd5e1;
        }

        .no-products-enhanced i {
            font-size: 5rem;
            color: #94a3b8;
            margin-bottom: 1.5rem;
        }

        @media (max-width: 768px) {
            .page-header-enhanced {
                padding: 1.5rem;
            }
            .dataTables_filter input {
                width: 100% !important;
            }
            .action-buttons-enhanced {
                flex-wrap: wrap;
            }
            .btn-enhanced-primary,
            .btn-enhanced-success,
            .btn-enhanced-secondary {
                padding: 0.5rem 1rem;
                font-size: 0.9rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid py-4">
        {{-- Enhanced Page Header --}}
        <div class="page-header-enhanced">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h1 class="d-flex align-items-center mb-2">
                        <i class="fas fa-boxes me-3"></i>
                        Wholesaler Products
                    </h1>
                    <p class="mb-0">
                        <i class="fas fa-chart-line me-2"></i>
                        Manage and update {{ $products->total() }} wholesaler product prices
                    </p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <button class="btn btn-enhanced-success" id="exportToGoogleSheets">
                        <i class="fab fa-google me-2"></i>Export to Sheets
                    </button>
                    <button class="btn btn-enhanced-primary" data-bs-toggle="modal" data-bs-target="#bulkPriceModal">
                        <i class="fas fa-edit me-2"></i>Bulk Update
                    </button>
                </div>
            </div>
        </div>

        {{-- Enhanced Filter Card --}}
        <div class="card-enhanced mb-4">
            <div class="card-header-enhanced">
                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-filter me-2 text-primary"></i>Filter Products
                </h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('employee.wholesaler.products') }}" id="filterForm">
                    <div class="row g-3">
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label fw-semibold small">Search Products</label>
                            <input type="text"
                                   class="form-control form-control-enhanced"
                                   placeholder="Name, code, barcode..."
                                   name="search"
                                   value="{{ request('search', '') }}">
                        </div>
                        <div class="col-lg-2 col-md-6">
                            <label class="form-label fw-semibold small">Status</label>
                            <select class="form-select form-select-enhanced" name="status">
                                <option value="all" {{ request('status', 'all') == 'all' ? 'selected' : '' }}>All Status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label fw-semibold small">Wholesaler</label>
                            <select class="form-select form-select-enhanced" name="wholesaler_id">
                                <option value="all">All Wholesalers</option>
                                @foreach($wholesalers as $wholesaler)
                                    <option value="{{ $wholesaler->id }}" {{ request('wholesaler_id') == $wholesaler->id ? 'selected' : '' }}>
                                        {{ $wholesaler->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <label class="form-label small d-block">&nbsp;</label>
                            <button type="submit" class="btn btn-enhanced-primary me-2">
                                <i class="fas fa-search me-1"></i>Search
                            </button>
                            <a href="{{ route('employee.wholesaler.products') }}" class="btn btn-enhanced-secondary">
                                <i class="fas fa-redo me-1"></i>Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Enhanced Products Table --}}
        @if($products->isEmpty())
            <div class="no-products-enhanced">
                <i class="fas fa-box-open"></i>
                <h4 class="mb-3">No Products Found</h4>
                <p class="text-muted mb-0">Try adjusting your filters to see products</p>
            </div>
        @else
            <div class="card-enhanced">
                <div class="card-header-enhanced">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-list me-2 text-primary"></i>Products List
                        </h5>
                        <span class="badge-enhanced badge-count-enhanced">
                        {{ $products->total() }} Products
                    </span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="productsTable">
                            <thead class="bg-light">
                            <tr>
                                <th width="50" class="ps-4">
                                    <input type="checkbox" id="selectAll" class="form-check-input form-check-input-enhanced">
                                </th>
                                <th width="80">Image</th>
                                <th>Product Details</th>
                                <th>Wholesaler</th>
                                <th>Category</th>
                                <th class="text-end">Selling Price</th>
                                <th class="text-end">Wholesale Price</th>
                                <th class="text-end">Display Price</th>
                                <th class="text-center">Status</th>
                                <th class="text-center" width="120">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($products as $product)
                                <tr>
                                    <td class="ps-4">
                                        <input type="checkbox" class="form-check-input form-check-input-enhanced product-checkbox" value="{{ $product->id }}">
                                    </td>
                                    <td>
                                        @if($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}"
                                                 alt="{{ $product->name }}"
                                                 class="product-img-enhanced"
                                                 data-bs-toggle="tooltip"
                                                 title="{{ $product->name }}">
                                        @else
                                            <div class="product-img-enhanced d-flex align-items-center justify-content-center bg-light">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $product->name }}</div>
                                        <div class="small text-muted mt-1">
                                            <i class="fas fa-barcode me-1"></i>
                                            <span class="badge-enhanced" style="background: #f1f5f9; color: #475569;">{{ $product->code }}</span>
                                            @if($product->barcode)
                                                <span class="badge-enhanced ms-1" style="background: #f1f5f9; color: #475569;">{{ $product->barcode }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-semibold text-dark">{{ $product->wholesaler->name ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                    <span class="badge-enhanced badge-category-enhanced">
                                        <i class="fas fa-tag me-1"></i>
                                        {{ $product->category->name ?? 'N/A' }}
                                    </span>
                                    </td>
                                    <td class="text-end">
                                        <div class="selling-price-enhanced"
                                             data-field="selling_price"
                                             data-id="{{ $product->id }}">
                                            <i class="fas fa-dollar-sign me-1"></i>
                                            Rs. {{ number_format($product->selling_price, 2) }}
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <div class="wholesale-price-enhanced"
                                             data-field="wholesale_price"
                                             data-id="{{ $product->id }}">
                                            <i class="fas fa-store me-1"></i>
                                            Rs. {{ number_format($product->wholesale_price, 2) }}
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <div class="price-display-enhanced"
                                             data-field="display_price"
                                             data-id="{{ $product->id }}">
                                            <i class="fas fa-tag me-1"></i>
                                            Rs. {{ number_format($product->display_price, 2) }}
                                        </div>
                                    </td>
                                    <td class="text-center">
                                    <span class="status-badge-enhanced {{ $product->status == 'active' ? 'status-active-enhanced' : 'status-inactive-enhanced' }}">
                                        <i class="fas {{ $product->status == 'active' ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                                        {{ ucfirst($product->status) }}
                                    </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="action-buttons-enhanced">
                                            <a href="{{ route('employee.wholesaler.product-view', $product->id) }}"
                                               class="btn-action-enhanced btn-view-enhanced"
                                               data-bs-toggle="tooltip"
                                               title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button class="btn-action-enhanced btn-edit-enhanced edit-price-btn"
                                                    data-id="{{ $product->id }}"
                                                    data-name="{{ $product->name }}"
                                                    data-selling="{{ $product->selling_price }}"
                                                    data-wholesale="{{ $product->wholesale_price }}"
                                                    data-display="{{ $product->display_price }}"
                                                    data-bs-toggle="tooltip"
                                                    title="Edit Prices">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if($products->hasPages())
                        <div class="card-footer bg-white border-top p-3">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                                <div class="text-muted small">
                                    Showing <strong>{{ $products->firstItem() }}</strong> to
                                    <strong>{{ $products->lastItem() }}</strong> of
                                    <strong>{{ $products->total() }}</strong> entries
                                </div>
                                <div>
                                    {{ $products->links() }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>

    {{-- Enhanced Edit Price Modal --}}
    <div class="modal fade" id="editPriceModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-content-enhanced">
                <div class="modal-header modal-header-enhanced">
                    <h5 class="modal-title fw-bold">
                        <i class="fas fa-edit me-2"></i>Edit Product Prices
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="priceUpdateForm">
                    @csrf
                    <input type="hidden" id="productId" name="product_id">

                    <div class="modal-body modal-body-enhanced">
                        <div class="alert-enhanced alert-info-enhanced mb-4">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-info-circle fa-2x me-3"></i>
                                <div>
                                    <strong id="productName"></strong>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-dollar-sign me-1 text-warning"></i>
                                Selling Price <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">Rs.</span>
                                <input type="number" step="0.01" class="form-control form-control-enhanced"
                                       id="sellingPrice" name="selling_price" placeholder="0.00" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-store me-1 text-success"></i>
                                Wholesale Price <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">Rs.</span>
                                <input type="number" step="0.01" class="form-control form-control-enhanced"
                                       id="wholesalePrice" name="wholesale_price" placeholder="0.00" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-tag me-1 text-primary"></i>
                                Display Price <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">Rs.</span>
                                <input type="number" step="0.01" class="form-control form-control-enhanced"
                                       id="displayPrice" name="display_price" placeholder="0.00" required>
                            </div>
                        </div>

                        <div class="mb-0">
                            <label class="form-label fw-bold">
                                <i class="fas fa-comment me-1"></i>
                                Notes (Optional)
                            </label>
                            <textarea class="form-control form-control-enhanced" name="notes" rows="3"
                                      placeholder="Enter reason for price change..."></textarea>
                        </div>
                    </div>

                    <div class="modal-footer modal-footer-enhanced">
                        <button type="button" class="btn btn-enhanced-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>Cancel
                        </button>
                        <button type="submit" class="btn btn-enhanced-primary">
                            <i class="fas fa-save me-1"></i>Update Prices
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Enhanced Bulk Price Modal --}}
    <div class="modal fade" id="bulkPriceModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-content-enhanced">
                <div class="modal-header modal-header-enhanced">
                    <h5 class="modal-title fw-bold">
                        <i class="fas fa-edit me-2"></i>Bulk Price Update
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="bulkPriceForm">
                    @csrf
                    <div class="modal-body modal-body-enhanced">
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-list me-1"></i>
                                Price Type <span class="text-danger">*</span>
                            </label>
                            <select class="form-select form-select-enhanced" name="price_type" required>
                                <option value="">Select price type...</option>
                                <option value="selling_price">Selling Price</option>
                                <option value="wholesale_price">Wholesale Price</option>
                                <option value="display_price">Display Price</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-cog me-1"></i>
                                Update Type <span class="text-danger">*</span>
                            </label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="update_type" value="fixed" id="fixedType" checked>
                                <label class="btn btn-outline-primary" for="fixedType">
                                    <i class="fas fa-dollar-sign me-1"></i>Fixed Amount
                                </label>

                                <input type="radio" class="btn-check" name="update_type" value="percentage" id="percentageType">
                                <label class="btn btn-outline-primary" for="percentageType">
                                    <i class="fas fa-percent me-1"></i>Percentage
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold" id="valueLabel">
                                Amount <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="number"
                                       step="0.01"
                                       class="form-control form-control-enhanced"
                                       name="value"
                                       placeholder="0.00"
                                       required>
                                <span class="input-group-text bg-light" id="valueSuffix">Rs.</span>
                            </div>
                            <small class="text-muted" id="valueHelp">Enter the new price amount</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-comment me-1"></i>
                                Notes (Optional)
                            </label>
                            <textarea class="form-control form-control-enhanced"
                                      name="notes"
                                      rows="3"
                                      placeholder="Enter reason for bulk update..."></textarea>
                        </div>

                        <div class="alert-enhanced alert-warning-enhanced mb-0">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
                                <div>
                                    <strong>Selected Products: <span id="selectedCount" class="text-dark">0</span></strong>
                                    <p class="mb-0 small">This action will update prices for all selected products</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer modal-footer-enhanced">
                        <button type="button" class="btn btn-enhanced-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>Cancel
                        </button>
                        <button type="submit" class="btn btn-enhanced-primary">
                            <i class="fas fa-save me-1"></i>Update Selected Products
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#productsTable').DataTable({
                responsive: true,
                language: {
                    search: "",
                    searchPlaceholder: "Search products...",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ products",
                    infoEmpty: "No products available",
                    zeroRecords: "No matching products found",
                    paginate: {
                        first: '<i class="fas fa-angle-double-left"></i>',
                        previous: '<i class="fas fa-angle-left"></i>',
                        next: '<i class="fas fa-angle-right"></i>',
                        last: '<i class="fas fa-angle-double-right"></i>'
                    }
                },
                columnDefs: [
                    { orderable: false, targets: [0, 1, 9] },
                    { className: "align-middle", targets: "_all" }
                ],
                order: [[2, 'asc']],
                pageLength: 10,
                lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
                dom: '<"row"<"col-md-6"l><"col-md-6"f>>rt<"row"<"col-md-6"i><"col-md-6"p>>',
                initComplete: function() {
                    $('.dataTables_filter input').addClass('form-control');
                    $('.dataTables_length select').addClass('form-select');
                }
            });

            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(el => new bootstrap.Tooltip(el));

            // Edit Price Modal Handler
            $('.edit-price-btn').on('click', function() {
                const data = {
                    id: $(this).data('id'),
                    name: $(this).data('name'),
                    selling: $(this).data('selling'),
                    wholesale: $(this).data('wholesale'),
                    display: $(this).data('display')
                };

                $('#productId').val(data.id);
                $('#productName').text(data.name);
                $('#sellingPrice').val(data.selling);
                $('#wholesalePrice').val(data.wholesale);
                $('#displayPrice').val(data.display);

                new bootstrap.Modal('#editPriceModal').show();
            });

            // Update Price Form Submission
            $('#priceUpdateForm').on('submit', function(e) {
                e.preventDefault();
                const $form = $(this);
                const $submitBtn = $form.find('button[type="submit"]');
                const originalBtnText = $submitBtn.html();

                $submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>Updating...');

                $.ajax({
                    url: "{{ route('employee.wholesaler.price-product-update') }}",
                    type: "POST",
                    data: $form.serialize(),
                    success: function(response) {
                        if (response.success) {
                            const productId = $('#productId').val();
                            updatePriceFields(productId, response.product);
                            bootstrap.Modal.getInstance('#editPriceModal').hide();
                            showAlert('success', response.message);
                            $form[0].reset();
                        }
                    },
                    error: function(xhr) {
                        showAlert('error', xhr.responseJSON?.message || 'Error updating price');
                    },
                    complete: function() {
                        $submitBtn.prop('disabled', false).html(originalBtnText);
                    }
                });
            });

            // Update price fields in table
            function updatePriceFields(productId, product) {
                $(`[data-id="${productId}"][data-field="selling_price"]`)
                    .html('<i class="fas fa-dollar-sign me-1"></i>Rs. ' + parseFloat(product.selling_price).toFixed(2));
                $(`[data-id="${productId}"][data-field="wholesale_price"]`)
                    .html('<i class="fas fa-store me-1"></i>Rs. ' + parseFloat(product.wholesale_price).toFixed(2));
                $(`[data-id="${productId}"][data-field="display_price"]`)
                    .html('<i class="fas fa-tag me-1"></i>Rs. ' + parseFloat(product.display_price).toFixed(2));
            }

            // Select All Checkbox
            $('#selectAll').on('change', function() {
                $('.product-checkbox').prop('checked', this.checked);
                updateSelectedCount();
            });

            // Individual Checkbox
            $('.product-checkbox').on('change', function() {
                const totalCheckboxes = $('.product-checkbox').length;
                const checkedCheckboxes = $('.product-checkbox:checked').length;
                $('#selectAll').prop('checked', totalCheckboxes === checkedCheckboxes);
                updateSelectedCount();
            });

            // Update Selected Count
            function updateSelectedCount() {
                const count = $('.product-checkbox:checked').length;
                $('#selectedCount').text(count);
            }

            // Bulk Price Update Type Change
            $('input[name="update_type"]').on('change', function() {
                const type = $(this).val();
                const isFixed = type === 'fixed';

                $('#valueLabel').html((isFixed ? 'Amount' : 'Percentage') + ' <span class="text-danger">*</span>');
                $('#valueSuffix').text(isFixed ? 'Rs.' : '%');
                $('#valueHelp').text(isFixed
                    ? 'Enter the new price amount'
                    : 'Enter percentage (e.g., 10 for 10% increase, -10 for 10% decrease)');
            });

            // Bulk Price Update Form Submission
            $('#bulkPriceForm').on('submit', function(e) {
                e.preventDefault();

                const selectedProducts = $('.product-checkbox:checked').map(function() {
                    return $(this).val();
                }).get();

                if (selectedProducts.length === 0) {
                    showAlert('warning', 'Please select at least one product');
                    return;
                }

                const $form = $(this);
                const $submitBtn = $form.find('button[type="submit"]');
                const originalBtnText = $submitBtn.html();

                $submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>Updating...');

                const formData = $form.serializeArray();
                formData.push({ name: 'products', value: JSON.stringify(selectedProducts) });

                $.ajax({
                    url: "{{ route('employee.wholesaler.bulk-price-update') }}",
                    type: "POST",
                    data: $.param(formData),
                    success: function(response) {
                        if (response.success) {
                            bootstrap.Modal.getInstance('#bulkPriceModal').hide();
                            showAlert('success', response.message);
                            setTimeout(() => location.reload(), 1500);
                        }
                    },
                    error: function(xhr) {
                        showAlert('error', xhr.responseJSON?.message || 'Error updating prices');
                        $submitBtn.prop('disabled', false).html(originalBtnText);
                    }
                });
            });

            // Alert Function using SweetAlert2
            function showAlert(type, message) {
                const icons = {
                    success: 'success',
                    error: 'error',
                    warning: 'warning',
                    info: 'info'
                };

                Swal.fire({
                    icon: icons[type] || 'info',
                    title: type.charAt(0).toUpperCase() + type.slice(1) + '!',
                    text: message,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            }

            // Export to Google Sheets
            $('#exportToGoogleSheets').on('click', function() {
                const $btn = $(this);
                const originalHtml = $btn.html();

                $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Exporting...');

                const tableData = [];
                const headers = ['Product Name', 'Code', 'Barcode', 'Wholesaler', 'Category', 'Selling Price', 'Wholesale Price', 'Display Price', 'Status'];
                tableData.push(headers);

                $('#productsTable tbody tr').each(function() {
                    if ($(this).find('td').length > 1) {
                        const row = [];
                        row.push($(this).find('td:eq(2) .fw-bold').text().trim());
                        row.push($(this).find('td:eq(2) .badge-enhanced').first().text().trim());
                        row.push($(this).find('td:eq(2) .badge-enhanced').eq(1).text().trim() || 'N/A');
                        row.push($(this).find('td:eq(3)').text().trim());
                        row.push($(this).find('td:eq(4)').text().trim());
                        row.push($(this).find('td:eq(5)').text().trim().replace('Rs. ', ''));
                        row.push($(this).find('td:eq(6)').text().trim().replace('Rs. ', ''));
                        row.push($(this).find('td:eq(7)').text().trim().replace('Rs. ', ''));
                        row.push($(this).find('td:eq(8)').text().trim());
                        tableData.push(row);
                    }
                });

                const csvContent = tableData.map(row => row.join(',')).join('\n');
                const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
                const link = document.createElement('a');
                const url = URL.createObjectURL(blob);

                link.setAttribute('href', url);
                link.setAttribute('download', `wholesaler_products_${new Date().getTime()}.csv`);
                link.style.visibility = 'hidden';

                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);

                setTimeout(() => {
                    $btn.prop('disabled', false).html(originalHtml);
                    showAlert('success', 'Products exported successfully! You can now import this CSV into Google Sheets.');
                }, 1000);
            });
        });
    </script>
@endpush
