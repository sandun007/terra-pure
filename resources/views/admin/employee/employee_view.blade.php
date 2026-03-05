@extends('admin.app')

@push('title')
    Employee Details - {{ $employee->name ?? 'Employee' }}
@endpush

@push('css')
    <style>
        .phone-img {
            position: relative;
            display: inline-block;
            margin: 12px;
            text-align: center;
        }
        .phone-img img {
            width: 180px;
            height: 180px;
            object-fit: cover;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.08);
        }
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
        .no-image-placeholder {
            width: 180px;
            height: 180px;
            background: #f1f3f5;
            border: 2px dashed #adb5bd;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            color: #6c757d;
            margin: 12px auto;
        }
        .bg-green {
            background-color: var(--main-theme-color) !important;
        }
    </style>
@endpush

@section('content')

    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4 class="fw-bold">Employee Details</h4>
            </div>
        </div>
        <ul class="table-top-head">
            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Refresh"
                   title="Refresh"><i class="ti ti-refresh"></i></a>
            </li>
            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" id="collapse-header" aria-label="Collapse"
                   title="Collapse"><i class="ti ti-chevron-up"></i></a>
            </li>
        </ul>
        <div class="page-btn mt-0">
            <a href="{{ route('employee.list') }}" class="btn btn-secondary me-2">Back to List</a>
        </div>
    </div>

    <div class="add-product">
        <div class="accordions-items-seperate" id="accordionSpacingExample">

            <!-- Employee Information -->
            <div class="accordion-item border mb-4">
                <h2 class="accordion-header" id="headingSpacingOne">
                    <div class="d-flex align-items-center justify-content-between flex-fill p-3">
                        <h5 class="d-flex align-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="feather feather-info text-primary me-2">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="16" x2="12" y2="12"></line>
                                <line x1="12" y1="8" x2="12.01" y2="8"></line>
                            </svg>
                            <span>Employee Information</span>
                        </h5>
                    </div>
                </h2>
                <div class="accordion-body border-top">
                    <div class="row">

                        <div class="col-sm-6 col-12">
                            <div class="mb-3">
                                <label class="form-label info-label">Employee Code</label>
                                <div class="view-field">{{ $employee->code ?? '—' }}</div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-12">
                            <div class="mb-3">
                                <label class="form-label info-label">Full Name</label>
                                <div class="view-field">{{ $employee->name ?? '—' }}</div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-12">
                            <div class="mb-3">
                                <label class="form-label info-label">NIC No</label>
                                <div class="view-field">{{ $employee->nic_no ?? '—' }}</div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-12">
                            <div class="mb-3">
                                <label class="form-label info-label">Mobile No</label>
                                <div class="view-field">{{ $employee->mobile_no ?? '—' }}</div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-12">
                            <div class="mb-3">
                                <label class="form-label info-label">Address</label>
                                <div class="view-field">{{ $employee->address ?? '—' }}</div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-12">
                            <div class="mb-3">
                                <label class="form-label info-label">District</label>
                                <div class="view-field">{{ $employee->district ?? '—' }}</div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- NIC Images -->
            <div class="accordion-item border mb-4">
                <h2 class="accordion-header" id="headingSpacingThree">
                    <div class="d-flex align-items-center justify-content-between flex-fill p-3">
                        <h5 class="d-flex align-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="feather feather-image text-primary me-2">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                <polyline points="21 15 16 10 5 21"></polyline>
                            </svg>
                            <span>NIC Images</span>
                        </h5>
                    </div>
                </h2>
                <div class="accordion-body border-top">
                    <div class="row justify-content-center">

                        <!-- NIC Front -->
                        <div class="col-md-6 col-12 text-center mb-4">
                            <h6 class="mb-3 fw-semibold">NIC Front</h6>
                            @if($employee->nic_front && Storage::disk('public')->exists($employee->nic_front))
                                <div class="phone-img">
                                    <img src="{{ Storage::url($employee->nic_front) }}"
                                         alt="NIC Front Image"
                                         onerror="this.src='/images/placeholder.jpg'; this.alt='Image failed to load';">
                                </div>
                            @else
                                <div class="no-image-placeholder">
                                    <i class="ti ti-photo-x" style="font-size: 3.5rem;"></i>
                                    <p class="mt-2 mb-0">No front image uploaded</p>
                                </div>
                            @endif
                        </div>

                        <!-- NIC Back -->
                        <div class="col-md-6 col-12 text-center mb-4">
                            <h6 class="mb-3 fw-semibold">NIC Back</h6>
                            @if($employee->nic_back && Storage::disk('public')->exists($employee->nic_back))
                                <div class="phone-img">
                                    <img src="{{ Storage::url($employee->nic_back) }}"
                                         alt="NIC Back Image"
                                         onerror="this.src='/images/placeholder.jpg'; this.alt='Image failed to load';">
                                </div>
                            @else
                                <div class="no-image-placeholder">
                                    <i class="ti ti-photo-x" style="font-size: 3.5rem;"></i>
                                    <p class="mt-2 mb-0">No back image uploaded</p>
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
