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
            <a href="{{ route('seller.new-order') }}" class="btn btn-secondary">
                <span class="material-icons-round" style="font-size:18px">arrow_back</span>
                Back to Orders
            </a>
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
                                autocomplete="off">
                            <div class="helper-text">
                                <i class="fas fa-info-circle"></i> Enter the product code and click Fetch to load product details
                            </div>
                        </div>
                        <button type="button" id="fetchProductBtn" class="btn btn-primary">
                            <span class="material-icons-round" style="font-size:18px">search</span>
                            Fetch Product
                        </button>
                        <button type="button" id="resetBtn" class="btn btn-danger" style="display: none;">
                            <span class="material-icons-round" style="font-size:18px">refresh</span>
                            Reset
                        </button>
                    </div>
                </div>

                <!-- Product Info Card (Hidden by default) -->
                <div id="productInfoCard" class="product-info-card">
                    <div class="product-info-header">
                        <img id="productImagePreview" src="" alt="Product" class="product-image-preview" style="display: none;">
                        <div id="productImagePlaceholder" class="product-image-placeholder">
                            <span class="material-icons-round" style="font-size:32px">inventory_2</span>
                        </div>
                        <div class="product-info-text" style="flex: 1;">
                            <h4 id="displayProductName">-</h4>
                            <span class="product-code-badge">
                                <span class="material-icons-round" style="font-size:14px">tag</span>
                                <span id="displayProductCode">-</span>
                            </span>
                        </div>
                    </div>
                    <div class="product-details-grid">
                        <div class="detail-item">
                            <div class="detail-label">Price</div>
                            <div class="detail-value" style="color: var(--danger);">
                                Rs. <span id="displayProductPrice">0.00</span>
                            </div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Available Stock</div>
                            <div class="detail-value">
                                <span id="displayProductStock">0</span> units
                            </div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Status</div>
                            <div class="detail-value">
                                <span id="stockStatusBadge" class="stock-badge stock-out">Out of Stock</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Details Form -->
                <div class="form-grid">
                    <!-- Product Code (Hidden Input) -->
                    <input type="hidden" name="product_code" id="product_code" required>

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
                            value="1"
                            required>
                        <div class="helper-text">Available: <span id="availableStock">0</span> units</div>
                    </div>

                    <!-- Discount (Optional) -->
                    <div class="form-group">
                        <label class="form-label">
                            Discount (%)
                        </label>
                        <input
                            type="number"
                            name="discount"
                            id="discount"
                            class="form-control"
                            placeholder="Enter discount percentage"
                            min="0"
                            max="100"
                            value="0"
                            step="0.01">
                    </div>

                    <!-- Customer Email (Optional) -->
                    <div class="form-group">
                        <label class="form-label">
                            Customer Email
                        </label>
                        <input
                            type="email"
                            name="customer_email"
                            id="customer_email"
                            class="form-control"
                            placeholder="Enter email (optional)">
                    </div>

                    <!-- Payment Method -->
                    <div class="form-group">
                        <label class="form-label">
                            Payment Method <span class="required">*</span>
                        </label>
                        <select name="payment_method" id="payment_method" class="form-control" required>
                            <option value="">Select Payment Method</option>
                            <option value="cash">Cash</option>
                            <option value="card">Card</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="online">Online Payment</option>
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
                        placeholder="Enter delivery address (optional)"></textarea>
                </div>

                <!-- Order Notes (Full Width) -->
                <div class="form-group" style="margin-bottom: 24px;">
                    <label class="form-label">
                        Order Notes
                    </label>
                    <textarea
                        name="notes"
                        id="notes"
                        class="form-control"
                        placeholder="Add any special instructions or notes (optional)"></textarea>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <a href="{{ route('seller.new-order') }}" class="btn btn-secondary">
                        <span class="material-icons-round" style="font-size:18px">close</span>
                        Cancel
                    </a>
                    <button type="submit" id="submitBtn" class="btn btn-success" disabled>
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
        // AUTO-FILL FROM URL PARAMETERS (FROM SHOP PAGE)
        // =============================================
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);

            if (urlParams.has('product_code')) {
                // Fill search input
                const productCode = urlParams.get('product_code');
                document.getElementById('searchProductCode').value = productCode;

                // Create product data object from URL params
                const productData = {
                    code: urlParams.get('product_code'),
                    name: urlParams.get('product_name'),
                    display_price: parseFloat(urlParams.get('product_price')) || 0,
                    qty: parseInt(urlParams.get('product_stock')) || 0,
                    image: urlParams.get('product_image'),
                    description: urlParams.get('product_description')
                };

                // Display product info
                displayProductInfo(productData);

                // Set quantity if provided
                const quantity = urlParams.get('quantity');
                if (quantity) {
                    document.getElementById('quantity').value = quantity;
                }
            }
        });

        // =============================================
        // FETCH PRODUCT BY CODE
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

        // Allow Enter key to fetch product
        document.getElementById('searchProductCode').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                document.getElementById('fetchProductBtn').click();
            }
        });

        async function fetchProductByCode(productCode) {
            const fetchBtn = document.getElementById('fetchProductBtn');
            const originalHTML = fetchBtn.innerHTML;

            // Show loading state
            fetchBtn.disabled = true;
            fetchBtn.innerHTML = '<span class="spinner"></span> Fetching...';

            try {
                const response = await fetch(`/seller/shop/product/find/${encodeURIComponent(productCode)}`);

                if (!response.ok) {
                    if (response.status === 404) {
                        throw new Error('Product not found with code: ' + productCode);
                    }
                    throw new Error('Failed to fetch product data');
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
                    throw new Error('Invalid response from server');
                }

            } catch (error) {
                console.error('Fetch error:', error);

                Swal.fire({
                    icon: 'error',
                    title: 'Product Not Found',
                    text: error.message || 'Could not find product with this code',
                    confirmButtonColor: '#4f46e5'
                });

                resetForm();

            } finally {
                // Restore button state
                fetchBtn.disabled = false;
                fetchBtn.innerHTML = originalHTML;
            }
        }

        // =============================================
        // DISPLAY PRODUCT INFO
        // =============================================
        function displayProductInfo(product) {
            currentProductData = product;

            // Show product info card
            const infoCard = document.getElementById('productInfoCard');
            infoCard.classList.add('show');

            // Show reset button
            document.getElementById('resetBtn').style.display = 'inline-flex';

            // Fill hidden product code input
            document.getElementById('product_code').value = product.code;

            // Display product name
            document.getElementById('displayProductName').textContent = product.name;
            document.getElementById('displayProductCode').textContent = product.code;

            // Display product price
            const price = parseFloat(product.display_price) || parseFloat(product.price) || 0;
            document.getElementById('displayProductPrice').textContent = price.toFixed(2);

            // Display stock
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

                // Handle image load error
                imagePreview.onerror = function() {
                    this.style.display = 'none';
                    imagePlaceholder.style.display = 'flex';
                };
            } else {
                imagePreview.style.display = 'none';
                imagePlaceholder.style.display = 'flex';
            }

            // Enable/disable quantity input based on stock
            const quantityInput = document.getElementById('quantity');
            if (stock > 0) {
                quantityInput.max = stock;
                quantityInput.disabled = false;
                if (quantityInput.value > stock) {
                    quantityInput.value = stock;
                }
            } else {
                quantityInput.disabled = true;
                quantityInput.value = 0;
            }

            // Enable submit button if stock available
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = stock <= 0;

            if (stock <= 0) {
                submitBtn.innerHTML = '<span class="material-icons-round" style="font-size:18px">block</span> Out of Stock';
            } else {
                submitBtn.innerHTML = '<span class="material-icons-round" style="font-size:18px">shopping_cart</span> Create Order';
            }
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
            // Reset product data
            currentProductData = null;

            // Hide product info card
            document.getElementById('productInfoCard').classList.remove('show');

            // Hide reset button
            document.getElementById('resetBtn').style.display = 'none';

            // Clear search input
            document.getElementById('searchProductCode').value = '';

            // Clear hidden inputs
            document.getElementById('product_code').value = '';

            // Reset display fields
            document.getElementById('displayProductName').textContent = '-';
            document.getElementById('displayProductCode').textContent = '-';
            document.getElementById('displayProductPrice').textContent = '0.00';
            document.getElementById('displayProductStock').textContent = '0';
            document.getElementById('availableStock').textContent = '0';

            // Reset image
            document.getElementById('productImagePreview').style.display = 'none';
            document.getElementById('productImagePlaceholder').style.display = 'flex';

            // Clear form inputs
            document.getElementById('customer_name').value = '';
            document.getElementById('customer_phone').value = '';
            document.getElementById('customer_email').value = '';
            document.getElementById('customer_address').value = '';
            document.getElementById('quantity').value = '1';
            document.getElementById('discount').value = '0';
            document.getElementById('payment_method').value = '';
            document.getElementById('notes').value = '';

            // Disable quantity input and submit button
            document.getElementById('quantity').disabled = true;
            document.getElementById('submitBtn').disabled = true;
        }

        // =============================================
        // FORM VALIDATION
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
        // FORM SUBMISSION
        // =============================================
        document.getElementById('orderForm').addEventListener('submit', function(e) {
            e.preventDefault();

            // Validate product is selected
            if (!currentProductData || !document.getElementById('product_code').value) {
                Swal.fire({
                    icon: 'error',
                    title: 'No Product Selected',
                    text: 'Please fetch a product before creating an order',
                    confirmButtonColor: '#4f46e5'
                });
                return;
            }

            // Validate stock
            const quantity = parseInt(document.getElementById('quantity').value) || 0;
            const stock = parseInt(currentProductData.qty) || 0;

            if (quantity > stock) {
                Swal.fire({
                    icon: 'error',
                    title: 'Insufficient Stock',
                    text: `Only ${stock} units available`,
                    confirmButtonColor: '#4f46e5'
                });
                return;
            }

            // Show confirmation
            Swal.fire({
                title: 'Create Order?',
                html: `
                    <div style="text-align: left; margin-top: 16px;">
                        <p><strong>Product:</strong> ${currentProductData.name}</p>
                        <p><strong>Code:</strong> ${currentProductData.code}</p>
                        <p><strong>Quantity:</strong> ${quantity} units</p>
                        <p><strong>Customer:</strong> ${document.getElementById('customer_name').value}</p>
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, Create Order',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Creating Order...',
                        html: 'Please wait',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Submit form
                    this.submit();
                }
            });
        });
    </script>
@endpush
