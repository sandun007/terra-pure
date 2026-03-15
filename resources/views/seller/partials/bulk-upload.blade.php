<form id="bulkOrderForm">
    @csrf
    <input type="hidden" name="date" id="bulk_date" value="{{ date('Y-m-d') }}">

    <div class="form-container">
        <!-- Step 1: Product Selection Mode -->
        <div class="product-search-section" style="border-color:#f59e0b">
            <div class="search-header">
                <span class="material-icons-round" style="color:var(--warning)">looks_one</span>
                Step 1: Select Product Mode
            </div>

            <div style="display:flex; gap:20px; margin-bottom:20px; padding:10px; background:#f8fafc; border-radius:8px;">
                <label style="display:flex; align-items:center; gap:8px; cursor:pointer;">
                    <input type="radio" name="product_mode" id="singleProductMode" value="single" checked>
                    <strong>Single Product</strong> (all rows use same product)
                </label>
                <label style="display:flex; align-items:center; gap:8px; cursor:pointer;">
                    <input type="radio" name="product_mode" id="multiProductMode" value="multi">
                    <strong>Multiple Products</strong> (CSV has product_id or product_code column)
                </label>
            </div>

            <!-- Single Product Selection -->
            <div id="singleProductSection">
                <div class="input-with-button mb-3">
                    <div style="flex:1">
                        <input type="text" id="bulkProductCode" class="form-control"
                               placeholder="Enter product code (e.g., DX/ITM/0001)">
                    </div>
                    <button type="button" id="bulkFetchProductBtn" class="btn btn-warning">
                        <span class="material-icons-round" style="font-size:18px">search</span>Verify Product
                    </button>
                    <button type="button" id="bulkResetProductBtn" class="btn btn-danger" style="display:none">
                        <span class="material-icons-round" style="font-size:18px">close</span>Clear
                    </button>
                </div>

                <!-- Product Info -->
                <div id="bulkProductCard" style="display:none; background:#fff; border:2px solid #10b981; border-radius:12px; padding:15px;">
                    <div style="display:flex; align-items:center; gap:15px;">
                        <img id="bulkProductImg" src="" alt="" style="width:60px; height:60px; border-radius:8px; display:none;">
                        <div id="bulkProductImgPh" style="width:60px; height:60px; background:#f1f5f9; border-radius:8px; display:flex; align-items:center; justify-content:center;">
                            <span class="material-icons-round">inventory_2</span>
                        </div>
                        <div style="flex:1">
                            <div style="font-size:16px; font-weight:800;" id="bulkProductName">-</div>
                            <div style="display:flex; gap:10px; margin-top:5px; flex-wrap:wrap;">
                                <span class="product-code-badge"><span id="bulkProductCodeDisplay">-</span></span>
                                <span style="color:var(--danger); font-weight:700;">Rs. <span id="bulkProductPrice">0.00</span></span>
                                <span id="bulkStockBadge" class="stock-badge stock-available">
                                    <span id="bulkProductStock">0</span> in stock
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Multiple Products Info (shown when multi-product mode selected) -->
            <div id="multiProductSection" style="display:none; padding:15px; background:#e6f7ff; border:2px solid #1890ff; border-radius:8px;">
                <div style="display:flex; align-items:center; gap:10px; margin-bottom:10px;">
                    <span class="material-icons-round" style="color:#1890ff;">info</span>
                    <strong>CSV must include one of these columns:</strong>
                </div>
                <ul style="margin-bottom:15px;">
                    <li><code>product_id</code> - Product ID from database</li>
                    <li><code>product_code</code> - Product code (e.g., DX/ITM/0001)</li>
                </ul>
                <div class="alert alert-warning" style="padding:10px; font-size:13px;">
                    <span class="material-icons-round" style="font-size:14px;">warning</span>
                    Each row can have a different product. Stock will be checked per product.
                </div>
            </div>

            <div id="bulkStockWarning" style="display:none; margin-top:10px; padding:10px; background:#fef3c7; border:1px solid #f59e0b; border-radius:8px; color:#92400e;">
                <span class="material-icons-round" style="font-size:15px; vertical-align:middle;">warning</span>
                <span id="bulkStockWarningText"></span>
            </div>
        </div>

        <!-- Step 2: CSV Upload -->
        <div class="product-search-section" style="border-color:#3b82f6; margin-top:20px;">
            <div class="search-header">
                <span class="material-icons-round" style="color:var(--info)">looks_two</span>
                Step 2: Upload CSV File
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">CSV File <span class="required">*</span></label>
                    <input type="file" id="csvFile" class="form-control" accept=".csv">
                    <small class="text-muted">
                        <strong>Required columns:</strong> customer_name, customer_phone, customer_address<br>
                        <strong>Optional columns:</strong> city, platform, remark, product_id, product_code, payment_method
                    </small>
                </div>
                <div class="form-group">
                    <label class="form-label">Default Platform</label>
                    <select id="bulkDefaultPlatform" class="form-control">
                        <option value="fb">Facebook</option>
                        <option value="ig">Instagram</option>
                        <option value="tiktok">TikTok</option>
                        <option value="website">Website</option>
                        <option value="manual">Manual</option>
                    </select>
                </div>
            </div>

            <!-- Sample CSV Template -->
            <div style="margin-top:15px; padding:10px; background:#f8fafc; border-radius:8px;">
                <details>
                    <summary style="cursor:pointer; font-weight:600; color:#3b82f6;">
                        <span class="material-icons-round" style="font-size:16px; vertical-align:middle;">description</span>
                        View Sample CSV Template
                    </summary>
                    <div style="margin-top:10px; padding:10px; background:#fff; border:1px solid var(--border); border-radius:8px; overflow-x:auto;">
                        <pre style="margin:0; font-size:12px;">
