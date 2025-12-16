@extends('wholesaler.layouts.app')
@push('title')
    Create Brand
@endpush
@push('css')
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons+Outlined" rel="stylesheet">
    <style>
        .page-header {
            background: linear-gradient(135deg, var(--main-theme-color) 0%, #2c5282 100%);
            color: white;
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
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

        .remove-image-btn i {
            font-size: 1.2rem;
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

        .btn-dark {
            background: linear-gradient(135deg, var(--main-theme-color) 0%, #2c5282 100%);
            border: none;
            color: white;
        }

        .btn-dark:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
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
                <span class="material-icons-outlined me-2" style="font-size: 2.5rem;">add_circle</span>
                Create New Brand
            </h3>
            <p class="mb-0 opacity-90 d-flex align-items-center">
                <span class="material-icons-outlined me-2" style="font-size: 1.2rem;">info</span>
                Add a new brand to your product catalog
            </p>
        </div>
        <div class="page-actions">
            <a href="{{ route('wholesaler.brands.index') }}" class="btn btn-outline-light d-flex align-items-center">
                <span class="material-icons-outlined me-2">arrow_back</span>
                Back to Brands
            </a>
        </div>
    </div>

    <form action="{{ route('wholesaler.brands.store') }}" method="post" enctype="multipart/form-data" id="brandForm">
        @csrf
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
                            <span class="required">Brand Code</span>
                        </label>
                        <input type="text" name="code" class="form-control" required readonly
                               value="{{ $code }}">
                        <div class="form-text">
                            <span class="material-icons-outlined" style="font-size: 1rem;">auto_awesome</span>
                            Auto-generated brand code
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
                               placeholder="Enter brand name" value="{{ old('name') }}">
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
                </div>
                <div class="col-md-6">
                    <div class="mb-4">
                        <label class="form-label d-flex align-items-center">
                            <span class="material-icons-outlined">image</span>
                            <span class="required">Brand Image</span>
                        </label>

                        <div class="upload-area" id="uploadArea">
                            <div class="upload-icon">
                                <span class="material-icons-outlined">cloud_upload</span>
                            </div>
                            <h5>Click or Drag & Drop to Upload</h5>
                            <p class="text-muted mb-0">JPG, PNG, GIF, WEBP - Max 2MB</p>
                            <input type="file" name="image" id="imageInput" accept="image/*" class="d-none" required>
                        </div>

                        <div id="imagePreviewContainer" class="preview-container">
                            <!-- Preview will appear here -->
                        </div>

                        @error('image')
                        <div class="text-danger mt-2 d-flex align-items-center">
                            <span class="material-icons-outlined me-1" style="font-size: 1rem;">error</span>
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="d-flex align-items-center justify-content-end gap-3">
                    <a href="{{ route('wholesaler.brands.index') }}" class="btn btn-danger d-flex align-items-center">
                        <span class="material-icons-outlined me-2">cancel</span>
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-dark d-flex align-items-center" id="submitBtn">
                        <span class="material-icons-outlined me-2">save</span>
                        Create Brand
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

                const reader = new FileReader();
                reader.onload = function (e) {
                    const previewCard = document.createElement('div');
                    previewCard.className = 'image-preview-card';

                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.alt = 'Brand image preview';

                    const removeBtn = document.createElement('div');
                    removeBtn.className = 'remove-image-btn';
                    removeBtn.innerHTML = '<span class="material-icons-outlined">close</span>';
                    removeBtn.onclick = () => {
                        previewCard.remove();
                        imageInput.value = '';
                    };

                    previewCard.appendChild(img);
                    previewCard.appendChild(removeBtn);
                    previewContainer.appendChild(previewCard);

                    Swal.fire({
                        icon: 'success',
                        title: 'Image Uploaded!',
                        text: 'Image selected successfully',
                        showConfirmButton: false,
                        timer: 1500,
                        toast: true,
                        position: 'top-end'
                    });
                };
                reader.readAsDataURL(file);
            }

            // Form Validation
            if (form) {
                form.addEventListener('submit', function (e) {
                    e.preventDefault(); // Prevent default submission

                    // Check if image is uploaded
                    const image = document.querySelector('#imagePreviewContainer .image-preview-card');
                    if (!image) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Missing Image',
                            text: 'Please upload a brand image.',
                            confirmButtonColor: '#ef4444'
                        });
                        return false;
                    }

                    // Validate file size
                    if (imageInput.files.length > 0) {
                        const file = imageInput.files[0];
                        if (file.size > 2 * 1024 * 1024) {
                            Swal.fire({
                                icon: 'error',
                                title: 'File Too Large',
                                text: 'Image size should be less than 2MB.',
                                confirmButtonColor: '#ef4444'
                            });
                            return false;
                        }
                    }

                    // Get brand name for confirmation
                    const brandName = document.querySelector('input[name="name"]').value;

                    // Show confirmation dialog
                    Swal.fire({
                        title: 'Create Brand?',
                        html: `Are you sure you want to create the brand <strong>"${brandName}"</strong>?`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#667eea',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: '<span class="material-icons-outlined" style="font-size: 1rem; vertical-align: middle;">save</span> Yes, Create Brand',
                        cancelButtonText: '<span class="material-icons-outlined" style="font-size: 1rem; vertical-align: middle;">cancel</span> Cancel',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Show loading state
                            const submitBtn = document.getElementById('submitBtn');
                            submitBtn.disabled = true;
                            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Creating...';

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
