@extends('admin.app')
@push('title')
    View Product - {{ $product->name }}
@endpush
@push('css')
    <style>
        .product-image {
            width: 100%;
            max-width: 400px;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .image-gallery {
            display: flex;
            gap: 10px;
            margin-top: 15px;
            flex-wrap: wrap;
        }

        .gallery-thumb {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 6px;
            cursor: pointer;
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }

        .gallery-thumb:hover,
        .gallery-thumb.active {
            border-color: var(--main-theme-color);
        }

        .info-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            padding: 25px;
            margin-bottom: 25px;
            border: 1px solid #e9ecef;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #f8f9fa;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: #495057;
        }

        .info-value {
            color: #6c757d;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .status-active {
            background-color: #d4edda;
            color: #155724;
        }

        .status-inactive {
            background-color: #f8d7da;
            color: #721c24;
        }

        .price-highlight {
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--main-theme-color);
        }

        .stock-warning {
            color: #dc3545;
            font-weight: 600;
        }

        .stock-good {
            color: #28a745;
            font-weight: 600;
        }
    </style>
@endpush

@section('content')
    <div class="page-header">
        <div class="page-title">
            <h3 class="fw-bold">Product Details</h3>
            <p class="text-muted">View complete information about {{ $product->name }}</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('product.index') }}" class="btn btn-outline-secondary me-2">
                <i class="fas fa-arrow-left me-2"></i>Back to Products
            </a>
            <a href="{{ route('product.edit', $product->id) }}" class="btn btn-warning me-2">
                <i class="fas fa-edit me-2"></i>Edit Product
            </a>
            <a href="{{ route('product.delete', $product->id) }}" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this product?')">
                <i class="fas fa-trash me-2"></i>Delete
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-5">
            <div class="info-card">
                <h5 class="mb-4"><i class="fas fa-images me-2"></i>Product Images</h5>
                @if($product->img)
                    @php
                        $images = json_decode($product->img, true);
                        $mainImage = is_array($images) ? $images[0] : $product->img;
                    @endphp
                    <img src="{{ asset('storage/' . $mainImage) }}" alt="{{ $product->name }}" class="product-image" id="mainImage">

                    @if(is_array($images) && count($images) > 1)
                        <div class="image-gallery">
                            @foreach($images as $image)
                                <img src="{{ asset('storage/' . $image) }}"
                                     alt="{{ $product->name }}"
                                     class="gallery-thumb {{ $loop->first ? 'active' : '' }}"
                                     onclick="changeMainImage('{{ asset('storage/' . $image) }}', this)">
                            @endforeach
                        </div>
                    @endif
                @else
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-image fa-3x mb-3"></i>
                        <p>No images available</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="col-lg-7">
            <div class="info-card">
                <h5 class="mb-4"><i class="fas fa-info-circle me-2"></i>Basic Information</h5>
                <div class="info-row">
                    <span class="info-label">Product Code:</span>
                    <span class="info-value">{{ $product->code }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Product Name:</span>
                    <span class="info-value">{{ $product->name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Barcode:</span>
                    <span class="info-value">{{ $product->barcode ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Brand:</span>
                    <span class="info-value">{{ $product->brand ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Category:</span>
                    <span class="info-value">{{ $product->category ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Status:</span>
                    <span class="status-badge {{ $product->status === 'active' ? 'status-active' : 'status-inactive' }}">
                        {{ ucfirst($product->status) }}
                    </span>
                </div>
            </div>

            <div class="info-card">
                <h5 class="mb-4"><i class="fas fa-cube me-2"></i>Inventory & Pricing</h5>
                <div class="info-row">
                    <span class="info-label">Stock Quantity:</span>
                    <span class="info-value {{ $product->qty < $product->min_qty ? 'stock-warning' : 'stock-good' }}">
                        {{ $product->qty }}
                        @if($product->qty < $product->min_qty)
                            <i class="fas fa-exclamation-triangle ms-1"></i>
                        @endif
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Minimum Stock Level:</span>
                    <span class="info-value">{{ $product->min_qty }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Cost Price:</span>
                    <span class="info-value">Rs. {{ number_format($product->stock_price, 2) }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Selling Price:</span>
                    <span class="info-value">Rs. {{ number_format($product->display_price, 2) }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Discount:</span>
                    <span class="info-value">{{ $product->discount }}%</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Packing Cost:</span>
                    <span class="info-value">Rs. {{ number_format($product->packing_cost, 2) }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Final Price:</span>
                    <span class="price-highlight">
                        Rs. {{ number_format($product->display_price - ($product->display_price * $product->discount / 100) + $product->packing_cost, 2) }}
                    </span>
                </div>
            </div>

            @if($product->description)
                <div class="info-card">
                    <h5 class="mb-4"><i class="fas fa-file-alt me-2"></i>Description</h5>
                    <p class="info-value">{{ $product->description }}</p>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('script')
    <script>
        function changeMainImage(src, element) {
            document.getElementById('mainImage').src = src;

            // Remove active class from all thumbnails
            document.querySelectorAll('.gallery-thumb').forEach(thumb => {
                thumb.classList.remove('active');
            });

            // Add active class to clicked thumbnail
            element.classList.add('active');
        }
    </script>
@endpush
