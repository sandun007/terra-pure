@extends('seller.layout.app')

@push('title')
    Create New Order
@endpush

@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&family=DM+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --primary-light: #eef2ff;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --dark: #0f172a;
            --mid: #64748b;
            --border: #e2e8f0;
            --surface: #ffffff;
            --bg: #f8fafc;
            --mono: 'DM Mono', monospace;
            --sans: 'DM Sans', sans-serif;
            --radius-xl: 16px;
            --radius-lg: 12px;
            --radius-md: 8px;
            --shadow-sm: 0 2px 8px rgba(0,0,0,.07);
            --shadow-md: 0 6px 20px rgba(0,0,0,.09);
        }

        body {
            font-family: var(--sans);
            background: var(--bg);
        }

        .order-wrapper {
            padding: 32px;
            max-width: 1400px;
            margin: 0 auto;
        }

        @media (max-width: 768px) {
            .order-wrapper { padding: 16px; }
        }

        /* Page Header */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 28px;
            flex-wrap: wrap;
            gap: 16px;
        }

        .page-title {
            font-size: 26px;
            font-weight: 800;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 0;
        }

        .header-actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        /* Form Container */
        .form-container {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-xl);
            padding: 32px;
            box-shadow: var(--shadow-sm);
        }

        @media (max-width: 768px) {
            .form-container { padding: 20px; }
        }

        /* Grid Layout */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            margin-bottom: 24px;
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }
        }

        /* Form Group */
        .form-group {
            margin-bottom: 0;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 8px;
        }

        .form-label .required {
            color: var(--danger);
            margin-left: 2px;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid var(--border);
            border-radius: var(--radius-md);
            font-family: var(--sans);
            font-size: 14px;
            transition: all .2s;
            background: var(--surface);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79,70,229,.1);
        }

        .form-control:disabled {
            background: #f1f5f9;
            cursor: not-allowed;
        }

        textarea.form-control {
            resize: vertical;
            min-height: 100px;
        }

        /* Product Code Search Section */
        .product-search-section {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border: 2px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 24px;
            margin-bottom: 24px;
        }

        .search-header {
            font-size: 16px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .input-with-button {
            display: flex;
            gap: 12px;
            align-items: flex-start;
        }

        .input-with-button .form-control {
            flex: 1;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            border-radius: var(--radius-md);
            font-size: 14px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all .2s;
            font-family: var(--sans);
            text-decoration: none;
            white-space: nowrap;
        }

        .btn-primary {
            background: var(--primary);
            color: #fff;
        }

        .btn-primary:hover {
            background: var(--primary-hover);
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: var(--dark);
            color: #fff;
        }

        .btn-secondary:hover {
            background: #1e293b;
            transform: translateY(-1px);
        }

        .btn-success {
            background: var(--success);
            color: #fff;
        }

        .btn-success:hover {
            background: #059669;
            transform: translateY(-1px);
        }

        .btn-warning {
            background: var(--warning);
            color: #fff;
        }

        .btn-warning:hover {
            background: #d97706;
            transform: translateY(-1px);
        }

        .btn-danger {
            background: var(--danger);
            color: #fff;
        }

        .btn-danger:hover {
            background: #dc2626;
        }

        .btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none !important;
        }

        /* Product Info Card */
        .product-info-card {
            background: var(--surface);
            border: 2px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 20px;
            margin-bottom: 24px;
            display: none;
        }

        .product-info-card.show {
            display: block;
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .product-info-header {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 16px;
            padding-bottom: 16px;
            border-bottom: 2px solid var(--border);
        }

        .product-image-preview {
            width: 80px;
            height: 80px;
            border-radius: var(--radius-md);
            object-fit: cover;
            border: 2px solid var(--border);
            background: #f1f5f9;
        }

        .product-image-placeholder {
            width: 80px;
            height: 80px;
            border-radius: var(--radius-md);
            border: 2px dashed var(--border);
            background: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--mid);
        }

        .product-info-text h4 {
            font-size: 18px;
            font-weight: 700;
            color: var(--dark);
            margin: 0 0 4px 0;
        }

        .product-code-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            background: var(--primary-light);
            color: var(--primary);
            border-radius: 999px;
            font-family: var(--mono);
            font-size: 12px;
            font-weight: 600;
        }

        .product-details-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
        }

        @media (max-width: 768px) {
            .product-details-grid {
                grid-template-columns: 1fr;
            }
        }

        .detail-item {
            padding: 12px;
            background: var(--bg);
            border-radius: var(--radius-md);
        }

        .detail-label {
            font-size: 12px;
            color: var(--mid);
            margin-bottom: 4px;
        }

        .detail-value {
            font-size: 16px;
            font-weight: 700;
            color: var(--dark);
        }

        .stock-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 8px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 600;
        }

        .stock-available { background: #d1fae5; color: #065f46; }
        .stock-low { background: #fef3c7; color: #92400e; }
        .stock-out { background: #fee2e2; color: #991b1b; }

        /* Form Actions */
        .form-actions {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            padding-top: 24px;
            border-top: 2px solid var(--border);
        }

        @media (max-width: 576px) {
            .form-actions {
                flex-direction: column-reverse;
            }
            .btn {
                width: 100%;
                justify-content: center;
            }
        }

        /* Alert */
        .alert {
            padding: 14px 18px;
            border-radius: var(--radius-md);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-success {
            background: #d1fae5;
            border: 1px solid #10b981;
            color: #065f46;
        }

        .alert-danger {
            background: #fee2e2;
            border: 1px solid #ef4444;
            color: #991b1b;
        }

        .alert-info {
            background: #dbeafe;
            border: 1px solid #3b82f6;
            color: #1e40af;
        }

        /* Loading Spinner */
        .spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255,255,255,.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 0.6s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Helper Text */
        .helper-text {
            font-size: 12px;
            color: var(--mid);
            margin-top: 4px;
        }

        .error-text {
            font-size: 12px;
            color: var(--danger);
            margin-top: 4px;
            display: none;
        }

        .error-text.show {
            display: block;
        }
    </style>
@endpush

@section('content')
    <div class="order-wrapper">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <span class="material-icons-round" style="color:var(--primary);font-size:30px">add_shopping_cart</span>
                Create New Order
            </h1>
            <div class="header-actions">
                <a href="{{ route('seller.order.csv-upload') }}" class="btn btn-warning">
                    <span class="material-icons-round" style="font-size:18px">upload_file</span>
                    Upload CSV Leads
                </a>
                <a href="{{ route('seller.pending-order') }}" class="btn btn-secondary">
                    <span class="material-icons-round" style="font-size:18px">list_alt</span>
                    View Orders
                </a>
            </div>
        </div>

        <!-- Alerts -->
        @if(session('error'))
            <div class="alert alert-danger">
                <span class="material-icons-round">error</span>
                <div>{{ session('error') }}</div>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success">
                <span class="material-icons-round">check_circle</span>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        <!-- Order Form -->
        <form id="orderForm" action="{{ route('seller.order.store') }}" method="POST">
            @csrf

            <div class="form-container">
                <!-- Product Code Search Section -->
                <div class="product-search-section">
                    <div class="search-header">
                        <span class="material-icons-round" style="color:var(--primary)">qr_code_scanner</span>
                        Search Product by Code
                    </div>
                    <div class="input-with-button">
                        <div style="flex: 1;">
                            <input
                                type="text"
                                id="searchProductCode"
                                class="form-control"
                                placeholder="Enter product code (e.g., DX/ITM/0001)"
                                autocomplete="off"
                                value="{{ $productCode ?? '' }}">
                            <div class="helper-text">
                                <i class="fas fa-info-circle"></i> Enter the product code and click Fetch to load product details
                            </div>
                        </div>
                        <button type="button" id="fetchProductBtn" class="btn btn-primary">
                            <span class="material-icons-round" style="font-size:18px">search</span>
                            Fetch Product
                        </button>
                        <button type="button" id="resetBtn" class="btn btn-danger" style="display: {{ $productId ? 'inline-flex' : 'none' }};">
                            <span class="material-icons-round" style="font-size:18px">refresh</span>
                            Reset
                        </button>
                    </div>
                </div>

                <!-- Product Info Card (Hidden by default) -->
                <div id="productInfoCard" class="product-info-card" style="display: {{ $productId ? 'block' : 'none' }};">
                    <div class="product-info-header">
                        <img id="productImagePreview" src="{{ $productImage ?? '' }}" alt="Product" class="product-image-preview" style="display: {{ $productImage ? 'block' : 'none' }};">
                        <div id="productImagePlaceholder" class="product-image-placeholder" style="display: {{ $productImage ? 'none' : 'flex' }};">
                            <span class="material-icons-round" style="font-size:32px">inventory_2</span>
                        </div>
                        <div class="product-info-text" style="flex: 1;">
                            <h4 id="displayProductName">{{ $productName ?? '-' }}</h4>
                            <span class="product-code-badge">
                                <span class="material-icons-round" style="font-size:14px">tag</span>
                                <span id="displayProductCode">{{ $productCode ?? '-' }}</span>
                            </span>
                        </div>
                    </div>
                    <div class="product-details-grid">
                        <div class="detail-item">
                            <div class="detail-label">Price</div>
                            <div class="detail-value" style="color: var(--danger);">
                                Rs. <span id="displayProductPrice">{{ $productPrice ?? '0.00' }}</span>
                            </div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Available Stock</div>
                            <div class="detail-value">
                                <span id="displayProductStock">{{ $productStock ?? '0' }}</span> units
                            </div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Status</div>
                            <div class="detail-value">
                                <span id="stockStatusBadge" class="stock-badge
                                    @if(($productStock ?? 0) <= 0) stock-out
                                    @elseif(($productStock ?? 0) <= 10) stock-low
                                    @else stock-available
                                    @endif">
                                    @if(($productStock ?? 0) <= 0)
                                        <span class="material-icons-round" style="font-size:14px">cancel</span> Out of Stock
                                    @elseif(($productStock ?? 0) <= 10)
                                        <span class="material-icons-round" style="font-size:14px">warning</span> Low Stock
                                    @else
                                        <span class="material-icons-round" style="font-size:14px">check_circle</span> Available
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hidden Fields -->
                <input type="hidden" name="product_code" id="product_code" value="{{ $productCode ?? '' }}" required>
                <input type="hidden" name="product_id" id="product_id" value="{{ $productId ?? '' }}" required>
                <input type="hidden" name="product_name" id="product_name" value="{{ $productName ?? '' }}" required>
                <input type="hidden" name="product_price" id="product_price" value="{{ $productPrice ?? '0' }}" required>
                <input type="hidden" name="product_image" id="product_image" value="{{ $productImage ?? '' }}">
                <input type="hidden" name="date" id="order_date" value="{{ date('Y-m-d') }}">

                <!-- Order Details Form -->
                <div class="form-grid">
                    <!-- Customer Name -->
                    <div class="form-group">
                        <label class="form-label">
                            Customer Name <span class="required">*</span>
                        </label>
                        <input
                            type="text"
                            name="customer_name"
                            id="customer_name"
                            class="form-control"
                            placeholder="Enter customer name"
                            value="{{ old('customer_name') }}"
                            required>
                    </div>

                    <!-- Customer Phone -->
                    <div class="form-group">
                        <label class="form-label">
                            Customer Phone <span class="required">*</span>
                        </label>
                        <input
                            type="text"
                            name="customer_phone"
                            id="customer_phone"
                            class="form-control"
                            placeholder="Enter phone number"
                            value="{{ old('customer_phone') }}"
                            required>
                    </div>

                    <!-- Quantity -->
                    <div class="form-group">
                        <label class="form-label">
                            Quantity <span class="required">*</span>
                        </label>
                        <input
                            type="number"
                            name="quantity"
                            id="quantity"
                            class="form-control"
                            placeholder="Enter quantity"
                            min="1"
                            value="{{ $quantity ?? 1 }}"
                            {{ !$productId ? 'disabled' : '' }}
                            required>
                        <div class="helper-text">Available: <span id="availableStock">{{ $productStock ?? '0' }}</span> units</div>
                    </div>

                    <!-- District/City -->
                    <div class="form-group">
                        <label class="form-label">
                            District/City
                        </label>
                        <input
                            type="text"
                            name="district"
                            id="district"
                            class="form-control"
                            placeholder="Enter district or city"
                            value="{{ old('district') }}">
                    </div>

                    <!-- Platform -->
                    <div class="form-group">
                        <label class="form-label">
                            Platform
                        </label>
                        <select name="platform" id="platform" class="form-control">
                            <option value="">Select Platform</option>
                            <option value="fb" {{ old('platform') == 'fb' ? 'selected' : '' }}>Facebook</option>
                            <option value="ig" {{ old('platform') == 'ig' ? 'selected' : '' }}>Instagram</option>
                            <option value="website" {{ old('platform') == 'website' ? 'selected' : '' }}>Website</option>
                            <option value="phone" {{ old('platform') == 'phone' ? 'selected' : '' }}>Phone</option>
                            <option value="other" {{ old('platform') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    <!-- Payment Method -->
                    <div class="form-group">
                        <label class="form-label">
                            Payment Method <span class="required">*</span>
                        </label>
                        <select name="payment_method" id="payment_method" class="form-control" required>
                            <option value="">Select Payment Method</option>
                            <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash on Delivery</option>
                            <option value="card" {{ old('payment_method') == 'card' ? 'selected' : '' }}>Card Payment</option>
                            <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="online" {{ old('payment_method') == 'online' ? 'selected' : '' }}>Online Payment</option>
                        </select>
                    </div>
                </div>

                <!-- Customer Address (Full Width) -->
                <div class="form-group" style="margin-bottom: 24px;">
                    <label class="form-label">
                        Delivery Address
                    </label>
                    <textarea
                        name="customer_address"
                        id="customer_address"
                        class="form-control"
                        placeholder="Enter delivery address (optional)">{{ old('customer_address') }}</textarea>
                </div>

                <!-- Order Notes (Full Width) -->
                <div class="form-group" style="margin-bottom: 24px;">
                    <label class="form-label">
                        Order Notes / Remarks
                    </label>
                    <textarea
                        name="remark"
                        id="remark"
                        class="form-control"
                        placeholder="Add any special instructions or notes (optional)">{{ old('remark') }}</textarea>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <button type="button" id="printBtn" class="btn btn-secondary" {{ !$productId ? 'disabled' : '' }}>
                        <span class="material-icons-round" style="font-size:18px">print</span>
                        Print Order
                    </button>
                    <a href="{{ route('seller.pending-order') }}" class="btn btn-secondary">
                        <span class="material-icons-round" style="font-size:18px">list_alt</span>
                        View Orders
                    </a>
                    <button type="button" id="submitBtn" class="btn btn-success" {{ !$productId ? 'disabled' : '' }}>
                        <span class="material-icons-round" style="font-size:18px">shopping_cart</span>
                        Create Order
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Global variables
        let currentProductData = null;

        // =============================================
        // INITIALIZE FROM URL PARAMETERS OR EXISTING DATA
        // =============================================
        document.addEventListener('DOMContentLoaded', function() {
            // Check if we have product data from URL parameters (passed from controller)
            @if($productId && $productCode && $productName && $productPrice)
            const initialProductData = {
                id: '{{ $productId }}',
                code: '{{ $productCode }}',
                name: '{{ $productName }}',
                display_price: parseFloat('{{ $productPrice }}') || 0,
                qty: parseInt('{{ $productStock }}') || 0,
                image: '{{ $productImage }}',
                description: '{{ $productDescription ?? '' }}'
            };
            currentProductData = initialProductData;

            // Enable submit and print buttons
            document.getElementById('submitBtn').disabled = false;
            document.getElementById('printBtn').disabled = false;
            document.getElementById('resetBtn').style.display = 'inline-flex';
            document.getElementById('quantity').disabled = false;
            @endif

            // Auto-hide alerts
            setTimeout(() => {
                document.querySelectorAll('.alert').forEach(alert => {
                    alert.style.transition = 'opacity 0.5s';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                });
            }, 5000);
        });

        // =============================================
        // FETCH PRODUCT BY CODE - FIXED VERSION
        // =============================================
        document.getElementById('fetchProductBtn').addEventListener('click', function() {
            const productCode = document.getElementById('searchProductCode').value.trim();

            if (!productCode) {
                Swal.fire({
                    icon: 'error',
                    title: 'Missing Product Code',
                    text: 'Please enter a product code',
                    confirmButtonColor: '#4f46e5'
                });
                return;
            }

            fetchProductByCode(productCode);
        });

        document.getElementById('searchProductCode').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                document.getElementById('fetchProductBtn').click();
            }
        });

        async function fetchProductByCode(productCode) {
            const fetchBtn = document.getElementById('fetchProductBtn');
            const originalHTML = fetchBtn.innerHTML;

            fetchBtn.disabled = true;
            fetchBtn.innerHTML = '<span class="spinner"></span> Fetching...';

            try {
                // Properly encode the product code for URL
                const encodedCode = encodeURIComponent(productCode);
                const url = `/seller/order/product/${encodedCode}/details`;

                console.log('Fetching product from URL:', url); // Debug log

                const response = await fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    const errorData = await response.json().catch(() => ({}));
                    throw new Error(errorData.message || `HTTP error ${response.status}`);
                }

                const data = await response.json();

                if (data.success && data.product) {
                    displayProductInfo(data.product);

                    Swal.fire({
                        icon: 'success',
                        title: 'Product Found!',
                        text: `${data.product.name} loaded successfully`,
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    throw new Error(data.message || 'Invalid response from server');
                }

            } catch (error) {
                console.error('Fetch error:', error);

                Swal.fire({
                    icon: 'error',
                    title: 'Product Not Found',
                    text: error.message || 'Could not find product with this code. Please check the product code and try again.',
                    confirmButtonColor: '#4f46e5'
                });

                resetForm();

            } finally {
                fetchBtn.disabled = false;
                fetchBtn.innerHTML = originalHTML;
            }
        }

        // =============================================
        // DISPLAY PRODUCT INFO
        // =============================================
        function displayProductInfo(product) {
            currentProductData = product;

            const infoCard = document.getElementById('productInfoCard');
            infoCard.style.display = 'block';
            infoCard.classList.add('show');

            document.getElementById('resetBtn').style.display = 'inline-flex';

            // Fill hidden fields
            document.getElementById('product_code').value = product.code;
            document.getElementById('product_id').value = product.id;
            document.getElementById('product_name').value = product.name;
            document.getElementById('product_price').value = product.display_price || product.price;
            document.getElementById('product_image').value = product.image || '';

            // Display product info
            document.getElementById('displayProductName').textContent = product.name;
            document.getElementById('displayProductCode').textContent = product.code;

            const price = parseFloat(product.display_price) || parseFloat(product.price) || 0;
            document.getElementById('displayProductPrice').textContent = price.toFixed(2);

            const stock = parseInt(product.qty) || 0;
            document.getElementById('displayProductStock').textContent = stock;
            document.getElementById('availableStock').textContent = stock;

            // Update stock status badge
            const statusBadge = document.getElementById('stockStatusBadge');
            if (stock <= 0) {
                statusBadge.className = 'stock-badge stock-out';
                statusBadge.innerHTML = '<span class="material-icons-round" style="font-size:14px">cancel</span> Out of Stock';
            } else if (stock <= (product.min_qty || 10)) {
                statusBadge.className = 'stock-badge stock-low';
                statusBadge.innerHTML = '<span class="material-icons-round" style="font-size:14px">warning</span> Low Stock';
            } else {
                statusBadge.className = 'stock-badge stock-available';
                statusBadge.innerHTML = '<span class="material-icons-round" style="font-size:14px">check_circle</span> Available';
            }

            // Display product image
            const imagePreview = document.getElementById('productImagePreview');
            const imagePlaceholder = document.getElementById('productImagePlaceholder');

            if (product.image && product.image.trim() !== '') {
                imagePreview.src = product.image;
                imagePreview.style.display = 'block';
                imagePlaceholder.style.display = 'none';

                imagePreview.onerror = function() {
                    this.style.display = 'none';
                    imagePlaceholder.style.display = 'flex';
                };
            } else {
                imagePreview.style.display = 'none';
                imagePlaceholder.style.display = 'flex';
            }

            // Enable/disable quantity input
            const quantityInput = document.getElementById('quantity');
            if (stock > 0) {
                quantityInput.max = stock;
                quantityInput.disabled = false;
                if (parseInt(quantityInput.value) > stock) {
                    quantityInput.value = stock;
                }
            } else {
                quantityInput.disabled = true;
                quantityInput.value = 0;
            }

            // Enable submit and print buttons
            const submitBtn = document.getElementById('submitBtn');
            const printBtn = document.getElementById('printBtn');

            submitBtn.disabled = false;
            printBtn.disabled = false;

            submitBtn.innerHTML = '<span class="material-icons-round" style="font-size:18px">shopping_cart</span> Create Order';
        }

        // =============================================
        // RESET FORM
        // =============================================
        document.getElementById('resetBtn').addEventListener('click', function() {
            Swal.fire({
                title: 'Reset Form?',
                text: 'This will clear all product and order information',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, Reset',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    resetForm();
                    Swal.fire({
                        icon: 'success',
                        title: 'Form Reset',
                        text: 'All fields have been cleared',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            });
        });

        function resetForm() {
            currentProductData = null;

            document.getElementById('productInfoCard').style.display = 'none';
            document.getElementById('productInfoCard').classList.remove('show');
            document.getElementById('resetBtn').style.display = 'none';
            document.getElementById('searchProductCode').value = '';

            // Clear all form fields
            document.getElementById('product_code').value = '';
            document.getElementById('product_id').value = '';
            document.getElementById('product_name').value = '';
            document.getElementById('product_price').value = '';
            document.getElementById('product_image').value = '';
            document.getElementById('customer_name').value = '';
            document.getElementById('customer_phone').value = '';
            document.getElementById('customer_address').value = '';
            document.getElementById('district').value = '';
            document.getElementById('platform').value = '';
            document.getElementById('quantity').value = '1';
            document.getElementById('payment_method').value = '';
            document.getElementById('remark').value = '';

            // Reset display
            document.getElementById('displayProductName').textContent = '-';
            document.getElementById('displayProductCode').textContent = '-';
            document.getElementById('displayProductPrice').textContent = '0.00';
            document.getElementById('displayProductStock').textContent = '0';
            document.getElementById('availableStock').textContent = '0';

            document.getElementById('productImagePreview').style.display = 'none';
            document.getElementById('productImagePlaceholder').style.display = 'flex';

            document.getElementById('quantity').disabled = true;
            document.getElementById('submitBtn').disabled = true;
            document.getElementById('printBtn').disabled = true;
        }

        // =============================================
        // QUANTITY VALIDATION
        // =============================================
        document.getElementById('quantity').addEventListener('input', function() {
            if (currentProductData) {
                const maxStock = parseInt(currentProductData.qty) || 0;
                const quantity = parseInt(this.value) || 0;

                if (quantity > maxStock) {
                    this.value = maxStock;
                    Swal.fire({
                        icon: 'warning',
                        title: 'Quantity Exceeds Stock',
                        text: `Maximum available quantity is ${maxStock} units`,
                        timer: 2000,
                        showConfirmButton: false
                    });
                }
            }
        });

        // =============================================
        // PRINT FUNCTIONALITY
        // =============================================
        document.getElementById('printBtn').addEventListener('click', function() {
            if (!currentProductData) {
                Swal.fire({
                    icon: 'error',
                    title: 'No Product Selected',
                    text: 'Please select a product first',
                    confirmButtonColor: '#4f46e5'
                });
                return;
            }

            const customerName = document.getElementById('customer_name').value;
            const customerPhone = document.getElementById('customer_phone').value;
            const customerAddress = document.getElementById('customer_address').value;
            const quantity = document.getElementById('quantity').value;
            const district = document.getElementById('district').value;

            if (!customerName || !customerPhone) {
                Swal.fire({
                    icon: 'error',
                    title: 'Missing Information',
                    text: 'Please fill in customer name and phone number',
                    confirmButtonColor: '#4f46e5'
                });
                return;
            }

            // Create print content
            const printWindow = window.open('', '', 'height=600,width=800');
            printWindow.document.write('<html><head><title>Order Print</title>');
            printWindow.document.write('<style>');
            printWindow.document.write('body { font-family: Arial, sans-serif; padding: 20px; }');
            printWindow.document.write('h1 { color: #4f46e5; margin-bottom: 20px; }');
            printWindow.document.write('table { width: 100%; border-collapse: collapse; margin-top: 20px; }');
            printWindow.document.write('td { padding: 10px; border-bottom: 1px solid #e2e8f0; }');
            printWindow.document.write('td:first-child { font-weight: bold; width: 40%; }');
            printWindow.document.write('.header { background: #f8fafc; padding: 20px; border-radius: 8px; margin-bottom: 20px; }');
            printWindow.document.write('</style>');
            printWindow.document.write('</head><body>');

            printWindow.document.write('<div class="header">');
            printWindow.document.write('<h1>Order Details</h1>');
            printWindow.document.write('<p>Date: ' + new Date().toLocaleDateString() + '</p>');
            printWindow.document.write('</div>');

            printWindow.document.write('<table>');
            printWindow.document.write('<tr><td>Product Code:</td><td>' + currentProductData.code + '</td></tr>');
            printWindow.document.write('<tr><td>Product Name:</td><td>' + currentProductData.name + '</td></tr>');
            printWindow.document.write('<tr><td>Price:</td><td>Rs. ' + currentProductData.display_price + '</td></tr>');
            printWindow.document.write('<tr><td>Quantity:</td><td>' + quantity + '</td></tr>');
            printWindow.document.write('<tr><td>Total Amount:</td><td>Rs. ' + (currentProductData.display_price * quantity).toFixed(2) + '</td></tr>');
            printWindow.document.write('<tr><td>Customer Name:</td><td>' + customerName + '</td></tr>');
            printWindow.document.write('<tr><td>Customer Phone:</td><td>' + customerPhone + '</td></tr>');
            if (customerAddress) {
                printWindow.document.write('<tr><td>Delivery Address:</td><td>' + customerAddress + '</td></tr>');
            }
            if (district) {
                printWindow.document.write('<tr><td>District:</td><td>' + district + '</td></tr>');
            }
            printWindow.document.write('</table>');

            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.focus();

            setTimeout(function() {
                printWindow.print();
                printWindow.close();
            }, 250);
        });

        // =============================================
        // FORM SUBMISSION
        // =============================================
        document.getElementById('submitBtn').addEventListener('click', function(e) {
            e.preventDefault();

            // Check if product is selected
            if (!currentProductData || !document.getElementById('product_id').value) {
                Swal.fire({
                    icon: 'error',
                    title: 'No Product Selected',
                    text: 'Please fetch a product before creating an order',
                    confirmButtonColor: '#4f46e5'
                });
                return false;
            }

            // Check customer name and phone
            const customerName = document.getElementById('customer_name').value.trim();
            const customerPhone = document.getElementById('customer_phone').value.trim();

            if (!customerName) {
                Swal.fire({
                    icon: 'error',
                    title: 'Missing Information',
                    text: 'Please enter customer name',
                    confirmButtonColor: '#4f46e5'
                });
                return false;
            }

            if (!customerPhone) {
                Swal.fire({
                    icon: 'error',
                    title: 'Missing Information',
                    text: 'Please enter customer phone number',
                    confirmButtonColor: '#4f46e5'
                });
                return false;
            }

            // Check payment method
            const paymentMethod = document.getElementById('payment_method').value;
            if (!paymentMethod) {
                Swal.fire({
                    icon: 'error',
                    title: 'Missing Information',
                    text: 'Please select a payment method',
                    confirmButtonColor: '#4f46e5'
                });
                return false;
            }

            // Check quantity
            const quantity = parseInt(document.getElementById('quantity').value) || 0;
            if (quantity <= 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Quantity',
                    text: 'Please enter a valid quantity',
                    confirmButtonColor: '#4f46e5'
                });
                return false;
            }

            // Check stock
            const stock = parseInt(currentProductData.qty) || 0;
            if (quantity > stock) {
                Swal.fire({
                    icon: 'error',
                    title: 'Insufficient Stock',
                    text: `Only ${stock} units available`,
                    confirmButtonColor: '#4f46e5'
                });
                return false;
            }

            // Show confirmation dialog
            Swal.fire({
                title: 'Create Order?',
                html: `
                    <div style="text-align: left; margin-top: 16px;">
                        <p><strong>Product:</strong> ${currentProductData.name}</p>
                        <p><strong>Code:</strong> ${currentProductData.code}</p>
                        <p><strong>Quantity:</strong> ${quantity} units</p>
                        <p><strong>Customer:</strong> ${customerName}</p>
                        <p><strong>Phone:</strong> ${customerPhone}</p>
                        <p><strong>Total:</strong> Rs. ${(currentProductData.display_price * quantity).toFixed(2)}</p>
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, Create Order',
                cancelButtonText: 'Cancel',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    // Show loading
                    Swal.showLoading();

                    // Submit the form via AJAX
                    const form = document.getElementById('orderForm');
                    const formData = new FormData(form);

                    return fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                        .then(response => {
                            if (!response.ok) {
                                return response.json().then(err => {
                                    throw new Error(err.message || 'Something went wrong');
                                });
                            }
                            return response.json();
                        })
                        .catch(error => {
                            Swal.showValidationMessage(`Request failed: ${error.message}`);
                        });
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed && result.value && result.value.success) {
                    // Success - show success message and redirect
                    Swal.fire({
                        icon: 'success',
                        title: 'Order Created!',
                        html: `
                            <div style="text-align: center;">
                                <p>${result.value.message}</p>
                                <div class="alert alert-success mt-3" style="background: #d1fae5; padding: 12px; border-radius: 8px;">
                                    <span class="material-icons-round" style="vertical-align: middle;">check_circle</span>
                                    Redirecting to pending orders...
                                </div>
                            </div>
                        `,
                        timer: 2000,
                        timerProgressBar: true,
                        showConfirmButton: false,
                        willClose: () => {
                            window.location.href = result.value.redirect_url || '{{ route("seller.pending-order") }}';
                        }
                    });
                } else if (result.value && !result.value.success) {
                    // Error from server
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: result.value.message || 'Failed to create order',
                        confirmButtonColor: '#4f46e5'
                    });
                }
            });
        });

        // Regular form submission fallback (if JS fails)
        document.getElementById('orderForm').addEventListener('submit', function(e) {
            // Prevent default if submit button was clicked (handled by our custom handler)
            if (e.submitter && e.submitter.id === 'submitBtn') {
                e.preventDefault();
            }
        });
    </script>
@endpush
