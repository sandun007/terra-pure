@extends('employee.layout.app')

@push('title')
    Exchange #{{ $exchange->id }} Details
@endpush

@push('css')
    <style>
        .exchange-detail-header {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }

        .exchange-detail-header::after {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        }

        .status-badge-large {
            font-size: 1rem;
            padding: 0.5rem 1.5rem;
            border-radius: 30px;
            font-weight: 600;
        }

        .exchange-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            height: 100%;
            border: 1px solid #e2e8f0;
        }

        .exchange-card.source {
            border-left: 4px solid #4f46e5;
        }

        .exchange-card.destination {
            border-left: 4px solid #10b981;
        }

        .product-image {
            width: 80px;
            height: 80px;
            object-fit: contain;
            background: #f8fafc;
            border-radius: 10px;
            padding: 0.5rem;
        }

        .value-box {
            background: #f8fafc;
            border-radius: 12px;
            padding: 1rem;
            text-align: center;
            border: 1px solid #e2e8f0;
        }

        .value-box .label {
            font-size: 0.85rem;
            color: #64748b;
            text-transform: uppercase;
        }

        .value-box .value {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1e293b;
        }

        .timeline {
            position: relative;
            padding-left: 2rem;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e2e8f0;
        }

        .timeline-item {
            position: relative;
            padding-bottom: 1.5rem;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -0.5rem;
            top: 0;
            width: 1rem;
            height: 1rem;
            border-radius: 50%;
            background: #4f46e5;
            border: 2px solid white;
        }

        .action-buttons {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            z-index: 100;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="exchange-detail-header">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h1 class="mb-2">Exchange #{{ $exchange->id }}</h1>
                    <p class="mb-0 opacity-75">Created {{ $exchange->created_at->format('M d, Y h:i A') }}</p>
                </div>
                <div>
                <span class="status-badge-large bg-white bg-opacity-25">
                    <i class="fas fa-circle me-2" style="color: {{
                        $exchange->status == 'completed' ? '#10b981' :
                        ($exchange->status == 'pending' ? '#f59e0b' :
                        ($exchange->status == 'approved' ? '#3b82f6' :
                        ($exchange->status == 'cancelled' ? '#ef4444' : '#94a3b8')))
                    }}"></i>
                    {{ ucfirst($exchange->status) }}
                </span>
                    @if($exchange->exchange_type)
                        <span class="badge bg-light text-dark ms-2">{{ ucfirst($exchange->exchange_type) }}</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Exchange Flow -->
        <div class="row mb-4">
            <div class="col-md-5">
                <div class="exchange-card source">
                    <h5 class="mb-3">
                        <i class="fas fa-arrow-right me-2 text-primary"></i>Source Product
                        <small class="text-muted ms-2">(From {{ $exchange->sourceWholesaler->business_name ?? 'N/A' }})</small>
                    </h5>

                    <div class="d-flex gap-3">
                        @if($exchange->sourceProduct->image)
                            <img src="{{ asset('storage/' . $exchange->sourceProduct->image) }}" class="product-image">
                        @else
                            <div class="product-image d-flex align-items-center justify-content-center">
                                <i class="fas fa-box fa-2x text-muted"></i>
                            </div>
                        @endif

                        <div class="flex-grow-1">
                            <h6 class="mb-1">{{ $exchange->sourceProduct->name }}</h6>
                            <code class="text-muted">{{ $exchange->sourceProduct->code }}</code>

                            <div class="row mt-2">
                                <div class="col-6">
                                    <small class="text-muted">Price per unit</small>
                                    <p class="mb-0">Rs. {{ number_format($exchange->sourceProduct->selling_price, 2) }}</p>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Quantity</small>
                                    <p class="mb-0">{{ $exchange->exchange_quantity }}</p>
                                </div>
                            </div>

                            <div class="value-box mt-2">
                                <div class="label">Total Value</div>
                                <div class="value">Rs. {{ number_format($exchange->source_price_at_time, 2) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-2 d-flex align-items-center justify-content-center">
                <div class="text-center">
                    <i class="fas fa-exchange-alt fa-3x text-muted mb-2"></i>
                    <span class="badge bg-warning d-block">{{ $exchange->exchange_quantity }} units</span>
                </div>
            </div>

            <div class="col-md-5">
                <div class="exchange-card destination">
                    <h5 class="mb-3">
                        <i class="fas fa-arrow-left me-2 text-success"></i>Destination Product
                        <small class="text-muted ms-2">(To {{ $exchange->destinationWholesaler->business_name ?? 'N/A' }})</small>
                    </h5>

                    <div class="d-flex gap-3">
                        @if($exchange->destinationProduct->image)
                            <img src="{{ asset('storage/' . $exchange->destinationProduct->image) }}" class="product-image">
                        @else
                            <div class="product-image d-flex align-items-center justify-content-center">
                                <i class="fas fa-box fa-2x text-muted"></i>
                            </div>
                        @endif

                        <div class="flex-grow-1">
                            <h6 class="mb-1">{{ $exchange->destinationProduct->name }}</h6>
                            <code class="text-muted">{{ $exchange->destinationProduct->code }}</code>

                            <div class="row mt-2">
                                <div class="col-6">
                                    <small class="text-muted">Price per unit</small>
                                    <p class="mb-0">Rs. {{ number_format($exchange->destinationProduct->selling_price, 2) }}</p>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Quantity</small>
                                    <p class="mb-0">{{ $exchange->exchange_quantity }}</p>
                                </div>
                            </div>

                            <div class="value-box mt-2">
                                <div class="label">Total Value</div>
                                <div class="value">Rs. {{ number_format($exchange->destination_price_at_time, 2) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Financial Details -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="exchange-card">
                    <h6 class="text-muted mb-2">Source Value</h6>
                    <h3>Rs. {{ number_format($exchange->source_price_at_time, 2) }}</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="exchange-card">
                    <h6 class="text-muted mb-2">Destination Value</h6>
                    <h3>Rs. {{ number_format($exchange->destination_price_at_time, 2) }}</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="exchange-card">
                    <h6 class="text-muted mb-2">Price Difference</h6>
                    <h3 class="{{ $exchange->price_difference > 0 ? 'text-success' : ($exchange->price_difference < 0 ? 'text-danger' : '') }}">
                        {{ $exchange->price_difference > 0 ? '+' : '' }}{{ number_format($exchange->price_difference, 2) }}
                    </h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="exchange-card">
                    <h6 class="text-muted mb-2">Adjustment</h6>
                    <h3>Rs. {{ number_format($exchange->adjustment_amount, 2) }}</h3>
                </div>
            </div>
        </div>

        <!-- Payment Details -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="exchange-card">
                    <h6 class="text-muted mb-2">Payment Method</h6>
                    <p class="mb-0 fw-bold">{{ ucfirst($exchange->payment_method ?? 'Not specified') }}</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="exchange-card">
                    <h6 class="text-muted mb-2">Payment Status</h6>
                    <span class="badge bg-{{
                    $exchange->payment_status == 'completed' ? 'success' :
                    ($exchange->payment_status == 'pending' ? 'warning' : 'secondary')
                }}">
                    {{ ucfirst($exchange->payment_status ?? 'pending') }}
                </span>
                    @if($exchange->payment_date)
                        <br><small class="text-muted">{{ $exchange->payment_date->format('M d, Y') }}</small>
                    @endif
                </div>
            </div>
            <div class="col-md-4">
                <div class="exchange-card">
                    <h6 class="text-muted mb-2">Transaction Reference</h6>
                    <p class="mb-0">{{ $exchange->transaction_reference ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <!-- Additional Info -->
        <div class="row">
            <div class="col-md-6">
                <div class="exchange-card">
                    <h6 class="mb-3">Exchange Notes</h6>
                    <p>{{ $exchange->notes ?? 'No notes provided' }}</p>

                    @if($exchange->reason)
                        <div class="mt-3 p-3 bg-light rounded">
                            <small class="text-muted">Reason</small>
                            <p class="mb-0">{{ $exchange->reason }}</p>
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <div class="exchange-card">
                    <h6 class="mb-3">Audit Trail</h6>
                    <div class="timeline">
                        <div class="timeline-item">
                            <small class="text-muted">{{ $exchange->created_at->format('M d, Y h:i A') }}</small>
                            <p class="mb-0">Exchange created by <strong>{{ $exchange->employee->name ?? 'Unknown' }}</strong></p>
                        </div>

                        @if($exchange->approved_at)
                            <div class="timeline-item">
                                <small class="text-muted">{{ $exchange->approved_at->format('M d, Y h:i A') }}</small>
                                <p class="mb-0">Exchange approved by <strong>{{ $exchange->approvedBy->name ?? 'Unknown' }}</strong></p>
                            </div>
                        @endif

                        @if($exchange->status == 'completed')
                            <div class="timeline-item">
                                <small class="text-muted">{{ $exchange->updated_at->format('M d, Y h:i A') }}</small>
                                <p class="mb-0">Exchange completed</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            @if($exchange->status == 'pending')
                <div class="btn-group">
                    <form action="{{ route('employee.wholesaler.exchanges.approve', $exchange->id) }}"
                          method="POST"
                          class="d-inline"
                          onsubmit="return confirm('Approve this exchange? This will transfer the products.')">
                        @csrf
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-check me-2"></i>Approve & Process
                        </button>
                    </form>
                    <button class="btn btn-danger btn-lg" onclick="showRejectModal()">
                        <i class="fas fa-times me-2"></i>Reject
                    </button>
                </div>
            @elseif($exchange->status == 'approved')
                <form action="{{ route('employee.wholesaler.exchanges.approve', $exchange->id) }}"
                      method="POST"
                      class="d-inline"
                      onsubmit="return confirm('Process this approved exchange?')">
                    @csrf
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="fas fa-play me-2"></i>Process Exchange
                    </button>
                </form>
            @endif

            @if(in_array($exchange->status, ['pending', 'approved']))
                <button class="btn btn-secondary btn-lg mt-2" onclick="showCancelModal()">
                    <i class="fas fa-ban me-2"></i>Cancel Exchange
                </button>
            @endif
        </div>
    </div>

    <!-- Reject Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Reject Exchange</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('employee.wholesaler.exchanges.reject', $exchange->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Reason for Rejection</label>
                            <textarea name="rejection_reason" class="form-control" rows="4" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Reject Exchange</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Cancel Modal -->
    <div class="modal fade" id="cancelModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title">Cancel Exchange</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('employee.wholesaler.exchange.cancel', $exchange->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Reason for Cancellation</label>
                            <textarea name="reason" class="form-control" rows="4" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-warning">Cancel Exchange</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        function showRejectModal() {
            const modal = new bootstrap.Modal(document.getElementById('rejectModal'));
            modal.show();
        }

        function showCancelModal() {
            const modal = new bootstrap.Modal(document.getElementById('cancelModal'));
            modal.show();
        }
    </script>
@endpush
