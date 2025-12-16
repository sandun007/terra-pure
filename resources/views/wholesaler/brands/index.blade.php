@extends('wholesaler.layouts.app')
@push('title')
    Brand Management
@endpush

@push('css')
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons+Outlined" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <style>
        .page-header {
            background: linear-gradient(135deg, var(--main-theme-color) 0%, #2c5282 100%);
            color: white;
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .brand-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            overflow: hidden;
            height: 100%;
            background: white;
        }

        .brand-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .brand-image-container {
            height: 150px;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        .brand-image {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 20px;
            transition: transform 0.3s ease;
        }

        .brand-card:hover .brand-image {
            transform: scale(1.05);
        }

        .no-image {
            font-size: 3rem;
            color: #94a3b8;
        }

        .brand-code {
            background: rgba(102, 126, 234, 0.1);
            color: var(--main-theme-color);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
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
            gap: 10px;
        }

        .btn-action {
            flex: 1;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            transition: all 0.3s ease;
            border: none;
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

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-radius: 12px;
            border: 2px dashed #cbd5e1;
        }

        .empty-state i {
            font-size: 4rem;
            color: #94a3b8;
            margin-bottom: 1rem;
        }

        .table-brand-image {
            width: 60px;
            height: 60px;
            object-fit: contain;
            border-radius: 8px;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            padding: 5px;
            border: 2px solid #e2e8f0;
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

        .view-toggle {
            display: flex;
            gap: 10px;
            background: white;
            padding: 10px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .view-toggle-btn {
            padding: 8px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            background: white;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .view-toggle-btn.active {
            background: var(--main-theme-color);
            color: white;
            border-color: var(--main-theme-color);
        }

        .view-toggle-btn:hover {
            border-color: var(--main-theme-color);
        }

        @media (max-width: 768px) {
            .brand-card {
                margin-bottom: 1.5rem;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn-action {
                width: 100%;
            }
        }
    </style>
@endpush

@section('content')
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-start flex-wrap">
            <div>
                <h3 class="fw-bold d-flex align-items-center mb-2">
                    <span class="material-icons-outlined me-2" style="font-size: 2.5rem;">branding_watermark</span>
                    Brand Management
                </h3>
                <p class="mb-0 opacity-90 d-flex align-items-center">
                    <span class="material-icons-outlined me-2" style="font-size: 1.2rem;">format_list_bulleted</span>
                    {{ $brands->count() }} Brands in Catalog
                </p>
            </div>
            <div class="page-actions d-flex gap-2 mt-3 mt-md-0">
                <a href="{{ route('wholesaler.brands.create') }}" class="btn btn-light d-flex align-items-center">
                    <span class="material-icons-outlined me-2">add_circle</span>
                    Add New Brand
                </a>
            </div>
        </div>
    </div>

    @if($brands->isEmpty())
        <div class="empty-state">
            <span class="material-icons-outlined">branding_watermark</span>
            <h4 class="text-muted mb-3">No Brands Found</h4>
            <p class="text-muted mb-4">Start by creating your first brand for your products</p>
            <a href="{{ route('wholesaler.brands.create') }}" class="btn btn-primary d-flex align-items-center mx-auto" style="width: fit-content;">
                <span class="material-icons-outlined me-2">add_circle</span>
                Create First Brand
            </a>
        </div>
    @else
        <!-- View Toggle -->
        <div class="d-flex justify-content-end mb-3">
            <div class="view-toggle">
                <button class="view-toggle-btn active" id="gridViewBtn" onclick="toggleView('grid')">
                    <span class="material-icons-outlined">grid_view</span>
                    Grid
                </button>
                <button class="view-toggle-btn" id="tableViewBtn" onclick="toggleView('table')">
                    <span class="material-icons-outlined">table_rows</span>
                    Table
                </button>
            </div>
        </div>

        <!-- Grid View -->
        <div id="gridView" class="row mb-4">
            @foreach($brands as $brand)
                <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                    <div class="brand-card">
                        <div class="brand-image-container">
                            @if($brand->image)
                                <img src="{{ asset('storage/' . $brand->image) }}"
                                     alt="{{ $brand->name }}"
                                     class="brand-image"
                                     onerror="this.parentElement.innerHTML='<span class=\'material-icons-outlined no-image\'>category</span>'">
                            @else
                                <span class="material-icons-outlined no-image">category</span>
                            @endif
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title mb-0">{{ $brand->name }}</h5>
                                <span class="brand-code">{{ $brand->code }}</span>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="product-count-badge">
                                    <span class="material-icons-outlined" style="font-size: 1rem;">inventory</span>
                                    {{ \App\Models\WholesalerProduct::where('brand_id', $brand->id)->where('wholesaler_id', Auth::guard('wholesaler')->id())->count() }} products
                                </span>
                                <span class="status-badge {{ $brand->status == 'active' ? 'status-active' : 'status-inactive' }}">
                                    <span class="material-icons-outlined" style="font-size: 1rem;">
                                        {{ $brand->status == 'active' ? 'check_circle' : 'cancel' }}
                                    </span>
                                    {{ ucfirst($brand->status) }}
                                </span>
                            </div>

                            <div class="action-buttons">
                                <a href="{{ route('wholesaler.brands.edit', $brand->id) }}"
                                   class="btn-action btn-edit">
                                    <span class="material-icons-outlined">edit</span>
                                    Edit
                                </a>
                                <button type="button" class="btn-action btn-delete" onclick="deleteBrand({{ $brand->id }}, '{{ $brand->name }}')">
                                    <span class="material-icons-outlined">delete</span>
                                    Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Table View (Hidden by default) -->
        <div id="tableView" class="card" style="display: none;">
            <div class="card-body">
                <table id="brands-table" class="display table table-hover" style="width:100%">
                    <thead>
                    <tr>
                        <th>Image</th>
                        <th>Brand Name</th>
                        <th>Code</th>
                        <th>Products</th>
                        <th>Status</th>
                        <th>Created</th>
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
                                         class="table-brand-image"
                                         onerror="this.parentElement.innerHTML='<div class=\'table-brand-image d-flex align-items-center justify-content-center\'><span class=\'material-icons-outlined text-muted\'>image</span></div>'">
                                @else
                                    <div class="table-brand-image d-flex align-items-center justify-content-center">
                                        <span class="material-icons-outlined text-muted">image</span>
                                    </div>
                                @endif
                            </td>
                            <td><strong>{{ $brand->name }}</strong></td>
                            <td><code>{{ $brand->code }}</code></td>
                            <td>
                                <span class="product-count-badge">
                                    {{ \App\Models\WholesalerProduct::where('brand_id', $brand->id)->where('wholesaler_id', Auth::guard('wholesaler')->id())->count() }}
                                </span>
                            </td>
                            <td>
                                <span class="status-badge {{ $brand->status == 'active' ? 'status-active' : 'status-inactive' }}">
                                    {{ ucfirst($brand->status) }}
                                </span>
                            </td>
                            <td>{{ $brand->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('wholesaler.brands.edit', $brand->id) }}"
                                       class="btn btn-sm btn-outline-warning d-flex align-items-center">
                                        <span class="material-icons-outlined" style="font-size: 1rem;">edit</span>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger d-flex align-items-center"
                                            onclick="deleteBrand({{ $brand->id }}, '{{ $brand->name }}')">
                                        <span class="material-icons-outlined" style="font-size: 1rem;">delete</span>
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
                    zeroRecords: "No matching brands found"
                },
                columnDefs: [
                    { orderable: false, targets: [0, 6] },
                    { className: "align-middle", targets: "_all" }
                ],
                order: [[1, 'asc']],
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
                position: 'top-end'
            });
            @endif

            // Show error message if exists
            @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '{{ session('error') }}',
                showConfirmButton: true
            });
            @endif
        });

        // Toggle between grid and table view
        function toggleView(view) {
            const gridView = document.getElementById('gridView');
            const tableView = document.getElementById('tableView');
            const gridBtn = document.getElementById('gridViewBtn');
            const tableBtn = document.getElementById('tableViewBtn');

            if (view === 'grid') {
                gridView.style.display = 'flex';
                tableView.style.display = 'none';
                gridBtn.classList.add('active');
                tableBtn.classList.remove('active');
            } else {
                gridView.style.display = 'none';
                tableView.style.display = 'block';
                tableBtn.classList.add('active');
                gridBtn.classList.remove('active');
            }
        }

        // Delete brand with SweetAlert
        function deleteBrand(brandId, brandName) {
            Swal.fire({
                title: 'Are you sure?',
                html: `You are about to delete <strong>${brandName}</strong><br>This action cannot be undone!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '<span class="material-icons-outlined" style="font-size: 1rem; vertical-align: middle;">delete</span> Yes, delete it!',
                cancelButtonText: '<span class="material-icons-outlined" style="font-size: 1rem; vertical-align: middle;">cancel</span> Cancel'
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
