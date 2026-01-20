@extends('wholesaler.layouts.app')
@push('title')
    {{ $product->name }} - Product Details
@endpush
@push('css')
    <style>
        .product-header {
            background: linear-gradient(135deg, var(--main-theme-color) 0%, #2c5282 100%);
            color: white;
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .product-image-container {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            margin-bottom: 1.5rem;
        }

        .main-product-image {
            width: 100%;
            height: 350px;
            object-fit: contain;
            border-radius: 10px;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            padding: 1rem;
        }

        .image-thumbnails {
            display: flex;
            gap: 10px;
            margin-top: 15px;
            overflow-x: auto;
            padding-bottom: 10px;
        }

        .thumbnail-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            cursor: pointer;
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }

        .thumbnail-img:hover,
        .thumbnail-img.active {
            border-color: var(--main-theme-color);
            transform: scale(1.05);
        }

        .info-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            border-left: 4px solid var(--main-theme-color);
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: #334155;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .info-value {
            color: #475569;
            font-weight: 500;
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

        .price-card {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border-radius: 12px;
            padding: 1.5rem;
            border: 1px solid #bae6fd;
        }

        .price-display {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--main-theme-color);
            margin-bottom: 1rem;
        }

        .stock-indicator {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 600;
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

        .badge-chip {
            background: #e2e8f0;
            color: #475569;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            margin-right: 6px;
            margin-bottom: 6px;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .description-content {
            line-height: 1.8;
            color: #475569;
            white-space: pre-line;
        }

        .feature-list {
            list-style: none;
            padding: 0;
        }

        .feature-list li {
            padding: 8px 0;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .feature-list li:last-child {
            border-bottom: none;
        }

        .feature-list li::before {
            content: "check_circle";
            font-family: 'Material Icons Outlined';
            color: var(--main-theme-color);
            font-size: 1.2rem;
        }

        @media (max-width: 768px) {
            .product-header {
                padding: 1.5rem;
            }

            .main-product-image {
                height: 250px;
            }

            .action-buttons {
                flex-direction: column;
            }

            .action-buttons .btn {
                width: 100%;
            }
        }
    </style>
@endpush

@section('content')
    <div class="product-header">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h2 class="fw-bold mb-2">{{ $product->name }}</h2>
                <p class="mb-0 opacity-90">
                    <span class="me-3">
                        <span class="material-icons-outlined me-1" style="vertical-align: middle;">inventory</span>
                        Product Code: {{ $product->code }}
                    </span>
                    @if($product->barcode)
                        <span>
                            <span class="material-icons-outlined me-1" style="vertical-align: middle;">qr_code</span>
                            Barcode: {{ $product->barcode }}
                        </span>
                    @endif
                </p>
            </div>
            <div class="status-badge {{ $product->status === 'active' ? 'status-active' : 'status-inactive' }}">
                <span class="material-icons-outlined">
                    {{ $product->status === 'active' ? 'check_circle' : 'cancel' }}
                </span>
                {{ ucfirst($product->status) }}
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left Column: Images -->
        <div class="col-lg-6">
            <div class="product-image-container">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}"
                         alt="{{ $product->name }}"
                         class="main-product-image"
                         id="mainProductImage">
                @else
                    <div class="main-product-image d-flex flex-column align-items-center justify-content-center">
                        <span class="material-icons-outlined" style="font-size: 4rem; color: #94a3b8;">
                            image
                        </span>
                        <p class="mt-3 text-muted">No image available</p>
                    </div>
                @endif
            </div>

            @if($product->description)
                <div class="info-card">
                    <h5 class="mb-3 d-flex align-items-center">
                        <span class="material-icons-outlined me-2">description</span>
                        Product Description
                    </h5>
                    <div class="description-content">
                        {{ $product->description }}
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Column: Product Details -->
        <div class="col-lg-6">
            <!-- Pricing Information -->
            <div class="info-card">
                <h5 class="mb-3 d-flex align-items-center">
                    <span class="material-icons-outlined me-2">payments</span>
                    Pricing Information
                </h5>
                {{-- Updated pricing information --}}
                <div class="price-card">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="text-muted mb-1">Display Price</div>
                                <div class="price-display">
                                    Rs. {{ number_format($product->display_price, 2) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="text-muted mb-1">Final Price</div>
                                <div class="price-display" style="color: #10b981;">
                                    Rs. {{ number_format($product->final_price, 2) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @if($product->discount > 0)
                        <div class="text-center">
                            <span class="badge bg-success">Discount: {{ $product->discount }}%</span>
                        </div>
                    @endif
                </div>
            <!-- Inventory Information -->
            <div class="info-card">
                <h5 class="mb-3 d-flex align-items-center">
                    <span class="material-icons-outlined me-2">inventory</span>
                    Inventory Details
                </h5>
                <div class="info-row">
                    <span class="info-label">
                        <span class="material-icons-outlined">warehouse</span>
                        Current Stock
                    </span>
                    <span class="info-value">
                        <span class="stock-indicator {{
                            $product->qty > 50 ? 'stock-high' :
                            ($product->qty > 10 ? 'stock-medium' : 'stock-low')
                        }}">
                            <span class="material-icons-outlined" style="font-size: 1rem;">
                                {{
                                    $product->qty > 50 ? 'check_circle' :
                                    ($product->qty > 10 ? 'warning' : 'error')
                                }}
                            </span>
                            {{ $product->qty }} units
                        </span>
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">
                        <span class="material-icons-outlined">shopping_cart</span>
                        Minimum Order
                    </span>
                    <span class="info-value">{{ $product->min_order_quantity }} units</span>
                </div>
            </div>

            <!-- Product Specifications -->
            <div class="info-card">
                <h5 class="mb-3 d-flex align-items-center">
                    <span class="material-icons-outlined me-2">tune</span>
                    Specifications
                </h5>
                <div class="info-row">
                    <span class="info-label">
                        <span class="material-icons-outlined">category</span>
                        Category
                    </span>
                    <span class="info-value">
                        {{ $product->category->name ?? 'N/A' }}
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">
                        <span class="material-icons-outlined">branding_watermark</span>
                        Brand
                    </span>
                    <span class="info-value">
                        {{ $product->brand->name ?? 'N/A' }}
                    </span>
                </div>
                @if($product->weight)
                    <div class="info-row">
                        <span class="info-label">
                            <span class="material-icons-outlined">scale</span>
                            Weight
                        </span>
                        <span class="info-value">{{ $product->weight }} kg</span>
                    </div>
                @endif
                @if($product->dimensions)
                    <div class="info-row">
                        <span class="info-label">
                            <span class="material-icons-outlined">straighten</span>
                            Dimensions
                        </span>
                        <span class="info-value">{{ $product->dimensions }}</span>
                    </div>
                @endif
            </div>

            <!-- Features -->
            @if($product->features)
                <div class="info-card">
                    <h5 class="mb-3 d-flex align-items-center">
                        <span class="material-icons-outlined me-2">star</span>
                        Product Features
                    </h5>
                    <ul class="feature-list">
                        @php
                            $features = array_filter(array_map('trim', explode(',', $product->features)));
                        @endphp
                        @foreach($features as $feature)
                            <li>{{ $feature }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Action Buttons -->
            <div class="info-card">
                <h5 class="mb-3 d-flex align-items-center">
                    <span class="material-icons-outlined me-2">settings</span>
                    Actions
                </h5>
                <div class="action-buttons">
                    <a href="{{ route('wholesaler.products.edit', $product->id) }}"
                       class="btn btn-warning d-flex align-items-center">
                        <span class="material-icons-outlined me-2">edit</span>
                        Edit Product
                    </a>
                    <a href="{{ route('wholesaler.products.index') }}"
                       class="btn btn-outline-secondary d-flex align-items-center">
                        <span class="material-icons-outlined me-2">arrow_back</span>
                        Back to Products
                    </a>
                    <form action="{{ route('wholesaler.products.destroy', $product->id) }}"
                          method="POST"
                          class="d-inline"
                          onsubmit="return confirm('Are you sure you want to delete this product?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger d-flex align-items-center">
                            <span class="material-icons-outlined me-2">delete</span>
                            Delete
                        </button>
                    </form>
                </div>
            </div>

            <!-- Product Information -->
            <div class="info-card mt-3">
                <h5 class="mb-3 d-flex align-items-center">
                    <span class="material-icons-outlined me-2">info</span>
                    Additional Information
                </h5>
                <div class="info-row">
                    <span class="info-label">
                        <span class="material-icons-outlined">calendar_today</span>
                        Created
                    </span>
                    <span class="info-value">
                        {{ $product->created_at->format('M d, Y h:i A') }}
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">
                        <span class="material-icons-outlined">update</span>
                        Last Updated
                    </span>
                    <span class="info-value">
                        {{ $product->updated_at->format('M d, Y h:i A') }}
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">
                        <span class="material-icons-outlined">person</span>
                        Added By
                    </span>
                    <span class="info-value">
                        {{ $product->wholesaler->name ?? 'System' }}
                    </span>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Image thumbnail functionality (if multiple images are implemented later)
            const thumbnails = document.querySelectorAll('.thumbnail-img');
            const mainImage = document.getElementById('mainProductImage');

            thumbnails.forEach(thumbnail => {
                thumbnail.addEventListener('click', function() {
                    // Update main image
                    mainImage.src = this.src;

                    // Update active thumbnail
                    thumbnails.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                });
            });

            // Print product details
            const printBtn = document.getElementById('printProduct');
            if (printBtn) {
                printBtn.addEventListener('click', function() {
                    window.print();
                });
            }

            // Share product functionality
            const shareBtn = document.getElementById('shareProduct');
            if (shareBtn && navigator.share) {
                shareBtn.addEventListener('click', async function() {
                    try {
                        await navigator.share({
                            title: '{{ $product->name }}',
                            text: 'Check out this product: {{ $product->name }}',
                            url: window.location.href,
                        });
                    } catch (error) {
                        console.log('Error sharing:', error);
                    }
                });
            }
        });
    </script>
@endpush
