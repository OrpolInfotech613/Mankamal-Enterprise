@php
    $currentIndex = ($employees->currentPage() - 1) * $employees->perPage() + 1;
@endphp
@foreach($employees as $key => $employee)
    <tr>
        <td>{{ $currentIndex++ }}</td>
        <td>{{ $employee->name }}</td>
        <td>{{ $employee->email }}</td>
        <td>{{ $employee->phone_no }}</td>
        <td>{{ $employee->salary }}</td>
        <td>{{ $employee->doj?->format('d-m-Y') }}</td>
        <td>{{ $employee->dob?->format('d-m-Y') }}</td>
        <td>
            {{ ucfirst($employee->status) }}
        </td>
        <td>
            <a href="{{ route('employees.show', $employee->id) }}" class="btn btn-success btn-sm"><i data-lucide="view"
                    class="w-4 h-4"></i></a>
            <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-warning btn-sm"><i data-lucide="edit"
                    class="w-4 h-4"></i></a>
            <form action="{{ route('employees.destroy', $employee->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm"
                    onclick="return confirm('Are you sure you want to delete this employee?')">
                    <i data-lucide="trash" class="w-4 h-4"></i>
                </button>
            </form>
        </td>
    </tr>
@endforeach
