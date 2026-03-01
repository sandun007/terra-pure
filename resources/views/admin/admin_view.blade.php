@extends('admin.app')
@push('title')
    View Admin Details
@endpush

@section('content')
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4 class="fw-bold">View Admin</h4>
                <h6>Admin Profile Data</h6>
            </div>
        </div>
        <div class="page-btn mt-0">
            <a href="{{ route('admin.admins.index') }}" class="btn btn-secondary">Back to Admins</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4 text-center">
                    @if ($admin->img)
                        <img src="{{ asset('storage/' . $admin->img) }}" alt="{{ $admin->name }}"
                            style="width: 120px; height: 120px; object-fit: cover;"
                            class="rounded-circle mb-3 shadow-sm border border-3 border-white">
                    @else
                        <div class="d-flex align-items-center justify-content-center bg-light text-secondary rounded-circle mb-3 shadow-sm border border-3 border-white mx-auto"
                            style="width: 120px; height: 120px;">
                            <i data-feather="user" style="width: 50px; height: 50px;"></i>
                        </div>
                    @endif
                    <h4 class="fw-bold mb-1">{{ $admin->name }}</h4>
                    <p class="text-muted mb-4">{{ $admin->email }}</p>

                    <div class="row text-start mt-4">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted d-block small fw-bold text-uppercase">Username</label>
                            <p class="fw-medium text-dark">{{ $admin->username }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted d-block small fw-bold text-uppercase">Phone</label>
                            <p class="fw-medium text-dark">{{ $admin->phone ?? 'N/A' }}</p>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label text-muted d-block small fw-bold text-uppercase">Address</label>
                            <p class="fw-medium text-dark">{{ $admin->address ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted d-block small fw-bold text-uppercase">Status</label>
                            @if (\Illuminate\Support\Facades\Cache::has('admin-is-online-' . $admin->id))
                                <div><span class="badge bg-success px-3 py-2"><i data-feather="activity" class="me-1"
                                            style="width: 14px; height: 14px;"></i> Online</span></div>
                            @else
                                <div><span class="badge bg-secondary px-3 py-2"><i data-feather="clock" class="me-1"
                                            style="width: 14px; height: 14px;"></i> Offline</span></div>
                            @endif
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted d-block small fw-bold text-uppercase">Joined Date</label>
                            <p class="fw-medium text-dark">
                                {{ $admin->created_at ? $admin->created_at->format('M d, Y') : 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
