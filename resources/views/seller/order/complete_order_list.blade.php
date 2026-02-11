@extends('seller.layout.app')
@push('title')
    Complete Order
@endpush
@push('css')
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- Google Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --success-gradient: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            --secondary-gradient: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
        }

        .complete-order-header {
            background: var(--primary-gradient);
            color: white;
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
        }

        .stats-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            transition: transform 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-5px);
        }

        .stats-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }

        .stats-icon.primary {
            background: rgba(102, 126, 234, 0.1);
            color: #667eea;
        }

        .stats-icon.success {
            background: rgba(67, 233, 123, 0.1);
            color: #43e97b;
        }

        .stats-icon.info {
            background: rgba(41, 117, 252, 0.1);
            color: #2575fc;
        }

        .nav-tabs {
            border-bottom: 2px solid #e9ecef;
        }

        .nav-tabs .nav-link {
            border: none;
            font-weight: 600;
            color: #6c757d;
            padding: 0.75rem 1.5rem;
            border-radius: 8px 8px 0 0;
            margin-right: 5px;
        }

        .nav-tabs .nav-link.active {
            background: var(--primary-gradient);
            color: white;
            border: none;
        }

        .order-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-weight: 600;
        }

        .badge-complete {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            color: white;
        }

        .action-btn {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .action-btn.view {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
        }

        .action-btn.view:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        .dataTables_wrapper {
            padding: 0;
        }

        .table-responsive {
            border-radius: 12px;
            overflow: hidden;
        }

        table.dataTable {
            margin: 0 !important;
            border-collapse: collapse !important;
        }

        table.dataTable thead th {
            background: #f8f9fa;
            border-bottom: 2px solid #e9ecef;
            padding: 1rem;
            font-weight: 600;
            color: #495057;
        }

        table.dataTable tbody td {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #e9ecef;
        }

        table.dataTable tbody tr:hover {
            background-color: #f8f9fa;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .complete-order-header {
                padding: 1.5rem;
                text-align: center;
            }

            .stats-card {
                margin-bottom: 1rem;
            }

            .nav-tabs .nav-link {
                padding: 0.5rem 1rem;
                font-size: 0.9rem;
                margin-bottom: 5px;
            }

            .action-btn {
                width: 100%;
                margin-bottom: 5px;
            }

            table.dataTable thead th,
            table.dataTable tbody td {
                padding: 0.75rem 0.5rem;
                font-size: 0.85rem;
            }
        }

        @media (max-width: 576px) {
            .complete-order-header h1 {
                font-size: 1.5rem;
            }

            .stats-card {
                padding: 1rem;
            }

            .stats-icon {
                width: 50px;
                height: 50px;
            }

            .table-responsive {
                margin: 0 -15px;
                width: calc(100% + 30px);
            }
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid px-3 px-md-4 px-lg-5 py-4">
        <!-- Header Section -->
        <div class="complete-order-header">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="h2 mb-3 fw-bold">
                        <span class="material-icons-round me-2">check_circle</span>
                        Complete Orders
                    </h1>
                    <p class="mb-0 opacity-75">Successfully delivered and completed orders</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="d-inline-block bg-white bg-opacity-25 rounded-pill px-4 py-2">
                        <span class="fw-bold">{{ $count }}</span> Orders Completed
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="stats-card">
                    <div class="stats-icon primary">
                        <span class="material-icons-round" style="font-size: 2rem">receipt_long</span>
                    </div>
                    <h4 class="fw-bold mb-2">Total Orders</h4>
                    <div class="d-flex align-items-center">
                        <span class="material-icons-round text-success me-2">trending_up</span>
                        <h3 class="mb-0 fw-bold">{{ $count }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card">
                    <div class="stats-icon success">
                        <span class="material-icons-round" style="font-size: 2rem">attach_money</span>
                    </div>
                    <h4 class="fw-bold mb-2">Total Revenue</h4>
                    <div class="d-flex align-items-center">
                        <span class="material-icons-round text-success me-2">trending_up</span>
                        <h3 class="mb-0 fw-bold">
                            Rs. {{ number_format($orders->sum('total_amount'), 2) }}
                        </h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card">
                    <div class="stats-icon info">
                        <span class="material-icons-round" style="font-size: 2rem">calendar_today</span>
                    </div>
                    <h4 class="fw-bold mb-2">Recent Completion</h4>
                    <div class="d-flex align-items-center">
                        <span class="material-icons-round text-info me-2">update</span>
                        <div>
                            @if($orders->first())
                                <h5 class="mb-0 fw-bold">{{ $orders->first()->delivery_date?->format('M d, Y') ?? 'N/A' }}</h5>
                            @else
                                <h5 class="mb-0 fw-bold">N/A</h5>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body p-4">
                        <ul class="nav nav-tabs nav-tabs-bordered" id="orderTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a href="{{ route('seller.pending-order') }}"
                                   class="nav-link d-flex align-items-center">
                                    <span class="material-icons-round me-2">pending_actions</span>
                                    Pending Orders
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a href="{{ route('seller.complete-order') }}"
                                   class="nav-link active d-flex align-items-center">
                                    <span class="material-icons-round me-2">check_circle</span>
                                    Complete Orders
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a href="{{ route('seller.return-order') }}"
                                   class="nav-link d-flex align-items-center">
                                    <span class="material-icons-round me-2">assignment_return</span>
                                    Return Orders
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a href="{{ route('seller.rescheduling-order') }}"
                                   class="nav-link d-flex align-items-center">
                                    <span class="material-icons-round me-2">schedule</span>
                                    Rescheduling
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-lg rounded-3">
                    <div class="card-header bg-white border-0 py-4">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h3 class="h5 mb-0 fw-bold">
                                    <span class="material-icons-round me-2">list_alt</span>
                                    Completed Orders List
                                </h3>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <button class="btn btn-light me-2" onclick="window.location.reload()">
                                    <span class="material-icons-round me-2">refresh</span>
                                    Refresh
                                </button>
                                <button class="btn btn-outline-primary" id="exportBtn">
                                    <span class="material-icons-round me-2">download</span>
                                    Export
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table id="order-table" class="table table-hover w-100">
                                <thead>
                                <tr>
                                    <th>Order No</th>
                                    <th>Invoice No</th>
                                    <th>Invoice Date</th>
                                    <th>Delivery Date</th>
                                    <th>Customer</th>
                                    <th>Phone</th>
                                    <th>District</th>
                                    <th>Total Amount</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($orders as $order)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                    <span class="material-icons-round text-success me-2">
                                                        shopping_bag
                                                    </span>
                                                <strong>{{ $order->order_id }}</strong>
                                            </div>
                                        </td>
                                        <td>
                                                <span class="badge bg-light text-dark">
                                                    {{ $order->invoice_no ?? 'N/A' }}
                                                </span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                    <span class="material-icons-round text-info me-2">
                                                        calendar_month
                                                    </span>
                                                {{ $order->invoice_date ? $order->invoice_date->format('Y.m.d') : 'N/A' }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                    <span class="material-icons-round text-success me-2">
                                                        delivery_dining
                                                    </span>
                                                {{ $order->delivery_date ? $order->delivery_date->format('Y.m.d') : 'N/A' }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                    <span class="material-icons-round text-primary me-2">
                                                        person
                                                    </span>
                                                <span class="text-truncate" style="max-width: 150px">
                                                        {{ $order->customer_name }}
                                                    </span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                    <span class="material-icons-round text-secondary me-2">
                                                        phone
                                                    </span>
                                                {{ $order->customer_phone ?? 'N/A' }}
                                            </div>
                                        </td>
                                        <td>
                                                <span class="badge bg-light text-dark">
                                                    {{ $order->district ?? 'N/A' }}
                                                </span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                    <span class="material-icons-round text-success me-2">
                                                        payments
                                                    </span>
                                                <strong class="text-success">
                                                    Rs. {{ number_format($order->total_amount, 2) }}
                                                </strong>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('seller.order.show', $order->id) }}"
                                                   class="action-btn view d-flex align-items-center">
                                                    <span class="material-icons-round me-1">visibility</span>
                                                    View
                                                </a>
                                                <button class="action-btn btn btn-outline-secondary d-flex align-items-center"
                                                        onclick="showOrderDetails({{ json_encode($order) }})">
                                                    <span class="material-icons-round me-1">info</span>
                                                    Details
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0 py-3">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <div class="text-muted">
                                    Showing {{ $orders->count() }} of {{ $count }} complete orders
                                </div>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <div class="btn-group">
                                    <button class="btn btn-light" onclick="printTable()">
                                        <span class="material-icons-round me-2">print</span>
                                        Print
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
    <!-- Excel Export -->
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

    <script>
        $(document).ready(function () {
            // Initialize DataTable
            const table = $('#order-table').DataTable({
                order: [[3, 'desc']], // Sort by delivery date descending
                pageLength: 10,
                responsive: true,
                dom: '<"row"<"col-md-6"l><"col-md-6"f>>rt<"row"<"col-md-6"i><"col-md-6"p>>',
                language: {
                    search: '<span class="material-icons-round">search</span>',
                    searchPlaceholder: 'Search orders...',
                    lengthMenu: 'Show _MENU_ entries',
                    info: 'Showing _START_ to _END_ of _TOTAL_ orders',
                    infoEmpty: 'No orders available',
                    zeroRecords: 'No matching orders found',
                    paginate: {
                        first: '<span class="material-icons-round">first_page</span>',
                        last: '<span class="material-icons-round">last_page</span>',
                        next: '<span class="material-icons-round">chevron_right</span>',
                        previous: '<span class="material-icons-round">chevron_left</span>'
                    }
                },
                columnDefs: [
                    { responsivePriority: 1, targets: 0 }, // Order No
                    { responsivePriority: 2, targets: -1 }, // Actions
                    { responsivePriority: 3, targets: 4 }, // Customer
                    { responsivePriority: 4, targets: 7 }, // Total Amount
                    { orderable: false, targets: -1 } // Disable sorting for Actions column
                ]
            });

            // Export button functionality
            $('#exportBtn').on('click', function() {
                Swal.fire({
                    title: 'Export Options',
                    html: `
                        <div class="text-start">
                            <div class="mb-3">
                                <label class="form-label">Export Format:</label>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="exportFormat" id="excel" value="excel" checked>
                                    <label class="form-check-label d-flex align-items-center" for="excel">
                                        <span class="material-icons-round me-2 text-success">description</span>
                                        Excel (.xlsx)
                                    </label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="exportFormat" id="csv" value="csv">
                                    <label class="form-check-label d-flex align-items-center" for="csv">
                                        <span class="material-icons-round me-2 text-info">table_chart</span>
                                        CSV (.csv)
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="exportFormat" id="pdf" value="pdf">
                                    <label class="form-check-label d-flex align-items-center" for="pdf">
                                        <span class="material-icons-round me-2 text-danger">picture_as_pdf</span>
                                        PDF (.pdf)
                                    </label>
                                </div>
                            </div>
                        </div>
                    `,
                    showCancelButton: true,
                    confirmButtonText: 'Export',
                    cancelButtonText: 'Cancel',
                    customClass: {
                        confirmButton: 'btn btn-primary',
                        cancelButton: 'btn btn-outline-secondary'
                    },
                    didOpen: () => {
                        // Custom styling for radio buttons
                        const radios = document.querySelectorAll('.form-check-input');
                        radios.forEach(radio => {
                            radio.addEventListener('change', function() {
                                radios.forEach(r => r.parentElement.classList.remove('active'));
                                this.parentElement.classList.add('active');
                            });
                        });
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const format = document.querySelector('input[name="exportFormat"]:checked').value;
                        exportData(format);
                    }
                });
            });

            // Show order details in modal
            window.showOrderDetails = function(order) {
                const deliveryDate = order.delivery_date ? new Date(order.delivery_date).toLocaleDateString() : 'N/A';
                const invoiceDate = order.invoice_date ? new Date(order.invoice_date).toLocaleDateString() : 'N/A';

                Swal.fire({
                    title: `Order #${order.order_id}`,
                    html: `
                        <div class="text-start">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <h6 class="fw-semibold mb-2">
                                        <span class="material-icons-round me-2 text-primary">person</span>
                                        Customer Details
                                    </h6>
                                    <p class="mb-1"><strong>Name:</strong> ${order.customer_name}</p>
                                    <p class="mb-1"><strong>Phone:</strong> ${order.customer_phone || 'N/A'}</p>
                                    <p class="mb-0"><strong>District:</strong> ${order.district || 'N/A'}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="fw-semibold mb-2">
                                        <span class="material-icons-round me-2 text-success">receipt</span>
                                        Order Details
                                    </h6>
                                    <p class="mb-1"><strong>Invoice:</strong> ${order.invoice_no || 'N/A'}</p>
                                    <p class="mb-1"><strong>Invoice Date:</strong> ${invoiceDate}</p>
                                    <p class="mb-0"><strong>Delivery Date:</strong> ${deliveryDate}</p>
                                </div>
                            </div>
                            <div class="mb-3">
                                <h6 class="fw-semibold mb-2">
                                    <span class="material-icons-round me-2 text-success">payments</span>
                                    Amount Details
                                </h6>
                                <div class="alert alert-success mb-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span>Total Amount:</span>
                                        <h4 class="mb-0 fw-bold">Rs. ${parseFloat(order.total_amount).toFixed(2)}</h4>
                                    </div>
                                </div>
                            </div>
                            ${order.customer_address ? `
                            <div class="mb-3">
                                <h6 class="fw-semibold mb-2">
                                    <span class="material-icons-round me-2 text-info">location_on</span>
                                    Delivery Address
                                </h6>
                                <p class="mb-0 text-muted">${order.customer_address}</p>
                            </div>` : ''}
                        </div>
                    `,
                    showCloseButton: true,
                    showCancelButton: true,
                    confirmButtonText: 'View Full Details',
                    cancelButtonText: 'Close',
                    customClass: {
                        confirmButton: 'btn btn-primary',
                        cancelButton: 'btn btn-outline-secondary'
                    },
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = `/seller/order/${order.id}`;
                    }
                });
            };

            // Print table function
            window.printTable = function() {
                Swal.fire({
                    title: 'Print Options',
                    html: `
                        <div class="text-start">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="printWithLogo" checked>
                                <label class="form-check-label" for="printWithLogo">
                                    Include company logo
                                </label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="printSummary" checked>
                                <label class="form-check-label" for="printSummary">
                                    Include summary statistics
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="printDate" checked>
                                <label class="form-check-label" for="printDate">
                                    Include print date
                                </label>
                            </div>
                        </div>
                    `,
                    showCancelButton: true,
                    confirmButtonText: 'Print',
                    cancelButtonText: 'Cancel',
                    customClass: {
                        confirmButton: 'btn btn-primary',
                        cancelButton: 'btn btn-outline-secondary'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const originalTable = table;
                        const printWindow = window.open('', '_blank');
                        const tableClone = $('#order-table').clone();

                        // Add print styles
                        const printStyles = `
                            <style>
                                @media print {
                                    body { font-family: Arial, sans-serif; }
                                    .print-header {
                                        text-align: center;
                                        margin-bottom: 20px;
                                        border-bottom: 2px solid #333;
                                        padding-bottom: 10px;
                                    }
                                    table {
                                        width: 100%;
                                        border-collapse: collapse;
                                        margin-top: 20px;
                                    }
                                    th {
                                        background-color: #f8f9fa !important;
                                        font-weight: bold;
                                        padding: 10px;
                                        border: 1px solid #dee2e6;
                                    }
                                    td {
                                        padding: 8px;
                                        border: 1px solid #dee2e6;
                                    }
                                    .total-row {
                                        font-weight: bold;
                                        background-color: #e9ecef;
                                    }
                                }
                            </style>
                        `;

                        const printDate = new Date().toLocaleString();
                        const header = document.querySelector('#printWithLogo').checked ?
                            '<h2>Complete Orders Report</h2>' : '';
                        const summary = document.querySelector('#printSummary').checked ?
                            `<p>Total Orders: ${$count} | Total Amount: Rs. ${$orders->sum('total_amount')}</p>` : '';
                        const date = document.querySelector('#printDate').checked ?
                            `<p>Printed on: ${printDate}</p>` : '';

                        printWindow.document.write(`
                            <html>
                                <head>
                                    <title>Complete Orders Report</title>
                                    ${printStyles}
                                </head>
                                <body>
                                    <div class="print-header">
                                        ${header}
                                        ${summary}
                                        ${date}
                                    </div>
                                    ${tableClone[0].outerHTML}
                                </body>
                            </html>
                        `);

                        printWindow.document.close();
                        printWindow.print();
                    }
                });
            };

            // Export data function
            function exportData(format) {
                Swal.fire({
                    title: 'Preparing Export',
                    html: 'Please wait while we prepare your file...',
                    timerProgressBar: true,
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Simulate export process (replace with actual export logic)
                setTimeout(() => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Export Complete',
                        text: `Your ${format.toUpperCase()} file is ready for download`,
                        confirmButtonText: 'Download',
                        customClass: {
                            confirmButton: 'btn btn-success'
                        }
                    });
                }, 2000);
            }

            // Add refresh confirmation
            $('button[onclick*="refresh"]').on('click', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Refresh Page?',
                    text: 'This will reload all data. Any unsaved changes will be lost.',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Refresh',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.reload();
                    }
                });
            });
        });
    </script>
@endpush
