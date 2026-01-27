@extends('seller.layout.app')

@push('title')
    Edit Product
@endpush

@push('css')
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons+Outlined" rel="stylesheet">
    <style>
        .form-section-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            padding: 2rem;
            margin-bottom: 1.5rem;
            border: 1px solid #e2e8f0;
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
            color: #6366f1;
        }

        .upload-area {
            border: 2px dashed #6366f1;
            border-radius: 12px;
            padding: 40px 20px;
            text-align: center;
            cursor: pointer;
            background: #f8fafc;
        }

        .upload-area:hover {
            background: #eff6ff;
        }

        .preview-card {
            position: relative;
            width: 150px;
            height: 150px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.1);
            display: inline-block;
            margin: 10px;
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
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        .remove-image:hover {
            background: #ef4444;
            color: white;
        }

        .price-calculator {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border-radius: 12px;
            padding: 1.5rem;
            margin-top: 20px;
        }

        .price-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .form-control, .form-select {
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 12px 15px;
        }

        .form-control:focus, .form-select:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .page-header {
            background: #f8fafc;
            padding: 1.5rem;
            border-radius: 12px;
            margin-bottom: 2rem;
        }

        .btn-action {
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: 600;
        }
    </style>
@endpush

@section('content')
    <div class="page-header d-flex justify-content-between align-items-center">
        <div>
            <h4 class="fw-bold mb-2">
                <span class="material-icons-outlined align-middle me-2">edit</span>
                Edit Product
            </h4>
            <h6 class="text-muted">Update product: {{ $product->name }}</h6>
        </div>
        <a href="{{ route('seller.product.index') }}" class="btn btn-outline-secondary">
            <span class="material-icons-outlined align-middle me-1">arrow_back</span>
            Back to Products
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('seller.product.update', $product->id) }}" method="POST" enctype="multipart/form-data" id="productForm">
        @csrf
        @method('PUT')

        <div class="row">
            <!-- Left Column -->
            <div class="col-lg-8">
                <!-- Basic Information -->
                <div class="form-section-card">
                    <div class="section-header">
                        <span class="material-icons-outlined">info</span>
                        <h5>Product Information</h5>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <span class="material-icons-outlined align-middle me-1">qr_code</span>
                                Product Code
                            </label>
                            <input type="text" class="form-control" value="{{ $product->code }}" disabled>
                            <small class="text-muted">Code cannot be changed</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <span class="material-icons-outlined align-middle me-1">inventory</span>
                                Product Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="name" class="form-control" required
                                   value="{{ old('name', $product->name) }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <span class="material-icons-outlined align-middle me-1">barcode</span>
                                Barcode
                            </label>
                            <input type="text" name="barcode" class="form-control"
                                   value="{{ old('barcode', $product->barcode) }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <span class="material-icons-outlined align-middle me-1">category</span>
                                Product Type <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" name="type" required>
                                <option value="stock_item" {{ old('type', $product->type) == 'stock_item' ? 'selected' : '' }}>Stock Item</option>
                                <option value="service" {{ old('type', $product->type) == 'service' ? 'selected' : '' }}>Service</option>
                                <option value="digital" {{ old('type', $product->type) == 'digital' ? 'selected' : '' }}>Digital</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <span class="material-icons-outlined align-middle me-1">category</span>
                                Category
                            </label>
                            <select class="form-select" name="category_id">
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <span class="material-icons-outlined align-middle me-1">branding_watermark</span>
                                Brand
                            </label>
                            <select class="form-select" name="brand_id">
                                <option value="">Select Brand</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}"
                                        {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                        {{ $brand->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label">
                                <span class="material-icons-outlined align-middle me-1">description</span>
                                Description
                            </label>
                            <textarea rows="4" class="form-control" name="description">{{ old('description', $product->description) }}</textarea>
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label">
                                <span class="material-icons-outlined align-middle me-1">star</span>
                                Features
                            </label>
                            <textarea rows="3" class="form-control" name="features">{{ old('features', $product->features) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Product Image -->
                <div class="form-section-card">
                    <div class="section-header">
                        <span class="material-icons-outlined">image</span>
                        <h5>Product Image</h5>
                    </div>

                    @if($product->img)
                        <div class="mb-3">
                            <p class="text-muted mb-2">Current Image:</p>
                            <div class="preview-card">
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                            </div>
                        </div>
                    @endif

                    <div class="upload-area" id="uploadTrigger">
                        <span class="material-icons-outlined" style="font-size: 3rem; color: #6366f1;">cloud_upload</span>
                        <h5 class="mt-3">Click to Upload New Image</h5>
                        <p class="text-muted mb-0">JPG, PNG, GIF, WEBP - Max 2MB</p>
                        <input type="file" id="imageInput" name="img" class="d-none" accept="image/*">
                    </div>

                    <div id="imagePreviewContainer" class="mt-4"></div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="col-lg-4">
                <!-- Pricing Information -->
                <div class="form-section-card">
                    <div class="section-header">
                        <span class="material-icons-outlined">payments</span>
                        <h5>Pricing</h5>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            Stock Price (Rs.) <span class="text-danger">*</span>
                        </label>
                        <input type="number" name="stock_price" class="form-control" required step="0.01"
                               id="stockPrice" value="{{ old('stock_price', $product->stock_price) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            Display Price (Rs.) <span class="text-danger">*</span>
                        </label>
                        <input type="number" name="display_price" class="form-control" required step="0.01"
                               id="displayPrice" value="{{ old('display_price', $product->display_price) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Discount (%)</label>
                        <input type="number" name="discount" class="form-control" step="0.01"
                               id="discount" value="{{ old('discount', $product->discount) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Packaging Cost (Rs.)</label>
                        <input type="number" name="packaging_cost" class="form-control" step="0.01"
                               id="packagingCost" value="{{ old('packaging_cost', $product->packaging_cost) }}">
                    </div>

                    <!-- Price Summary -->
                    <div class="price-calculator">
                        <h6 class="mb-3">Price Summary</h6>
                        <div class="price-row">
                            <span>Total Cost:</span>
                            <span id="totalCostDisplay" class="fw-bold">Rs. 0.00</span>
                        </div>
                        <div class="price-row">
                            <span>After Discount:</span>
                            <span id="finalPriceDisplay" class="fw-bold text-success">Rs. 0.00</span>
                        </div>
                        <div class="price-row border-0">
                            <span>Profit Margin:</span>
                            <span id="profitMargin" class="fw-bold text-primary">0.00%</span>
                        </div>
                    </div>
                </div>

                <!-- Inventory Information -->
                <div class="form-section-card">
                    <div class="section-header">
                        <span class="material-icons-outlined">inventory_2</span>
                        <h5>Inventory</h5>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            Stock Quantity <span class="text-danger">*</span>
                        </label>
                        <input type="number" name="qty" class="form-control" required
                               id="quantity" value="{{ old('qty', $product->qty) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            Minimum Quantity <span class="text-danger">*</span>
                        </label>
                        <input type="number" name="min_qty" class="form-control" required
                               value="{{ old('min_qty', $product->min_qty) }}">
                    </div>

                    <div class="price-calculator">
                        <h6 class="mb-3">Stock Value</h6>
                        <div class="price-row">
                            <span>Current Stock Value:</span>
                            <span id="stockValueDisplay" class="fw-bold">Rs. 0.00</span>
                        </div>
                        <div class="price-row border-0">
                            <span>Potential Sales Value:</span>
                            <span id="potentialValueDisplay" class="fw-bold text-success">Rs. 0.00</span>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="form-section-card">
                    <button type="submit" class="btn btn-primary w-100 mb-2 btn-action">
                        <span class="material-icons-outlined align-middle me-1">save</span>
                        Update Product
                    </button>
                    <a href="{{ route('seller.product.index') }}" class="btn btn-outline-secondary w-100 btn-action">
                        <span class="material-icons-outlined align-middle me-1">cancel</span>
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Image upload
            const uploadTrigger = document.getElementById('uploadTrigger');
            const imageInput = document.getElementById('imageInput');
            const previewContainer = document.getElementById('imagePreviewContainer');

            if (uploadTrigger && imageInput) {
                uploadTrigger.addEventListener('click', () => imageInput.click());

                imageInput.addEventListener('change', function(event) {
                    const file = event.target.files[0];
                    if (file) {
                        if (!file.type.startsWith('image/')) {
                            alert('Please upload only image files.');
                            return;
                        }
                        if (file.size > 2 * 1024 * 1024) {
                            alert('Image size should be less than 2MB.');
                            return;
                        }

                        const reader = new FileReader();
                        reader.onload = function(e) {
                            previewContainer.innerHTML = `
                                <p class="text-muted mb-2">New Image Preview:</p>
                                <div class="preview-card">
                                    <img src="${e.target.result}" alt="Preview">
                                    <div class="remove-image" onclick="this.parentElement.parentElement.innerHTML = ''; document.getElementById('imageInput').value = '';">
                                        <span class="material-icons-outlined">close</span>
                                    </div>
                                </div>
                            `;
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }

            // Price calculation
            const stockPriceInput = document.getElementById('stockPrice');
            const displayPriceInput = document.getElementById('displayPrice');
            const discountInput = document.getElementById('discount');
            const packagingCostInput = document.getElementById('packagingCost');
            const quantityInput = document.getElementById('quantity');

            function updatePriceDisplay() {
                const stockPrice = parseFloat(stockPriceInput.value) || 0;
                const displayPrice = parseFloat(displayPriceInput.value) || 0;
                const discount = parseFloat(discountInput.value) || 0;
                const packagingCost = parseFloat(packagingCostInput.value) || 0;
                const quantity = parseInt(quantityInput.value) || 0;

                const totalCost = stockPrice + packagingCost;
                const finalPrice = displayPrice - (displayPrice * (discount / 100));
                const profit = finalPrice - totalCost;
                const marginPercent = totalCost > 0 ? (profit / totalCost) * 100 : 0;
                const stockValue = stockPrice * quantity;
                const potentialValue = finalPrice * quantity;

                document.getElementById('totalCostDisplay').textContent = `Rs. ${totalCost.toFixed(2)}`;
                document.getElementById('finalPriceDisplay').textContent = `Rs. ${finalPrice.toFixed(2)}`;
                document.getElementById('profitMargin').textContent = `${marginPercent.toFixed(2)}%`;
                document.getElementById('stockValueDisplay').textContent = `Rs. ${stockValue.toFixed(2)}`;
                document.getElementById('potentialValueDisplay').textContent = `Rs. ${potentialValue.toFixed(2)}`;
            }

            [stockPriceInput, displayPriceInput, discountInput, packagingCostInput, quantityInput].forEach(input => {
                if (input) input.addEventListener('input', updatePriceDisplay);
            });

            // Form validation
            document.getElementById('productForm').addEventListener('submit', function(e) {
                const stockPrice = parseFloat(stockPriceInput.value) || 0;
                const displayPrice = parseFloat(displayPriceInput.value) || 0;

                if (displayPrice < stockPrice) {
                    e.preventDefault();
                    alert('Display price cannot be lower than stock price.');
                    return false;
                }
            });

            // Initial calculation
            updatePriceDisplay();
        });
    </script>
@endpush
