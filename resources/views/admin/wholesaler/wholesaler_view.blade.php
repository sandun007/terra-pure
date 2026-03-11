@extends('admin.app')

@push('title')
    Wholesaler Details - {{ $wholesaler->name ?? 'Wholesaler' }}
@endpush

@push('css')
    <style>
        .info-label {
            font-weight: 600;
            min-width: 160px;
            display: inline-block;
            color: #495057;
        }
        .view-field {
            padding: 8px 12px;
            background: #f8f9fa;
            border-radius: 6px;
            margin-bottom: 12px;
            word-break: break-word;
        }
        .profile-img-wrap { text-align: center; margin-bottom: 1.5rem; }
        .profile-img-wrap img {
            width: 120px; height: 120px;
            object-fit: cover; border-radius: 50%;
            border: 3px solid #dee2e6;
            box-shadow: 0 2px 8px rgba(0,0,0,0.10);
        }
        .no-image-placeholder {
            width: 180px; height: 180px;
            background: #f1f3f5;
            border: 2px dashed #adb5bd;
            border-radius: 8px;
            display: flex; align-items: center;
            justify-content: center; flex-direction: column;
            color: #6c757d; margin: 12px auto;
        }
    </style>
@endpush

@section('content')

    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4 class="fw-bold">Wholesaler Details</h4>
            </div>
        </div>
        <ul class="table-top-head">
            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh">
                    <i class="ti ti-refresh"></i>
                </a>
            </li>
            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" id="collapse-header" title="Collapse">
                    <i class="ti ti-chevron-up"></i>
                </a>
            </li>
        </ul>
        <div class="page-btn mt-0">
            <a href="{{ route('admin.wholesaler.edit', $wholesaler->id) }}" class="btn btn-warning me-2">
                <i class="fas fa-edit me-1"></i> Edit
            </a>
            <a href="{{ route('admin.wholesaler.list') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to List
            </a>
        </div>
    </div>

    <div class="add-product">
        <div class="accordions-items-seperate">

            {{-- Profile Image --}}
            <div class="accordion-item border mb-4">
                <h2 class="accordion-header">
                    <div class="d-flex align-items-center flex-fill p-3">
                        <h5 class="d-flex align-items-center mb-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="text-primary me-2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                            Profile Image
                        </h5>
                    </div>
                </h2>
                <div class="accordion-body border-top">
                    <div class="profile-img-wrap">
                        @if($wholesaler->img)
                            <img src="{{ asset('storage/' . $wholesaler->img) }}"
                                 alt="{{ $wholesaler->name }}"
                                 onerror="this.src='/asset/img/default-avatar.png'">
                        @else
                            <div class="no-image-placeholder mx-auto">
                                <i class="ti ti-user" style="font-size: 3.5rem;"></i>
                                <p class="mt-2 mb-0">No profile image</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Basic Information --}}
            <div class="accordion-item border mb-4">
                <h2 class="accordion-header">
                    <div class="d-flex align-items-center flex-fill p-3">
                        <h5 class="d-flex align-items-center mb-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="text-primary me-2">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="12" y1="16" x2="12" y2="12"/>
                                <line x1="12" y1="8" x2="12.01" y2="8"/>
                            </svg>
                            Basic Information
                        </h5>
                    </div>
                </h2>
                <div class="accordion-body border-top">
                    <div class="row">
                        <div class="col-sm-6 col-12">
                            <label class="form-label info-label">Wholesaler Code</label>
                            <div class="view-field">{{ $wholesaler->code ?? '—' }}</div>
                        </div>
                        <div class="col-sm-6 col-12">
                            <label class="form-label info-label">Full Name</label>
                            <div class="view-field">{{ $wholesaler->name ?? '—' }}</div>
                        </div>
                        <div class="col-sm-6 col-12">
                            <label class="form-label info-label">Business Name</label>
                            <div class="view-field">{{ $wholesaler->business_name ?? '—' }}</div>
                        </div>
                        <div class="col-sm-6 col-12">
                            <label class="form-label info-label">Type</label>
                            <div class="view-field">
                                @if($wholesaler->type)
                                    <span class="badge bg-info text-dark px-3 py-2">{{ $wholesaler->type }}</span>
                                @else —
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-6 col-12">
                            <label class="form-label info-label">Username</label>
                            <div class="view-field">{{ $wholesaler->username ?? '—' }}</div>
                        </div>
                        <div class="col-sm-6 col-12">
                            <label class="form-label info-label">Email</label>
                            <div class="view-field">{{ $wholesaler->email ?? '—' }}</div>
                        </div>
                        <div class="col-sm-6 col-12">
                            <label class="form-label info-label">Phone</label>
                            <div class="view-field">{{ $wholesaler->phone ?? '—' }}</div>
                        </div>
                        <div class="col-sm-6 col-12">
                            <label class="form-label info-label">WhatsApp</label>
                            <div class="view-field">{{ $wholesaler->whatsapp ?? '—' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Address Information --}}
            <div class="accordion-item border mb-4">
                <h2 class="accordion-header">
                    <div class="d-flex align-items-center flex-fill p-3">
                        <h5 class="d-flex align-items-center mb-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="text-primary me-2">
                                <path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0z"/>
                                <circle cx="12" cy="10" r="3"/>
                            </svg>
                            Address Information
                        </h5>
                    </div>
                </h2>
                <div class="accordion-body border-top">
                    <div class="row">
                        <div class="col-12">
                            <label class="form-label info-label">Address</label>
                            <div class="view-field">{{ $wholesaler->address ?? '—' }}</div>
                        </div>
                        <div class="col-sm-6 col-12">
                            <label class="form-label info-label">City</label>
                            <div class="view-field">{{ $wholesaler->city ?? '—' }}</div>
                        </div>
                        <div class="col-sm-6 col-12">
                            <label class="form-label info-label">Postal Code</label>
                            <div class="view-field">{{ $wholesaler->postal_code ?? '—' }}</div>
                        </div>
                        <div class="col-sm-6 col-12">
                            <label class="form-label info-label">District</label>
                            <div class="view-field">{{ $wholesaler->district ?? '—' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Account Information --}}
            <div class="accordion-item border mb-4">
                <h2 class="accordion-header">
                    <div class="d-flex align-items-center flex-fill p-3">
                        <h5 class="d-flex align-items-center mb-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="text-primary me-2">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                            </svg>
                            Account Information
                        </h5>
                    </div>
                </h2>
                <div class="accordion-body border-top">
                    <div class="row">
                        <div class="col-sm-6 col-12">
                            <label class="form-label info-label">Registered At</label>
                            <div class="view-field">
                                {{ $wholesaler->created_at ? $wholesaler->created_at->format('d M Y, h:i A') : '—' }}
                            </div>
                        </div>
                        <div class="col-sm-6 col-12">
                            <label class="form-label info-label">Last Updated</label>
                            <div class="view-field">
                                {{ $wholesaler->updated_at ? $wholesaler->updated_at->format('d M Y, h:i A') : '—' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
