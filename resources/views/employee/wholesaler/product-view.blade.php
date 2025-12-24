@extends('employee.layout.app')

@section('title', 'Product Details')

@section('content')
    <div class="container-fluid py-4 px-lg-5">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <a href="{{ route('wholesaler.employee.wholesaler-products') }}"
                   class="btn btn-light border-0 shadow-sm mb-3 hover-lift">
                    <i class="fas fa-arrow-left me-2"></i> Back to Products
                </a>
                <h2 class="mb-1 fw-bold">{{ $product->name }}</h2>
                <p class="text-muted mb-0">
                    <i class="fas fa-barcode me-2"></i>{{ $product->code }}
                    @if($product->barcode)
                        <span class="ms-3"><i class="fas fa-qrcode me-2"></i>{{ $product->barcode }}</span>
                    @endif
                </p>
            </div>
            <div>
                <span class="badge rounded-pill px-4 py-2 fs-6 bg-{{ $product->status === 'active' ? 'success' : 'danger' }}">
                    <i class="fas fa-circle me-2" style="font-size: 8px;"></i>
                    {{ ucfirst($product->status) }}
                </span>
            </div>
        </div>

        <div class="row g-4">
            <!-- Main Product Information -->
            <div class="col-lg-8">
                <!-- Product Overview Card -->
                <div class="card border-0 shadow-sm mb-4 hover-card">
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <!-- Product Image -->
                            <div class="col-md-5">
                                <div class="product-image-container bg-light rounded-3 p-4 position-relative"
                                     style="min-height: 320px;">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}"
                                             alt="{{ $product->name }}"
                                             class="img-fluid rounded-3"
                                             style="width: 100%; height: 320px; object-fit: contain;">
                                    @else
                                        <div class="d-flex align-items-center justify-content-center h-100">
                                            <div class="text-center">
                                                <i class="fas fa-box fa-4x text-muted mb-3"></i>
                                                <p class="text-muted mb-0">No image available</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Product Details -->
                            <div class="col-md-7">
                                <h5 class="fw-bold mb-4 text-primary">
                                    <i class="fas fa-info-circle me-2"></i>Product Information
                                </h5>

                                <div class="product-details">
                                    <div class="detail-row">
                                        <span class="detail-label">
                                            <i class="fas fa-building text-primary me-2"></i>Wholesaler
                                        </span>
                                        <span class="detail-value fw-semibold">
                                            {{ $product->wholesaler->name ?? 'N/A' }}
                                        </span>
                                    </div>

                                    <div class="detail-row">
                                        <span class="detail-label">
                                            <i class="fas fa-tag text-primary me-2"></i>Category
                                        </span>
                                        <span class="detail-value">
                                            <span class="badge bg-light text-dark border">
                                                {{ $product->category->name ?? 'N/A' }}
                                            </span>
                                        </span>
                                    </div>

                                    <div class="detail-row">
                                        <span class="detail-label">
                                            <i class="fas fa-certificate text-primary me-2"></i>Brand
                                        </span>
                                        <span class="detail-value">
                                            <span class="badge bg-light text-dark border">
                                                {{ $product->brand->name ?? 'N/A' }}
                                            </span>
                                        </span>
                                    </div>

                                    @if($product->description)
                                        <div class="detail-row align-items-start">
                                        <span class="detail-label">
                                            <i class="fas fa-align-left text-primary me-2"></i>Description
                                        </span>
                                            <span class="detail-value text-muted">
                                            {{ $product->description }}
                                        </span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pricing Cards -->
                <div class="row g-4 mb-4">
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm h-100 hover-card price-card">
                            <div class="card-body p-4 text-center">
                                <div class="price-icon bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                     style="width: 60px; height: 60px;">
                                    <i class="fas fa-shopping-bag fa-lg text-primary"></i>
                                </div>
                                <h6 class="text-uppercase text-muted mb-2 fw-semibold" style="font-size: 0.85rem; letter-spacing: 0.5px;">
                                    Selling Price
                                </h6>
                                <h2 class="fw-bold mb-0 text-primary">৳{{ number_format($product->selling_price, 2) }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm h-100 hover-card price-card">
                            <div class="card-body p-4 text-center">
                                <div class="price-icon bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                     style="width: 60px; height: 60px;">
                                    <i class="fas fa-warehouse fa-lg text-success"></i>
                                </div>
                                <h6 class="text-uppercase text-muted mb-2 fw-semibold" style="font-size: 0.85rem; letter-spacing: 0.5px;">
                                    Wholesale Price
                                </h6>
                                <h2 class="fw-bold mb-0 text-success">৳{{ number_format($product->wholesale_price, 2) }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm h-100 hover-card price-card">
                            <div class="card-body p-4 text-center">
                                <div class="price-icon bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                     style="width: 60px; height: 60px;">
                                    <i class="fas fa-eye fa-lg text-warning"></i>
                                </div>
                                <h6 class="text-uppercase text-muted mb-2 fw-semibold" style="font-size: 0.85rem; letter-spacing: 0.5px;">
                                    Display Price
                                </h6>
                                <h2 class="fw-bold mb-0 text-warning">৳{{ number_format($product->display_price, 2) }}</h2>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sales Statistics -->
                <div class="card border-0 shadow-sm hover-card">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4 text-primary">
                            <i class="fas fa-chart-bar me-2"></i>Sales Performance
                        </h5>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="stat-card bg-primary bg-opacity-10 rounded-3 p-4">
                                    <div class="d-flex align-items-center">
                                        <div class="stat-icon bg-primary text-white rounded-3 p-3 me-3">
                                            <i class="fas fa-shopping-cart fa-2x"></i>
                                        </div>
                                        <div>
                                            <p class="text-muted mb-1 fw-semibold">Total Orders</p>
                                            <h2 class="mb-0 fw-bold">{{ number_format($product->total_orders) }}</h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="stat-card bg-success bg-opacity-10 rounded-3 p-4">
                                    <div class="d-flex align-items-center">
                                        <div class="stat-icon bg-success text-white rounded-3 p-3 me-3">
                                            <i class="fas fa-chart-line fa-2x"></i>
                                        </div>
                                        <div>
                                            <p class="text-muted mb-1 fw-semibold">Total Revenue</p>
                                            <h2 class="mb-0 fw-bold">৳{{ number_format($product->total_sales ?? 0, 2) }}</h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Price History Sidebar -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm sticky-top hover-card" style="top: 20px;">
                    <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
                        <h5 class="fw-bold mb-0 text-primary">
                            <i class="fas fa-history me-2"></i>Price Change History
                        </h5>
                    </div>
                    <div class="card-body px-4 pb-4" style="max-height: 600px; overflow-y: auto;">
                        @if($priceHistory->isEmpty())
                            <div class="text-center py-5">
                                <div class="empty-state-icon bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                     style="width: 80px; height: 80px;">
                                    <i class="fas fa-history fa-2x text-muted"></i>
                                </div>
                                <p class="text-muted mb-0">No price changes recorded yet</p>
                            </div>
                        @else
                            <div class="timeline mt-4">
                                @foreach($priceHistory as $index => $history)
                                    <div class="timeline-item {{ !$loop->last ? 'mb-4' : '' }}">
                                        <div class="timeline-content">
                                            <div class="timeline-header d-flex justify-content-between align-items-start mb-2">
                                                <div>
                                                    <h6 class="mb-1 fw-semibold">Price Update</h6>
                                                    <small class="text-muted">
                                                        <i class="fas fa-user me-1"></i>Employee #{{ $history->employee_id }}
                                                    </small>
                                                </div>
                                                <small class="badge bg-light text-dark">
                                                    <i class="far fa-clock me-1"></i>
                                                    {{ \Carbon\Carbon::parse($history->created_at)->diffForHumans() }}
                                                </small>
                                            </div>

                                            @php
                                                $oldPrices = json_decode($history->old_prices, true);
                                                $newPrices = json_decode($history->new_prices, true);
                                            @endphp

                                            <div class="price-changes bg-light rounded-3 p-3 mt-2">
                                                @foreach(['selling_price', 'wholesale_price', 'display_price'] as $priceType)
                                                    @if(isset($oldPrices[$priceType]) && isset($newPrices[$priceType]))
                                                        <div class="price-change-item {{ !$loop->last ? 'mb-2 pb-2 border-bottom' : '' }}">
                                                            <div class="small text-muted mb-1 text-uppercase" style="font-size: 0.75rem; font-weight: 600; letter-spacing: 0.5px;">
                                                                {{ str_replace('_', ' ', $priceType) }}
                                                            </div>
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <span class="text-danger fw-semibold">
                                                                    <del>৳{{ number_format($oldPrices[$priceType], 2) }}</del>
                                                                </span>
                                                                <i class="fas fa-arrow-right text-muted mx-2"></i>
                                                                <span class="text-success fw-bold">
                                                                    ৳{{ number_format($newPrices[$priceType], 2) }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>

                                            @if($history->notes)
                                                <div class="alert alert-info border-0 mt-2 mb-0 py-2 px-3" style="background-color: #e3f2fd;">
                                                    <small class="d-flex align-items-start">
                                                        <i class="fas fa-sticky-note me-2 mt-1" style="font-size: 0.85rem;"></i>
                                                        <span>{{ $history->notes }}</span>
                                                    </small>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Modern Card Styles */
        .hover-card {
            transition: all 0.3s ease;
        }

        .hover-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.12) !important;
        }

        .hover-lift {
            transition: all 0.2s ease;
        }

        .hover-lift:hover {
            transform: translateY(-2px);
        }

        /* Product Details Styling */
        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            color: #6c757d;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .detail-value {
            text-align: right;
            color: #212529;
        }

        /* Price Cards Enhancement */
        .price-card {
            transition: all 0.3s ease;
            border-left: 4px solid transparent !important;
        }

        .price-card:hover {
            border-left-color: var(--bs-primary) !important;
        }

        /* Timeline Styling */
        .timeline {
            position: relative;
            padding-left: 30px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 11px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: linear-gradient(to bottom, #0d6efd, #e9ecef);
        }

        .timeline-item {
            position: relative;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -23px;
            top: 8px;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            background: #0d6efd;
            border: 3px solid #fff;
            box-shadow: 0 0 0 2px #0d6efd;
            z-index: 1;
        }

        .timeline-content {
            background: #fff;
            border-radius: 8px;
            padding: 16px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .timeline-content:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        /* Stat Cards */
        .stat-card {
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: scale(1.02);
        }

        .stat-icon {
            transition: all 0.3s ease;
        }

        .stat-card:hover .stat-icon {
            transform: scale(1.1);
        }

        /* Scrollbar Styling */
        .card-body::-webkit-scrollbar {
            width: 6px;
        }

        .card-body::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .card-body::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 10px;
        }

        .card-body::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .sticky-top {
                position: relative !important;
                top: 0 !important;
            }

            .detail-row {
                flex-direction: column;
                align-items: flex-start;
            }

            .detail-value {
                text-align: left;
                margin-top: 4px;
            }
        }
    </style>
@endsection
