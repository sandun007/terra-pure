@extends('admin.app')
@push('title')
Brand List
@endpush
@push('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <style>
        .phone-img {
            position: relative;
            display: inline-block;
            margin: 10px;
        }

        .phone-img img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .phone-img a {
            position: absolute;
            top: 2px;
            right: 2px;
            background: rgba(255, 255, 255, 0.7);
            border-radius: 50%;
            padding: 2px;
        }
        .bg-green{
            background-color: var(--main-theme-color) !important;
        }
    </style>

@endpush
@section('content')
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4 class="fw-bold">Brand List</h4>
                <h6>{{$brandCount}}</h6>
            </div>
        </div>
        <ul class="table-top-head">
            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Refresh"
                   data-bs-original-title="Refresh"><i class="ti ti-refresh"></i></a>
            </li>
            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" id="collapse-header" aria-label="Collapse"
                   data-bs-original-title="Collapse"><i class="ti ti-chevron-up"></i></a>
            </li>
        </ul>
        <div class="page-btn mt-0">
            <a href="{{route('brand.create')}}" class="btn btn-secondary">Create New Brand</a>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="mb-3 d-flex flex-wrap align-items-end gap-3">
                        <div>
                            <label for="brandTypeFilter" class="form-label mb-1 fw-semibold">Filter by Type</label>
                            <select id="brandTypeFilter" class="form-select" style="min-width:160px;">
                                <option value="">All Types</option>
                                <option value="Admin">Admin</option>
                                <option value="Seller">Seller</option>
                                <option value="Wholesaler">Wholesaler</option>
                            </select>
                        </div>
                        <div id="creatorContainer" style="display:none;">
                            <label for="brandCreatorFilter" class="form-label mb-1 fw-semibold">Select <span id="creatorTypeLabel">Creator</span></label>
                            <select id="brandCreatorFilter" class="form-select" style="min-width:180px;">
                                <option value="">All</option>
                            </select>
                        </div>
                        <div class="ms-auto">
                            <label for="brandSearch" class="form-label mb-1 fw-semibold">Search</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="ti ti-search"></i></span>
                                <input type="text" id="brandSearch" class="form-control" placeholder="Search brands..." style="min-width:200px;">
                            </div>
                        </div>
                    </div>
                    <table id="product-table" class="display table table-striped" style="width:100%">
                        <thead>
                        <tr>
                            <th>Brand Code</th>
                            <th>Brand Name</th>
                            <th>Created By</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
@foreach($brands as $brand)
    <tr data-type="{{ $brand->store }}" 
        data-creator="{{ $brand->creator_name }}">
        <td>{{ $brand->code }}</td>
        <td>{{ $brand->name }}</td>
        <td>{{ $brand->creator_name }}</td>
        <td>
    @if($brand->status == 1 || strtolower($brand->status) == 'active')
        <span class="badge bg-success">Active</span>
    @else
        <span class="badge bg-danger">Inactive</span>
    @endif
</td>

        <td>
            @php
                $typeParam = strtolower($brand->store);
            @endphp
            <!-- View Button -->
            <a href="{{ route('brand.show', ['id' => $brand->id, 'type' => $typeParam]) }}" class="btn btn-dark btn-sm">
    <i class="bi bi-eye"> View</i>
</a>

            <!-- Edit Button -->
            <a href="{{ route('brand.edit', ['id' => $brand->id, 'type' => $typeParam]) }}" 
               class="btn btn-warning btn-sm">
                <i class="bi bi-pencil-square"> Update</i>
            </a>

            <!-- Delete Button -->
            <a href="{{ route('brand.delete', ['id' => $brand->id, 'type' => $typeParam]) }}" 
   class="btn btn-danger btn-sm delete-brand">
    <i class="bi bi-trash"></i> Delete
</a>
        </td>
    </tr>
@endforeach
</tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>


@endsection
@push('script')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            var table = $('#product-table').DataTable({
                info: false,
                searching: true,
                dom: 'lrtip',
                columnDefs: [{
                    targets: 2,
                    render: function(data, type, row) {
                        if (type === 'filter' || type === 'sort') {
                            return $('<div>').html(data).text().trim();
                        }
                        return data;
                    }
                }]
            });
            
            // Wire custom search input to DataTable global search
            $('#brandSearch').on('keyup', function() {
                table.search($(this).val()).draw();
            });

            // Helper: escape regex special chars
            function escapeRegex(str) {
                return str.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
            }

            // Cascading Filter Logic
            $('#brandTypeFilter').on('change', function() {
                var type = $(this).val();

                // Reset Creator Filter
                $('#brandCreatorFilter').html('<option value="">All</option>').val('');

                if (type === 'Seller' || type === 'Wholesaler') {
                    $('#creatorTypeLabel').text(type);
                    $('#creatorContainer').show();

                    // Fetch only creators who have categories
                    $.ajax({
                        url: "{{ route('brand.get-creators') }}",
                        method: "GET",
                        data: { type: type },
                        dataType: 'json',
                        success: function(data) {
                            if (data.length > 0) {
                                $.each(data, function(index, item) {
                                    var fullName = item.name + ' (' + type + ')';
                                    $('#brandCreatorFilter').append(
                                        '<option value="' + fullName + '">' + item.name + '</option>'
                                    );
                                });
                            }
                        }
                    });

                    // Filter table to show only this type
                    table.column(2).search('\\(' + type + '\\)', true, false).draw();
                } else if (type === 'Admin') {
                    $('#creatorContainer').hide();
                    table.column(2).search('^Admin$', true, false).draw();
                } else {
                    $('#creatorContainer').hide();
                    table.column(2).search('').draw();
                }
            });

            $('#brandCreatorFilter').on('change', function() {
                var creator = $(this).val();
                if (creator) {
                    table.column(2).search('^' + escapeRegex(creator) + '$', true, false).draw();
                } else {
                    var type = $('#brandTypeFilter').val();
                    if (type) {
                        table.column(2).search('\\(' + type + '\\)', true, false).draw();
                    } else {
                        table.column(2).search('').draw();
                    }
                }
            });
        });
    </script>


<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.delete-brand').forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            let url = this.getAttribute('href');

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        });
    });
});
</script>

@endpush
