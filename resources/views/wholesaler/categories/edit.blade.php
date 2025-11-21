@extends('wholesaler.app')
@push('title')
    Edit Category - {{ $category->name }}
@endpush

@section('content')
    <div class="dashboard-container">
        <div class="dashboard-header">
            <div class="header-content">
                <div class="header-title">
                    <h1>📁 Edit Category</h1>
                    <p>Update category information</p>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="dashboard-section">
                    <div class="section-header">
                        <h3 class="section-title">Edit Category: {{ $category->name }}</h3>
                        <a href="{{ route('wholesaler.categories') }}" class="view-all-link">
                            <i class="fas fa-arrow-left me-1"></i> Back to Categories
                        </a>
                    </div>

                    <form action="{{ route('wholesaler.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('POST')

                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Category Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           id="name" name="name" value="{{ old('name', $category->name) }}"
                                           placeholder="Enter category name" required>
                                    @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                    <select class="form-control @error('status') is-invalid @enderror"
                                            id="status" name="status" required>
                                        <option value="active" {{ old('status', $category->status) == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status', $category->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Category Image</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror"
                                   id="image" name="image" accept="image/*">
                            @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Current Image</label>
                            <div>
                                @if($category->img)
                                    <img src="{{ asset('storage/' . $category->img) }}" alt="{{ $category->name }}"
                                         class="img-thumbnail" style="max-height: 150px;">
                                    <div class="form-text mt-1">
                                        <small>Leave empty to keep current image</small>
                                    </div>
                                @else
                                    <div class="text-muted">
                                        <i class="fas fa-folder fa-2x"></i>
                                        <div class="mt-1">No image uploaded</div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="image-preview mb-3" id="imagePreview" style="display: none;">
                                <label class="form-label">New Image Preview</label>
                                <img id="previewImage" class="img-thumbnail" style="max-height: 200px;">
                            </div>
                        </div>

                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('wholesaler.categories') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Update Category
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
        // Image preview functionality
        document.getElementById('image').addEventListener('change', function(e) {
            const preview = document.getElementById('imagePreview');
            const previewImage = document.getElementById('previewImage');
            const file = e.target.files[0];

            if (file) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    preview.style.display = 'block';
                }

                reader.readAsDataURL(file);
            } else {
                preview.style.display = 'none';
            }
        });
    </script>
@endpush
