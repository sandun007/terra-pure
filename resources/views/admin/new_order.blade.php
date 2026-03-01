@extends('admin.app')
@push('title')
 Create Order
@endpush
@push('css')
    <style>


        .pro-card {
            border-radius: 10px;
            padding: 20px;
            box-shadow: 5px 5px 10px  rgba(0,0,0,0.3);

        }
        .pro-row {
            padding: 10px;
        }



    </style>

@endpush
@section('content')
    <div class="page-header">
        <div class="d-flex">
            <div>
                <h4 class="">Create Order</h4>
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
    </div>

    <div class="row">


            <form action="" method="POST">
                @csrf
                <div class="row">
                    <div class="col-lg-8">
                        <div class="form-card bg-white p-4 mb-4 rounded-3 shadow-sm">
                            <div class="section-header border-bottom mb-3 pb-2 d-flex align-items-center">

                                <h5 class="mb-0">Add Order Information</h5>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label required">Product Id</label>
                                    <input type="text" name="product_id" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label required">Customer Name</label>
                                    <input type="text" name="customer_name" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label required">Phone Number 1</label>
                                    <input type="text" name="customer_phone1" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label required">Phone Number 2</label>
                                    <input type="text" name="customer_phone2" class="form-control" >
                                </div>


                                <div class="col-12 mb-3">
                                    <label class="form-label">Shipping Address</label>
                                    <textarea name="shipping_address" class="form-control" rows="3"></textarea>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label required">Remarks</label>
                                    <input type="text" name="remarks" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">District</label>
                                    <select class="form-control" name="district" required>
                                        <option value="">Select</option>
                                        <option>Ampara</option>
                                        <option>Anuradhapura</option>
                                        <option>Badulla</option>
                                        <option>Batticaloa</option>
                                        <option>Colombo</option>
                                        <option>Galle</option>
                                        <option>Gampaha</option>
                                        <option>Hambantota</option>
                                        <option>Jaffna</option>
                                        <option>Kalutara</option>
                                        <option>Kandy</option>
                                        <option>Kegalle</option>
                                        <option>Kilinochchi</option>
                                        <option>Kurunegala</option>
                                        <option>Mannar</option>
                                        <option>Matale</option>
                                        <option>Matara</option>
                                        <option>Monaragala</option>
                                        <option>Mullaitivu</option>
                                        <option>Nuwara Eliya</option>
                                        <option>Polonnaruwa</option>
                                        <option>Puttalam</option>
                                        <option>Ratnapura</option>
                                        <option>Trincomalee</option>
                                        <option>Vavuniya</option>
                                    </select>
                                </div>


                            </div>
                        </div>

                        <div class="form-card bg-white p-4 rounded-3 shadow-sm">
                            <div class="section-header border-bottom mb-3 pb-2 d-flex align-items-center">

                                <h5 class="mb-0">Additional Notes</h5>
                            </div>
                            <textarea name="notes" class="form-control" rows="4" placeholder="Any special notes..."></textarea>
                        </div>
                    </div>

                    <div class="col-lg-4 px-4">

                        <div class="pro-card card">
                        <div>
                            <div class="pro-row">
                                <span>Product Id:</span>
                                <span id="" ></span>
                            </div>
                            <div class="pro-row">
                                <span>Product Name:</span>
                                <span id=""></span>
                            </div>
                            <div class="pro-row">
                                <span>Brand:</span>
                                <span id=""></span>
                            </div>
                            <div class="pro-row">
                                <span>Product Price:</span>
                                <span id=""></span>
                            </div>
                            <div class="pro-row">
                                <span>Final Price:</span>
                                <span id=""></span>
                            </div>
                        </div>
                    </div>

                    </div>
                    <div class="col-lg-12">
                        <div class="d-flex align-items-center justify-content-end mb-4">
                            <button type="button" class="btn btn-secondary me-2" >Cancel</button>
                            <button type="submit" class="btn btn-primary" >Save</button>
                        </div>

                    </div>


                </div>

            </form>
















@endsection
