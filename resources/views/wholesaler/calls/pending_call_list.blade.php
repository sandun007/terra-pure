@extends('wholesaler.layouts.app')

@push('title')
    Pending Call List
@endpush

@push('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #4f46e5;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --info: #6b7280;
        }
        .page-header {
            background: linear-gradient(135deg, var(--primary) 0%, #4338ca 100%);
            color: white;
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 25px 50px rgba(79,70,229,0.3);
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
        .nav-btn:hover { transform: translateY(-2px); background: rgba(255,255,255,0.3); color: white; }
        .nav-btn.active { background: white; color: var(--primary); }
        .main-card { border: none; border-radius: 24px; box-shadow: 0 20px 60px rgba(0,0,0,0.1); }
        .status-badge { padding: 6px 14px; border-radius: 25px; font-size: 0.8rem; font-weight: 700; display: inline-flex; align-items: center; gap: 5px; }
        .status-pending { background: rgba(245,158,11,0.1); color: var(--warning); border: 1px solid rgba(245,158,11,0.2); }
        .action-btn { padding: 8px 12px; border-radius: 10px; font-weight: 600; border: none; transition: all 0.3s; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; font-size: 0.85rem; cursor: pointer; }
        .btn-view { background: var(--primary); color: white; }
        .btn-view:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(79,70,229,0.4); color: white; }
        .btn-edit { background: var(--warning); color: white; }
        .btn-edit:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(245,158,11,0.4); color: white; }
        .btn-print { background: var(--info); color: white; }
        .btn-print:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(107,114,128,0.4); color: white; }
        .btn-delete { background: var(--danger); color: white; border: none; }
        .btn-delete:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(239,68,68,0.4); color: white; }
        .filter-section { background: #f8f9fa; border-radius: 12px; padding: 16px; margin-bottom: 20px; }
        .delete-form { display: inline-block; margin: 0; padding: 0; }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current { background: var(--primary) !important; color: white !important; border: none; }

        /* Additional styles for better UX */
        .call-checkbox {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }
        #selectAll {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }
        .bulk-action-bar {
            background: white;
            border-radius: 12px;
            padding: 15px 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-top: 20px;
        }
        .badge-count {
            background: var(--primary);
            color: white;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 12px;
            margin-left: 5px;
        }
        .table-responsive {
            overflow-x: auto;
        }
        table.dataTable {
            width: 100% !important;
        }
        .dataTables_wrapper {
            padding: 0;
        }
    </style>
@endpush

@section('content')
    @php
        $callsCount = is_countable($calls) ? count($calls) : 0;
        $hasCalls = $callsCount > 0;
    @endphp

    <div class="container-fluid px-3 px-md-4 px-lg-5 py-4">
        <!-- Page Header -->
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                <div>
                    <h4 class="mb-2"><i class="fas fa-headset me-3"></i>Pending Call List</h4>
                    <p class="mb-0 opacity-75">Manage pending customer calls (<span id="totalCountLabel">{{ $callsCount }}</span> total)</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('wholesaler.call.csv_upload') }}" class="btn btn-light">
                        <i class="fas fa-upload me-2"></i>CSV Upload
                    </a>
                    <button onclick="window.location.reload()" class="btn btn-light">
                        <i class="fas fa-sync-alt me-2"></i>Refresh
                    </button>
                </div>
            </div>

            <!-- Navigation Tabs -->
            <div class="d-flex flex-wrap gap-2 mt-3">
                <a href="{{ route('wholesaler.call.index') }}" class="nav-btn active">
                    <i class="fas fa-clock"></i> Pending
                    <span class="badge bg-warning text-dark ms-1">{{ $callsCount }}</span>
                </a>
                <a href="{{ route('wholesaler.call.conform') }}" class="nav-btn">
                    <i class="fas fa-check-circle"></i> Confirmed
                </a>
                <a href="{{ route('wholesaler.call.reject') }}" class="nav-btn">
                    <i class="fas fa-times-circle"></i> Rejected
                </a>
                <a href="{{ route('wholesaler.call.not_answer_call_list') }}" class="nav-btn">
                    <i class="fas fa-phone-slash"></i> Not Answered
                </a>
                <a href="{{ route('wholesaler.call.other_call_list') }}" class="nav-btn">
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
            <div class="card-header" style="background:linear-gradient(135deg,#f9fafb,#f3f4f6);padding:1.5rem 2rem;">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-clock me-2 text-warning"></i>Pending Calls
                        <span class="badge bg-warning ms-2">{{ $callsCount }}</span>
                    </h5>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="callsTable" class="table table-hover mb-0" style="width:100%">
                        <thead>
                        <tr>
                            <th width="50"><input type="checkbox" id="selectAll" class="form-check-input"></th>
                            <th>Call #</th>
                            <th>Product Code</th>
                            <th>Product Name</th>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th width="220">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($calls as $call)
                            <tr data-call-id="{{ $call->id }}">
                                <td>
                                    <input type="checkbox" class="call-checkbox form-check-input" value="{{ $call->id }}">
                                </td>
                                <td>
                                    <strong>#{{ $call->call_number ?? $call->order_code ?? 'N/A' }}</strong>
                                </td>
                                <td>
                                    <code style="background:rgba(79,70,229,0.1);padding:4px 8px;border-radius:6px;">
                                        {{ $call->item_code ?? $call->product_code ?? 'N/A' }}
                                    </code>
                                </td>
                                <td>
                                    <strong>{{ $call->item_name ?? $call->product_name ?? 'N/A' }}</strong>
                                </td>
                                <td>
                                        <span class="badge bg-light text-dark">
                                            {{ $call->date ? \Carbon\Carbon::parse($call->date)->format('M d, Y') :
                                               ($call->created_at ? \Carbon\Carbon::parse($call->created_at)->format('M d, Y') : '-') }}
                                        </span>
                                </td>
                                <td>
                                    <strong>{{ $call->customer_name ?? 'N/A' }}</strong>
                                </td>
                                <td>
                                    <a href="tel:{{ $call->customer_phone_01 ?? $call->customer_phone ?? '#' }}"
                                       class="text-decoration-none">
                                        {{ $call->customer_phone_01 ?? $call->customer_phone ?? 'N/A' }}
                                    </a>
                                </td>
                                <td>
                                        <span class="status-badge status-pending">
                                            <i class="fas fa-clock me-1"></i> {{ $call->status ?? 'Pending' }}
                                        </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-1 flex-wrap">
                                        <a href="{{ route('wholesaler.call.view', $call->id) }}"
                                           class="action-btn btn-view"
                                           title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('wholesaler.call.edit', $call->id) }}"
                                           class="action-btn btn-edit"
                                           title="Edit Call">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('wholesaler.call.print', $call->id) }}"
                                           class="action-btn btn-print"
                                           target="_blank"
                                           title="Print Call">
                                            <i class="fas fa-print"></i>
                                        </a>
                                        @if(($call->status ?? 'Pending') === 'Pending')
                                            <form action="{{ route('wholesaler.call.destroy', $call->id) }}"
                                                  method="POST"
                                                  class="delete-form"
                                                  onsubmit="return confirmDelete(event, this)">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="action-btn btn-delete" title="Delete">
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
                                    <p class="text-muted mb-3">Upload a CSV file to create new calls</p>
                                    <a href="{{ route('wholesaler.call.csv_upload') }}" class="btn btn-primary mt-3">
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

        <!-- Bulk Actions Bar - Only show if there are calls -->
        @if($hasCalls)
            <div class="bulk-action-bar">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <strong><span id="selectedCount">0</span></strong> calls selected
                        <span class="badge-count" id="totalSelected">0</span>
                    </div>
                    <div class="col-md-6 text-end">
                        <div class="btn-group">
                            <button type="button" class="btn btn-success" onclick="bulkUpdate('Conform')">
                                <i class="fas fa-check-circle me-1"></i>Confirm Selected
                            </button>
                            <button type="button" class="btn btn-danger" onclick="bulkUpdate('Reject')">
                                <i class="fas fa-times-circle me-1"></i>Reject Selected
                            </button>
                            <button type="button" class="btn btn-warning" onclick="bulkUpdate('Not Answer')">
                                <i class="fas fa-phone-slash me-1"></i>Not Answer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Delete Confirmation Modal (Hidden by default) -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Confirm Delete
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this call?</p>
                    <p class="text-muted small">This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function () {
            let table;
            let deleteForm = null;

            @if($hasCalls)
            // Initialize DataTable only if there are calls
            table = $('#callsTable').DataTable({
                order: [[1, 'desc']], // Sort by Call # descending
                pageLength: 25,
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
                columnDefs: [
                    { orderable: false, targets: [0, 8] } // Disable sorting on checkbox and actions columns
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
                },
                drawCallback: function(settings) {
                    // Update total count after each draw
                    $('#totalCountLabel').text(this.api().data().length);
                    updateSelectedCount();
                }
            });

            // Custom search input
            $('#searchInput').on('keyup', function() {
                table.search(this.value).draw();
            });

            // Date range filter
            $.fn.dataTable.ext.search.push(function(settings, data) {
                if (settings.nTable.id !== 'callsTable') return true;

                var min = $('#dateFrom').val();
                var max = $('#dateTo').val();

                if (!min && !max) return true;

                var dateText = data[4]; // Date column index
                if (!dateText) return true;

                try {
                    // Extract date from text (e.g., "Dec 03, 2025")
                    var dateMatch = dateText.match(/([A-Za-z]{3} \d{2}, \d{4})/);
                    if (!dateMatch) return true;

                    var rowDate = new Date(dateMatch[0]);
                    var rowDateStr = rowDate.toISOString().split('T')[0];

                    if (min && max) return rowDateStr >= min && rowDateStr <= max;
                    if (min) return rowDateStr >= min;
                    if (max) return rowDateStr <= max;
                } catch(e) {
                    console.log('Date parsing error:', e);
                    return true;
                }

                return true;
            });

            $('#dateFrom, #dateTo').on('change', function() {
                table.draw();
            });

            $('#clearFilter').on('click', function() {
                $('#searchInput').val('');
                $('#dateFrom').val('');
                $('#dateTo').val('');
                table.search('').columns().search('').draw();
            });

            // Select All functionality
            $('#selectAll').on('change', function() {
                $('.call-checkbox').prop('checked', $(this).prop('checked'));
                updateSelectedCount();
            });

            $(document).on('change', '.call-checkbox', function() {
                updateSelectedCount();
                $('#selectAll').prop('checked',
                    $('.call-checkbox:checked').length === $('.call-checkbox').length &&
                    $('.call-checkbox').length > 0
                );
            });

            function updateSelectedCount() {
                var count = $('.call-checkbox:checked').length;
                $('#selectedCount').text(count);
                $('#totalSelected').text(count);
            }
            @else
            // No calls - disable filter inputs
            $('#searchInput, #dateFrom, #dateTo, #clearFilter').prop('disabled', true);

            // Add a simple message
            console.log('No pending calls to display');
            @endif

            // Session messages with SweetAlert
            @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: @json(session('success')),
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
            @endif

            @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: @json(session('error')),
                timer: 4000,
                showConfirmButton: true
            });
            @endif

            // Auto-dismiss alerts after 5 seconds
            setTimeout(() => {
                $('.alert').fadeOut('slow', function() {
                    $(this).remove();
                });
            }, 5000);
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
                reverseButtons: true,
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return new Promise((resolve) => {
                        setTimeout(() => {
                            form.submit();
                            resolve();
                        }, 100);
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

            let statusText = '';
            let confirmColor = '';

            switch(status) {
                case 'Conform':
                    statusText = 'Confirm';
                    confirmColor = '#10b981';
                    break;
                case 'Reject':
                    statusText = 'Reject';
                    confirmColor = '#ef4444';
                    break;
                case 'Not Answer':
                    statusText = 'Mark as Not Answered';
                    confirmColor = '#f59e0b';
                    break;
                default:
                    statusText = status;
                    confirmColor = '#3085d6';
            }

            Swal.fire({
                title: 'Are you sure?',
                text: `Mark ${selectedIds.length} selected call(s) as ${status}?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: confirmColor,
                cancelButtonColor: '#6c757d',
                confirmButtonText: `Yes, ${statusText}`,
                cancelButtonText: 'Cancel',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return $.ajax({
                        url: '{{ route("wholesaler.call.bulk_status") }}',
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
                            let errorMsg = 'An error occurred while updating calls.';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMsg = xhr.responseJSON.message;
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: errorMsg
                            });
                        }
                    });
                }
            });
        }

        // Individual call actions
        function viewCall(id) {
            window.location.href = '{{ route("wholesaler.call.view", "") }}/' + id;
        }

        function editCall(id) {
            window.location.href = '{{ route("wholesaler.call.edit", "") }}/' + id;
        }

        function printCall(id) {
            window.open('{{ route("wholesaler.call.print", "") }}/' + id, '_blank');
        }
    </script>
@endpush
