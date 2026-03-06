@extends('employee.layout.app')

@push('title')
    {{ $product->name }} - Product Details
@endpush

@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <style>
        :root {
            --primary: #4f46e5;
            --primary-light: #eef2ff;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #3b82f6;
            --dark: #1e293b;
            --light: #f8fafc;
            --border: #e2e8f0;
        }

        body {
            background: #f8fafc;
        }

        .product-header {
            background: linear-gradient(135deg, var(--primary) 0%, #6366f1 100%);
            color: white;
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 1.5rem;
            position: relative;
            overflow: hidden;
        }

        .product-header::after {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        }

        .product-code-badge {
            background: rgba(255,255,255,0.2);
            padding: 0.5rem 1rem;
            border-radius: 30px;
            font-size: 0.9rem;
            backdrop-filter: blur(10px);
            display: inline-block;
            margin-bottom: 1rem;
            border: 1px solid rgba(255,255,255,0.3);
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1.5rem;
            border-radius: 30px;
            font-weight: 600;
            font-size: 0.9rem;
            position: absolute;
            top: 2rem;
            right: 2rem;
            background: rgba(255,255,255,0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.3);
        }

        .info-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
            height: 100%;
            border: 1px solid var(--border);
            transition: all 0.3s;
        }

        .info-card:hover {
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }

        .info-card-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid var(--border);
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px dashed var(--border);
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            color: #64748b;
            font-size: 0.9rem;
        }

        .info-value {
            font-weight: 600;
            color: var(--dark);
        }

        .price-box {
            background: var(--primary-light);
            border-radius: 10px;
            padding: 1rem;
            text-align: center;
        }

        .price-box .label {
            font-size: 0.8rem;
            color: var(--primary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .price-box .value {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary);
            line-height: 1.2;
        }

        .price-box .old-price {
            font-size: 1rem;
            color: #94a3b8;
            text-decoration: line-through;
        }

        .timeline-item {
            position: relative;
            padding-left: 2rem;
            padding-bottom: 1.5rem;
            border-left: 2px solid var(--border);
        }

        .timeline-item:last-child {
            border-left-color: transparent;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -0.5rem;
            top: 0;
            width: 1rem;
            height: 1rem;
            border-radius: 50%;
            background: var(--primary);
            border: 2px solid white;
        }

        .timeline-item.increase::before {
            background: var(--success);
        }

        .timeline-item.decrease::before {
            background: var(--danger);
        }

        .timeline-date {
            font-size: 0.8rem;
            color: #94a3b8;
            margin-bottom: 0.25rem;
        }

        .timeline-title {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 0.25rem;
        }

        .timeline-desc {
            font-size: 0.9rem;
            color: #64748b;
        }

        .action-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
            text-decoration: none;
        }

        .action-btn.primary {
            background: var(--primary);
            color: white;
        }

        .action-btn.primary:hover {
            background: #4338ca;
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.3);
        }

        .action-btn.success {
            background: var(--success);
            color: white;
        }

        .action-btn.success:hover {
            background: #059669;
            transform: translateY(-2px);
        }

        .action-btn.warning {
            background: var(--warning);
            color: white;
        }

        .action-btn.warning:hover {
            background: #d97706;
            transform: translateY(-2px);
        }

        .action-btn.outline {
            background: transparent;
            border: 2px solid var(--border);
            color: var(--dark);
        }

        .action-btn.outline:hover {
            border-color: var(--primary);
            color: var(--primary);
        }

        .tab-container {
            border-bottom: 2px solid var(--border);
            margin-bottom: 1.5rem;
            display: flex;
            gap: 0.5rem;
        }

        .tab {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            color: #64748b;
            cursor: pointer;
            position: relative;
            text-decoration: none;
            background: transparent;
            border: none;
            font-size: 1rem;
        }

        .tab.active {
            color: var(--primary);
        }

        .tab.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--primary);
        }

        .badge-change {
            padding: 0.25rem 0.5rem;
            border-radius: 5px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-change.increase {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-change.decrease {
            background: #fee2e2;
            color: #991b1b;
        }

        .product-image {
            width: 100%;
            height: 300px;
            object-fit: contain;
            background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
            border-radius: 15px;
            padding: 1rem;
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 1rem;
        }

        .pagination .page-item .page-link {
            border-radius: 8px;
            padding: 0.5rem 1rem;
            color: var(--primary);
            border: 1px solid var(--border);
        }

        .pagination .page-item.active .page-link {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        @media (max-width: 768px) {
            .product-header {
                padding: 1.5rem;
            }

            .status-badge {
                position: static;
                margin-top: 1rem;
                display: inline-flex;
                width: fit-content;
            }

            .price-box .value {
                font-size: 1.4rem;
            }

            .tab-container {
                flex-wrap: wrap;
            }

            .tab {
                padding: 0.5rem 1rem;
                font-size: 0.9rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid py-3">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('employee.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('employee.wholesaler.products.index') }}">Wholesaler Products</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
            </ol>
        </nav>

        <!-- Product Header -->
        <div class="product-header">
            <div class="product-code-badge">
                <i class="fas fa-barcode me-2"></i>
                {{ $product->code }}
            </div>

            <span class="status-badge">
                <i class="fas fa-{{ $product->status == 'active' ? 'check-circle' : 'times-circle' }}"></i>
                {{ ucfirst($product->status) }}
            </span>

            <h1 class="mb-2" style="font-size: 2rem; font-weight: 700;">{{ $product->name }}</h1>

            <div class="d-flex gap-3 mt-3 flex-wrap">
                <span class="text-white-50">
                    <i class="fas fa-store me-1"></i>
                    {{ $product->wholesaler->business_name ?? 'N/A' }}
                </span>
                <span class="text-white-50">
                    <i class="fas fa-tag me-1"></i>
                    {{ $product->category->name ?? 'Uncategorized' }}
                </span>
                <span class="text-white-50">
                    <i class="fas fa-certificate me-1"></i>
                    {{ $product->brand->name ?? 'No Brand' }}
                </span>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="d-flex gap-2 mb-4 flex-wrap">
            <a href="{{ route('employee.wholesaler.price-changes.create', ['product_id' => $product->id]) }}" class="action-btn primary">
                <i class="fas fa-tag"></i>
                Change Price
            </a>
            <a href="{{ route('employee.wholesaler.exchanges.create', ['source_product_id' => $product->id]) }}" class="action-btn warning">
                <i class="fas fa-exchange-alt"></i>
                Exchange Product
            </a>
            <button class="action-btn success" onclick="window.print()">
                <i class="fas fa-print"></i>
                Print Details
            </button>
            <a href="{{ route('employee.wholesaler.products.index') }}" class="action-btn outline">
                <i class="fas fa-arrow-left"></i>
                Back to List
            </a>
        </div>

        <!-- Stats Cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-3 col-sm-6">
                <div class="info-card">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width: 48px; height: 48px; background: var(--primary-light); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-chart-line" style="color: var(--primary); font-size: 1.5rem;"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Price Changes</h6>
                            <h3 class="mb-0">{{ $product->price_changes_count ?? 0 }}</h3>
                            <small class="text-muted">Last: {{ $product->last_price_updated_at ? $product->last_price_updated_at->diffForHumans() : 'Never' }}</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="info-card">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width: 48px; height: 48px; background: #fef3c7; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-exchange-alt" style="color: var(--warning); font-size: 1.5rem;"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Exchanges</h6>
                            <h3 class="mb-0">{{ $product->exchange_count ?? 0 }}</h3>
                            <small class="text-muted">Last: {{ $product->last_exchanged_at ? $product->last_exchanged_at->diffForHumans() : 'Never' }}</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="info-card">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width: 48px; height: 48px; background: #d1fae5; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-dollar-sign" style="color: var(--success); font-size: 1.5rem;"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Total Profit</h6>
                            <h3 class="mb-0">Rs. {{ number_format($stats['total_profit'] ?? 0, 2) }}</h3>
                            <small class="text-muted">Margin: {{ number_format($stats['profit_margin'] ?? 0, 2) }}%</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="info-card">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width: 48px; height: 48px; background: #fee2e2; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-box" style="color: var(--danger); font-size: 1.5rem;"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Stock Value</h6>
                            <h3 class="mb-0">Rs. {{ number_format(($product->selling_price ?? 0) * ($product->qty ?? 0), 2) }}</h3>
                            <small class="text-muted">{{ $product->qty ?? 0 }} units</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="row">
            <!-- Left Column - Product Image & Basic Info -->
            <div class="col-lg-4 mb-4">
                <div class="info-card h-100">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" class="product-image mb-3" alt="{{ $product->name }}">
                    @else
                        <div class="product-image d-flex align-items-center justify-content-center">
                            <i class="fas fa-image" style="font-size: 4rem; color: #94a3b8;"></i>
                        </div>
                    @endif

                    <div class="price-box mb-3">
                        <div class="label">Current Selling Price</div>
                        <div class="value">Rs. {{ number_format($product->selling_price, 2) }}</div>
                        @if($product->display_price != $product->selling_price)
                            <div class="old-price">Display: Rs. {{ number_format($product->display_price, 2) }}</div>
                        @endif
                        @if($product->discount > 0)
                            <div class="mt-2">
                                <span class="badge bg-success">{{ $product->discount }}% OFF</span>
                            </div>
                        @endif
                    </div>

                    <div class="price-box mb-3" style="background: #d1fae5;">
                        <div class="label">Final Price (After Discount)</div>
                        <div class="value" style="color: var(--success);">Rs. {{ number_format($product->final_price, 2) }}</div>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Cost Price</span>
                        <span class="info-value">Rs. {{ number_format($product->cost_price ?? 0, 2) }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Packaging Cost</span>
                        <span class="info-value">Rs. {{ number_format($product->packaging_cost ?? 0, 2) }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Total Cost</span>
                        <span class="info-value">Rs. {{ number_format(($product->cost_price ?? 0) + ($product->packaging_cost ?? 0), 2) }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Profit per Unit</span>
                        <span class="info-value text-success">Rs. {{ number_format($product->final_price - (($product->cost_price ?? 0) + ($product->packaging_cost ?? 0)), 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Right Column - Details & History -->
            <div class="col-lg-8">
                <!-- Tabs -->
                <div class="info-card mb-4">
                    <div class="tab-container">
                        <button class="tab active" onclick="showTab('details')" id="tabDetails">Details</button>
                        <button class="tab" onclick="showTab('price-history')" id="tabPrice">Price History ({{ $priceChanges->total() }})</button>
                        <button class="tab" onclick="showTab('exchange-history')" id="tabExchange">Exchange History ({{ $exchanges->total() }})</button>
                    </div>

                    <!-- Details Tab -->
                    <div id="detailsTab" class="tab-pane" style="display: block;">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="fw-bold mb-3">Product Information</h6>
                                <div class="info-row">
                                    <span class="info-label">Product ID</span>
                                    <span class="info-value">#{{ $product->id }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Product Code</span>
                                    <span class="info-value"><code>{{ $product->code }}</code></span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Barcode</span>
                                    <span class="info-value">{{ $product->barcode ?? 'N/A' }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Category</span>
                                    <span class="info-value">{{ $product->category->name ?? 'N/A' }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Brand</span>
                                    <span class="info-value">{{ $product->brand->name ?? 'N/A' }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Wholesaler</span>
                                    <span class="info-value">{{ $product->wholesaler->business_name ?? 'N/A' }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6 class="fw-bold mb-3">Stock Information</h6>
                                <div class="info-row">
                                    <span class="info-label">Current Stock</span>
                                    <span class="info-value">
                                        <span class="badge bg-{{ $product->qty > 0 ? ($product->qty <= ($product->min_stock_level ?? 5) ? 'warning' : 'success') : 'danger' }}">
                                            {{ $product->qty ?? 0 }} units
                                        </span>
                                    </span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Min Stock Level</span>
                                    <span class="info-value">{{ $product->min_stock_level ?? 5 }} units</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Total Sold</span>
                                    <span class="info-value">{{ $product->total_sold ?? 0 }} units</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Average Rating</span>
                                    <span class="info-value">
                                        @if($product->average_rating)
                                            {{ number_format($product->average_rating, 1) }} ★
                                            ({{ $product->total_reviews ?? 0 }} reviews)
                                        @else
                                            No ratings
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>

                        @if($product->description)
                            <div class="mt-4">
                                <h6 class="fw-bold mb-3">Description</h6>
                                <p class="text-muted">{{ $product->description }}</p>
                            </div>
                        @endif

                        @if($product->features)
                            <div class="mt-4">
                                <h6 class="fw-bold mb-3">Features</h6>
                                <div class="row">
                                    @foreach(explode(',', $product->features) as $feature)
                                        <div class="col-md-6 mb-2">
                                            <i class="fas fa-check-circle text-success me-2"></i>
                                            {{ trim($feature) }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="row mt-4">
                            <div class="col-md-6">
                                <div class="info-row">
                                    <span class="info-label">Created At</span>
                                    <span class="info-value">{{ $product->created_at->format('M d, Y h:i A') }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-row">
                                    <span class="info-label">Last Updated</span>
                                    <span class="info-value">{{ $product->updated_at->format('M d, Y h:i A') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Price History Tab -->
                    <div id="priceHistoryTab" class="tab-pane" style="display: none;">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold mb-0">Price Change History</h6>
                            <a href="{{ route('employee.wholesaler.products.price-history', $product->id) }}" class="btn btn-sm btn-outline-primary">
                                View All <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                        </div>

                        <div style="max-height: 500px; overflow-y: auto;">
                            @forelse($priceChanges as $change)
                                <div class="timeline-item {{ $change->price_difference > 0 ? 'increase' : ($change->price_difference < 0 ? 'decrease' : '') }}">
                                    <div class="timeline-date">
                                        <i class="far fa-calendar me-1"></i> {{ $change->created_at->format('M d, Y h:i A') }}
                                    </div>
                                    <div class="timeline-title">
                                        <span class="badge-change {{ $change->price_difference > 0 ? 'increase' : ($change->price_difference < 0 ? 'decrease' : '') }}">
                                            {{ $change->price_difference > 0 ? '+' : '' }}{{ number_format($change->price_difference, 2) }} ({{ number_format($change->percentage_change, 2) }}%)
                                        </span>
                                        <span class="ms-2">
                                            Rs. {{ number_format($change->old_final_price, 2) }} →
                                            <strong>Rs. {{ number_format($change->new_final_price, 2) }}</strong>
                                        </span>
                                    </div>
                                    <div class="timeline-desc">
                                        <span class="badge bg-secondary">{{ ucfirst($change->change_type) }}</span>
                                        @if($change->employee)
                                            <span class="ms-2">by {{ $change->employee->name }}</span>
                                        @endif
                                        @if($change->change_reason)
                                            <div class="mt-1 text-muted"><small>Reason: {{ $change->change_reason }}</small></div>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4">
                                    <i class="fas fa-history fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No price changes recorded yet</p>
                                </div>
                            @endforelse
                        </div>

                        <!-- Price History Pagination -->
                        @if($priceChanges->hasPages())
                            <div class="mt-3">
                                {{ $priceChanges->appends(['exchange_page' => request('exchange_page')])->links() }}
                            </div>
                        @endif
                    </div>

                    <!-- Exchange History Tab -->
                    <div id="exchangeHistoryTab" class="tab-pane" style="display: none;">
                        <h6 class="fw-bold mb-3">Exchange History</h6>

                        <div style="max-height: 500px; overflow-y: auto;">
                            @forelse($exchanges as $exchange)
                                <div class="timeline-item">
                                    <div class="timeline-date">
                                        <i class="far fa-calendar me-1"></i> {{ $exchange->created_at->format('M d, Y h:i A') }}
                                    </div>
                                    <div class="timeline-title">
                                        @if($exchange->source_product_id == $product->id)
                                            <span class="badge bg-warning">Source</span>
                                            Exchanged to
                                            <strong>{{ $exchange->destinationProduct->name ?? 'Unknown' }}</strong>
                                            ({{ $exchange->destinationWholesaler->business_name ?? 'N/A' }})
                                        @else
                                            <span class="badge bg-success">Destination</span>
                                            Received from
                                            <strong>{{ $exchange->sourceProduct->name ?? 'Unknown' }}</strong>
                                            ({{ $exchange->sourceWholesaler->business_name ?? 'N/A' }})
                                        @endif
                                    </div>
                                    <div class="timeline-desc">
                                        <div class="row mt-2">
                                            <div class="col-md-3">
                                                <small class="text-muted">Quantity:</small><br>
                                                <strong>{{ $exchange->exchange_quantity }}</strong>
                                            </div>
                                            <div class="col-md-3">
                                                <small class="text-muted">Value:</small><br>
                                                <strong>Rs. {{ number_format($exchange->destination_price_at_time, 2) }}</strong>
                                            </div>
                                            <div class="col-md-3">
                                                <small class="text-muted">Difference:</small><br>
                                                <span class="badge-change {{ ($exchange->price_difference ?? 0) > 0 ? 'increase' : (($exchange->price_difference ?? 0) < 0 ? 'decrease' : '') }}">
                                                    Rs. {{ number_format($exchange->price_difference ?? 0, 2) }}
                                                </span>
                                            </div>
                                            <div class="col-md-3">
                                                <small class="text-muted">Status:</small><br>
                                                <span class="badge bg-{{
                                                    ($exchange->status ?? 'pending') == 'completed' ? 'success' :
                                                    (($exchange->status ?? 'pending') == 'pending' ? 'warning' :
                                                    (($exchange->status ?? 'pending') == 'cancelled' ? 'danger' : 'secondary'))
                                                }}">
                                                    {{ ucfirst($exchange->status ?? 'pending') }}
                                                </span>
                                            </div>
                                        </div>
                                        @if($exchange->employee)
                                            <small class="text-muted d-block mt-2">
                                                Processed by: {{ $exchange->employee->name }}
                                            </small>
                                        @endif
                                        <a href="{{ route('employee.wholesaler.exchanges.show', $exchange->id) }}" class="btn btn-sm btn-link mt-2">
                                            View Details <i class="fas fa-arrow-right ms-1"></i>
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4">
                                    <i class="fas fa-exchange-alt fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No exchanges recorded yet</p>
                                </div>
                            @endforelse
                        </div>

                        <!-- Exchange History Pagination -->
                        @if($exchanges->hasPages())
                            <div class="mt-3">
                                {{ $exchanges->appends(['price_page' => request('price_page')])->links() }}
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Additional Info -->
                <div class="info-card">
                    <h6 class="info-card-title">
                        <i class="fas fa-info-circle"></i>
                        Additional Information
                    </h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-row">
                                <span class="info-label">Original Wholesaler</span>
                                <span class="info-value">{{ $product->originalWholesaler->business_name ?? $product->wholesaler->business_name ?? 'N/A' }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Last Price Update By</span>
                                <span class="info-value">{{ $product->lastPriceUpdatedBy->name ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-row">
                                <span class="info-label">Last Exchange By</span>
                                <span class="info-value">{{ $product->lastExchangedBy->name ?? 'N/A' }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Total Price Changes</span>
                                <span class="info-value">{{ $product->price_changes_count ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        function showTab(tab) {
            // Hide all tabs
            document.getElementById('detailsTab').style.display = 'none';
            document.getElementById('priceHistoryTab').style.display = 'none';
            document.getElementById('exchangeHistoryTab').style.display = 'none';

            // Remove active class from all tabs
            document.getElementById('tabDetails').classList.remove('active');
            document.getElementById('tabPrice').classList.remove('active');
            document.getElementById('tabExchange').classList.remove('active');

            // Show selected tab
            if (tab === 'details') {
                document.getElementById('detailsTab').style.display = 'block';
                document.getElementById('tabDetails').classList.add('active');
                // Update URL hash without reloading
                history.pushState(null, null, '#details');
            } else if (tab === 'price-history') {
                document.getElementById('priceHistoryTab').style.display = 'block';
                document.getElementById('tabPrice').classList.add('active');
                history.pushState(null, null, '#price-history');
            } else if (tab === 'exchange-history') {
                document.getElementById('exchangeHistoryTab').style.display = 'block';
                document.getElementById('tabExchange').classList.add('active');
                history.pushState(null, null, '#exchange-history');
            }
        }

        // Check URL hash on load
        document.addEventListener('DOMContentLoaded', function() {
            const hash = window.location.hash;
            if (hash === '#price-history') {
                showTab('price-history');
            } else if (hash === '#exchange-history') {
                showTab('exchange-history');
            } else {
                showTab('details');
            }
        });

        // Handle browser back/forward buttons
        window.addEventListener('popstate', function() {
            const hash = window.location.hash;
            if (hash === '#price-history') {
                showTab('price-history');
            } else if (hash === '#exchange-history') {
                showTab('exchange-history');
            } else {
                showTab('details');
            }
        });
    </script>
@endpush
