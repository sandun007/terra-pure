@extends('admin.app')
@push('title')
    Category View
@endpush

@section('content')
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4 class="fw-bold">View Category</h4>
                <h6>Details of category {{ $category->name }}</h6>
            </div>
        </div>
        <div class="page-btn mt-0">
            <a href="{{ route('category.index') }}" class="btn btn-secondary">Back to Category List</a>
            <a href="{{ route('category.edit', $category->id) }}" class="btn btn-warning">Edit Category</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Category Information</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <tbody>
                            <tr>
                                <th style="width: 35%;">Code</th>
                                <td>{{ $category->code }}</td>
                            </tr>
                            <tr>
                                <th>Name</th>
                                <td>{{ $category->name }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    <span class="{{ $category->status_badge }}">
                                        {{ $category->status_text }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Created By</th>
                                <td>{{ $category->add_by ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Updated By</th>
                                <td>{{ $category->update_by ?? 'N/A' }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Category Image</h5>
                    <div class="phone-img">
                        <img src="{{ $category->image_url }}" alt="{{ $category->name }}">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


