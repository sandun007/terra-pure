@extends('wholesaler.app')
@push('title')
    Add New Product
@endpush
@push('css')
    <style>
        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .page-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            border-radius: 50%;
        }

        .page-title h4 {
            color: white;
            font-weight: 800;
            margin-bottom: 5px;
        }

        .product-form-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .form-header {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            padding: 25px 30px;
            border-bottom: 2px solid #e2e8f0;
        }

        .form-header h5 {
            margin: 0;
            color: #1e293b;
            font-weight: 700;
        }

        .form-body {
            padding: 40px;
        }

        .form-label {
            font-weight: 600;
            color: #475569;
            margin-bottom: 8px;
        }

        .form-control, .form-select, .form-textarea {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus, .form-textarea:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-textarea {
            min-height: 120px;
            resize: vertical;
        }

        .image-upload-area {
            border: 2px dashed #cbd5e1;
            border-radius: 12px;
            padding: 40px;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .image-upload-area:hover {
            border-color: #667eea;
            background: #f8fafc;
        }

        .image-upload-area i {
            font-size: 48px;
            color: #cbd5e1;
            margin-bottom: 15px;
        }

        .image-upload-area.dragover {
            border-color: #667eea;
            background: #f0f4ff;
        }

        .image-preview {
            margin-top: 20px;
            text-align: center;
        }

        .preview-image {
            max-width: 200px;
            max-height: 200px;
            border-radius: 10px;
            border: 2px solid #e2e8f0;
        }

        .btn-submit {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 15px 40px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }

        .price-input-group {
            position: relative;
        }

        .price-input-group .form-control {
            padding-left: 40px;
        }

        .price-prefix {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
            font-weight: 600;
        }

        .form-section {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f1f5f9;
        }

        .form-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .section-title {
            font-size: 18px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title::before {
            content: '';
            width: 4px;
            height: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 4px;
        }
    </style>
@endpush

@section('content')
    <div class="page-header">
        <div class="add-item d-flex justify-content-between align-items-center">
            <div class="page-title">
                <h4 class="fw-bold">Add New Product</h4>
                <h6>Expand your product catalog</h6>
            </div>
            <div class="page-btn">
                <a href="{{ route('wholesaler.products') }}" class="btn btn-light">
                    <i class="fas fa-arrow-left me-2"></i>Back to Products
                </a>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="product-form-card">
                <div class="form-header">
                    <h5><i class="fas fa-plus-circle me-2"></i>Product Information</h5>
                </div>
                <div class="form-body">
                    <form action="{{ route('wholesaler.products.store') }}" method="POST" enctype="multipart/form-data" id="productForm">
                        @csrf

                        <div class="form-section">
                            <div class="section-title">Basic Information</div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Product Name *</label>
                                    <input type="text" name="name" class="form-control" placeholder="Enter product name" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Category</label>
                                    <select name="category" class="form-select">
                                        <option value="">Select Category</option>
                                        <option value="electronics">Electronics</option>
                                        <option value="clothing">Clothing</option>
                                        <option value="food">Food & Beverages</option>
                                        <option value="home">Home & Garden</option>
                                        <option value="beauty">Beauty & Personal Care</option>
                                        <option value="sports">Sports & Outdoors</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Product Description</label>
                                <textarea name="description" class="form-control form-textarea" placeholder="Describe your product features, specifications, etc."></textarea>
                            </div>
                        </div>

                        <div class="form-section">
                            <div class="section-title">Pricing & Inventory</div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Regular Price (RS.) *</label>
                                    <div class="price-input-group">
                                        <span class="price-prefix">RS.</span>
                                        <input type="number" name="price" class="form-control" step="0.01" min="0" placeholder="0.00" required>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Wholesale Price (RS.) *</label>
                                    <div class="price-input-group">
                                        <span class="price-prefix">RS.</span>
                                        <input type="number" name="wholesale_price" class="form-control" step="0.01" min="0" placeholder="0.00" required>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Stock Quantity *</label>
                                    <input type="number" name="stock" class="form-control" min="0" placeholder="0" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Minimum Order Quantity *</label>
                                    <input type="number" name="min_order_quantity" class="form-control" min="1" placeholder="1" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Product Status</label>
                                    <select name="status" class="form-select">
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-section">
                            <div class="section-title">Product Image</div>
                            <div class="mb-3">
                                <label class="form-label">Product Image</label>
                                <div class="image-upload-area" id="imageUploadArea">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <h5>Drag & Drop your image here</h5>
                                    <p class="text-muted">or click to browse (Max: 2MB)</p>
                                    <input type="file" name="image" id="imageInput" accept="image/*" hidden>
                                </div>
                                <div class="image-preview" id="imagePreview" style="display: none;">
                                    <img src="" alt="Preview" class="preview-image">
                                    <button type="button" class="btn btn-danger btn-sm mt-2" onclick="removeImage()">
                                        <i class="fas fa-times me-1"></i>Remove Image
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="form-section">
                            <div class="section-title">Additional Information</div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Weight (kg)</label>
                                    <input type="number" name="weight" class="form-control" step="0.01" min="0" placeholder="0.00">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Dimensions (L x W x H cm)</label>
                                    <input type="text" name="dimensions" class="form-control" placeholder="e.g., 10x5x2">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Product Features (one per line)</label>
                                <textarea name="features" class="form-control form-textarea" placeholder="Enter key features, each on a new line"></textarea>
                            </div>
                        </div>

                        <div class="text-end mt-4">
                            <button type="reset" class="btn btn-light me-3">
                                <i class="fas fa-redo me-2"></i>Reset
                            </button>
                            <button type="submit" class="btn btn-submit">
                                <i class="fas fa-save me-2"></i>Add Product
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const imageUploadArea = document.getElementById('imageUploadArea');
            const imageInput = document.getElementById('imageInput');
            const imagePreview = document.getElementById('imagePreview');
            const previewImage = imagePreview.querySelector('img');

            // Click to upload
            imageUploadArea.addEventListener('click', function() {
                imageInput.click();
            });

            // Drag and drop functionality
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                imageUploadArea.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            ['dragenter', 'dragover'].forEach(eventName => {
                imageUploadArea.addEventListener(eventName, highlight, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                imageUploadArea.addEventListener(eventName, unhighlight, false);
            });

            function highlight() {
                imageUploadArea.classList.add('dragover');
            }

            function unhighlight() {
                imageUploadArea.classList.remove('dragover');
            }

            imageUploadArea.addEventListener('drop', handleDrop, false);

            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;
                imageInput.files = files;
                handleFiles(files);
            }

            // File input change
            imageInput.addEventListener('change', function() {
                handleFiles(this.files);
            });

            function handleFiles(files) {
                if (files.length > 0) {
                    const file = files[0];
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            previewImage.src = e.target.result;
                            imagePreview.style.display = 'block';
                            imageUploadArea.style.display = 'none';
                        }
                        reader.readAsDataURL(file);
                    } else {
                        alert('Please select a valid image file.');
                    }
                }
            }
        });

        function removeImage() {
            const imageInput = document.getElementById('imageInput');
            const imagePreview = document.getElementById('imagePreview');
            const imageUploadArea = document.getElementById('imageUploadArea');

            imageInput.value = '';
            imagePreview.style.display = 'none';
            imageUploadArea.style.display = 'block';
        }

        // Form validation
        document.getElementById('productForm').addEventListener('submit', function(e) {
            const wholesalePrice = document.querySelector('input[name="wholesale_price"]').value;
            const regularPrice = document.querySelector('input[name="price"]').value;

            if (parseFloat(wholesalePrice) >= parseFloat(regularPrice)) {
                e.preventDefault();
                alert('Wholesale price must be less than regular price.');
                return false;
            }
        });
    </script>
@endpush
