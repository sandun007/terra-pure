@extends('seller.layout.app')
@push('title')
    Edit Brand - {{ $brand->name }}
@endpush
@push('css')
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons+Outlined" rel="stylesheet">
    <style>
        .page-header {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .current-image-container {
            text-align: center;
            margin-bottom: 2rem;
            padding: 1.5rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        .current-image {
            width: 200px;
            height: 200px;
            object-fit: contain;
            border-radius: 10px;
            border: 3px solid #e2e8f0;
            padding: 10px;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        }

        .upload-area {
            border: 3px dashed var(--main-theme-color);
            border-radius: 12px;
            padding: 3rem 2rem;
            text-align: center;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            margin-bottom: 1.5rem;
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
            color: var(--main-theme-color);
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
            border-color: var(--main-theme-color);
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

        .section-header i {
            font-size: 1.5rem;
            margin-right: 12px;
            color: var(--main-theme-color);
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
            border-color: var(--main-theme-color);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
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

        .btn-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            border: none;
            color: white;
        }

        .btn-warning:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(245, 158, 11, 0.4);
        }

        .btn-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            border: none;
            color: white;
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
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
    <div class="page-header">
        <div class="page-title">
            <h3 class="fw-bold d-flex align-items-center">
                <span class="material-icons-outlined me-2" style="font-size: 2.5rem;">edit</span>
                Edit Brand
            </h3>
            <p class="mb-0 opacity-90 d-flex align-items-center">
                <span class="material-icons-outlined me-2" style="font-size: 1.2rem;">inventory</span>
                Update brand information and image
            </p>
        </div>
        <div class="page-actions">
            <a href="{{ route('seller.brands.index') }}" class="btn btn-outline-light d-flex align-items-center">
                <span class="material-icons-outlined me-2">arrow_back</span>
                Back to Brands
            </a>
        </div>
    </div>

    <form action="{{ route('seller.brands.update', $brand->id) }}" method="post" enctype="multipart/form-data" id="brandForm">
        @csrf
        @method('PUT')

        <div class="form-card">
            <div class="section-header">
                <span class="material-icons-outlined">info</span>
                <h5 class="mb-0">Brand Information</h5>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-4">
                        <label class="form-label d-flex align-items-center">
                            <span class="material-icons-outlined">qr_code</span>
                            Brand Code
                        </label>
                        <input type="text" class="form-control" readonly value="{{ $brand->code }}">
                        <div class="form-text">
                            <span class="material-icons-outlined" style="font-size: 1rem;">lock</span>
                            Brand code cannot be changed
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-4">
                        <label class="form-label d-flex align-items-center">
                            <span class="material-icons-outlined">branding_watermark</span>
                            <span class="required">Brand Name</span>
                        </label>
                        <input type="text" name="name" class="form-control" required
                               placeholder="Enter brand name" value="{{ old('name', $brand->name) }}">
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
                            <span class="material-icons-outlined">toggle_on</span>
                            <span class="required">Status</span>
                        </label>
                        <select name="status" class="form-select" required>
                            <option value="active" {{ old('status', $brand->status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $brand->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                        <div class="text-danger mt-2 d-flex align-items-center">
                            <span class="material-icons-outlined me-1" style="font-size: 1rem;">error</span>
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Current Image -->
            @if($brand->image_url && $brand->image_url != asset('images/default-brand.png'))
                <div class="current-image-container" id="currentImageContainer">
                    <label class="form-label d-flex align-items-center justify-content-center">
                        <span class="material-icons-outlined">image</span>
                        Current Brand Image
                    </label>
                    <img src="{{ $brand->image_url }}"
                         alt="{{ $brand->name }}"
                         class="current-image"
                         id="currentImage">
                    <div class="mt-3">
                        <button type="button" class="btn btn-sm btn-outline-danger d-flex align-items-center mx-auto" onclick="removeCurrentImage()">
                            <span class="material-icons-outlined me-1">delete</span>
                            Remove Current Image
                        </button>
                        <input type="hidden" name="remove_current_image" id="removeCurrentImageInput" value="0">
                    </div>
                </div>
            @endif

            <!-- New Image Upload -->
            <div class="mb-4">
                <label class="form-label d-flex align-items-center">
                    <span class="material-icons-outlined">cloud_upload</span>
                    {{ $brand->image_url && $brand->image_url != asset('images/default-brand.png') ? 'Change Brand Image' : 'Upload Brand Image' }}
                </label>

                <div class="upload-area" id="uploadArea">
                    <div class="upload-icon">
                        <span class="material-icons-outlined">cloud_upload</span>
                    </div>
                    <h5>Click or Drag & Drop to Upload</h5>
                    <p class="text-muted mb-0">JPG, PNG, GIF, WEBP - Max 2MB</p>
                    <input type="file" name="image" id="imageInput" accept="image/*" class="d-none">
                </div>

                <div id="imagePreviewContainer" class="preview-container">
                    <!-- New image preview will appear here -->
                </div>

                <div class="form-text">
                    <span class="material-icons-outlined" style="font-size: 1rem;">info</span>
                    Leave empty to keep current image
                </div>

                @error('image')
                <div class="text-danger mt-2 d-flex align-items-center">
                    <span class="material-icons-outlined me-1" style="font-size: 1rem;">error</span>
                    {{ $message }}
                </div>
                @enderror
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="d-flex align-items-center justify-content-end gap-3">
                    <a href="{{ route('seller.brands.index') }}" class="btn btn-danger d-flex align-items-center">
                        <span class="material-icons-outlined me-2">cancel</span>
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-warning d-flex align-items-center" id="submitBtn">
                        <span class="material-icons-outlined me-2">save</span>
                        Update Brand
                    </button>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const uploadArea = document.getElementById('uploadArea');
            const imageInput = document.getElementById('imageInput');
            const previewContainer = document.getElementById('imagePreviewContainer');
            const currentImage = document.getElementById('currentImage');
            const currentImageContainer = document.getElementById('currentImageContainer');
            const removeCurrentImageInput = document.getElementById('removeCurrentImageInput');
            const form = document.getElementById('brandForm');

            // Click to upload
            if (uploadArea && imageInput) {
                uploadArea.addEventListener('click', function () {
                    imageInput.click();
                });
            }

            // Drag and drop functionality
            if (uploadArea) {
                uploadArea.addEventListener('dragover', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    this.classList.add('drag-over');
                });

                uploadArea.addEventListener('dragleave', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    this.classList.remove('drag-over');
                });

                uploadArea.addEventListener('drop', function (e) {
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
            if (imageInput && previewContainer) {
                imageInput.addEventListener('change', function (event) {
                    const file = event.target.files[0];
                    if (file) handleImageUpload(file);
                });
            }

            function handleImageUpload(file) {
                if (!file.type || !file.type.startsWith('image/')) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid File',
                        text: 'Please upload only image files (JPG, PNG, GIF, WEBP).',
                        confirmButtonColor: '#ef4444'
                    });
                    return;
                }

                if (file.size > 2 * 1024 * 1024) {
                    Swal.fire({
                        icon: 'error',
                        title: 'File Too Large',
                        text: 'Image size should be less than 2MB.',
                        confirmButtonColor: '#ef4444'
                    });
                    return;
                }

                previewContainer.innerHTML = '';

                // Hide current image if exists
                if (currentImageContainer) {
                    currentImageContainer.style.display = 'none';
                    if (removeCurrentImageInput) {
                        removeCurrentImageInput.value = "1";
                    }
                }

                const reader = new FileReader();
                reader.onload = function (e) {
                    const previewCard = document.createElement('div');
                    previewCard.className = 'image-preview-card';

                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.alt = 'New brand image preview';

                    const removeBtn = document.createElement('div');
                    removeBtn.className = 'remove-image-btn';
                    removeBtn.innerHTML = '<span class="material-icons-outlined">close</span>';
                    removeBtn.onclick = () => {
                        previewCard.remove();
                        imageInput.value = '';
                        // Show current image again
                        if (currentImageContainer) {
                            currentImageContainer.style.display = 'block';
                            if (removeCurrentImageInput) {
                                removeCurrentImageInput.value = "0";
                            }
                        }
                    };

                    previewCard.appendChild(img);
                    previewCard.appendChild(removeBtn);
                    previewContainer.appendChild(previewCard);

                    Swal.fire({
                        icon: 'success',
                        title: 'Image Selected!',
                        text: 'New image selected successfully',
                        showConfirmButton: false,
                        timer: 1500,
                        toast: true,
                        position: 'top-end'
                    });
                };
                reader.readAsDataURL(file);
            }

            // Remove Current Image
            window.removeCurrentImage = function() {
                Swal.fire({
                    title: 'Remove Current Image?',
                    text: "This will mark the current image for removal.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, remove it',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        if (currentImageContainer) {
                            currentImageContainer.style.display = 'none';
                        }
                        if (removeCurrentImageInput) {
                            removeCurrentImageInput.value = "1";
                        }
                        Swal.fire({
                            icon: 'info',
                            title: 'Image Marked for Removal',
                            text: 'Click Update to save changes',
                            showConfirmButton: false,
                            timer: 2000,
                            toast: true,
                            position: 'top-end'
                        });
                    }
                });
            };

            // Form submission
            if (form) {
                form.addEventListener('submit', function (e) {
                    e.preventDefault(); // Prevent default submission

                    const brandName = document.querySelector('input[name="name"]').value;

                    // Show confirmation dialog
                    Swal.fire({
                        title: 'Update Brand?',
                        html: `Are you sure you want to update the brand <strong>"${brandName}"</strong>?`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#f59e0b',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: '<span class="material-icons-outlined" style="font-size: 1rem; vertical-align: middle;">save</span> Yes, Update Brand',
                        cancelButtonText: '<span class="material-icons-outlined" style="font-size: 1rem; vertical-align: middle;">cancel</span> Cancel',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const submitBtn = document.getElementById('submitBtn');
                            submitBtn.disabled = true;
                            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Updating...';

                            // Submit the form
                            form.submit();
                        }
                    });

                    return false;
                });
            }

            // Show validation errors
            @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                html: '<ul style="text-align: left;">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
                confirmButtonColor: '#ef4444'
            });
            @endif
        });
    </script>
@endpush
