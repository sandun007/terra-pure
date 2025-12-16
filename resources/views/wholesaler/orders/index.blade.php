@extends('wholesaler.layouts.app')
@push('title')
    Orders Management
@endpush

@push('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --secondary: #8b5cf6;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #3b82f6;
        }

        .page-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .page-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            border-radius: 50%;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border: 1px solid #e5e7eb;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .stat-value {
            font-size: 2.5rem;
            font-weight: 800;
            line-height: 1;
            margin-bottom: 10px;
        }

        .stat-label {
            color: #6b7280;
            font-size: 0.875rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .order-table-container {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }

        .order-status-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-pending { background: #fef3c7; color: #92400e; }
        .status-confirmed { background: #dbeafe; color: #1e40af; }
        .status-processing { background: #e0e7ff; color: #3730a3; }
        .status-shipped { background: #f0f9ff; color: #075985; }
        .status-completed { background: #d1fae5; color: #065f46; }
        .status-cancelled { background: #fee2e2; color: #991b1b; }

        .payment-status-badge {
            padding: 6px 12px;
            border-radius: 15px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .payment-paid { background: #d1fae5; color: #065f46; }
        .payment-pending { background: #fef3c7; color: #92400e; }
        .payment-failed { background: #fee2e2; color: #991b1b; }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .btn-action {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-view { background: #dbeafe; color: #1e40af; }
        .btn-view:hover { background: #1e40af; color: white; }

        .btn-edit { background: #fef3c7; color: #92400e; }
        .btn-edit:hover { background: #f59e0b; color: white; }

        .filter-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }

        .filter-tab {
            padding: 10px 20px;
            border-radius: 12px;
            background: white;
            border: 2px solid #e5e7eb;
            color: #6b7280;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .filter-tab:hover {
            border-color: var(--primary);
            color: var(--primary);
        }

        .filter-tab.active {
            background: var(--primary);
            border-color: var(--primary);
            color: white;
        }

        .empty-state {
            text-align: center;
            padding: 50px 20px;
        }

        .empty-state-icon {
            font-size: 4rem;
            color: #d1d5db;
            margin-bottom: 20px;
        }

        .empty-state-text {
            color: #6b7280;
            font-size: 1.125rem;
        }

        .product-thumb {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            object-fit: cover;
            border: 2px solid #f3f4f6;
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .action-buttons {
                flex-direction: column;
            }

            .filter-tabs {
                flex-direction: column;
            }

            .filter-tab {
                text-align: center;
            }
        }
    </style>
@endpush

@section('content')
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h2 fw-bold mb-2">Orders Management</h1>
                <p class="mb-0">Track and manage all your orders in one place</p>
            </div>
            <div>
                <button class="btn btn-light" onclick="refreshOrders()">
                    <i class="fas fa-sync-alt me-2"></i>
                    Refresh
                </button>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                <i class="fas fa-shopping-bag"></i>
            </div>
            <div class="stat-value">{{ $totalOrders }}</div>
            <div class="stat-label">Total Orders</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-value">{{ $pendingOrders }}</div>
            <div class="stat-label">Pending Orders</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon bg-success bg-opacity-10 text-success">
                <i class="fas fa-check-double"></i>
            </div>
            <div class="stat-value">{{ $completedOrders }}</div>
            <div class="stat-label">Completed Orders</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon bg-info bg-opacity-10 text-info">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <div class="stat-value">Rs. {{ number_format($totalRevenue) }}</div>
            <div class="stat-label">Total Revenue</div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="filter-tabs">
        <a href="{{ route('wholesaler.orders.index') }}"
           class="filter-tab {{ !request()->has('status') || request('status') == 'all' ? 'active' : '' }}">
            <i class="fas fa-list me-2"></i>
            All Orders
        </a>
        <a href="{{ route('wholesaler.orders.index', ['status' => 'pending']) }}"
           class="filter-tab {{ request('status') == 'pending' ? 'active' : '' }}">
            <i class="fas fa-clock me-2"></i>
            Pending
        </a>
        <a href="{{ route('wholesaler.orders.index', ['status' => 'confirmed']) }}"
           class="filter-tab {{ request('status') == 'confirmed' ? 'active' : '' }}">
            <i class="fas fa-check-circle me-2"></i>
            Confirmed
        </a>
        <a href="{{ route('wholesaler.orders.index', ['status' => 'processing']) }}"
           class="filter-tab {{ request('status') == 'processing' ? 'active' : '' }}">
            <i class="fas fa-cog me-2"></i>
            Processing
        </a>
        <a href="{{ route('wholesaler.orders.index', ['status' => 'completed']) }}"
           class="filter-tab {{ request('status') == 'completed' ? 'active' : '' }}">
            <i class="fas fa-check-double me-2"></i>
            Completed
        </a>
        <a href="{{ route('wholesaler.orders.index', ['status' => 'cancelled']) }}"
           class="filter-tab {{ request('status') == 'cancelled' ? 'active' : '' }}">
            <i class="fas fa-times-circle me-2"></i>
            Cancelled
        </a>
    </div>

    <!-- Orders Table -->
    <div class="order-table-container">
        @if($orders->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover" id="ordersTable">
                    <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Items</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td>
                                <strong>#{{ $order->order_number }}</strong>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center"
                                             style="width: 40px; height: 40px;">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <strong>{{ $order->customer_name }}</strong>
                                        <div class="text-muted small">{{ $order->customer_email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($order->orderItems->count() > 0 && $order->orderItems[0]->product)
                                        @if($order->orderItems[0]->product->image)
                                            <img src="{{ asset('storage/' . $order->orderItems[0]->product->image) }}"
                                                 alt="Product" class="product-thumb me-2">
                                        @else
                                            <div class="product-thumb bg-light d-flex align-items-center justify-content-center me-2">
                                                <i class="fas fa-box text-muted"></i>
                                            </div>
                                        @endif
                                    @endif
                                    <span class="badge bg-secondary">{{ $order->orderItems->count() }} items</span>
                                </div>
                            </td>
                            <td>
                                <strong class="text-primary">Rs. {{ number_format($order->total_amount) }}</strong>
                            </td>
                            <td>
                                    <span class="order-status-badge status-{{ $order->status }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                            </td>
                            <td>
                                    <span class="payment-status-badge payment-{{ $order->payment_status }}">
                                        {{ ucfirst($order->payment_status) }}
                                    </span>
                            </td>
                            <td>
                                <div class="text-nowrap">
                                    {{ $order->created_at->format('M d, Y') }}
                                    <div class="text-muted small">{{ $order->created_at->format('h:i A') }}</div>
                                </div>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('wholesaler.orders.show', $order->id) }}"
                                       class="btn-action btn-view" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if(in_array($order->status, ['pending', 'confirmed', 'processing']))
                                        <button class="btn-action btn-edit"
                                                onclick="updateStatusModal({{ $order->id }})"
                                                title="Update Status">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    @endif
                                    @if($order->status == 'completed')
                                        <a href="{{ route('wholesaler.orders.invoice.download', $order->id) }}"
                                           class="btn-action btn-view" title="Download Invoice">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($orders->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $orders->withQueryString()->links() }}
                </div>
            @endif
        @else
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-shopping-bag"></i>
                </div>
                <h4 class="fw-bold mb-3">No Orders Found</h4>
                <p class="empty-state-text mb-4">You haven't received any orders yet</p>
                <a href="{{ route('wholesaler.shop.index') }}" class="btn btn-primary">
                    <i class="fas fa-store me-2"></i>
                    View Shop
                </a>
            </div>
        @endif
    </div>

    <!-- Update Status Modal -->
    <div class="modal fade" id="updateStatusModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Order Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="updateStatusForm">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="orderId" name="order_id">
                        <div class="mb-3">
                            <label class="form-label">Select Status</label>
                            <select class="form-select" name="status" required>
                                <option value="pending">Pending</option>
                                <option value="confirmed">Confirmed</option>
                                <option value="processing">Processing</option>
                                <option value="shipped">Shipped</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Notes (Optional)</label>
                            <textarea class="form-control" name="notes" rows="3"
                                      placeholder="Add notes about this status change..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Status</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script>
        // Initialize DataTable
        $(document).ready(function() {
            $('#ordersTable').DataTable({
                responsive: true,
                order: [[0, 'desc']],
                language: {
                    search: "Search orders...",
                    zeroRecords: "No matching orders found",
                    info: "Showing _START_ to _END_ of _TOTAL_ orders",
                    infoEmpty: "No orders available",
                    infoFiltered: "(filtered from _MAX_ total orders)"
                }
            });
        });

        // Update Status Modal
        function updateStatusModal(orderId) {
            $('#orderId').val(orderId);
            $('#updateStatusModal').modal('show');
        }

        // Update Status Form Submission
        $('#updateStatusForm').submit(function(e) {
            e.preventDefault();

            const formData = $(this).serialize();
            const orderId = $('#orderId').val();

            $.ajax({
                url: `{{ route('wholesaler.orders.updateStatus', '') }}/${orderId}`,
                method: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        showNotification('success', response.message);
                        $('#updateStatusModal').modal('hide');
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    }
                },
                error: function(xhr) {
                    showNotification('error', xhr.responseJSON?.message || 'An error occurred');
                }
            });
        });

        // Refresh Orders
        function refreshOrders() {
            location.reload();
        }

        // Show Notification
        function showNotification(type, message) {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed top-0 end-0 m-3`;
            alertDiv.style.zIndex = '9999';
            alertDiv.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

            document.body.appendChild(alertDiv);

            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.remove();
                }
            }, 5000);
        }
    </script>
@endpush