customer_name,customer_phone,customer_address,city,platform,remark,product_code
John Doe,0771234567,"123 Main Street, Colombo",Colombo 03,fb,Fragile item,DX/ITM/0001
Jane Smith,0789876543,"45 Beach Road, Negombo",Negombo,ig,Call before delivery,DX/ITM/0002
                        </pre>
                        <p class="text-muted mt-2" style="font-size:12px;">
                            <span class="material-icons-round" style="font-size:14px;">info</span>
                            For multiple products, include product_code or product_id column
                        </p>
                    </div>
                </details>
            </div>

            <div style="display:flex; gap:10px; margin-top:15px; align-items:center; flex-wrap:wrap;">
                <button type="button" id="parseCsvBtn" class="btn btn-info" disabled>
                    <span class="material-icons-round" style="font-size:18px">preview</span>Preview & Validate
                </button>
                <button type="button" id="bulkImportBtn" class="btn btn-success" disabled>
                    <span class="material-icons-round" style="font-size:18px">cloud_upload</span>Import Orders
                </button>
                <label style="display:flex; align-items:center; gap:5px; font-size:13px;">
                    <input type="checkbox" id="allowNonCurfoxCity"> Allow non-Curfox cities
                </label>
            </div>

            <!-- Validation Summary -->
            <div id="validationSummary" style="display:none; margin-top:15px; padding:15px; background:#f8fafc; border-radius:8px;">
                <div id="validationBadges" style="display:flex; gap:10px; margin-bottom:10px; flex-wrap:wrap;"></div>
                <div id="errorList" style="display:none; max-height:200px; overflow-y:auto; background:#fee2e2; padding:10px; border-radius:8px;"></div>
                <div id="addressErrorList" style="display:none; max-height:200px; overflow-y:auto; background:#fff3cd; padding:10px; border-radius:8px; margin-top:10px;"></div>
            </div>

            <!-- Preview Table -->
            <div id="csvPreview" style="display:none; margin-top:20px;">
                <h6 style="font-weight:700; margin-bottom:10px;">Preview (first 20 rows)</h6>
                <div style="overflow-x:auto; border:1px solid var(--border); border-radius:8px; max-height:400px; overflow-y:auto;">
                    <table class="csv-preview-table" style="width:100%; border-collapse:collapse; font-size:12px;">
                        <thead style="position:sticky; top:0; background:#f8fafc;">
                        <tr>
                            <th style="padding:10px; border-bottom:2px solid var(--border);">#</th>
                            <th style="padding:10px; border-bottom:2px solid var(--border);">Product</th>
                            <th style="padding:10px; border-bottom:2px solid var(--border);">Customer</th>
                            <th style="padding:10px; border-bottom:2px solid var(--border);">Phone</th>
                            <th style="padding:10px; border-bottom:2px solid var(--border);">Address</th>
                            <th style="padding:10px; border-bottom:2px solid var(--border);">City</th>
                            <th style="padding:10px; border-bottom:2px solid var(--border);">City Status</th>
                            <th style="padding:10px; border-bottom:2px solid var(--border);">Platform</th>
                        </tr>
                        </thead>
                        <tbody id="csvPreviewBody"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</form>

