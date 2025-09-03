@extends('app')
@section('content')

    <!-- BEGIN: Content -->
    <div class="content">
        <h2 class="intro-y text-lg font-medium mt-10 heading">
            Employee
        </h2>
        <div class="grid grid-cols-12 gap-6 mt-5 grid-updated">
            <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
                <a href="{{ Route('employees.create') }}" class="btn btn-primary shadow-md mr-2 btn-hover">Add New Employee</a>
            </div>
        </div>

        <table class="table table-bordered table-striped w-full">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone No</th>
                    <th>Salary</th>
                    <th>DOJ</th>
                    <th>DOB</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employees as $key => $employee)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $employee->name }}</td>
                        <td>{{ $employee->email }}</td>
                        <td>{{ $employee->phone_no }}</td>
                        <td>{{ $employee->salary }}</td>
                        <td>{{ $employee->doj?->format('d-m-Y') }}</td>
                        <td>{{ $employee->dob?->format('d-m-Y') }}</td>
                        <td>
                            <span class="badge {{ $employee->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                                {{ ucfirst($employee->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('employees.show', $employee->id) }}" class="btn btn-success btn-sm"><i data-lucide="view" class="w-4 h-4"></i></a>
                            <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-warning btn-sm"><i data-lucide="edit" class="w-4 h-4"></i></a>
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
                @empty
                    <tr>
                        <td colspan="9" class="text-center">No Employees Found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection