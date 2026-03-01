{{-- resources/views/wholesaler/calls/edit_call.blade.php --}}
@extends('wholesaler.layouts.app')

@push('title')
    Edit Call
@endpush

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <style>
        .page-header {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 25px 50px rgba(245, 158, 11, 0.3);
        }

        .form-card {
            border: none;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .card-header-custom {
            background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
            padding: 1.5rem 2rem;
            border: none;
        }

        .form-label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
        }

        .form-control, .form-select {
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: #f59e0b;
            box-shadow: 0 0 0 3px rgba(245,158,11,0.1);
        }

        .info-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(245,158,11,0.1);
            color: #f59e0b;
            padding: 0.5rem 1rem;
            border-radius: 12px;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .btn-modern {
            padding: 0.75rem 2rem;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(245,158,11,0.4);
        }

        .btn-secondary-custom {
            background: #6b7280;
            color: white;
        }

        .btn-secondary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(107,114,128,0.4);
        }

        .select2-container .select2-selection--single {
            height: 48px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
        }

        .select2-container .select2-selection--single .select2-selection__rendered {
            line-height: 44px;
            padding-left: 1rem;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 44px;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid px-3 px-md-4 px-lg-5 py-4">
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h4 class="mb-2">
                        <i class="fas fa-edit me-3"></i>Edit Call
                    </h4>
                    <p class="mb-0 opacity-75">Call #{{ $call->call_number ?? $call->order_code }}</p>
                </div>
                <a href="{{ route($call->status == 'Pending' ? 'wholesaler.call.index' : ($call->status == 'Conform' ? 'wholesaler.call.conform' : ($call->status == 'Reject' ? 'wholesaler.call.reject' : ($call->status == 'Not Answer' ? 'wholesaler.call.not_answer_call_list' : 'wholesaler.call.other_call_list')))) }}"
                   class="btn btn-light">
                    <i class="fas fa-arrow-left me-2"></i>Back to List
                </a>
            </div>
        </div>

        <form action="{{ route('wholesaler.call.update', $call->id) }}" method="POST" class="needs-validation" novalidate>
            @csrf
            @method('PUT')

            <div class="form-card mb-4">
                <div class="card-header-custom">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2 text-warning"></i>
                        Edit Call Information
                    </h5>
                </div>
                <div class="card-body p-4">
                    <!-- Call Details -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="info-badge">
                                <i class="fas fa-hashtag"></i>
                                <span>Call #: <strong>{{ $call->call_number ?? $call->order_code }}</strong></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-badge">
                                <i class="fas fa-calendar"></i>
                                <span>Date: <strong>{{ $call->date ? \Carbon\Carbon::parse($call->date)->format('M d, Y') : '-' }}</strong></span>
                            </div>
                        </div>
                    </div>

                    <!-- Product Information (Read-only) -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fas fa-box me-1"></i>Product Name
                            </label>
                            <input type="text" class="form-control" value="{{ $call->item_name }}" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fas fa-barcode me-1"></i>Product Code
                            </label>
                            <input type="text" class="form-control" value="{{ $call->item_code }}" disabled>
                        </div>
                    </div>

                    <!-- Customer Information (Editable) -->
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">
                                <i class="fas fa-user me-1"></i>Customer Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="customer_name" class="form-control @error('customer_name') is-invalid @enderror"
                                   value="{{ old('customer_name', $call->customer_name) }}" required>
                            @error('customer_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <i class="fas fa-phone me-1"></i>Phone Number 1 <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="customer_phone_01" class="form-control @error('customer_phone_01') is-invalid @enderror"
                                   value="{{ old('customer_phone_01', $call->customer_phone_01) }}" required>
                            @error('customer_phone_01')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <i class="fas fa-phone-alt me-1"></i>Phone Number 2
                            </label>
                            <input type="text" name="customer_phone_02" class="form-control"
                                   value="{{ old('customer_phone_02', $call->customer_phone_02) }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <i class="fas fa-map-marker-alt me-1"></i>Address
                            </label>
                            <input type="text" name="customer_address" class="form-control"
                                   value="{{ old('customer_address', $call->customer_address) }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <i class="fas fa-city me-1"></i>City
                            </label>
                            <select name="city" class="form-control select2 @error('city') is-invalid @enderror">
                                <option value="">Select City</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city->city }}"
                                        {{ old('city', $call->city) == $city->city ? 'selected' : '' }}>
                                        {{ $city->city }}
                                    </option>
                                @endforeach
                            </select>
                            @error('city')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">
                                <i class="fas fa-sort-numeric-up me-1"></i>Quantity <span class="text-danger">*</span>
                            </label>
                            <input type="number" name="qty" class="form-control @error('qty') is-invalid @enderror"
                                   value="{{ old('qty', $call->qty ?? 1) }}" min="1" required id="qty">
                            @error('qty')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">
                                <i class="fas fa-tag me-1"></i>Price (Rs.) <span class="text-danger">*</span>
                            </label>
                            <input type="number" name="price" class="form-control @error('price') is-invalid @enderror"
                                   value="{{ old('price', $call->price ?? 0) }}" step="0.01" min="0" required id="price">
                            @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">
                                <i class="fas fa-calculator me-1"></i>Total (Rs.)
                            </label>
                            <input type="text" class="form-control" id="total" value="{{ number_format($call->total ?? 0, 2) }}" readonly>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <i class="fas fa-check-circle me-1"></i>Status <span class="text-danger">*</span>
                            </label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror" required id="status">
                                <option value="Pending" {{ old('status', $call->status) == 'Pending' ? 'selected' : '' }}>
                                    Pending
                                </option>
                                <option value="Conform" {{ old('status', $call->status) == 'Conform' ? 'selected' : '' }}>
                                    Conform
                                </option>
                                <option value="Reject" {{ old('status', $call->status) == 'Reject' ? 'selected' : '' }}>
                                    Reject
                                </option>
                                <option value="Not Answer" {{ old('status', $call->status) == 'Not Answer' ? 'selected' : '' }}>
                                    Not Answer
                                </option>
                                <option value="Other" {{ old('status', $call->status) == 'Other' ? 'selected' : '' }}>
                                    Other
                                </option>
                                <option value="WhatsApp" {{ old('status', $call->status) == 'WhatsApp' ? 'selected' : '' }}>
                                    WhatsApp
                                </option>
                            </select>
                            @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <i class="fas fa-globe me-1"></i>Platform
                            </label>
                            <input type="text" class="form-control" value="{{ ucfirst($call->platform ?? 'N/A') }}" disabled>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">
                                <i class="fas fa-sticky-note me-1"></i>Remark
                            </label>
                            <textarea name="remark" class="form-control" rows="3"
                                      placeholder="Add any notes or comments...">{{ old('remark', $call->remark) }}</textarea>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-end gap-3 mt-4">
                        <a href="{{ route($call->status == 'Pending' ? 'wholesaler.call.index' : ($call->status == 'Conform' ? 'wholesaler.call.conform' : ($call->status == 'Reject' ? 'wholesaler.call.reject' : ($call->status == 'Not Answer' ? 'wholesaler.call.not_answer_call_list' : 'wholesaler.call.other_call_list')))) }}"
                           class="btn btn-modern btn-secondary-custom">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-modern btn-primary-custom">
                            <i class="fas fa-save me-2"></i>Update Call
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2({
                placeholder: "Select a city",
                allowClear: true
            });

            // Calculate total
            function calculateTotal() {
                const qty = parseFloat($('#qty').val()) || 0;
                const price = parseFloat($('#price').val()) || 0;
                const total = qty * price;
                $('#total').val(total.toFixed(2));
            }

            $('#qty, #price').on('input', calculateTotal);
            calculateTotal();

            // Form validation
            const forms = document.querySelectorAll('.needs-validation');
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });

            @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false
            });
            @endif

            @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '{{ session('error') }}',
                timer: 3000,
                showConfirmButton: false
            });
            @endif
        });
    </script>
@endpush
