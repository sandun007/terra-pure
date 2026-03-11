@extends('admin.app')
@push('title')
    Wholesaler Create
@endpush
@push('css')
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
        .bg-green {
            background-color: var(--main-theme-color) !important;
        }
    </style>
@endpush

@section('content')
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4 class="fw-bold">Wholesaler Create</h4>
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
            <a href="{{ route('admin.wholesaler.list') }}" class="btn btn-secondary">View Wholesaler List</a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('admin.wholesaler.store') }}" class="add-product-form" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="add-product">
            <div class="accordions-items-seperate" id="accordionSpacingExample">

                {{-- ── Section 1: Wholesaler Information ── --}}
                <div class="accordion-item border mb-4">
                    <h2 class="accordion-header" id="headingSpacingOne">
                        <div class="d-flex align-items-center justify-content-between flex-fill p-3">
                            <h5 class="d-flex align-items-center mb-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" class="feather feather-info text-primary me-2">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="12" y1="16" x2="12" y2="12"></line>
                                    <line x1="12" y1="8" x2="12.01" y2="8"></line>
                                </svg>
                                <span>Wholesaler Information</span>
                            </h5>
                        </div>
                    </h2>
                    <div id="SpacingOne">
                        <div class="accordion-body border-top">
                            <div class="row">

                                <div class="col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Wholesaler Code <span class="text-danger ms-1">*</span></label>
                                        <input type="text" name="code" class="form-control"
                                               value="{{ $code }}" readonly required>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Full Name <span class="text-danger ms-1">*</span></label>
                                        <input type="text" name="name" class="form-control"
                                               placeholder="Full Name" value="{{ old('name') }}" required>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Business Name</label>
                                        <input type="text" name="business_name" class="form-control"
                                               placeholder="Business Name" value="{{ old('business_name') }}">
                                    </div>
                                </div>

                                <div class="col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Type</label>
                                        <select name="type" class="form-control">
                                            <option value="">Select Type</option>
                                            <option value="retailer"    {{ old('type') === 'retailer'    ? 'selected' : '' }}>Retailer</option>
                                            <option value="distributor" {{ old('type') === 'distributor' ? 'selected' : '' }}>Distributor</option>
                                            <option value="wholesaler"  {{ old('type') === 'wholesaler'  ? 'selected' : '' }}>Wholesaler</option>
                                            <option value="other"       {{ old('type') === 'other'       ? 'selected' : '' }}>Other</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Phone No</label>
                                        <input type="text" name="phone" class="form-control"
                                               placeholder="Phone No" value="{{ old('phone') }}">
                                    </div>
                                </div>

                                <div class="col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">WhatsApp</label>
                                        <input type="text" name="whatsapp" class="form-control"
                                               placeholder="WhatsApp No" value="{{ old('whatsapp') }}">
                                    </div>
                                </div>

                                <div class="col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control"
                                               placeholder="Email" value="{{ old('email') }}">
                                    </div>
                                </div>

                                <div class="col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Address</label>
                                        <input type="text" name="address" class="form-control"
                                               placeholder="Address" value="{{ old('address') }}">
                                    </div>
                                </div>

                                <div class="col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">City</label>
                                        <input type="text" name="city" class="form-control"
                                               placeholder="City" value="{{ old('city') }}">
                                    </div>
                                </div>

                                <div class="col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Postal Code</label>
                                        <input type="text" name="postal_code" class="form-control"
                                               placeholder="Postal Code" value="{{ old('postal_code') }}">
                                    </div>
                                </div>
                                {{-- Stock --}}
                                <div class="col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Stock</label>
                                        <input type="text" name="stock" class="form-control"
                                               placeholder="Stock" value="{{ old('stock') }}">

                                    </div>
                                </div>

                                <div class="col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">District</label>
                                        <select name="district" class="form-control">
                                            <option value="">Select District</option>
                                            @foreach(['Ampara','Anuradhapura','Badulla','Batticaloa','Colombo','Galle','Gampaha','Hambantota','Jaffna','Kalutara','Kandy','Kegalle','Kilinochchi','Kurunegala','Mannar','Matale','Matara','Monaragala','Mullaitivu','Nuwara Eliya','Polonnaruwa','Puttalam','Ratnapura','Trincomalee','Vavuniya'] as $dist)
                                                <option value="{{ $dist }}" {{ old('district') === $dist ? 'selected' : '' }}>
                                                    {{ $dist }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                {{-- NIC No --}}
                                <div class="col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">NIC No</label>
                                        <input type="text" name="nic_no" class="form-control"
                                               placeholder="NIC No" value="{{ old('nic_no') }}">
                                    </div>
                                </div>
                                <div class="col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Username <span class="text-danger ms-1">*</span></label>
                                        <input type="text" name="username" class="form-control"
                                               placeholder="Username" value="{{ old('username') }}" required>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Password <span class="text-danger ms-1">*</span></label>
                                        <input type="password" name="password" class="form-control"
                                               placeholder="Password" required>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── Section 2: Profile Image ── --}}
                <div class="accordion-item border mb-4">
                    <h2 class="accordion-header" id="headingSpacingTwo">
                        <div class="d-flex align-items-center justify-content-between flex-fill p-3">
                            <h5 class="d-flex align-items-center mb-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                     stroke-linecap="round" stroke-linejoin="round"
                                     class="feather feather-image text-primary me-2">
                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                    <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                    <polyline points="21 15 16 10 5 21"></polyline>
                                </svg>
                                <span>Profile Image</span>
                            </h5>
                        </div>
                    </h2>
                    <div class="accordion-body border-top">
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <div class="image-upload image-upload-two">
                                        <input type="file" id="profileImg" name="img" accept="image/*">
                                        <div class="image-uploads">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                 viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                 class="feather feather-plus-circle plus-down-add me-0">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <line x1="12" y1="8" x2="12" y2="16"></line>
                                                <line x1="8" y1="12" x2="16" y2="12"></line>
                                            </svg>
                                            <h4>Upload Profile Image</h4>
                                        </div>
                                    </div>
                                </div>
                                <div id="profileImgPreview" class="preview-container"></div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="col-lg-12">
            <div class="d-flex align-items-center justify-content-end mb-4">
                <a href="{{ route('admin.wholesaler.list') }}" class="btn btn-secondary me-2">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Wholesaler</button>
            </div>
        </div>

    </form>
@endsection

@push('script')
    <script>
        document.getElementById('profileImg').addEventListener('change', function (event) {
            const files = event.target.files;
            const previewContainer = document.getElementById('profileImgPreview');
            previewContainer.innerHTML = '';

            for (const file of files) {
                if (!file.type.startsWith('image/')) continue;

                const reader = new FileReader();
                reader.onload = function (e) {
                    const imgDiv = document.createElement('div');
                    imgDiv.className = 'phone-img';

                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.alt = 'Profile Preview';

                    const removeBtn = document.createElement('a');
                    removeBtn.href = 'javascript:void(0);';
                    removeBtn.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                             viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="feather feather-x x-square-add remove-product">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>`;
                    removeBtn.onclick = () => {
                        imgDiv.remove();
                        document.getElementById('profileImg').value = '';
                    };

                    imgDiv.appendChild(img);
                    imgDiv.appendChild(removeBtn);
                    previewContainer.appendChild(imgDiv);
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
@endpush
