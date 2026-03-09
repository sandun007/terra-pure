@extends('seller.layout.app')

@push('title')
    Pending Delivery Calls
@endpush

@push('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <style>
        /* Reuse styles from index */
        :root {
            --primary: #f59e0b;
            --primary-dark: #d97706;
            --pending: #f59e0b;
            --packing: #3b82f6;
            --packed: #8b5cf6;
            --givedriver: #10b981;
        }

        .page-header {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
            padding: 2rem;
            border-radius: 16px;
            margin-bottom: 2rem;
        }

        .status-tabs {
            display: flex;
            gap: 8px;
            background: white;
            padding: 8px;
            border-radius: 12px;
            margin-bottom: 24px;
        }

        .status-tab {
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 13px;
            color: #64748b;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .status-tab.active {
            background: var(--primary);
            color: white;
        }

        .bulk-actions {
            background: white;
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 20px;
            border: 2px solid var(--primary);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
        }

        .table-container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #f8fafc;
            padding: 16px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            color: #64748b;
            border-bottom: 2px solid #e2e8f0;
        }

        td {
            padding: 16px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: middle;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
            background: #fef3c7;
            color: #92400e;
        }

        .action-btn {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .action-btn.view { background: var(--primary); color: white; }
        .action-btn.next { background: #10b981; color: white; }
        .action-btn:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.15); }
    </style>
@endpush

@section('content')
    <div class="container-fluid px-4 py-4">
        <!-- Header -->
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mb-2">📞 Pending Delivery Calls</h1>
                    <p class="mb-0 opacity-75">Orders waiting to be processed</p>
                </div>
                <div class="bg-white bg-opacity-25 rounded-pill px-4 py-2">
                    <span class="fw-bold">{{ $calls->total() }}</span> calls
                </div>
            </div>
        </div>

        <!-- Status Tabs -->
        <div class="status-tabs">
            <a href="{{ route('seller.delivery_calls.pending') }}" class="status-tab active">
                <span class="material-icons-round">pending_actions</span> Pending
                <span class="badge bg-white text-dark ms-2">{{ $counts['pending'] }}</span>
            </a>
            <a href="{{ route('seller.delivery_calls.packing') }}" class="status-tab">
                <span class="material-icons-round">inventory</span> Packing
                <span class="badge bg-secondary ms-2">{{ $counts['packing'] }}</span>
            </a>
            <a href="{{ route('seller.delivery_calls.packed') }}" class="status-tab">
                <span class="material-icons-round">checklist</span> Packed
                <span class="badge bg-secondary ms-2">{{ $counts['packed'] }}</span>
            </a>
            <a href="{{ route('seller.delivery_calls.givedriver') }}" class="status-tab">
                <span class="material-icons-round">local_shipping</span> Give Driver
                <span class="badge bg-secondary ms-2">{{ $counts['givedriver'] }}</span>
            </a>
        </div>

        <!-- Bulk Actions -->
        <div class="bulk-actions">
            <div class="d-flex align-items-center gap-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="selectAll">
                    <label class="form-check-label fw-semibold">Select All</label>
                </div>
                <span id="selectedCount" class="badge bg-primary">0 selected</span>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-warning" id="bulkNextBtn" disabled onclick="bulkNext()">
                    <span class="material-icons-round me-1">arrow_forward</span>
                    Move to Packing
                </button>
            </div>
        </div>

        <!-- Table -->
        <div class="table-container">
            <div class="table-responsive">
                <table>
                    <thead>
                    <tr>
                        <th style="width: 40px;"></th>
                        <th>Call #</th>
                        <th>Order Code</th>
                        <th>Customer</th>
                        <th>Phone</th>
                        <th>City</th>
                        <th>Item</th>
                        <th>Qty</th>
                        <th>Total</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($calls as $call)
                        <tr>
                            <td>
                                <input type="checkbox" class="rowCheckbox" value="{{ $call->id }}">
                            </td>
                            <td><span class="fw-bold text-primary">{{ $call->call_number }}</span></td>
                            <td>{{ $call->order_code ?? '-' }}</td>
                            <td>{{ $call->customer_name }}</td>
                            <td>{{ $call->customer_phone_01 }}</td>
                            <td>{{ $call->city ?? $call->district }}</td>
                            <td>{{ Str::limit($call->item_name, 20) }}</td>
                            <td>{{ $call->qty }}</td>
                            <td>Rs. {{ number_format($call->total, 2) }}</td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('seller.delivery_calls.show', $call->id) }}"
                                       class="action-btn view" title="View">
                                        <span class="material-icons-round">visibility</span>
                                    </a>
                                    <form method="POST" action="{{ route('seller.delivery_calls.update_status', $call->id) }}"
                                          class="d-inline" onsubmit="return confirm('Move to Packing?')">
                                        @csrf
                                        <input type="hidden" name="status" value="packing">
                                        <button type="submit" class="action-btn next" title="Move to Packing">
                                            <span class="material-icons-round">arrow_forward</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $calls->links() }}
        </div>
    </div>
@endsection

@push('script')
    <script>
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.rowCheckbox');
        const selectedCount = document.getElementById('selectedCount');
        const bulkNextBtn = document.getElementById('bulkNextBtn');

        function updateSelection() {
            const checked = document.querySelectorAll('.rowCheckbox:checked').length;
            selectedCount.textContent = checked + ' selected';
            bulkNextBtn.disabled = checked === 0;

            if (selectAll) {
                selectAll.indeterminate = checked > 0 && checked < checkboxes.length;
                selectAll.checked = checked === checkboxes.length;
            }
        }

        selectAll?.addEventListener('change', function() {
            checkboxes.forEach(cb => cb.checked = this.checked);
            updateSelection();
        });

        checkboxes.forEach(cb => cb.addEventListener('change', updateSelection));

        async function bulkNext() {
            const selected = Array.from(document.querySelectorAll('.rowCheckbox:checked')).map(cb => cb.value);
            if (selected.length === 0) return;

            const result = await Swal.fire({
                title: 'Bulk Move to Packing?',
                text: `Move ${selected.length} calls to Packing stage`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, Move',
                cancelButtonText: 'Cancel'
            });

            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("seller.delivery_calls.bulk_status") }}';

                const csrf = document.createElement('input');
                csrf.type = 'hidden';
                csrf.name = '_token';
                csrf.value = '{{ csrf_token() }}';

                const ids = document.createElement('input');
                ids.type = 'hidden';
                ids.name = 'ids';
                ids.value = JSON.stringify(selected);

                const status = document.createElement('input');
                status.type = 'hidden';
                status.name = 'status';
                status.value = 'packing';

                form.appendChild(csrf);
                form.appendChild(ids);
                form.appendChild(status);
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
@endpush
