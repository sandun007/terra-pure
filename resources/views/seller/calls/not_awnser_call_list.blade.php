@extends('seller.layout.app')

@push('title')
    Not Answer Call List
@endpush

@push('css')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            display: inline-block;
            text-transform: capitalize;
        }

        .status-reject {
            background-color: #ffe6e6;
            color: #dc3545;
            border: 1px solid #dc3545;
        }

        .status-confirm {
            background-color: #d4edda;
            color: #28a745;
            border: 1px solid #28a745;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffc107;
        }

        .status-not-answer {
            background-color: #cce5ff;
            color: #004085;
            border: 1px solid #007bff;
        }

        .status-whatsapp {
            background-color: #d1ecf1;
            color: #0c5460;
            border: 1px solid #17a2b8;
        }

        .action-buttons {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
        }

        .btn-sm {
            padding: 5px 10px;
            font-size: 12px;
        }

        .btn-view {
            background-color: #007bff;
            color: white;
            border: none;
        }

        .btn-view:hover {
            background-color: #0056b3;
        }

        .btn-edit {
            background-color: #ffc107;
            color: #212529;
            border: none;
        }

        .btn-edit:hover {
            background-color: #e0a800;
        }

        .btn-delete {
            background-color: #dc3545;
            color: white;
            border: none;
        }

        .btn-delete:hover {
            background-color: #c82333;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
        }

        .filter-section {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }

        .date-filter {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
        }

        .search-box {
            margin-bottom: 15px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .date-filter {
                flex-direction: column;
                align-items: stretch;
            }

            .action-buttons {
                flex-direction: column;
            }
        }
    </style>
@endpush

