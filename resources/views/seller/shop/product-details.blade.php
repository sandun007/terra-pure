@extends('seller.layout.app')
@push('title')
    {{ $product->name }} - Product Details
@endpush
@push('css')
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --secondary: #8b5cf6;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
        }

        .product-hero {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .product-image-main {
            width: 100%;
            height: 400px;
            object-fit: contain;
            background: white;
            border-radius: 15px;
            padding: 20px;
            border: 1px solid #e2e8f0;
        }

        .product-thumbnails {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        .product-thumbnail {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 10px;
            cursor: pointer;
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }

        .product-thumbnail:hover,
        .product-thumbnail.active {
            border-color: var(--primary);
        }

        .product-info {
            padding: 20px;
        }

        .product-title {
            font-size: 2rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 1rem;
        }

        .product-meta {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .product-rating {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .product-price {
            margin-bottom: 1.5rem;
        }

        .current-price {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--primary);
        }

        .original-price {
            font-size: 1.2rem;
            color: #94a3b8;
            text-decoration: line-through;
            margin-left: 10px;
        }

        /* Quantity Controls */
        .quantity-section {
            margin-bottom: 1.5rem;
        }

        .quantity-control-detailed {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 10px;
        }

        .quantity-btn-detailed {
            width: 48px;
            height: 48px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            background: white;
            color: #1e293b;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1.2rem;
            font-weight: bold;
        }

        .quantity-btn-detailed:hover:not(:disabled) {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
            transform: translateY(-2px);
        }

        .quantity-btn-detailed:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .quantity-input-detailed {
            width: 100px;
            height: 48px;
            text-align: center;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-weight: 700;
            font-size: 1.2rem;
            color: #1e293b;
            transition: all 0.3s ease;
        }

        .quantity-input-detailed:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .stock-info {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 1.5rem;
            padding: 12px 15px;
            background: #f8fafc;
            border-radius: 10px;
        }

        .stock-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: {{ $product->qty > 50 ? '#10b981' : ($product->qty > 10 ? '#f59e0b' : '#ef4444') }};
            animation: pulse 2s infinite;
        }

        .stock-text {
            font-weight: 600;
            color: #475569;
        }

        .action-buttons {
            display: flex;
            gap: 15px;
            margin-bottom: 2rem;
        }

        .btn-action {
            flex: 1;
            padding: 16px 24px;
            border: none;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-add-to-cart {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
        }

        .btn-add-to-cart:hover:not(:disabled) {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(99, 102, 241, 0.4);
        }

        .btn-buy-now {
            background: linear-gradient(135deg, var(--secondary) 0%, #7c3aed 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(139, 92, 246, 0.3);
        }

        .btn-buy-now:hover:not(:disabled) {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(139, 92, 246, 0.4);
        }

        .btn-action:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none !important;
        }

        .product-tabs {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            margin-top: 2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .tab-content {
            padding: 1.5rem 0;
        }

        .product-description {
            line-height: 1.8;
            color: #475569;
        }

        .specifications-table {
            width: 100%;
            border-collapse: collapse;
        }

        .specifications-table th,
        .specifications-table td {
            padding: 12px 16px;
            border-bottom: 1px solid #e2e8f0;
            text-align: left;
        }

        .specifications-table th {
            background: #f8fafc;
            font-weight: 600;
            color: #1e293b;
            width: 30%;
        }

        .related-products {
            margin-top: 3rem;
        }

        .related-product-card {
            background: white;
            border-radius: 12px;
            padding: 1rem;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .related-product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            border-color: var(--primary);
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        @media (max-width: 768px) {
            .product-title {
                font-size: 1.5rem;
            }

            .current-price {
                font-size: 2rem;
            }

            .action-buttons {
                flex-direction: column;
            }

            .product-image-main {
                height: 300px;
            }
        }
    </style>
@endpush

@section('content')
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('seller.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('seller.shop.index') }}">Shop</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
        </ol>
    </nav>

    <!-- Product Details -->
    <div class="product-hero">
        <div class="row">
            <!-- Product Images -->
            <div class="col-lg-6 mb-4">
                <div class="product-image-container">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}"
                             alt="{{ $product->name }}"
                             class="product-image-main"
                             id="mainImage">
                    @else
                        <div class="product-image-main d-flex align-items-center justify-content-center bg-light">
                            <i class="fas fa-box-open fa-4x text-muted"></i>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Product Info -->
            <div class="col-lg-6">
                <div class="product-info">
                    <!-- Title and Meta -->
                    <h1 class="product-title">{{ $product->name }}</h1>

                    <div class="product-meta">
                        <div class="product-sku">
                            <i class="fas fa-barcode me-2"></i>
                            <span class="text-muted">Code: {{ $product->code }}</span>
                        </div>

                        <div class="product-category">
                            <i class="fas fa-tag me-2"></i>
                            <span class="text-muted">{{ $product->category->name ?? 'Uncategorized' }}</span>
                        </div>
                    </div>

                    <!-- Pricing Information -->
                    <div class="info-card mb-4">
                        <h5 class="mb-3 d-flex align-items-center">
                            <i class="fas fa-money-bill-wave me-2"></i>
                            Pricing Information
                        </h5>
                        <div class="price-card">
                            <div class="row">
                                <!-- Stock Price -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <div class="text-muted mb-1">Stock Price</div>
                                        <div class="price-display" style="color: #6b7280;">
                                            Rs. {{ number_format($product->stock_price, 2) }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Display Price -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <div class="text-muted mb-1">Display Price</div>
                                        <div class="price-display" style="color: var(--primary);">
                                            Rs. {{ number_format($product->display_price, 2) }}
                                            @if($product->discount > 0)
                                                <span class="badge bg-success ms-2">-{{ $product->discount }}%</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Profit Calculation -->
                            @if($product->display_price > $product->stock_price)
                                <div class="mt-3 pt-3 border-top">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="text-center">
                                                <div class="text-muted small">Profit Amount</div>
                                                <div class="fw-bold text-success">
                                                    Rs. {{ number_format($product->display_price - $product->stock_price, 2) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="text-center">
                                                <div class="text-muted small">Profit Margin</div>
                                                <div class="fw-bold text-success">
                                                    @php
                                                        $profitMargin = (($product->display_price - $product->stock_price) / $product->stock_price) * 100;
                                                    @endphp
                                                    {{ number_format($profitMargin, 1) }}%
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Quantity Control Section -->
                    <div class="quantity-section">
                        <label class="fw-bold mb-2 d-block">Quantity:</label>
                        <div class="quantity-control-detailed">
                            <button class="quantity-btn-detailed decrement-qty-detailed"
                                    type="button"
                                    onclick="decrementQuantityDetailed()"
                                    disabled>
                                <i class="fas fa-minus"></i>
                            </button>
                            <input type="number"
                                   id="productQuantity"
                                   class="form-control text-center quantity-input-detailed"
                                   value="0"
                                   min="0"
                                   max="{{ $product->qty }}"
                                   data-price="{{ $product->display_price }}"
                                   oninput="updateQuantityDetailed(this.value)">
                            <button class="quantity-btn-detailed increment-qty-detailed"
                                    type="button"
                                    onclick="incrementQuantityDetailed()"
                                {{ $product->qty == 0 ? 'disabled' : '' }}>
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        <div class="mt-2">
                            @if($product->qty <= 0)
                                <div class="alert alert-danger py-2 mb-2">
                                    <i class="fas fa-times-circle me-2"></i>
                                    Out of Stock
                                </div>
                            @elseif($product->qty < $product->min_qty)
                                <div class="alert alert-warning py-2 mb-2">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    Low Stock: Only {{ $product->qty }} items left
                                </div>
                            @else
                                <div class="text-muted small">
                                    <i class="fas fa-check-circle me-2 text-success"></i>
                                    {{ $product->qty }} items available
                                </div>
                            @endif
                            <div class="text-muted small">
                                <i class="fas fa-shopping-cart me-2"></i>
                                Minimum Order: {{ $product->min_qty }}
                            </div>
                        </div>
                    </div>

                    <!-- Total Price Display -->
                    <div class="total-price-display mb-3">
                        <div class="d-flex justify-content-between align-items-center bg-light p-3 rounded">
                            <span class="fw-bold">Total:</span>
                            <span class="fs-4 fw-bold text-primary" id="totalPriceDetailed">Rs. 0</span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="action-buttons">
                        <button class="btn-action btn-add-to-cart"
                                onclick="addToCart({{ $product->id }})"
                                id="addToCartBtn"
                                disabled>
                            <i class="fas fa-cart-plus me-2"></i>
                            Add to Cart
                        </button>
                        <button class="btn-action btn-buy-now"
                                onclick="buyNow({{ $product->id }})"
                                id="buyNowBtn"
                                disabled>
                            <i class="fas fa-bolt me-2"></i>
                            Buy Now
                        </button>
                    </div>

                    <!-- Product Features -->
                    @if($product->features)
                        <div class="product-features mb-4">
                            <h6 class="fw-bold mb-3">
                                <i class="fas fa-star me-2"></i>
                                Key Features
                            </h6>
                            <ul class="list-unstyled">
                                @foreach(explode(',', $product->features) as $feature)
                                    <li class="mb-2">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        {{ trim($feature) }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Product Tabs -->
    <div class="product-tabs">
        <ul class="nav nav-tabs" id="productTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button">
                    <i class="fas fa-file-alt me-2"></i>
                    Description
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="specifications-tab" data-bs-toggle="tab" data-bs-target="#specifications" type="button">
                    <i class="fas fa-list me-2"></i>
                    Specifications
                </button>
            </li>
        </ul>

        <div class="tab-content" id="productTabContent">
            <!-- Description Tab -->
            <div class="tab-pane fade show active" id="description">
                <div class="product-description">
                    {!! $product->description ? nl2br(e($product->description)) : '<p class="text-muted">No description available.</p>' !!}
                </div>
            </div>

            <!-- Specifications Tab -->
            <div class="tab-pane fade" id="specifications">
                <div class="product-specifications">
                    <table class="specifications-table">
                        <tbody>
                        <tr>
                            <th>Product Code</th>
                            <td>{{ $product->code }}</td>
                        </tr>
                        <tr>
                            <th>Category</th>
                            <td>{{ $product->category->name ?? 'Not specified' }}</td>
                        </tr>
                        <tr>
                            <th>Brand</th>
                            <td>{{ $product->brand->name ?? 'Not specified' }}</td>
                        </tr>
                        <tr>
                            <th>Type</th>
                            <td>{{ ucfirst($product->type) }}</td>
                        </tr>
                        <tr>
                            <th>Stock</th>
                            <td>{{ $product->qty }} units</td>
                        </tr>
                        <tr>
                            <th>Minimum Order</th>
                            <td>{{ $product->min_qty }} units</td>
                        </tr>
                        @if($product->barcode)
                            <tr>
                                <th>Barcode</th>
                                <td>{{ $product->barcode }}</td>
                            </tr>
                        @endif
                        <tr>
                            <th>Status</th>
                            <td>
                                    <span class="badge bg-{{ $product->status == 'active' ? 'success' : 'danger' }}">
                                        {{ ucfirst($product->status) }}
                                    </span>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
        <div class="related-products">
            <h3 class="fw-bold mb-4">Related Products</h3>
            <div class="row">
                @foreach($relatedProducts as $relatedProduct)
                    <div class="col-md-3 mb-4">
                        <a href="{{ route('seller.shop.product.show', $relatedProduct->id) }}" class="related-product-card">
                            @if($relatedProduct->image)
                                <img src="{{ asset('storage/' . $relatedProduct->image) }}"
                                     class="img-fluid mb-3 rounded"
                                     alt="{{ $relatedProduct->name }}"
                                     style="height: 150px; width: 100%; object-fit: cover;">
                            @else
                                <div class="bg-light rounded mb-3 d-flex align-items-center justify-content-center"
                                     style="height: 150px;">
                                    <i class="fas fa-box-open fa-2x text-muted"></i>
                                </div>
                            @endif
                            <h6 class="mb-2">{{ Str::limit($relatedProduct->name, 40) }}</h6>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold text-primary">
                                    Rs. {{ number_format($relatedProduct->display_price) }}
                                </span>
                                <span class="badge bg-{{ $relatedProduct->qty > 0 ? 'success' : 'danger' }}">
                                    {{ $relatedProduct->qty }} in stock
                                </span>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
@endsection

@push('script')
    <script>
        // Product details quantity functions
        function updateQuantityDetailed(value) {
            const quantityInput = document.getElementById('productQuantity');
            const decrementBtn = document.querySelector('.decrement-qty-detailed');
            const incrementBtn = document.querySelector('.increment-qty-detailed');
            const addToCartBtn = document.getElementById('addToCartBtn');
            const buyNowBtn = document.getElementById('buyNowBtn');
            const totalPriceElement = document.getElementById('totalPriceDetailed');

            let quantity = parseInt(value) || 0;
            const maxQuantity = parseInt(quantityInput.max) || 0;
            const unitPrice = parseFloat(quantityInput.dataset.price) || 0;

            // Validate input
            if (isNaN(quantity) || quantity < 0) quantity = 0;
            if (quantity > maxQuantity) {
                quantity = maxQuantity;
                showNotification('warning', `Only ${maxQuantity} items available`);
            }

            // Update input value
            quantityInput.value = quantity;

            // Update button states
            decrementBtn.disabled = quantity <= 0;
            incrementBtn.disabled = quantity >= maxQuantity;

            // Update cart buttons
            addToCartBtn.disabled = quantity === 0;
            buyNowBtn.disabled = quantity === 0;

            // Calculate and update total price
            const totalPrice = quantity * unitPrice;
            totalPriceElement.textContent = 'Rs. ' + totalPrice.toLocaleString();
        }

        function incrementQuantityDetailed() {
            const quantityInput = document.getElementById('productQuantity');
            let currentValue = parseInt(quantityInput.value) || 0;
            const maxQuantity = parseInt(quantityInput.max) || 0;

            if (currentValue < maxQuantity) {
                updateQuantityDetailed(currentValue + 1);
            }
        }

        function decrementQuantityDetailed() {
            const quantityInput = document.getElementById('productQuantity');
            let currentValue = parseInt(quantityInput.value) || 0;

            if (currentValue > 0) {
                updateQuantityDetailed(currentValue - 1);
            }
        }

        // Add to cart function for product details
        async function addToCart(productId) {
            const quantityInput = document.getElementById('productQuantity');
            const quantity = parseInt(quantityInput.value) || 0;

            if (quantity === 0) {
                showNotification('error', 'Please select quantity first');
                return;
            }

            const addToCartBtn = document.getElementById('addToCartBtn');
            const originalText = addToCartBtn.innerHTML;

            // Show loading
            addToCartBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Adding...';
            addToCartBtn.disabled = true;

            try {
                // Placeholder for cart functionality
                {{--// const response = await fetch('{{ route("seller.shop.cart.add") }}', {--}}
                //     method: 'POST',
                //     headers: {
                //         'Content-Type': 'application/json',
                //         'X-CSRF-TOKEN': '{{ csrf_token() }}'
                //     },
                //     body: JSON.stringify({
                //         product_id: productId,
                //         quantity: quantity
                //     })
                // });

                // For now, show success message
                await new Promise(resolve => setTimeout(resolve, 1000));

                showNotification('success', `${quantity} item(s) added to cart!`);

                // Reset quantity to 0 after successful add
                updateQuantityDetailed(0);

            } catch (error) {
                console.error('Error:', error);
                showNotification('error', 'An error occurred. Please try again.');
            } finally {
                addToCartBtn.innerHTML = originalText;
                addToCartBtn.disabled = quantity === 0;
            }
        }

        // Buy now function
        async function buyNow(productId) {
            const quantityInput = document.getElementById('productQuantity');
            const quantity = parseInt(quantityInput.value) || 0;

            if (quantity === 0) {
                showNotification('error', 'Please select quantity first');
                return;
            }

            const buyNowBtn = document.getElementById('buyNowBtn');
            const originalText = buyNowBtn.innerHTML;

            // Show loading
            buyNowBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Processing...';
            buyNowBtn.disabled = true;

            try {
                // Direct order functionality
                const response = await fetch('{{ route("seller.shop.order.now") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity: quantity
                    })
                });

                const data = await response.json();

                if (data.success) {
                    window.location.href = data.redirect;
                } else {
                    showNotification('error', data.message || 'Failed to process buy now');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('error', 'An error occurred. Please try again.');
            } finally {
                buyNowBtn.innerHTML = originalText;
                buyNowBtn.disabled = quantity === 0;
            }
        }

        // Helper function for notifications
        function showNotification(type, message) {
            const icon = type === 'success' ? 'fas fa-check-circle' :
                type === 'error' ? 'fas fa-times-circle' :
                    'fas fa-exclamation-triangle';

            const alert = document.createElement('div');
            alert.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
            alert.style.cssText = 'top: 20px; right: 20px; z-index: 1050; min-width: 300px;';
            alert.innerHTML = `
                <i class="${icon} me-2"></i> ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;

            document.body.appendChild(alert);

            // Auto remove after 5 seconds
            setTimeout(() => {
                if (alert.parentNode) {
                    alert.parentNode.removeChild(alert);
                }
            }, 5000);
        }

        // Initialize quantity
        document.addEventListener('DOMContentLoaded', function() {
            updateQuantityDetailed(0);
        });
    </script>
@endpush
