@extends('admin.app')

@push('title')
    View Brand - {{ $brand->name }}
@endpush

@push('css')
<style>
    .brand-image {
        width: 100%;
        max-width: 300px;
        height: auto;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .info-card {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        padding: 25px;
        margin-bottom: 25px;
        border: 1px solid #e9ecef;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid #f8f9fa;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-label {
        font-weight: 600;
        color: #495057;
    }

    .info-value {
        color: #6c757d;
    }

    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .status-active {
        background-color: #d4edda;
        color: #155724;
    }

    .status-inactive {
        background-color: #f8d7da;
        color: #721c24;
    }
</style>
@endpush


@section('content')

<div class="page-header">
    <div class="page-title">
        <h3 class="fw-bold">Brand Details</h3>
        <p class="text-muted">View complete information about {{ $brand->name }}</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('brand.index') }}" class="btn btn-outline-secondary me-2">
            <i class="fas fa-arrow-left me-2"></i>Back to Brands
        </a>
        <a href="{{ route('brand.edit', ['id' => $brand->id, 'type' => request()->query('type')]) }}" class="btn btn-warning me-2">
            <i class="fas fa-edit me-2"></i>Edit Brand
        </a>
        <a href="{{ route('brand.delete', ['id' => $brand->id, 'type' => request()->query('type')]) }}" 
   class="btn btn-danger delete-brand" 
   data-id="{{ $brand->id }}">
   <i class="fas fa-trash me-2"></i>Delete
</a>
    </div>
</div>

<div class="row">
    <div class="col-lg-5">
        <div class="info-card text-center">
            <h5 class="mb-4"><i class="fas fa-image me-2"></i>Brand Image</h5>

            @if($brand->image)
    <img src="{{ asset('storage/' . $brand->image) }}"
         alt="{{ $brand->name }}"
         class="brand-image">
@else
    <div class="text-muted py-5">
        <i class="fas fa-image fa-3x mb-3"></i>
        <p>No image available</p>
    </div>
@endif
        </div>
    </div>

    <div class="col-lg-7">
        <div class="info-card">
            <h5 class="mb-4"><i class="fas fa-info-circle me-2"></i>Brand Information</h5>

            <div class="info-row">
                <span class="info-label">Brand Code:</span>
                <span class="info-value">{{ $brand->code }}</span>
            </div>

            <div class="info-row">
                <span class="info-label">Brand Name:</span>
                <span class="info-value">{{ $brand->name }}</span>
            </div>

            <div class="info-row">
                <span class="info-label">Status:</span>
                <span class="status-badge {{ $brand->status == 1 || $brand->status === 'active' ? 'status-active' : 'status-inactive' }}">
                    {{ $brand->status == 1 || $brand->status === 'active' ? 'Active' : 'Inactive' }}
                </span>
            </div>

            <div class="info-row">
                <span class="info-label">Seller Code:</span>
                <span class="info-value">
                    {{ $brand->seller_code == 0 ? 'Admin Brand' : $brand->seller_code }}
                </span>
            </div>

            <div class="info-row">
                <span class="info-label">Created At:</span>
                <span class="info-value">{{ $brand->created_at ?? 'N/A' }}</span>
            </div>

            <div class="info-row">
                <span class="info-label">Last Updated:</span>
                <span class="info-value">{{ $brand->updated_at ?? 'N/A' }}</span>
            </div>

        </div>
    </div>
</div>

@endsection
@push('script')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const deleteButtons = document.querySelectorAll('.delete-brand');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault(); // prevent the default link action
            const url = this.href;

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // If confirmed, go to the delete URL
                    window.location.href = url;
                }
            });
        });
    });
});
</script>
@endpush