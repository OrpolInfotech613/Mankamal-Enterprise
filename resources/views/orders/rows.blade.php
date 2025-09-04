@php
    $currentIndex = ($orders->currentPage() - 1) * $orders->perPage() + 1;
@endphp
@foreach ($orders as $order)
    <tr>
        <td>{{ $order->id }}</td>
        <td>{{ $order->dealer->name ?? 'N/A' }}</td>
        <td>{{ $order->customer_name }}</td>
        <td>{{ $order->product->product_name ?? 'N/A' }}</td>
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
        <td>{{ optional($order->delivery_time)->format('Y-m-d') }}</td>
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
                <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-primary "><i class="fas fa-edit text-white"></i></a>
                <form action="{{ route('orders.destroy', $order->id) }}" method="POST"
                    onsubmit="return confirm('Are you sure you want to delete this order?');"
                    style="display: inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger "><i
                data-lucide="trash" class="w-4 h-4"></i></button>
                </form>
            </div>
        </td>
    </tr>
@endforeach
