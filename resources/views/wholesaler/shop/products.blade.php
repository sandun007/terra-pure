@extends('wholesaler.layouts.app')
@push('title')
    All Products - Wholesale Shop
@endpush
@push('css')
    <style>
        .products-page {
            padding: 60px 0;
            background: var(--light-bg);
        }

        .page-header-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            padding: 60px 0;
            color: white;
            margin-bottom: 60px;
        }

        .page-header-title {
            font-size: 42px;
            font-weight: 900;
            margin-bottom: 15px;
        }

        .page-header-subtitle {
            font-size: 18px;
            opacity: 0.9;
        }

        /* Filter Sidebar */
        .filter-sidebar {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            position: sticky;
            top: 120px;
            max-height: calc(100vh - 150px);
            overflow-y: auto;
        }

        .filter-sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .filter-sidebar::-webkit-scrollbar-track {
            background: var(--light-bg);
            border-radius: 3px;
        }

        .filter-sidebar::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 3px;
        }

        .filter-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 2px solid var(--border-color);
        }

        .filter-title {
            font-size: 22px;
            font-weight: 800;
            color: var(--secondary-color);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .clear-filters {
            background: none;
            border: none;
            color: var(--primary-color);
            font-weight: 600;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .clear-filters:hover {
            color: var(--secondary-color);
        }

        .filter-group {
            margin-bottom: 30px;
            padding-bottom: 30px;
            border-bottom: 1px solid var(--border-color);
        }

        .filter-group:last-child {
            border-bottom: none;
        }

        .filter-group-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--secondary-color);
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .filter-option {
            display: flex;
            align-items: center;
            padding: 10px 0;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .filter-option:hover {
            padding-left: 5px;
        }

        .filter-option input[type="checkbox"],
        .filter-option input[type="radio"] {
            width: 20px;
            height: 20px;
            margin-right: 12px;
            cursor: pointer;
            accent-color: var(--primary-color);
        }

        .filter-option label {
            flex: 1;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .option-count {
            font-size: 13px;
            color: #999;
            background: var(--light-bg);
            padding: 2px 8px;
            border-radius: 10px;
        }

        /* Price Range Slider */
        .price-range-slider {
            padding: 20px 0;
        }

        .price-inputs {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        .price-input-wrapper {
            flex: 1;
        }

        .price-input-wrapper label {
            font-size: 13px;
            color: #666;
            display: block;
            margin-bottom: 8px;
        }

        .price-input-wrapper input {
            width: 100%;
            padding: 10px 15px;
            border: 2px solid var(--border-color);
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .price-input-wrapper input:focus {
            border-color: var(--primary-color);
            outline: none;
        }

        /* Products Grid */
        .products-grid-section {
            background: transparent;
        }

        .toolbar {
            background: white;
            border-radius: 15px;
            padding: 20px 25px;
            margin-bottom: 30px;
            box-shadow: 0 3px 15px rgba(0,0,0,0.08);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .results-count {
            font-size: 16px;
            color: var(--text-color);
            font-weight: 600;
        }

        .results-count span {
            color: var(--primary-color);
            font-weight: 800;
        }

        .toolbar-actions {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .view-toggle {
            display: flex;
            gap: 5px;
        }

        .view-btn {
            width: 40px;
            height: 40px;
            background: var(--light-bg);
            border: 2px solid var(--border-color);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            color: var(--text-color);
        }

        .view-btn:hover,
        .view-btn.active {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }

        .sort-select {
            padding: 10px 40px 10px 15px;
            border: 2px solid var(--border-color);
            border-radius: 10px;
            font-weight: 600;
            background: white;
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23333' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 15px center;
            transition: all 0.3s ease;
        }

        .sort-select:focus {
            border-color: var(--primary-color);
            outline: none;
        }

        /* Products Grid */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 30px;
            margin-bottom: 50px;
        }

        .grid-view-4 .products-grid {
            grid-template-columns: repeat(4, 1fr);
        }

        .grid-view-3 .products-grid {
            grid-template-columns: repeat(3, 1fr);
        }

        .grid-view-list .products-grid {
            grid-template-columns: 1fr;
        }

        /* List View Card */
        .grid-view-list .product-card {
            flex-direction: row;
            height: auto;
        }

        .grid-view-list .product-image-wrapper {
            width: 250px;
            padding-top: 0;
            height: 250px;
            flex-shrink: 0;
        }

        .grid-view-list .product-image {
            position: relative;
        }

        .grid-view-list .product-info {
            flex: 1;
        }

        .grid-view-list .product-buttons {
            flex-direction: row;
        }

        /* Pagination */
        .pagination-wrapper {
            display: flex;
            justify-content: center;
            margin-top: 50px;
        }

        .pagination {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .page-link {
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            border: 2px solid var(--border-color);
            border-radius: 10px;
            color: var(--text-color);
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .page-link:hover,
        .page-link.active {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
            transform: translateY(-2px);
        }

        /* No Results */
        .no-results {
            text-align: center;
            padding: 80px 20px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        }

        .no-results-icon {
            font-size: 80px;
            color: var(--border-color);
            margin-bottom: 20px;
        }

        .no-results-title {
            font-size: 28px;
            font-weight: 800;
            color: var(--secondary-color);
            margin-bottom: 15px;
        }

        .no-results-text {
            font-size: 16px;
            color: #666;
            margin-bottom: 30px;
        }

        /* Mobile Filters */
        .mobile-filter-btn {
            display: none;
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 60px;
            height: 60px;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 50%;
            font-size: 24px;
            box-shadow: 0 5px 20px rgba(1, 103, 243, 0.4);
            z-index: 999;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .mobile-filter-btn:hover {
            transform: scale(1.1);
        }

        @media (max-width: 1200px) {
            .grid-view-4 .products-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 992px) {
            .filter-sidebar {
                display: none;
            }

            .mobile-filter-btn {
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .grid-view-3 .products-grid,
            .grid-view-4 .products-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .products-grid,
            .grid-view-3 .products-grid,
            .grid-view-4 .products-grid {
                grid-template-columns: 1fr;
            }

            .toolbar {
                flex-direction: column;
                align-items: stretch;
            }

            .toolbar-actions {
                justify-content: space-between;
            }
        }
    </style>
@endpush

@section('content')
    <!-- Page Header -->
    <div class="page-header-section">
        <div class="container">
            <h1 class="page-header-title">All Products</h1>
            <p class="page-header-subtitle">Discover our complete range of wholesale products</p>
        </div>
    </div>

    <!-- Products Section -->
    <section class="products-page">
        <div class="container">
            <div class="row">
                <!-- Filter Sidebar -->
                <div class="col-lg-3">
                    <div class="filter-sidebar" id="filterSidebar">
                        <div class="filter-header">
                            <h3 class="filter-title">
                                <i class="fas fa-filter"></i>
                                Filters
                            </h3>
                            <button class="clear-filters" onclick="clearFilters()">
                                <i class="fas fa-times-circle me-1"></i>Clear All
                            </button>
                        </div>

                        <!-- Price Range Filter -->
                        <div class="filter-group">
                            <h4 class="filter-group-title">
                                <i class="fas fa-dollar-sign"></i>
                                Price Range
                            </h4>
                            <div class="price-range-slider">
                                <div class="price-inputs">
                                    <div class="price-input-wrapper">
                                        <label>Min Price</label>
                                        <input type="number" id="minPrice" placeholder="0" value="0">
                                    </div>
                                    <div class="price-input-wrapper">
                                        <label>Max Price</label>
                                        <input type="number" id="maxPrice" placeholder="10000" value="10000">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Categories Filter -->
                        <div class="filter-group">
                            <h4 class="filter-group-title">
                                <i class="fas fa-folder"></i>
                                Categories
                            </h4>
                            @foreach($categories as $category)
                                <div class="filter-option">
                                    <input type="checkbox" id="cat{{ $category->id }}" name="category[]" value="{{ $category->id }}">
                                    <label for="cat{{ $category->id }}">
                                        <span>{{ $category->name }}</span>
                                        <span class="option-count">{{ $category->products_count }}</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        <!-- Brands Filter -->
                        <div class="filter-group">
                            <h4 class="filter-group-title">
                                <i class="fas fa-certificate"></i>
                                Brands
                            </h4>
                            @foreach($brands as $brand)
                                <div class="filter-option">
                                    <input type="checkbox" id="brand{{ $brand->id }}" name="brand[]" value="{{ $brand->id }}">
                                    <label for="brand{{ $brand->id }}">
                                        <span>{{ $brand->name }}</span>
                                        <span class="option-count">{{ $brand->products_count }}</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        <!-- Stock Status Filter -->
                        <div class="filter-group">
                            <h4 class="filter-group-title">
                                <i class="fas fa-warehouse"></i>
                                Stock Status
                            </h4>
                            <div class="filter-option">
                                <input type="radio" id="allStock" name="stock" value="all" checked>
                                <label for="allStock">
                                    <span>All Products</span>
                                </label>
                            </div>
                            <div class="filter-option">
                                <input type="radio" id="inStock" name="stock" value="in">
                                <label for="inStock">
                                    <span>In Stock</span>
                                </label>
                            </div>
                            <div class="filter-option">
                                <input type="radio" id="lowStock" name="stock" value="low">
                                <label for="lowStock">
                                    <span>Low Stock</span>
                                </label>
                            </div>
                        </div>

                        <button class="btn btn-primary-custom w-100" onclick="applyFilters()">
                            <i class="fas fa-check me-2"></i>Apply Filters
                        </button>
                    </div>
                </div>

                <!-- Products Grid -->
                <div class="col-lg-9">
                    <div class="products-grid-section" id="productsSection">
                        <!-- Toolbar -->
                        <div class="toolbar">
                            <div class="results-count">
                                Showing <span>{{ $products->count() }}</span> of <span>{{ $products->total() }}</span> products
                            </div>

                            <div class="toolbar-actions">
                                <div class="view-toggle">
                                    <button class="view-btn active" data-view="grid-4" onclick="changeView('grid-4')">
                                        <i class="fas fa-th"></i>
                                    </button>
                                    <button class="view-btn" data-view="grid-3" onclick="changeView('grid-3')">
                                        <i class="fas fa-th-large"></i>
                                    </button>
                                    <button class="view-btn" data-view="list" onclick="changeView('list')">
                                        <i class="fas fa-list"></i>
                                    </button>
                                </div>

                                <select class="sort-select" onchange="sortProducts(this.value)">
                                    <option value="newest">Newest First</option>
                                    <option value="price_asc">Price: Low to High</option>
                                    <option value="price_desc">Price: High to Low</option>
                                    <option value="name_asc">Name: A-Z</option>
                                    <option value="name_desc">Name: Z-A</option>
                                </select>
                            </div>
                        </div>

                        <!-- Products Grid -->
                        <div class="grid-view-4" id="productsContainer">
                            <div class="products-grid">
                                @forelse($products as $product)
                                    <!-- Same product card structure as index page -->
                                    <div class="product-card" onclick="window.location.href='{{ route('shop.product.details', $product->id) }}'">
                                        <div class="product-image-wrapper">
                                            @if($product->image)
                                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="product-image">
                                            @else
                                                <div class="product-image d-flex align-items-center justify-content-center bg-light">
                                                    <i class="fas fa-box fa-3x text-muted"></i>
                                                </div>
                                            @endif

                                            <div class="product-badges">
                                                @if($product->created_at->diffInDays(now()) < 7)
                                                    <span class="badge-new">NEW</span>
                                                @endif
                                                @if($product->qty > 100)
                                                    <span class="badge-stock">In Stock</span>
                                                @endif
                                            </div>

                                            <div class="product-actions">
                                                <button class="action-btn" onclick="event.stopPropagation(); quickView({{ $product->id }})" title="Quick View">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="action-btn" onclick="event.stopPropagation(); addToWishlist({{ $product->id }})" title="Add to Wishlist">
                                                    <i class="fas fa-heart"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="product-info">
                                            <div class="product-category">{{ $product->category->name ?? 'Uncategorized' }}</div>
                                            <h3 class="product-title">{{ $product->name }}</h3>

                                            <div class="product-rating">
                                                <div class="stars">
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star-half-alt"></i>
                                                </div>
                                                <span class="rating-count">(4.5)</span>
                                            </div>

                                            <div class="product-prices">
                                                <span class="wholesale-price">Rs. {{ number_format($product->wholesale_price, 2) }}</span>
                                                <span class="display-price">Rs. {{ number_format($product->display_price, 2) }}</span>
                                                @php
                                                    $discount = (($product->display_price - $product->wholesale_price) / $product->display_price) * 100;
                                                @endphp
                                                <span class="price-badge">-{{ round($discount) }}%</span>
                                            </div>

                                            <div class="product-meta">
                                                <div class="min-order">
                                                    <i class="fas fa-shopping-cart"></i>
                                                    <span>Min: {{ $product->min_order_quantity }}</span>
                                                </div>
                                                <div class="stock-status">
                                                    <span class="stock-dot"></span>
                                                    <span>{{ $product->qty }} available</span>
                                                </div>
                                            </div>

                                            <div class="product-buttons">
                                                <button class="btn-add-cart" onclick="event.stopPropagation(); addToCart({{ $product->id }}, {{ $product->min_order_quantity }})">
                                                    <i class="fas fa-cart-plus"></i>
                                                    <span>Add to Cart</span>
                                                </button>
                                                <button class="btn-buy-now" onclick="event.stopPropagation(); buyNow({{ $product->id }}, {{ $product->min_order_quantity }})">
                                                    <i class="fas fa-bolt"></i>
                                                    <span>Buy Now</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12">
                                        <div class="no-results">
                                            <div class="no-results-icon">
                                                <i class="fas fa-search"></i>
                                            </div>
                                            <h3 class="no-results-title">No Products Found</h3>
                                            <p class="no-results-text">Try adjusting your filters or search terms</p>
                                            <button class="btn btn-primary-custom" onclick="clearFilters()">
                                                <i class="fas fa-redo me-2"></i>Reset Filters
                                            </button>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Pagination -->
                        @if($products->hasPages())
                            <div class="pagination-wrapper">
                                {{ $products->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Mobile Filter Button -->
    <button class="mobile-filter-btn" onclick="toggleMobileFilters()">
        <i class="fas fa-filter"></i>
    </button>
@endsection

@push('script')
    <script>
        function changeView(view) {
            const container = document.getElementById('productsContainer');
            const buttons = document.querySelectorAll('.view-btn');

            // Remove all view classes
            container.className = '';

            // Add new view class
            if (view === 'grid-4') {
                container.classList.add('grid-view-4');
            } else if (view === 'grid-3') {
                container.classList.add('grid-view-3');
            } else if (view === 'list') {
                container.classList.add('grid-view-list');
            }

            // Update active button
            buttons.forEach(btn => btn.classList.remove('active'));
            document.querySelector(`[data-view="${view}"]`).classList.add('active');
        }

        function sortProducts(sortBy) {
            const url = new URL(window.location.href);
            url.searchParams.set('sort', sortBy);
            window.location.href = url.toString();
        }

        function applyFilters() {
            const url = new URL(window.location.href);

            // Get selected categories
            const categories = Array.from(document.querySelectorAll('input[name="category[]"]:checked'))
                .map(input => input.value);

            // Get selected brands
            const brands = Array.from(document.querySelectorAll('input[name="brand[]"]:checked'))
                .map(input => input.value);

            // Get price range
            const minPrice = document.getElementById('minPrice').value;
            const maxPrice = document.getElementById('maxPrice').value;

            // Get stock status
            const stock = document.querySelector('input[name="stock"]:checked').value;

            // Set URL parameters
            if (categories.length) url.searchParams.set('categories', categories.join(','));
            if (brands.length) url.searchParams.set('brands', brands.join(','));
            if (minPrice) url.searchParams.set('price_min', minPrice);
            if (maxPrice) url.searchParams.set('price_max', maxPrice);
            if (stock !== 'all') url.searchParams.set('stock', stock);

            window.location.href = url.toString();
        }

        function clearFilters() {
            // Uncheck all checkboxes
            document.querySelectorAll('input[type="checkbox"]').forEach(input => {
                input.checked = false;
            });

            // Reset radio buttons
            document.getElementById('allStock').checked = true;

            // Reset price inputs
            document.getElementById('minPrice').value = 0;
            document.getElementById('maxPrice').value = 10000;

            // Reload without filters
            window.location.href = '{{ route("shop.products") }}';
        }

        function toggleMobileFilters() {
            const sidebar = document.getElementById('filterSidebar');
            sidebar.style.display = sidebar.style.display === 'block' ? 'none' : 'block';

            if (sidebar.style.display === 'block') {
                sidebar.style.position = 'fixed';
                sidebar.style.top = '0';
                sidebar.style.left = '0';
                sidebar.style.right = '0';
                sidebar.style.bottom = '0';
                sidebar.style.zIndex = '1000';
                sidebar.style.maxHeight = '100vh';
            }
        }
    </script>
@endpush
