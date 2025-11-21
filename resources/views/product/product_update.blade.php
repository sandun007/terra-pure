@extends('admin.app')
@push('title')
    Update Product
@endpush
@push('css')
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
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }

        .preview-card:hover {
            transform: translateY(-5px);
        }

        .preview-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .remove-image {
            position: absolute;
            top: 5px;
            right: 5px;
            background: rgba(255, 255, 255, 0.9);
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        .upload-area {
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            padding: 30px;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            background: #f8f9fa;
        }

        .upload-area:hover {
            border-color: var(--main-theme-color);
            background: rgba(var(--main-theme-color-rgb), 0.05);
        }

        .upload-area i {
            font-size: 48px;
            color: #6c757d;
            margin-bottom: 15px;
        }

        .section-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e9ecef;
        }

        .section-header i {
            font-size: 20px;
            margin-right: 10px;
            color: var(--main-theme-color);
        }

        .form-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            padding: 25px;
            margin-bottom: 25px;
            border: 1px solid #e9ecef;
        }

        .price-display {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-top: 10px;
        }

        .price-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .final-price {
            font-weight: bold;
            font-size: 1.2rem;
            color: var(--main-theme-color);
            border-top: 1px solid #dee2e6;
            padding-top: 10px;
            margin-top: 10px;
        }

        .existing-image {
            position: relative;
            width: 120px;
            height: 120px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }

        .existing-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    </style>
@endpush

