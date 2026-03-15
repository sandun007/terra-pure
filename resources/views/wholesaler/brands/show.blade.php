@extends('wholesaler.layouts.app')

@push('title')
    Brand Details - {{ $brand->name }}
@endpush

@push('css')
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        .page-header {
            background: linear-gradient(135deg, var(--main-theme-color) 0%, #2c5282 100%);
            color: white;
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .brand-detail-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            background: white;
        }

        .brand-image-container {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            padding: 2rem;
            text-align: center;
            border-bottom: 1px solid #e2e8f0;
        }

        .brand-detail-image {
            max-width: 200px;
            max-height: 200px;
            object-fit: contain;
            margin: 0 auto;
        }

        .detail-item {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
        }

        .detail-item:last-child {
            border-bottom: none;
        }

        .detail-icon {
            width: 40px;
            height: 40px;
            background: rgba(59, 130, 246, 0.1);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            color: var(--main-theme-color);
        }

        .detail-icon .material-icons {
            font-size: 24px;
        }

        .detail-content {
            flex: 1;
        }

        .detail-label {
            font-size: 0.85rem;
            color: #64748b;
            margin-bottom: 0.25rem;
        }

        .detail-value {
            font-size: 1.1rem;
            font-weight: 600;
            color: #1e293b;
        }

        .status-badge {
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 0.95rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .status-active {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }

        .status-inactive {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }

        .brand-code {
            background: rgba(102, 126, 234, 0.1);
            color: var(--main-theme-color);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            display: inline-block;
        }

        .info-card {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-radius: 10px;
            padding: 1.5rem;
            text-align: center;
        }

        .info-card .material-icons {
            font-size: 2.5rem;
            color: var(--main-theme-color);
            margin-bottom: 0.5rem;
        }

        .info-card .info-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1e293b;
        }

        .info-card .info-label {
            color: #64748b;
            font-size: 0.9rem;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }

        .btn-action {
            flex: 1;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.3s ease;
            border: none;
            text-decoration: none;
        }

        .btn-edit {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
        }

        .btn-delete {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }

        .btn-back {
            background: linear-gradient(135deg, #64748b 0%, #475569 100%);
            color: white;
        }

        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            color: white;
        }

        @media (max-width: 768px) {
            .action-buttons {
                flex-direction: column;
            }
        }
    </style>
@endpush

@section('content')
    <div class="page-header">
        <div class="d-flex align-items-center">
            <a href="{{ route('wholesaler.brands.index') }}" class="btn btn-light me-3 d-flex align-items-center">
                <span class="material-icons me-1">arrow_back</span>
                Back to Brands
            </a>
            <div>
                <h3 class="fw-bold d-flex align-items-center mb-2">
                    <span class="material-icons me-2" style="font-size: 2.5rem;">branding_watermark</span>
                    Brand Details
                </h3>
                <p class="mb-0 opacity-90">View detailed information about {{ $brand->name }}</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="brand-detail-card">
                <div class="brand-image-container">
                    @if($brand->image)
                        <img src="{{ asset('storage/' . $brand->image) }}"
                             alt="{{ $brand->name }}"
                             class="brand-detail-image"
                             onerror="this.onerror=null; this.src='{{ asset('images/no-image.png') }}';">
                    @else
                        <div class="brand-detail-image d-flex align-items-center justify-content-center">
                            <span class="material-icons" style="font-size: 100px; color: #94a3b8;">branding_watermark</span>
                        </div>
                    @endif
                </div>

                <div class="detail-item">
                    <div class="detail-icon">
                        <span class="material-icons">badge</span>
                    </div>
                    <div class="detail-content">
                        <div class="detail-label">Brand Code</div>
                        <div class="detail-value">
                            <span class="brand-code">{{ $brand->code }}</span>
                        </div>
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-icon">
                        <span class="material-icons">check_circle</span>
                    </div>
                    <div class="detail-content">
                        <div class="detail-label">Status</div>
                        <div class="detail-value">
                            <span class="status-badge {{ $brand->status == 'active' ? 'status-active' : 'status-inactive' }}">
                                <span class="material-icons" style="font-size: 18px;">
                                    {{ $brand->status == 'active' ? 'check_circle' : 'cancel' }}
                                </span>
                                {{ ucfirst($brand->status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-icon">
                        <span class="material-icons">calendar_today</span>
                    </div>
                    <div class="detail-content">
                        <div class="detail-label">Created At</div>
                        <div class="detail-value">{{ $brand->created_at->format('F d, Y') }}</div>
                        <small class="text-muted">{{ $brand->created_at->format('h:i A') }}</small>
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-icon">
                        <span class="material-icons">update</span>
                    </div>
                    <div class="detail-content">
                        <div class="detail-label">Last Updated</div>
                        <div class="detail-value">{{ $brand->updated_at->format('F d, Y') }}</div>
                        <small class="text-muted">{{ $brand->updated_at->format('h:i A') }}</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="info-card">
                        <span class="material-icons">inventory</span>
                        <div class="info-value">{{ $productsCount }}</div>
                        <div class="info-label">Total Products</div>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="info-card">
                        <span class="material-icons">production_quantity_limits</span>
                        <div class="info-value">
                            {{ \App\Models\WholesalerProduct::where('brand_id', $brand->id)
                                ->where('wholesaler_id', Auth::guard('wholesaler')->id())
                                ->count() }}
                        </div>
                        <div class="info-label">Total Products</div> <!-- Changed from "Products in Stock" -->
                    </div>
                </div>

                <div class="col-12">
                    <div class="brand-detail-card">
                        <div class="card-header bg-transparent py-3">
                            <h5 class="mb-0 d-flex align-items-center">
                                <span class="material-icons me-2">description</span>
                                Brand Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="detail-label">Brand Name</div>
                                    <div class="fs-5 fw-semibold">{{ $brand->name }}</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="detail-label">Brand ID</div>
                                    <div class="fs-5 fw-semibold">#{{ $brand->id }}</div>
                                </div>
                                <div class="col-12">
                                    <div class="detail-label">Brand Code Description</div>
                                    <div class="text-muted">
                                        The brand code <strong>{{ $brand->code }}</strong> is a unique identifier
                                        for this brand in the system. It's used for internal reference and product categorization.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="action-buttons">
                <a href="{{ route('wholesaler.brands.edit', $brand->id) }}" class="btn-action btn-edit">
                    <span class="material-icons">edit</span>
                    Edit Brand
                </a>
                <button type="button" class="btn-action btn-delete" onclick="deleteBrand({{ $brand->id }}, '{{ $brand->name }}')">
                    <span class="material-icons">delete</span>
                    Delete Brand
                </button>
                <a href="{{ route('wholesaler.brands.index') }}" class="btn-action btn-back">
                    <span class="material-icons">arrow_back</span>
                    Back to List
                </a>
            </div>
        </div>
    </div>

    <!-- Hidden form for deletion -->
    <form id="delete-form" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function deleteBrand(brandId, brandName) {
            Swal.fire({
                title: 'Are you sure?',
                html: `You are about to delete <strong>${brandName}</strong><br>This action cannot be undone!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '<span class="material-icons" style="font-size: 18px; vertical-align: middle;">delete</span> Yes, delete it!',
                cancelButtonText: '<span class="material-icons" style="font-size: 18px; vertical-align: middle;">cancel</span> Cancel',
                background: '#fff',
                backdrop: `
                    rgba(0,0,0,0.4)
                    left top
                    no-repeat
                `,
                customClass: {
                    popup: 'animated fadeInDown faster'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('delete-form');
                    form.action = `/wholesaler/brands/${brandId}`;
                    form.submit();
                }
            });
        }

        // Show success message if exists
        @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('success') }}',
            showConfirmButton: false,
            timer: 3000,
            toast: true,
            position: 'top-end',
            showCloseButton: true
        });
        @endif

        // Show error message if exists
        @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: '{{ session('error') }}',
            confirmButtonColor: '#ef4444',
            confirmButtonText: '<span class="material-icons" style="font-size: 18px; vertical-align: middle;">check</span> OK'
        });
        @endif
    </script>
@endpush
