<form id="singleOrderForm" action="{{ route('seller.order.store') }}" method="POST">
    @csrf
    <input type="hidden" name="date" value="{{ date('Y-m-d') }}">

    <div class="form-container">
        <!-- Product Search -->
        <div class="product-search-section">
            <div class="search-header">
                <span class="material-icons-round" style="color:var(--primary)">qr_code_scanner</span>
                Search Product by Code
            </div>
            <div class="input-with-button">
                <div style="flex:1">
                    <input type="text" id="searchProductCode" class="form-control"
                           placeholder="Enter product code (e.g., DX/ITM/0001)" autocomplete="off">
                </div>
                <button type="button" id="fetchProductBtn" class="btn btn-primary">
                    <span class="material-icons-round" style="font-size:18px">search</span>Fetch Product
                </button>
                <button type="button" id="resetBtn" class="btn btn-danger" style="display:none">
                    <span class="material-icons-round" style="font-size:18px">refresh</span>Reset
                </button>
            </div>
        </div>

        <!-- Product Info (hidden initially) -->
        <div id="productInfoCard" class="product-info-card" style="display:none">
            <div class="product-info-header">
                <img id="productImagePreview" src="" alt="Product" class="product-image-preview" style="display:none">
                <div id="productImagePlaceholder" class="product-image-placeholder">
                    <span class="material-icons-round" style="font-size:32px">inventory_2</span>
                </div>
                <div style="flex:1">
                    <h4 id="displayProductName">-</h4>
                    <span class="product-code-badge">
                        <span class="material-icons-round" style="font-size:14px">tag</span>
                        <span id="displayProductCode">-</span>
                    </span>
                </div>
            </div>
            <div class="product-details-grid">
                <div class="detail-item">
                    <div class="detail-label">Price</div>
                    <div class="detail-value" style="color:var(--danger)">Rs. <span id="displayProductPrice">0.00</span></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Available Stock</div>
                    <div class="detail-value"><span id="displayProductStock">0</span> units</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Status</div>
                    <div class="detail-value">
                        <span id="stockStatusBadge" class="stock-badge stock-available">✓ Available</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hidden fields -->
        <input type="hidden" name="product_code"  id="product_code">
        <input type="hidden" name="product_id"    id="product_id">
        <input type="hidden" name="product_name"  id="product_name">
        <input type="hidden" name="product_price" id="product_price">
        <input type="hidden" name="product_image" id="product_image">
        <input type="hidden" name="destination_city_id"    id="destination_city_id">
        <input type="hidden" name="destination_city_name"  id="destination_city_name">
        <input type="hidden" name="destination_state_name" id="destination_state_name">

        <!-- Order Details -->
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Customer Name <span class="required">*</span></label>
                <input type="text" name="customer_name" id="customer_name" class="form-control" placeholder="Enter customer name" required>
            </div>

            <div class="form-group">
                <label class="form-label">Customer Phone <span class="required">*</span></label>
                <div class="phone-pair">
                    <input type="text" name="customer_phone" id="customer_phone" class="form-control" placeholder="Primary number" required>
                    <input type="text" name="customer_phone_2" id="customer_phone_2" class="form-control" placeholder="Secondary (optional)">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Quantity <span class="required">*</span></label>
                <input type="number" name="quantity" id="quantity" class="form-control" min="1" value="1" disabled required>
                <small class="text-muted">Available: <span id="availableStock">0</span></small>
            </div>

            <div class="form-group">
                <label class="form-label">Delivery City <span class="required">*</span></label>
                <div class="city-autocomplete-wrapper">
                    <input type="text" name="district" id="district" class="form-control"
                           placeholder="Type to search city..." autocomplete="off" required>
                    <div class="city-dropdown" id="cityDropdown"></div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Platform</label>
                <select name="platform" id="platform" class="form-control">
                    <option value="fb">Facebook</option>
                    <option value="ig">Instagram</option>
                    <option value="tiktok">TikTok</option>
                    <option value="website">Website</option>
                    <option value="manual">Manual</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Delivery Address</label>
                <textarea name="customer_address" id="customer_address" class="form-control" rows="2" placeholder="Enter delivery address"></textarea>
            </div>
        </div>

        <div class="form-group mb-4">
            <label class="form-label">Order Notes</label>
            <textarea name="remark" id="remark" class="form-control" rows="2" placeholder="Add notes..."></textarea>
        </div>

        <div class="form-actions">
            <button type="button" id="printBtn" class="btn btn-secondary" disabled>
                <span class="material-icons-round" style="font-size:18px">print</span>Print
            </button>
            <button type="button" id="submitBtn" class="btn btn-success" disabled>
                <span class="material-icons-round" style="font-size:18px">shopping_cart</span>Create Order
            </button>
        </div>
    </div>
