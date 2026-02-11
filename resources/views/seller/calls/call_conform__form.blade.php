@extends('seller.layout.app')
@push('title')
    View Order
@endpush
@push('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>

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
                <h4 class="fw-bold">View Order</h4>
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
            <a href="{{route('call.index')}}" class="btn btn-secondary">View Orders List</a>
        </div>
    </div>
    <form action="{{route('call.store')}}" class="add-product-form" method="post" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="item_code" class="form-control" placeholder="Product Name"
               value="{{$order->item_code}}" required>
        <input type="hidden" name="id" value="{{$id}}">
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
                                <span>Order Information</span></h5>
                        </div>
                    </h2>
                    <div id="SpacingOne">
                        <div class="accordion-body border-top">
                            <div class="row">
                                <div class="col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Order Code<span
                                                    class="text-danger ms-1">*</span></label>
                                        <input type="text" name="order_code" class="form-control" required disabled
                                               value="{{$order->order_no}}">
                                    </div>
                                </div>
                                <div class="col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Product Name<span
                                                    class="text-danger ms-1">*</span></label>
                                        <input type="text" name="product_name" class="form-control"
                                               placeholder="Product Name" value="{{$order->item_name}}" required
                                               disabled>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Price<span class="text-danger ms-1">*</span></label>
                                        <input type="number" name="price" class="form-control" placeholder="price"
                                               value="{{$order->price ?? 0}}" required>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Qty<span class="text-danger ms-1">*</span></label>
                                        <input type="number" name="qty" class="form-control"
                                               value="{{$order->qty ?? 1}}" required>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Customer Name<span
                                                    class="text-danger ms-1">*</span></label>
                                        <input type="text" name="customer_name" class="form-control"
                                               placeholder="Customer Name" value="{{$order->customer_name}}" required>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Mobile No 01<span
                                                    class="text-danger ms-1">*</span></label>
                                        <input type="text" name="phone_01" class="form-control"
                                               placeholder="Mobile No 01" value="{{$order->customer_phone_01}}"
                                               required>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Mobile No 02<span
                                                    class="text-danger ms-1">*</span></label>
                                        <input type="text" name="phone_02" class="form-control"
                                               placeholder="Mobile No 02" value="{{$order->customer_phone_02}}"
                                               required>
                                    </div>
                                </div>


                                <div class="col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Address <span
                                                    class="text-danger ms-1">*</span></label>
                                        <input type="text" name="address" class="form-control" placeholder="Address"
                                               value="{{$order->customer_address}}" required>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">City <span class="text-danger ms-1">*</span></label>
                                        <select name="city" class="form-control select2" required>
                                            <option value="">Select City</option>
                                            @foreach($cities as $city)
                                                <option value="{{ $city->city }}" {{ strtolower($order->city) == strtolower($city->city) ? 'selected' : '' }}>
                                                    {{ $city->city }}
                                                </option>

                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Remark<span class="text-danger ms-1">*</span></label>
                                        <input type="text" name="remark" class="form-control" placeholder="Remark"
                                               value="{{$order->remark ?? ''}}">
                                    </div>
                                </div>
                                <div class="col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Status<span class="text-danger ms-1">*</span></label>
                                        <select class="form-control" name="status" required>
                                            <option value="Conform" {{ $order->status == 'Conform' ? 'selected' : '' }}>
                                                Conform
                                            </option>
                                            <option value="Not Answer" {{ $order->status == 'Not Answer' ? 'selected' : '' }}>
                                                Not Answer
                                            </option>
                                            <option value="Reject" {{ $order->status == 'Reject' ? 'selected' : '' }}>
                                                Reject
                                            </option>
                                            <option value="Other" {{ $order->status == 'Other' ? 'selected' : '' }}>
                                                Other
                                            </option>
                                        </select>

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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.select2').select2({
                placeholder: "Select a city",
                allowClear: true
            });
        });
    </script>

@endpush
