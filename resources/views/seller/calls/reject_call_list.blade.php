@extends('seller.layout.app')

@push('title')
    Rejected Call List
@endpush

@push('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #3b82f6;
            --danger: #ef4444;
            --warning: #f59e0b;
            --info: #6b7280;
        }

        .page-header {
            background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%);
            color: white;
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 25px 50px rgba(239,68,68,0.3);
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
            color: #b91c1c;
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

        .status-reject {
            background: rgba(239,68,68,0.1);
            color: #ef4444;
            border: 1px solid rgba(239,68,68,0.2);
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

        .filter-section {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 20px;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #ef4444 !important;
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
                    <i class="fas fa-times-circle me-3"></i>Rejected Call List
                </h4>
                <p class="mb-0 opacity-75">Rejected customer calls ({{ $callsCount }} total)</p>
            </div>
            <button onclick="window.location.reload()" class="btn btn-light">
                <i class="fas fa-sync-alt me-2"></i>Refresh
            </button>
        </div>

        <div class="d-flex flex-wrap gap-2 mt-3">
            <a href="{{ route('call.index') }}" class="nav-btn">
                <i class="fas fa-clock"></i> Pending
            </a>
            <a href="{{ route('call.conform') }}" class="nav-btn">
                <i class="fas fa-check-circle"></i> Confirmed
            </a>
            <a href="{{ route('call.reject') }}" class="nav-btn active">
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
        <div class="card-header" style="background:linear-gradient(135deg,#f9fafb,#f3f4f6);padding:1.5rem 2rem;">
            <h5 class="mb-0 fw-bold">
                <i class="fas fa-times-circle me-2 text-danger"></i>
                Rejected Calls
                <span class="badge bg-danger ms-2">{{ $callsCount }}</span>
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="callsTable" class="table table-hover mb-0">
                    <thead>
                    <tr>
                        <th>Call #</th>
                        <th>Product Code</th>
                        <th>Product Name</th>
                        <th>Date</th>
                        <th>Customer</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($calls as $call)
                        <tr>
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
                                    <span class="status-badge status-reject">
                                        <i class="fas fa-times-circle me-1"></i>
                                        Rejected
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
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                                <h5 class="text-muted">No Rejected Calls Found</h5>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
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
                    order: [[0, 'desc']],
                    pageLength: 25,
                    lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
                    columnDefs: [
                        { orderable: false, targets: [7] }
                    ],
                    language: {
                        search: "Search:",
                        lengthMenu: "Show _MENU_ entries per page",
                        info: "Showing _START_ to _END_ of _TOTAL_ entries",
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
                    var date = data[3]; // Date column index

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
    </script>
@endpush
