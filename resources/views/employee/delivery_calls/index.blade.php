@extends('employee.layout.app')

@section('content')
    <div class="container-fluid">

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h4 class="mb-1">Employee Delivery Calls - {{ $status }}</h4>
                <div class="text-muted small">
                    Showing only <b>{{ $status }}</b> status records
                    @if($nextStatus)
                        → Next: <b>{{ $nextStatus }}</b>
                    @else
                        (Final stage)
                    @endif
                </div>
            </div>

            <div class="d-flex gap-2 flex-wrap">
                <a class="btn btn-outline-secondary btn-sm" href="{{ route('employee.delivery_calls.pending') }}">Pending</a>
                <a class="btn btn-outline-secondary btn-sm" href="{{ route('employee.delivery_calls.packing') }}">Packing</a>
                <a class="btn btn-outline-secondary btn-sm" href="{{ route('employee.delivery_calls.packed') }}">Packed</a>
                <a class="btn btn-outline-secondary btn-sm" href="{{ route('employee.delivery_calls.givedriver') }}">Give Driver</a>
            </div>
        </div>

        @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
        @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card">
            <div class="card-body">

                {{-- Search --}}
                <form class="row g-2 mb-3" method="GET" action="{{ url()->current() }}">
                    <div class="col-md-4">
                        <input type="text" name="q" class="form-control form-control-sm"
                               value="{{ request('q') }}"
                               placeholder="Search: order code, customer, phone, item...">
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-sm btn-primary" type="submit">Search</button>
                        <a class="btn btn-sm btn-light" href="{{ url()->current() }}">Reset</a>
                    </div>
                </form>

                {{-- Bulk controls (JS bulk) --}}
                <div class="d-flex align-items-center justify-content-between mb-2 flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-3 flex-wrap">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="selectAll">
                            <label class="form-check-label" for="selectAll">Select All</label>
                        </div>

                        @if($nextStatus)
                            <button type="button" id="bulkUpdateBtn" class="btn btn-sm btn-success">
                                Update Selected → {{ $nextStatus }}
                            </button>
                            <span class="text-muted small" id="bulkProgress" style="display:none;"></span>
                        @else
                            <button type="button" class="btn btn-sm btn-secondary" disabled>
                                Final status (no next)
                            </button>
                        @endif
                    </div>

                    <div class="text-muted small">Total: {{ $calls->total() }}</div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                        <tr>
                            <th style="width:55px;">Sel</th>
                            <th>ID</th>
                            <th>Call No</th>
                            <th>Order Code</th>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>Phone</th>
                            <th>City/District</th>
                            <th>Item</th>
                            <th>Qty</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th style="width:240px;">Action</th>
                        </tr>
                        </thead>

                        <tbody>
                        @forelse($calls as $c)
                            <tr id="row_tr_{{ $c->id }}">
                                <td>
                                    <div class="form-check m-0">
                                        <input class="form-check-input rowCheck"
                                               type="checkbox"
                                               value="{{ $c->id }}"
                                               id="row_{{ $c->id }}">
                                        <label class="form-check-label" for="row_{{ $c->id }}"></label>
                                    </div>
                                </td>

                                <td>{{ $c->id }}</td>
                                <td>{{ $c->call_number }}</td>
                                <td>{{ $c->order_code ?? '-' }}</td>
                                <td>{{ optional($c->date)->format('Y-m-d') }}</td>

                                <td>
                                    <div class="fw-semibold">{{ $c->customer_name }}</div>
                                    <div class="text-muted small">{{ $c->customer_address }}</div>
                                </td>

                                <td>
                                    <div>{{ $c->customer_phone_01 }}</div>
                                    <div class="text-muted small">{{ $c->customer_phone_02 }}</div>
                                </td>

                                <td>{{ $c->city }} / {{ $c->district }}</td>
                                <td>{{ $c->item_name }}</td>
                                <td>{{ $c->qty }}</td>
                                <td>{{ $c->formatted_total }}</td>

                                <td>
                                <span class="badge {{ $c->status_badge_class }}" id="badge_{{ $c->id }}">
                                    {{ $c->status }}
                                </span>
                                </td>

                                <td>
                                    <div class="d-flex gap-2">
                                        <a class="btn btn-sm btn-outline-primary w-50"
                                           href="{{ route('employee.delivery_calls.view', $c->id) }}">
                                            View
                                        </a>

                                        @if($nextStatus)
                                            {{-- single update form (keep as-is) --}}
                                            <form class="w-50" method="POST"
                                                  action="{{ route('employee.delivery_calls.status.update', $c->id) }}"
                                                  onsubmit="return confirm('Update status to {{ $nextStatus }}?');">
                                                @csrf
                                                @method('PUT')
                                                <button class="btn btn-sm btn-primary w-100" type="submit">
                                                    Update → {{ $nextStatus }}
                                                </button>
                                            </form>
                                        @else
                                            <button class="btn btn-sm btn-secondary w-50" disabled>Final</button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="13" class="text-center text-muted py-4">
                                    No records in {{ $status }} status.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $calls->links() }}
                </div>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const selectAll = document.getElementById('selectAll');
            const bulkBtn = document.getElementById('bulkUpdateBtn');
            const progressEl = document.getElementById('bulkProgress');
            const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const nextStatus = @json($nextStatus);

            const rowChecks = () => Array.from(document.querySelectorAll('.rowCheck'));

            if (selectAll) {
                selectAll.addEventListener('change', function () {
                    rowChecks().forEach(ch => ch.checked = selectAll.checked);
                });
            }

            rowChecks().forEach(ch => {
                ch.addEventListener('change', () => {
                    const all = rowChecks();
                    const checkedCount = all.filter(x => x.checked).length;
                    if (selectAll) selectAll.checked = (checkedCount === all.length && all.length > 0);
                });
            });

            async function updateOne(id) {
                // ✅ IMPORTANT: use your single route
                const url = `/employee/delivery-calls/${id}/status`;

                const res = await fetch(url, {
                    method: 'POST', // Laravel method spoofing
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                        'X-CSRF-TOKEN': csrf,
                        'Accept': 'application/json'
                    },
                    body: new URLSearchParams({
                        _method: 'PUT'
                        // if your controller needs a "status" param, uncomment below:
                        // status: nextStatus
                    })
                });

                // If controller returns JSON
                if (res.headers.get('content-type')?.includes('application/json')) {
                    const data = await res.json();
                    if (!res.ok) throw new Error(data?.message || 'Update failed');
                    return data;
                }

                // If controller redirects (HTML response), treat non-OK as error
                if (!res.ok) throw new Error('Update failed with status ' + res.status);
                return true;
            }

            async function bulkUpdateSequential(ids) {
                let ok = 0, fail = 0;

                progressEl.style.display = 'inline';
                progressEl.textContent = `Updating 0/${ids.length}...`;

                for (let i = 0; i < ids.length; i++) {
                    const id = ids[i];
                    try {
                        await updateOne(id);
                        ok++;

                        // optional UI update: mark row green + update badge text
                        const tr = document.getElementById('row_tr_' + id);
                        if (tr) tr.classList.add('table-success');

                        const badge = document.getElementById('badge_' + id);
                        if (badge && nextStatus) badge.textContent = nextStatus;

                    } catch (e) {
                        fail++;
                        const tr = document.getElementById('row_tr_' + id);
                        if (tr) tr.classList.add('table-danger');
                    }

                    progressEl.textContent = `Updating ${i+1}/${ids.length}... (OK: ${ok}, Fail: ${fail})`;
                }

                alert(`Done!\nOK: ${ok}\nFail: ${fail}`);
            }

            if (bulkBtn) {
                bulkBtn.addEventListener('click', async function () {
                    const selected = rowChecks().filter(ch => ch.checked).map(ch => ch.value);
                    if (!selected.length) return alert('Please select at least one row.');

                    if (!confirm('Update selected ' + selected.length + ' row(s) one-by-one?')) return;

                    bulkBtn.disabled = true;
                    try {
                        await bulkUpdateSequential(selected);
                        // Optional: reload to refresh pagination + correct status set
                        window.location.reload();
                    } finally {
                        bulkBtn.disabled = false;
                    }
                });
            }
        });
    </script>
@endsection
