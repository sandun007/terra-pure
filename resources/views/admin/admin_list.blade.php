@extends('admin.app')
@push('title')
    Admins List
@endpush

@push('css')
    <style>
        .admin-img-circle {
            width: 45px;
            height: 45px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .admin-placeholder {
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f3f6f9;
            color: #adb5bd;
            border-radius: 50%;
            border: 2px solid #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .table-responsive {
            border-radius: 10px;
            overflow: hidden;
        }

        .table thead th {
            background-color: #f8f9fa;
            border-top: none;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
            color: #555;
            padding: 1.2rem 1rem;
        }

        .table tbody td {
            vertical-align: middle;
            padding: 1rem;
            color: #444;
            border-bottom: 1px solid #f1f2f3;
        }

        .action-btn {
            width: 32px;
            height: 32px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .action-btn:hover {
            transform: translateY(-2px);
        }

        .search-container {
            position: relative;
            max-width: 350px;
        }

        .search-container .feather-search {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #adb5bd;
            width: 18px;
            height: 18px;
        }

        .search-container .form-control {
            padding-left: 40px;
            border-radius: 8px;
        }

        .badge-role {
            font-weight: 500;
            padding: 0.4rem 0.8rem;
            border-radius: 6px;
        }
    </style>
@endpush

@section('content')
    <div class="page-header justify-content-between">
        <div class="page-title">
            <h4 class="fw-bold mb-1">Admins Management</h4>
            <h6 class="text-muted">Manage system administrators (Total: <span
                    class="text-primary fw-bold">{{ $count }}</span>)</h6>
        </div>
        <div class="page-btn">
            <a href="{{ route('admin.create') }}" class="btn btn-primary d-flex align-items-center">
                <i data-feather="plus-circle" class="me-2"></i>
                Add New Admin
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert">
            <div class="d-flex align-items-center">
                <i data-feather="check-circle" class="me-2"></i>
                {{ session('success') }}
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert">
            <div class="d-flex align-items-center">
                <i data-feather="alert-circle" class="me-2"></i>
                {{ session('error') }}
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom py-3">
            <div class="row align-items-center">
                <div class="col">
                    <div class="search-container">
                        <i data-feather="search"></i>
                        <input type="text" id="adminSearch" class="form-control"
                            placeholder="Search admins by name, email or username...">
                    </div>
                </div>
                <div class="col-auto">
                    {{-- Future filters can go here --}}
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="adminsTable">
                    <thead>
                        <tr>
                            <th width="50">#</th>
                            <th>Admin</th>
                            <th>Contact Info</th>
                            <th>Username</th>
                            <th>Address</th>
                            <th>Joined Date</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($admins as $admin)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if ($admin->img)
                                            <img src="{{ asset('storage/' . $admin->img) }}" alt="{{ $admin->name }}"
                                                class="admin-img-circle me-3">
                                        @else
                                            <div class="admin-placeholder me-3">
                                                <i data-feather="user"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="fw-bold d-flex align-items-center">
                                                {{ $admin->name }}
                                                @if (\Illuminate\Support\Facades\Cache::has('admin-is-online-' . $admin->id))
                                                    <span class="ms-2 d-inline-block bg-success rounded-circle"
                                                        style="width: 8px; height: 8px;" title="Online"></span>
                                                @endif
                                            </div>
                                            <span
                                                class="badge bg-light-primary text-primary badge-role">Administrator</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <small class="text-muted d-flex align-items-center mb-1">
                                            <i data-feather="mail" class="me-1" style="width: 12px; height: 12px;"></i>
                                            {{ $admin->email }}
                                        </small>
                                        <small class="text-muted d-flex align-items-center">
                                            <i data-feather="phone" class="me-1" style="width: 12px; height: 12px;"></i>
                                            {{ $admin->phone ?? 'No phone' }}
                                        </small>
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-medium text-dark">{{ $admin->username }}</span>
                                </td>
                                <td>
                                    <span class="text-muted small">{{ Str::limit($admin->address, 30) ?: 'N/A' }}</span>
                                </td>
                                <td>
                                    @if ($admin->created_at)
                                        <div class="small text-muted">{{ $admin->created_at->format('M d, Y') }}</div>
                                        <div class="text-dark-lightest small">{{ $admin->created_at->diffForHumans() }}
                                        </div>
                                    @else
                                        <div class="small text-muted">N/A</div>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.admins.view', $admin->id) }}"
                                        class="btn btn-sm btn-outline-primary action-btn" title="View Admin">
                                        <i data-feather="eye" style="width: 14px; height: 14px;"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="mb-3">
                                        <i data-feather="users" style="width: 48px; height: 48px; color: #ced4da;"></i>
                                    </div>
                                    <h5 class="text-muted">No admins found.</h5>
                                    <p class="text-muted small">Start by adding a new administrator to the system.</p>
                                    <a href="{{ route('admin.create') }}" class="btn btn-primary mt-2">Add New Admin</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Search functionality
        document.getElementById('adminSearch').addEventListener('keyup', function() {
            let value = this.value.toLowerCase();
            let rows = document.querySelectorAll('#adminsTable tbody tr');

            rows.forEach(row => {
                let text = row.textContent.toLowerCase();
                row.style.display = text.includes(value) ? '' : 'none';
            });
        });

        // Confirm delete with SweetAlert2
        function confirmDelete(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This admin account will be permanently deleted!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }

        // Refresh feathers icons after any potential DOM updates
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        });
    </script>
@endpush
