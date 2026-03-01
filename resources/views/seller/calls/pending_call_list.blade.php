@extends('seller.layout.app')

@push('title')
    Pending Call List
@endpush

@push('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        :root { --primary:#3b82f6; --success:#10b981; --danger:#ef4444; --warning:#f59e0b; }
        .page-header { background:linear-gradient(135deg,var(--primary) 0%,#1d4ed8 100%); color:white; border-radius:20px; padding:2rem; margin-bottom:2rem; box-shadow:0 25px 50px rgba(59,130,246,.3); }
        .nav-btn { padding:10px 20px; border-radius:12px; font-weight:600; text-decoration:none; display:inline-flex; align-items:center; gap:8px; transition:all .3s; background:rgba(255,255,255,.2); color:white; border:1px solid rgba(255,255,255,.2); }
        .nav-btn:hover { transform:translateY(-2px); background:rgba(255,255,255,.3); color:white; }
        .nav-btn.active { background:white; color:var(--primary); }
        .main-card { border:none; border-radius:24px; box-shadow:0 20px 60px rgba(0,0,0,.1); }
        .action-btn { padding:6px 12px; border-radius:10px; font-weight:600; border:none; transition:all .3s; text-decoration:none; display:inline-flex; align-items:center; gap:4px; font-size:.82rem; cursor:pointer; }
        .btn-view    { background:var(--primary); color:white; }
        .btn-view:hover    { transform:translateY(-2px); box-shadow:0 8px 25px rgba(59,130,246,.4); color:white; }
        .btn-confirm { background:var(--success); color:white; }
        .btn-confirm:hover { transform:translateY(-2px); box-shadow:0 8px 25px rgba(16,185,129,.4); color:white; }
        .btn-reject  { background:var(--danger); color:white; }
        .btn-reject:hover  { transform:translateY(-2px); color:white; }
        .btn-delete  { background:#6b7280; color:white; border:none; }
        .btn-delete:hover  { transform:translateY(-2px); color:white; }
        .status-badge { padding:5px 12px; border-radius:25px; font-size:.78rem; font-weight:700; display:inline-flex; align-items:center; gap:5px; }
        .status-pending  { background:rgba(245,158,11,.1); color:var(--warning); border:1px solid rgba(245,158,11,.2); }
        .status-conform  { background:rgba(16,185,129,.1); color:var(--success); border:1px solid rgba(16,185,129,.2); }
        .status-reject   { background:rgba(239,68,68,.1); color:var(--danger); border:1px solid rgba(239,68,68,.2); }
        .city-badge { font-family:monospace; font-size:11px; background:#eef2ff; color:#4f46e5; padding:2px 8px; border-radius:4px; }
        .city-warn  { font-family:monospace; font-size:11px; background:#fef3c7; color:#92400e; padding:2px 8px; border-radius:4px; }
        .filter-section { background:#f8f9fa; border-radius:12px; padding:16px; margin-bottom:20px; }
        .delete-form { display:inline-block; margin:0; padding:0; }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current { background:var(--primary)!important; color:white!important; border:none; }
        .dataTables_filter input { border:2px solid #e5e7eb; border-radius:8px; padding:.375rem .75rem; }
        .dataTables_filter input:focus { border-color:var(--primary); outline:none; }
        .bulk-bar { background:#eef2ff; border:1px solid rgba(79,70,229,.2); border-radius:12px; padding:12px 16px; margin-bottom:16px; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px; }
    </style>
@endpush

@section('content')
    @php $callsCount = is_countable($calls) ? count($calls) : 0; @endphp

    <div class="page-header">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div>
                <h4 class="mb-2"><i class="fas fa-headset me-3"></i>Pending Call List</h4>
                <p class="mb-0 opacity-75">Manage pending calls ({{ $callsCount }} total)</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('call.csv_upload') }}" class="btn btn-light"><i class="fas fa-upload me-2"></i>CSV Upload</a>
                <button onclick="window.location.reload()" class="btn btn-light"><i class="fas fa-sync-alt me-2"></i>Refresh</button>
            </div>
        </div>
        <div class="d-flex flex-wrap gap-2 mt-3">
            <a href="{{ route('call.index') }}"               class="nav-btn active"><i class="fas fa-clock"></i> Pending <span class="badge bg-warning text-dark ms-1">{{ $callsCount }}</span></a>
            <a href="{{ route('call.conform') }}"             class="nav-btn"><i class="fas fa-check-circle"></i> Confirmed</a>
            <a href="{{ route('call.reject') }}"              class="nav-btn"><i class="fas fa-times-circle"></i> Rejected</a>
            <a href="{{ route('call.not_answer_call_list') }}" class="nav-btn"><i class="fas fa-phone-slash"></i> Not Answered</a>
            <a href="{{ route('call.other_call_list') }}"     class="nav-btn"><i class="fas fa-ellipsis-h"></i> Other</a>
            <a href="{{ route('employee.delivery_calls.pending') }}" class="nav-btn" style="background:rgba(16,185,129,.3)">
                <i class="fas fa-truck"></i> Delivery Queue
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-3">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-3">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    {{-- Bulk action bar --}}
    <div class="bulk-bar">
        <div class="d-flex align-items-center gap-3 flex-wrap">
            <label class="d-flex align-items-center gap-2 mb-0 fw-semibold" style="color:#4f46e5;cursor:pointer">
                <input type="checkbox" id="selectAll" style="width:16px;height:16px;accent-color:#4f46e5">
                Select All
            </label>
            <span id="selectedCount" class="badge bg-primary">0 selected</span>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <button type="button" onclick="bulkAction('confirm')" class="btn btn-sm btn-success fw-bold">
                <i class="fas fa-check-circle me-1"></i>Bulk Confirm → Delivery
            </button>
            <button type="button" onclick="bulkAction('reject')" class="btn btn-sm btn-danger fw-bold">
                <i class="fas fa-times-circle me-1"></i>Bulk Reject
            </button>
        </div>
    </div>

    {{-- Filter --}}
    <div class="filter-section">
        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold">Search</label>
                <input type="text" id="searchInput" class="form-control" placeholder="Name, phone, product…">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">From Date</label>
                <input type="date" id="dateFrom" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">To Date</label>
                <input type="date" id="dateTo" class="form-control">
            </div>
            <div class="col-md-2">
                <button id="clearFilter" class="btn btn-secondary w-100"><i class="fas fa-times me-1"></i>Clear</button>
            </div>
        </div>
    </div>

    <div class="card main-card">
        <div class="card-header" style="background:linear-gradient(135deg,#f9fafb,#f3f4f6);padding:1.5rem 2rem;">
            <h5 class="mb-0 fw-bold">
                <i class="fas fa-clock me-2 text-warning"></i>Pending Calls
                <span class="badge bg-warning ms-2">{{ $callsCount }}</span>
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="callsTable" class="table table-hover mb-0">
                    <thead>
                    <tr>
                        <th width="50"><input type="checkbox" id="selectAllHeader"></th>
                        <th>Call #</th>
                        <th>Code</th>
                        <th>Product</th>
                        <th>Date</th>
                        <th>Customer</th>
                        <th>Phone</th>
                        <th>City</th>
                        <th>Status</th>
                        <th width="200">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($calls as $call)
                        <tr id="row-{{ $call->id }}">
                            <td><input type="checkbox" class="call-checkbox" value="{{ $call->id }}"></td>
                            <td><strong>#{{ $call->call_number ?? $call->order_code }}</strong></td>
                            <td><code style="background:rgba(79,70,229,.08);padding:3px 7px;border-radius:5px;font-size:12px">{{ $call->item_code }}</code></td>
                            <td><strong>{{ $call->item_name }}</strong></td>
                            <td><span class="badge bg-light text-dark">{{ $call->date ? \Carbon\Carbon::parse($call->date)->format('M d, Y') : '-' }}</span></td>
                            <td><strong>{{ $call->customer_name }}</strong></td>
                            <td><a href="tel:{{ $call->customer_phone_01 }}" class="text-decoration-none">{{ $call->customer_phone_01 }}</a></td>
                            <td>
                                @if($call->city)
                                    <span class="city-badge">{{ $call->city }}</span>
                                @else
                                    <span class="city-warn">No city</span>
                                @endif
                            </td>
                            <td>
                                <span class="status-badge status-{{ strtolower($call->status) }}">
                                    <i class="fas fa-clock"></i> {{ $call->status }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-1 flex-wrap">
                                    <a href="{{ route('call.view', $call->id) }}" class="action-btn btn-view" title="View"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('call.edit', $call->id) }}" class="action-btn" style="background:#f59e0b;color:#fff" title="Edit"><i class="fas fa-edit"></i></a>

                                    {{-- CONFIRM → sends to employee delivery --}}
                                    <button type="button"
                                            class="action-btn btn-confirm confirm-btn"
                                            data-id="{{ $call->id }}"
                                            data-name="{{ $call->customer_name }}"
                                            data-city="{{ $call->city ?? 'Unknown city' }}"
                                            title="Confirm & Send to Delivery">
                                        <i class="fas fa-check-double"></i> Confirm
                                    </button>

                                    @if($call->status === 'Pending')
                                        <form action="{{ route('call.destroy', $call->id) }}" method="POST" class="delete-form" onsubmit="return confirmDelete(event, this)">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="action-btn btn-delete" title="Delete"><i class="fas fa-trash"></i></button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-5">
                                <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                                <h5 class="text-muted">No Pending Calls</h5>
                                <a href="{{ route('call.csv_upload') }}" class="btn btn-primary mt-2"><i class="fas fa-upload me-2"></i>Upload CSV</a>
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
        {{-- ── FIX #11: pass the real route and replace the literal placeholder in JS,
             not in PHP. route('call.confirm', ['id' => '__ID__']) would URL-encode
             the double-underscores in some Laravel versions, producing a broken URL.
             Instead, generate the base URL without the id and append it in JS. ── --}}
        const CONFIRM_BASE_URL = '{{ url("/seller/calls") }}';
        const BULK_CONFIRM_URL = '{{ route("call.bulk_confirm") }}';
        const CSRF_TOKEN       = '{{ csrf_token() }}';

        $(document).ready(function () {
            const table = $('#callsTable').DataTable({
                order: [[1, 'desc']],
                pageLength: 25,
                lengthMenu: [[10,25,50,100,-1],[10,25,50,100,'All']],
                columnDefs: [{ orderable: false, targets: [0, 9] }],
                language: { search: 'Search:', zeroRecords: 'No matching records found' }
            });

            $('#searchInput').on('keyup', function () { table.search(this.value).draw(); });

            // Date filter on col 4
            $.fn.dataTable.ext.search.push(function (settings, data) {
                if (settings.nTable.id !== 'callsTable') return true;
                const min = $('#dateFrom').val(), max = $('#dateTo').val();
                const dateText = data[4]?.replace(/<[^>]+>/g,'').trim();
                if (!min && !max) return true;
                try {
                    const d = new Date(dateText).toISOString().split('T')[0];
                    if (min && max) return d >= min && d <= max;
                    if (min) return d >= min;
                    return d <= max;
                } catch { return true; }
            });

            $('#dateFrom,#dateTo').on('change', () => table.draw());
            $('#clearFilter').on('click', () => {
                $('#searchInput').val(''); $('#dateFrom').val(''); $('#dateTo').val('');
                table.search('').draw();
            });

            // Checkboxes
            function syncSelectAll() {
                const all = $('.call-checkbox');
                const chk = all.filter(':checked');
                $('#selectAll, #selectAllHeader').prop('indeterminate', chk.length > 0 && chk.length < all.length);
                $('#selectAll, #selectAllHeader').prop('checked', chk.length > 0 && chk.length === all.length);
                $('#selectedCount').text(chk.length + ' selected');
            }

            $('#selectAll, #selectAllHeader').on('change', function () {
                $('.call-checkbox').prop('checked', $(this).prop('checked'));
                syncSelectAll();
            });
            $(document).on('change', '.call-checkbox', syncSelectAll);
        });

        // ── Single Confirm ────────────────────────────────────────────────
        $(document).on('click', '.confirm-btn', function () {
            const id   = $(this).data('id');
            const name = $(this).data('name');
            const city = $(this).data('city');

            // ── FIX #11 cont.: build confirm URL safely in JS ──
            const confirmUrl = CONFIRM_BASE_URL + '/' + id + '/confirm';

            Swal.fire({
                title: 'Confirm Call?',
                html: `<p>Customer: <strong>${name}</strong></p><p>City: <strong>${city}</strong></p><p style="color:#64748b;font-size:13px">This will create a delivery call and send it to the employee delivery queue.</p>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#64748b',
                confirmButtonText: '<i class="fas fa-check-double me-1"></i>Confirm & Send',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return fetch(confirmUrl, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': CSRF_TOKEN,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    })
                        .then(r => r.json())
                        .catch(err => Swal.showValidationMessage('Error: ' + err.message));
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then(result => {
                if (result.isConfirmed && result.value?.success) {
                    $(`#row-${id}`).fadeOut(400, function () { $(this).remove(); });
                    Swal.fire({
                        icon: 'success',
                        title: 'Confirmed!',
                        html: `<p>${result.value.message}</p><a href="{{ route('employee.delivery_calls.pending') }}" class="btn btn-primary btn-sm mt-2"><i class="fas fa-truck me-1"></i>View Delivery Queue</a>`,
                        showConfirmButton: true,
                        confirmButtonText: 'OK'
                    });
                } else if (result.value && !result.value.success) {
                    Swal.fire({ icon: 'error', title: 'Failed', text: result.value.message });
                }
            });
        });

        // ── Bulk Action ───────────────────────────────────────────────────
        function bulkAction(type) {
            const ids = [];
            $('.call-checkbox:checked').each(function () { ids.push($(this).val()); });
            if (!ids.length) {
                Swal.fire({ icon: 'warning', title: 'No Selection', text: 'Please select at least one call.' });
                return;
            }

            if (type === 'confirm') {
                Swal.fire({
                    title: `Confirm ${ids.length} call(s)?`,
                    text: 'All selected calls will be sent to the employee delivery queue.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#10b981',
                    confirmButtonText: 'Yes, Confirm All',
                    showLoaderOnConfirm: true,
                    preConfirm: () => {
                        return fetch(BULK_CONFIRM_URL, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': CSRF_TOKEN,
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({ call_ids: ids })
                        })
                            .then(r => r.json())
                            .catch(err => Swal.showValidationMessage('Error: ' + err.message));
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                }).then(result => {
                    if (result.isConfirmed && result.value?.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Done!',
                            text: result.value.message,
                            showConfirmButton: true
                        }).then(() => window.location.reload());
                    }
                });

            } else if (type === 'reject') {
                Swal.fire({
                    title: `Reject ${ids.length} call(s)?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    confirmButtonText: 'Yes, Reject'
                }).then(r => {
                    if (r.isConfirmed) {
                        $.ajax({
                            url: '{{ route("call.bulk_status") }}',
                            type: 'POST',
                            data: { _token: CSRF_TOKEN, call_ids: ids, status: 'Reject' },
                            success: res => {
                                if (res.success) {
                                    Swal.fire({ icon:'success', title:'Done!', text:res.message, timer:2000 })
                                        .then(() => location.reload());
                                } else {
                                    Swal.fire({ icon:'error', title:'Error!', text:res.message });
                                }
                            }
                        });
                    }
                });
            }
        }

        function confirmDelete(event, form) {
            event.preventDefault();
            Swal.fire({
                title: 'Delete?',
                text: 'This call will be permanently deleted.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Delete'
            }).then(r => { if (r.isConfirmed) form.submit(); });
            return false;
        }

        @if(session('success'))
        Swal.fire({ icon:'success', title:'Success!', text:@json(session('success')), timer:3000, showConfirmButton:false });
        @endif
        @if(session('error'))
        Swal.fire({ icon:'error', title:'Error!', text:@json(session('error')) });
        @endif
    </script>
@endpush