</form>

<script>
    const CITIES_API_URL = '{{ route("seller.order.cities.json") }}';
    let CITIES = [], citiesLoaded = false;
    let currentProduct = null;

    @php
        $serverCities = [];
        if (!empty($statesWithCities)) {
            foreach ($statesWithCities as $state) {
                foreach ($state['cities'] ?? [] as $city) {
                    if ($city['is_active'] ?? true) {
                        $serverCities[] = ['id' => $city['id'], 'name' => $city['name'], 'state_name' => $state['name']];
                    }
                }
            }
        }
    @endphp
    const SERVER_CITIES = @json($serverCities);
    if (SERVER_CITIES.length > 0) { CITIES = SERVER_CITIES; citiesLoaded = true; }

    async function loadCities() {
        try {
            const res = await fetch(CITIES_API_URL);
            const json = await res.json();
            if (json.success) { CITIES = json.data; citiesLoaded = true; }
        } catch (e) { console.warn('City load failed'); }
    }
    if (!citiesLoaded) loadCities();

    // Product fetch
    document.getElementById('fetchProductBtn').addEventListener('click', async () => {
        const code = document.getElementById('searchProductCode').value.trim();
        if (!code) return Swal.fire({ icon: 'error', title: 'Missing Code', text: 'Enter a product code' });

        const btn = document.getElementById('fetchProductBtn');
        const orig = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner"></span> Fetching...';

        try {
            const res = await fetch(`/seller/order/product/${encodeURIComponent(code)}/details`);
            const data = await res.json();
            if (!data.success) throw new Error(data.message);

            displayProduct(data.product);
        } catch (err) {
            Swal.fire({ icon: 'error', title: 'Not Found', text: err.message });
        } finally {
            btn.disabled = false;
            btn.innerHTML = orig;
        }
    });

    function displayProduct(p) {
        currentProduct = p;
        document.getElementById('productInfoCard').style.display = 'block';
        document.getElementById('resetBtn').style.display = 'inline-flex';

        document.getElementById('product_code').value = p.code;
        document.getElementById('product_id').value = p.id;
        document.getElementById('product_name').value = p.name;
        document.getElementById('product_price').value = p.display_price;
        document.getElementById('product_image').value = p.image || '';

        document.getElementById('displayProductName').textContent = p.name;
        document.getElementById('displayProductCode').textContent = p.code;
        document.getElementById('displayProductPrice').textContent = p.display_price.toFixed(2);

        const stock = parseInt(p.qty) || 0;
        document.getElementById('displayProductStock').textContent = stock;
        document.getElementById('availableStock').textContent = stock;

        const badge = document.getElementById('stockStatusBadge');
        if (stock <= 0) {
            badge.className = 'stock-badge stock-out';
            badge.innerHTML = '<span class="material-icons-round" style="font-size:14px">cancel</span> Out of Stock';
        } else if (stock <= 10) {
            badge.className = 'stock-badge stock-low';
            badge.innerHTML = '<span class="material-icons-round" style="font-size:14px">warning</span> Low Stock';
        } else {
            badge.className = 'stock-badge stock-available';
            badge.innerHTML = '<span class="material-icons-round" style="font-size:14px">check_circle</span> Available';
        }

        if (p.image) {
            document.getElementById('productImagePreview').src = p.image;
            document.getElementById('productImagePreview').style.display = 'block';
            document.getElementById('productImagePlaceholder').style.display = 'none';
        }

        const qty = document.getElementById('quantity');
        if (stock > 0) { qty.disabled = false; qty.max = stock; qty.value = 1; }
        else { qty.disabled = true; qty.value = 0; }

        document.getElementById('submitBtn').disabled = false;
        document.getElementById('printBtn').disabled = false;
    }

    document.getElementById('resetBtn').addEventListener('click', () => {
        document.getElementById('productInfoCard').style.display = 'none';
        document.getElementById('resetBtn').style.display = 'none';
        document.getElementById('searchProductCode').value = '';
        document.getElementById('quantity').disabled = true;
        document.getElementById('submitBtn').disabled = true;
        document.getElementById('printBtn').disabled = true;
        currentProduct = null;
    });

    // City autocomplete
    (function() {
        const input = document.getElementById('district');
        const dropdown = document.getElementById('cityDropdown');
        let debounceTimer;

        input.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => search(this.value), 150);
        });

        function search(query) {
            if (!query || query.length < 1) { dropdown.classList.remove('open'); return; }
            const q = query.toLowerCase();
            const matches = CITIES.filter(c => c.name.toLowerCase().includes(q)).slice(0, 50);
            renderDropdown(matches, query);
        }

        function renderDropdown(matches, q) {
            dropdown.innerHTML = '';
            if (matches.length === 0) {
                dropdown.innerHTML = '<div class="city-dropdown-item">No cities found</div>';
            } else {
                matches.forEach(c => {
                    const div = document.createElement('div');
                    div.className = 'city-dropdown-item';
                    div.innerHTML = `<span class="material-icons-round" style="font-size:14px">location_on</span>
                                    <span>${c.name}</span>
                                    <span class="city-state">${c.state_name || ''}</span>`;
                    div.onclick = () => selectCity(c);
                    dropdown.appendChild(div);
                });
            }
            dropdown.classList.add('open');
        }

        function selectCity(c) {
            input.value = c.name;
            document.getElementById('destination_city_id').value = c.id;
            document.getElementById('destination_city_name').value = c.name;
            document.getElementById('destination_state_name').value = c.state_name || '';
            dropdown.classList.remove('open');
        }

        input.addEventListener('blur', () => setTimeout(() => dropdown.classList.remove('open'), 200));
    })();

    // Submit order
    document.getElementById('submitBtn').addEventListener('click', async function(e) {
        e.preventDefault();
        if (!currentProduct) return Swal.fire({ icon: 'error', title: 'No Product', text: 'Fetch a product first' });

        const customerName = document.getElementById('customer_name').value.trim();
        const customerPhone = document.getElementById('customer_phone').value.trim();
        const district = document.getElementById('district').value.trim();
        const cityId = document.getElementById('destination_city_id').value;
        const quantity = parseInt(document.getElementById('quantity').value) || 0;

        if (!customerName) return Swal.fire({ icon: 'error', title: 'Missing', text: 'Enter customer name' });
        if (!customerPhone) return Swal.fire({ icon: 'error', title: 'Missing', text: 'Enter customer phone' });
        if (!district) return Swal.fire({ icon: 'error', title: 'Missing', text: 'Select delivery city' });
        if (!cityId) return Swal.fire({ icon: 'error', title: 'Invalid City', text: 'Select a valid city from dropdown' });
        if (quantity <= 0 || quantity > currentProduct.qty) return Swal.fire({ icon: 'error', title: 'Invalid Quantity', text: `Enter quantity between 1 and ${currentProduct.qty}` });

        const form = document.getElementById('singleOrderForm');
        const formData = new FormData(form);

        const result = await Swal.fire({
            title: 'Create Order?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, Create',
            showLoaderOnConfirm: true,
            preConfirm: async () => {
                const res = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                });
                const json = await res.json();
                if (!res.ok || !json.success) throw new Error(json.message || 'Error');
                return json;
            }
        });

        if (result.isConfirmed && result.value.success) {
            Swal.fire({ icon: 'success', title: 'Order Created!', timer: 1500, showConfirmButton: false })
                .then(() => window.location.href = result.value.redirect_url || '{{ route("seller.order.pending") }}');
        }
    });
</script>
