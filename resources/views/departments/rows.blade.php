@php
    $currentIndex = ($departments->currentPage() - 1) * $departments->perPage() + 1;
@endphp
@foreach ($departments as $department)
    <tr>
        <td>{{ $currentIndex++ }}</td>
        <td>{{ $department->name }}</td>
        <td>
            <div class="flex gap-2 justify-content-left">
                <form action="{{ route('departments.destroy', $department->id) }}" method="POST"
                    onsubmit="return confirm('Are you sure you want to delete this department?');"
                    style="display: inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger mr-1 mb-2"><i class="fas fa-trash-alt"></i></button>
                </form>

                <a href="{{ route('departments.edit', $department->id) }}" class="btn btn-primary mr-1 mb-2"> <i class="fas fa-edit text-white"></i>
                </a>
            </div>
        </td>
    </tr>
@endforeach
