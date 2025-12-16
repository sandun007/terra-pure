@extends('wholesaler.layouts.app')
@push('title')
    Product List
@endpush
@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <style>
        :root {
            --main-theme-color: #667eea;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --info-color: #3b82f6;
        }

        .product-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 10px;
            border: 2px solid #e2e8f0;
            transition: all 0.3s ease;
            background: white;
        }

        .product-img:hover {
            transform: scale(1.1);
            border-color: var(--main-theme-color);
        }

        .status-badge {
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .status-active {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }

        .status-inactive {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }

        .stock-indicator {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .stock-high {
            background: #d1fae5;
            color: #065f46;
        }

        .stock-medium {
            background: #fef3c7;
            color: #92400e;
        }

        .stock-low {
            background: #fee2e2;
            color: #991b1b;
        }

        .price-display {
            font-weight: 700;
            color: var(--main-theme-color);
        }

        .wholesale-price {
            font-weight: 600;
            color: #10b981;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .btn-action {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            transition: all 0.3s ease;
            border: none;
            text-decoration: none;
        }

        .btn-view {
            background: linear-gradient(135deg, #334155 0%, #1e293b 100%);
            color: white;
        }

        .btn-edit {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
        }

        .btn-delete {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            cursor: pointer;
        }

        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .dataTables_wrapper {
            padding: 0;
        }

        .dataTables_filter input {
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 10px 15px 10px 40px;
            width: 300px !important;
            transition: all 0.3s ease;
            background: white;
        }

        .dataTables_filter {
            position: relative;
        }

        .dataTables_filter::before {
            content: '\f002';
            font-family: 'Font Awesome 6 Free', serif;
            font-weight: 900;
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            z-index: 1;
        }

        .dataTables_filter input:focus {
            border-color: var(--main-theme-color);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            outline: none;
        }

        .dataTables_length select {
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 8px 12px;
            background: white;
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
            background: linear-gradient(135deg, var(--main-theme-color) 0%, #3b82f6 100%) !important;
            color: white !important;
            border-color: transparent !important;
        }

        .dataTables_paginate .paginate_button.current {
            background: linear-gradient(135deg, var(--main-theme-color) 0%, #3b82f6 100%) !important;
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

        .page-header {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            padding: 2rem;
            border-radius: 12px;
            margin-bottom: 2rem;
        }

        .no-products {
            text-align: center;
            padding: 4rem 2rem;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-radius: 12px;
            border: 2px dashed #cbd5e1;
        }

        .no-products i {
            font-size: 4rem;
            color: #94a3b8;
            margin-bottom: 1rem;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .card-body {
            padding: 1.5rem;
        }

        .badge-custom {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .badge-light-custom {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            color: #475569;
            border: 1px solid #e2e8f0;
        }

        .alert {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .alert-success {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            border-left: 4px solid #10b981;
            color: #065f46;
        }

        .alert-danger {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            border-left: 4px solid #ef4444;
            color: #991b1b;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--main-theme-color) 0%, #3b82f6 100%);
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        }

        .btn-outline-secondary {
            border: 2px solid #e2e8f0;
            background: white;
            color: #475569;
            padding: 8px 20px;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .btn-outline-secondary:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
        }

        .btn-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            border: none;
            padding: 8px 20px;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(239, 68, 68, 0.3);
        }

        .modal-header {
            border-bottom: none;
        }

        .modal-content {
            border: none;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
        }

        .btn-close-white {
            filter: invert(1) grayscale(100%) brightness(200%);
        }

        @media (max-width: 768px) {
            .dataTables_filter input {
                width: 100% !important;
            }

            .action-buttons {
                flex-wrap: wrap;
                justify-content: center;
            }

            .page-header {
                padding: 1.5rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h3 class="fw-bold d-flex align-items-center">
                    <i class="fas fa-boxes me-2" style="font-size: 2rem;"></i>
                    Product List
                </h3>
                <p class="text-muted mb-0 d-flex align-items-center">
                    <i class="fas fa-list me-2" style="font-size: 1rem;"></i>
                    {{ $products->count() }} Products in Inventory
                </p>
            </div>
            <div class="page-actions">
                <a href="{{ route('wholesaler.products.create') }}" class="btn btn-primary d-flex align-items-center">
                    <i class="fas fa-plus-circle me-2"></i>
                    Add New Product
                </a>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle me-2"></i>
                <div>{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-circle me-2"></i>
                <div>{{ session('error') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($products->isEmpty())
        <div class="no-products">
            <i class="fas fa-box-open"></i>
            <h4 class="text-muted mb-3">No Products Found</h4>
            <p class="text-muted mb-4">Start by adding your first product to the inventory</p>
            <a href="{{ route('wholesaler.products.create') }}" class="btn btn-primary d-flex align-items-center mx-auto" style="width: fit-content;">
                <i class="fas fa-plus-circle me-2"></i>
                Add First Product
            </a>
        </div>
    @else
        <div class="card">
            <div class="card-body">
                <table id="product-table" class="display table table-hover" style="width:100%">
                    <thead>
                    <tr>
                        <th>Image</th>
                        <th>Product</th>
                        <th>Category</th>
                        <th>Brand</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Min Order</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($products as $product)
                        <tr>
                            <td>
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}"
                                         alt="{{ $product->name }}"
                                         class="product-img"
                                         data-bs-toggle="tooltip"
                                         title="{{ $product->name }}">
                                @else
                                    <div class="product-img d-flex align-items-center justify-content-center bg-light">
                                        <i class="fas fa-image text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="fw-bold">{{ $product->name }}</div>
                                <small class="text-muted d-flex align-items-center">
                                    <i class="fas fa-barcode me-1"></i>
                                    {{ $product->code }}
                                </small>
                            </td>
                            <td>
                                <span class="badge-custom badge-light-custom">
                                    <i class="fas fa-tag me-1"></i>
                                    {{ $product->category->name ?? 'N/A' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge-custom badge-light-custom">
                                    <i class="fas fa-tags me-1"></i>
                                    {{ $product->brand->name ?? 'N/A' }}
                                </span>
                            </td>
                            <td>
                                <div class="price-display">
                                    Rs. {{ number_format($product->display_price, 2) }}
                                </div>
                                <div class="wholesale-price small">
                                    <i class="fas fa-store me-1"></i>
                                    Wholesale: Rs. {{ number_format($product->wholesale_price, 2) }}
                                </div>
                            </td>
                            <td>
                                <span class="stock-indicator {{
                                    $product->qty > 50 ? 'stock-high' :
                                    ($product->qty > 10 ? 'stock-medium' : 'stock-low')
                                }}">
                                    <i class="fas {{
                                        $product->qty > 50 ? 'fa-check-circle' :
                                        ($product->qty > 10 ? 'fa-exclamation-triangle' : 'fa-exclamation-circle')
                                    }}"></i>
                                    {{ $product->qty }}
                                </span>
                            </td>
                            <td>
                                <span class="badge-custom badge-light-custom">
                                    <i class="fas fa-shopping-cart me-1"></i>
                                    {{ $product->min_order_quantity }}
                                </span>
                            </td>
                            <td>
                                <span class="status-badge {{ $product->status == 'active' ? 'status-active' : 'status-inactive' }}">
                                    <i class="fas {{ $product->status == 'active' ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                                    {{ ucfirst($product->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('wholesaler.products.show', $product->id) }}"
                                       class="btn-action btn-view"
                                       data-bs-toggle="tooltip"
                                       title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('wholesaler.products.edit', $product->id) }}"
                                       class="btn-action btn-edit"
                                       data-bs-toggle="tooltip"
                                       title="Edit Product">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button"
                                            class="btn-action btn-delete"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteModal{{ $product->id }}"
                                            title="Delete Product">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>

                                <!-- Delete Confirmation Modal -->
                                <div class="modal fade" id="deleteModal{{ $product->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white;">
                                                <h5 class="modal-title d-flex align-items-center">
                                                    <i class="fas fa-trash me-2"></i>
                                                    Confirm Delete
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body text-center py-4">
                                                <div class="mb-3">
                                                    <i class="fas fa-exclamation-triangle" style="font-size: 4rem; color: #ef4444;"></i>
                                                </div>
                                                <h5 class="mb-3">Delete "{{ $product->name }}"?</h5>
                                                <p class="text-muted mb-4">
                                                    This action cannot be undone. All product data will be permanently deleted.
                                                </p>
                                                <div class="d-flex justify-content-center gap-3">
                                                    <button type="button" class="btn btn-outline-secondary d-flex align-items-center" data-bs-dismiss="modal">
                                                        <i class="fas fa-times me-1"></i>
                                                        Cancel
                                                    </button>
                                                    <form action="{{ route('wholesaler.products.destroy', $product->id) }}"
                                                          method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger d-flex align-items-center">
                                                            <i class="fas fa-trash me-1"></i>
                                                            Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection

@push('script')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function () {
            // Initialize DataTable
            $('#product-table').DataTable({
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
                    { orderable: false, targets: [0, 8] },
                    { className: "align-middle", targets: "_all" }
                ],
                order: [[1, 'asc']],
                pageLength: 10,
                lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
                dom: '<"row"<"col-md-6"l><"col-md-6"f>>rt<"row"<"col-md-6"i><"col-md-6"p>>',
                initComplete: function() {
                    // Add custom class to inputs
                    $('.dataTables_filter input').addClass('form-control');
                    $('.dataTables_length select').addClass('form-select');
                }
            });

            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Auto-hide alerts after 5 seconds
            setTimeout(function() {
                $('.alert').alert('close');
            }, 5000);

            // Table row click effect
            $('#product-table tbody tr').on('click', function(e) {
                if (!$(e.target).closest('.action-buttons').length) {
                    $(this).toggleClass('table-active');
                }
            });

            // Export functionality
            $('#exportBtn').on('click', function() {
                alert('Export functionality would go here!');
            });

            // Bulk actions
            $('#selectAll').on('change', function() {
                $('.product-checkbox').prop('checked', this.checked);
            });

            $('.bulk-action-btn').on('click', function() {
                const action = $(this).data('action');
                const selected = $('.product-checkbox:checked');

                if (selected.length === 0) {
                    alert('Please select at least one product');
                    return;
                }

                const ids = selected.map(function() {
                    return $(this).val();
                }).get();

                // Here you would handle the bulk action
                console.log('Bulk action:', action, 'on products:', ids);
            });
        });
    </script>
@endpush
