@extends('employee.layout.app')
@push('title')
    Wholesaler Orders | Employee Dashboard
@endpush

@push('css')
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Round" rel="stylesheet">
    <style>
        .orders-section {
            padding: 30px 0;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 20px;
        }

        .section-title {
            font-size: 28px;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title .material-icons {
            font-size: 32px;
            color: #10b981;
        }

        .orders-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }

        .stat-title {
            font-size: 14px;
            color: #64748b;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .stat-value {
            font-size: 32px;
            font-weight: 800;
            color: #1e293b;
            margin: 0;
        }

        .stat-trend {
            font-size: 12px;
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .stat-trend .material-icons {
            font-size: 14px;
        }

        .status-filter {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
            flex-wrap: wrap;
            padding: 15px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .status-btn {
            padding: 8px 20px;
            border: 2px solid #e2e8f0;
            background: white;
            border-radius: 25px;
            font-weight: 600;
            color: #64748b;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .status-btn:hover {
            border-color: #10b981;
            color: #10b981;
        }

        .status-btn.active {
            background: #10b981;
            border-color: #10b981;
            color: white;
        }

        .status-btn .material-icons {
            font-size: 16px;
        }

        .status-count {
            background: rgba(255, 255, 255, 0.2);
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 12px;
        }

        .orders-table {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
        }

        .table-header {
            padding: 20px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .table-title {
            font-size: 18px;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .table-title .material-icons {
            font-size: 20px;
            color: #10b981;
        }

        .search-box {
            position: relative;
            width: 300px;
        }

        .search-input {
            width: 100%;
            padding: 10px 15px 10px 40px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            outline: none;
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }

        .search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }

        .table-container {
            overflow-x: auto;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1000px;
        }

        .table thead {
            background: #f8fafc;
        }

        .table th {
            padding: 15px 20px;
            text-align: left;
            font-weight: 600;
            color: #475569;
            font-size: 14px;
            border-bottom: 2px solid #e2e8f0;
        }

        .table tbody tr {
            border-bottom: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .table tbody tr:hover {
            background: #f8fafc;
        }

        .table td {
            padding: 15px 20px;
            font-size: 14px;
            color: #1e293b;
        }

        .order-number {
            font-weight: 600;
            color: #10b981;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .order-number:hover {
            text-decoration: underline;
        }

        .order-number .material-icons {
            font-size: 16px;
        }

        .wholesaler-info {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .wholesaler-name {
            font-weight: 600;
            color: #1e293b;
        }

        .wholesaler-email {
            font-size: 12px;
            color: #64748b;
        }

        .amount {
            font-weight: 700;
            color: #1e293b;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .status-badge .material-icons {
            font-size: 14px;
        }

        .status-pending {
            background: #fef3c7;
            color: #d97706;
        }

        .status-confirmed {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .status-processing {
            background: #f3e8ff;
            color: #7c3aed;
        }

        .status-shipped {
            background: #ecfccb;
            color: #3f6212;
        }

        .status-completed {
            background: #dcfce7;
            color: #166534;
        }

        .status-cancelled {
            background: #fee2e2;
            color: #dc2626;
        }

        .actions {
            display: flex;
            gap: 8px;
        }

        .action-btn {
            width: 36px;
            height: 36px;
            border: 1px solid #e2e8f0;
            background: white;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            color: #64748b;
        }

        .action-btn:hover {
            background: #10b981;
            border-color: #10b981;
            color: white;
        }

        .action-btn .material-icons {
            font-size: 18px;
        }

        .status-select {
            padding: 6px 12px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            background: white;
            color: #475569;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            min-width: 120px;
            transition: all 0.3s ease;
        }

        .status-select:focus {
            outline: none;
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-icon {
            font-size: 64px;
            color: #cbd5e1;
            margin-bottom: 20px;
        }

        .empty-title {
            font-size: 20px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 10px;
        }

        .empty-text {
            color: #64748b;
            margin-bottom: 30px;
            max-width: 400px;
            margin-left: auto;
            margin-right: auto;
        }

        .pagination-container {
            display: flex;
            justify-content: center;
            margin-top: 30px;
        }

        .btn-export {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: #10b981;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-export:hover {
            background: #059669;
            color: white;
            transform: translateY(-2px);
        }

        .btn-export .material-icons {
            font-size: 18px;
        }

        @media (max-width: 768px) {
            .section-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .search-box {
                width: 100%;
            }

            .orders-stats {
                grid-template-columns: 1fr;
            }

            .status-filter {
                justify-content: center;
            }
        }
    </style>
@endpush

@section('content')
    <div class="orders-section">
        <div class="container">
            <!-- Section Header -->
            <div class="section-header">
                <h1 class="section-title">
                    <i class="material-icons-outlined">store</i>
                    Wholesaler Orders Management
                </h1>
                <div class="d-flex gap-3">
                    <a href="{{ route('employee.export.report') }}?type=wholesaler_orders"
                       class="btn-export">
                        <i class="material-icons-outlined">download</i>
                        Export Report
                    </a>
                </div>
            </div>

            <!-- Order Statistics -->
            <div class="orders-stats">
                <div class="stat-card">
                    <div class="stat-title">
                        <i class="material-icons-outlined">shopping_cart</i>
                        Total Orders
                    </div>
                    <h3 class="stat-value">{{ $statusCounts['all'] }}</h3>
                    <div class="stat-trend">
                        <i class="material-icons-outlined">trending_up</i>
                        All wholesaler orders
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-title">
                        <i class="material-icons-outlined">pending</i>
                        Pending
                    </div>
                    <h3 class="stat-value">{{ $statusCounts['pending'] }}</h3>
                    <div class="stat-trend">
                        {{ $statusCounts['pending'] > 0 ? 'Needs attention' : 'All clear' }}
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-title">
                        <i class="material-icons-outlined">check_circle</i>
                        Completed
                    </div>
                    <h3 class="stat-value">{{ $statusCounts['completed'] }}</h3>
                    <div class="stat-trend">
                        <i class="material-icons-outlined">done_all</i>
                        Successful orders
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-title">
                        <i class="material-icons-outlined">payments</i>
                        Revenue
                    </div>
                    <h3 class="stat-value">
                        RS. {{ number_format($totalRevenue, 2) }}
                    </h3>
                    <div class="stat-trend">
                        <i class="material-icons-outlined">show_chart</i>
                        Total revenue
                    </div>
                </div>
            </div>

            <!-- Status Filter -->
            <div class="status-filter">
                <button class="status-btn {{ !request('status') ? 'active' : '' }}"
                        onclick="filterOrders('')">
                    <i class="material-icons-outlined">list</i>
                    All Orders
                    <span class="status-count">{{ $statusCounts['all'] }}</span>
                </button>
                <button class="status-btn {{ request('status') === 'pending' ? 'active' : '' }}"
                        onclick="filterOrders('pending')">
                    <i class="material-icons-outlined">pending</i> Pending
                    <span class="status-count">{{ $statusCounts['pending'] }}</span>
                </button>
                <button class="status-btn {{ request('status') === 'confirmed' ? 'active' : '' }}"
                        onclick="filterOrders('confirmed')">
                    <i class="material-icons-outlined">check_circle</i> Confirmed
                    <span class="status-count">{{ $statusCounts['confirmed'] }}</span>
                </button>
                <button class="status-btn {{ request('status') === 'processing' ? 'active' : '' }}"
                        onclick="filterOrders('processing')">
                    <i class="material-icons-outlined">settings</i> Processing
                    <span class="status-count">{{ $statusCounts['processing'] }}</span>
                </button>
                <button class="status-btn {{ request('status') === 'shipped' ? 'active' : '' }}"
                        onclick="filterOrders('shipped')">
                    <i class="material-icons-outlined">local_shipping</i> Shipped
                    <span class="status-count">{{ $statusCounts['shipped'] }}</span>
                </button>
                <button class="status-btn {{ request('status') === 'completed' ? 'active' : '' }}"
                        onclick="filterOrders('completed')">
                    <i class="material-icons-outlined">done_all</i> Completed
                    <span class="status-count">{{ $statusCounts['completed'] }}</span>
                </button>
                <button class="status-btn {{ request('status') === 'cancelled' ? 'active' : '' }}"
                        onclick="filterOrders('cancelled')">
                    <i class="material-icons-outlined">cancel</i> Cancelled
                    <span class="status-count">{{ $statusCounts['cancelled'] }}</span>
                </button>
            </div>

            <!-- Orders Table -->
            <div class="orders-table">
                <div class="table-header">
                    <h3 class="table-title">
                        <i class="material-icons-outlined">receipt</i>
                        Recent Wholesaler Orders
                    </h3>
                    <form action="{{ route('employee.wholesaler.orders') }}" method="GET" class="search-box">
                        <i class="material-icons-outlined search-icon">search</i>
                        <input type="text"
                               name="search"
                               class="search-input"
                               placeholder="Search orders..."
                               value="{{ request('search') }}">
                    </form>
                </div>

                <div class="table-container">
                    @if($orders->count() > 0)
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Order No.</th>
                                <th>Wholesaler</th>
                                <th>Customer</th>
                                <th>Date</th>
                                <th>Items</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td>
                                        <a href="{{ route('employee.wholesaler.order-view', $order->id) }}"
                                           class="order-number">
                                            <i class="material-icons-outlined">receipt_long</i>
                                            {{ $order->order_number }}
                                        </a>
                                    </td>
                                    <td>
                                        <div class="wholesaler-info">
                                            <span class="wholesaler-name">{{ $order->wholesaler->business_name ?? 'N/A' }}</span>
                                            <span class="wholesaler-email">{{ $order->wholesaler->email ?? '' }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="wholesaler-info">
                                            <span class="wholesaler-name">{{ $order->customer_name }}</span>
                                            <span class="wholesaler-email">{{ $order->customer_email }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $order->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="material-icons-outlined" style="font-size: 16px;">inventory_2</i>
                                            {{ $order->items->count() }} item(s)
                                        </div>
                                    </td>
                                    <td class="amount">RS. {{ number_format($order->total_amount, 2) }}</td>
                                    <td>
                                        <select class="status-select"
                                                data-order-id="{{ $order->id }}"
                                                onchange="updateOrderStatus(this)">
                                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                            <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        </select>
                                    </td>
                                    <td>
                                        <div class="actions">
                                            <a href="{{ route('employee.wholesaler.order-view', $order->id) }}"
                                               class="action-btn"
                                               title="View Details">
                                                <i class="material-icons-outlined">visibility</i>
                                            </a>
                                            <a href="{{ route('wholesaler.orders.invoice.download', $order->id) }}"
                                               class="action-btn"
                                               title="Download Invoice">
                                                <i class="material-icons-outlined">download</i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="material-icons-outlined" style="font-size: 64px;">store</i>
                            </div>
                            <h3 class="empty-title">No Wholesaler Orders Found</h3>
                            <p class="empty-text">
                                {{ request('search') || request('status')
                                    ? 'Try adjusting your search criteria or filters.'
                                    : 'No wholesaler orders have been placed yet.' }}
                            </p>
                            @if(request('search') || request('status'))
                                <a href="{{ route('employee.wholesaler.orders') }}" class="btn btn-primary">
                                    <i class="material-icons-outlined me-2">clear_all</i>Clear Filters
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Pagination -->
            @if($orders->hasPages())
                <div class="pagination-container">
                    {{ $orders->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

@push('script')
    <script>
        function filterOrders(status) {
            const url = new URL(window.location.href);
            if (status) {
                url.searchParams.set('status', status);
            } else {
                url.searchParams.delete('status');
            }
            window.location.href = url.toString();
        }

        function updateOrderStatus(selectElement) {
            const orderId = selectElement.dataset.orderId;
            const newStatus = selectElement.value;
            const statusText = selectElement.options[selectElement.selectedIndex].text;

            Swal.fire({
                title: 'Update Order Status?',
                text: `Change order status to "${statusText}"?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, Update',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#10b981',
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    const originalText = selectElement.value;
                    selectElement.disabled = true;

                    // Make API call to update status
                    fetch(`/employee/wholesaler/orders/${orderId}/status`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            status: newStatus,
                            notes: `Status updated by employee`
                        })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                showNotification('success', data.message);
                                // Update the select class based on status
                                updateStatusBadge(selectElement, newStatus);
                            } else {
                                showNotification('error', data.message);
                                selectElement.value = originalText;
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showNotification('error', 'Error updating status');
                            selectElement.value = originalText;
                        })
                        .finally(() => {
                            selectElement.disabled = false;
                        });
                } else {
                    // Revert to original value
                    location.reload();
                }
            });
        }

        function updateStatusBadge(selectElement, status) {
            // Remove all status classes
            selectElement.classList.remove(
                'status-pending', 'status-confirmed', 'status-processing',
                'status-shipped', 'status-completed', 'status-cancelled'
            );

            // Add new status class
            selectElement.classList.add(`status-${status}`);
        }

        function showNotification(type, message) {
            const notification = document.createElement('div');
            notification.className = `alert alert-${type} alert-dismissible fade show`;
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 10000;
                min-width: 300px;
                animation: slideIn 0.3s ease;
            `;

            const icon = type === 'success' ? 'check_circle' : 'error';
            notification.innerHTML = `
                <div class="d-flex align-items-center">
                    <i class="material-icons-outlined me-2">${icon}</i>
                    <div>
                        <strong>${type === 'success' ? 'Success!' : 'Error!'}</strong>
                        <div>${message}</div>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;

            document.body.appendChild(notification);

            setTimeout(() => {
                if (notification.parentNode) notification.remove();
            }, 5000);
        }

        // Add slideIn animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideIn {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
        `;
        document.head.appendChild(style);

        // Auto submit search on typing
        let searchTimeout;
        document.querySelector('.search-input')?.addEventListener('input', function (e) {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                this.form.submit();
            }, 500);
        });
    </script>
@endpush
