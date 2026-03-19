@extends('admin.app')
@push('title')
    Category Edit
@endpush

@section('content')
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4 class="fw-bold">Edit Category</h4>
                <h6>Update category {{ $category->name }}</h6>
            </div>
        </div>
        <div class="page-btn mt-0">
            <a href="{{ route('category.index') }}" class="btn btn-secondary">Back to Category List</a>
        </div>
    </div>

    <form action="{{ route('category.update', ['id' => $category->id, 'type' => request('type')]) }}" method="post" enctype="multipart/form-data"
        class="add-product-form">
        @csrf
        <div class="add-product">
            <div class="accordions-items-seperate" id="accordionSpacingExample">
                <div class="accordion-item border mb-4">
                    <h2 class="accordion-header " id="headingSpacingOne">
                        <div style="background-color: var(--main-theme-color) !important;"
                            class=" accordion-button bg-white" data-bs-toggle="collapse" data-bs-target="#SpacingOne"
                            aria-expanded="true" aria-controls="SpacingOne">
                            <div class="d-flex align-items-center justify-content-between flex-fill">
                                <h5 class="d-flex align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="feather feather-info text-primary me-2">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <line x1="12" y1="16" x2="12" y2="12"></line>
                                        <line x1="12" y1="8" x2="12.01" y2="8"></line>
                                    </svg>
                                    <span>Category Information</span>
                                </h5>
                            </div>
                        </div>
                    </h2>
                    <div id="SpacingOne">
                        <div class="accordion-body border-top">
                            <div class="row">
                                <div class="col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Category Code</label>
                                        <input type="text" class="form-control" disabled value="{{ $category->code }}">
                                    </div>
                                </div>
                                <div class="col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Category Name</label>
                                        <input type="text" name="name" class="form-control"
                                            value="{{ $category->name }}" placeholder="Category Name">
                                    </div>
                                </div>
                                <div class="col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Status</label>
                                        <select name="status" class="form-control">
                                            <option value="active"
                                                {{ $category->status == 'active' || $category->status == 1 || $category->status === null ? 'selected' : '' }}>
                                                Active</option>
                                            <option value="inactive"
                                                {{ $category->status == 'inactive' || $category->status == 0 ? 'selected' : '' }}>
                                                Inactive</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Category Image</label>
                                        <div class="image-upload image-upload-two mb-2">
                                            <input type="file" id="imageInput" name="img" accept="image/*">
                                            <div class="image-uploads">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="feather feather-plus-circle plus-down-add me-0">
                                                    <circle cx="12" cy="12" r="10"></circle>
                                                    <line x1="12" y1="8" x2="12" y2="16">
                                                    </line>
                                                    <line x1="8" y1="12" x2="16" y2="12">
                                                    </line>
                                                </svg>
                                                <h4>Change Image</h4>
                                            </div>
                                        </div>
                                        @if ($category->image_url)
                                            <div class="phone-img mt-2">
                                                <img src="{{ $category->image_url }}" alt="{{ $category->name }}">
                                            </div>
                                        @endif
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-end mb-4">
                <a href="{{ route('category.index') }}" class="btn btn-danger me-2">Cancel</a>
                <button type="submit" class="btn btn-dark">Update</button>
            </div>
        </div>
    </form>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            @if (session('success'))
                Swal.fire({
                    title: 'Success!',
                    text: "{{ session('success') }}",
                    icon: 'success',
                    customClass: {
                        confirmButton: 'btn btn-primary'
                    },
                    buttonsStyling: false
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    title: 'Error!',
                    text: "{{ session('error') }}",
                    icon: 'error',
                    customClass: {
                        confirmButton: 'btn btn-primary'
                    },
                    buttonsStyling: false
                });
            @endif
        });
    </script>
@endpush
