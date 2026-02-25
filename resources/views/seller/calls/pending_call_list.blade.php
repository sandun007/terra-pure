@extends('seller.layout.app')

@push('title')
    Pending Call List
@endpush

@push('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #3b82f6;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --info: #6b7280;
        }

        .page-header {
            background: linear-gradient(135deg, var(--primary) 0%, #1d4ed8 100%);
            color: white;
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 25px 50px rgba(59, 130, 246, 0.3);
        }

        .nav-btn {
            padding: 10px 20px;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            background: rgba(255,255,255,0.2);
            color: white;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
        }

        .nav-btn:hover {
            transform: translateY(-2px);
            background: rgba(255,255,255,0.3);
            color: white;
        }

        .nav-btn.active {
            background: white;
            color: var(--primary);
        }

        .main-card {
            border: none;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1);
        }

        .status-badge {
            padding: 6px 14px;
            border-radius: 25px;
            font-size: 0.8rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .status-pending {
            background: rgba(245,158,11,0.1);
            color: var(--warning);
            border: 1px solid rgba(245,158,11,0.2);
        }

        .action-btn {
            padding: 8px 12px;
            border-radius: 10px;
            font-weight: 600;
            border: none;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 0.85rem;
            cursor: pointer;
        }

        .btn-view {
            background: var(--primary);
            color: white;
        }

        .btn-view:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(59,130,246,0.4);
            color: white;
        }

        .btn-edit {
            background: var(--warning);
            color: white;
        }

        .btn-edit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(245,158,11,0.4);
            color: white;
        }

        .btn-print {
            background: var(--info);
            color: white;
        }

        .btn-print:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(107,114,128,0.4);
            color: white;
        }

        .btn-delete {
            background: var(--danger);
            color: white;
            border: none;
        }

        .btn-delete:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(239,68,68,0.4);
            color: white;
        }

        .filter-section {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 20px;
        }

        .delete-form {
            display: inline-block;
            margin: 0;
            padding: 0;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: var(--primary) !important;
            color: white !important;
            border: none;
        }
    </style>
@endpush

