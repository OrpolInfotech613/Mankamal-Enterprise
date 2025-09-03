@extends('app')

@section('content')
    <div class="content">
        <h2 class="intro-y text-lg font-medium mt-10 heading">
            Edit Order
        </h2>
        <form id="orderForm" class="grid grid-cols-12 gap-4" action="{{ route('orders.update', $order->id) }}" method="post">
            @csrf
            @method('PUT')

            <!-- Dealer Name -->
            <div class="col-span-12 md:col-span-6">
                <label for="dealer_name" class="block text-sm font-medium text-gray-700 mb-1">Dealer Name <span
                        class="text-red-500">*</span></label>
                <select id="dealer_id" name="dealer_id" required 
                    class="form-control @error('dealer_id') is-invalid @enderror"
                    {{ old('dealer_id', $record->dealer_id ?? '') ? '' : 'data-placeholder="true"' }}>
                    <option value="">Select a Dealer</option>
                    @forelse($dealers ?? [] as $dealer)
                        <option value="{{ $dealer->id }}"
                            {{ (isset($order) && $order->dealer_id == $dealer->id) ? 'selected' : '' }}
                            {{ old('dealer_id', $record->dealer_id ?? '') == $dealer->id ? 'selected' : '' }}>
                            {{ $dealer->name }}
                        </option>
                    @empty
                        <option value="" disabled>No dealers available</option>
                    @endforelse
                </select>
            </div>

            <!-- Customer Name -->
            <div class="col-span-12 md:col-span-6">
                <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-1">Customer Name <span
                        class="text-red-500">*</span></label>
                <input type="text" id="customer_name" name="customer_name"
                    value="{{ old('customer_name', $order->customer_name) }}" required placeholder="Customer Name"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <!-- Product Name -->
            <div class="col-span-12 md:col-span-6">
                <label for="product_name" class="block text-sm font-medium text-gray-700 mb-1">Product Name <span
                        class="text-red-500">*</span></label>
                <input type="text" id="product_name" name="product_name"
                    value="{{ old('product_name', $order->product_name) }}" required placeholder="Product Name"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <!-- Production Step -->
            <div class="col-span-12 md:col-span-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Production Step</label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach ($departments as $department)
                        @php
                            // Find the order item for this department
                            $orderItem = $order->Orderstep->firstWhere('d_id', $department->id);
                            $orderItemId = $orderItem ? $orderItem->id : null;
                        @endphp

                        <label class="flex items-center space-x-2">
                            <!-- Single hidden input per department -->
                            <input type="hidden" name="orderitemId[{{ $department->id }}]" value="{{ $orderItemId }}">

                            <input type="checkbox" name="production_step[]" value="{{ $department->id }}"
                                class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 mr-2"
                                {{ in_array($department->id, old('production_step', $order->production_step ?? [])) ? 'checked' : '' }}>
                            <span>{{ $department->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Price -->
            <div class="col-span-12 md:col-span-6">
                <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Price (₹) <span
                        class="text-red-500">*</span></label>
                <input type="number" id="price" name="price" min="0" step="0.01"
                    value="{{ old('price', $order->price) }}" required placeholder="Price"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <!-- Quantity -->
            <div class="col-span-12 md:col-span-6">
                <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Quantity <span
                        class="text-red-500">*</span></label>
                <input type="number" id="quantity" name="quantity" min="1"
                    value="{{ old('quantity', $order->quantity) }}" required placeholder="Quantity"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <!-- Shade Number -->
            <div class="col-span-12 md:col-span-6">
                <label for="shade_number" class="block text-sm font-medium text-gray-700 mb-1">Shade Number</label>
                <input type="text" id="shade_number" name="shade_number"
                    value="{{ old('shade_number', $order->shade_number) }}" placeholder="Shade Number"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <!-- Color -->
            <div class="col-span-12 md:col-span-6">
                <label for="color" class="block text-sm font-medium text-gray-700 mb-1">Color</label>
                <div class="flex items-center">
                    <input type="text" id="color" name="color" value="{{ old('color', $order->color) }}"
                        placeholder="Color" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <!-- Delivery Time -->
            <div class="col-span-12 md:col-span-6">
                <label for="delivery_time" class="block text-sm font-medium text-gray-700 mb-1">Delivery Time <span
                        class="text-red-500">*</span></label>
                <input type="date" id="delivery_time" name="delivery_time"
                    value="{{ old('delivery_time', $order->delivery_time) }}" required placeholder="Delivery Time"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <!-- Status -->
            <div class="col-span-12 md:col-span-6">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status <span
                        class="text-red-500">*</span></label>
                <select id="status" name="status" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="pending" {{ old('status', $order->status) == 'pending' ? 'selected' : '' }}>Pending
                    </option>
                    <option value="progress" {{ old('status', $order->status) == 'progress' ? 'selected' : '' }}>Progress
                    </option>
                    <option value="completed" {{ old('status', $order->status) == 'completed' ? 'selected' : '' }}>
                        Completed</option>
                </select>
            </div>

            <!-- Form Actions -->
            <div class="col-span-12 flex justify-between pt-6 border-t">
                <button type="submit"
                    class="px-6 py-2 bg-blue-600 text-dark rounded-lg hover:bg-blue-700 transition duration-200 flex items-center">
                    <i class="fas fa-save mr-2"></i> Update Order
                </button>
                <a href="{{ route('orders.index') }}"
                    class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition duration-200 flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Orders
                </a>
            </div>
        </form>
    </div>
@endsection
