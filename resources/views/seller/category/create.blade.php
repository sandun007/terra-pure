@extends('seller.layout.app')
@push('title')
    Create Category
@endpush
@push('css')
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons+Outlined" rel="stylesheet">
    <style>
        .page-header {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .upload-area {
            border: 3px dashed #10b981;
            border-radius: 12px;
            padding: 3rem 2rem;
            text-align: center;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .upload-area:hover {
            border-color: #3b82f6;
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.2);
        }

        .upload-area.drag-over {
            border-color: #10b981;
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        }

        .upload-icon {
            font-size: 4rem;
            color: #10b981;
            margin-bottom: 1rem;
            opacity: 0.7;
        }

        .preview-container {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 20px;
        }

        .image-preview-card {
            position: relative;
            width: 150px;
            height: 150px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }

        .image-preview-card:hover {
            transform: translateY(-5px);
            border-color: #10b981;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .image-preview-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .remove-image-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(255, 255, 255, 0.95);
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
            z-index: 10;
        }

        .remove-image-btn:hover {
            background: #ef4444;
            color: white;
            transform: scale(1.1);
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

        .section-header {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #e2e8f0;
        }

        .form-label {
            font-weight: 600;
            color: #334155;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
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
            font-size: 1rem;
        }

        .form-control:focus, .form-select:focus {
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
            outline: none;
        }

        .btn {
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border: none;
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #64748b 0%, #475569 100%);
            border: none;
            color: white;
        }

        .alert {
            border-radius: 10px;
            border: none;
            padding: 1rem 1.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
    </style>
@endpush

@section('content')
    <div class="page-header">
        <div class="page-title">
            <h3 class="fw-bold d-flex align-items-center">
                <span class="material-icons-outlined me-2" style="font-size: 2.5rem;">add_circle</span>
                Create New Category
            </h3>
            <p class="mb-0 opacity-90 d-flex align-items-center">
                <span class="material-icons-outlined me-2" style="font-size: 1.2rem;">category</span>
                Add a new product category to your catalog
            </p>
        </div>
        <div class="page-actions">
            <a href="{{ route('seller.category.index') }}" class="btn btn-outline-light d-flex align-items-center">
                <span class="material-icons-outlined me-2">arrow_back</span>
                Back to Categories
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

    <form action="{{ route('seller.category.store') }}" method="POST" enctype="multipart/form-data" id="categoryForm">
        @csrf
        <div class="form-card">
            <div class="row">
                <!-- Left Column -->
                <div class="col-md-6">
                    <div class="mb-4">
                        <label class="form-label d-flex align-items-center">
                            <span class="material-icons-outlined">qr_code</span>
                            <span class="required">Category Code</span>
                        </label>
                        <input type="text"
                               class="form-control"
                               value="{{ $code }}"
                               readonly
                               style="background-color: #f8f9fa;">
                        <input type="hidden" name="code" value="{{ $code }}">
                        <div class="form-text d-flex align-items-center mt-2">
                            <span class="material-icons-outlined me-1" style="font-size: 1rem;">auto_awesome</span>
                            Automatically generated category code
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label d-flex align-items-center">
                            <span class="material-icons-outlined">category</span>
                            <span class="required">Category Name</span>
                        </label>
                        <input type="text"
                               name="name"
                               class="form-control"
                               value="{{ old('name') }}"
                               placeholder="Enter category name"
                               required>
                        @error('name')
                        <div class="text-danger small mt-2 d-flex align-items-center">
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
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>
                                Active
                            </option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>
                                Inactive
                            </option>
                        </select>
                        @error('status')
                        <div class="text-danger small mt-2 d-flex align-items-center">
                            <span class="material-icons-outlined me-1" style="font-size: 1rem;">error</span>
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-md-6">
                    <div class="mb-4">
                        <label class="form-label d-flex align-items-center">
                            <span class="material-icons-outlined">image</span>
                            <span class="required">Category Image</span>
                        </label>

                        <div class="upload-area" id="uploadArea">
                            <div class="upload-icon">
                                <span class="material-icons-outlined">cloud_upload</span>
                            </div>
                            <h5>Click or Drag & Drop to Upload</h5>
                            <p class="text-muted mb-0">JPG, PNG, GIF, WEBP - Max 2MB</p>
                            <input type="file" name="image" id="categoryImage" accept="image/*" class="d-none" required>
                        </div>

                        <div id="imagePreviewContainer" class="preview-container">
                            <!-- Preview will appear here -->
                        </div>

                        @error('image')
                        <div class="text-danger small mt-2 d-flex align-items-center">
                            <span class="material-icons-outlined me-1" style="font-size: 1rem;">error</span>
                            {{ $message }}
                        </div>
                        @enderror

                        <div class="form-text d-flex align-items-center mt-2">
                            <span class="material-icons-outlined me-1" style="font-size: 1rem;">info</span>
                            Allowed formats: JPG, PNG, GIF, WebP. Max size: 2MB
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('seller.category.index') }}"
                       class="btn btn-secondary d-flex align-items-center">
                        <span class="material-icons-outlined me-2">cancel</span>
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary d-flex align-items-center" id="submitBtn">
                        <span class="material-icons-outlined me-2">save</span>
                        Create Category
                    </button>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ========== Image Upload with Drag & Drop ==========
            const uploadArea = document.getElementById('uploadArea');
            const imageInput = document.getElementById('categoryImage');
            const imagePreview = document.getElementById('imagePreviewContainer');
            const form = document.getElementById('categoryForm');
            const submitBtn = document.getElementById('submitBtn');

            // Click to upload
            if (uploadArea && imageInput) {
                uploadArea.addEventListener('click', function() {
                    imageInput.click();
                });
            }

            // Drag and drop functionality
            if (uploadArea) {
                uploadArea.addEventListener('dragover', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    this.classList.add('drag-over');
                });

                uploadArea.addEventListener('dragleave', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    this.classList.remove('drag-over');
                });

                uploadArea.addEventListener('drop', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    this.classList.remove('drag-over');

                    if (e.dataTransfer.files.length) {
                        imageInput.files = e.dataTransfer.files;
                        handleImageUpload(e.dataTransfer.files[0]);
                    }
                });
            }

            // Image input change handler
            if (imageInput && imagePreview) {
                imageInput.addEventListener('change', function(event) {
                    const file = event.target.files[0];
                    if (file) handleImageUpload(file);
                });
            }

            function handleImageUpload(file) {
                if (!file.type || !file.type.startsWith('image/')) {
                    showAlert('Please upload only image files (JPG, PNG, GIF, WEBP).', 'error');
                    return;
                }

                if (file.size > 2 * 1024 * 1024) {
                    showAlert('Image size should be less than 2MB.', 'error');
                    return;
                }

                // Clear previous preview
                imagePreview.innerHTML = '';

                const reader = new FileReader();
                reader.onload = function(e) {
                    const previewCard = document.createElement('div');
                    previewCard.className = 'image-preview-card';

                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.alt = 'Category image preview';

                    const removeBtn = document.createElement('div');
                    removeBtn.className = 'remove-image-btn';
                    removeBtn.innerHTML = '<span class="material-icons-outlined">close</span>';
                    removeBtn.onclick = () => {
                        previewCard.remove();
                        imageInput.value = '';
                    };

                    previewCard.appendChild(img);
                    previewCard.appendChild(removeBtn);
                    imagePreview.appendChild(previewCard);

                    showAlert('Image uploaded successfully!', 'success');
                };
                reader.readAsDataURL(file);
            }

            // ========== Form Validation ==========
            if (form) {
                form.addEventListener('submit', function(e) {
                    // Check if image is uploaded
                    const image = document.querySelector('#imagePreviewContainer .image-preview-card');
                    if (!image) {
                        e.preventDefault();
                        showAlert('Please upload a category image.', 'error');
                        return false;
                    }

                    // Validate file size if needed
                    if (imageInput.files.length > 0) {
                        const file = imageInput.files[0];
                        if (file.size > 2 * 1024 * 1024) {
                            e.preventDefault();
                            showAlert('Image size should be less than 2MB.', 'error');
                            return false;
                        }
                    }

                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<span class="material-icons-outlined me-2">hourglass_empty</span>Creating...';
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
        });
    </script>
@endpush