@section('content')
    @php
        $callsCount = is_countable($calls) ? count($calls) : 0;
    @endphp

    <div class="page-header">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div>
                <h4 class="mb-2">
                    <i class="fas fa-headset me-3"></i>Pending Call List
                </h4>
                <p class="mb-0 opacity-75">Manage pending customer calls ({{ $callsCount }} total)</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('call.csv_upload') }}" class="btn btn-light">
                    <i class="fas fa-upload me-2"></i>CSV Upload
                </a>
                <button onclick="window.location.reload()" class="btn btn-light">
                    <i class="fas fa-sync-alt me-2"></i>Refresh
                </button>
            </div>
        </div>

        <div class="d-flex flex-wrap gap-2 mt-3">
            <a href="{{ route('call.index') }}" class="nav-btn active">
                <i class="fas fa-clock"></i> Pending
            </a>
            <a href="{{ route('call.conform') }}" class="nav-btn">
                <i class="fas fa-check-circle"></i> Confirmed
            </a>
            <a href="{{ route('call.reject') }}" class="nav-btn">
                <i class="fas fa-times-circle"></i> Rejected
            </a>
            <a href="{{ route('call.not_answer_call_list') }}" class="nav-btn">
                <i class="fas fa-phone-slash"></i> Not Answered
            </a>
            <a href="{{ route('call.other_call_list') }}" class="nav-btn">
                <i class="fas fa-ellipsis-h"></i> Other
            </a>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <div class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-semibold">Search</label>
                <input type="text" id="searchInput" class="form-control" placeholder="Search calls...">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">From Date</label>
                <input type="date" id="dateFrom" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">To Date</label>
                <input type="date" id="dateTo" class="form-control">
            </div>
            <div class="col-md-3">
                <button id="clearFilter" class="btn btn-secondary w-100">
                    <i class="fas fa-times me-1"></i>Clear Filters
                </button>
            </div>
        </div>
    </div>

    <!-- Main Card -->
    <div class="card main-card">
        <div class="card-header" style="background: linear-gradient(135deg,#f9fafb,#f3f4f6); padding:1.5rem 2rem;">
            <h5 class="mb-0 fw-bold">
                <i class="fas fa-clock me-2 text-warning"></i>
                Pending Calls
                <span class="badge bg-warning ms-2">{{ $callsCount }}</span>
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="callsTable" class="table table-hover mb-0">
                    <thead>
                    <tr>
                        <th width="50">
                            <input type="checkbox" id="selectAll">
                        </th>
                        <th>Call #</th>
                        <th>Product Code</th>
                        <th>Product Name</th>
                        <th>Date</th>
                        <th>Customer</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th width="200">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($calls as $call)
                        @php
                            $statusIcon = 'fa-clock';
                            $statusClass = 'status-pending';
                        @endphp
                        <tr>
                            <td>
                                <input type="checkbox" class="call-checkbox" value="{{ $call->id }}">
                            </td>
                            <td>
                                <strong>#{{ $call->call_number ?? $call->order_code }}</strong>
                            </td>
                            <td>
                                <code style="background:rgba(59,130,246,0.1);padding:4px 8px;border-radius:6px;">
                                    {{ $call->item_code }}
                                </code>
                            </td>
                            <td>
                                <strong>{{ $call->item_name }}</strong>
                            </td>
                            <td>
                                    <span class="badge bg-light text-dark">
                                        {{ $call->date ? \Carbon\Carbon::parse($call->date)->format('M d, Y') : '-' }}
                                    </span>
                            </td>
                            <td>
                                <strong>{{ $call->customer_name }}</strong>
                            </td>
                            <td>
                                <a href="tel:{{ $call->customer_phone_01 }}" class="text-decoration-none">
                                    {{ $call->customer_phone_01 }}
                                </a>
                            </td>
                            <td>
                                    <span class="status-badge {{ $statusClass }}">
                                        <i class="fas {{ $statusIcon }}"></i>
                                        {{ $call->status }}
                                    </span>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <!-- View Button -->
                                    <a href="{{ route('call.view', $call->id) }}"
                                       class="action-btn btn-view"
                                       title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <!-- Edit Button -->
                                    <a href="{{ route('call.edit', $call->id) }}"
                                       class="action-btn btn-edit"
                                       title="Edit Call">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <!-- Print Button -->
                                    <a href="{{ route('call.print', $call->id) }}"
                                       class="action-btn btn-print"
                                       target="_blank"
                                       title="Print Call">
                                        <i class="fas fa-print"></i>
                                    </a>

                                    <!-- Delete Button with Form -->
                                    @if($call->status === 'Pending')
                                        <form action="{{ route('call.destroy', $call->id) }}"
                                              method="POST"
                                              class="delete-form"
                                              onsubmit="return confirmDelete(event, this)">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action-btn btn-delete" title="Delete Call">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                                <h5 class="text-muted">No Pending Calls Found</h5>
                                <a href="{{ route('call.csv_upload') }}" class="btn btn-primary mt-3">
                                    <i class="fas fa-upload me-2"></i>Upload CSV
                                </a>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Bulk Action Bar -->
    @if(count($calls) > 0)
        <div class="card mt-3">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <strong><span id="selectedCount">0</span></strong> calls selected
                    </div>
                    <div class="col-md-6 text-end">
                        <div class="btn-group">
                            <button type="button" class="btn btn-success" onclick="bulkUpdate('Conform')">
                                <i class="fas fa-check-circle me-1"></i>Confirm
                            </button>
                            <button type="button" class="btn btn-danger" onclick="bulkUpdate('Reject')">
                                <i class="fas fa-times-circle me-1"></i>Reject
                            </button>
                            <button type="button" class="btn btn-warning" onclick="bulkUpdate('Not Answer')">
                                <i class="fas fa-phone-slash me-1"></i>Not Answer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function () {
            // Initialize DataTable
            if (typeof $.fn.DataTable !== 'undefined') {
                var table = $('#callsTable').DataTable({
                    order: [[1, 'desc']],
                    pageLength: 25,
                    lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
                    columnDefs: [
                        { orderable: false, targets: [0, 8] }
                    ],
                    language: {
                        search: "Search:",
                        lengthMenu: "Show _MENU_ entries per page",
                        info: "Showing _START_ to _END_ of _TOTAL_ entries",
                        infoEmpty: "Showing 0 to 0 of 0 entries",
                        infoFiltered: "(filtered from _MAX_ total entries)",
                        zeroRecords: "No matching records found",
                        paginate: {
                            first: "First",
                            last: "Last",
                            next: "Next",
                            previous: "Previous"
                        }
                    }
                });

                // Custom search
                $('#searchInput').on('keyup', function () {
                    table.search(this.value).draw();
                });

                // Date range filter
                $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
                    var min = $('#dateFrom').val();
                    var max = $('#dateTo').val();
                    var date = data[4];

                    if (!min && !max) return true;
                    if (!date) return true;

                    var rowDate = new Date(date).toISOString().split('T')[0];

                    if (min && max) {
                        return rowDate >= min && rowDate <= max;
                    } else if (min) {
                        return rowDate >= min;
                    } else if (max) {
                        return rowDate <= max;
                    }
                    return true;
                });

                $('#dateFrom, #dateTo').on('change', function () {
                    table.draw();
                });

                $('#clearFilter').on('click', function () {
                    $('#searchInput').val('');
                    $('#dateFrom').val('');
                    $('#dateTo').val('');
                    table.search('').draw();
                });
            }

            // Select All functionality
            $('#selectAll').on('change', function() {
                $('.call-checkbox').prop('checked', $(this).prop('checked'));
                updateSelectedCount();
            });

            // Individual checkbox change
            $(document).on('change', '.call-checkbox', function() {
                updateSelectedCount();
                var allChecked = $('.call-checkbox:checked').length === $('.call-checkbox').length;
                $('#selectAll').prop('checked', allChecked);
            });

            // Update selected count
            function updateSelectedCount() {
                var count = $('.call-checkbox:checked').length;
                $('#selectedCount').text(count);
            }

            // Session messages
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

        // Confirm Delete function
        function confirmDelete(event, form) {
            event.preventDefault();

            Swal.fire({
                title: 'Are you sure?',
                text: "This call will be permanently deleted!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Deleting...',
                        text: 'Please wait',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        allowEnterKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                            form.submit();
                        }
                    });
                }
            });

            return false;
        }

        // Bulk Update function
        function bulkUpdate(status) {
            var selectedIds = [];
            $('.call-checkbox:checked').each(function() {
                selectedIds.push($(this).val());
            });

            if (selectedIds.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No Selection',
                    text: 'Please select at least one call to update.'
                });
                return;
            }

            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to mark " + selectedIds.length + " calls as " + status + "?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, update them!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("call.bulk_status") }}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            call_ids: selectedIds,
                            status: status
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success!',
                                    text: response.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: response.message
                                });
                            }
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'An error occurred while updating calls.'
                            });
                        }
                    });
                }
            });
        }
    </script>
@endpush
