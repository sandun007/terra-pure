@extends('wholesaler.layouts.app')
@push('title')
    Create Product
@endpush
@push('css')
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons+Outlined" rel="stylesheet">
    <style>
        .image-preview-container {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 20px;
        }

        .preview-card {
            position: relative;
            width: 120px;
            height: 120px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .preview-card:hover {
            transform: translateY(-5px);
            border-color: var(--main-theme-color);
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
            background: rgba(255, 255, 255, 0.95);
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }

        .remove-image:hover {
            background: #ef4444;
            color: white;
            transform: scale(1.1);
        }

        .upload-area {
            border: 2px dashed var(--main-theme-color);
            border-radius: 12px;
            padding: 40px 20px;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            min-height: 200px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .upload-area:hover {
            border-color: #3b82f6;
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            transform: translateY(-2px);
        }

        .upload-area i {
            font-size: 3.5rem;
            color: var(--main-theme-color);
            margin-bottom: 15px;
            opacity: 0.7;
        }

        .upload-area.drag-over {
            border-color: #10b981;
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
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

        .price-display {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border-radius: 12px;
            padding: 1.5rem;
            margin-top: 15px;
            border: 1px solid #bae6fd;
        }

        .price-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .price-row:last-child {
            border-bottom: none;
        }

        .final-price {
            font-weight: bold;
            font-size: 1.3rem;
            color: var(--main-theme-color);
            border-top: 2px solid #bae6fd;
            padding-top: 15px;
            margin-top: 10px;
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

        .btn-outline-secondary {
            border: 2px solid #cbd5e1;
        }

        .btn-outline-secondary:hover {
            border-color: #94a3b8;
            background: #f8fafc;
        }

        .alert {
            border-radius: 10px;
            border: none;
            padding: 1rem 1.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .form-text {
            color: #64748b;
            font-size: 0.9rem;
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
    </style>
@endpush

@section('content')
    <div class="page-header" style="background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); padding: 2rem; border-radius: 12px; margin-bottom: 2rem;">
        <div class="page-title">
            <h3 class="fw-bold d-flex align-items-center">
                <span class="material-icons-outlined me-2" style="font-size: 2.5rem;">add_circle</span>
                Create New Product
            </h3>
            <p class="text-muted mb-0 d-flex align-items-center">
                <span class="material-icons-outlined me-2" style="font-size: 1.2rem;">info</span>
                Add a new product to your inventory
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

    <form action="{{ route('wholesaler.products.store') }}" method="post" enctype="multipart/form-data"
          class="product-form" id="productForm">
        @csrf
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
                                    <span class="required">Product Code</span>
                                </label>
                                <input type="text" name="code" class="form-control" required readonly
                                       value="{{ $productCode ?? $code ?? '' }}">
                                <div class="form-text">
                                    <span class="material-icons-outlined" style="font-size: 1rem;">auto_awesome</span>
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
                                <input type="text" name="name" class="form-control" required
                                       placeholder="Enter product name" value="{{ old('name') }}">
                                @error('name')
                                <div class="text-danger mt-2 d-flex align-items-center">
                                    <span class="material-icons-outlined me-1" style="font-size: 1rem;">error</span>
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label d-flex align-items-center">
                                    <span class="material-icons-outlined">barcode</span>
                                    Barcode
                                </label>
                                <input type="text" name="barcode" class="form-control" placeholder="Enter barcode"
                                       value="{{ old('barcode') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label d-flex align-items-center">
                                    <span class="material-icons-outlined">branding_watermark</span>
                                    Brand
                                </label>
                                <select class="form-select" name="brand_id">
                                    <option value="">Select Brand</option>
                                    @foreach($brands as $brand)
                                        <option
                                            value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
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
                                <select class="form-select" name="category_id">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option
                                            value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                                <textarea rows="5" class="form-control" name="description"
                                          placeholder="Enter detailed product description">{{ old('description') }}</textarea>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-4">
                                <label class="form-label d-flex align-items-center">
                                    <span class="material-icons-outlined">star</span>
                                    Features
                                </label>
                                <textarea rows="3" class="form-control" name="features"
                                          placeholder="Enter product features separated by commas">{{ old('features') }}</textarea>
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

                    <div class="upload-area" id="uploadTrigger">
                        <span class="material-icons-outlined" style="font-size: 4rem;">cloud_upload</span>
                        <h5 class="mt-3">Click to Upload Product Image</h5>
                        <p class="text-muted mb-0">JPG, PNG, GIF, WEBP - Max 2MB</p>
                        <input type="file" id="imageInput" name="image" class="d-none" accept="image/*" required>
                    </div>

                    <div id="imagePreviewContainer" class="image-preview-container mt-4">
                        <!-- Preview image will appear here -->
                    </div>
                    @error('image')
                    <div class="text-danger mt-2 d-flex align-items-center">
                        <span class="material-icons-outlined me-1" style="font-size: 1rem;">error</span>
                        {{ $message }}
                    </div>
                    @enderror
                </div>
            </div>

            <!-- Right Column -->
            <div class="col-lg-4">
                <!-- Inventory & Pricing -->
                <div class="form-card">
                    <div class="section-header">
                        <span class="material-icons-outlined">payments</span>
                        <h5 class="mb-0">Inventory & Pricing</h5>
                    </div>

                    <div class="mb-4">
                        <label class="form-label d-flex align-items-center">
                            <span class="material-icons-outlined">inventory_2</span>
                            <span class="required">Stock Quantity</span>
                        </label>
                        <input type="number" name="qty" class="form-control" required placeholder="Enter quantity"
                               value="{{ old('qty') }}" min="0">
                        @error('qty')
                        <div class="text-danger mt-2 d-flex align-items-center">
                            <span class="material-icons-outlined me-1" style="font-size: 1rem;">error</span>
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label d-flex align-items-center">
                            <span class="material-icons-outlined">warning</span>
                            <span class="required">Minimum Stock Level</span>
                        </label>
                        <input type="number" name="min_stock_level" class="form-control" required
                               placeholder="Enter minimum stock level" value="{{ old('min_stock_level', 10) }}" min="1">
                        @error('min_stock_level')
                        <div class="text-danger mt-2 d-flex align-items-center">
                            <span class="material-icons-outlined me-1" style="font-size: 1rem;">error</span>
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label d-flex align-items-center">
                            <span class="material-icons-outlined">attach_money</span>
                            <span class="required">Cost Price (Rs.)</span>
                        </label>
                        <input type="number" name="cost_price" class="form-control" required
                               placeholder="Enter cost price" step="0.01" id="costPrice"
                               value="{{ old('cost_price') }}" min="0">
                        @error('cost_price')
                        <div class="text-danger mt-2 d-flex align-items-center">
                            <span class="material-icons-outlined me-1" style="font-size: 1rem;">error</span>
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label d-flex align-items-center">
                            <span class="material-icons-outlined">price_check</span>
                            <span class="required">Selling Price (Rs.)</span>
                        </label>
                        <input type="number" name="selling_price" class="form-control" required
                               placeholder="Enter selling price" step="0.01" id="sellingPrice"
                               value="{{ old('selling_price') }}" min="0">
                        @error('selling_price')
                        <div class="text-danger mt-2 d-flex align-items-center">
                            <span class="material-icons-outlined me-1" style="font-size: 1rem;">error</span>
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label d-flex align-items-center">
                            <span class="material-icons-outlined">percent</span>
                            Discount (%)
                        </label>
                        <input type="number" name="discount" class="form-control" placeholder="Enter discount percentage"
                               step="0.01" id="discount" value="{{ old('discount', 0) }}" min="0" max="100">
                        @error('discount')
                        <div class="text-danger mt-2 d-flex align-items-center">
                            <span class="material-icons-outlined me-1" style="font-size: 1rem;">error</span>
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label d-flex align-items-center">
                            <span class="material-icons-outlined">inventory</span>
                            Packaging Cost (Rs.)
                        </label>
                        <input type="number" name="packaging_cost" class="form-control" placeholder="Enter packaging cost"
                               step="0.01" id="packagingCost" value="{{ old('packaging_cost', 0) }}" min="0">
                        @error('packaging_cost')
                        <div class="text-danger mt-2 d-flex align-items-center">
                            <span class="material-icons-outlined me-1" style="font-size: 1rem;">error</span>
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label d-flex align-items-center">
                            <span class="material-icons-outlined">toggle_on</span>
                            <span class="required">Status</span>
                        </label>
                        <select name="status" class="form-select" required>
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                        <div class="text-danger mt-2 d-flex align-items-center">
                            <span class="material-icons-outlined me-1" style="font-size: 1rem;">error</span>
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <!-- Price Summary -->
                    <div class="price-display">
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
                        <div class="price-row final-price">
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
                            Create Product
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
            // ========== Image Upload with Drag & Drop ==========
            const uploadTrigger = document.getElementById('uploadTrigger');
            const imageInput = document.getElementById('imageInput');
            const previewContainer = document.getElementById('imagePreviewContainer');

            // Click to upload
            if (uploadTrigger && imageInput) {
                uploadTrigger.addEventListener('click', function () {
                    imageInput.click();
                });
            }

            // Drag and drop functionality
            if (uploadTrigger) {
                uploadTrigger.addEventListener('dragover', function (e) {
                    e.preventDefault();
                    this.classList.add('drag-over');
                });

                uploadTrigger.addEventListener('dragleave', function () {
                    this.classList.remove('drag-over');
                });

                uploadTrigger.addEventListener('drop', function (e) {
                    e.preventDefault();
                    this.classList.remove('drag-over');

                    if (e.dataTransfer.files.length) {
                        imageInput.files = e.dataTransfer.files;
                        handleImageUpload(imageInput.files[0]);
                    }
                });
            }

            // Image input change handler
            if (imageInput && previewContainer) {
                imageInput.addEventListener('change', function (event) {
                    const file = event.target.files[0];
                    if (file) handleImageUpload(file);
                });
            }

            function handleImageUpload(file) {
                if (!file.type || !file.type.startsWith('image/')) {
                    showAlert('Please upload only image files.', 'error');
                    return;
                }

                if (file.size > 2 * 1024 * 1024) { // 2MB limit
                    showAlert('Image size should be less than 2MB.', 'error');
                    return;
                }

                // Clear previous preview
                previewContainer.innerHTML = '';

                const reader = new FileReader();
                reader.onload = function (e) {
                    const previewCard = document.createElement('div');
                    previewCard.className = 'preview-card';

                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.alt = 'Uploaded image';

                    const removeBtn = document.createElement('div');
                    removeBtn.className = 'remove-image';
                    removeBtn.innerHTML = '<span class="material-icons-outlined">close</span>';
                    removeBtn.onclick = () => {
                        previewCard.remove();
                        imageInput.value = '';
                    };

                    previewCard.appendChild(img);
                    previewCard.appendChild(removeBtn);
                    previewContainer.appendChild(previewCard);

                    showAlert('Image uploaded successfully!', 'success');
                };
                reader.readAsDataURL(file);
            }

// ========== Price Calculation ==========
            const costPriceInput = document.getElementById('costPrice');
            const sellingPriceInput = document.getElementById('sellingPrice');
            const discountInput = document.getElementById('discount');
            const packagingCostInput = document.getElementById('packagingCost');
            const totalCostDisplay = document.getElementById('totalCostDisplay');
            const sellingPriceDisplay = document.getElementById('sellingPriceDisplay');
            const finalPriceDisplay = document.getElementById('finalPriceDisplay');
            const profitMargin = document.getElementById('profitMargin');

            function updatePriceDisplay() {
                const costPrice = costPriceInput ? parseFloat(costPriceInput.value) || 0 : 0;
                const sellingPrice = sellingPriceInput ? parseFloat(sellingPriceInput.value) || 0 : 0;
                const discount = discountInput ? parseFloat(discountInput.value) || 0 : 0;
                const packagingCost = packagingCostInput ? parseFloat(packagingCostInput.value) || 0 : 0;

                // Calculations
                const totalCost = costPrice + packagingCost;
                const discountAmount = sellingPrice * (discount / 100);
                const finalPrice = sellingPrice - discountAmount;
                const profit = finalPrice - totalCost;
                const marginPercent = totalCost > 0 ? (profit / totalCost) * 100 : 0;

                if (totalCostDisplay) {
                    totalCostDisplay.textContent = `Rs. ${totalCost.toLocaleString('en-IN', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    })}`;
                }

                if (sellingPriceDisplay) {
                    sellingPriceDisplay.textContent = `Rs. ${sellingPrice.toLocaleString('en-IN', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    })}`;
                }

                if (finalPriceDisplay) {
                    finalPriceDisplay.textContent = `Rs. ${finalPrice.toLocaleString('en-IN', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    })}`;

                    if (discount > 0) {
                        finalPriceDisplay.innerHTML += ` <small class="text-success">(-${discount}%)</small>`;
                    }
                }

                if (profitMargin) {
                    profitMargin.textContent = `${marginPercent.toLocaleString('en-IN', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    })}%`;

                    if (marginPercent > 20) {
                        profitMargin.style.color = '#10b981';
                    } else if (marginPercent > 0) {
                        profitMargin.style.color = '#f59e0b';
                    } else {
                        profitMargin.style.color = '#ef4444';
                    }
                }
            }

            if (costPriceInput) costPriceInput.addEventListener('input', updatePriceDisplay);
            if (sellingPriceInput) sellingPriceInput.addEventListener('input', updatePriceDisplay);
            if (discountInput) discountInput.addEventListener('input', updatePriceDisplay);
            if (packagingCostInput) packagingCostInput.addEventListener('input', updatePriceDisplay);

// ========== Form Validation ==========
            const form = document.getElementById('productForm');
            if (form) {
                form.addEventListener('submit', function (e) {
                    // Check if image is uploaded
                    const image = document.querySelector('#imagePreviewContainer .preview-card');
                    if (!image) {
                        e.preventDefault();
                        showAlert('Please upload a product image.', 'error');
                        return false;
                    }

                    // Check price validation
                    const costPrice = parseFloat(costPriceInput.value) || 0;
                    const sellingPrice = parseFloat(sellingPriceInput.value) || 0;
                    const discount = parseFloat(discountInput.value) || 0;
                    const finalPrice = sellingPrice - (sellingPrice * (discount / 100));

                    if (finalPrice < costPrice) {
                        e.preventDefault();
                        showAlert('Final price after discount cannot be lower than cost price.', 'error');
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