@section('content')
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4 class="fw-bold"><i class="fas fa-phone-slash me-2"></i>Not Answer Call List</h4>
            </div>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <div class="page-btn">
                <a href="{{ route('call.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-clock me-2"></i>Pending Call
                </a>
            </div>
            <div class="page-btn">
                <a href="{{ route('call.conform') }}" class="btn btn-outline-success">
                    <i class="fas fa-check-circle me-2"></i>Confirmed
                </a>
            </div>
            <div class="page-btn">
                <a href="{{ route('call.reject') }}" class="btn btn-outline-danger">
                    <i class="fas fa-times-circle me-2"></i>Rejected
                </a>
            </div>
            <div class="page-btn">
                <a href="{{ route('call.not_answer_call_list') }}" class="btn btn-outline-info active">
                    <i class="fas fa-phone-slash me-2"></i>Not Answered
                </a>
            </div>
            <div class="page-btn">
                <a href="{{ route('call.other_call_list') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-ellipsis-h me-2"></i>Other
                </a>
            </div>
        </div>
        <ul class="table-top-head">
            <li>
                <a href="#" data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh" onclick="window.location.reload()">
                    <i class="fas fa-sync-alt"></i>
                </a>
            </li>
            <li>
                <a href="#" data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header">
                    <i class="fas fa-chevron-up"></i>
                </a>
            </li>
        </ul>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <div class="row">
            <div class="col-md-4">
                <div class="search-box">
                    <label for="searchInput" class="form-label">Search:</label>
                    <input type="text" id="searchInput" class="form-control" placeholder="Search by order no, customer, product...">
                </div>
            </div>
            <div class="col-md-8">
                <div class="date-filter">
                    <div class="flex-grow-1">
                        <label for="dateFrom" class="form-label">From:</label>
                        <input type="date" id="dateFrom" class="form-control">
                    </div>
                    <div class="flex-grow-1">
                        <label for="dateTo" class="form-label">To:</label>
                        <input type="date" id="dateTo" class="form-control">
                    </div>
                    <div class="align-self-end">
                        <button id="clearFilter" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Clear
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="product-table" class="display table table-striped table-hover" style="width:100%">
                            <thead class="table-dark">
                            <tr>
                                <th class="d-none">ID</th>
                                <th>Order No</th>
                                <th>Product Name</th>
                                <th>Order Date</th>
                                <th>Customer Name</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($orders as $order)
                                @php
                                    $status = strtolower($order->status);
                                    $statusClass = match($status) {
                                        'reject' => 'status-reject',
                                        'conform', 'confirm' => 'status-confirm',
                                        'pending' => 'status-pending',
                                        'not answer', 'not anser' => 'status-not-answer',
                                        'whatsapp' => 'status-whatsapp',
                                        default => 'status-pending',
                                    };
                                @endphp
                                <tr>
                                    <td class="d-none">{{ $order->id }}</td>
                                    <td>
                                        <span class="fw-bold">{{ $order->order_no }}</span>
                                    </td>
                                    <td>{{ $order->item_name }}</td>
                                    <td>
                                        <i class="far fa-calendar-alt me-1"></i>
                                        {{ \Carbon\Carbon::parse($order->date)->format('d M, Y') }}
                                    </td>
                                    <td>
                                        <i class="far fa-user me-1"></i>
                                        {{ $order->customer_name }}
                                    </td>
                                    <td>
                                            <span class="status-badge {{ $statusClass }}">
                                                <i class="fas
                                                    @if($status == 'reject') fa-times-circle
                                                    @elseif(in_array($status, ['conform', 'confirm'])) fa-check-circle
                                                    @elseif($status == 'pending') fa-clock
                                                    @elseif(in_array($status, ['not answer', 'not anser'])) fa-phone-slash
                                                    @elseif($status == 'whatsapp') fa-whatsapp
                                                    @else fa-circle
                                                    @endif me-1">
                                                </i>
                                                {{ ucfirst($order->status) }}
                                            </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('call.view', $order->id) }}"
                                               class="btn btn-sm btn-view"
                                               title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('call.edit', $order->id) }}"
                                               class="btn btn-sm btn-edit"
                                               title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button"
                                                    class="btn btn-sm btn-delete"
                                                    onclick="confirmDelete({{ $order->id }})"
                                                    title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i class="fas fa-inbox fa-3x mb-3 text-muted"></i>
                                        <h5 class="text-muted">No Not Answer Calls Found</h5>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this call record? This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function () {
            // Initialize DataTable with advanced features
            var table = $('#product-table').DataTable({
                order: [[0, 'desc']],
                responsive: true,
                pageLength: 25,
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                dom: '<"row"<"col-sm-12 col-md-6"B><"col-sm-12 col-md-6"f>>' +
                    '<"row"<"col-sm-12"tr>>' +
                    '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                buttons: [
                    {
                        extend: 'copy',
                        text: '<i class="fas fa-copy"></i> Copy',
                        className: 'btn btn-sm btn-outline-secondary'
                    },
                    {
                        extend: 'excel',
                        text: '<i class="fas fa-file-excel"></i> Excel',
                        className: 'btn btn-sm btn-outline-success'
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="fas fa-file-pdf"></i> PDF',
                        className: 'btn btn-sm btn-outline-danger'
                    },
                    {
                        extend: 'print',
                        text: '<i class="fas fa-print"></i> Print',
                        className: 'btn btn-sm btn-outline-primary'
                    }
                ],
                language: {
                    emptyTable: "No data available",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    infoEmpty: "Showing 0 to 0 of 0 entries",
                    lengthMenu: "Show _MENU_ entries",
                    search: "Search:",
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "Next",
                        previous: "Previous"
                    }
                }
            });

            // Custom search functionality
            $('#searchInput').on('keyup', function() {
                table.search(this.value).draw();
            });

            // Date range filter
            $.fn.dataTable.ext.search.push(
                function(settings, data, dataIndex) {
                    var min = $('#dateFrom').val();
                    var max = $('#dateTo').val();
                    var date = data[3]; // Index of date column

                    if (min === '' && max === '') return true;

                    var orderDate = moment(date, 'DD MMM, YYYY').format('YYYY-MM-DD');

                    if (min !== '' && max !== '') {
                        return orderDate >= min && orderDate <= max;
                    } else if (min !== '') {
                        return orderDate >= min;
                    } else if (max !== '') {
                        return orderDate <= max;
                    }
                    return true;
                }
            );

            $('#dateFrom, #dateTo').on('change', function() {
                table.draw();
            });

            // Clear filters
            $('#clearFilter').on('click', function() {
                $('#searchInput').val('');
                $('#dateFrom').val('');
                $('#dateTo').val('');
                table.search('').columns().search('').draw();
            });

            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });

        // Delete confirmation function
        function confirmDelete(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Create a form and submit it
                    var form = document.createElement('form');
                    form.method = 'POST';
                    {{--form.action = '{{ route("call.destroy", "") }}/' + id;--}}

                    var csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = '{{ csrf_token() }}';

                    var methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';

                    form.appendChild(csrfInput);
                    form.appendChild(methodInput);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        // Show success message if exists
        @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('success') }}',
            timer: 3000,
            showConfirmButton: false
        });
        @endif

        // Show error message if exists
        @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: '{{ session('error') }}',
            timer: 3000,
            showConfirmButton: false
        });
        @endif

        // Collapse header functionality
        document.getElementById('collapse-header')?.addEventListener('click', function(e) {
            e.preventDefault();
            document.querySelector('.page-header').classList.toggle('collapsed');
        });
    </script>
@endpush
