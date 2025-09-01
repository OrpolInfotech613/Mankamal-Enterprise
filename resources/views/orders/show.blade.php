@extends('app')
@section('content')

<!-- BEGIN: Content -->
<div class="content">
    <h2 class="intro-y text-lg font-medium mt-10 heading">
        Order Details
    </h2>

    <div class="grid grid-cols-12 gap-4 mt-5">
        <!-- Dealer Name -->
        <div class="col-span-12 md:col-span-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Dealer Name</label>
            <p class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100">{{ $order->dealer_name }}</p>
        </div>

        <!-- Customer Name -->
        <div class="col-span-12 md:col-span-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Customer Name</label>
            <p class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100">{{ $order->customer_name }}</p>
        </div>

        <!-- Product Name -->
        <div class="col-span-12 md:col-span-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Product Name</label>
            <p class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100">{{ $order->product_name }}</p>
        </div>

        <!-- Production Step -->
        <div class="col-span-12 md:col-span-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Production Steps</label>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                @foreach ($departments as $department)
                    @if(in_array($department->id, $order->production_step ?? []))
                        <p class="px-2 py-1 border border-gray-300 rounded-lg bg-gray-100">
                            {{ in_array($department->id, $order->production_step ?? []) ? $department->name : '' }}
                        </p>
                    @endif
                @endforeach
            </div>
        </div>

        <!-- Price -->
        <div class="col-span-12 md:col-span-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Price (₹)</label>
            <p class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100">{{ number_format($order->price, 2) }}</p>
        </div>

        <!-- Quantity -->
        <div class="col-span-12 md:col-span-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
            <p class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100">{{ $order->quantity }}</p>
        </div>

        <!-- Shade Number -->
        <div class="col-span-12 md:col-span-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Shade Number</label>
            <p class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100">{{ $order->shade_number }}</p>
        </div>

        <!-- Color -->
        <div class="col-span-12 md:col-span-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Color</label>
            <div class="flex items-center space-x-2">
                <p class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100">{{ $order->color }}</p>
                <div style="width: 40px; height: 40px; background-color: {{ $order->color }}; border: 1px solid #ccc;"></div>
            </div>
        </div>

        <!-- Delivery Time -->
        <div class="col-span-12 md:col-span-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Delivery Time</label>
            <p class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100">{{ \Carbon\Carbon::parse($order->delivery_time)->format('d-m-Y') }}</p>
        </div>

        <!-- Back Button -->
        <div class="col-span-12 flex justify-start pt-6 border-t">
            <a href="{{ route('orders.index') }}"
                class="px-6 py-2 bg-gray-500 text-white btn btn-primary rounded-lg hover:bg-gray-600 transition duration-200 flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Back to Orders
            </a>
        </div>
    </div>
</div>

@endsection
