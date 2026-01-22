@extends('seller.layout.app')
@push('title')
    Withdraw
@endpush
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h3>Withdraw</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Withdraw Money</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-4">
                                <label>Available Balance</label>
                                <input type="number" class="form-control" name="availableBalance" disabled>
                            </div>
                            <div class="col-4">
                                <label>Withdraw Amount</label>
                                <input type="number" class="form-control" name="withdraw_amount"
                                       placeholder="Withdraw Amount">
                            </div>
                            <div class="col-4">
                                <label>Withdraw Bank</label>
                                <select name="bank" class="form-control">
                                    <option value="">Select Bank</option>
                                    <option value="">BOC</option>
                                    <option value="">NSB</option>
                                    <option value="">HNB</option>
                                </select>
                            </div>
                            <div class="col-4">
                                <label>Mobile No</label>
                                <input type="text" class="form-control" name="mobile_no" placeholder="Mobile No">
                            </div>
                            <div class="col-4">
                                <label>OTP</label>
                                <input type="text" class="form-control" name="otp" placeholder="OTP">
                            </div>
                            <div class="col-1">
                                <button class="btn btn-warning  mt-4">GET</button>
                            </div>
                        </div>
                        <div class="row mt-5">
                            <div class="col-12 d-flex justify-content-end">
                                <button class="btn btn-success">Request Withdraw</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')

@endpush
