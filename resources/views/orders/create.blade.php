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

            <div class="col-span-12 md:col-span-6">
                <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-1">
                    Customer Name <span class="text-red-500">*</span>
                </label>
                <input type="text" id="customer_name" name="customer_name" value="{{ old('customer_name') }}" required
                    placeholder="Customer Name"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div class="col-span-12 md:col-span-6">
                <label for="product_id" class="block text-sm font-medium text-gray-700 mb-1">
                    Product Name <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <!-- Search Input -->
                    <input type="text" id="productSearch" placeholder="Search Product..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                    <!-- Hidden field to store product_id -->
                    <input type="hidden" name="product_id" id="product_id" required>

                    <!-- Dropdown Results -->
                    <ul id="productResults"
                        class="absolute z-10 w-full bg-white border border-gray-300 rounded-lg mt-1 max-h-60 overflow-y-auto hidden">
                    </ul>
                </div>
            </div>

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

            <div class="col-span-12 md:col-span-6">
                <label for="price" class="block text-sm font-medium text-gray-700 mb-1">
                    Price (₹) <span class="text-red-500">*</span>
                </label>
                <input type="number" id="price" name="price" min="0" step="0.01"
                    value="{{ old('price') }}" required placeholder="Price"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div class="col-span-12 md:col-span-6">
                <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">
                    Quantity <span class="text-red-500">*</span>
                </label>
                <input type="number" id="quantity" name="quantity" min="1" value="{{ old('quantity') }}" required
                    placeholder="Quantity"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div class="col-span-12 md:col-span-6">
                <label for="shade_number" class="block text-sm font-medium text-gray-700 mb-1">Shade Number</label>
                <input type="text" id="shade_number" name="shade_number" value="{{ old('shade_number') }}"
                    placeholder="Shade Number"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div class="col-span-12 md:col-span-6">
                <label for="color" class="block text-sm font-medium text-gray-700 mb-1">Color</label>
                <input type="text" id="color" name="color" value="{{ old('color') }}" placeholder="Color"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div class="col-span-12 md:col-span-6">
                <label for="delivery_time" class="block text-sm font-medium text-gray-700 mb-1">
                    Delivery Time <span class="text-red-500">*</span>
                </label>
                <input type="date" id="delivery_time" name="delivery_time" value="{{ old('delivery_time') }}" required
                    placeholder="Delivery Time"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <!-- Form Actions -->
            <div class="col-span-12 flex justify-between pt-6 border-t">
                <button type="submit" class="btn btn-primary shadow-md mr-2 btn-hover">
                    <i class="fas fa-save mr-2"></i> Save Order
                </button>
            </div>
        </form>
    </div>
@endsection
@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const searchInput = document.getElementById("productSearch");
        const resultsBox = document.getElementById("productResults");
        const hiddenField = document.getElementById("product_id");

        let debounceTimer;

        searchInput.addEventListener("keyup", function () {
            clearTimeout(debounceTimer);
            const query = this.value.trim();

            if (query.length < 2) {
                resultsBox.innerHTML = "";
                resultsBox.classList.add("hidden");
                return;
            }

            debounceTimer = setTimeout(() => {
                fetch(`/products/search?q=${encodeURIComponent(query)}`)
                    .then(res => res.json())
                    .then(data => {
                        resultsBox.innerHTML = "";
                        if (data.results && data.results.length > 0) {
                            data.results.forEach(item => {
                                const li = document.createElement("li");
                                li.textContent = item.text;
                                li.classList.add("px-4", "py-2", "cursor-pointer", "hover:bg-blue-100");
                                li.dataset.id = item.id;
                                resultsBox.appendChild(li);
                            });
                            resultsBox.classList.remove("hidden");
                        } else {
                            resultsBox.classList.add("hidden");
                        }
                    });
            }, 300); // debounce 300ms
        });

        // Handle selection
        resultsBox.addEventListener("click", function (e) {
            if (e.target && e.target.nodeName === "LI") {
                const productName = e.target.textContent;
                const productId = e.target.dataset.id;

                searchInput.value = productName;
                hiddenField.value = productId;

                resultsBox.innerHTML = "";
                resultsBox.classList.add("hidden");
            }
        });

        // Hide dropdown if clicked outside
        document.addEventListener("click", function (e) {
            if (!resultsBox.contains(e.target) && e.target !== searchInput) {
                resultsBox.classList.add("hidden");
            }
        });
    });
</script>
@endpush