@php
    $currentIndex = ($products->currentPage() - 1) * $products->perPage() + 1;
@endphp
@foreach ($products as $key => $product)
    <tr>
        <td>{{ $currentIndex++ }}</td>
        <td>{{ $product->product_name }}</td>
        <td>{{ $product->type->name ?? '-' }}</td>
        <td>
            @if($product->image)
                <img src="{{ asset($product->image) }}" alt="Image" width="70" height="70" class="rounded">
            @else
                <span class="text-gray-500">No Image</span>
            @endif
        </td>
        <td>
            <div class="flex gap-2">
                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary mr-1 mb-2"><i class="fas fa-edit text-white"></i></a>
                <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                    onsubmit="return confirm('Are you sure you want to delete this product?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger mr-1 mb-2"><i class="fas fa-trash-alt"></i></button>
                </form>
            </div>
        </td>
    </tr>
@endforeach
