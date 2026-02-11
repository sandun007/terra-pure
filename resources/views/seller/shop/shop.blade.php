@extends('seller.layout.app')

@push('title')
    Shop
@endpush

@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        :root {
            --primary-color: #4f46e5;
            --primary-hover: #4338ca;
            --success-color: #10b981;
            --danger-color: #ef4444;
            --warning-color: #f59e0b;
            --dark-color: #1f2937;
            --light-color: #f9fafb;
            --border-color: #e5e7eb;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
            --radius-lg: 12px;
            --radius-md: 8px;
            --radius-sm: 6px;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 24px;
            padding: 0;
        }

        .product-card {
            background: white;
            border-radius: var(--radius-lg);
            border: 1px solid var(--border-color);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
            box-shadow: var(--shadow-sm);
            position: relative;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-lg);
            border-color: var(--primary-color);
        }

        .product-img-container {
            height: 180px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        .product-img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            transition: transform 0.3s ease;
        }

        .product-card:hover .product-img {
            transform: scale(1.05);
        }

        .product-info {
            padding: 20px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .product-name {
            font-size: 15px;
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 8px;
            line-height: 1.4;
            height: 42px;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        .product-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .product-code {
            font-size: 12px;
            color: #6b7280;
            background: #f3f4f6;
            padding: 4px 10px;
            border-radius: 20px;
            font-weight: 600;
            font-family: 'Courier New', monospace;
        }

        .product-id {
            font-size: 11px;
            color: #9ca3af;
            background: #f8fafc;
            padding: 3px 8px;
            border-radius: 12px;
            border: 1px dashed #d1d5db;
            font-family: 'Courier New', monospace;
        }

        .product-price {
            font-size: 18px;
            color: var(--danger-color);
            font-weight: 700;
            margin: 12px 0;
        }

        .original-price {
            text-decoration: line-through;
            color: #9ca3af;
            font-size: 14px;
            font-weight: 400;
            margin-left: 8px;
        }

        .product-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-top: auto;
        }

        .action-btn {
            border: none;
            border-radius: var(--radius-md);
            padding: 10px 16px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .copy-btn {
            background: var(--dark-color);
            color: white;
        }

        .copy-btn:hover {
            background: #374151;
            transform: translateY(-1px);
        }

        .copy-btn:disabled {
            background: #d1d5db;
            cursor: not-allowed;
            transform: none;
        }

        .view-btn {
            background: var(--primary-color);
            color: white;
            text-decoration: none;
        }

        .view-btn:hover {
            background: var(--primary-hover);
            color: white;
            transform: translateY(-1px);
        }

        /* Badges */
        .badge-container {
            position: absolute;
            top: 12px;
            left: 12px;
            right: 12px;
            display: flex;
            justify-content: space-between;
            pointer-events: none;
        }

        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: var(--shadow-sm);
        }

        .discount-badge {
            background: linear-gradient(135deg, var(--danger-color), #dc2626);
            color: white;
        }

        .stock-badge {
            background: white;
            color: var(--dark-color);
            padding: 6px 12px;
        }

        .stock-in {
            color: var(--success-color);
            background: #d1fae5;
        }

        .stock-low {
            color: var(--warning-color);
            background: #fef3c7;
        }

        .stock-out {
            color: var(--danger-color);
            background: #fee2e2;
        }

        /* Search & Filter */
        .search-container {
            position: relative;
            max-width: 400px;
        }

        .search-input {
            width: 100%;
            padding: 14px 48px 14px 20px;
            border: 2px solid var(--border-color);
            border-radius: var(--radius-lg);
            font-size: 15px;
            transition: all 0.3s;
            background: white;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .search-btn {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #6b7280;
            cursor: pointer;
            padding: 4px;
            transition: color 0.2s;
        }

        .search-btn:hover {
            color: var(--primary-color);
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: var(--radius-lg);
            padding: 24px;
            border: 1px solid var(--border-color);
            transition: transform 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-4px);
        }

        .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: var(--dark-color);
            line-height: 1;
        }

        .stat-label {
            font-size: 14px;
            color: #6b7280;
            margin-top: 8px;
        }

        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
            font-size: 24px;
        }

        .stat-icon.total { background: #e0e7ff; color: var(--primary-color); }
        .stat-icon.stock { background: #d1fae5; color: var(--success-color); }
        .stat-icon.low { background: #fef3c7; color: var(--warning-color); }
        .stat-icon.out { background: #fee2e2; color: var(--danger-color); }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-radius: var(--radius-lg);
            margin: 40px 0;
            border: 2px dashed var(--border-color);
        }

        .empty-icon {
            font-size: 64px;
            color: #9ca3af;
            margin-bottom: 20px;
        }

        /* Pagination */
        .pagination-container {
            background: white;
            border-radius: var(--radius-lg);
            padding: 20px;
            margin-top: 40px;
            border: 1px solid var(--border-color);
        }

        .custom-pagination .page-link {
            border: none;
            color: var(--dark-color);
            padding: 10px 18px;
            margin: 0 4px;
            border-radius: var(--radius-md);
            font-weight: 600;
            transition: all 0.2s;
        }

        .custom-pagination .page-link:hover {
            background: var(--primary-color);
            color: white;
        }

        .custom-pagination .page-item.active .page-link {
            background: var(--primary-color);
            color: white;
            border: none;
        }

        /* Code Display Section */
        .code-display-section {
            background: white;
            border-radius: var(--radius-lg);
            padding: 24px;
            margin-bottom: 24px;
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-sm);
        }

        .code-display-title {
            font-size: 14px;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .code-value {
            font-family: 'Courier New', monospace;
            font-size: 24px;
            font-weight: 700;
            color: var(--primary-color);
            background: #f8fafc;
            padding: 12px 20px;
            border-radius: var(--radius-md);
            border: 2px dashed #e5e7eb;
            margin-bottom: 16px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }

        .copy-code-btn {
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: var(--radius-md);
            padding: 12px 24px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
        }

        .copy-code-btn:hover {
            background: var(--primary-hover);
            transform: translateY(-2px);
        }

        /* Quick Copy Section */
        .quick-copy-section {
            display: flex;
            gap: 12px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .quick-copy-btn {
            padding: 10px 20px;
            border: 2px solid var(--border-color);
            border-radius: var(--radius-lg);
            background: white;
            color: var(--dark-color);
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 8px;
            font-family: 'Courier New', monospace;
        }

        .quick-copy-btn:hover {
            border-color: var(--primary-color);
            background: var(--primary-color);
            color: white;
            transform: translateY(-2px);
        }

        /* Product ID and Code Container */
        .product-id-container {
            display: flex;
            gap: 8px;
            margin-top: 8px;
            flex-wrap: wrap;
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .product-grid {
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            }
        }

        @media (max-width: 768px) {
            .product-grid {
                grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
                gap: 16px;
            }

            .product-img-container {
                height: 140px;
            }

            .product-name {
                font-size: 14px;
                height: 40px;
            }

            .product-actions {
                grid-template-columns: 1fr;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .quick-copy-section {
                justify-content: center;
            }

            .quick-copy-btn {
                flex: 1;
                min-width: 150px;
                justify-content: center;
            }
        }

        @media (max-width: 576px) {
            .product-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
            }

            .product-img-container {
                height: 120px;
                padding: 12px;
            }

            .product-info {
                padding: 12px;
            }

            .product-price {
                font-size: 16px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .stat-card {
                padding: 20px;
            }

            .code-value {
                font-size: 18px;
                padding: 10px 16px;
            }

            .quick-copy-btn {
                min-width: 120px;
                font-size: 12px;
                padding: 8px 12px;
            }
        }

        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .product-card {
            animation: fadeIn 0.5s ease-out;
        }

        /* Custom Toast */
        .custom-toast {
            position: fixed;
            top: 24px;
            right: 24px;
            z-index: 9999;
            min-width: 300px;
            background: white;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-lg);
            border-left: 4px solid var(--success-color);
            padding: 16px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            transform: translateX(400px);
            transition: transform 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        .custom-toast.show {
            transform: translateX(0);
        }

        .toast-icon {
            font-size: 24px;
            color: var(--success-color);
        }

        /* Quick Actions */
        .quick-actions {
            display: flex;
            gap: 12px;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }

        .quick-btn {
            padding: 12px 24px;
            border: 2px solid var(--border-color);
            border-radius: var(--radius-lg);
            background: white;
            color: var(--dark-color);
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .quick-btn:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
            transform: translateY(-2px);
        }

        .quick-btn.active {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        /* Copied Animation */
        .copied-animation {
            animation: copiedPulse 1.5s ease-in-out;
        }

        @keyframes copiedPulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); box-shadow: 0 0 20px rgba(79, 70, 229, 0.3); }
            100% { transform: scale(1); }
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-6">
            <div class="mb-4 mb-md-0">
                <h1 class="h2 mb-2 d-flex align-items-center gap-2">
                    <span class="material-icons-round text-primary" style="font-size: 32px">storefront</span>
                    My Shop
                </h1>
                <p class="text-muted mb-0">Manage your products and inventory</p>
            </div>

            <div class="d-flex flex-column flex-sm-row gap-3 w-100 w-md-auto">
                <div class="search-container flex-grow-1">
                    <input type="text"
                           id="productSearch"
                           class="search-input"
                           placeholder="Search by product name, code, or ID..."
                           value="{{ request()->get('search', '') }}"
                           autocomplete="off">
                    <button class="search-btn" onclick="performSearch()">
                        <span class="material-icons-round">search</span>
                    </button>
                </div>
                <a href="{{ route('seller.product.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
                    <span class="material-icons-round">add</span>
                    Add Product
                </a>
            </div>
        </div>

        <!-- Next Product Code Display -->
        @if(isset($nextProductCode))
            <div class="code-display-section">
                <div class="code-display-title">
                    <span class="material-icons-round">qr_code</span>
                    Next Available Product Code
                </div>
                <div class="code-value" id="nextProductCode">
                    <span class="material-icons-round">tag</span>
                    {{ $nextProductCode }}
                </div>
                <button class="copy-code-btn" onclick="copyText('{{ $nextProductCode }}', 'Product Code')">
                    <span class="material-icons-round">content_copy</span>
                    Copy Product Code
                </button>
            </div>
        @endif

        <!-- Quick Copy Buttons -->
        <div class="quick-copy-section">
            <button class="quick-copy-btn" onclick="copyText('{{ $nextProductCode ?? 'DX/ITM/0001' }}', 'Product Code')">
                <span class="material-icons-round" style="font-size: 18px">tag</span>
                Copy Next Code
            </button>
            <button class="quick-copy-btn" onclick="copyToClipboard('DX/ITM/')">
                <span class="material-icons-round" style="font-size: 18px">content_copy</span>
                Copy Code Prefix
            </button>
            <button class="quick-copy-btn" onclick="copyToClipboard('ID: ')">
                <span class="material-icons-round" style="font-size: 18px">fingerprint</span>
                Copy ID Prefix
            </button>
        </div>

        <!-- Stats Overview -->
        <div class="stats-grid">
            @php
                $totalProducts = $products->total();
                $inStock = $products->where('qty', '>', 0)->count();
                $lowStock = $products->where('qty', '>', 0)->where('qty', '<=', 10)->count();
                $outOfStock = $products->where('qty', '<=', 0)->count();
            @endphp

            <div class="stat-card">
                <div class="stat-icon total">
                    <span class="material-icons-round">inventory_2</span>
                </div>
                <div class="stat-value">{{ $totalProducts }}</div>
                <div class="stat-label">Total Products</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon stock">
                    <span class="material-icons-round">check_circle</span>
                </div>
                <div class="stat-value">{{ $inStock }}</div>
                <div class="stat-label">In Stock</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon low">
                    <span class="material-icons-round">warning</span>
                </div>
                <div class="stat-value">{{ $lowStock }}</div>
                <div class="stat-label">Low Stock</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon out">
                    <span class="material-icons-round">cancel</span>
                </div>
                <div class="stat-value">{{ $outOfStock }}</div>
                <div class="stat-label">Out of Stock</div>
            </div>
        </div>

        <!-- Quick Filters -->
        <div class="quick-actions">
            <button class="quick-btn" onclick="filterProducts('all')">
                <span class="material-icons-round">grid_view</span>
                All Products
            </button>
            <button class="quick-btn" onclick="filterProducts('in_stock')">
                <span class="material-icons-round">check_circle</span>
                In Stock
            </button>
            <button class="quick-btn" onclick="filterProducts('low_stock')">
                <span class="material-icons-round">warning</span>
                Low Stock
            </button>
            <button class="quick-btn" onclick="filterProducts('out_of_stock')">
                <span class="material-icons-round">cancel</span>
                Out of Stock
            </button>
        </div>

        <!-- Messages -->
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-3 mb-4" role="alert">
                <span class="material-icons-round">error</span>
                <div class="flex-grow-1">{{ session('error') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-3 mb-4" role="alert">
                <span class="material-icons-round">check_circle</span>
                <div class="flex-grow-1">{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Products Grid -->
        @if($products->count() > 0)
            <div class="product-grid" id="productsContainer">
                @foreach($products as $product)
                    @php
                        $stockStatus = 'stock-out';
                        $stockIcon = 'cancel';
                        if ($product->qty > 0) {
                            $stockStatus = $product->qty <= $product->min_qty ? 'stock-low' : 'stock-in';
                            $stockIcon = $product->qty <= $product->min_qty ? 'warning' : 'check_circle';
                        }

                        $hasDiscount = $product->discount > 0;
                        $finalPrice = $hasDiscount ?
                            $product->display_price * (1 - $product->discount/100) :
                            $product->display_price;
                    @endphp

                    <div class="product-card" data-stock="{{ $stockStatus }}" data-id="{{ $product->id }}" data-code="{{ $product->code }}">
                        <div class="badge-container">
                            @if($hasDiscount)
                                <span class="badge discount-badge">
                                    <span class="material-icons-round" style="font-size: 14px; margin-right: 2px">local_offer</span>
                                    {{ $product->discount }}% OFF
                                </span>
                            @endif
                            <span class="badge stock-badge {{ $stockStatus }}">
                                <span class="material-icons-round" style="font-size: 14px; margin-right: 2px">{{ $stockIcon }}</span>
                                {{ $stockStatus === 'stock-in' ? 'In Stock' : ($stockStatus === 'stock-low' ? 'Low Stock' : 'Out of Stock') }}
                            </span>
                        </div>

                        <div class="product-img-container">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}"
                                     alt="{{ $product->name }}"
                                     class="product-img"
                                     onerror="this.onerror=null; this.src='{{ asset('images/default-product.png') }}'">
                            @else
                                <span class="material-icons-round" style="font-size: 64px; color: #9ca3af">inventory_2</span>
                            @endif
                        </div>

                        <div class="product-info">
                            <h3 class="product-name" title="{{ $product->name }}">
                                {{ Str::limit($product->name, 50) }}
                            </h3>

                            <div class="product-meta">
                                <span class="product-code" title="Product Code: {{ $product->code }}">
                                    <span class="material-icons-round" style="font-size: 14px; margin-right: 2px">qr_code</span>
                                    {{ $product->code }}
                                </span>
                                <span class="badge" style="background: #f3f4f6; color: #6b7280; font-size: 11px">
                                    Qty: {{ $product->qty }}
                                </span>
                            </div>

                            <div class="product-id-container">
                                <span class="product-id" title="Product ID: {{ $product->id }}">
                                    <span class="material-icons-round" style="font-size: 12px; margin-right: 2px">fingerprint</span>
                                    ID: {{ $product->id }}
                                </span>
                            </div>

                            <div class="product-price">
                                Rs. {{ number_format($finalPrice, 2) }}
                                @if($hasDiscount)
                                    <span class="original-price">
                                        Rs. {{ number_format($product->display_price, 2) }}
                                    </span>
                                @endif
                            </div>

                            <div class="product-actions">
                                <button class="action-btn copy-btn"
                                        onclick="copyProductCode('{{ $product->code }}', {{ $product->id }}, '{{ addslashes($product->name) }}')"
                                        {{ $product->qty <= 0 ? 'disabled' : '' }}
                                        data-product-name="{{ $product->name }}"
                                        data-product-code="{{ $product->code }}">
                                    <span class="material-icons-round" style="font-size: 18px">content_copy</span>
                                    Copy Code
                                </button>

                                <a href="{{ route('seller.shop.product.show', $product->id) }}"
                                   class="action-btn view-btn">
                                    <span class="material-icons-round" style="font-size: 18px">visibility</span>
                                    View
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <div class="empty-icon">
                    <span class="material-icons-round">inventory</span>
                </div>
                <h3 class="h4 mb-3">No Products Found</h3>
                <p class="text-muted mb-4">You haven't added any products to your shop yet.</p>
                <a href="{{ route('seller.product.create') }}" class="btn btn-primary btn-lg d-inline-flex align-items-center gap-2">
                    <span class="material-icons-round">add</span>
                    Add Your First Product
                </a>
            </div>
        @endif

        <!-- Pagination -->
        @if($products->hasPages())
            <div class="pagination-container">
                <div class="d-flex justify-content-center">
                    {{ $products->onEachSide(1)->links('vendor.pagination.bootstrap-5') }}
                </div>
            </div>
        @endif
    </div>

    <!-- Custom Toast -->
    <div class="custom-toast" id="customToast">
        <span class="material-icons-round toast-icon">check_circle</span>
        <div class="toast-content">
            <strong id="toastTitle">Success!</strong>
            <p id="toastMessage" class="mb-0">Operation completed successfully.</p>
        </div>
        <button class="btn-close ms-auto" onclick="hideToast()"></button>
    </div>
@endsection

@push('script')
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Initialize variables
        let currentFilter = 'all';
        let debounceTimer;

        // Show custom toast
        function showToast(title, message, type = 'success') {
            const toast = document.getElementById('customToast');
            const toastIcon = toast.querySelector('.toast-icon');
            const toastTitle = document.getElementById('toastTitle');
            const toastMessage = document.getElementById('toastMessage');

            // Set colors based on type
            const colors = {
                success: '#10b981',
                error: '#ef4444',
                warning: '#f59e0b',
                info: '#3b82f6'
            };

            toast.style.borderLeftColor = colors[type];
            toastIcon.style.color = colors[type];
            toastIcon.textContent = type === 'success' ? 'check_circle' :
                type === 'error' ? 'error' :
                    type === 'warning' ? 'warning' : 'info';

            toastTitle.textContent = title;
            toastMessage.textContent = message;

            toast.classList.add('show');

            // Auto hide after 5 seconds
            setTimeout(() => {
                hideToast();
            }, 5000);
        }

        // Hide toast
        function hideToast() {
            document.getElementById('customToast').classList.remove('show');
        }

        // Generic copy text function
        function copyText(text, label = 'Text') {
            navigator.clipboard.writeText(text).then(() => {
                showToast('Copied!', `${label} copied to clipboard`, 'success');

                // Add animation to the copied element
                if (label === 'Product Code') {
                    const codeElement = document.getElementById('nextProductCode');
                    if (codeElement) {
                        codeElement.classList.add('copied-animation');
                        setTimeout(() => {
                            codeElement.classList.remove('copied-animation');
                        }, 1500);
                    }
                }
            }).catch(err => {
                console.error('Failed to copy: ', err);
                showToast('Error', 'Failed to copy to clipboard', 'error');
            });
        }

        // Copy to clipboard helper
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                showToast('Copied!', 'Text copied to clipboard', 'success');
            }).catch(err => {
                console.error('Failed to copy: ', err);
                showToast('Error', 'Failed to copy to clipboard', 'error');
            });
        }

        // Main function to copy product code (DX/ITM/XXXX format)
        function copyProductCode(productCode, productId, productName) {
            // Check if product is in stock
            const productCard = document.querySelector(`[data-id="${productId}"]`);
            if (productCard && productCard.dataset.stock === 'stock-out') {
                Swal.fire({
                    icon: 'error',
                    title: 'Out of Stock',
                    text: `${productName} is currently out of stock and cannot be ordered.`,
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#ef4444'
                });
                return;
            }

            // Copy product code to clipboard
            navigator.clipboard.writeText(productCode).then(() => {
                // Show success message with options
                Swal.fire({
                    title: 'Product Code Copied!',
                    html: `
                        <div class="text-center">
                            <span class="material-icons-round text-success mb-3" style="font-size: 64px">content_copy</span>
                            <p><strong>${productName}</strong></p>
                            <div class="text-start bg-light p-3 rounded mb-3">
                                <p class="mb-1"><strong>Product ID:</strong> <code class="text-dark">${productId}</code></p>
                                <p class="mb-0"><strong>Product Code:</strong> <code class="text-dark fs-5 fw-bold text-primary">${productCode}</code></p>
                            </div>
                            <p class="text-muted small">Copied: ${productCode}</p>
                        </div>
                    `,
                    showDenyButton: true,
                    showCancelButton: true,
                    confirmButtonText: 'Go to New Order',
                    denyButtonText: 'Copy Product ID',
                    cancelButtonText: 'Close',
                    confirmButtonColor: '#4f46e5',
                    denyButtonColor: '#6b7280',
                    cancelButtonColor: '#9ca3af',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Redirect to new order page with product code
                        window.location.href = "{{ route('seller.new-order') }}?product_id=" + productId;
                    } else if (result.isDenied) {
                        // Copy product ID
                        copyText(productId.toString(), 'Product ID');
                    }
                });

                // Add animation to product card
                if (productCard) {
                    productCard.classList.add('copied-animation');
                    setTimeout(() => {
                        productCard.classList.remove('copied-animation');
                    }, 1500);
                }
            }).catch(err => {
                console.error('Failed to copy: ', err);
                showToast('Error', 'Failed to copy product code', 'error');
            });
        }

        // Search functionality with debounce
        function performSearch() {
            const searchTerm = document.getElementById('productSearch').value.trim();
            const url = new URL(window.location.href);

            if (searchTerm) {
                url.searchParams.set('search', searchTerm);
            } else {
                url.searchParams.delete('search');
            }

            // Remove filter param when searching
            if (currentFilter !== 'all') {
                url.searchParams.delete('filter');
                currentFilter = 'all';
                updateQuickButtons('all');
            }

            window.location.href = url.toString();
        }

        // Filter products
        function filterProducts(filter) {
            currentFilter = filter;
            const url = new URL(window.location.href);

            if (filter !== 'all') {
                url.searchParams.set('filter', filter);
            } else {
                url.searchParams.delete('filter');
            }

            // Clear search when filtering
            url.searchParams.delete('search');
            document.getElementById('productSearch').value = '';

            updateQuickButtons(filter);
            window.location.href = url.toString();
        }

        // Update quick filter buttons
        function updateQuickButtons(activeFilter) {
            document.querySelectorAll('.quick-btn').forEach(btn => {
                btn.classList.remove('active');
            });

            const activeBtn = document.querySelector(`[onclick="filterProducts('${activeFilter}')"]`);
            if (activeBtn) {
                activeBtn.classList.add('active');
            }
        }

        // Real-time search filtering
        document.getElementById('productSearch').addEventListener('input', function(e) {
            clearTimeout(debounceTimer);
            const keyword = this.value.toLowerCase().trim();

            debounceTimer = setTimeout(() => {
                const products = document.querySelectorAll('.product-card');
                let visibleCount = 0;

                products.forEach(card => {
                    const name = card.querySelector('.product-name').textContent.toLowerCase();
                    const code = card.querySelector('.product-code').textContent.toLowerCase();
                    const id = card.querySelector('.product-id').textContent.toLowerCase();

                    if (name.includes(keyword) || code.includes(keyword) || id.includes(keyword)) {
                        card.style.display = 'flex';
                        visibleCount++;
                    } else {
                        card.style.display = 'none';
                    }
                });

                // Show message if no results
                if (keyword && visibleCount === 0) {
                    showToast('No Results', 'No products match your search', 'info');
                }
            }, 300);
        });

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Get current filter from URL
            const urlParams = new URLSearchParams(window.location.search);
            const filter = urlParams.get('filter') || 'all';
            const productId = urlParams.get('product_id');
            const searchTerm = urlParams.get('search');

            // Set active filter button
            updateQuickButtons(filter);

            // Highlight product if ID is in URL
            if (productId) {
                const productCard = document.querySelector(`[data-id="${productId}"]`);
                if (productCard) {
                    setTimeout(() => {
                        productCard.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });

                        productCard.style.animation = 'none';
                        setTimeout(() => {
                            productCard.style.animation = 'fadeIn 0.5s ease-out';
                            productCard.style.boxShadow = '0 0 0 3px rgba(79, 70, 229, 0.3)';
                            productCard.style.borderColor = '#4f46e5';

                            setTimeout(() => {
                                productCard.style.boxShadow = '';
                                productCard.style.borderColor = '';
                            }, 3000);
                        }, 100);
                    }, 500);
                }
            }

            // Focus search if there's a search term
            if (searchTerm) {
                document.getElementById('productSearch').focus();
                document.getElementById('productSearch').select();
            }

            // Add event listener for Enter key in search
            document.getElementById('productSearch').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    performSearch();
                }
            });

            // Add click event to search button
            document.querySelector('.search-btn').addEventListener('click', performSearch);
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl/Cmd + F to focus search
            if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
                e.preventDefault();
                document.getElementById('productSearch').focus();
                document.getElementById('productSearch').select();
            }

            // Esc to clear search
            if (e.key === 'Escape') {
                document.getElementById('productSearch').value = '';
                document.getElementById('productSearch').dispatchEvent(new Event('input'));
            }
        });
    </script>
@endpush
