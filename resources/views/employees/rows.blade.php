@php
    $currentIndex = ($employees->currentPage() - 1) * $employees->perPage() + 1;
@endphp
@foreach ($employees as $employee)
    <tr>
        <td>{{ $currentIndex++ }}</td>
        <td>{{ $employee->name }}</td>
        <td>{{ $employee->email }}</td>
        <td>{{ $employee->phone_no ?? '-' }}</td>
        <td>{{ $employee->salary ?? '-' }}</td>
        <td>{{ optional($employee->doj)->format('Y-m-d') ?? '-' }}</td>
        <td>{{ optional($employee->dob)->format('Y-m-d') ?? '-' }}</td>
        <td>{{ ucfirst($employee->status) }}</td>
        <td>
            <div class="flex gap-2 justify-content-left">
                <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-primary mr-1 mb-2"> <i data-lucide="edit" class="w-4 h-4"></i></a>
                <form action="{{ route('employees.destroy', $employee->id) }}" method="POST"
                    onsubmit="return confirm('Are you sure you want to delete this employee?');"
                    style="display: inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger mr-1 mb-2"><i data-lucide="trash" class="w-4 h-4"></i></button>
                </form>
            </div>
        </td>
    </tr>
@endforeach
