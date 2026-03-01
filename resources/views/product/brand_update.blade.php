@extends('admin.app')

@push('title')
    Update Brand
@endpush

@push('css')
<style>
    .form-card {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        padding: 25px;
        margin-bottom: 25px;
        border: 1px solid #e9ecef;
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

    .existing-image {
        position: relative;
        width: 150px;
        height: 150px;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    }

    .existing-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .upload-area {
        border: 2px dashed #dee2e6;
        border-radius: 10px;
        padding: 30px;
        text-align: center;
        cursor: pointer;
        background: #f8f9fa;
        transition: 0.3s;
    }

    .upload-area:hover {
        border-color: var(--main-theme-color);
        background: rgba(var(--main-theme-color-rgb), 0.05);
    }

    .preview-card {
        position: relative;
        width: 120px;
        height: 120px;
        border-radius: 8px;
        overflow: hidden;
        margin-top: 15px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.1);
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
        background: white;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }
</style>
@endpush

@section('content')

<div class="page-header">
    <div class="page-title">
        <h3 class="fw-bold">Update Brand</h3>
        <p class="text-muted">Edit brand details and image</p>
    </div>
    <div>
        <a href="{{ route('brand.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Brands
        </a>
    </div>
</div>

<form action="{{ route('brand.update', $brand->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="row">
        <div class="col-lg-8">

            <div class="form-card">
                <div class="section-header">
                    <i class="fas fa-info-circle"></i>
                    <h5 class="mb-0">Brand Information</h5>
                </div>

                <div class="mb-3">
                    <label class="form-label">Brand Code</label>
                    <input type="text" class="form-control" value="{{ $brand->code }}" readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label">Brand Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" 
                           value="{{ $brand->name }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="1" {{ $brand->status == 1 ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ $brand->status == 0 ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>

            <div class="form-card">
                <div class="section-header">
                    <i class="fas fa-image"></i>
                    <h5 class="mb-0">Brand Image</h5>
                </div>

                <div class="mb-3">
                    <label class="form-label">Current Image</label>
                    <div class="existing-image">
                        <img src="{{ asset('storage/' . $brand->image) }}" 
                             alt="{{ $brand->name }}">
                    </div>
                </div>

                <div class="upload-area" onclick="document.getElementById('imageInput').click();">
                    <i class="fas fa-cloud-upload-alt fa-2x mb-2"></i>
                    <p>Click to upload new image</p>
                    <input type="file" name="img" id="imageInput" class="d-none">
                </div>

                <div id="imagePreviewContainer"></div>

            </div>
        </div>

        <div class="col-lg-4">
            <div class="form-card">
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save me-2"></i>Update Brand
                    </button>

                    <a href="{{ route('brand.index') }}" 
                       class="btn btn-outline-secondary">
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
document.getElementById('imageInput').addEventListener('change', function (event) {
    const file = event.target.files[0];
    const previewContainer = document.getElementById('imagePreviewContainer');
    previewContainer.innerHTML = '';

    if (!file || !file.type.startsWith('image/')) return;

    const reader = new FileReader();
    reader.onload = function (e) {
        const previewCard = document.createElement('div');
        previewCard.className = 'preview-card';

        const img = document.createElement('img');
        img.src = e.target.result;

        const removeBtn = document.createElement('div');
        removeBtn.className = 'remove-image';
        removeBtn.innerHTML = '<i class="fas fa-times"></i>';
        removeBtn.onclick = () => {
            previewCard.remove();
            document.getElementById('imageInput').value = '';
        };

        previewCard.appendChild(img);
        previewCard.appendChild(removeBtn);
        previewContainer.appendChild(previewCard);
    };
    reader.readAsDataURL(file);
});
</script>
@endpush