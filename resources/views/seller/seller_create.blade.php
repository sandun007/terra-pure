@extends('admin.app')
@push('title')
   Seller Create
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
                <h4 class="fw-bold">Create Seller</h4>
                <h6>Create new seller</h6>
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
            <a href="{{route('seller.list')}}" class="btn btn-secondary">View Seller List</a>
        </div>
    </div>
    <form action="{{route('admin.seller.store')}}" class="add-product-form" method="post" enctype="multipart/form-data">
        @csrf
        <div class="add-product">
            <div class="accordions-items-seperate" id="accordionSpacingExample">
                <div class="accordion-item border mb-4">
                    <h2 class="accordion-header " id="headingSpacingOne">
                            <div class="d-flex align-items-center justify-content-between flex-fill p-3">
                                <h5 class="d-flex align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                         stroke-linejoin="round" class="feather feather-info text-primary me-2">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <line x1="12" y1="16" x2="12" y2="12"></line>
                                        <line x1="12" y1="8" x2="12.01" y2="8"></line>
                                    </svg>
                                    <span>Seller Information</span></h5>
                            </div>
                    </h2>
                    <div id="SpacingOne">
                        <div class="accordion-body border-top">
                            <div class="row">
                                <div class="col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Seller Code<span
                                                class="text-danger ms-1">*</span></label>
                                        <input type="text" name="code" class="form-control" required disabled value="{{$code}}">
                                    </div>
                                </div>
                                <div class="col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Full Name<span
                                                class="text-danger ms-1">*</span></label>
                                        <input type="text" name="name" class="form-control" placeholder="Full Name" required>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">NIC No<span class="text-danger ms-1">*</span></label>
                                        <input type="text" name="nic_no" class="form-control" placeholder="NIC NO" required>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Mobile No<span
                                                class="text-danger ms-1">*</span></label>
                                        <input type="text" name="phone" class="form-control" placeholder="Mobile No 01" required>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Whatsapp<span class="text-danger ms-1">*</span></label>
                                        <input type="text" name="whatsapp" class="form-control" placeholder="Whatsapp No" required>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Email<span class="text-danger ms-1">*</span></label>
                                        <input type="email" name="email" class="form-control" placeholder="Email" required>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Address <span class="text-danger ms-1">*</span></label>
                                        <input type="text" name="address" class="form-control" placeholder="Address" required>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">City<span class="text-danger ms-1">*</span></label>
                                        <input type="text" name="city" class="form-control" placeholder="City" required>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Postal Code<span class="text-danger ms-1">*</span></label>
                                        <input type="text" name="postal_code" class="form-control" placeholder="Postal Code" required>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Username<span class="text-danger ms-1">*</span></label>
                                        <input type="text" name="username" class="form-control" placeholder="Username" required>
                                    </div>
                                </div><div class="col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Password<span class="text-danger ms-1">*</span></label>
                                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Deposit Amount<span class="text-danger ms-1">*</span></label>
                                        <input type="number" name="deposit" class="form-control" placeholder="Deposit Amount" required>
                                    </div>
                                </div>
                                   <div class="col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Seller Type<span class="text-danger ms-1">*</span></label>
                                        <select class="form-control" name="type" required>
                                            <option value="self_seller">Self Seller</option>
                                            <option value="system_seller">System Seller</option>
                                        </select>
                                    </div>
                                </div>
                                 <div class="col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Referral Code<span class="text-danger ms-1">*</span></label>
                                        <input type="text" name="ref_code" class="form-control" placeholder="Referral Code" value="0" required>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-12">
    <div class="mb-3">
        <label class="form-label">District</label>
        <select class="form-control" name="district" required>
            <option value="">Select</option>
            <option value="Ampara">Ampara</option>
            <option value="Anuradhapura">Anuradhapura</option>
            <option value="Badulla">Badulla</option>
            <option value="Batticaloa">Batticaloa</option>
            <option value="Colombo">Colombo</option>
            <option value="Galle">Galle</option>
            <option value="Gampaha">Gampaha</option>
            <option value="Hambantota">Hambantota</option>
            <option value="Jaffna">Jaffna</option>
            <option value="Kalutara">Kalutara</option>
            <option value="Kandy">Kandy</option>
            <option value="Kegalle">Kegalle</option>
            <option value="Kilinochchi">Kilinochchi</option>
            <option value="Kurunegala">Kurunegala</option>
            <option value="Mannar">Mannar</option>
            <option value="Matale">Matale</option>
            <option value="Matara">Matara</option>
            <option value="Monaragala">Monaragala</option>
            <option value="Mullaitivu">Mullaitivu</option>
            <option value="Nuwara Eliya">Nuwara Eliya</option>
            <option value="Polonnaruwa">Polonnaruwa</option>
            <option value="Puttalam">Puttalam</option>
            <option value="Ratnapura">Ratnapura</option>
            <option value="Trincomalee">Trincomalee</option>
            <option value="Vavuniya">Vavuniya</option>
        </select>
    </div>
