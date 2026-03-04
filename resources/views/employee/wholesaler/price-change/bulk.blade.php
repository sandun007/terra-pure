@extends('employee.layout.app')

@push('title')
    Bulk Price Update
@endpush

@push('css')
    <style>
        .bulk-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
        }

        .selected-products-section {
            background: #f8fafc;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border: 1px solid #e2e8f0;
        }

        .product-chip {
            display: inline-flex;
            align-items: center;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 30px;
            padding: 0.5rem 1rem;
            margin: 0.25rem;
            font-size: 0.9rem;
        }

        .product-chip .remove {
            margin-left: 0.5rem;
            color: #ef4444;
            cursor: pointer;
        }

        .update-option-card {
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 1.5rem;
            cursor: pointer;
            transition: all 0.3s;
            height: 100%;
        }

        .update-option-card:hover {
            border-color: #667eea;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
        }

        .update-option-card.selected {
            border-color: #667eea;
            background: #eef2ff;
        }

        .update-option-icon {
            width: 48px;
            height: 48px;
            background: #eef2ff;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            color: #667eea;
            font-size: 1.5rem;
        }

        .preview-table {
            max-height: 400px;
            overflow-y: auto;
        }

        .preview-table table {
            font-size: 0.9rem;
        }

        .preview-table thead th {
            position: sticky;
            top: 0;
            background: #f8fafc;
            z-index: 10;
        }

        .calculation-badge {
            padding: 0.35rem 0.75rem;
            border-radius: 20px;
            font-weight: 600;
        }

        .badge-increase {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-decrease {
            background: #fee2e2;
            color: #991b1b;
        }

        .summary-card {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            color: white;
            border-radius: 12px;
            padding: 1.5rem;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="bulk-header">
            <h1 class="mb-2">Bulk Price Update</h1>
            <p class="mb-0 opacity-75">Update prices for multiple products at once</p>
        </div>

        <!-- Selected Products -->
        <div class="selected-products-section">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">
                    <i class="fas fa-check-circle text-success me-2"></i>
                    Selected Products ({{ count($selectedProducts) }})
                </h5>
                <a href="{{ route('employee.wholesaler.products.index') }}" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-plus me-1"></i>Add More Products
                </a>
            </div>

            <div class="selected-products-list">
                @foreach($selectedProducts as $product)
                    <span class="product-chip">
                    <span class="fw-bold me-2">{{ $product->code }}</span>
                    {{ $product->name }} - Rs. {{ number_format($product->selling_price, 2) }}
                    <span class="remove" onclick="removeProduct({{ $product->id }})">
                        <i class="fas fa-times-circle"></i>
                    </span>
                </span>
                @endforeach
            </div>

            <input type="hidden" id="selectedProductIds" value="{{ json_encode($selectedProducts->pluck('id')) }}">
        </div>

        <!-- Update Options -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="update-option-card" onclick="selectOption('fixed')" id="optionFixed">
                    <div class="update-option-icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <h6 class="fw-bold">Fixed Amount</h6>
                    <p class="text-muted small mb-0">Set specific price or add/subtract fixed amount</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="update-option-card" onclick="selectOption('percentage')" id="optionPercentage">
                    <div class="update-option-icon">
                        <i class="fas fa-percent"></i>
                    </div>
                    <h6 class="fw-bold">Percentage</h6>
                    <p class="text-muted small mb-0">Increase or decrease by percentage</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="update-option-card" onclick="selectOption('formula')" id="optionFormula">
                    <div class="update-option-icon">
                        <i class="fas fa-calculator"></i>
                    </div>
                    <h6 class="fw-bold">Formula</h6>
                    <p class="text-muted small mb-0">Use custom formula with {price}</p>
                </div>
            </div>
        </div>

        <!-- Update Form -->
        <form method="POST" action="{{ route('employee.wholesaler.price-change.bulk.store') }}" id="bulkForm">
            @csrf
            <input type="hidden" name="product_ids" id="productIdsInput" value="{{ json_encode($selectedProducts->pluck('id')) }}">
            <input type="hidden" name="update_type" id="updateTypeInput" value="">
            <input type="hidden" name="price_field" id="priceFieldInput" value="selling_price">
            <input type="hidden" name="operation" id="operationInput" value="set">

            <!-- Update Details -->
            <div class="row">
                <div class="col-md-8">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Update Parameters</h5>

                            <!-- Price Field Selection -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Apply To</label>
                                <div class="d-flex gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="price_field_radio"
                                               id="fieldSelling" value="selling_price" checked onchange="updatePriceField('selling_price')">
                                        <label class="form-check-label" for="fieldSelling">
                                            Selling Price
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="price_field_radio"
                                               id="fieldDisplay" value="display_price" onchange="updatePriceField('display_price')">
                                        <label class="form-check-label" for="fieldDisplay">
                                            Display Price
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="price_field_radio"
                                               id="fieldFinal" value="final_price" onchange="updatePriceField('final_price')">
                                        <label class="form-check-label" for="fieldFinal">
                                            Final Price
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Operation Selection -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Operation</label>
                                <div class="d-flex gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="operation_radio"
                                               id="opSet" value="set" checked onchange="updateOperation('set')">
                                        <label class="form-check-label" for="opSet">
                                            Set to Value
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="operation_radio"
                                               id="opIncrease" value="increase" onchange="updateOperation('increase')">
                                        <label class="form-check-label" for="opIncrease">
                                            Increase
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="operation_radio"
                                               id="opDecrease" value="decrease" onchange="updateOperation('decrease')">
                                        <label class="form-check-label" for="opDecrease">
                                            Decrease
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Value Input -->
                            <div class="mb-3">
                                <label class="form-label fw-bold" id="valueLabel">Value</label>
                                <div class="input-group">
                                    <span class="input-group-text" id="valuePrefix">Rs.</span>
                                    <input type="number" name="value" id="updateValue" class="form-control"
                                           step="0.01" min="0" required oninput="updatePreview()">
                                    <span class="input-group-text" id="valueSuffix" style="display: none;">%</span>
                                </div>
                            </div>

                            <!-- Discount Option -->
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="apply_discount"
                                           id="applyDiscount" value="1" onchange="toggleDiscount()">
                                    <label class="form-check-label fw-bold" for="applyDiscount">
                                        Apply/Update Discount
                                    </label>
                                </div>
                            </div>

                            <div id="discountSection" style="display: none;">
                                <div class="mb-3">
                                    <label class="form-label">Discount Percentage</label>
                                    <div class="input-group">
                                        <input type="number" name="discount_value" id="discountValue"
                                               class="form-control" step="0.1" min="0" max="100">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Info -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Change Reason</label>
                                <select name="change_reason" class="form-select">
                                    <option value="">Select Reason</option>
                                    <option value="bulk_update">Bulk Update</option>
                                    <option value="price_adjustment">Price Adjustment</option>
                                    <option value="market_trend">Market Trend</option>
                                    <option value="promotion">Promotion</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Notes</label>
                                <textarea name="notes" class="form-control" rows="2"></textarea>
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="require_approval"
                                           id="requireApproval" value="1">
                                    <label class="form-check-label" for="requireApproval">
                                        <strong>Require Approval</strong> - Changes will need approval
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Summary -->
                <div class="col-md-4">
                    <div class="summary-card mb-4">
                        <h6 class="text-white-50 mb-3">Update Summary</h6>
                        <div class="mb-3">
                            <small class="text-white-50">Products to Update</small>
                            <h3 class="text-white mb-0">{{ count($selectedProducts) }}</h3>
                        </div>
                        <div class="mb-3">
                            <small class="text-white-50">Update Type</small>
                            <h5 class="text-white mb-0" id="summaryType">Fixed Amount (Set)</h5>
                        </div>
                        <div class="mb-3">
                            <small class="text-white-50">Value</small>
                            <h4 class="text-white mb-0" id="summaryValue">-</h4>
                        </div>
                        <hr class="opacity-25">
                        <div class="d-flex justify-content-between text-white-50">
                            <span>Average Current Price</span>
                            <span>Rs. {{ number_format($selectedProducts->avg('selling_price'), 2) }}</span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save me-2"></i>Apply Bulk Update
                        </button>
                        <a href="{{ route('employee.wholesaler.price-change.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                    </div>
                </div>
            </div>

            <!-- Preview Table -->
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Preview Changes</h5>
                </div>
                <div class="card-body preview-table">
                    <table class="table table-sm">
                        <thead>
                        <tr>
                            <th>Code</th>
                            <th>Product</th>
                            <th>Current Price</th>
                            <th>New Price</th>
                            <th>Difference</th>
                            <th>Change %</th>
                        </tr>
                        </thead>
                        <tbody id="previewBody">
                        @foreach($selectedProducts as $product)
                            <tr data-product-id="{{ $product->id }}"
                                data-current-price="{{ $product->selling_price }}"
                                data-display-price="{{ $product->display_price ?? $product->selling_price }}"
                                data-final-price="{{ $product->final_price }}"
                                data-discount="{{ $product->discount ?? 0 }}">
                                <td><code>{{ $product->code }}</code></td>
                                <td>{{ Str::limit($product->name, 30) }}</td>
                                <td>Rs. {{ number_format($product->selling_price, 2) }}</td>
                                <td id="newPrice-{{ $product->id }}">-</td>
                                <td id="diff-{{ $product->id }}">-</td>
                                <td id="percent-{{ $product->id }}">-</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('script')
    <script>
        let currentOption = 'fixed';
        let currentPriceField = 'selling_price';
        let currentOperation = 'set';

        function selectOption(option) {
            currentOption = option;

            // Update UI
            document.getElementById('optionFixed').classList.remove('selected');
            document.getElementById('optionPercentage').classList.remove('selected');
            document.getElementById('optionFormula').classList.remove('selected');
            document.getElementById('option' + option.charAt(0).toUpperCase() + option.slice(1)).classList.add('selected');

            // Update form
            document.getElementById('updateTypeInput').value = option;

            // Update UI elements
            updateValueInputUI();
            updateSummary();
            updatePreview();
        }

        function updatePriceField(field) {
            currentPriceField = field;
            document.getElementById('priceFieldInput').value = field;
            updatePreview();
            updateSummary();
        }

        function updateOperation(operation) {
            currentOperation = operation;
            document.getElementById('operationInput').value = operation;
            updateValueInputUI();
            updateSummary();
            updatePreview();
        }

        function updateValueInputUI() {
            const valueLabel = document.getElementById('valueLabel');
            const valuePrefix = document.getElementById('valuePrefix');
            const valueSuffix = document.getElementById('valueSuffix');

            if (currentOption === 'percentage') {
                valueLabel.innerText = 'Percentage';
                valuePrefix.style.display = 'none';
                valueSuffix.style.display = 'inline';
                valueSuffix.innerText = '%';
            } else if (currentOption === 'formula') {
                valueLabel.innerText = 'Formula (use {price})';
                valuePrefix.style.display = 'none';
                valueSuffix.style.display = 'none';
            } else {
                valueLabel.innerText = 'Amount';
                valuePrefix.style.display = 'inline';
                valueSuffix.style.display = 'none';
            }
        }

        function toggleDiscount() {
            const discountSection = document.getElementById('discountSection');
            const applyDiscount = document.getElementById('applyDiscount').checked;
            discountSection.style.display = applyDiscount ? 'block' : 'none';
            updatePreview();
        }

        function updateSummary() {
            const value = document.getElementById('updateValue').value || 0;
            let typeText = '';

            if (currentOption === 'fixed') {
                typeText = 'Fixed Amount';
            } else if (currentOption === 'percentage') {
                typeText = 'Percentage';
            } else {
                typeText = 'Formula';
            }

            typeText += ' (' +
                (currentOperation === 'set' ? 'Set' :
                    currentOperation === 'increase' ? 'Increase' : 'Decrease') +
                ')';

            document.getElementById('summaryType').innerText = typeText;

            let valueText = value;
            if (currentOption === 'fixed' && currentOperation === 'set') {
                valueText = 'Rs. ' + parseFloat(value).toFixed(2);
            } else if (currentOption === 'fixed') {
                valueText = 'Rs. ' + parseFloat(value).toFixed(2) +
                    (currentOperation === 'increase' ? ' increase' : ' decrease');
            } else if (currentOption === 'percentage') {
                valueText = value + '% ' +
                    (currentOperation === 'set' ? ' of current' :
                        currentOperation === 'increase' ? ' increase' : ' decrease');
            } else {
                valueText = value;
            }

            document.getElementById('summaryValue').innerText = valueText;
        }

        function updatePreview() {
            const value = parseFloat(document.getElementById('updateValue').value) || 0;
            const applyDiscount = document.getElementById('applyDiscount').checked;
            const discountValue = parseFloat(document.getElementById('discountValue').value) || 0;

            document.querySelectorAll('[data-product-id]').forEach(row => {
                const productId = row.dataset.productId;
                const currentPrice = parseFloat(row.dataset.currentPrice);
                const displayPrice = parseFloat(row.dataset.displayPrice);
                const finalPrice = parseFloat(row.dataset.finalPrice);
                const oldDiscount = parseFloat(row.dataset.discount);

                let newPrice = currentPrice;
                let newDisplayPrice = displayPrice;

                // Apply update based on selected price field
                if (currentPriceField === 'selling_price') {
                    newPrice = calculateNewPrice(currentPrice, value);
                    newDisplayPrice = displayPrice;
                } else if (currentPriceField === 'display_price') {
                    newPrice = currentPrice;
                    newDisplayPrice = calculateNewPrice(displayPrice, value);
                } else {
                    // final_price - need to back-calculate
                    let newFinal = calculateNewPrice(finalPrice, value);
                    newDisplayPrice = applyDiscount && discountValue > 0
                        ? newFinal / (1 - discountValue / 100)
                        : newFinal;
                    newPrice = currentPrice;
                }

                // Apply discount if selected
                let newFinalPrice = newDisplayPrice;
                const finalDiscount = applyDiscount ? discountValue : oldDiscount;
                if (finalDiscount > 0) {
                    newFinalPrice = newDisplayPrice * (1 - finalDiscount / 100);
                }

                // Calculate difference
                const difference = newFinalPrice - finalPrice;
                const percentage = finalPrice > 0 ? (difference / finalPrice * 100) : 0;

                // Update display
                document.getElementById('newPrice-' + productId).innerHTML =
                    '<strong>Rs. ' + newFinalPrice.toFixed(2) + '</strong>';

                document.getElementById('diff-' + productId).innerHTML =
                    '<span class="' + (difference > 0 ? 'text-success' : (difference < 0 ? 'text-danger' : '')) + '">' +
                    (difference > 0 ? '+' : '') + difference.toFixed(2) +
                    '</span>';

                document.getElementById('percent-' + productId).innerHTML =
                    '<span class="' + (percentage > 0 ? 'text-success' : (percentage < 0 ? 'text-danger' : '')) + '">' +
                    (percentage > 0 ? '+' : '') + percentage.toFixed(2) + '%' +
                    '</span>';
            });
        }

        function calculateNewPrice(current, value) {
            if (currentOption === 'fixed') {
                if (currentOperation === 'set') {
                    return value;
                } else if (currentOperation === 'increase') {
                    return current + value;
                } else if (currentOperation === 'decrease') {
                    return Math.max(0, current - value);
                }
            } else if (currentOption === 'percentage') {
                if (currentOperation === 'set') {
                    return current * (value / 100);
                } else if (currentOperation === 'increase') {
                    return current * (1 + value / 100);
                } else if (currentOperation === 'decrease') {
                    return current * (1 - value / 100);
                }
            } else if (currentOption === 'formula') {
                try {
                    // Simple formula evaluation - be careful with this in production
                    const formula = value.replace('{price}', current);
                    // Only allow basic math operations
                    if (/^[0-9+\-*/().]+$/.test(formula)) {
                        return eval(formula);
                    }
                } catch (e) {
                    console.error('Formula error', e);
                }
            }
            return current;
        }

        function removeProduct(productId) {
            // Remove from selected products
            const chips = document.querySelectorAll('.product-chip');
            chips.forEach(chip => {
                if (chip.querySelector('.remove').onclick.toString().includes(productId)) {
                    chip.remove();
                }
            });

            // Update hidden input
            const ids = JSON.parse(document.getElementById('selectedProductIds').value);
            const newIds = ids.filter(id => id != productId);
            document.getElementById('selectedProductIds').value = JSON.stringify(newIds);
            document.getElementById('productIdsInput').value = JSON.stringify(newIds);

            // Remove from preview table
            document.querySelector(`tr[data-product-id="${productId}"]`).remove();
        }

        // Initialize
        window.addEventListener('load', function() {
            selectOption('fixed');
        });
    </script>
@endpush
