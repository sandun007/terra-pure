@extends('wholesaler.layouts.app')
@push('title')
    Edit Category - {{ $category->name }}
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
    </style>
@endpush

@section('content')
    <div class="page-header">
        <div class="page-title">
            <h3 class="fw-bold d-flex align-items-center">
                <span class="material-icons-outlined me-2" style="font-size: 2.5rem;">edit</span>
                Edit Category
            </h3>
            <p class="mb-0 opacity-90 d-flex align-items-center">
                <span class="material-icons-outlined me-2" style="font-size: 1.2rem;">update</span>
                Update category information and image
            </p>
        </div>
        <div class="page-actions">
            <a href="{{ route('wholesaler.categories.index') }}" class="btn btn-outline-light d-flex align-items-center">
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

    <form action="{{ route('wholesaler.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data" id="categoryForm">
        @csrf
        @method('PUT')

        <div class="form-card">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-4">
                        <label class="form-label d-flex align-items-center">
                            <span class="material-icons-outlined">qr_code</span>
                            Category Code
                        </label>
                        <input type="text" class="form-control" readonly value="{{ $category->code }}">
                        <div class="form-text d-flex align-items-center mt-2">
                            <span class="material-icons-outlined me-1" style="font-size: 1rem;">lock</span>
                            Category code cannot be changed
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label d-flex align-items-center">
                            <span class="material-icons-outlined">category</span>
                            <span class="required">Category Name</span>
                        </label>
                        <input type="text" name="name" class="form-control" required
                               placeholder="Category Name" value="{{ old('name', $category->name) }}">
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
                            <option value="active" {{ old('status', $category->status) == 'active' ? 'selected' : '' }}>
                                Active
                            </option>
                            <option value="inactive" {{ old('status', $category->status) == 'inactive' ? 'selected' : '' }}>
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

                <!-- Current Image -->
                @if($category->image)
                    <div class="col-md-6">
                        <div class="current-image-container">
                            <label class="form-label d-flex align-items-center justify-content-center">
                                <span class="material-icons-outlined">image</span>
                                Current Category Image
                            </label>
                            <img src="{{ asset('storage/' . $category->image) }}"
                                 alt="{{ $category->name }}"
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
                    </div>
                @endif
            </div>

            <!-- New Image Upload -->
            <div class="mb-4">
                <label class="form-label d-flex align-items-center">
                    <span class="material-icons-outlined">cloud_upload</span>
                    {{ $category->image ? 'Change Category Image' : 'Upload Category Image' }}
                </label>

                <div class="upload-area" id="uploadArea">
                    <div class="upload-icon">
                        <span class="material-icons-outlined">cloud_upload</span>
                    </div>
                    <h5>Click to Upload New Image</h5>
                    <p class="text-muted mb-0">JPG, PNG, GIF, WEBP - Max 2MB</p>
                    <input type="file" name="image" id="categoryImage" accept="image/*" class="d-none">
                </div>

                <div id="imagePreviewContainer" class="preview-container">
                    <!-- New image preview will appear here -->
                </div>

                <div class="form-text d-flex align-items-center mt-2">
                    <span class="material-icons-outlined me-1" style="font-size: 1rem;">info</span>
                    Leave empty to keep current image
                </div>

                @error('image')
                <div class="text-danger small mt-2 d-flex align-items-center">
                    <span class="material-icons-outlined me-1" style="font-size: 1rem;">error</span>
                    {{ $message }}
                </div>
                @enderror
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="d-flex align-items-center justify-content-end gap-3">
                    <a href="{{ route('wholesaler.categories.index') }}" class="btn btn-danger d-flex align-items-center">
                        <span class="material-icons-outlined me-2">cancel</span>
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-warning d-flex align-items-center">
                        <span class="material-icons-outlined me-2">save</span>
                        Update Category
                    </button>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ========== Image Upload ==========
            const uploadArea = document.getElementById('uploadArea');
            const imageInput = document.getElementById('categoryImage');
            const previewContainer = document.getElementById('imagePreviewContainer');
            const currentImage = document.getElementById('currentImage');
            const removeCurrentImageInput = document.getElementById('removeCurrentImageInput');

            // Click to upload
            if (uploadArea && imageInput) {
                uploadArea.addEventListener('click', function() {
                    imageInput.click();
                });
            }

            // Image input change handler
            if (imageInput && previewContainer) {
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
                previewContainer.innerHTML = '';

                // Hide current image if exists
                if (currentImage) {
                    currentImage.style.display = 'none';
                    if (removeCurrentImageInput) {
                        removeCurrentImageInput.value = "1";
                    }
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    const previewCard = document.createElement('div');
                    previewCard.className = 'image-preview-card';

                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.alt = 'New category image preview';

                    const removeBtn = document.createElement('div');
                    removeBtn.className = 'remove-image-btn';
                    removeBtn.innerHTML = '<span class="material-icons-outlined">close</span>';
                    removeBtn.onclick = () => {
                        previewCard.remove();
                        imageInput.value = '';
                        // Show current image again
                        if (currentImage) {
                            currentImage.style.display = 'block';
                            if (removeCurrentImageInput) {
                                removeCurrentImageInput.value = "0";
                            }
                        }
                    };

                    previewCard.appendChild(img);
                    previewCard.appendChild(removeBtn);
                    previewContainer.appendChild(previewCard);

                    showAlert('New image selected! Click Update to save changes.', 'success');
                };
                reader.readAsDataURL(file);
            }

            // ========== Remove Current Image ==========
            window.removeCurrentImage = function() {
                if (confirm('Are you sure you want to remove the current image?')) {
                    if (currentImage) {
                        currentImage.style.display = 'none';
                    }
                    if (removeCurrentImageInput) {
                        removeCurrentImageInput.value = "1";
                    }
                    showAlert('Current image marked for removal. Click Update to save changes.', 'warning');
                }
            };

            // ========== Alert Function ==========
            function showAlert(message, type) {
                // Remove existing alerts
                const existingAlert = document.querySelector('.custom-alert');
                if (existingAlert) existingAlert.remove();

                const alert = document.createElement('div');
                alert.className = `alert alert-${type === 'error' ? 'danger' : type === 'warning' ? 'warning' : 'success'} custom-alert`;
                alert.innerHTML = `
                    <div class="d-flex align-items-center">
                        <span class="material-icons-outlined me-2">${type === 'error' ? 'error' : type === 'warning' ? 'warning' : 'check_circle'}</span>
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
