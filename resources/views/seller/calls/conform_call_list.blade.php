@extends('seller.layout.app')

@push('title')
    Confirmed Call List
@endpush

@push('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #3b82f6;
            --success: #10b981;
            --danger: #ef4444;
        }

        .page-header {
            background: linear-gradient(135deg, var(--success) 0%, #059669 100%);
            color: white;
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 25px 50px rgba(16, 185, 129, 0.3);
        }

        .export-section {
            background: linear-gradient(135deg, var(--primary), #1d4ed8);
            color: white;
            padding: 1.5rem;
            border-radius: 16px;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(59, 130, 246, 0.2);
        }

        .export-btn {
            background: white;
            color: var(--primary);
            border: none;
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .export-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        }

        .status-confirm {
            background: rgba(16,185,129,0.1);
            color: var(--success);
            border: 1px solid rgba(16,185,129,0.2);
            padding: 8px 16px;
            border-radius: 25px;
            font-size: 0.8rem;
            font-weight: 700;
        }

        .main-card {
            border: none;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1);
        }

        .nav-btn {
            padding: 12px 24px;
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
            color: var(--success);
        }

        .table thead th {
            background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
            font-weight: 700;
            color: #374151;
        }

        .table tbody tr:hover {
            background: rgba(16,185,129,0.05);
            transform: translateY(-1px);
        }

        .action-btn {
            padding: 10px 20px;
            border-radius: 12px;
            font-weight: 600;
            border: none;
            transition: all 0.3s ease;
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
    </style>
@endpush

@section('content')
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div>
                <h4 class="mb-2">
                    <i class="fas fa-check-circle me-3"></i>Confirmed Call List
                </h4>
                <p class="mb-0 opacity-75">Successfully confirmed customer calls ({{ count($calls) }} total)</p>
            </div>
            <div class="d-flex gap-2">
                <button onclick="window.location.reload()" class="btn btn-light">
                    <i class="fas fa-sync-alt me-2"></i>Refresh
                </button>
            </div>
        </div>

        <div class="d-flex flex-wrap gap-2 mt-3">
            <a href="{{route('call.index')}}" class="nav-btn">
                <i class="fas fa-clock"></i> Pending
            </a>
            <a href="{{route('call.conform')}}" class="nav-btn active">
                <i class="fas fa-check-circle"></i> Confirmed
            </a>
            <a href="{{route('call.reject')}}" class="nav-btn">
                <i class="fas fa-times-circle"></i> Rejected
            </a>
            <a href="{{route('call.not_answer_call_list')}}" class="nav-btn">
                <i class="fas fa-phone-slash"></i> Not Answered
            </a>
            <a href="{{route('call.other_call_list')}}" class="nav-btn">
                <i class="fas fa-ellipsis-h"></i> Other
            </a>
        </div>
    </div>

    @if(auth()->user()->type == 'self_seller' || auth()->user()->type == 'system_seller')
        <div class="export-section">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center gap-3">
                        <div class="icon-box" style="font-size: 2.5rem;">
                            <i class="fas fa-file-excel"></i>
                        </div>
                        <div>
                            <h5 class="mb-1">Export Confirmed Calls</h5>
                            <p class="mb-0 opacity-90">Download all confirmed calls as Excel file</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <a href="{{route('calls.export')}}?status=Conform" class="export-btn">
                        <i class="fas fa-download me-2"></i>Export to Excel
                    </a>
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card main-card">
                <div class="card-header" style="background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%); padding: 1.5rem 2rem;">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-list me-2 text-success"></i>
                        Confirmed Calls
                        <span class="badge bg-success ms-2">{{ count($calls) }}</span>
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table id="callsTable" class="table table-hover mb-0">
                            <thead>
                            <tr>
                                <th><i class="fas fa-hashtag me-1"></i>Call #</th>
                                <th><i class="fas fa-barcode me-1"></i>Product Code</th>
                                <th><i class="fas fa-box me-1"></i>Product</th>
                                <th><i class="fas fa-calendar me-1"></i>Date</th>
                                <th><i class="fas fa-user me-1"></i>Customer</th>
                                <th><i class="fas fa-tag me-1"></i>Status</th>
                                <th><i class="fas fa-cogs me-1"></i>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($calls as $call)
                                <tr>
                                    <td><strong class="text-success">#{{ $call->call_number ?? $call->order_code }}</strong></td>
                                    <td>
                                        <code style="background: rgba(59,130,246,0.1); padding: 4px 8px; border-radius: 6px;">
                                            {{ $call->item_code }}
                                        </code>
                                    </td>
                                    <td><strong>{{ $call->item_name }}</strong></td>
                                    <td>
                                        <span class="badge bg-light text-dark">
                                            {{ \Carbon\Carbon::parse($call->date)->format('M d, Y') }}
                                        </span>
                                    </td>
                                    <td>
                                        <div>
                                            <strong class="d-block">{{ $call->customer_name }}</strong>
                                            <small class="text-muted">
                                                <i class="fas fa-phone me-1"></i>{{ $call->customer_phone_01 }}
                                            </small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="status-confirm">
                                            <i class="fas fa-check-circle me-1"></i>
                                            Confirmed
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('call.view', $call->id) }}" class="action-btn btn-view">
                                            <i class="fas fa-eye me-1"></i>View
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                                        <h5 class="text-muted mb-1">No Confirmed Calls</h5>
                                        <p class="text-muted">No confirmed calls found.</p>
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
@endsection

@push('script')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTable with export buttons
            $('#callsTable').DataTable({
                responsive: true,
                order: [[0, 'desc']],
                pageLength: 25,
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'excel',
                        text: '<i class="fas fa-file-excel me-1"></i>Excel',
                        className: 'btn btn-success btn-sm',
                        exportOptions: {
                            columns: ':not(:last-child)'
                        }
                    },
                    {
                        extend: 'csv',
                        text: '<i class="fas fa-file-csv me-1"></i>CSV',
                        className: 'btn btn-info btn-sm',
                        exportOptions: {
                            columns: ':not(:last-child)'
                        }
                    }
                ],
                language: {
                    search: "Search calls:",
                    lengthMenu: "Show _MENU_ calls per page",
                    info: "Showing _START_ to _END_ of _TOTAL_ calls",
                }
            });

            // Success/Error messages
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush
