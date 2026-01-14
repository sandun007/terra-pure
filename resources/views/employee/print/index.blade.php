@extends('employee.layout.app')
@push('title')
    Print Labels | Employee Dashboard
@endpush

@push('css')
    <style>
        .print-container {
            padding: 30px 0;
        }

        .print-header {
            background: linear-gradient(135deg, #4f46e5, #3b82f6);
            color: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .search-section {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .orders-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .order-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .order-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            border-color: #3b82f6;
        }

        .order-card.selected {
            border: 2px solid #10b981;
            background: #f0fdf4;
        }

        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .order-number {
            font-weight: 700;
            color: #1e293b;
            font-size: 16px;
        }

        .order-date {
            font-size: 12px;
            color: #64748b;
        }

        .order-details p {
            margin: 5px 0;
            font-size: 14px;
        }

        .order-actions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        .btn-print-single {
            padding: 8px 16px;
            background: #3b82f6;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-print-single:hover {
            background: #2563eb;
        }

        .btn-preview {
            background: #10b981;
        }

        .btn-preview:hover {
            background: #059669;
        }

        .bulk-actions {
            position: sticky;
            bottom: 20px;
            background: white;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            margin-top: 30px;
            display: none;
            z-index: 100;
        }

        .bulk-actions.show {
            display: block;
            animation: slideUp 0.3s ease;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .selected-count {
            background: #4f46e5;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 14px;
        }

        .print-options {
            background: #f8fafc;
            border-radius: 10px;
            padding: 20px;
            margin-top: 30px;
        }

        .option-card {
            background: white;
            border-radius: 8px;
            padding: 15px;
            border: 1px solid #e2e8f0;
            margin-bottom: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .option-card:hover {
            border-color: #3b82f6;
            background: #f0f9ff;
        }

        .option-card.selected {
            border-color: #10b981;
            background: #f0fdf4;
        }

        .option-title {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 5px;
        }

        .option-description {
            font-size: 13px;
            color: #64748b;
        }

        .label-preview {
            border: 2px dashed #cbd5e1;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
            min-height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8fafc;
        }

        .preview-content {
            text-align: center;
            color: #64748b;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            .label-preview {
                border: none;
                padding: 0;
                margin: 0;
            }
        }
    </style>
@endpush

@section('content')
    <div class="print-container">
        <div class="container">
            <!-- Header -->
            <div class="print-header">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div>
                        <h1 class="h3 mb-2">🖨️ Print Labels</h1>
                        <p class="mb-0 opacity-90">Print delivery labels for wholesale and seller orders</p>
                    </div>
                    <div class="mt-2 mt-md-0">
                        <a href="{{ route('employee.wholesaler_orders.index') }}" class="btn btn-light">
                            <i class="fas fa-arrow-left me-2"></i> Back to Orders
                        </a>
                    </div>
                </div>
            </div>

            <!-- Search Section -->
            <div class="search-section no-print">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Search Orders</label>
                        <div class="input-group">
                            <input type="text" class="form-control"
                                   placeholder="Search by order number, customer name..."
                                   id="searchInput">
                            <button class="btn btn-primary" type="button" onclick="searchOrders()">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Order Type</label>
                        <select class="form-select" id="orderType">
                            <option value="all">All Orders</option>
                            <option value="wholesaler">Wholesaler Orders</option>
                            <option value="seller">Seller Orders</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" id="orderStatus">
                            <option value="all">All Status</option>
                            <option value="pending">Pending</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="shipped">Shipped</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Print Options -->
            <div class="print-options no-print">
                <h5 class="mb-3">📋 Print Options</h5>
                <div class="row">
                    <div class="col-md-4">
                        <div class="option-card" onclick="selectPrintOption('single')" id="optionSingle">
                            <div class="option-title">
                                <i class="fas fa-file-alt me-2"></i> Single Label
                            </div>
                            <div class="option-description">
                                Print one label at a time. Perfect for individual orders.
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="option-card" onclick="selectPrintOption('bulk')" id="optionBulk">
                            <div class="option-title">
                                <i class="fas fa-copy me-2"></i> Bulk Labels
                            </div>
                            <div class="option-description">
                                Print multiple labels at once. Select multiple orders.
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="option-card" onclick="selectPrintOption('continuous')" id="optionContinuous">
                            <div class="option-title">
                                <i class="fas fa-print me-2"></i> Continuous Print
                            </div>
                            <div class="option-description">
                                Print labels continuously without interruption.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Label Template -->
                <div class="mt-4">
                    <label class="form-label">Label Template</label>
                    <select class="form-select" id="labelTemplate">
                        <option value="standard">Standard (4x6 inches)</option>
                        <option value="shipping">Shipping Label (4x2 inches)</option>
                        <option value="barcode">Barcode Label (2x1 inches)</option>
                        <option value="custom">Custom Size</option>
                    </select>
                </div>

                <!-- Preview -->
                <div class="mt-4">
                    <label class="form-label">Label Preview</label>
                    <div class="label-preview">
                        <div class="preview-content">
                            <i class="fas fa-tag fa-3x mb-3 text-muted"></i>
                            <p>Select an order to preview label</p>
                            <p class="small">Label will appear here based on selected template</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Orders Grid -->
            <h5 class="mt-4 mb-3">📦 Recent Orders</h5>
            <div class="orders-grid" id="ordersGrid">
                <!-- Orders will be loaded here via AJAX -->
                <div class="text-center py-5">
                    <i class="fas fa-spinner fa-spin fa-2x text-muted"></i>
                    <p class="mt-3">Loading orders...</p>
                </div>
            </div>

            <!-- Bulk Actions Bar -->
            <div class="bulk-actions no-print" id="bulkActions">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="selected-count" id="selectedCount">0</span> orders selected
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-success" onclick="printSelected()">
                            <i class="fas fa-print me-2"></i> Print Selected
                        </button>
                        <button class="btn btn-outline-secondary" onclick="clearSelection()">
                            <i class="fas fa-times me-2"></i> Clear
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let selectedOrders = [];
        let currentPrintOption = 'single';

        // Load orders on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadOrders();
            selectPrintOption('single');
        });

        function loadOrders() {
            const orderType = document.getElementById('orderType').value;
            const status = document.getElementById('orderStatus').value;

            fetch(`/api/employee/orders?type=${orderType}&status=${status}`)
                .then(response => response.json())
                .then(data => {
                    displayOrders(data.orders);
                })
                .catch(error => {
                    console.error('Error loading orders:', error);
                    document.getElementById('ordersGrid').innerHTML = `
                        <div class="text-center py-5">
                            <i class="fas fa-exclamation-triangle fa-2x text-danger"></i>
                            <p class="mt-3">Error loading orders</p>
                        </div>
                    `;
                });
        }

        function displayOrders(orders) {
            const grid = document.getElementById('ordersGrid');

            if (orders.length === 0) {
                grid.innerHTML = `
                    <div class="text-center py-5 col-span-full">
                        <i class="fas fa-inbox fa-3x text-muted"></i>
                        <h5 class="mt-3">No orders found</h5>
                        <p class="text-muted">Try changing your search criteria</p>
                    </div>
                `;
                return;
            }

            let html = '';
            orders.forEach(order => {
                const isSelected = selectedOrders.includes(order.id);
                html += `
                    <div class="order-card ${isSelected ? 'selected' : ''}"
                         onclick="toggleOrderSelection(${order.id})"
                         data-order-id="${order.id}">
                        <div class="order-header">
                            <div class="order-number">#${order.order_number}</div>
                            <span class="badge bg-${order.status === 'pending' ? 'warning' : 'success'}">
                                ${order.status}
                            </span>
                        </div>
                        <div class="order-date">
                            ${new Date(order.created_at).toLocaleDateString()}
                        </div>
                        <div class="order-details">
                            <p><strong>Customer:</strong> ${order.customer_name}</p>
                            <p><strong>Items:</strong> ${order.items_count || 0}</p>
                            <p><strong>Total:</strong> Rs. ${parseFloat(order.total_amount).toFixed(2)}</p>
                        </div>
                        <div class="order-actions">
                            <button class="btn-print-single"
                                    onclick="event.stopPropagation(); printSingleOrder(${order.id})">
                                <i class="fas fa-print"></i> Print
                            </button>
                            <button class="btn-print-single btn-preview"
                                    onclick="event.stopPropagation(); previewLabel(${order.id})">
                                <i class="fas fa-eye"></i> Preview
                            </button>
                        </div>
                    </div>
                `;
            });

            grid.innerHTML = html;
            updateBulkActions();
        }

        function toggleOrderSelection(orderId) {
            const index = selectedOrders.indexOf(orderId);

            if (index > -1) {
                selectedOrders.splice(index, 1);
                document.querySelector(`[data-order-id="${orderId}"]`).classList.remove('selected');
            } else {
                selectedOrders.push(orderId);
                document.querySelector(`[data-order-id="${orderId}"]`).classList.add('selected');
            }

            updateBulkActions();
        }

        function updateBulkActions() {
            const count = selectedOrders.length;
            const bulkActions = document.getElementById('bulkActions');
            const selectedCount = document.getElementById('selectedCount');

            selectedCount.textContent = count;

            if (count > 0) {
                bulkActions.classList.add('show');
            } else {
                bulkActions.classList.remove('show');
            }
        }

        function selectPrintOption(option) {
            currentPrintOption = option;

            // Remove selected class from all options
            document.querySelectorAll('.option-card').forEach(card => {
                card.classList.remove('selected');
            });

            // Add selected class to clicked option
            document.getElementById(`option${option.charAt(0).toUpperCase() + option.slice(1)}`)
                .classList.add('selected');
        }

        function printSingleOrder(orderId) {
            // Show loading
            Swal.fire({
                title: 'Printing...',
                text: 'Please wait while we prepare the label',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Open print page
            window.open(`/employee/print/${orderId}`, '_blank');

            // Close loading after 2 seconds
            setTimeout(() => {
                Swal.close();
            }, 2000);
        }

        function printSelected() {
            if (selectedOrders.length === 0) {
                Swal.fire({
                    title: 'No Orders Selected',
                    text: 'Please select at least one order to print.',
                    icon: 'warning'
                });
                return;
            }

            Swal.fire({
                title: 'Print Selected Labels?',
                text: `You are about to print ${selectedOrders.length} label(s).`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, Print',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Print each selected order
                    selectedOrders.forEach(orderId => {
                        printSingleOrder(orderId);
                    });

                    // Clear selection after printing
                    setTimeout(() => {
                        clearSelection();
                    }, 3000);
                }
            });
        }

        function clearSelection() {
            selectedOrders = [];
            document.querySelectorAll('.order-card').forEach(card => {
                card.classList.remove('selected');
            });
            updateBulkActions();
        }

        function searchOrders() {
            loadOrders();
        }

        function previewLabel(orderId) {
            // In a real implementation, this would show a modal with label preview
            Swal.fire({
                title: 'Label Preview',
                html: `
                    <div style="text-align: center; padding: 20px; border: 1px solid #ddd; background: white;">
                        <h5>DELIVERY LABEL</h5>
                        <hr>
                        <p><strong>Order #:</strong> ${orderId}</p>
                        <p><strong>Scan to track delivery</strong></p>
                        <div style="border: 1px solid #000; padding: 10px; margin: 10px auto; width: 200px; height: 100px;">
                            <p style="font-size: 12px;">BARCODE AREA</p>
                        </div>
                        <p style="font-size: 11px;">Printed on: ${new Date().toLocaleDateString()}</p>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Print Now',
                cancelButtonText: 'Close'
            }).then((result) => {
                if (result.isConfirmed) {
                    printSingleOrder(orderId);
                }
            });
        }

        // Event listeners for filters
        document.getElementById('orderType').addEventListener('change', loadOrders);
        document.getElementById('orderStatus').addEventListener('change', loadOrders);
        document.getElementById('searchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                searchOrders();
            }
        });
    </script>
@endpush
