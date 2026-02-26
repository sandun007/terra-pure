@extends('employee.layout.app')

@push('title')
    Seller Products Management
@endpush

@push('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <style>
        :root {
            --primary: #4f46e5;
            --primary-light: #6366f1;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #3b82f6;
            --dark: #1f2937;
            --light: #f9fafb;
            --border: #e5e7eb;
        }

        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 25px;
            color: white;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            border-left: 4px solid;
            transition: all 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.12);
        }

        .stat-card.primary { border-left-color: var(--primary); }
        .stat-card.success { border-left-color: var(--success); }
        .stat-card.warning { border-left-color: var(--warning); }
        .stat-card.danger { border-left-color: var(--danger); }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
        }

        .stat-icon.primary { background: linear-gradient(135deg, var(--primary), var(--primary-light)); }
        .stat-icon.success { background: linear-gradient(135deg, var(--success), #34d399); }
        .stat-icon.warning { background: linear-gradient(135deg, var(--warning), #fbbf24); }
        .stat-icon.danger { background: linear-gradient(135deg, var(--danger), #f87171); }

        .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 5px;
        }

        .stat-label {
            color: #6b7280;
            font-size: 14px;
        }

        .filter-section {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }

        .product-image {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid var(--border);
        }

        .badge-status {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }

        .badge-active { background: #d1fae5; color: #065f46; }
        .badge-inactive { background: #fee2e2; color: #991b1b; }
        .badge-pending { background: #fef3c7; color: #92400e; }
        .badge-approved { background: #dbeafe; color: #1e40af; }

        .action-buttons {
            display: flex;
            gap: 5px;
            justify-content: center;
        }

        .action-btn {
            width: 35px;
            height: 35px;
            border-radius: 8px;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
            color: white;
            text-decoration: none;
        }

        .action-btn:hover {
            transform: translateY(-2px);
        }

        .btn-view { background: var(--info); }
        .btn-edit { background: var(--warning); }
        .btn-price { background: var(--primary); }
        .btn-approve { background: var(--success); }
        .btn-reject { background: var(--danger); }

        .seller-info {
            display: flex;
            flex-direction: column;
        }

        .seller-name {
            font-weight: 600;
            color: var(--dark);
        }

        .seller-code {
            font-size: 11px;
            color: #6b7280;
        }

        .price-info {
            display: flex;
            flex-direction: column;
        }

        .current-price {
            font-weight: 600;
            color: var(--dark);
        }

        .original-price {
            font-size: 11px;
            color: #9ca3af;
            text-decoration: line-through;
        }

        .discount-badge {
            background: var(--danger);
            color: white;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 600;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="text-white mb-2">
                        <i class="fas fa-store-alt me-2"></i> Seller Products Management
                    </h4>
                    <p class="text-white-50 mb-0">Manage all products from sellers, approve new products, and update prices</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('employee.seller.products.export') }}" class="btn btn-light">
                        <i class="fas fa-file-export me-2"></i>Export
                    </a>
                    <button class="btn btn-light" onclick="showBulkPriceModal()">
                        <i class="fas fa-tags me-2"></i>Bulk Price Update
                    </button>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        @php
            $totalProducts = $products->total();
            $pendingApproval = \App\Models\SellerProduct::whereNull('approved_by')->where('status', 'active')->count();
            $approvedProducts = \App\Models\SellerProduct::whereNotNull('approved_by')->count();
            $pricePending = \App\Models\EmployeePriceChange::where('status', 'pending')->where('product_type', 'seller')->count();
        @endphp

        <div class="stats-grid">
            <div class="stat-card primary">
                <div class="stat-header">
                    <div class="stat-icon primary">
                        <i class="fas fa-boxes"></i>
                    </div>
                    <span class="badge bg-primary bg-opacity-10 text-primary">{{ $totalProducts }}</span>
                </div>
                <div class="stat-value">{{ $totalProducts }}</div>
                <div class="stat-label">Total Seller Products</div>
            </div>

            <div class="stat-card warning">
                <div class="stat-header">
                    <div class="stat-icon warning">
                        <i class="fas fa-clock"></i>
                    </div>
                    <span class="badge bg-warning bg-opacity-10 text-warning">{{ $pendingApproval }}</span>
                </div>
                <div class="stat-value">{{ $pendingApproval }}</div>
                <div class="stat-label">Pending Approval</div>
            </div>

            <div class="stat-card success">
                <div class="stat-header">
                    <div class="stat-icon success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <span class="badge bg-success bg-opacity-10 text-success">{{ $approvedProducts }}</span>
                </div>
                <div class="stat-value">{{ $approvedProducts }}</div>
                <div class="stat-label">Approved Products</div>
            </div>

            <div class="stat-card danger">
                <div class="stat-header">
                    <div class="stat-icon danger">
                        <i class="fas fa-tag"></i>
                    </div>
                    <span class="badge bg-danger bg-opacity-10 text-danger">{{ $pricePending }}</span>
                </div>
                <div class="stat-value">{{ $pricePending }}</div>
                <div class="stat-label">Price Change Requests</div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <form method="GET" action="{{ route('employee.seller.products.index') }}" id="filterForm">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Seller</label>
                        <select name="seller_id" class="form-select select2">
                            <option value="">All Sellers</option>
                            @foreach($sellers as $seller)
                                <option value="{{ $seller->id }}" {{ request('seller_id') == $seller->id ? 'selected' : '' }}>
                                    {{ $seller->name }} ({{ $seller->code }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Approval</label>
                        <select name="approval" class="form-select">
                            <option value="">All</option>
                            <option value="pending" {{ request('approval') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('approval') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('approval') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Search</label>
                        <input type="text" name="search" class="form-control" placeholder="Product name, code, seller..." value="{{ request('search') }}">
                    </div>

                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter me-2"></i>Apply Filters
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Products Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="productsTable">
                        <thead>
                        <tr>
                            <th>
                                <input type="checkbox" id="selectAll">
                            </th>
                            <th>Product</th>
                            <th>Seller</th>
                            <th>Category/Brand</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th>Approval</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td>
                                    <input type="checkbox" class="product-checkbox" value="{{ $product->id }}">
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="product-image">
                                        <div>
                                            <div class="fw-semibold">{{ Str::limit($product->name, 30) }}</div>
                                            <small class="text-muted">{{ $product->code }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="seller-info">
                                        <span class="seller-name">{{ $product->seller->name ?? 'N/A' }}</span>
                                        <span class="seller-code">{{ $product->seller_code }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div>{{ $product->category->name ?? 'Uncategorized' }}</div>
                                    <small class="text-muted">{{ $product->brand->name ?? 'No Brand' }}</small>
                                </td>
                                <td>
                                    <div class="price-info">
                                        <span class="current-price">Rs. {{ number_format($product->final_price, 2) }}</span>
                                        @if($product->display_price > $product->final_price)
                                            <span class="original-price">Rs. {{ number_format($product->display_price, 2) }}</span>
                                            <span class="discount-badge">-{{ $product->discount }}%</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                        <span class="badge {{ $product->qty > 0 ? 'bg-success' : 'bg-danger' }}">
                                            {{ $product->qty }} units
                                        </span>
                                </td>
                                <td>
                                        <span class="badge-status {{ $product->status == 'active' ? 'badge-active' : 'badge-inactive' }}">
                                            {{ ucfirst($product->status) }}
                                        </span>
                                </td>
                                <td>
                                    @if($product->isApproved())
                                        <span class="badge-status badge-approved">
                                                <i class="fas fa-check-circle me-1"></i>Approved
                                            </span>
                                    @elseif($product->isRejected())
                                        <span class="badge-status badge-inactive">
                                                <i class="fas fa-times-circle me-1"></i>Rejected
                                            </span>
                                    @else
                                        <span class="badge-status badge-pending">
                                                <i class="fas fa-clock me-1"></i>Pending
                                            </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('employee.seller.products.show', $product->id) }}" class="action-btn btn-view" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <a href="{{ route('employee.seller.products.price-edit', $product->id) }}" class="action-btn btn-price" title="Update Price">
                                            <i class="fas fa-tag"></i>
                                        </a>

                                        @if(!$product->isApproved() && $product->status == 'active')
                                            <button onclick="approveProduct({{ $product->id }})" class="action-btn btn-approve" title="Approve">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button onclick="showRejectModal({{ $product->id }})" class="action-btn btn-reject" title="Reject">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5">
                                    <img src="{{ asset('images/no-data.svg') }}" alt="No products" style="width: 150px; opacity: 0.5;">
                                    <h5 class="mt-3 text-muted">No products found</h5>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        Showing {{ $products->firstItem() ?? 0 }} to {{ $products->lastItem() ?? 0 }} of {{ $products->total() }} products
                    </div>
                    <div>
                        {{ $products->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Price Update Modal -->
    <div class="modal fade" id="bulkPriceModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Bulk Price Update</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('employee.seller.products.bulk-price-update') }}" method="POST" id="bulkPriceForm">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Selected products: <span id="selectedCount">0</span>
                        </div>

                        <input type="hidden" name="product_ids" id="productIds">

                        <div class="mb-3">
                            <label class="form-label">Update Type</label>
                            <select name="update_type" class="form-select" required>
                                <option value="percentage">Percentage (%)</option>
                                <option value="fixed">Fixed Amount (Rs.)</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Value</label>
                            <input type="number" name="value" class="form-control" step="0.01" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Operation</label>
                            <select name="operation" class="form-select" required>
                                <option value="increase">Increase</option>
                                <option value="decrease">Decrease</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Apply To</label>
                            <select name="apply_to" class="form-select" required>
                                <option value="display_price">Display Price Only</option>
                                <option value="stock_price">Stock Price Only</option>
                                <option value="both">Both Prices</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Change Reason</label>
                            <textarea name="change_reason" class="form-control" rows="3" required placeholder="Why are you updating these prices?"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Submit for Approval</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.select2').select2({
                theme: 'classic',
                width: '100%'
            });

            // Select All functionality
            $('#selectAll').on('change', function() {
                $('.product-checkbox').prop('checked', $(this).prop('checked'));
            });

            // Update select all when individual checkboxes change
            $(document).on('change', '.product-checkbox', function() {
                const allChecked = $('.product-checkbox:checked').length === $('.product-checkbox').length;
                $('#selectAll').prop('checked', allChecked);
            });
        });

        function showBulkPriceModal() {
            const selectedIds = $('.product-checkbox:checked').map(function() {
                return $(this).val();
            }).get();

            if (selectedIds.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No Products Selected',
                    text: 'Please select at least one product to update prices.'
                });
                return;
            }

            $('#productIds').val(selectedIds.join(','));
            $('#selectedCount').text(selectedIds.length);
            new bootstrap.Modal(document.getElementById('bulkPriceModal')).show();
        }

        function approveProduct(id) {
            Swal.fire({
                title: 'Approve Product?',
                text: 'This product will be visible in the seller\'s shop after approval.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, Approve',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/employee/seller-products/approve/' + id,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            Swal.fire('Approved!', 'Product has been approved.', 'success')
                                .then(() => location.reload());
                        },
                        error: function() {
                            Swal.fire('Error!', 'Failed to approve product.', 'error');
                        }
                    });
                }
            });
        }

        function showRejectModal(id) {
            Swal.fire({
                title: 'Reject Product',
                input: 'textarea',
                inputLabel: 'Rejection Reason',
                inputPlaceholder: 'Enter reason for rejection...',
                inputAttributes: {
                    required: true
                },
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Reject Product',
                cancelButtonText: 'Cancel',
                preConfirm: (reason) => {
                    if (!reason) {
                        Swal.showValidationMessage('Rejection reason is required');
                        return false;
                    }
                    return $.ajax({
                        url: '/employee/seller-products/reject/' + id,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            rejection_reason: reason
                        }
                    });
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire('Rejected!', 'Product has been rejected.', 'success')
                        .then(() => location.reload());
                }
            });
        }

        @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('success') }}',
            timer: 3000,
            showConfirmButton: false
        });
        @endif

        @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: '{{ session('error') }}',
            timer: 3000,
            showConfirmButton: false
        });
        @endif
    </script>
@endpush
