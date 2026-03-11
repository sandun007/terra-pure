@extends('admin.app')
@push('title')
    Wholesaler List
@endpush
@push('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <style>
        .bg-green { background-color: var(--main-theme-color) !important; }
    </style>
@endpush

@section('content')
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4 class="fw-bold">Wholesaler List</h4>
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
            <a href="{{ route('admin.wholesaler.create') }}" class="btn btn-secondary">Create New Wholesaler</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table id="wholesaler-table" class="display table table-striped" style="width:100%">
                        <thead>
                        <tr>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Business Name</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>City</th>
                            <th>District</th>
                            <th>Type</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($wholesalers as $wholesaler)
                            <tr>
                                <td>{{ $wholesaler->code }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="{{ $wholesaler->profile_image_url }}"
                                             alt="{{ $wholesaler->name }}"
                                             width="36" height="36"
                                             style="border-radius:50%;object-fit:cover;border:1px solid #e2e6f0;">
                                        {{ $wholesaler->name }}
                                    </div>
                                </td>
                                <td>{{ $wholesaler->business_name ?? '—' }}</td>
                                <td>{{ $wholesaler->phone ?? '—' }}</td>
                                <td>{{ $wholesaler->email ?? '—' }}</td>
                                <td>{{ $wholesaler->city ?? '—' }}</td>
                                <td>{{ $wholesaler->district ?? '—' }}</td>
                                <td>
                                    @if($wholesaler->type)
                                        <span class="badge bg-info text-dark">{{ $wholesaler->type }}</span>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.wholesaler.view', $wholesaler->id) }}"
                                           class="btn btn-dark btn-sm"
                                           data-bs-toggle="tooltip" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.wholesaler.edit', $wholesaler->id) }}"
                                           class="btn btn-warning btn-sm"
                                           data-bs-toggle="tooltip" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form id="delete-form-{{ $wholesaler->id }}"
                                              action="{{ route('admin.wholesaler.delete', $wholesaler->id) }}"
                                              method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                    class="btn btn-danger btn-sm"
                                                    onclick="confirmDelete({{ $wholesaler->id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
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
            $('#wholesaler-table').DataTable();
        });

        function confirmDelete(id) {
            if (confirm('Are you sure you want to delete this wholesaler?')) {
                document.getElementById('delete-form-' + id).submit();
            }
        }
    </script>
@endpush
