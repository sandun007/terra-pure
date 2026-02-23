@extends('wholesaler.layouts.app')
@push('title')
    {{ $shop->shop_name ?? 'My Shop' }} | Wholesale Marketplace
@endpush

@push('css')
    <style>
        /* Hero Section with Advanced Animation and Background Image */
        .shop-hero {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.95) 0%, rgba(139, 92, 246, 0.95) 50%, rgba(168, 85, 247, 0.95) 100%);
            padding: 3rem 0;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
            /* Add background image with asset route */
            background-image:
                url('{{ asset("asset/img/seller_3.jpg") }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        /* Alternative: If you want to use shop-specific image */
        .shop-hero.with-shop-image {
            background-image:
                linear-gradient(135deg, rgba(99, 102, 241, 0.95) 0%, rgba(139, 92, 246, 0.95) 50%, rgba(168, 85, 247, 0.95) 100%),
                url('{{ $shop->banner_image ? asset("storage/" . $shop->banner_image) : asset("images/default-hero.jpg") }}');
        }

        .shop-hero::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
            background-size: 50px 50px;
            animation: heroPattern 20s linear infinite;
            z-index: 1;
        }

        .shop-hero::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, transparent 30%, rgba(255,255,255,0.05) 50%, transparent 70%);
            animation: heroShine 3s ease-in-out infinite;
            z-index: 1;
        }

        /* Ensure content appears above pseudo-elements */
        .shop-hero .container {
            position: relative;
            z-index: 2;
        }

        @keyframes heroPattern {
            0% {
                transform: translate(0, 0);
            }
            100% {
                transform: translate(50px, 50px);
            }
        }

        @keyframes heroShine {
            0%, 100% {
                opacity: 0;
            }
            50% {
                opacity: 1;
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-10px);
            }
        }

        .shop-info-card {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            position: relative;
            z-index: 1;
            animation: fadeInUp 0.6s ease-out;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .shop-info-card::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(45deg, #6366f1, #8b5cf6, #a855f7, #6366f1);
            border-radius: 20px;
            z-index: -1;
            opacity: 0;
            transition: opacity 0.3s ease;
            background-size: 300% 300%;
            animation: gradientShift 3s ease infinite;
        }

        .shop-info-card:hover::before {
            opacity: 0.6;
        }

        @keyframes gradientShift {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
        }

        .shop-title {
            font-size: 2.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, #1e293b 0%, #6366f1 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1rem;
            animation: fadeInUp 0.8s ease-out 0.2s backwards;
        }

        .shop-description {
            animation: fadeInUp 0.8s ease-out 0.4s backwards;
        }

        .shop-badges {
            animation: fadeInUp 0.8s ease-out 0.6s backwards;
        }

        .badge {
            animation: scaleIn 0.5s ease-out backwards;
            transition: transform 0.3s ease;
        }

        .badge:hover {
            transform: scale(1.05) translateY(-2px);
        }

        .product-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            height: 100%;
            display: flex;
            flex-direction: column;
            border: 1px solid #e2e8f0;
            animation: fadeInUp 0.5s ease-out backwards;
        }

        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 35px rgba(99, 102, 241, 0.2);
        }

        .card-img-container {
            width: 100%;
            height: 200px;
            overflow: hidden;
            background: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            position: relative;
        }

        .card-img-container::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
            transition: left 0.5s ease;
        }

        .product-card:hover .card-img-container::after {
            left: 100%;
        }

        .card-img {
            max-height: 100%;
            max-width: 100%;
            object-fit: contain;
            transition: transform 0.3s ease;
        }

        .product-card:hover .card-img {
            transform: scale(1.05);
        }

        .card-content {
            padding: 1.25rem;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .card-title {
            font-size: 1rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.5rem;
            line-height: 1.4;
            height: 45px;
            overflow: hidden;
        }

        .product-pricing {
            margin: 1rem 0;
        }

        .price-display {
            font-size: 1.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .stock-info {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 1rem;
            font-size: 0.875rem;
            color: #64748b;
        }

        .stock-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #10b981;
            animation: pulse 2s ease-in-out infinite;
        }

        .stock-dot.low {
            background: #f59e0b;
        }

        .stock-dot.out {
            background: #ef4444;
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 1;
                transform: scale(1);
            }
            50% {
                opacity: 0.7;
                transform: scale(1.1);
            }
        }

        .quantity-controls {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 1rem;
        }

        .quantity-btn {
            width: 36px;
            height: 36px;
            border: 1px solid #e2e8f0;
            background: white;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .quantity-btn:hover:not(:disabled) {
            background: #6366f1;
            border-color: #6366f1;
            color: white;
            transform: scale(1.1);
        }

        .quantity-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .quantity-input {
            width: 60px;
            height: 36px;
            text-align: center;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            font-weight: 600;
            transition: border-color 0.2s ease;
        }

        .quantity-input:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .order-now-btn {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            position: relative;
            overflow: hidden;
        }

        .order-now-btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s ease, height 0.6s ease;
        }

        .order-now-btn:hover::before {
            width: 300px;
            height: 300px;
        }

        .order-now-btn:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(99, 102, 241, 0.4);
        }

        .order-now-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: white;
            border-radius: 12px;
            border: 2px dashed #e2e8f0;
            animation: fadeInUp 0.5s ease-out;
        }

        .empty-state-icon {
            font-size: 4rem;
            color: #cbd5e1;
            margin-bottom: 1rem;
            animation: float 3s ease-in-out infinite;
        }

        /* Google Icon Styling */
        .material-icons {
            font-family: 'Material Icons';
            font-weight: normal;
            font-style: normal;
            font-size: 20px;
            line-height: 1;
            letter-spacing: normal;
            text-transform: none;
            display: inline-block;
            white-space: nowrap;
            word-wrap: normal;
            direction: ltr;
            vertical-align: middle;
        }

        /* SweetAlert2 Custom Styling */
        .swal2-popup {
            border-radius: 16px !important;
            padding: 2rem !important;
        }

        .swal2-icon {
            border-width: 3px !important;
        }

        .swal2-confirm {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%) !important;
            border-radius: 8px !important;
            padding: 10px 30px !important;
            font-weight: 600 !important;
        }

        .swal2-cancel {
            border-radius: 8px !important;
            padding: 10px 30px !important;
            font-weight: 600 !important;
        }

        /* Loading Animation */
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .loading-spinner {
            animation: spin 1s linear infinite;
        }

        /* Stagger animation for product cards */
        .product-card:nth-child(1) { animation-delay: 0.1s; }
        .product-card:nth-child(2) { animation-delay: 0.2s; }
        .product-card:nth-child(3) { animation-delay: 0.3s; }
        .product-card:nth-child(4) { animation-delay: 0.4s; }
        .product-card:nth-child(5) { animation-delay: 0.5s; }
        .product-card:nth-child(6) { animation-delay: 0.6s; }
        .product-card:nth-child(7) { animation-delay: 0.7s; }
        .product-card:nth-child(8) { animation-delay: 0.8s; }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .shop-hero {
                background-attachment: scroll;
            }

            .shop-title {
                font-size: 2rem;
            }
        }
    </style>
    <!-- Google Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endpush

