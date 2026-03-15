@extends('wholesaler.layouts.app')

@push('title')
    Category Management
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
            box-shadow: 0 4px 20px rgba(0,0,0,.1);
        }

        .table-category-image {
            width: 56px;
            height: 56px;
            object-fit: contain;
            border-radius: 10px;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            padding: 6px;
            border: 2px solid #e2e8f0;
        }

        .status-badge {
            padding: 6px 14px;
            border-radius: 999px;
            font-size: .85rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #fff;
        }
        .status-active { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
        .status-inactive { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); }

        .count-badge {
            background: rgba(59, 130, 246, 0.12);
            color: #2563eb;
            padding: 4px 12px;
            border-radius: 999px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        /* DataTable search styling */
        #categories-table_filter { position: relative; }
        #categories-table_filter:before {
            content: "search";
            font-family: 'Material Icons Outlined';
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            z-index: 10;
        }
        #categories-table_filter input {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 10px 14px 10px 44px;
            width: 320px !important;
            outline: none;
        }
        #categories-table_filter input:focus {
            border-color: var(--main-theme-color);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.12);
        }
    </style>
@endpush

@section('content')
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h3 class="fw-bold d-flex align-items-center mb-1">
                    <span class="material-icons-outlined me-2" style="font-size:2.4rem;">category</span>
                    Category Management
                </h3>
                <p class="mb-0 opacity-90 d-flex align-items-center">
                    <span class="material-icons-outlined me-2">format_list_bulleted</span>
                    {{ $count }} Categories in Catalog
                </p>
            </div>
            <div>
                <a href="{{ route('wholesaler.categories.create') }}" class="btn btn-light d-flex align-items-center">
                    <span class="material-icons-outlined me-2">add_circle</span>
                    Add New Category
                </a>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center">
                <span class="material-icons-outlined me-2">check_circle</span>
                <div>{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center">
                <span class="material-icons-outlined me-2">error</span>
                <div>{{ session('error') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($categories->isEmpty())
        <div class="text-center p-5 bg-light rounded-4 border border-2 border-dashed">
            <span class="material-icons-outlined" style="font-size:64px;color:#94a3b8;">category</span>
            <h4 class="text-muted mt-3">No Categories Found</h4>
            <p class="text-muted">Start by creating your first category.</p>
            <a href="{{ route('wholesaler.categories.create') }}" class="btn btn-primary d-inline-flex align-items-center">
                <span class="material-icons-outlined me-2">add_circle</span>
                Create First Category
            </a>
        </div>
    @else
        <div class="card">
            <div class="card-body">
                <table id="categories-table" class="display table table-hover align-middle" style="width:100%">
                    <thead>
                    <tr>
                        <th style="width:80px;">Image</th>
                        <th>Category Name</th>
                        <th>Code</th>
                        <th style="width:120px;">Products</th>
                        <th style="width:130px;">Status</th>
                        <th style="width:140px;">Created</th>
                        <th style="width:180px;">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($categories as $category)
                        <tr>
                            <td>
                                @if($category->image)
                                    <img class="table-category-image"
                                         src="{{ asset('storage/' . $category->image) }}"
                                         alt="{{ $category->name }}"
                                         onerror="this.src='{{ asset('images/default-category.png') }}'">
                                @else
                                    <div class="table-category-image d-flex align-items-center justify-content-center">
                                        <span class="material-icons-outlined text-muted">image</span>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="fw-bold">{{ $category->name }}</div>
                            </td>
                            <td><code>{{ $category->code }}</code></td>
                            <td>
                                <span class="count-badge">
                                    <span class="material-icons-outlined" style="font-size:18px;">inventory</span>
                                    {{ $category->products_count ?? 0 }}
                                </span>
                            </td>
                            <td>
                                <span class="status-badge {{ $category->status == 'active' ? 'status-active' : 'status-inactive' }}">
                                    <span class="material-icons-outlined" style="font-size:18px;">
                                        {{ $category->status == 'active' ? 'check_circle' : 'cancel' }}
                                    </span>
                                    {{ ucfirst($category->status) }}
                                </span>
                            </td>
                            <td>{{ $category->created_at?->format('M d, Y') }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    {{-- ✅ VIEW BUTTON --}}
                                    <a href="{{ route('wholesaler.categories.show', $category->id) }}"
                                       class="btn btn-sm btn-outline-primary d-flex align-items-center"
                                       title="View">
                                        <span class="material-icons-outlined" style="font-size:18px;">visibility</span>
                                    </a>

                                    {{-- EDIT --}}
                                    <a href="{{ route('wholesaler.categories.edit', $category->id) }}"
                                       class="btn btn-sm btn-outline-warning d-flex align-items-center"
                                       title="Edit">
                                        <span class="material-icons-outlined" style="font-size:18px;">edit</span>
                                    </a>

                                    {{-- DELETE --}}
                                    <button type="button"
                                            class="btn btn-sm btn-outline-danger d-flex align-items-center delete-category"
                                            data-id="{{ $category->id }}"
                                            data-name="{{ $category->name }}"
                                            title="Delete">
                                        <span class="material-icons-outlined" style="font-size:18px;">delete</span>
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

    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@push('script')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function () {

            const table = $('#categories-table').DataTable({
                responsive: true,
                order: [[1, 'asc']],
                pageLength: 10,
                lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
                columnDefs: [
                    { orderable: false, targets: [0, 6] },
                    { className: "align-middle", targets: "_all" }
                ],
                language: {
                    search: "",
                    searchPlaceholder: "Search categories...",
                    lengthMenu: "Show _MENU_",
                    info: "Showing _START_ to _END_ of _TOTAL_",
                    infoEmpty: "No categories available",
                    zeroRecords: "No matching categories found",
                    paginate: {
                        first: '<span class="material-icons-outlined">first_page</span>',
                        previous: '<span class="material-icons-outlined">chevron_left</span>',
                        next: '<span class="material-icons-outlined">chevron_right</span>',
                        last: '<span class="material-icons-outlined">last_page</span>'
                    }
                },
                dom: '<"row"<"col-md-6"l><"col-md-6"f>>rt<"row"<"col-md-6"i><"col-md-6"p>>',
                initComplete: function() {
                    $('.dataTables_filter input').addClass('form-control');
                    $('.dataTables_length select').addClass('form-select');
                }
            });

            const csrfToken = $('meta[name="csrf-token"]').attr('content');

            // ✅ SweetAlert Delete
            $(document).on('click', '.delete-category', function() {
                const categoryId = $(this).data('id');
                const categoryName = $(this).data('name');
                const deleteUrl = "{{ route('wholesaler.categories.destroy', ':id') }}".replace(':id', categoryId);

                Swal.fire({
                    title: 'Delete Category?',
                    html: `<div style="text-align:left">
                            <b>Category:</b> ${categoryName}<br>
                            <span style="color:#64748b">This action cannot be undone.</span>
                           </div>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Delete',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#64748b',
                    showLoaderOnConfirm: true,
                    preConfirm: () => {
                        return $.ajax({
                            url: deleteUrl,
                            type: 'DELETE',
                            data: { _token: csrfToken },
                            dataType: 'json'
                        }).then(res => {
                            if (!res.success) throw new Error(res.message || 'Delete failed');
                            return res;
                        }).catch(err => {
                            Swal.showValidationMessage(err.responseJSON?.message || err.message || 'Request failed');
                        });
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Deleted!',
                            text: 'Category deleted successfully.',
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => location.reload());
                    }
                });
            });

            // auto-hide alerts
            setTimeout(() => $('.alert').alert('close'), 5000);
        });
    </script>
@endpush
