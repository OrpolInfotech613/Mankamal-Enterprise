@extends('app')

@section('content')
<div class="content">
    <h2 class="intro-y text-lg font-medium mt-10 heading">
        Order Create
    </h2>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Validation failed!</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="orderForm" class="grid grid-cols-12 gap-4" action="{{ route('orders.store') }}" method="post">
        @csrf

        <!-- Dealer -->
        <div class="col-span-12 md:col-span-6">
            <label for="dealer_id" class="block text-sm font-medium text-gray-700 mb-1">
                Dealer Name <span class="text-red-500">*</span>
            </label>
            <select id="dealer_id" name="dealer_id" required class="form-control">
                <option value="">Select a Dealer</option>
                @foreach ($dealers as $dealer)
                    <option value="{{ $dealer->id }}" {{ old('dealer_id') == $dealer->id ? 'selected' : '' }}>
                        {{ $dealer->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Customer -->
        <div class="col-span-12 md:col-span-6">
            <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-1">
                Customer Name <span class="text-red-500">*</span>
            </label>
            <input type="text" id="customer_name" name="customer_name" value="{{ old('customer_name') }}" required
                placeholder="Customer Name"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>

        <!-- Product Search -->
        <div class="col-span-12 md:col-span-6">
            <label for="product_search" class="form-label w-full flex flex-col sm:flex-row">
                Product Name<span style="color: red;margin-left: 3px;"> *</span>
            </label>
            <div class="product-search-container" style="position: relative;">
                <input id="product_search" type="text" name="product_search" class="form-control field-new"
                    placeholder="Type to search products..." autocomplete="off" required>
                <input type="hidden" id="product_id" name="product_id" required>
                <div id="product_dropdown" class="product-dropdown" style="display: none;">
                    <div class="dropdown-content"></div>
                </div>
            </div>
        </div>

        <!-- Production Steps -->
        <div class="col-span-12 md:col-span-6">
            <label for="production_step" class="block text-sm font-medium text-gray-700 mb-1">Production Step</label>
            <div class="flex flex-col md:flex-row gap-4">
                @foreach ($steps as $stepOrder => $processingSteps)
                    <div class="mb-4">
                        @if ($processingSteps->count() > 1)
                            {{-- Multiple departments in same step_order → Radio buttons --}}
                            @foreach ($processingSteps as $step)
                                <label class="flex items-center space-x-2">
                                    <input type="radio" name="production_step[]" value="{{ $step->department->id }}"
                                        {{ in_array($step->department->id, old('production_step', [])) ? 'checked' : '' }}
                                        class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500 mr-2">
                                    <span>{{ $step->department->name ?? 'Unknown Department' }}</span>
                                </label>
                            @endforeach
                        @else
                            @php $step = $processingSteps->first(); @endphp
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" name="production_step[]" value="{{ $step->department->id }}"
                                    {{ in_array($step->department->id, old('production_step', [])) ? 'checked' : '' }}
                                    class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500 mr-1">
                                <span>{{ $step->department->name ?? 'Unknown Department' }}</span>
                            </label>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Price -->
        <div class="col-span-12 md:col-span-6">
            <label for="price" class="block text-sm font-medium text-gray-700 mb-1">
                Price (₹) <span class="text-red-500">*</span>
            </label>
            <input type="number" id="price" name="price" min="0" step="0.01"
                value="{{ old('price') }}" required placeholder="Price"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>

        <!-- Quantity -->
        <div class="col-span-12 md:col-span-6">
            <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">
                Quantity <span class="text-red-500">*</span>
            </label>
            <input type="number" id="quantity" name="quantity" min="1" value="{{ old('quantity') }}" required
                placeholder="Quantity"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>

        <!-- Shade -->
        <div class="col-span-12 md:col-span-6">
            <label for="shade_number" class="block text-sm font-medium text-gray-700 mb-1">Shade Number</label>
            <input type="text" id="shade_number" name="shade_number" value="{{ old('shade_number') }}"
                placeholder="Shade Number"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>

        <!-- Color -->
        <div class="col-span-12 md:col-span-6">
            <label for="color" class="block text-sm font-medium text-gray-700 mb-1">Color</label>
            <input type="text" id="color" name="color" value="{{ old('color') }}" placeholder="Color"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>

        <!-- Delivery -->
        <div class="col-span-12 md:col-span-6">
            <label for="delivery_time" class="block text-sm font-medium text-gray-700 mb-1">
                Delivery Time <span class="text-red-500">*</span>
            </label>
            <input type="date" id="delivery_time" name="delivery_time" value="{{ old('delivery_time') }}" required
                placeholder="Delivery Time"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>

        <!-- Actions -->
        <div class="col-span-12 flex justify-between pt-6 border-t">
            <button type="submit" class="btn btn-primary shadow-md mr-2 btn-hover">
                <i class="fas fa-save mr-2"></i> Save Order
            </button>
        </div>
    </form>
</div>
@endsection
@push('styles')
    <style>
        .product-search-container {
            position: relative;
        }

        .product-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            max-height: 200px;
            overflow-y: auto;
        }

        .dropdown-item {
            padding: 10px 15px;
            cursor: pointer;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .dropdown-item:last-child {
            border-bottom: none;
        }

        .dropdown-item:hover,
        .dropdown-item.highlighted {
            background-color: #f5f5f5;
        }

        .dropdown-item.selected {
            background-color: #e3f2fd;
        }

        .product-name {
            font-weight: 500;
        }

        .product-prices {
            font-size: 0.85em;
            color: #666;
        }

        .no-results {
            padding: 15px;
            text-align: center;
            color: #666;
            font-style: italic;
        }
    </style>
@endpush


@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const productSearch = document.getElementById('product_search');
        const productId = document.getElementById('product_id');
        const dropdown = document.getElementById('product_dropdown');
        const dropdownContent = dropdown.querySelector('.dropdown-content');

        let currentProducts = [];
        let currentIndex = -1;
        let searchTimeout;
        let isSelecting = false;

        function setupEnterNavigation() {
            let currentFieldIndex = 0;

            const formFields = [
                { selector: '#dealer_id', type: 'select' },
                { selector: '#customer_name', type: 'input' },
                { selector: '#product_search', type: 'input' },
                { selector: '#product_search', type: 'input' },
                { selector: '#price', type: 'input' },
                { selector: '#quantity', type: 'input' },
                { selector: '#shade_number', type: 'input' },
                { selector: '#color', type: 'input' },
                { selector: '#delivery_time', type: 'input' },
            ];

            function focusField(selector) {
                const element = document.querySelector(selector);
                if (element) {
                    element.focus();
                    if (element.tagName === 'SELECT') {
                        setTimeout(() => {
                            if (element.size <= 1) {
                                element.click();
                            }
                        }, 100);
                    }
                }
            }

            function handleFormFieldNavigation(e, fieldIndex) {
                if (e.key === 'Enter') {
                    e.preventDefault();

                    if (fieldIndex < formFields.length - 1) {
                        currentFieldIndex = fieldIndex + 1;
                        focusField(formFields[currentFieldIndex].selector);
                    } else {
                        const submitButton = document.querySelector('button[type="submit"]');
                        if (submitButton) {
                            submitButton.focus();
                        }
                    }
                }
            }

            formFields.forEach((field, index) => {
                const element = document.querySelector(field.selector);
                if (element) {
                    element.addEventListener('keydown', (e) => {
                        if (e.key === 'Enter') {
                            handleFormFieldNavigation(e, index);
                        }
                    });
                }
            });

            setTimeout(() => {
                focusField(formFields[0].selector);
            }, 500);
        }

        setupEnterNavigation();

        // Search products with debounce
        productSearch.addEventListener('input', function() {
            const searchTerm = this.value.trim();

            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                if (searchTerm.length >= 2) {
                    searchProducts(searchTerm);
                } else {
                    hideDropdown();
                }
            }, 300);
        });

        // Handle keyboard navigation
        productSearch.addEventListener('keydown', function(e) {
            if (dropdown.style.display === 'none') return;

            switch (e.key) {
                case 'ArrowDown':
                    e.preventDefault();
                    navigateDropdown(1);
                    break;
                case 'ArrowUp':
                    e.preventDefault();
                    navigateDropdown(-1);
                    break;
                case 'Enter':
                    e.preventDefault();
                    if (currentIndex >= 0 && currentProducts[currentIndex]) {
                        selectProduct(currentProducts[currentIndex]);
                    }
                    break;
                case 'Escape':
                    hideDropdown();
                    break;
            }
        });

        // Hide dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!productSearch.contains(e.target) && !dropdown.contains(e.target)) {
                hideDropdown();
            }
        });

        // Prevent form submission on Enter if dropdown is open
        productSearch.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && dropdown.style.display !== 'none') {
                e.preventDefault();
            }
        });

        function searchProducts(searchTerm) {
            fetch(`{{ route('products.search') }}?q=${encodeURIComponent(searchTerm)}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        currentProducts = data.products;
                        displayProducts(currentProducts);
                    } else {
                        showNoResults();
                    }
                })
                .catch(error => {
                    console.error('Error searching products:', error);
                    showNoResults();
                });
        }

        function displayProducts(products) {
            if (products.length === 0) {
                showNoResults();
                return;
            }

            dropdownContent.innerHTML = '';
            currentIndex = -1;

            products.forEach((product, index) => {
                const item = document.createElement('div');
                item.className = 'dropdown-item';
                item.dataset.index = index;

                item.innerHTML = `
                    <div class="product-name">${product.text}</div>
                    `;

                item.addEventListener('click', function() {
                    selectProduct(product);
                });

                dropdownContent.appendChild(item);
            });

            showDropdown();
        }

        function showNoResults() {
            dropdownContent.innerHTML = '<div class="no-results">No products found</div>';
            currentProducts = [];
            currentIndex = -1;
            showDropdown();
        }

        function navigateDropdown(direction) {
            const items = dropdownContent.querySelectorAll('.dropdown-item');
            if (items.length === 0) return;

            // Remove current highlight
            if (currentIndex >= 0) {
                items[currentIndex].classList.remove('highlighted');
            }

            // Calculate new index
            currentIndex += direction;
            if (currentIndex < 0) currentIndex = items.length - 1;
            if (currentIndex >= items.length) currentIndex = 0;

            // Add highlight to new item
            items[currentIndex].classList.add('highlighted');

            // Scroll into view
            items[currentIndex].scrollIntoView({
                block: 'nearest',
                behavior: 'smooth'
            });
        }

        function selectProduct(product) {
            isSelecting = true;

            productSearch.value = product.text;
            productId.value = product.id;


            hideDropdown();

            setTimeout(() => {
                isSelecting = false;
            }, 100);
        }

        function showDropdown() {
            dropdown.style.display = 'block';
        }

        function hideDropdown() {
            dropdown.style.display = 'none';
            currentIndex = -1;

            // Clear highlights
            const items = dropdownContent.querySelectorAll('.dropdown-item');
            items.forEach(item => item.classList.remove('highlighted'));
        }

        // Clear product selection if search input is manually cleared
        productSearch.addEventListener('blur', function() {
            if (!isSelecting && this.value.trim() === '') {
                productId.value = '';
            }
        });
    });
</script>
@endpush
