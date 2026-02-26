@extends('employee.layout.app')

@push('title')
    Price Change History
@endpush

@push('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <style>
        .history-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 25px;
            color: white;
        }

        .filter-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }

        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }

        .status-pending { background: #fef3c7; color: #92400e; }
        .status-approved { background: #d1fae5; color: #065f46; }
        .status-rejected { background: #fee2e2; color: #991b1b; }

        .product-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .product-image {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 8px;
        }

        .price-change {
            font-size: 13px;
        }

        .price-up { color: #10b981; }
        .price-down { color: #ef4444; }

        .action-buttons {
            display: flex;
            gap: 5px;
        }

        .btn-icon {
            width: 32px;
            height: 32px;
            border-radius: 6px;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
            color: white;
        }

        .btn-approve { background: #10b981; }
        .btn-reject { background: #ef4444; }
        .btn-view { background: #3b82f6; }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="history-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="text-white mb-2">
                        <i class="fas fa-history me-2"></i>Price Change History
                    </h4>
                    <p class="text-white-50 mb-0">Track and manage all price change requests</p>
                </div>
                <a href="{{ route('employee.seller.products.index') }}" class="btn btn-light">
                    <i class="fas fa-arrow-left me-2"></i>Back to Products
                </a>
            </div>
        </div>

        <!-- Filter Card -->
        <div class="filter-card">
            <form method="GET" action="{{ route('employee.seller.products.price-history') }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Product ID/Code</label>
                        <input type="text" name="product_id" class="form-control" placeholder="Enter product ID or code" value="{{ request('product_id') }}">
                    </div>

                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter me-2"></i>Apply Filters
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- History Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="historyTable">
                        <thead>
                        <tr>
                            <th>Date</th>
                            <th>Product</th>
                            <th>Employee</th>
                            <th>Old Price</th>
                            <th>New Price</th>
                            <th>Change</th>
                            <th>Reason</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($history as $item)
                            <tr>
                                <td>{{ $item->created_at->format('M d, H:i') }}</td>
                                <td>
                                    <div class="product-info">
                                        @if($item->product)
                                            <img src="{{ $item->product->image_url }}" class="product-image">
                                            <div>
                                                <div class="fw-semibold">{{ Str::limit($item->product->name, 30) }}</div>
                                                <small class="text-muted">{{ $item->product->code }}</small>
                                            </div>
                                        @else
                                            <span class="text-muted">Product Deleted</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <div class="fw-semibold">{{ $item->employee->name ?? 'N/A' }}</div>
                                        <small class="text-muted">ID: {{ $item->employee_id }}</small>
                                    </div>
                                </td>
                                <td>Rs. {{ number_format($item->old_display_price, 2) }}</td>
                                <td>Rs. {{ number_format($item->new_display_price, 2) }}</td>
                                <td>
                                    <div class="price-change">
                                            <span class="{{ $item->price_difference >= 0 ? 'price-up' : 'price-down' }}">
                                                {{ $item->formatted_difference }}
                                            </span>
                                        <br>
                                        <small>({{ $item->formatted_percentage }})</small>
                                    </div>
                                </td>
                                <td>
                                        <span title="{{ $item->change_reason }}">
                                            {{ Str::limit($item->change_reason, 30) }}
                                        </span>
                                </td>
                                <td>
                                        <span class="status-badge status-{{ $item->status }}">
                                            {{ ucfirst($item->status) }}
                                        </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        @if($item->status == 'pending')
                                            <button onclick="approvePriceChange({{ $item->id }})"
                                                    class="btn-icon btn-approve" title="Approve">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button onclick="showRejectModal({{ $item->id }})"
                                                    class="btn-icon btn-reject" title="Reject">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        @endif

                                        @if($item->product)
                                            <a href="{{ route('employee.seller.products.show', $item->product_id) }}"
                                               class="btn-icon btn-view" title="View Product">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5">
                                    <i class="fas fa-history fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">No price change history found</h5>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-end mt-3">
                    {{ $history->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Modal (Hidden) -->
    <div class="modal fade" id="rejectModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reject Price Change</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="rejectForm">
                        <input type="hidden" id="rejectId">
                        <div class="mb-3">
                            <label class="form-label">Rejection Reason</label>
                            <textarea class="form-control" id="rejectionReason" rows="3" required></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" onclick="submitRejection()">Reject</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function approvePriceChange(id) {
            Swal.fire({
                title: 'Approve Price Change?',
                text: 'This will update the product price immediately.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, Approve',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/employee/seller-products/approve-price/' + id,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            Swal.fire('Approved!', 'Price change has been approved and applied.', 'success')
                                .then(() => location.reload());
                        },
                        error: function() {
                            Swal.fire('Error!', 'Failed to approve price change.', 'error');
                        }
                    });
                }
            });
        }

        function showRejectModal(id) {
            $('#rejectId').val(id);
            new bootstrap.Modal(document.getElementById('rejectModal')).show();
        }

        function submitRejection() {
            const id = $('#rejectId').val();
            const reason = $('#rejectionReason').val();

            if (!reason) {
                Swal.fire('Error!', 'Please enter a rejection reason.', 'error');
                return;
            }

            $.ajax({
                url: '/employee/seller-products/reject-price/' + id,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    rejection_reason: reason
                },
                success: function(response) {
                    bootstrap.Modal.getInstance(document.getElementById('rejectModal')).hide();
                    Swal.fire('Rejected!', 'Price change has been rejected.', 'success')
                        .then(() => location.reload());
                },
                error: function() {
                    Swal.fire('Error!', 'Failed to reject price change.', 'error');
                }
            });
        }
    </script>
@endpush
