@extends('seller.layout.app')

@section('content')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap');
        :root {
            --bg:#f4f6f9;--surface:#fff;--surface-2:#f8fafc;--surface-3:#f1f4f8;
            --border:#e2e8f0;--border-s:#edf0f5;
            --text:#0f172a;--text-sub:#475569;--text-muted:#94a3b8;
            --emerald:#059669;--emerald-lt:#ecfdf5;--emerald-bd:rgba(5,150,105,.25);
            --indigo:#4f46e5;--indigo-lt:#eef2ff;--indigo-bd:rgba(79,70,229,.25);
            --amber:#d97706;--amber-lt:#fffbeb;--amber-bd:rgba(217,119,6,.25);
            --rose:#e11d48;--rose-lt:#fff1f2;--rose-bd:rgba(225,29,72,.25);
            --sky:#0284c7;--sky-lt:#f0f9ff;--sky-bd:rgba(2,132,199,.25);
            --sh-sm:0 1px 3px rgba(15,23,42,.06),0 1px 2px rgba(15,23,42,.04);
            --sh:0 4px 12px rgba(15,23,42,.08),0 1px 3px rgba(15,23,42,.05);
            --r:10px;--r-lg:14px;--r-xl:18px;
        }
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
        body{font-family:'Plus Jakarta Sans',sans-serif}
        .pg{background:var(--bg);min-height:100vh;padding:24px 20px 56px}
        .wrap{max-width:1000px;margin:0 auto;animation:up .3s cubic-bezier(.16,1,.3,1) both}
        @keyframes up{from{opacity:0;transform:translateY(8px)}to{opacity:1;transform:translateY(0)}}

        .topbar{display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:24px}
        .crumb{font-size:12px;color:var(--text-muted);display:flex;align-items:center;gap:4px;margin-bottom:4px}
        .crumb a{color:var(--text-muted);text-decoration:none}.crumb a:hover{color:var(--indigo)}
        .title{font-size:22px;font-weight:800;color:var(--text)}

        .btn{font-family:'Plus Jakarta Sans',sans-serif;font-size:13px;font-weight:600;padding:9px 18px;border-radius:var(--r);border:1px solid var(--border);background:var(--surface);color:var(--text-sub);cursor:pointer;transition:.15s;display:inline-flex;align-items:center;gap:7px;text-decoration:none}
        .btn:hover{background:var(--surface-3);color:var(--text)}
        .btn-em{background:var(--emerald);color:#fff;border-color:transparent;box-shadow:0 2px 8px rgba(5,150,105,.25)}.btn-em:hover{background:#047857;color:#fff}
        .btn-ind{background:var(--indigo-lt);color:var(--indigo);border-color:var(--indigo-bd)}.btn-ind:hover{background:var(--indigo);color:#fff}
        .btn-rose{background:var(--rose-lt);color:var(--rose);border-color:var(--rose-bd)}.btn-rose:hover{background:var(--rose);color:#fff}
        .btn-sm{padding:6px 12px;font-size:12px}

        .card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r-xl);box-shadow:var(--sh);overflow:hidden;margin-bottom:20px}
        .card-head{padding:16px 22px;border-bottom:1px solid var(--border-s);background:var(--surface-2);display:flex;align-items:center;justify-content:space-between;gap:10px}
        .card-title{font-size:14px;font-weight:700;color:var(--text)}
        .card-body{padding:22px}

        /* Business grid */
        .biz-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:16px}
        .biz-card{border:1px solid var(--border);border-radius:var(--r-lg);padding:18px 20px;background:var(--surface-2);transition:.15s;position:relative}
        .biz-card:hover{border-color:var(--emerald-bd);background:var(--emerald-lt)}
        .biz-card-head{display:flex;align-items:flex-start;justify-content:space-between;gap:8px;margin-bottom:12px}
        .biz-card-name{font-size:15px;font-weight:800;color:var(--text)}
        .biz-badge{font-size:10px;font-weight:700;padding:2px 8px;border-radius:20px;background:var(--emerald-lt);color:var(--emerald);border:1px solid var(--emerald-bd)}
        .biz-field{margin-bottom:8px}
        .biz-lbl{font-size:10px;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.6px;margin-bottom:2px}
        .biz-val{font-size:12.5px;color:var(--text);font-weight:500}
        .biz-val.mono{font-family:'JetBrains Mono',monospace;font-size:12px}

        /* Form */
        .form-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px}
        @media(max-width:600px){.form-grid{grid-template-columns:1fr}}
        .form-group{margin-bottom:0}
        .form-label{font-size:11px;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.6px;margin-bottom:5px;display:block}
        .form-control{width:100%;font-family:'Plus Jakarta Sans',sans-serif;font-size:13px;color:var(--text);border:1px solid var(--border);border-radius:var(--r);padding:9px 13px;background:var(--surface);outline:none;transition:.15s}
        .form-control:focus{border-color:var(--emerald);box-shadow:0 0 0 3px var(--emerald-bd)}
        .form-section{margin:16px 0 10px;font-size:11px;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.7px;display:flex;align-items:center;gap:8px}
        .form-section::after{content:'';flex:1;height:1px;background:var(--border-s)}

        .empty-state{text-align:center;padding:40px 20px}
        .empty-ico{font-size:44px;margin-bottom:12px}
        .empty-txt{font-size:15px;font-weight:700;color:var(--text);margin-bottom:6px}
        .empty-sub{font-size:13px;color:var(--text-muted)}
    </style>

    <div class="pg">
        <div class="wrap">

            <div class="topbar">
                <div>
                    <div class="crumb">
                        <a href="{{ route('seller.dashboard') }}">Dashboard</a> /
                        <a href="{{ route('seller.delivery_calls.pending') }}">Delivery Calls</a> /
                        <span>My Businesses</span>
                    </div>
                    <div class="title">🏢 My Curfox Businesses</div>
                </div>
                <div style="display:flex;gap:8px">
                    <a href="{{ route('seller.delivery_calls.pending') }}" class="btn">← Back to Calls</a>
                </div>
            </div>

            @if(session('success'))
                <div style="padding:12px 16px;background:var(--emerald-lt);border:1px solid var(--emerald-bd);border-radius:var(--r-lg);color:var(--emerald);font-size:13px;font-weight:600;margin-bottom:16px">
                    ✅ {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div style="padding:12px 16px;background:var(--rose-lt);border:1px solid var(--rose-bd);border-radius:var(--r-lg);color:var(--rose);font-size:13px;font-weight:600;margin-bottom:16px">
                    ⚠️ {{ session('error') }}
                </div>
            @endif

            {{-- ── EXISTING BUSINESSES ── --}}
            <div class="card">
                <div class="card-head">
                    <span class="card-title">📋 My Registered Businesses ({{ $myBusinesses->count() }})</span>
                </div>
                <div class="card-body">
                    @if($myBusinesses->isEmpty())
                        <div class="empty-state">
                            <div class="empty-ico">🏗️</div>
                            <div class="empty-txt">No businesses yet</div>
                            <div class="empty-sub">Create your first Curfox business below to start dispatching orders.</div>
                        </div>
                    @else
                        <div class="biz-grid">
                            @foreach($myBusinesses as $biz)
                                <div class="biz-card">
                                    <div class="biz-card-head">
                                        <div class="biz-card-name">{{ $biz->business_name }}</div>
                                        @if($biz->is_default)
                                            <span class="biz-badge">Default</span>
                                        @endif
                                    </div>

                                    <div class="biz-field">
                                        <div class="biz-lbl">Ref No</div>
                                        <div class="biz-val mono">{{ $biz->ref_no ?? '—' }}</div>
                                    </div>
                                    <div class="biz-field">
                                        <div class="biz-lbl">Curfox Business ID (dispatch ID)</div>
                                        <div class="biz-val mono" style="color:var(--emerald)">{{ $biz->curfox_id ?? '—' }}</div>
                                    </div>
                                    <div class="biz-field">
                                        <div class="biz-lbl">Merchant ID</div>
                                        <div class="biz-val mono">{{ $biz->merchant_id ?? '—' }}</div>
                                    </div>
                                    @if($biz->address_line_1)
                                        <div class="biz-field">
                                            <div class="biz-lbl">Address</div>
                                            <div class="biz-val">{{ $biz->address_line_1 }}{{ $biz->address_line_2 ? ', '.$biz->address_line_2 : '' }}</div>
                                        </div>
                                    @endif
                                    @if($biz->city_name)
                                        <div class="biz-field">
                                            <div class="biz-lbl">City</div>
                                            <div class="biz-val">{{ $biz->city_name }} (ID: {{ $biz->city_id }})</div>
                                        </div>
                                    @endif
                                    @if($biz->bank_name)
                                        <div class="biz-field">
                                            <div class="biz-lbl">Bank</div>
                                            <div class="biz-val">{{ $biz->bank_name }} — {{ $biz->bank_account_number }}</div>
                                        </div>
                                    @endif

                                    <div style="display:flex;gap:6px;margin-top:14px;flex-wrap:wrap">
                                        <form method="POST" action="{{ route('seller.delivery_calls.business_set_active') }}">
                                            @csrf
                                            <input type="hidden" name="business_id" value="{{ $biz->curfox_id }}">
                                            <button type="submit" class="btn btn-em btn-sm">Set Active</button>
                                        </form>
                                        <form method="POST" action="{{ route('seller.delivery_calls.business_delete', $biz->id) }}"
                                              onsubmit="return confirm('Remove this business record?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-rose btn-sm">Remove</button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- ── CREATE NEW BUSINESS ── --}}
            <div class="card">
                <div class="card-head">
                    <span class="card-title">➕ Create New Curfox Business</span>
                </div>
                <div class="card-body">
                    <form id="createBizForm" onsubmit="createBusiness(event)">
                        @csrf

                        <div class="form-section">Business Details</div>
                        <div class="form-grid">
                            <div class="form-group" style="grid-column:1/-1">
                                <label class="form-label">Business Name *</label>
                                <input name="business_name" class="form-control" required placeholder="e.g. Ravindu Enterprises">
                            </div>
                        </div>

                        <div class="form-section">Address</div>
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">Address Line 1 *</label>
                                <input name="address_line_1" class="form-control" required placeholder="Street address">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Address Line 2</label>
                                <input name="address_line_2" class="form-control" placeholder="Apartment, floor, etc.">
                            </div>
                            <div class="form-group">
                                <label class="form-label">City (Curfox City) *</label>
                                <input id="citySearchInput" class="form-control" placeholder="Search city…" autocomplete="off"
                                       oninput="filterCreateCities()">
                                <input type="hidden" name="city_id" id="selectedCreateCityId">
                                <div id="createCityDropdown" style="display:none;border:1px solid var(--border);border-radius:var(--r-lg);max-height:220px;overflow-y:auto;margin-top:4px;background:var(--surface);box-shadow:var(--sh)"></div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Phone *</label>
                                <input name="phone" class="form-control" required placeholder="0712345678">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Postal Code</label>
                                <input name="postal_code" class="form-control" placeholder="00100">
                            </div>
                        </div>

                        <div class="form-section">Bank Details</div>
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">Account Name *</label>
                                <input name="bank_account_name" class="form-control" required placeholder="Account holder name">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Account Number *</label>
                                <input name="bank_account_number" class="form-control" required placeholder="1234567890">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Bank Name *</label>
                                <input name="bank_name" class="form-control" required placeholder="Sampath Bank">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Branch Name</label>
                                <input name="bank_branch" class="form-control" placeholder="Colombo">
                            </div>
                        </div>

                        <div style="margin-top:20px;display:flex;gap:10px;align-items:center">
                            <button type="submit" class="btn btn-em" id="createBizBtn">
                                🏢 Create Business in Curfox
                            </button>
                            <span id="createBizStatus" style="font-size:13px;color:var(--text-muted)"></span>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <script>
        const CSRF = '{{ csrf_token() }}';
        const ALL_CITIES = @json($curfoxCities);
        const CREATE_URL = '{{ route("seller.delivery_calls.business_create") }}';

        // City search for create form
        function filterCreateCities() {
            const q = document.getElementById('citySearchInput').value.toLowerCase();
            const dropdown = document.getElementById('createCityDropdown');

            if (!q) { dropdown.style.display = 'none'; return; }

            const matches = ALL_CITIES.filter(c =>
                c.name.toLowerCase().includes(q) || c.state_name.toLowerCase().includes(q)
            ).slice(0, 50);

            if (!matches.length) {
                dropdown.innerHTML = '<div style="padding:12px;font-size:13px;color:var(--text-muted)">No cities found</div>';
                dropdown.style.display = 'block';
                return;
            }

            dropdown.innerHTML = matches.map(c => `
        <div onclick="pickCreateCity(${c.id},'${c.name.replace(/'/g,"\\'")}','${c.state_name.replace(/'/g,"\\'")}')"
             style="padding:10px 14px;cursor:pointer;font-size:13px;border-bottom:1px solid var(--border-s)"
             onmouseover="this.style.background='var(--surface-2)'" onmouseout="this.style.background=''">
            <span style="font-weight:600">${c.name}</span>
            <span style="font-size:11px;color:var(--text-muted);margin-left:6px">${c.state_name}</span>
        </div>
    `).join('');
            dropdown.style.display = 'block';
        }

        function pickCreateCity(id, name, state) {
            document.getElementById('citySearchInput').value = name + ' (' + state + ')';
            document.getElementById('selectedCreateCityId').value = id;
            document.getElementById('createCityDropdown').style.display = 'none';
        }

        document.addEventListener('click', e => {
            if (!e.target.closest('#citySearchInput') && !e.target.closest('#createCityDropdown')) {
                document.getElementById('createCityDropdown').style.display = 'none';
            }
        });

        // Create business via AJAX
        async function createBusiness(e) {
            e.preventDefault();

            const form    = document.getElementById('createBizForm');
            const btn     = document.getElementById('createBizBtn');
            const status  = document.getElementById('createBizStatus');
            const cityId  = document.getElementById('selectedCreateCityId').value;

            if (!cityId) { alert('Please select a city from the dropdown.'); return; }

            const formData = new FormData(form);
            const payload  = {};
            formData.forEach((v, k) => { payload[k] = v; });

            btn.disabled = true;
            btn.textContent = '⏳ Creating…';
            status.textContent = '';

            try {
                const res  = await fetch(CREATE_URL, {
                    method: 'POST',
                    headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': CSRF },
                    body: JSON.stringify(payload),
                });
                const data = await res.json();

                if (data.success) {
                    status.textContent = '✅ Created! Ref: ' + (data.data?.ref_no || '');
                    status.style.color = 'var(--emerald)';
                    form.reset();
                    document.getElementById('selectedCreateCityId').value = '';
                    document.getElementById('citySearchInput').value = '';
                    setTimeout(() => location.reload(), 1200);
                } else {
                    throw new Error(data.message || 'Creation failed');
                }
            } catch (err) {
                status.textContent = '⚠️ ' + err.message;
                status.style.color = 'var(--rose)';
            } finally {
                btn.disabled = false;
                btn.textContent = '🏢 Create Business in Curfox';
            }
        }
    </script>
@endsection
