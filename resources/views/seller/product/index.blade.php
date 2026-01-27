@extends('seller.layout.app')

@push('title')
    My Products
@endpush

@push('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #6366f1;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --info-color: #3b82f6;
        }

        .dashboard-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, #3b82f6 100%);
            border-radius: 12px;
            padding: 30px;
            color: white;
            margin-bottom: 30px;
            box-shadow: 0 10px 25px rgba(99, 102, 241, 0.2);
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border-left: 4px solid;
            height: 100%;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .stat-card.primary { border-left-color: var(--primary-color); }
        .stat-card.success { border-left-color: var(--success-color); }
        .stat-card.warning { border-left-color: var(--warning-color); }
        .stat-card.danger { border-left-color: var(--danger-color); }

        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 16px;
        }

        .stat-card.primary .stat-icon {
            background: rgba(99, 102, 241, 0.1);
            color: var(--primary-color);
        }

        .stat-card.success .stat-icon {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
        }

        .stat-card.warning .stat-icon {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning-color);
        }

        .stat-card.danger .stat-icon {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger-color);
        }

        .product-table-container {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            margin-top: 24px;
        }

        .table-header {
            background: linear-gradient(90deg, #f8fafc, white);
            padding: 24px;
            border-bottom: 1px solid #e2e8f0;
        }

        .action-btn {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            border: none;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .btn-view { background: linear-gradient(135deg, #3b82f6, #2563eb); }
        .btn-edit { background: linear-gradient(135deg, #f59e0b, #d97706); }
        .btn-delete { background: linear-gradient(135deg, #ef4444, #dc2626); }
        .btn-status { background: linear-gradient(135deg, #10b981, #059669); }

        .product-image-wrapper {
            width: 48px;
            height: 48px;
            border-radius: 8px;
            overflow: hidden;
            border: 2px solid #e2e8f0;
        }

        .product-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            gap: 6px;
        }

        .status-badge.active {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .status-badge.inactive {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger-color);
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .stock-badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }

        .stock-in {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
        }

        .stock-low {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning-color);
        }

        .stock-out {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger-color);
        }

        #product-table th {
            background: #f8fafc;
            font-weight: 600;
            color: #334155;
            padding: 16px;
        }

        #product-table td {
            padding: 16px;
            vertical-align: middle;
        }

        #product-table tbody tr:hover {
            background: rgba(99, 102, 241, 0.03);
        }
    </style>
@endpush

@section('content')
    <!-- Dashboard Header -->
    <div class="dashboard-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-2">Product Management</h4>
                <p class="mb-0 opacity-90">Manage your inventory with ease</p>
            </div>
            <a href="{{ route('seller.product.create') }}" class="btn btn-light">
                <i class="fas fa-plus me-2"></i>New Product
            </a>
        </div>
    </div>

    <!-- Statistics Dashboard -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card primary">
                <div class="stat-icon">
                    <i class="fas fa-boxes"></i>
                </div>
                <h3 class="mb-2">{{ $products->count() }}</h3>
                <p class="text-muted mb-0">Total Products</p>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card success">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h3 class="mb-2">{{ $products->where('status', 'active')->count() }}</h3>
                <p class="text-muted mb-0">Active Products</p>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card warning">
                <div class="stat-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                @php
                    $lowStockCount = $products->filter(function($product) {
                        return $product->qty <= $product->min_qty && $product->qty > 0;
                    })->count();
                @endphp
                <h3 class="mb-2">{{ $lowStockCount }}</h3>
                <p class="text-muted mb-0">Low Stock</p>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card danger">
                <div class="stat-icon">
                    <i class="fas fa-ban"></i>
                </div>
                <h3 class="mb-2">{{ $products->where('qty', 0)->count() }}</h3>
                <p class="text-muted mb-0">Out of Stock</p>
            </div>
        </div>
    </div>

    <!-- Products Table -->
    <div class="product-table-container">
        <div class="table-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0 fw-semibold">Product Inventory</h5>
                    <p class="text-muted mb-0 small">Manage your product catalog</p>
                </div>
                <button class="btn btn-outline-primary btn-sm" onclick="location.reload()">
                    <i class="fas fa-sync-alt me-1"></i> Refresh
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table id="product-table" class="display table" style="width:100%">
                <thead>
                <tr>
                    <th>Product</th>
                    <th>Code</th>
                    <th>Category</th>
                    <th>Brand</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($products as $product)
                    @php
                        $stockStatus = $product->qty == 0 ? 'stock-out' : ($product->qty <= $product->min_qty ? 'stock-low' : 'stock-in');
                        $stockText = $product->qty == 0 ? 'Out of Stock' : ($product->qty <= $product->min_qty ? 'Low Stock' : 'In Stock');
                    @endphp
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="product-image-wrapper">
                                    <img src="{{ $product->image_url }}"
                                         alt="{{ $product->name }}"
                                         class="product-image">
                                </div>
                                <div>
                                    <h6 class="mb-1 fw-semibold">{{ Str::limit($product->name, 30) }}</h6>
                                    <small class="text-muted">{{ Str::limit($product->description, 40) }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">{{ $product->code }}</span>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">
                                {{ $product->categoryModel->name ?? 'N/A' }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">
                                {{ $product->brandModel->name ?? 'N/A' }}
                            </span>
                        </td>
                        <td>
                            <div>
                                <span class="fw-bold">Rs. {{ number_format($product->final_price, 2) }}</span>
                                @if($product->hasDiscount())
                                    <br><small class="text-muted text-decoration-line-through">
                                        Rs. {{ number_format($product->display_price, 2) }}
                                    </small>
                                    <span class="badge bg-danger ms-1">{{ $product->discount }}% OFF</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="d-flex flex-column gap-1">
                                <span class="stock-badge {{ $stockStatus }}">
                                    <i class="fas {{ $stockStatus == 'stock-out' ? 'fa-times' : ($stockStatus == 'stock-low' ? 'fa-exclamation' : 'fa-check') }}"></i>
                                    {{ $stockText }}
                                </span>
                                <small class="text-muted">{{ $product->qty }} units</small>
                            </div>
                        </td>
                        <td>
                            <span class="status-badge {{ $product->status }}">
                                <i class="fas fa-circle" style="font-size: 8px;"></i>
                                {{ ucfirst($product->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <!-- Edit Button -->
                                <a href="{{ route('seller.product.edit', $product->id) }}"
                                   class="action-btn btn-edit"
                                   title="Edit Product">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <!-- Status Toggle -->
                                <button class="action-btn btn-status"
                                        onclick="toggleStatus({{ $product->id }}, '{{ $product->status }}')"
                                        title="{{ $product->status == 'active' ? 'Deactivate' : 'Activate' }}">
                                    <i class="fas fa-power-off"></i>
                                </button>

                                <!-- Delete Button -->
                                <button class="action-btn btn-delete"
                                        onclick="deleteProduct({{ $product->id }}, '{{ addslashes($product->name) }}')"
                                        title="Delete Product">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No products found</h5>
                            <p class="text-muted">Start by adding your first product</p>
                            <a href="{{ route('seller.product.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Add Product
                            </a>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function () {
            @if($products->count() > 0)
            $('#product-table').DataTable({
                "pageLength": 10,
                "order": [[0, 'asc']],
                "language": {
                    "search": "Search products:",
                    "lengthMenu": "Show _MENU_ products",
                    "info": "Showing _START_ to _END_ of _TOTAL_ products"
                }
            });
            @endif
        });

        function toggleStatus(productId, currentStatus) {
            const newStatus = currentStatus === 'active' ? 'inactive' : 'active';

            Swal.fire({
                title: `${newStatus === 'active' ? 'Activate' : 'Deactivate'} Product?`,
                text: `Are you sure you want to ${newStatus} this product?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#6366f1',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, proceed!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/seller/product/${productId}/status`,
                        type: 'POST',
                        data: {
                            '_token': '{{ csrf_token() }}',
                            'status': newStatus
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Updated!',
                                text: response.message,
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => location.reload());
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Failed to update status',
                                confirmButtonColor: '#6366f1'
                            });
                        }
                    });
                }
            });
        }

        function deleteProduct(productId, productName) {
            Swal.fire({
                title: 'Delete Product?',
                html: `Are you sure you want to delete<br><strong>"${productName}"</strong>?<br><br>This action cannot be undone.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/seller/product/${productId}`,
                        type: 'DELETE',
                        data: {
                            '_token': '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: response.message,
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => location.reload());
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Failed to delete product',
                                confirmButtonColor: '#6366f1'
                            });
                        }
                    });
                }
            });
        }
    </script>
@endpush