</div>

                            </div>
                        </div>
                    </div>
                </div>
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
                                    <span>NIC Image</span></h5>
                            </div>
                    </h2>
                    <div class="row">
                        <div class="col-6">
                            <div id="NicFront">
                                <div class="accordion-body border-top">
                                    <div class="text-editor add-list add">
                                        <div class="col-lg-12">
                                            <div class="add-choosen">
                                                <div class="mb-3">
                                                    <div class="image-upload image-upload-two">
                                                        <input type="file" id="nicFront" name="nic_front" multiple required>
                                                        <div class="image-uploads">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                                 viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                                 class="feather feather-plus-circle plus-down-add me-0">
                                                                <circle cx="12" cy="12" r="10"></circle>
                                                                <line x1="12" y1="8" x2="12" y2="16"></line>
                                                                <line x1="8" y1="12" x2="16" y2="12"></line>
                                                            </svg>
                                                            <h4>NIC Front</h4>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Preview images will appear here -->
                                                <div id="nicFrontPreview" class="preview-container">
                                                    <!-- Dynamically generated previews -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div id="NicFBack">
                                <div class="accordion-body border-top">
                                    <div class="text-editor add-list add">
                                        <div class="col-lg-12">
                                            <div class="add-choosen">
                                                <div class="mb-3">
                                                    <div class="image-upload image-upload-two">
                                                        <input type="file" id="nicBack" name="nic_back" multiple required>
                                                        <div class="image-uploads">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                                 viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                                 class="feather feather-plus-circle plus-down-add me-0">
                                                                <circle cx="12" cy="12" r="10"></circle>
                                                                <line x1="12" y1="8" x2="12" y2="16"></line>
                                                                <line x1="8" y1="12" x2="16" y2="12"></line>
                                                            </svg>
                                                            <h4>NIC Front</h4>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Preview images will appear here -->
                                                <div id="nicBackPreview" class="preview-container">
                                                    <!-- Dynamically generated previews -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="d-flex align-items-center justify-content-end mb-4">
                <button type="button" class="btn btn-secondary me-2" fdprocessedid="2bjap">Cancel</button>
                <button type="submit" class="btn btn-primary" fdprocessedid="to0jbb">Save</button>
            </div>
        </div>
    </form>
@endsection
@push('script')
    <script>
        document.getElementById('nicFront').addEventListener('change', function (event) {
            const files = event.target.files;
            const previewContainer = document.getElementById('nicFrontPreview');

            // Clear previous previews
            previewContainer.innerHTML = '';

            for (const file of files) {
                if (!file.type.startsWith('image/')) continue;

                const reader = new FileReader();
                reader.onload = function (e) {
                    const imgDiv = document.createElement('div');
                    imgDiv.className = 'phone-img';

                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.alt = 'Uploaded image';

                    const removeBtn = document.createElement('a');
                    removeBtn.href = 'javascript:void(0);';
                    removeBtn.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                     viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     class="feather feather-x x-square-add remove-product">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            `;

                    removeBtn.onclick = () => imgDiv.remove();

                    imgDiv.appendChild(img);
                    imgDiv.appendChild(removeBtn);
                    previewContainer.appendChild(imgDiv);
                };
                reader.readAsDataURL(file);
            }
        });
    </script>

    <script>
        document.getElementById('nicBack').addEventListener('change', function (event) {
            const files = event.target.files;
            const previewContainer = document.getElementById('nicBackPreview');

            // Clear previous previews
            previewContainer.innerHTML = '';

            for (const file of files) {
                if (!file.type.startsWith('image/')) continue;

                const reader = new FileReader();
                reader.onload = function (e) {
                    const imgDiv = document.createElement('div');
                    imgDiv.className = 'phone-img';

                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.alt = 'Uploaded image';

                    const removeBtn = document.createElement('a');
                    removeBtn.href = 'javascript:void(0);';
                    removeBtn.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                     viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     class="feather feather-x x-square-add remove-product">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            `;

                    removeBtn.onclick = () => imgDiv.remove();

                    imgDiv.appendChild(img);
                    imgDiv.appendChild(removeBtn);
                    previewContainer.appendChild(imgDiv);
                };
                reader.readAsDataURL(file);
            }
        });
    </script>

@endpush
