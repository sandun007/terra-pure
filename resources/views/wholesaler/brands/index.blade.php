@extends('wholesaler.layouts.app')

@push('title')
    Brand List
@endpush

@push('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
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

        .brand-image {
            width: 60px;
            height: 60px;
            object-fit: contain;
            border-radius: 8px;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            padding: 5px;
            border: 2px solid #e2e8f0;
        }

        .status-badge {
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 0.85rem;
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

        .product-count-badge {
            background: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
            justify-content: center;
        }

        .btn-action {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-view {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
        }

        .btn-edit {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
        }

        .btn-delete {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }

        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .btn-view:hover {
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }

        .btn-edit:hover {
            box-shadow: 0 4px 12px rgba(217, 119, 6, 0.3);
        }

        .btn-delete:hover {
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
        }

        #brands-table_wrapper {
            padding: 0;
        }

        #brands-table_filter input {
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 10px 15px;
            width: 300px !important;
            transition: all 0.3s ease;
        }

        #brands-table_filter input:focus {
            border-color: var(--main-theme-color);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            outline: none;
        }

        table.dataTable tbody tr {
            transition: all 0.3s ease;
        }

        table.dataTable tbody tr:hover {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        }

        .material-icons {
            font-size: 18px;
            vertical-align: middle;
        }

        .brand-code {
            background: rgba(102, 126, 234, 0.1);
            color: var(--main-theme-color);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-block;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-radius: 12px;
            border: 2px dashed #cbd5e1;
        }

        .empty-state .material-icons {
            font-size: 4rem;
            color: #94a3b8;
            margin-bottom: 1rem;
        }

        /* DataTable customization */
        .dataTables_length select {
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            padding: 5px 10px;
            margin: 0 5px;
        }

        .dataTables_paginate .paginate_button {
            border-radius: 8px !important;
            margin: 0 3px !important;
        }

        .dataTables_paginate .paginate_button.current {
            background: var(--main-theme-color) !important;
            color: white !important;
            border: none !important;
        }

        .dataTables_info {
            color: #64748b;
            font-size: 0.9rem;
        }
    </style>
@endpush

@section('content')
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-start flex-wrap">
            <div>
                <h3 class="fw-bold d-flex align-items-center mb-2">
                    <span class="material-icons me-2" style="font-size: 2.5rem;">branding_watermark</span>
                    Brand Management
                </h3>
                <p class="mb-0 opacity-90 d-flex align-items-center">
                    <span class="material-icons me-2" style="font-size: 1.2rem;">format_list_bulleted</span>
                    {{ $brands->count() }} Brands in Catalog
                </p>
            </div>
            <div class="page-actions d-flex gap-2 mt-3 mt-md-0">
                <a href="{{ route('wholesaler.brands.create') }}" class="btn btn-light d-flex align-items-center">
                    <span class="material-icons me-2">add_circle</span>
                    Add New Brand
                </a>
            </div>
        </div>
    </div>

    @if($brands->isEmpty())
        <div class="empty-state">
            <span class="material-icons">branding_watermark</span>
            <h4 class="text-muted mb-3">No Brands Found</h4>
            <p class="text-muted mb-4">Start by creating your first brand for your products</p>
            <a href="{{ route('wholesaler.brands.create') }}" class="btn btn-primary d-flex align-items-center mx-auto" style="width: fit-content;">
                <span class="material-icons me-2">add_circle</span>
                Create First Brand
            </a>
        </div>
    @else
        <div class="card">
            <div class="card-body">
                <table id="brands-table" class="display table table-hover" style="width:100%">
                    <thead>
                    <tr>
                        <th>Image</th>
                        <th>Brand Code</th>
                        <th>Brand Name</th>
                        <th>Products</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($brands as $brand)
                        <tr>
                            <td>
                                @if($brand->image)
                                    <img src="{{ asset('storage/' . $brand->image) }}"
                                         alt="{{ $brand->name }}"
                                         class="brand-image"
                                         onerror="this.onerror=null; this.src='{{ asset('images/no-image.png') }}';">
                                @else
                                    <div class="brand-image d-flex align-items-center justify-content-center">
                                        <span class="material-icons text-muted">image</span>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <span class="brand-code">{{ $brand->code }}</span>
                            </td>
                            <td>
                                <strong>{{ $brand->name }}</strong>
                            </td>
                            <td>
                                    <span class="product-count-badge">
                                        <span class="material-icons" style="font-size: 16px;">inventory</span>
                                        {{ \App\Models\WholesalerProduct::where('brand_id', $brand->id)->where('wholesaler_id', Auth::guard('wholesaler')->id())->count() }}
                                    </span>
                            </td>
                            <td>
                                    <span class="status-badge {{ $brand->status == 'active' ? 'status-active' : 'status-inactive' }}">
                                        <span class="material-icons" style="font-size: 16px;">
                                            {{ $brand->status == 'active' ? 'check_circle' : 'cancel' }}
                                        </span>
                                        {{ ucfirst($brand->status) }}
                                    </span>
                            </td>
                            <td>{{ $brand->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('wholesaler.brands.show', $brand->id) }}"
                                       class="btn-action btn-view"
                                       title="View Brand">
                                        <span class="material-icons">visibility</span>
                                    </a>
                                    <a href="{{ route('wholesaler.brands.edit', $brand->id) }}"
                                       class="btn-action btn-edit"
                                       title="Edit Brand">
                                        <span class="material-icons">edit</span>
                                    </a>
                                    <button type="button"
                                            class="btn-action btn-delete"
                                            onclick="deleteBrand({{ $brand->id }}, '{{ $brand->name }}')"
                                            title="Delete Brand">
                                        <span class="material-icons">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- Hidden form for deletion -->
    <form id="delete-form" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
@endsection

@push('script')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function () {
            // Initialize DataTable
            $('#brands-table').DataTable({
                responsive: true,
                language: {
                    search: "",
                    searchPlaceholder: "Search brands...",
                    lengthMenu: "Show _MENU_ brands",
                    info: "Showing _START_ to _END_ of _TOTAL_ brands",
                    infoEmpty: "No brands available",
                    zeroRecords: "No matching brands found",
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "Next",
                        previous: "Previous"
                    }
                },
                columnDefs: [
                    { orderable: false, targets: [0, 6] },
                    { className: "align-middle", targets: "_all" }
                ],
                order: [[2, 'asc']],
                pageLength: 10,
                lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]]
            });

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
                confirmButtonText: 'OK',
                confirmButtonText: '<span class="material-icons" style="font-size: 18px; vertical-align: middle;">check</span> OK'
            });
            @endif
        });

        // Delete brand with SweetAlert
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
    </script>
@endpush
