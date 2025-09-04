@php
    $currentIndex = ($types->currentPage() - 1) * $types->perPage() + 1;
@endphp
@foreach ($types as $key => $type)
    <tr>
        <td>{{ $currentIndex++ }}</td>
        <td>{{ $type->name }}</td>
        <td>
            <div class="flex gap-2 justify-content-left">
                <!-- Edit -->
                <a href="{{ route('types.edit', $type->id) }}" class="btn btn-primary mr-1 mb-2"> <i class="fas fa-edit text-white"></i></a>

                <!-- Delete -->
                <form action="{{ route('types.destroy', $type->id) }}" method="POST"
                    onsubmit="return confirm('Are you sure you want to delete this type?');"
                    style="display: inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger mr-1 mb-2"><i class="fas fa-trash-alt"></i></button>
                </form>
            </div>
        </td>
    </tr>
@endforeach