<style>
    .csv-preview-table td { padding:8px 10px; border-bottom:1px solid var(--border); }
    .city-ok { color:#10b981; font-weight:600; }
    .city-invalid { color:#ef4444; font-weight:600; }
    .city-missing { color:#f59e0b; font-weight:600; }
    .validation-badge { padding:4px 12px; border-radius:999px; font-size:12px; font-weight:600; }
    .badge-ok { background:#d1fae5; color:#065f46; }
    .badge-warn { background:#fef3c7; color:#92400e; }
    .badge-err { background:#fee2e2; color:#991b1b; }
    .product-cell { display:flex; flex-direction:column; }
    .product-code { font-size:11px; color:#64748b; font-family:monospace; }
</style>

<script>
    const BULK_STORE_URL = '{{ route("seller.order.bulk-store") }}';
    const BULK_VALIDATE_URL = '{{ route("seller.order.bulk-validate") }}';
    const PRODUCT_DETAILS_URL = '{{ route("seller.order.product.details", ["code" => "PLACEHOLDER"]) }}';
    const CSRF_TOKEN = '{{ csrf_token() }}';

    let bulkProduct = null;
    let leads = [];
    let productCache = new Map(); // Cache for multi-product mode

    // Toggle between single and multi product modes
    document.querySelectorAll('input[name="product_mode"]').forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'single') {
                document.getElementById('singleProductSection').style.display = 'block';
                document.getElementById('multiProductSection').style.display = 'none';
                resetBulkProduct();
            } else {
                document.getElementById('singleProductSection').style.display = 'none';
                document.getElementById('multiProductSection').style.display = 'block';
                bulkProduct = null; // No single product in multi mode
                document.getElementById('parseCsvBtn').disabled = false; // Enable preview without product
            }
            document.getElementById('parseCsvBtn').disabled = true;
            document.getElementById('bulkImportBtn').disabled = true;
            document.getElementById('csvPreview').style.display = 'none';
            document.getElementById('validationSummary').style.display = 'none';
        });
    });

    // Bulk product fetch (for single product mode)
    document.getElementById('bulkFetchProductBtn').addEventListener('click', async () => {
        const code = document.getElementById('bulkProductCode').value.trim();
        if (!code) return Swal.fire({ icon: 'error', title: 'Missing Code', text: 'Enter product code' });

        const btn = document.getElementById('bulkFetchProductBtn');
        const orig = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner"></span> Verifying...';

        try {
            const url = PRODUCT_DETAILS_URL.replace('PLACEHOLDER', encodeURIComponent(code));
            const res = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                }
            });
            const data = await res.json();
            if (!data.success) throw new Error(data.message);

            bulkProduct = data.product;
            showBulkProductCard(bulkProduct);
            document.getElementById('parseCsvBtn').disabled = false;
            Swal.fire({ icon: 'success', title: 'Product Verified!', text: `${bulkProduct.name} - Stock: ${bulkProduct.qty}`, timer: 2000 });
        } catch (err) {
            Swal.fire({ icon: 'error', title: 'Not Found', text: err.message });
        } finally {
            btn.disabled = false;
            btn.innerHTML = orig;
        }
    });

    function showBulkProductCard(p) {
        document.getElementById('bulkProductCard').style.display = 'block';
        document.getElementById('bulkResetProductBtn').style.display = 'inline-flex';
        document.getElementById('bulkProductName').textContent = p.name;
        document.getElementById('bulkProductCodeDisplay').textContent = p.code;
        document.getElementById('bulkProductPrice').textContent = p.display_price.toFixed(2);
        document.getElementById('bulkProductStock').textContent = p.qty;
        if (p.image) {
            document.getElementById('bulkProductImg').src = p.image;
            document.getElementById('bulkProductImg').style.display = 'block';
            document.getElementById('bulkProductImgPh').style.display = 'none';
        }
    }

    document.getElementById('bulkResetProductBtn').addEventListener('click', resetBulkProduct);

    function resetBulkProduct() {
        bulkProduct = null;
        document.getElementById('bulkProductCard').style.display = 'none';
        document.getElementById('bulkResetProductBtn').style.display = 'none';
        document.getElementById('bulkProductCode').value = '';
        document.getElementById('parseCsvBtn').disabled = true;
        document.getElementById('bulkImportBtn').disabled = true;
        document.getElementById('csvPreview').style.display = 'none';
        document.getElementById('validationSummary').style.display = 'none';
        leads = [];
    }

    // Advanced CSV Parser (handles quotes and commas in fields)
    function parseCSVLine(line) {
        const result = [];
        let current = '';
        let inQuotes = false;

        for (let i = 0; i < line.length; i++) {
            const char = line[i];
            const nextChar = line[i + 1];

            if (inQuotes) {
                if (char === '"' && nextChar === '"') {
                    current += '"';
                    i++;
                } else if (char === '"') {
                    inQuotes = false;
                } else {
                    current += char;
                }
            } else {
                if (char === '"') {
                    inQuotes = true;
                } else if (char === ',') {
                    result.push(current.trim());
                    current = '';
                } else {
                    current += char;
                }
            }
        }
        result.push(current.trim());
        return result;
    }

    // Parse CSV to leads with full column support
    function parseCSV(text) {
        const lines = text.replace(/\r\n/g, '\n').split('\n').filter(l => l.trim());
        if (lines.length < 2) return [];

        const header = parseCSVLine(lines[0]);
        const headerLower = header.map(h => h.toLowerCase().trim());

        // Find column indexes
        const nameIdx = headerLower.findIndex(h => h.includes('customer_name') || h.includes('name'));
        const phoneIdx = headerLower.findIndex(h => h.includes('customer_phone') || h.includes('phone'));
        const addressIdx = headerLower.findIndex(h => h.includes('customer_address') || h.includes('address'));
        const cityIdx = headerLower.findIndex(h => h === 'city' || h.includes('district'));
        const platformIdx = headerLower.findIndex(h => h.includes('platform'));
        const remarkIdx = headerLower.findIndex(h => h.includes('remark') || h.includes('notes'));
        const productCodeIdx = headerLower.findIndex(h => h.includes('product_code') || h.includes('code'));
        const productIdIdx = headerLower.findIndex(h => h.includes('product_id') || h.includes('id'));
        const paymentIdx = headerLower.findIndex(h => h.includes('payment_method') || h.includes('payment'));

        if (nameIdx === -1 || phoneIdx === -1) {
            Swal.fire({ icon: 'error', title: 'Invalid CSV', text: 'CSV must contain customer_name and customer_phone columns' });
            return [];
        }

        if (addressIdx === -1) {
            Swal.fire({ icon: 'warning', title: 'Missing Address Column', text: 'CSV does not have customer_address column. Address will be empty and may cause issues with delivery.' });
        }

        const leads = [];
        for (let i = 1; i < lines.length; i++) {
            const cols = parseCSVLine(lines[i]);
            const name = cols[nameIdx]?.trim() || '';
            const phone = cols[phoneIdx]?.trim() || '';

            if (!name || !phone) continue;

            leads.push({
                customer_name: name,
                customer_phone: phone,
                customer_address: addressIdx !== -1 ? (cols[addressIdx]?.trim() || '') : '',
                district: cityIdx !== -1 ? (cols[cityIdx]?.trim() || '') : '',
                destination_city_name: cityIdx !== -1 ? (cols[cityIdx]?.trim() || '') : '',
                platform: platformIdx !== -1 ? (cols[platformIdx]?.trim() || '') : '',
                remark: remarkIdx !== -1 ? (cols[remarkIdx]?.trim() || '') : '',
                product_code: productCodeIdx !== -1 ? (cols[productCodeIdx]?.trim() || '') : '',
                product_id: productIdIdx !== -1 ? (cols[productIdIdx]?.trim() || '') : '',
                payment_method: paymentIdx !== -1 ? (cols[paymentIdx]?.trim() || '') : '',
            });
        }
        return leads;
    }

    // CSV Parse and Validate
    document.getElementById('parseCsvBtn').addEventListener('click', async () => {
        const productMode = document.querySelector('input[name="product_mode"]:checked').value;

        if (productMode === 'single' && !bulkProduct) {
            return Swal.fire({ icon: 'error', title: 'No Product', text: 'Verify a product first' });
        }

        const file = document.getElementById('csvFile').files[0];
        if (!file) return Swal.fire({ icon: 'error', title: 'No File', text: 'Select a CSV file' });

        const text = await file.text();
        leads = parseCSV(text);

        if (!leads.length) {
            return Swal.fire({ icon: 'error', title: 'Empty CSV', text: 'No valid rows found' });
        }

        // Validate addresses
        const missingAddress = leads.filter(l => !l.customer_address).length;
        if (missingAddress > 0) {
            const proceed = await Swal.fire({
                icon: 'warning',
                title: 'Missing Addresses',
                html: `${missingAddress} rows have no address. Orders without addresses cannot be confirmed later.<br>Continue anyway?`,
                showCancelButton: true,
                confirmButtonText: 'Yes, Continue',
                cancelButtonText: 'No, Fix CSV'
            });
            if (!proceed.isConfirmed) return;
        }

        // Check stock warning for single product mode
        if (productMode === 'single') {
            checkStockWarning();
        }

        // Show badge
        const badge = document.getElementById('bulkPreviewProductBadge');
        if (badge) {
            badge.style.display = 'inline-flex';
            badge.textContent = productMode === 'single'
                ? `✓ Product: ${bulkProduct.name} (${bulkProduct.code}) — Stock: ${bulkProduct.qty}`
                : `✓ Multi-Product Mode — ${leads.length} rows`;
        }

        const btn = document.getElementById('parseCsvBtn');
        const orig = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner"></span> Validating...';

        try {
            const fd = new FormData();
            fd.append('leads', JSON.stringify(leads));
            fd.append('product_mode', productMode);
            if (productMode === 'single') {
                fd.append('product_code', bulkProduct.code);
            }
            fd.append('_token', CSRF_TOKEN);

            const res = await fetch(BULK_VALIDATE_URL, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Accept': 'application/json'
                },
                body: fd
            });

            const json = await res.json();

            if (json.success) {
                renderPreviewWithValidation(leads, json.results);
                renderValidationSummary(json);
                document.getElementById('bulkImportBtn').disabled = false;
            } else {
                renderPreview(leads);
                document.getElementById('bulkImportBtn').disabled = false;
            }
        } catch (err) {
            console.error('Validation error:', err);
            renderPreview(leads);
            document.getElementById('bulkImportBtn').disabled = false;
        } finally {
            btn.disabled = false;
            btn.innerHTML = orig;
        }
    });

    function checkStockWarning() {
        const stock = bulkProduct?.qty || 0;
        const warn = document.getElementById('bulkStockWarning');
        if (leads.length > stock) {
            warn.style.display = 'block';
            document.getElementById('bulkStockWarningText').textContent =
                `Only ${stock} units in stock but CSV has ${leads.length} rows. Only the first ${stock} orders will succeed.`;
        } else {
            warn.style.display = 'none';
        }
    }

    function renderPreviewWithValidation(leads, results) {
        document.getElementById('csvPreview').style.display = 'block';
        const tbody = document.getElementById('csvPreviewBody');
        tbody.innerHTML = '';

        leads.slice(0, 20).forEach((lead, i) => {
            const res = results[i] || {};
            const status = res.status || 'unknown';
            const statusClass = status === 'ok' ? 'city-ok' : status === 'invalid' ? 'city-invalid' : 'city-missing';
            const statusText = status === 'ok' ? '✓ Valid' : status === 'invalid' ? '✕ Invalid' : '⚠ Missing';

            const productInfo = lead.product_code || lead.product_id
                ? `<div class="product-cell">
                    <span>${lead.product_name || 'Unknown'}</span>
                    <span class="product-code">${lead.product_code || ''}</span>
                   </div>`
                : (bulkProduct?.name || 'Single Product');

            tbody.innerHTML += `
                <tr>
                    <td style="padding:8px 10px;">${i+1}</td>
                    <td style="padding:8px 10px;">${productInfo}</td>
                    <td style="padding:8px 10px;">${lead.customer_name}</td>
                    <td style="padding:8px 10px;">${lead.customer_phone}</td>
                    <td style="padding:8px 10px; max-width:200px; word-break:break-word;">${lead.customer_address || '<span class="text-danger">Missing</span>'}</td>
                    <td style="padding:8px 10px;">${lead.district || '-'}</td>
                    <td style="padding:8px 10px;"><span class="${statusClass}">${statusText}</span></td>
                    <td style="padding:8px 10px;">${lead.platform || '-'}</td>
                </tr>
            `;
        });
    }

    function renderValidationSummary(json) {
        const summary = document.getElementById('validationSummary');
        const badges = document.getElementById('validationBadges');
        const errorList = document.getElementById('errorList');
        const addressErrorList = document.getElementById('addressErrorList');

        summary.style.display = 'block';
        const valid = json.results?.filter(r => r.status === 'ok').length || 0;
        const invalid = json.invalid_count || 0;
        const missing = json.missing_count || 0;
        const addressMissing = json.address_missing_count || 0;

        badges.innerHTML = `
            <span class="validation-badge badge-ok">✓ ${valid} Valid Cities</span>
            ${invalid > 0 ? `<span class="validation-badge badge-err">✕ ${invalid} Invalid Cities</span>` : ''}
            ${missing > 0 ? `<span class="validation-badge badge-warn">⚠ ${missing} Missing Cities</span>` : ''}
            ${addressMissing > 0 ? `<span class="validation-badge badge-warn">⚠ ${addressMissing} Missing Addresses</span>` : ''}
        `;

        if (invalid > 0) {
            errorList.style.display = 'block';
            errorList.innerHTML = '<strong>Invalid Cities (not in Curfox network):</strong><br>' +
                json.results.filter(r => r.status === 'invalid')
                    .map(r => `Row ${r.row}: "${r.input}"`).join('<br>');
        } else {
            errorList.style.display = 'none';
        }

        if (addressMissing > 0) {
            addressErrorList.style.display = 'block';
            addressErrorList.innerHTML = '<strong>Rows Missing Address:</strong><br>' +
                json.address_missing.map(row => `Row ${row}`).join('<br>');
        } else {
            addressErrorList.style.display = 'none';
        }
    }

    function renderPreview(leads) {
        document.getElementById('csvPreview').style.display = 'block';
        const tbody = document.getElementById('csvPreviewBody');
        tbody.innerHTML = '';

        leads.slice(0, 20).forEach((lead, i) => {
            tbody.innerHTML += `
                <tr>
                    <td style="padding:8px 10px;">${i+1}</td>
                    <td style="padding:8px 10px;">${lead.product_code || (bulkProduct?.code || 'N/A')}</td>
                    <td style="padding:8px 10px;">${lead.customer_name}</td>
                    <td style="padding:8px 10px;">${lead.customer_phone}</td>
                    <td style="padding:8px 10px; max-width:200px;">${lead.customer_address || '-'}</td>
                    <td style="padding:8px 10px;">${lead.district || '-'}</td>
                    <td style="padding:8px 10px;">-</td>
                    <td style="padding:8px 10px;">${lead.platform || '-'}</td>
                </tr>
            `;
        });
    }

    // Bulk import
    document.getElementById('bulkImportBtn').addEventListener('click', async () => {
        const productMode = document.querySelector('input[name="product_mode"]:checked').value;

        if (productMode === 'single' && !bulkProduct) {
            return Swal.fire({ icon: 'error', title: 'No Product', text: 'Verify a product first' });
        }

        if (!leads.length) {
            return Swal.fire({ icon: 'error', title: 'No Data', text: 'Preview & Validate first' });
        }

        const allowNonCurfox = document.getElementById('allowNonCurfoxCity').checked ? '1' : '0';
        const defaultPlatform = document.getElementById('bulkDefaultPlatform').value;
        const date = document.getElementById('bulk_date').value;

        // Prepare leads data
        const leadsToSend = leads.map(lead => ({
            ...lead,
            // Ensure address is at least empty string if missing
            customer_address: lead.customer_address || ''
        }));

        const result = await Swal.fire({
            title: 'Confirm Bulk Import',
            html: `
                <div style="text-align:left;">
                    <p><strong>Mode:</strong> ${productMode === 'single' ? 'Single Product' : 'Multiple Products'}</p>
                    <p><strong>Orders to create:</strong> ${leadsToSend.length}</p>
                    <p><strong>Default Platform:</strong> ${defaultPlatform}</p>
                    ${allowNonCurfox === '1' ? '<p style="color:#f59e0b;">⚠ Non-Curfox cities will be saved as-is</p>' : ''}
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, Import',
            confirmButtonColor: '#10b981',
            showLoaderOnConfirm: true,
            preConfirm: async () => {
                try {
                    const fd = new FormData();
                    if (productMode === 'single') {
                        fd.append('product_code', bulkProduct.code);
                    }
                    fd.append('product_mode', productMode);
                    fd.append('default_platform', defaultPlatform);
                    fd.append('date', date);
                    fd.append('leads', JSON.stringify(leadsToSend));
                    fd.append('allow_non_curfox', allowNonCurfox);
                    fd.append('_token', CSRF_TOKEN);

                    const res = await fetch(BULK_STORE_URL, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': CSRF_TOKEN,
                            'Accept': 'application/json'
                        },
                        body: fd
                    });

                    const json = await res.json();
                    if (!res.ok || !json.success) {
                        throw new Error(json.message || 'Import failed');
                    }
                    return json;
                } catch (e) {
                    Swal.showValidationMessage(e.message);
                    return false;
                }
            }
        });

        if (result.isConfirmed && result.value?.success) {
            Swal.fire({
                icon: 'success',
                title: 'Import Complete!',
                html: `
                    <p>${result.value.message}</p>
                    ${result.value.errors?.length ?
                    `<div style="text-align:left; margin-top:10px; color:#ef4444; font-size:12px; max-height:150px; overflow-y:auto;">
                            ${result.value.errors.slice(0,10).map(e => e).join('<br>')}
                        </div>` : ''
                }
                `,
                confirmButtonColor: '#4f46e5'
            }).then(() => {
                window.location.href = result.value.redirect_url || '{{ route("seller.order.pending") }}';
            });
        }
    });
</script>
