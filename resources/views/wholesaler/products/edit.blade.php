@extends('wholesaler.layouts.app')
@push('title')
    Edit Product - {{ $product->name }}
@endpush
@push('css')
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons+Outlined" rel="stylesheet">
    <style>
        .product-image-container {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .current-image {
            width: 100%;
            max-width: 300px;
            height: 250px;
            object-fit: contain;
            border-radius: 10px;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .image-preview {
            width: 100%;
            max-width: 300px;
            height: 250px;
            object-fit: contain;
            border-radius: 10px;
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            padding: 1rem;
            display: none;
            margin: 0 auto 1rem;
        }

        .upload-area {
            border: 2px dashed var(--main-theme-color);
            border-radius: 12px;
            padding: 40px 20px;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        }

        .upload-area:hover {
            border-color: #3b82f6;
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            transform: translateY(-2px);
        }

        .upload-area i {
            font-size: 3rem;
            color: var(--main-theme-color);
            margin-bottom: 15px;
            opacity: 0.7;
        }

        .section-header {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #e2e8f0;
        }

        .section-header i {
            font-size: 1.5rem;
            margin-right: 12px;
            color: var(--main-theme-color);
            background: linear-gradient(135deg, var(--main-theme-color) 0%, #3b82f6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .form-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            padding: 2rem;
            margin-bottom: 2rem;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .form-card:hover {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }

        .form-label {
            font-weight: 600;
            color: #334155;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .form-label i {
            font-size: 1.2rem;
            opacity: 0.7;
        }

        .required::after {
            content: "*";
            color: #ef4444;
            margin-left: 4px;
        }

        .form-control, .form-select {
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--main-theme-color);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .btn-lg {
            padding: 15px 30px;
            font-size: 1.1rem;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--main-theme-color) 0%, #3b82f6 100%);
            border: none;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
        }

        .btn-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            border: none;
            color: white;
        }

        .btn-warning:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(245, 158, 11, 0.4);
        }

        .price-summary {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border-radius: 12px;
            padding: 1.5rem;
            margin-top: 20px;
            border: 1px solid #bae6fd;
        }

        .price-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .price-row:last-child {
            border-bottom: none;
        }
    </style>
@endpush

@section('content')
    <div class="page-header" style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); padding: 2rem; border-radius: 12px; margin-bottom: 2rem;">
        <div class="page-title">
            <h3 class="fw-bold d-flex align-items-center">
                <span class="material-icons-outlined me-2" style="font-size: 2.5rem;">edit</span>
                Edit Product
            </h3>
            <p class="text-muted mb-0 d-flex align-items-center">
                <span class="material-icons-outlined me-2" style="font-size: 1.2rem;">inventory</span>
                Update product details and inventory information
            </p>
        </div>
        <div class="page-actions">
            <a href="{{ route('wholesaler.products.index') }}" class="btn btn-outline-secondary d-flex align-items-center">
                <span class="material-icons-outlined me-2">arrow_back</span>
                Back to Products
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <div class="d-flex align-items-center">
                <span class="material-icons-outlined me-2">error</span>
                <strong>Please fix the following errors:</strong>
            </div>
            <ul class="mb-0 mt-2 ps-4">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('wholesaler.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" id="editProductForm">
        @csrf
        @method('PUT')
        <div class="row">
            <!-- Left Column -->
            <div class="col-lg-8">
                <!-- Product Information -->
                <div class="form-card">
                    <div class="section-header">
                        <span class="material-icons-outlined">info</span>
                        <h5 class="mb-0">Product Information</h5>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label d-flex align-items-center">
                                    <span class="material-icons-outlined">qr_code</span>
                                    Product Code
                                </label>
                                <input type="text" class="form-control" value="{{ $product->code }}" readonly>
                                <div class="form-text">
                                    <span class="material-icons-outlined" style="font-size: 1rem;">lock</span>
                                    Auto-generated product code
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label d-flex align-items-center">
                                    <span class="material-icons-outlined">inventory</span>
                                    <span class="required">Product Name</span>
                                </label>
                                <input type="text" name="name" class="form-control"
                                       value="{{ old('name', $product->name) }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label d-flex align-items-center">
                                    <span class="material-icons-outlined">barcode</span>
                                    Barcode
                                </label>
                                <input type="text" name="barcode" class="form-control"
                                       value="{{ old('barcode', $product->barcode) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label d-flex align-items-center">
                                    <span class="material-icons-outlined">branding_watermark</span>
                                    Brand
                                </label>
                                <select name="brand_id" class="form-select">
                                    <option value="">Select Brand</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                            {{ $brand->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label d-flex align-items-center">
                                    <span class="material-icons-outlined">category</span>
                                    Category
                                </label>
                                <select name="category_id" class="form-select">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-4">
                                <label class="form-label d-flex align-items-center">
                                    <span class="material-icons-outlined">description</span>
                                    Description
                                </label>
                                <textarea name="description" class="form-control"
                                          rows="5">{{ old('description', $product->description) }}</textarea>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-4">
                                <label class="form-label d-flex align-items-center">
                                    <span class="material-icons-outlined">star</span>
                                    Features
                                </label>
                                <textarea name="features" class="form-control" rows="3"
                                          placeholder="Enter product features separated by commas">{{ old('features', $product->features) }}</textarea>
                                <div class="form-text">
                                    <span class="material-icons-outlined" style="font-size: 1rem;">format_list_bulleted</span>
                                    Separate multiple features with commas
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Image -->
                <div class="form-card">
                    <div class="section-header">
                        <span class="material-icons-outlined">image</span>
                        <h5 class="mb-0">Product Image</h5>
                    </div>
                    <div class="product-image-container">
                        @if($product->image)
                            <div class="mb-3">
                                <label class="form-label d-flex align-items-center">
                                    <span class="material-icons-outlined">image</span>
                                    Current Image
                                </label>
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                     class="current-image" id="currentImage">
                            </div>
                        @endif

                        <div class="upload-area" id="uploadTrigger">
                            <span class="material-icons-outlined">cloud_upload</span>
                            <h5 class="mt-3">Click to Upload New Image</h5>
                            <p class="text-muted mb-0">JPG, PNG, GIF - Max 2MB</p>
                            <input type="file" name="image" id="imageInput" class="d-none" accept="image/*">
                            <img id="imagePreview" class="image-preview">
                        </div>
                        <div class="form-text mt-2">
                            <span class="material-icons-outlined" style="font-size: 1rem;">info</span>
                            Leave empty to keep current image
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="col-lg-4">
                <!-- Pricing & Inventory -->
                <div class="form-card">
                    <div class="section-header">
                        <span class="material-icons-outlined">payments</span>
                        <h5 class="mb-0">Pricing & Inventory</h5>
                    </div>

                    <div class="mb-4">
                        <label class="form-label d-flex align-items-center">
                            <span class="material-icons-outlined">attach_money</span>
                            <span class="required">Cost Price (Rs.)</span>
                        </label>
                        <input type="number" name="cost_price" class="form-control" step="0.01"
                               value="{{ old('cost_price', $product->cost_price) }}" required id="costPrice">
                    </div>

                    <div class="mb-4">
                        <label class="form-label d-flex align-items-center">
                            <span class="material-icons-outlined">price_check</span>
                            <span class="required">Selling Price (Rs.)</span>
                        </label>
                        <input type="number" name="selling_price" class="form-control" step="0.01"
                               value="{{ old('selling_price', $product->selling_price) }}" required id="sellingPrice">
                    </div>

                    <div class="mb-4">
                        <label class="form-label d-flex align-items-center">
                            <span class="material-icons-outlined">percent</span>
                            Discount (%)
                        </label>
                        <input type="number" name="discount" class="form-control" step="0.01"
                               value="{{ old('discount', $product->discount) }}" id="discount" min="0" max="100">
                    </div>

                    <div class="mb-4">
                        <label class="form-label d-flex align-items-center">
                            <span class="material-icons-outlined">inventory</span>
                            Packaging Cost (Rs.)
                        </label>
                        <input type="number" name="packaging_cost" class="form-control" step="0.01"
                               value="{{ old('packaging_cost', $product->packaging_cost) }}" id="packagingCost" min="0">
                    </div>

                    <div class="mb-4">
                        <label class="form-label d-flex align-items-center">
                            <span class="material-icons-outlined">inventory_2</span>
                            <span class="required">Stock Quantity</span>
                        </label>
                        <input type="number" name="qty" class="form-control" value="{{ old('qty', $product->qty) }}"
                               required min="0">
                    </div>

                    <div class="mb-4">
                        <label class="form-label d-flex align-items-center">
                            <span class="material-icons-outlined">warning</span>
                            <span class="required">Minimum Stock Level</span>
                        </label>
                        <input type="number" name="min_stock_level" class="form-control"
                               value="{{ old('min_stock_level', $product->min_stock_level) }}" required min="1">
                    </div>

                    <div class="mb-4">
                        <label class="form-label d-flex align-items-center">
                            <span class="material-icons-outlined">toggle_on</span>
                            <span class="required">Status</span>
                        </label>
                        <select name="status" class="form-select" required>
                            <option value="active" {{ old('status', $product->status) == 'active' ? 'selected' : '' }}>
                                Active
                            </option>
                            <option value="inactive" {{ old('status', $product->status) == 'inactive' ? 'selected' : '' }}>
                                Inactive
                            </option>
                        </select>
                    </div>

                    <!-- Price Summary -->
                    <div class="price-summary">
                        <h6 class="mb-3 d-flex align-items-center">
                            <span class="material-icons-outlined me-2">calculate</span>
                            Price Summary
                        </h6>
                        <div class="price-row">
            <span class="d-flex align-items-center">
                <span class="material-icons-outlined me-1" style="font-size: 1.2rem;">attach_money</span>
                Total Cost:
            </span>
                            <span id="totalCostDisplay" class="fw-bold">Rs. 0.00</span>
                        </div>
                        <div class="price-row">
            <span class="d-flex align-items-center">
                <span class="material-icons-outlined me-1" style="font-size: 1.2rem;">price_check</span>
                Selling Price:
            </span>
                            <span id="sellingPriceDisplay" class="fw-bold">Rs. 0.00</span>
                        </div>
                        <div class="price-row">
            <span class="d-flex align-items-center">
                <span class="material-icons-outlined me-1" style="font-size: 1.2rem;">percent</span>
                After Discount:
            </span>
                            <span id="finalPriceDisplay" class="fw-bold" style="color: #10b981;">Rs. 0.00</span>
                        </div>
                        <div class="price-row">
            <span class="d-flex align-items-center">
                <span class="material-icons-outlined me-1" style="font-size: 1.2rem;">trending_up</span>
                Profit Margin:
            </span>
                            <span id="profitMargin" class="fw-bold">0.00%</span>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="form-card">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <span class="material-icons-outlined">save</span>
                            Update Product
                        </button>
                        <a href="{{ route('wholesaler.products.index') }}" class="btn btn-outline-secondary d-flex align-items-center justify-content-center">
                            <span class="material-icons-outlined me-2">cancel</span>
                            Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // ========== Image Upload ==========
            const uploadTrigger = document.getElementById('uploadTrigger');
            const imageInput = document.getElementById('imageInput');
            const imagePreview = document.getElementById('imagePreview');
            const currentImage = document.getElementById('currentImage');

            uploadTrigger.addEventListener('click', function () {
                imageInput.click();
            });

            imageInput.addEventListener('change', function (event) {
                const file = event.target.files[0];
                if (file) {
                    if (!file.type.startsWith('image/')) {
                        showAlert('Please upload only image files.', 'error');
                        return;
                    }

                    if (file.size > 2 * 1024 * 1024) {
                        showAlert('Image size should be less than 2MB.', 'error');
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function (e) {
                        imagePreview.src = e.target.result;
                        imagePreview.style.display = 'block';

                        if (currentImage) {
                            currentImage.style.display = 'none';
                        }
                    };
                    reader.readAsDataURL(file);
                }
            });

            // ========== Price Calculation ==========
            const displayPriceInput = document.getElementById('displayPrice');
            const wholesalePriceInput = document.getElementById('wholesalePrice');
            const displayPriceDisplay = document.getElementById('displayPriceDisplay');
            const wholesalePriceDisplay = document.getElementById('wholesalePriceDisplay');
            const priceDifference = document.getElementById('priceDifference');

            function updatePriceDisplay() {
                const displayPrice = displayPriceInput ? parseFloat(displayPriceInput.value) || 0 : 0;
                const wholesalePrice = wholesalePriceInput ? parseFloat(wholesalePriceInput.value) || 0 : 0;
                const difference = displayPrice - wholesalePrice;
                const marginPercent = wholesalePrice > 0 ? (difference / wholesalePrice) * 100 : 0;

                if (displayPriceDisplay) {
                    displayPriceDisplay.textContent = `Rs. ${displayPrice.toLocaleString('en-IN', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    })}`;
                }

                if (wholesalePriceDisplay) {
                    wholesalePriceDisplay.textContent = `Rs. ${wholesalePrice.toLocaleString('en-IN', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    })}`;
                }

                if (priceDifference) {
                    priceDifference.textContent = `Rs. ${difference.toLocaleString('en-IN', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    })} (${marginPercent.toFixed(2)}%)`;

                    if (difference > 0) {
                        priceDifference.style.color = '#10b981';
                    } else if (difference < 0) {
                        priceDifference.style.color = '#ef4444';
                    } else {
                        priceDifference.style.color = '#6b7280';
                    }
                }
            }

            if (displayPriceInput) displayPriceInput.addEventListener('input', updatePriceDisplay);
            if (wholesalePriceInput) wholesalePriceInput.addEventListener('input', updatePriceDisplay);

            // ========== Form Validation ==========
            const form = document.getElementById('editProductForm');
            if (form) {
                form.addEventListener('submit', function (e) {
                    // Check price validation
                    const displayPrice = parseFloat(displayPriceInput.value) || 0;
                    const wholesalePrice = parseFloat(wholesalePriceInput.value) || 0;

                    if (wholesalePrice > displayPrice) {
                        e.preventDefault();
                        showAlert('Wholesale price cannot be higher than display price.', 'error');
                        return false;
                    }

                    return true;
                });
            }

            // ========== Alert Function ==========
            function showAlert(message, type) {
                // Remove existing alerts
                const existingAlert = document.querySelector('.custom-alert');
                if (existingAlert) existingAlert.remove();

                const alert = document.createElement('div');
                alert.className = `alert alert-${type === 'error' ? 'danger' : 'success'} custom-alert`;
                alert.innerHTML = `
                    <div class="d-flex align-items-center">
                        <span class="material-icons-outlined me-2">${type === 'error' ? 'error' : 'check_circle'}</span>
                        <span>${message}</span>
                    </div>
                `;

                document.querySelector('.page-header').after(alert);

                // Auto remove after 5 seconds
                setTimeout(() => {
                    if (alert.parentNode) {
                        alert.remove();
                    }
                }, 5000);
            }

            // Initialize price display
            updatePriceDisplay();
        });
    </script>
@endpush
