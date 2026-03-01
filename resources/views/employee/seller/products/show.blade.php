@extends('employee.layout.app')

@push('title')
    Product Details - {{ $product->name }}
@endpush

@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <style>
        :root {
            --primary: #4f46e5;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #3b82f6;
        }

        .product-header {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            position: relative;
            border-left: 4px solid var(--primary);
        }

        .status-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 12px;
        }

        .status-badge.active { background: #d1fae5; color: #065f46; }
        .status-badge.inactive { background: #fee2e2; color: #991b1b; }
        .status-badge.pending { background: #fef3c7; color: #92400e; }
        .status-badge.approved { background: #dbeafe; color: #1e40af; }

        .info-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }

        .info-title {
            font-size: 16px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e5e7eb;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
        }

        .info-item {
            padding: 10px;
            background: #f9fafb;
            border-radius: 8px;
        }

        .info-label {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 5px;
        }

        .info-value {
            font-weight: 600;
            color: #1f2937;
            font-size: 14px;
        }

        .product-image {
            max-width: 100%;
            max-height: 300px;
            border-radius: 10px;
            border: 2px solid #e5e7eb;
        }

        .price-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .price-label {
            font-size: 12px;
            opacity: 0.9;
            margin-bottom: 5px;
        }

        .price-value {
            font-size: 24px;
            font-weight: 700;
        }

        .price-old {
            text-decoration: line-through;
            opacity: 0.7;
            font-size: 14px;
        }

        .history-timeline {
            position: relative;
            padding-left: 30px;
        }

        .history-item {
            position: relative;
            padding-bottom: 20px;
            border-left: 2px solid #e5e7eb;
            padding-left: 20px;
            margin-left: 10px;
        }

        .history-item::before {
            content: '';
            position: absolute;
            left: -8px;
            top: 0;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            background: var(--primary);
            border: 2px solid white;
        }

        .history-item.pending::before { background: var(--warning); }
        .history-item.approved::before { background: var(--success); }
        .history-item.rejected::before { background: var(--danger); }

        .history-date {
            font-size: 11px;
            color: #9ca3af;
            margin-bottom: 5px;
        }

        .history-title {
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 5px;
        }

        .history-detail {
            font-size: 13px;
            color: #6b7280;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn-action {
            padding: 10px 20px;
            border-radius: 8px;
            border: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-action:hover {
            transform: translateY(-2px);
        }

        .btn-primary { background: var(--primary); color: white; }
        .btn-success { background: var(--success); color: white; }
        .btn-warning { background: var(--warning); color: white; }
        .btn-danger { background: var(--danger); color: white; }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <!-- Product Header -->
        <div class="product-header">
            @php
                $approvalStatus = $product->isApproved() ? 'approved' : ($product->isRejected() ? 'rejected' : 'pending');
            @endphp
            <span class="status-badge {{ $approvalStatus }}">
                <i class="fas fa-{{ $approvalStatus == 'approved' ? 'check-circle' : ($approvalStatus == 'rejected' ? 'times-circle' : 'clock') }} me-1"></i>
                {{ ucfirst($approvalStatus) }}
            </span>

            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h4 class="mb-2">{{ $product->name }}</h4>
                    <div class="d-flex gap-3">
                        <span class="text-muted"><i class="fas fa-barcode me-2"></i>{{ $product->code }}</span>
                        <span class="text-muted"><i class="fas fa-store me-2"></i>{{ $product->seller->name ?? 'N/A' }}</span>
                        <span class="text-muted"><i class="fas fa-user me-2"></i>{{ $product->seller_code }}</span>
                    </div>
                </div>

                <div class="action-buttons">
                    <a href="{{ route('employee.seller.products.price-edit', $product->id) }}" class="btn-action btn-warning">
                        <i class="fas fa-tag"></i> Update Price
                    </a>

                    @if(!$product->isApproved() && $product->status == 'active')
                        <button onclick="approveProduct({{ $product->id }})" class="btn-action btn-success">
                            <i class="fas fa-check"></i> Approve
                        </button>
                        <button onclick="showRejectModal({{ $product->id }})" class="btn-action btn-danger">
                            <i class="fas fa-times"></i> Reject
                        </button>
                    @endif

                    <a href="{{ route('employee.seller.products.index') }}" class="btn-action btn-primary">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Left Column - Product Images -->
            <div class="col-md-4">
                <div class="info-card text-center">
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="product-image mb-3">

                    @if($product->additional_images)
                        <div class="row g-2 mt-3">
                            @foreach(json_decode($product->additional_images, true) ?? [] as $image)
                                <div class="col-4">
                                    <img src="{{ asset('storage/' . $image) }}" class="img-thumbnail" style="height: 80px; object-fit: cover;">
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Seller Information -->
                <div class="info-card">
                    <div class="info-title">
                        <i class="fas fa-store me-2"></i>Seller Information
                    </div>

                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">Seller Name</div>
                            <div class="info-value">{{ $product->seller->name ?? 'N/A' }}</div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">Seller Code</div>
                            <div class="info-value">{{ $product->seller_code }}</div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">Shop Name</div>
                            <div class="info-value">{{ $product->shop->shop_name ?? 'N/A' }}</div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">Contact</div>
                            <div class="info-value">{{ $product->seller->phone ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Product Details -->
            <div class="col-md-8">
                <!-- Price Information -->
                <div class="info-card">
                    <div class="info-title">
                        <i class="fas fa-tags me-2"></i>Price Information
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="price-box">
                                <div class="price-label">Display Price</div>
                                <div class="price-value">Rs. {{ number_format($product->display_price, 2) }}</div>
                                @if($product->discount > 0)
                                    <div class="price-old mt-2">Original: Rs. {{ number_format($product->display_price, 2) }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="price-box" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                                <div class="price-label">Final Price</div>
                                <div class="price-value">Rs. {{ number_format($product->final_price, 2) }}</div>
                                @if($product->discount > 0)
                                    <div class="price-old mt-2">{{ $product->discount }}% OFF</div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="price-box" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                                <div class="price-label">Stock Price</div>
                                <div class="price-value">Rs. {{ number_format($product->stock_price, 2) }}</div>
                                @if($product->packaging_cost > 0)
                                    <div class="price-old mt-2">+ Rs. {{ number_format($product->packaging_cost, 2) }} packaging</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-3">
                            <div class="info-item">
                                <div class="info-label">Profit</div>
                                <div class="info-value {{ $product->profit_amount >= 0 ? 'text-success' : 'text-danger' }}">
                                    Rs. {{ number_format($product->profit_amount, 2) }}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="info-item">
                                <div class="info-label">Profit Margin</div>
                                <div class="info-value {{ $product->profit_margin_percentage >= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ number_format($product->profit_margin_percentage, 2) }}%
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="info-item">
                                <div class="info-label">Total Cost</div>
                                <div class="info-value">Rs. {{ number_format($product->total_cost, 2) }}</div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="info-item">
                                <div class="info-label">Discount</div>
                                <div class="info-value">{{ $product->discount ?? 0 }}%</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stock Information -->
                <div class="info-card">
                    <div class="info-title">
                        <i class="fas fa-boxes me-2"></i>Stock Information
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="info-item">
                                <div class="info-label">Current Stock</div>
                                <div class="info-value">{{ $product->qty }} units</div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="info-item">
                                <div class="info-label">Minimum Quantity</div>
                                <div class="info-value">{{ $product->min_qty }} units</div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="info-item">
                                <div class="info-label">Total Sold</div>
                                <div class="info-value">{{ $product->total_sold ?? 0 }} units</div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="info-item">
                                <div class="info-label">Stock Status</div>
                                <div class="info-value">
                                    @if($product->qty <= 0)
                                        <span class="badge bg-danger">Out of Stock</span>
                                    @elseif($product->qty <= $product->min_qty)
                                        <span class="badge bg-warning">Low Stock</span>
                                    @else
                                        <span class="badge bg-success">In Stock</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Details -->
                <div class="info-card">
                    <div class="info-title">
                        <i class="fas fa-info-circle me-2"></i>Product Details
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-label">Category</div>
                                <div class="info-value">{{ $product->category->name ?? 'Uncategorized' }}</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-label">Brand</div>
                                <div class="info-value">{{ $product->brand->name ?? 'No Brand' }}</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-label">Product Type</div>
                                <div class="info-value">{{ ucfirst($product->type ?? 'Physical') }}</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-label">Barcode</div>
                                <div class="info-value">{{ $product->barcode ?? 'N/A' }}</div>
                            </div>
                        </div>

                        <div class="col-12 mt-3">
                            <div class="info-item">
                                <div class="info-label">Description</div>
                                <div class="info-value">{{ $product->description ?? 'No description' }}</div>
                            </div>
                        </div>

                        <div class="col-12 mt-3">
                            <div class="info-item">
                                <div class="info-label">Features</div>
                                <div class="info-value">{{ $product->features ?? 'No features listed' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Price Change History -->
                @if(isset($priceHistory) && $priceHistory->count() > 0)
                    <div class="info-card">
                        <div class="info-title">
                            <i class="fas fa-history me-2"></i>Price Change History
                        </div>

                        <div class="history-timeline">
                            @foreach($priceHistory as $history)
                                <div class="history-item {{ $history->status }}">
                                    <div class="history-date">
                                        <i class="far fa-clock me-1"></i>
                                        {{ $history->created_at->format('M d, Y H:i A') }}
                                    </div>
                                    <div class="history-title">
                                        @if($history->status == 'approved')
                                            <span class="text-success">✓ Approved</span>
                                        @elseif($history->status == 'rejected')
                                            <span class="text-danger">✗ Rejected</span>
                                        @else
                                            <span class="text-warning">⏳ Pending</span>
                                        @endif
                                        - Price Change Request
                                    </div>
                                    <div class="history-detail">
                                        <div>Old Price: Rs. {{ number_format($history->old_display_price, 2) }}</div>
                                        <div>New Price: Rs. {{ number_format($history->new_display_price, 2) }}</div>
                                        <div class="mt-1">
                                            <span class="fw-semibold">Reason:</span> {{ $history->change_reason }}
                                        </div>
                                        @if($history->status == 'approved' && $history->approver)
                                            <div class="mt-1 text-muted">
                                                Approved by: {{ $history->approver->name ?? 'N/A' }}
                                            </div>
                                        @endif
                                        @if($history->status == 'rejected' && $history->rejection_reason)
                                            <div class="mt-1 text-danger">
                                                Rejection Reason: {{ $history->rejection_reason }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
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
    </script>
@endpush