@section('content')
    <!-- Shop Hero with Advanced Animation and Background Image -->
    <div class="shop-hero {{ $shop->banner_image ? 'with-shop-image' : '' }}">
        <div class="container">
            <div class="shop-info-card">
                <h1 class="shop-title">{{ $shop->shop_name }}</h1>
                @if($shop->description)
                    <p class="text-muted shop-description">{{ Str::limit($shop->description, 200) }}</p>
                @endif
                <div class="d-flex gap-3 mt-3 shop-badges">
                    <span class="badge bg-primary" style="animation-delay: 0.7s;">
                        <i class="material-icons" style="font-size: 16px; vertical-align: middle;">inventory_2</i>
                        Products: {{ $products->total() }}
                    </span>
                    @if($shop->is_verified)
                        <span class="badge bg-success" style="animation-delay: 0.8s;">
                            <i class="material-icons" style="font-size: 16px; vertical-align: middle;">verified</i>
                            Verified Seller
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="container py-4">
        <!-- Search Bar -->
        <div class="mb-4">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <form action="{{ route('wholesaler.shop.index') }}" method="GET" class="d-flex gap-2">
                        <div class="flex-grow-1">
                            <input type="text"
                                   name="search"
                                   class="form-control"
                                   placeholder="Search products..."
                                   value="{{ request('search') }}">
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="material-icons" style="font-size: 18px; vertical-align: middle;">search</i>
                            Search
                        </button>
                        @if(request('search'))
                            <a href="{{ route('wholesaler.shop.index') }}" class="btn btn-outline-secondary">
                                <i class="material-icons" style="font-size: 18px; vertical-align: middle;">clear</i>
                            </a>
                        @endif
                    </form>
                </div>
                <div class="col-md-4 text-end">
                    <select class="form-select" onchange="window.location.href=this.value" style="max-width: 200px;">
                        <option value="{{ route('wholesaler.shop.index', ['sort' => 'newest']) }}"
                            {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                        <option value="{{ route('wholesaler.shop.index', ['sort' => 'price_low']) }}"
                            {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="{{ route('wholesaler.shop.index', ['sort' => 'price_high']) }}"
                            {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                        <option value="{{ route('wholesaler.shop.index', ['sort' => 'name_asc']) }}"
                            {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name: A-Z</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="row g-4">
            @forelse($products as $product)
                <div class="col-md-6 col-lg-4 col-xl-3">
                    <div class="product-card" data-product-id="{{ $product->id }}">
                        <div class="card-img-container">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}"
                                     alt="{{ $product->name }}"
                                     class="card-img">
                            @else
                                <i class="material-icons" style="font-size: 48px; color: #9ca3af;">inventory_2</i>
                            @endif
                        </div>

                        <div class="card-content">
                            <h3 class="card-title">{{ Str::limit($product->name, 60) }}</h3>

                            {{-- Updated price display section --}}
                            <div class="product-pricing">
                                <div class="price-display">Rs. {{ number_format($product->display_price, 2) }}</div>
                                @if($product->discount > 0)
                                    <div class="wholesale-price small">
                                        <i class="fas fa-store me-1"></i>
                                        After Discount: Rs. {{ number_format($product->final_price, 2) }}
                                        <span class="badge bg-success ms-1">-{{ $product->discount }}%</span>
                                    </div>
                                @endif
                            </div>

                            <div class="stock-info">
                                <div class="stock-dot {{ $product->qty > 50 ? '' : ($product->qty > 10 ? 'low' : 'out') }}"></div>
                                <span>{{ $product->qty }} in stock</span>
                            </div>

                            <div class="quantity-controls">
                                <button class="quantity-btn decrement-btn"
                                        onclick="decrementQuantity({{ $product->id }})"
                                        disabled>
                                    <i class="material-icons" style="font-size: 18px;">remove</i>
                                </button>
                                <input type="number"
                                       class="quantity-input"
                                       id="quantity-{{ $product->id }}"
                                       value="0"
                                       min="0"
                                       max="{{ $product->qty }}"
                                       data-price="{{ $product->display_price }}"
                                       data-name="{{ $product->name }}"
                                       oninput="updateQuantity({{ $product->id }}, this.value)">
                                <button class="quantity-btn increment-btn"
                                        onclick="incrementQuantity({{ $product->id }})">
                                    <i class="material-icons" style="font-size: 18px;">add</i>
                                </button>
                            </div>

                            <button class="order-now-btn"
                                    onclick="placeOrder({{ $product->id }})"
                                    id="order-btn-{{ $product->id }}"
                                    disabled>
                                <i class="material-icons" style="font-size: 20px;">shopping_bag</i>
                                Order Now
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="material-icons" style="font-size: 64px;">inventory_2</i>
                        </div>
                        <h4 class="mb-3">No Products Found</h4>
                        <p class="text-muted mb-4">Try adjusting your search criteria</p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($products->hasPages())
            <div class="mt-5">
                {{ $products->links() }}
            </div>
        @endif
    </div>
@endsection

@push('script')
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Quantity management
        function updateQuantity(productId, value) {
            const quantityInput = document.getElementById(`quantity-${productId}`);
            const orderBtn = document.getElementById(`order-btn-${productId}`);
            const decrementBtn = document.querySelector(`.decrement-btn[onclick="decrementQuantity(${productId})"]`);
            const incrementBtn = document.querySelector(`.increment-btn[onclick="incrementQuantity(${productId})"]`);

            let quantity = parseInt(value) || 0;
            const maxQuantity = parseInt(quantityInput.max) || 0;

            // Validate input
            if (isNaN(quantity) || quantity < 0) quantity = 0;
            if (quantity > maxQuantity) {
                quantity = maxQuantity;
                Swal.fire({
                    icon: 'warning',
                    title: 'Stock Limit',
                    text: `Only ${maxQuantity} items available`,
                    timer: 2000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            }

            // Update input
            quantityInput.value = quantity;

            // Update button states
            orderBtn.disabled = quantity === 0;
            decrementBtn.disabled = quantity <= 0;
            incrementBtn.disabled = quantity >= maxQuantity;

            // Store quantity in data attribute
            orderBtn.setAttribute('data-quantity', quantity);
        }

        function incrementQuantity(productId) {
            const quantityInput = document.getElementById(`quantity-${productId}`);
            let currentValue = parseInt(quantityInput.value) || 0;
            const maxQuantity = parseInt(quantityInput.max) || 0;

            if (currentValue < maxQuantity) {
                updateQuantity(productId, currentValue + 1);
            }
        }

        function decrementQuantity(productId) {
            const quantityInput = document.getElementById(`quantity-${productId}`);
            let currentValue = parseInt(quantityInput.value) || 0;

            if (currentValue > 0) {
                updateQuantity(productId, currentValue - 1);
            }
        }

        // Direct order placement with SweetAlert
        async function placeOrder(productId) {
            const quantityInput = document.getElementById(`quantity-${productId}`);
            const quantity = parseInt(quantityInput.value) || 0;
            const productName = quantityInput.getAttribute('data-name');
            const productPrice = quantityInput.getAttribute('data-price');
            const orderBtn = document.getElementById(`order-btn-${productId}`);

            if (quantity === 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Please select quantity first',
                    confirmButtonText: 'Got it!'
                });
                return;
            }

            // Confirmation dialog
            const result = await Swal.fire({
                title: 'Confirm Order',
                html: `
                    <div style="text-align: left; padding: 1rem;">
                        <p><strong>Product:</strong> ${productName}</p>
                        <p><strong>Quantity:</strong> ${quantity}</p>
                        <p><strong>Total:</strong> Rs. ${(productPrice * quantity).toLocaleString('en-US', {minimumFractionDigits: 2})}</p>
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, Place Order',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#6366f1',
                cancelButtonColor: '#64748b'
            });

            if (!result.isConfirmed) return;

            // Show loading
            const originalText = orderBtn.innerHTML;
            orderBtn.innerHTML = '<i class="material-icons loading-spinner" style="font-size: 20px;">refresh</i> Processing...';
            orderBtn.disabled = true;

            try {
                const response = await fetch('{{ route("wholesaler.shop.order.now") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity: quantity,
                        customer_name: '{{ Auth::guard("wholesaler")->user()->name }}',
                        customer_email: '{{ Auth::guard("wholesaler")->user()->email }}',
                        customer_phone: '{{ Auth::guard("wholesaler")->user()->phone ?? "" }}',
                        shipping_address: 'Default Address',
                        payment_method: 'cash_on_delivery',
                        notes: ''
                    })
                });

                const data = await response.json();

                if (data.success) {
                    await Swal.fire({
                        icon: 'success',
                        title: 'Order Placed!',
                        text: data.message,
                        confirmButtonText: 'View Order',
                        confirmButtonColor: '#6366f1',
                        timer: 3000
                    });

                    // Reset quantity
                    updateQuantity(productId, 0);

                    // Redirect to order page
                    window.location.href = data.redirect;
                } else {
                    let errorMessage = data.message;
                    if (data.errors) {
                        errorMessage += '\n' + Object.values(data.errors).map(e => e[0]).join('\n');
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Order Failed',
                        text: errorMessage,
                        confirmButtonText: 'Try Again',
                        confirmButtonColor: '#ef4444'
                    });
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Network Error',
                    text: 'An error occurred. Please check your connection and try again.',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#ef4444'
                });
            } finally {
                orderBtn.innerHTML = originalText;
                orderBtn.disabled = false;
            }
        }

        // Initialize quantity controls
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.quantity-input').forEach(input => {
                updateQuantity(input.id.replace('quantity-', ''), 0);
            });

            // Add smooth scroll behavior
            document.documentElement.style.scrollBehavior = 'smooth';
        });
    </script>
@endpush