@section('content')
    <div class="page-header">
        <div class="page-title">
            <h3 class="fw-bold">Update Product</h3>
            <p class="text-muted">Edit product details and inventory information</p>
        </div>
        <div class="page-actions">
            <a href="{{route('product.index')}}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Products
            </a>
        </div>
    </div>

    <form action="{{route('product.update', $product->id)}}" method="post" enctype="multipart/form-data" class="product-form">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-lg-8">
                <div class="form-card">
                    <div class="section-header">
                        <i class="fas fa-info-circle"></i>
                        <h5 class="mb-0">Product Information</h5>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Product Code <span class="text-danger">*</span></label>
                                <input type="text" name="code" class="form-control" required disabled value="{{$product->code}}">
                                <div class="form-text">Auto-generated product code</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Product Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" required placeholder="Enter product name" value="{{$product->name}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Barcode</label>
                                <input type="text" name="barcode" class="form-control" placeholder="Enter barcode" value="{{$product->barcode}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Brand</label>
                                <select class="form-select" name="brand">
                                    <option value="">Select Brand</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->name }}" {{ $product->brand == $brand->name ? 'selected' : '' }}>
                                            {{ $brand->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Category</label>
                                <select class="form-select" name="category">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{$category->name}}" {{ $product->category == $category->name ? 'selected' : '' }}>
                                            {{$category->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-select" name="status">
                                    <option value="active" {{ $product->status == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ $product->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea rows="4" class="form-control" name="description" placeholder="Enter product description">{{ $product->description }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-card">
                    <div class="section-header">
                        <i class="fas fa-images"></i>
                        <h5 class="mb-0">Product Images</h5>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Current Image</label>
                        <div class="existing-image">
                            <img src="{{ asset('storage/' . $product->img) }}" alt="{{ $product->name }}">
                        </div>
                    </div>

                    <div class="upload-area" id="uploadTrigger">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <h5>Drag & Drop New Images Here</h5>
                        <p class="text-muted">or click to browse files</p>
                        <input type="file" id="imageInput" name="img[]" multiple class="d-none">
                    </div>

                    <div id="imagePreviewContainer" class="image-preview-container mt-4">
                        <!-- New preview images will appear here -->
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="form-card">
                    <div class="section-header">
                        <i class="fas fa-cube"></i>
                        <h5 class="mb-0">Inventory & Pricing</h5>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Stock Quantity <span class="text-danger">*</span></label>
                        <input type="number" name="qty" class="form-control" required placeholder="Enter quantity" value="{{$product->qty}}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Minimum Stock Level <span class="text-danger">*</span></label>
                        <input type="number" name="min_qty" class="form-control" required placeholder="Enter minimum quantity" value="{{$product->min_qty}}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Cost Price <span class="text-danger">*</span></label>
                        <input type="number" name="stock_price" class="form-control" required placeholder="Enter cost price" step="0.01" id="costPrice" value="{{$product->stock_price}}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Selling Price <span class="text-danger">*</span></label>
                        <input type="number" name="display_price" class="form-control" required placeholder="Enter selling price" step="0.01" id="sellingPrice" value="{{$product->display_price}}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Discount %</label>
                        <input type="number" name="discount" class="form-control" placeholder="Enter discount percentage" step="0.01" id="discountInput" value="{{$product->discount}}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Packing Cost</label>
                        <input type="number" name="packing_cost" class="form-control" placeholder="Enter packing cost" step="0.01" id="packingCost" value="{{$product->packing_cost}}">
                    </div>

                    <div class="price-display">
                        <div class="price-row">
                            <span>Cost Price:</span>
                            <span id="costDisplay">Rs. {{ number_format($product->stock_price, 2) }}</span>
                        </div>
                        <div class="price-row">
                            <span>Selling Price:</span>
                            <span id="sellingDisplay">Rs. {{ number_format($product->display_price, 2) }}</span>
                        </div>
                        <div class="price-row">
                            <span>Packing Cost:</span>
                            <span id="packingDisplay">Rs. {{ number_format($product->packing_cost, 2) }}</span>
                        </div>
                        <div class="price-row">
                            <span>Discount:</span>
                            <span id="discountDisplay">Rs. {{ number_format($product->display_price * ($product->discount / 100), 2) }}</span>
                        </div>
                        <div class="price-row final-price">
                            <span>Final Price:</span>
                            <span id="finalPrice">Rs. {{ number_format($product->display_price - ($product->display_price * ($product->discount / 100)) + $product->packing_cost, 2) }}</span>
                        </div>
                    </div>
                </div>

                <div class="form-card">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save me-2"></i>Update Product
                        </button>
                        <a href="{{route('product.index')}}" class="btn btn-outline-secondary">
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
        // Image upload and preview functionality
        document.getElementById('uploadTrigger').addEventListener('click', function() {
            document.getElementById('imageInput').click();
        });

        document.getElementById('imageInput').addEventListener('change', function (event) {
            const files = event.target.files;
            const previewContainer = document.getElementById('imagePreviewContainer');

            for (const file of files) {
                if (!file.type.startsWith('image/')) continue;

                const reader = new FileReader();
                reader.onload = function (e) {
                    const previewCard = document.createElement('div');
                    previewCard.className = 'preview-card';

                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.alt = 'Uploaded image';

                    const removeBtn = document.createElement('div');
                    removeBtn.className = 'remove-image';
                    removeBtn.innerHTML = '<i class="fas fa-times"></i>';

                    removeBtn.onclick = () => previewCard.remove();

                    previewCard.appendChild(img);
                    previewCard.appendChild(removeBtn);
                    previewContainer.appendChild(previewCard);
                };
                reader.readAsDataURL(file);
            }
        });

        // Price calculation and display
        const costPriceInput = document.getElementById('costPrice');
        const sellingPriceInput = document.getElementById('sellingPrice');
        const discountInput = document.getElementById('discountInput');
        const packingCostInput = document.getElementById('packingCost');

        const costDisplay = document.getElementById('costDisplay');
        const sellingDisplay = document.getElementById('sellingDisplay');
        const discountDisplay = document.getElementById('discountDisplay');
        const packingDisplay = document.getElementById('packingDisplay');
        const finalPrice = document.getElementById('finalPrice');

        function updatePriceDisplay() {
            const costPrice = parseFloat(costPriceInput.value) || 0;
            const sellingPrice = parseFloat(sellingPriceInput.value) || 0;
            const discount = parseFloat(discountInput.value) || 0;
            const packingCost = parseFloat(packingCostInput.value) || 0;

            const discountAmount = sellingPrice * (discount / 100);
            const finalSellingPrice = sellingPrice - discountAmount + packingCost;

            costDisplay.textContent = `Rs. ${costPrice.toFixed(2)}`;
            sellingDisplay.textContent = `Rs. ${sellingPrice.toFixed(2)}`;
            discountDisplay.textContent = `Rs. ${discountAmount.toFixed(2)}`;
            packingDisplay.textContent = `Rs. ${packingCost.toFixed(2)}`;
            finalPrice.textContent = `Rs. ${finalSellingPrice.toFixed(2)}`;
        }

        costPriceInput.addEventListener('input', updatePriceDisplay);
        sellingPriceInput.addEventListener('input', updatePriceDisplay);
        discountInput.addEventListener('input', updatePriceDisplay);
        packingCostInput.addEventListener('input', updatePriceDisplay);
    </script>
@endpush
