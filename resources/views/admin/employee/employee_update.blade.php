@extends('admin.app')

@push('title')
    Edit Employee - {{ $employee->name ?? 'Employee' }}
@endpush

@push('css')
    <style>
        .info-label {
            font-weight: 600;
            color: #495057;
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
                <h4 class="fw-bold">Edit Employee</h4>
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
        <form action="{{ route('employee.update', $employee->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="accordions-items-seperate" id="accordionSpacingExample">

                {{-- Employee Information  --}}
                <div class="accordion-item border mb-4">
                    <h2 class="accordion-header">
                        <div class="d-flex align-items-center justify-content-between flex-fill p-3">
                            <h5 class="d-flex align-items-center mb-0">
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

                            {{-- Name --}}
                            <div class="col-sm-6 col-12">
                                <div class="mb-3">
                                    <label for="name" class="form-label info-label">Full Name <span class="text-danger">*</span></label>
                                    <input type="text"
                                           id="name" name="name"
                                           class="form-control @error('name') is-invalid @enderror"
                                           value="{{ old('name', $employee->name) }}"
                                           placeholder="Enter full name" required>
                                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            {{-- Email --}}
                            <div class="col-sm-6 col-12">
                                <div class="mb-3">
                                    <label for="email" class="form-label info-label">Email <span class="text-danger">*</span></label>
                                    <input type="email"
                                           id="email" name="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           value="{{ old('email', $employee->email) }}"
                                           placeholder="Enter email address" required>
                                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            {{-- Phone --}}
                            <div class="col-sm-6 col-12">
                                <div class="mb-3">
                                    <label for="phone" class="form-label info-label">Phone <span class="text-danger">*</span></label>
                                    <input type="text"
                                           id="phone" name="phone"
                                           class="form-control @error('phone') is-invalid @enderror"
                                           value="{{ old('phone', $employee->mobile_no) }}"
                                           placeholder="Enter phone number" required>
                                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            {{-- WhatsApp --}}
                            <div class="col-sm-6 col-12">
                                <div class="mb-3">
                                    <label for="whatsapp" class="form-label info-label">WhatsApp <span class="text-danger">*</span></label>
                                    <input type="text"
                                           id="whatsapp" name="whatsapp"
                                           class="form-control @error('whatsapp') is-invalid @enderror"
                                           value="{{ old('whatsapp', $employee->whatsapp_no) }}"
                                           placeholder="Enter WhatsApp number" required>
                                    @error('whatsapp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            {{-- NIC No --}}
                            <div class="col-sm-6 col-12">
                                <div class="mb-3">
                                    <label for="nic_no" class="form-label info-label">NIC No <span class="text-danger">*</span></label>
                                    <input type="text"
                                           id="nic_no" name="nic_no"
                                           class="form-control @error('nic_no') is-invalid @enderror"
                                           value="{{ old('nic_no', $employee->nic_no) }}"
                                           placeholder="Enter NIC number" required>
                                    @error('nic_no')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            {{-- District --}}
                            <div class="col-sm-6 col-12">
                                <div class="mb-3">
                                    <label for="district" class="form-label info-label">District <span class="text-danger">*</span></label>
                                    <input type="text"
                                           id="district" name="district"
                                           class="form-control @error('district') is-invalid @enderror"
                                           value="{{ old('district', $employee->district) }}"
                                           placeholder="Enter district" required>
                                    @error('district')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            {{-- Address --}}
                            <div class="col-sm-6 col-12">
                                <div class="mb-3">
                                    <label for="address" class="form-label info-label">Address <span class="text-danger">*</span></label>
                                    <textarea id="address" name="address"
                                              class="form-control @error('address') is-invalid @enderror"
                                              rows="3" placeholder="Enter address" required>{{ old('address', $employee->address) }}</textarea>
                                    @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            {{-- Postal Code --}}
                            <div class="col-sm-6 col-12">
                                <div class="mb-3">
                                    <label for="postal_code" class="form-label info-label">Postal Code <span class="text-danger">*</span></label>
                                    <input type="text"
                                           id="postal_code" name="postal_code"
                                           class="form-control @error('postal_code') is-invalid @enderror"
                                           value="{{ old('postal_code', $employee->post_code) }}"
                                           placeholder="Enter postal code" required>
                                    @error('postal_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- Account & Access  --}}
                <div class="accordion-item border mb-4">
                    <h2 class="accordion-header">
                        <div class="d-flex align-items-center justify-content-between flex-fill p-3">
                            <h5 class="d-flex align-items-center mb-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" class="feather feather-lock text-primary me-2">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                </svg>
                                <span>Account &amp; Access</span>
                            </h5>
                        </div>
                    </h2>
                    <div class="accordion-body border-top">
                        <div class="row">

                            {{-- Username --}}
                            <div class="col-sm-6 col-12">
                                <div class="mb-3">
                                    <label for="username" class="form-label info-label">Username <span class="text-danger">*</span></label>
                                    <input type="text"
                                           id="username" name="username"
                                           class="form-control @error('username') is-invalid @enderror"
                                           value="{{ old('username', $employee->username) }}"
                                           placeholder="Enter username" required>
                                    @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            {{-- Password --}}
                            <div class="col-sm-6 col-12">
                                <div class="mb-3">
                                    <label for="password" class="form-label info-label">
                                        Password
                                        <small class="text-muted fw-normal">(leave blank to keep current)</small>
                                    </label>
                                    <div class="input-group">
                                        <input type="password"
                                               id="password" name="password"
                                               class="form-control @error('password') is-invalid @enderror"
                                               placeholder="Enter new password (min 8 chars)"
                                               autocomplete="new-password">
                                        <button class="btn btn-outline-secondary" type="button"
                                                onclick="togglePassword()">
                                            <i class="ti ti-eye" id="pwd-eye-icon"></i>
                                        </button>
                                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Role --}}
                            <div class="col-sm-6 col-12">
                                <div class="mb-3">
                                    <label for="role" class="form-label info-label">Role <span class="text-danger">*</span></label>
                                    <select id="role" name="role"
                                            class="form-select @error('role') is-invalid @enderror" required>
                                        <option value="">— Select Role —</option>
                                        <option value="admin"   {{ old('role', $employee->role) === 'admin'   ? 'selected' : '' }}>Admin</option>
                                        <option value="manager" {{ old('role', $employee->role) === 'manager' ? 'selected' : '' }}>Manager</option>
                                        <option value="staff"   {{ old('role', $employee->role) === 'staff'   ? 'selected' : '' }}>Staff</option>
                                    </select>
                                    @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            {{-- Status --}}
                            <div class="col-sm-6 col-12">
                                <div class="mb-3">
                                    <label for="status" class="form-label info-label">Status <span class="text-danger">*</span></label>
                                    <select id="status" name="status"
                                            class="form-select @error('status') is-invalid @enderror" required>
                                        <option value="">— Select Status —</option>
                                        <option value="active"   {{ old('status', $employee->status) === 'active'   ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status', $employee->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- NIC Images  --}}
                <div class="accordion-item border mb-4">
                    <h2 class="accordion-header">
                        <div class="d-flex align-items-center justify-content-between flex-fill p-3">
                            <h5 class="d-flex align-items-center mb-0">
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

                            {{-- NIC Front --}}
                            <div class="col-md-6 col-12 text-center mb-4">
                                <h6 class="mb-3 fw-semibold">NIC Front</h6>

                                @if($employee->nic_front && Storage::disk('public')->exists($employee->nic_front))
                                    <img id="nic_front_preview"
                                         src="{{ Storage::url($employee->nic_front) }}"
                                         alt="NIC Front"
                                         style="width:180px;height:180px;object-fit:cover;border:1px solid #dee2e6;border-radius:8px;box-shadow:0 2px 6px rgba(0,0,0,.08);margin-bottom:10px;">
                                @else
                                    <div id="nic_front_placeholder" class="no-image-placeholder">
                                        <i class="ti ti-photo-x" style="font-size:3.5rem;"></i>
                                        <p class="mt-2 mb-0">No front image uploaded</p>
                                    </div>
                                    <img id="nic_front_preview" src="" alt="NIC Front Preview"
                                         style="display:none;width:180px;height:180px;object-fit:cover;border:1px solid #dee2e6;border-radius:8px;box-shadow:0 2px 6px rgba(0,0,0,.08);margin:0 auto 10px;">
                                @endif

                                <label for="nic_front" class="form-label text-muted small d-block">
                                    Upload new image to replace (JPEG, PNG, GIF — max 2 MB)
                                </label>
                                <input type="file"
                                       id="nic_front" name="nic_front"
                                       class="form-control @error('nic_front') is-invalid @enderror"
                                       accept="image/jpeg,image/png,image/jpg,image/gif"
                                       onchange="previewImage(this, 'nic_front_preview', 'nic_front_placeholder')">
                                @error('nic_front')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            {{-- NIC Back --}}
                            <div class="col-md-6 col-12 text-center mb-4">
                                <h6 class="mb-3 fw-semibold">NIC Back</h6>

                                @if($employee->nic_back && Storage::disk('public')->exists($employee->nic_back))
                                    <img id="nic_back_preview"
                                         src="{{ Storage::url($employee->nic_back) }}"
                                         alt="NIC Back"
                                         style="width:180px;height:180px;object-fit:cover;border:1px solid #dee2e6;border-radius:8px;box-shadow:0 2px 6px rgba(0,0,0,.08);margin-bottom:10px;">
                                @else
                                    <div id="nic_back_placeholder" class="no-image-placeholder">
                                        <i class="ti ti-photo-x" style="font-size:3.5rem;"></i>
                                        <p class="mt-2 mb-0">No back image uploaded</p>
                                    </div>
                                    <img id="nic_back_preview" src="" alt="NIC Back Preview"
                                         style="display:none;width:180px;height:180px;object-fit:cover;border:1px solid #dee2e6;border-radius:8px;box-shadow:0 2px 6px rgba(0,0,0,.08);margin:0 auto 10px;">
                                @endif

                                <label for="nic_back" class="form-label text-muted small d-block">
                                    Upload new image to replace (JPEG, PNG, GIF — max 2 MB)
                                </label>
                                <input type="file"
                                       id="nic_back" name="nic_back"
                                       class="form-control @error('nic_back') is-invalid @enderror"
                                       accept="image/jpeg,image/png,image/jpg,image/gif"
                                       onchange="previewImage(this, 'nic_back_preview', 'nic_back_placeholder')">
                                @error('nic_back')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                        </div>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="d-flex justify-content-end gap-2 mb-4">
                    <a href="{{ route('employee.list') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary bg-green border-0">
                        <i class="ti ti-device-floppy me-1"></i> Save Changes
                    </button>
                </div>

            </div>
        </form>
    </div>

@endsection

@push('js')
    <script>
        // Live image preview when a new file is selected
        function previewImage(input, previewId, placeholderId) {
            const preview     = document.getElementById(previewId);
            const placeholder = placeholderId ? document.getElementById(placeholderId) : null;

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src           = e.target.result;
                    preview.style.display = 'block';
                    if (placeholder) placeholder.style.display = 'none';
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Toggle password visibility
        function togglePassword() {
            const input   = document.getElementById('password');
            const icon    = document.getElementById('pwd-eye-icon');
            const visible = input.type === 'text';
            input.type    = visible ? 'password' : 'text';
            icon.classList.toggle('ti-eye',      visible);
            icon.classList.toggle('ti-eye-off', !visible);
        }
    </script>
@endpush
