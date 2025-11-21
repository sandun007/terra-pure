@extends('wholesaler.app')
@push('title')
    Edit Shop
@endpush

@section('content')
    <div class="dashboard-container">
        <div class="dashboard-header">
            <div class="header-content">
                <div class="header-title">
                    <h1>🏪 Edit Shop</h1>
                    <p>Update your shop information and settings</p>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="dashboard-section">
                    <div class="section-header">
                        <h3 class="section-title">Shop Settings</h3>
                        <a href="{{ route('wholesaler.shop') }}" class="view-all-link">
                            <i class="fas fa-arrow-left me-1"></i> Back to Shop
                        </a>
                    </div>

                    <form action="{{ route('wholesaler.shop.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="shop_name" class="form-label">Shop Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('shop_name') is-invalid @enderror"
                                           id="shop_name" name="shop_name"
                                           value="{{ old('shop_name', $shop->shop_name ?? '') }}"
                                           placeholder="Enter your shop name" required>
                                    @error('shop_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="contact_email" class="form-label">Contact Email</label>
                                    <input type="email" class="form-control @error('contact_email') is-invalid @enderror"
                                           id="contact_email" name="contact_email"
                                           value="{{ old('contact_email', $shop->contact_email ?? '') }}"
                                           placeholder="contact@yourstore.com">
                                    @error('contact_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Shop Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description" name="description" rows="3"
                                      placeholder="Describe your shop...">{{ old('description', $shop->description ?? '') }}</textarea>
                            @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="contact_phone" class="form-label">Contact Phone</label>
                                    <input type="text" class="form-control @error('contact_phone') is-invalid @enderror"
                                           id="contact_phone" name="contact_phone"
                                           value="{{ old('contact_phone', $shop->contact_phone ?? '') }}"
                                           placeholder="+94 XXX XXX XXX">
                                    @error('contact_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="address" class="form-label">Address</label>
                                    <input type="text" class="form-control @error('address') is-invalid @enderror"
                                           id="address" name="address"
                                           value="{{ old('address', $shop->address ?? '') }}"
                                           placeholder="Enter your shop address">
                                    @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="city" class="form-label">City</label>
                                    <input type="text" class="form-control @error('city') is-invalid @enderror"
                                           id="city" name="city"
                                           value="{{ old('city', $shop->city ?? '') }}"
                                           placeholder="City">
                                    @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="state" class="form-label">State/Province</label>
                                    <input type="text" class="form-control @error('state') is-invalid @enderror"
                                           id="state" name="state"
                                           value="{{ old('state', $shop->state ?? '') }}"
                                           placeholder="State">
                                    @error('state')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="zip_code" class="form-label">ZIP Code</label>
                                    <input type="text" class="form-control @error('zip_code') is-invalid @enderror"
                                           id="zip_code" name="zip_code"
                                           value="{{ old('zip_code', $shop->zip_code ?? '') }}"
                                           placeholder="ZIP Code">
                                    @error('zip_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="logo" class="form-label">Shop Logo</label>
                                    <input type="file" class="form-control @error('logo') is-invalid @enderror"
                                           id="logo" name="logo" accept="image/*">
                                    @error('logo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    @if($shop->logo ?? false)
                                        <div class="mt-2">
                                            <small>Current Logo:</small>
                                            <img src="{{ asset('storage/' . $shop->logo) }}" alt="Current Logo" class="img-thumbnail ms-2" style="height: 50px;">
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="banner" class="form-label">Shop Banner</label>
                                    <input type="file" class="form-control @error('banner') is-invalid @enderror"
                                           id="banner" name="banner" accept="image/*">
                                    @error('banner')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    @if($shop->banner ?? false)
                                        <div class="mt-2">
                                            <small>Current Banner:</small>
                                            <img src="{{ asset('storage/' . $shop->banner) }}" alt="Current Banner" class="img-thumbnail ms-2" style="height: 50px;">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="return_policy" class="form-label">Return Policy</label>
                            <textarea class="form-control @error('return_policy') is-invalid @enderror"
                                      id="return_policy" name="return_policy" rows="3"
                                      placeholder="Describe your return policy...">{{ old('return_policy', $shop->return_policy ?? '') }}</textarea>
                            @error('return_policy')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="shipping_policy" class="form-label">Shipping Policy</label>
                            <textarea class="form-control @error('shipping_policy') is-invalid @enderror"
                                      id="shipping_policy" name="shipping_policy" rows="3"
                                      placeholder="Describe your shipping policy...">{{ old('shipping_policy', $shop->shipping_policy ?? '') }}</textarea>
                            @error('shipping_policy')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('wholesaler.shop') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Update Shop
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
