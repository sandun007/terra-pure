@extends('employee.layout.app')

@section('title', 'Seller Orders')

@section('content')
    <div class="container-fluid py-4">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Seller Orders</h1>
            <button type="button" class="btn btn-primary" onclick="exportReport('seller_orders')">
                <i class="fas fa-download me-1"></i> Export
            </button>
        </div>

        <!-- Filter Form -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('employee.seller.orders') }}">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <select class="form-select" name="status">
                                @foreach($statuses as $key => $value)
                                    <option value="{{ $key }}" {{ $status == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <input type="text" class="form-control"
                                   placeholder="Order #, Customer..."
                                   name="search" value="{{ $search }}">
                        </div>

                        <div class="col-md-3">
                            <input type="date" class="form-control"
                                   name="date_from"
                                   value="{{ $date_from }}"
                                   placeholder="From Date">
                        </div>

                        <div class="col-md-3">
                            <input type="date" class="form-control"
                                   name="date_to"
                                   value="{{ $date_to }}"
                                   placeholder="To Date">
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="fas fa-filter me-1"></i> Filter
                            </button>
                            <a href="{{ route('employee.seller.orders') }}" class="btn btn-secondary">
                                <i class="fas fa-redo me-1"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="ordersTable">
                        <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Seller</th>
                            <th>Customer</th>
                            <th>Phone</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td>
                                    <strong>#{{ $order->order_no }}</strong>
                                </td>
                                <td>{{ $order->seller->name ?? 'N/A' }}</td>
                                <td>
                                    <strong>{{ $order->customer_name }}</strong><br>
                                    <small class="text-muted">{{ $order->customer_email ?? 'N/A' }}</small>
                                </td>
                                <td>{{ $order->customer_phone_01 }}</td>
                                <td>
                                    <strong class="text-primary">
                                        ৳{{ number_format($order->total_amount, 2) }}
                                    </strong>
                                </td>
                                <td>
                                    <select class="form-select form-select-sm order-status"
                                            data-id="{{ $order->id }}"
                                            style="width: 120px;">
                                        @foreach($statuses as $key => $value)
                                            @if($key !== 'all')
                                                <option value="{{ $key }}"
                                                    {{ $order->status == $key ? 'selected' : '' }}>
                                                    {{ $value }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </td>
                                <td>{{ $order->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('seller.order.view', $order->id) }}"
                                           class="btn btn-outline-info" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('employee.print.out', $order->id) }}"
                                           target="_blank" class="btn btn-outline-success" title="Print">
                                            <i class="fas fa-print"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }}
                        entries
                    </div>
                    <div>
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Form -->
    <form id="exportForm" action="{{ route('employee.export.report') }}" method="GET" style="display: none;">
        <input type="hidden" name="type" id="exportType">
        <input type="hidden" name="format" value="csv">
    </form>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            // Update Order Status
            $('.order-status').change(function () {
                const orderId = $(this).data('id');
                const newStatus = $(this).val();
                const statusText = $(this).find('option:selected').text();

                Swal.fire({
                    title: 'Update Status?',
                    text: `Change order status to "${statusText}"?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Update',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ url('employee/seller/orders/status/update') }}/" + orderId,
                            type: "POST",
                            data: {
                                status: newStatus,
                                _token: "{{ csrf_token() }}"
                            },
                            success: function (response) {
                                if (response.success) {
                                    showAlert('success', response.message);
                                }
                            },
                            error: function (xhr) {
                                showAlert('error', xhr.responseJSON?.message || 'Error updating status');
                                // Revert select
                                location.reload();
                            }
                        });
                    } else {
                        // Revert to original status
                        location.reload();
                    }
                });
            });

            // Export report
            window.exportReport = function (type) {
                $('#exportType').val(type);
                $('#exportForm').submit();
            };

            function showAlert(type, message) {
                const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
                const alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show position-fixed"
                 style="top: 20px; right: 20px; z-index: 9999;">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
                $('body').append(alertHtml);
                setTimeout(() => $('.alert').alert('close'), 3000);
            }
        });
    </script>
@endsection

