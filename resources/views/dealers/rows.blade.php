@php
    $currentIndex = ($dealers->currentPage() - 1) * $dealers->perPage() + 1;
@endphp
@foreach ($dealers as $key => $dealer)
    <tr>
        <td>{{ $currentIndex++ }}</td>
        <td>{{ $dealer->name }}</td>
        <td>{{ $dealer->email }}</td>
        <td>{{ $dealer->phone_no }}</td>
        <td>{{ $dealer->address }}</td>
        <td>{{ $dealer->gst_number ?? '-' }}</td>
        <td>{{ $dealer->notes ?? '-' }}</td>
        <td>
            <div class="flex gap-2 justify-content-left">
                <!-- Delete -->
                <form action="{{ route('dealers.destroy', $dealer->id) }}" method="POST"
                    onsubmit="return confirm('Are you sure you want to delete this dealer?');"
                    style="display: inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger mr-1 mb-2"><i class="fas fa-trash-alt"></i></button>
                </form>

                <!-- Edit -->
                <a href="{{ route('dealers.edit', $dealer->id) }}" class="btn btn-primary mr-1 mb-2"><i class="fas fa-edit text-white"></i></a>

                <!-- View (optional) -->
                <a href="{{ route('dealers.show', $dealer->id) }}" class="btn btn-success mr-1 mb-2"><i class="fas fa-eye text-white"></i></a>
            </div>
        </td>
    </tr>
@endforeach
