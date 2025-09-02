@extends('app')
@section('content')
    <!-- BEGIN: Content -->
    <div class="content">
        <h2 class="intro-y text-lg font-medium mt-10 heading">
            Order Details
        </h2>
        <div class="intro-y grid grid-cols-11 gap-5 mt-5">
            <div class="col-span-12 lg:col-span-4 2xl:col-span-3">
                <div class="scrollable">
                    @foreach ($departments as $department)
                        @if (in_array($department->id, $order->production_step ?? []))
                            <div class="box p-5 rounded-md mt-5">
                                <div
                                    class="flex items-center border-b border-slate-200/60 dark:border-darkmode-400 pb-5 mb-5">
                                    <div class="font-medium text-base truncate">{{ $department->name }}</div>
                                </div>
                                @php
                                    $step = $order->Orderstep->firstWhere('d_id', $department->id);
                                @endphp
                                <p style="text-align: center">
                                    {{ $step->note ?? '—' }}
                                </p>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
            <div class="col-span-12 lg:col-span-7 mb-5 2xl:col-span-8">
                <div class="grid grid-cols-12 gap-4 mt-5">
                    <!-- Dealer Name -->
                    <div class="flex items-center mt-3 col-span-6">
                        <i data-lucide="user" class="w-4 h-4 text-slate-500 mr-2"></i>
                        Dealer : {{ $order->dealer_name }}
                    </div>

                    <!-- Customer Name -->
                    <div class="flex items-center mt-3 col-span-6">
                        <i data-lucide="user-check" class="w-4 h-4 text-slate-500 mr-2"></i>
                        Customer : {{ $order->customer_name }}
                    </div>

                    <!-- Product Name -->
                    <div class="flex items-center mt-3 col-span-6">
                        <i data-lucide="package" class="w-4 h-4 text-slate-500 mr-2"></i>
                        Product : {{ $order->product_name }}
                    </div>

                    <!-- Production Steps -->
                    <div class="flex items-center mt-3 col-span-6">
                        <i data-lucide="workflow" class="w-4 h-4 text-slate-500 mr-2"></i>
                        Steps :
                        <div class="flex flex-wrap ml-2 gap-1">
                            @foreach ($departments as $department)
                                @if (in_array($department->id, $order->production_step ?? []))
                                    <span class="px-2 py-0.5 border border-gray-300 rounded bg-gray-100 text-xs">
                                        {{ $department->name }}
                                    </span>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <!-- Price -->
                    <div class="flex items-center mt-3 col-span-6">
                        <i data-lucide="indian-rupee" class="w-4 h-4 text-slate-500 mr-2"></i>
                        Price : ₹{{ number_format($order->price, 2) }}
                    </div>

                    <!-- Quantity -->
                    <div class="flex items-center mt-3 col-span-6">
                        <i data-lucide="hash" class="w-4 h-4 text-slate-500 mr-2"></i>
                        Quantity : {{ $order->quantity }}
                    </div>

                    <!-- Shade Number -->
                    <div class="flex items-center mt-3 col-span-6">
                        <i data-lucide="droplet" class="w-4 h-4 text-slate-500 mr-2"></i>
                        Shade No : {{ $order->shade_number }}
                    </div>

                    <!-- Color -->
                    <div class="flex items-center mt-3 col-span-6">
                        <i data-lucide="palette" class="w-4 h-4 text-slate-500 mr-2"></i>
                        Color : {{ $order->color }}
                        <div class="w-6 h-6 rounded border ml-2" style="background-color: {{ $order->color }}"></div>
                    </div>

                    <!-- Delivery Time -->
                    <div class="flex items-center mt-3 col-span-6">
                        <i data-lucide="calendar" class="w-4 h-4 text-slate-500 mr-2"></i>
                        Delivery : {{ \Carbon\Carbon::parse($order->delivery_time)->format('d-m-Y') }}
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
        </div>
    </div>
@endsection
