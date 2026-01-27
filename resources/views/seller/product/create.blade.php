@extends('seller.layout.app')

@push('title')
    Create Product
@endpush

@push('css')
    <!-- Google Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        :root {
            --primary: #6366f1;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #3b82f6;
        }

        .page-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--info) 100%);
            color: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .form-section-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .form-section-card:hover {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }

        .section-header {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #e2e8f0;
        }

        .section-header .material-icons {
            font-size: 1.5rem;
            margin-right: 0.75rem;
            color: var(--primary);
        }

        /* Image Upload Styles */
        .upload-container {
            position: relative;
            margin-bottom: 1rem;
        }

        .upload-area {
            border: 3px dashed var(--primary);
            border-radius: 12px;
            padding: 2.5rem 1rem;
            text-align: center;
            cursor: pointer;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            transition: all 0.3s ease;
            min-height: 200px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .upload-area:hover {
            border-color: var(--info);
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            transform: translateY(-2px);
        }

        .upload-area.drag-over {
            border-color: var(--success);
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        }

        .upload-area .material-icons {
            font-size: 3rem;
            color: var(--primary);
            margin-bottom: 1rem;
        }

        #imagePreviewContainer {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .preview-card {
            position: relative;
            width: 150px;
            height: 150px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border: 3px solid transparent;
        }

        .preview-card:hover {
            transform: translateY(-5px);
            border-color: var(--primary);
        }

        .preview-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .remove-image {
            position: absolute;
            top: 8px;
            right: 8px;
            background: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }

        .remove-image:hover {
            background: var(--danger);
            color: white;
            transform: scale(1.1);
        }

        /* Form Elements */
        .form-label {
            font-weight: 600;
            color: #334155;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-label .material-icons {
            font-size: 1.2rem;
            opacity: 0.8;
        }

        .required::after {
            content: " *";
            color: var(--danger);
            margin-left: 2px;
        }

        .form-control, .form-select {
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        /* Price Calculator */
        .price-calculator {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border-radius: 12px;
            padding: 1.25rem;
            margin-top: 1.5rem;
            border: 1px solid #bae6fd;
        }

        .price-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .price-row:last-child {
            border-bottom: none;
        }

        /* Buttons */
        .btn-action {
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, #4f46e5 100%);
            border: none;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.4);
        }

        /* Alert Styles */
        .alert-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
        }

        /* Loading Overlay */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 99999;
            display: none;
        }

        .spinner {
            width: 60px;
            height: 60px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid var(--primary);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Info Text */
        .info-text {
            color: #64748b;
            font-size: 0.85rem;
            margin-top: 0.25rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="fw-bold mb-2 d-flex align-items-center">
                        <span class="material-icons me-2">add_circle</span>
                        Create New Product
                    </h4>
                    <p class="mb-0 opacity-90 d-flex align-items-center">
                        <span class="material-icons me-1" style="font-size: 1.1rem;">info</span>
                        Add a new product to your inventory
                    </p>
                </div>
                <a href="{{ route('seller.product.index') }}" class="btn btn-light d-flex align-items-center">
                    <span class="material-icons me-2">arrow_back</span>
                    Back to Products
                </a>
            </div>
        </div>

        <!-- Success/Error Messages -->
        <div class="alert-container">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <div class="d-flex align-items-center">
                        <span class="material-icons me-2">check_circle</span>
                        <strong>{{ session('success') }}</strong>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <div class="d-flex align-items-center">
                        <span class="material-icons me-2">error</span>
                        <strong>{{ session('error') }}</strong>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
        </div>

        <!-- Loading Overlay -->
        <div class="loading-overlay" id="loadingOverlay">
            <div class="spinner"></div>
            <div class="text-white mt-3">Creating Product...</div>
        </div>

        <!-- Main Form -->
        <form id="productForm" action="{{ route('seller.product.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <!-- Left Column -->
                <div class="col-lg-8">
                    <!-- Basic Information -->
                    <div class="form-section-card">
                        <div class="section-header">
                            <span class="material-icons">info</span>
                            <h5 class="mb-0">Product Information</h5>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label required">
                                    <span class="material-icons">qr_code</span>
                                    Product Code
                                </label>
                                <input type="text" name="code" class="form-control"
                                       value="{{ $code ?? old('code') }}" readonly required>
                                <div class="info-text">
                                    <span class="material-icons" style="font-size: 1rem;">auto_awesome</span>
                                    Auto-generated product code
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label required">
                                    <span class="material-icons">inventory</span>
                                    Product Name
                                </label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name') }}" placeholder="Enter product name" required>
                                @error('name')
                                <div class="invalid-feedback d-flex align-items-center">
                                    <span class="material-icons me-1" style="font-size: 1rem;">error</span>
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <span class="material-icons">barcode</span>
                                    Barcode
                                </label>
                                <input type="text" name="barcode" class="form-control @error('barcode') is-invalid @enderror"
                                       value="{{ old('barcode') }}" placeholder="Enter barcode">
                                @error('barcode')
                                <div class="invalid-feedback d-flex align-items-center">
                                    <span class="material-icons me-1" style="font-size: 1rem;">error</span>
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label required">
                                    <span class="material-icons">category</span>
                                    Product Type
                                </label>
                                <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                                    <option value="">Select Type</option>
                                    <option value="stock_item" {{ old('type') == 'stock_item' ? 'selected' : '' }}>Stock Item</option>
                                    <option value="service" {{ old('type') == 'service' ? 'selected' : '' }}>Service</option>
                                    <option value="digital" {{ old('type') == 'digital' ? 'selected' : '' }}>Digital</option>
                                </select>
                                @error('type')
                                <div class="invalid-feedback d-flex align-items-center">
                                    <span class="material-icons me-1" style="font-size: 1rem;">error</span>
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <span class="material-icons">category</span>
                                    Category
                                </label>
                                <select name="category_id" class="form-select @error('category_id') is-invalid @enderror">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                <div class="invalid-feedback d-flex align-items-center">
                                    <span class="material-icons me-1" style="font-size: 1rem;">error</span>
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <span class="material-icons">branding_watermark</span>
                                    Brand
                                </label>
                                <select name="brand_id" class="form-select @error('brand_id') is-invalid @enderror">
                                    <option value="">Select Brand</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                            {{ $brand->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('brand_id')
                                <div class="invalid-feedback d-flex align-items-center">
                                    <span class="material-icons me-1" style="font-size: 1rem;">error</span>
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label">
                                    <span class="material-icons">description</span>
                                    Description
                                </label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                                          rows="4" placeholder="Enter product description">{{ old('description') }}</textarea>
                                @error('description')
                                <div class="invalid-feedback d-flex align-items-center">
                                    <span class="material-icons me-1" style="font-size: 1rem;">error</span>
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label">
                                    <span class="material-icons">star</span>
                                    Features
                                </label>
                                <textarea name="features" class="form-control @error('features') is-invalid @enderror"
                                          rows="3" placeholder="Enter product features (separate with commas)">{{ old('features') }}</textarea>
                                <div class="info-text">
                                    <span class="material-icons" style="font-size: 1rem;">format_list_bulleted</span>
                                    Separate features with commas
                                </div>
                                @error('features')
                                <div class="invalid-feedback d-flex align-items-center">
                                    <span class="material-icons me-1" style="font-size: 1rem;">error</span>
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Product Image -->
                    <div class="form-section-card">
                        <div class="section-header">
                            <span class="material-icons">image</span>
                            <h5 class="mb-0">Product Image</h5>
                        </div>

                        <div class="upload-container">
                            <div class="upload-area" id="uploadTrigger">
                                <span class="material-icons">cloud_upload</span>
                                <h5 class="mt-3 mb-2">Click to Upload Product Image</h5>
                                <p class="text-muted mb-0">JPG, PNG, GIF, WEBP - Max 2MB</p>
                                <input type="file" name="img" id="imageInput" class="d-none" accept="image/*" required>
                            </div>

                            <div id="imagePreviewContainer"></div>

                            @error('img')
                            <div class="text-danger mt-2 d-flex align-items-center">
                                <span class="material-icons me-1" style="font-size: 1rem;">error</span>
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-lg-4">
                    <!-- Pricing Information -->
                    <div class="form-section-card">
                        <div class="section-header">
                            <span class="material-icons">payments</span>
                            <h5 class="mb-0">Pricing Information</h5>
                        </div>

                        <div class="mb-3">
                            <label class="form-label required">
                                <span class="material-icons">attach_money</span>
                                Stock Price (Rs.)
                            </label>
                            <input type="number" name="stock_price" id="stockPrice"
                                   class="form-control @error('stock_price') is-invalid @enderror"
                                   step="0.01" min="0" value="{{ old('stock_price') }}" required>
                            @error('stock_price')
                            <div class="invalid-feedback d-flex align-items-center">
                                <span class="material-icons me-1" style="font-size: 1rem;">error</span>
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label required">
                                <span class="material-icons">price_check</span>
                                Display Price (Rs.)
                            </label>
                            <input type="number" name="display_price" id="displayPrice"
                                   class="form-control @error('display_price') is-invalid @enderror"
                                   step="0.01" min="0" value="{{ old('display_price') }}" required>
                            @error('display_price')
                            <div class="invalid-feedback d-flex align-items-center">
                                <span class="material-icons me-1" style="font-size: 1rem;">error</span>
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                <span class="material-icons">percent</span>
                                Discount (%)
                            </label>
                            <input type="number" name="discount" id="discount"
                                   class="form-control @error('discount') is-invalid @enderror"
                                   step="0.01" min="0" max="100" value="{{ old('discount', 0) }}">
                            @error('discount')
                            <div class="invalid-feedback d-flex align-items-center">
                                <span class="material-icons me-1" style="font-size: 1rem;">error</span>
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                <span class="material-icons">inventory</span>
                                Packaging Cost (Rs.)
                            </label>
                            <input type="number" name="packaging_cost" id="packagingCost"
                                   class="form-control @error('packaging_cost') is-invalid @enderror"
                                   step="0.01" min="0" value="{{ old('packaging_cost', 0) }}">
                            @error('packaging_cost')
                            <div class="invalid-feedback d-flex align-items-center">
                                <span class="material-icons me-1" style="font-size: 1rem;">error</span>
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <!-- Price Summary -->
                        <div class="price-calculator">
                            <h6 class="mb-3 d-flex align-items-center">
                                <span class="material-icons me-2">calculate</span>
                                Price Summary
                            </h6>
                            <div class="price-row">
                                <span class="d-flex align-items-center">
                                    <span class="material-icons me-1">attach_money</span>
                                    Total Cost:
                                </span>
                                <span id="totalCostDisplay" class="fw-bold">Rs. 0.00</span>
                            </div>
                            <div class="price-row">
                                <span class="d-flex align-items-center">
                                    <span class="material-icons me-1">price_check</span>
                                    After Discount:
                                </span>
                                <span id="finalPriceDisplay" class="fw-bold" style="color: var(--success);">Rs. 0.00</span>
                            </div>
                            <div class="price-row">
                                <span class="d-flex align-items-center">
                                    <span class="material-icons me-1">trending_up</span>
                                    Profit Margin:
                                </span>
                                <span id="profitMargin" class="fw-bold">0.00%</span>
                            </div>
                        </div>
                    </div>

                    <!-- Inventory Information -->
                    <div class="form-section-card">
                        <div class="section-header">
                            <span class="material-icons">inventory_2</span>
                            <h5 class="mb-0">Inventory Information</h5>
                        </div>

                        <div class="mb-3">
                            <label class="form-label required">
                                <span class="material-icons">numbers</span>
                                Stock Quantity
                            </label>
                            <input type="number" name="qty" id="quantity"
                                   class="form-control @error('qty') is-invalid @enderror"
                                   min="0" value="{{ old('qty') }}" required>
                            @error('qty')
                            <div class="invalid-feedback d-flex align-items-center">
                                <span class="material-icons me-1" style="font-size: 1rem;">error</span>
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label required">
                                <span class="material-icons">warning</span>
                                Minimum Quantity
                            </label>
                            <input type="number" name="min_qty"
                                   class="form-control @error('min_qty') is-invalid @enderror"
                                   min="1" value="{{ old('min_qty', 1) }}" required>
                            @error('min_qty')
                            <div class="invalid-feedback d-flex align-items-center">
                                <span class="material-icons me-1" style="font-size: 1rem;">error</span>
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <!-- Stock Value -->
                        <div class="price-calculator">
                            <h6 class="mb-3 d-flex align-items-center">
                                <span class="material-icons me-2">assessment</span>
                                Stock Value
                            </h6>
                            <div class="price-row">
                                <span class="d-flex align-items-center">
                                    <span class="material-icons me-1">inventory_2</span>
                                    Current Stock Value:
                                </span>
                                <span id="stockValueDisplay" class="fw-bold">Rs. 0.00</span>
                            </div>
                            <div class="price-row">
                                <span class="d-flex align-items-center">
                                    <span class="material-icons me-1">trending_up</span>
                                    Potential Sales Value:
                                </span>
                                <span id="potentialValueDisplay" class="fw-bold" style="color: var(--success);">Rs. 0.00</span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="form-section-card">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-action" id="submitBtn">
                                <span class="material-icons">save</span>
                                Create Product
                            </button>
                            <a href="{{ route('seller.product.index') }}" class="btn btn-outline-secondary btn-action">
                                <span class="material-icons">cancel</span>
                                Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('script')
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const uploadTrigger = document.getElementById('uploadTrigger');
            const imageInput = document.getElementById('imageInput');
            const previewContainer = document.getElementById('imagePreviewContainer');
            const form = document.getElementById('productForm');
            const submitBtn = document.getElementById('submitBtn');
            const loadingOverlay = document.getElementById('loadingOverlay');

            // Initialize variables for price calculation
            const stockPrice = document.getElementById('stockPrice');
            const displayPrice = document.getElementById('displayPrice');
            const discount = document.getElementById('discount');
            const packagingCost = document.getElementById('packagingCost');
            const quantity = document.getElementById('quantity');

            // Image Upload Handling
            if (uploadTrigger && imageInput) {
                // Click to upload
                uploadTrigger.addEventListener('click', () => {
                    imageInput.click();
                });

                // Drag and drop functionality
                uploadTrigger.addEventListener('dragover', (e) => {
                    e.preventDefault();
                    uploadTrigger.classList.add('drag-over');
                });

                uploadTrigger.addEventListener('dragleave', () => {
                    uploadTrigger.classList.remove('drag-over');
                });

                uploadTrigger.addEventListener('drop', (e) => {
                    e.preventDefault();
                    uploadTrigger.classList.remove('drag-over');

                    if (e.dataTransfer.files.length > 0) {
                        imageInput.files = e.dataTransfer.files;
                        handleImageUpload(imageInput.files[0]);
                    }
                });

                // File input change
                imageInput.addEventListener('change', (e) => {
                    if (e.target.files.length > 0) {
                        handleImageUpload(e.target.files[0]);
                    }
                });
            }

            function handleImageUpload(file) {
                if (!file) return;

                // Validate file type
                const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
                if (!validTypes.includes(file.type)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid File Type',
                        text: 'Please upload only JPG, PNG, GIF, or WEBP images.',
                        confirmButtonColor: '#6366f1'
                    });
                    return;
                }

                // Validate file size (2MB max)
                if (file.size > 2 * 1024 * 1024) {
                    Swal.fire({
                        icon: 'error',
                        title: 'File Too Large',
                        text: 'Image size must be less than 2MB.',
                        confirmButtonColor: '#6366f1'
                    });
                    return;
                }

                // Create preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewContainer.innerHTML = `
                        <div class="preview-card">
                            <img src="${e.target.result}" alt="Product Image Preview">
                            <div class="remove-image" onclick="removeImagePreview()">
                                <span class="material-icons">close</span>
                            </div>
                        </div>
                    `;
                };
                reader.readAsDataURL(file);
            }

            window.removeImagePreview = function() {
                previewContainer.innerHTML = '';
                imageInput.value = '';
            };

            // Price Calculation Function
            function updatePriceDisplay() {
                const stockPriceVal = parseFloat(stockPrice.value) || 0;
                const displayPriceVal = parseFloat(displayPrice.value) || 0;
                const discountVal = parseFloat(discount.value) || 0;
                const packagingCostVal = parseFloat(packagingCost.value) || 0;
                const quantityVal = parseInt(quantity.value) || 0;

                // Calculate values
                const totalCost = stockPriceVal + packagingCostVal;
                const discountAmount = displayPriceVal * (discountVal / 100);
                const finalPrice = displayPriceVal - discountAmount;
                const profit = finalPrice - totalCost;
                const marginPercent = totalCost > 0 ? (profit / totalCost) * 100 : 0;
                const stockValue = stockPriceVal * quantityVal;
                const potentialValue = finalPrice * quantityVal;

                // Update displays
                document.getElementById('totalCostDisplay').textContent = `Rs. ${totalCost.toFixed(2)}`;
                document.getElementById('finalPriceDisplay').textContent = `Rs. ${finalPrice.toFixed(2)}`;
                document.getElementById('profitMargin').textContent = `${marginPercent.toFixed(2)}%`;
                document.getElementById('stockValueDisplay').textContent = `Rs. ${stockValue.toFixed(2)}`;
                document.getElementById('potentialValueDisplay').textContent = `Rs. ${potentialValue.toFixed(2)}`;

                // Color code profit margin
                const profitMarginEl = document.getElementById('profitMargin');
                if (marginPercent > 20) {
                    profitMarginEl.style.color = 'var(--success)';
                } else if (marginPercent > 0) {
                    profitMarginEl.style.color = 'var(--warning)';
                } else {
                    profitMarginEl.style.color = 'var(--danger)';
                }
            }

            // Bind input events for price calculation
            if (stockPrice && displayPrice && discount && packagingCost && quantity) {
                [stockPrice, displayPrice, discount, packagingCost, quantity].forEach(input => {
                    input.addEventListener('input', updatePriceDisplay);
                });

                // Initial calculation
                updatePriceDisplay();
            }

            // Form Submission Handling
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    // Validate required fields
                    const requiredFields = form.querySelectorAll('[required]');
                    let isValid = true;

                    requiredFields.forEach(field => {
                        if (!field.value.trim()) {
                            isValid = false;
                            field.classList.add('is-invalid');
                        }
                    });

                    // Validate image
                    if (!imageInput.files.length) {
                        isValid = false;
                        Swal.fire({
                            icon: 'error',
                            title: 'Image Required',
                            text: 'Please upload a product image.',
                            confirmButtonColor: '#6366f1'
                        });
                    }

                    // Validate display price vs stock price
                    const stockPriceVal = parseFloat(stockPrice.value) || 0;
                    const displayPriceVal = parseFloat(displayPrice.value) || 0;

                    if (displayPriceVal < stockPriceVal) {
                        isValid = false;
                        Swal.fire({
                            icon: 'error',
                            title: 'Price Error',
                            text: 'Display price cannot be lower than stock price.',
                            confirmButtonColor: '#6366f1'
                        });
                    }

                    if (!isValid) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Validation Error',
                            text: 'Please fill all required fields correctly.',
                            confirmButtonColor: '#6366f1'
                        });
                        return false;
                    }

                    // Show loading
                    loadingOverlay.style.display = 'flex';
                    submitBtn.disabled = true;

                    // Submit form
                    const formData = new FormData(form);

                    fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                        .then(response => {
                            if (response.redirected) {
                                // Handle redirect (traditional form submission)
                                window.location.href = response.url;
                                return;
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data && data.redirect) {
                                // Handle AJAX response with redirect
                                window.location.href = data.redirect;
                            } else if (data && data.success) {
                                // Show success message and redirect
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success!',
                                    text: data.message || 'Product created successfully!',
                                    confirmButtonColor: '#6366f1'
                                }).then(() => {
                                    window.location.href = "{{ route('seller.product.index') }}";
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            loadingOverlay.style.display = 'none';
                            submitBtn.disabled = false;

                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'An error occurred. Please try again.',
                                confirmButtonColor: '#6366f1'
                            });
                        });
                });
            }

            // Auto-close alerts after 5 seconds
            setTimeout(() => {
                document.querySelectorAll('.alert').forEach(alert => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);

            // Real-time validation
            form.querySelectorAll('input, select, textarea').forEach(field => {
                field.addEventListener('blur', function() {
                    if (this.hasAttribute('required') && !this.value.trim()) {
                        this.classList.add('is-invalid');
                    } else {
                        this.classList.remove('is-invalid');
                    }
                });
            });
        });
    </script>
@endpush
