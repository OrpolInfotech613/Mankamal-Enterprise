@extends('app')
@push('styles')
    <style>
        .table td {
            border-bottom-width: 1px;
            padding: 0.25rem 0.5rem;
        }   
    </style>
@endpush
@section('content')

    <!-- BEGIN: Content -->
    <div class="content">
        <h2 class="intro-y text-lg font-medium mt-10 heading">
            Orders
        </h2>
        {{-- Add this at the top of your blade file, before the content --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong> {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        <div class="grid grid-cols-12 gap-6 mt-5 grid-updated">
            <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
                <a href="{{ route('orders.create') }}" class="btn btn-primary shadow-md mr-2 btn-hover">Add New Order</a>
            </div>

            <!-- BEGIN: Orders Layout -->
            <table id="DataTable" class="display table table-bordered intro-y col-span-12">
                <thead>
                    <tr class="bg-primary font-bold text-white">
                        <th>ID</th>
                        <th>Dealer Name</th>
                        <th>Customer Name</th>
                        <th>Product Name</th>
                        <th>Production Step</th>
                        <th>Working</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Shade Number</th>
                        <th>Color</th>
                        <th>Delivery Time</th>
                        <th>Status</th>
                        <th style="text-align: left;">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @forelse ($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->dealer->name ?? 'N/A' }}</td>
                            <td>{{ $order->customer_name }}</td>
                            <td>{{ $order->product_name }}</td>
                            <td>
                                @if(is_array($order->production_step))
                                    {{ implode(', ', collect($order->production_step)->map(fn($id) => $departments[$id] ?? '')->toArray()) }}
                                @else
                                    {{ $order->production_step }}
                                @endif
                            </td>
                            <td>
                                {{-- ✅ Display Order Steps in Progress --}}
                                @if($order->Orderstep->count())
                                    <ul class="list-disc pl-4">
                                        @foreach($order->Orderstep as $step)
                                            <li>
                                                {{ $departments[$step->d_id] ?? 'Unknown Department' }}
                                                @if($step->note)
                                                    <br><small class="text-muted">Note: {{ $step->note }}</small>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <span class="text-muted">No step in progress</span>
                                @endif
                            </td>
                            <td>{{ number_format($order->price, 2) }}</td>
                            <td>{{ $order->quantity }}</td>
                            <td>{{ $order->shade_number }}</td>
                            <td>{{ $order->color }}</td>
                            <td>{{ $order->delivery_time }}</td>
                            <td>
                                <select class="form-select form-select-sm status-select" data-order-id="{{ $order->id }}" onchange="updateOrderStatus(this)">
                                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="progress" {{ $order->status == 'progress' ? 'selected' : '' }}>Progress</option>
                                    <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="rework" {{ $order->status == 'rework' ? 'selected' : '' }}>Rework</option>
                                </select>
                            </td>
                            <td>
                                <div class="flex items-start mt-4 gap-2 justify-content-left">
                                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-success "><i
                                    data-lucide="view" class="w-4 h-4"></i></a>
                                    <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-primary "><i
                                    data-lucide="edit" class="w-4 h-4"></i></a>
                                    <form action="{{ route('orders.destroy', $order->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this order?');"
                                        style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger "><i
                                    data-lucide="trash" class="w-4 h-4"></i></a></button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="text-center">No orders found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <!-- END: Orders Layout -->

            <div class="col-span-12 mt-4">
                {{ $orders->links() }} {{-- Laravel pagination --}}
            </div>
        </div>
    </div>
@endsection
<script>
function updateOrderStatus(selectElement) {
    const orderId = selectElement.getAttribute('data-order-id');
    const newStatus = selectElement.value;
    const originalStatus = selectElement.getAttribute('data-original-status');
    
    // Show loading state
    selectElement.disabled = true;
    const originalHtml = selectElement.innerHTML;
    selectElement.innerHTML = '<option>Updating...</option>';
    const url = `/orders/update-status/${orderId}`;

    // Send AJAX request to update status
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            status: newStatus
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            selectElement.setAttribute('data-original-status', newStatus);
            toastr.success('Status updated successfully');
        } else {
            selectElement.value = originalStatus;
            toastr.error(data.message || 'Failed to update status');
        }
    })
    .catch(error => {
        // Revert to original value on error
        selectElement.value = originalStatus;
        
        // Show error message
        toastr.error('Failed to update status: ' + error.message);
    })
    .finally(() => {
        // Restore select element
        selectElement.disabled = false;
        selectElement.innerHTML = originalHtml;
        selectElement.value = newStatus; // Maintain the selected value if update was successful
    });
}
</script>