@extends('app')

@section('content')
<div class="content">
    <h2 class="intro-y text-lg font-medium mt-10 heading">
        Employee Details
    </h2>

    <div class="mt-5">
        <a href="{{ route('employees.index') }}" class="btn btn-outline-primary mb-5">Back</a>

        <div class="grid grid-cols-12 gap-4">
            <!-- Name -->
            <div class="col-span-6 mt-3">
                <strong>Name:</strong> {{ $employee->name }}
            </div>

            <!-- Email -->
            <div class="col-span-6 mt-3">
                <strong>Email:</strong> {{ $employee->email }}
            </div>

            <!-- Phone -->
            <div class="col-span-6 mt-3">
                <strong>Phone No:</strong> {{ $employee->phone_no }}
            </div>

            <!-- Salary -->
            <div class="col-span-6 mt-3">
                <strong>Salary:</strong> {{ $employee->salary }}
            </div>

            <!-- Date of Joining -->
            <div class="col-span-6 mt-3">
                <strong>Date of Joining:</strong> {{ $employee->doj?->format('d-m-Y') }}
            </div>

            <!-- Date of Birth -->
            <div class="col-span-6 mt-3">
                <strong>Date of Birth:</strong> {{ $employee->dob?->format('d-m-Y') }}
            </div>

            <!-- Age -->
            <div class="col-span-6 mt-3">
                <strong>Age:</strong> {{ $employee->age ?? 'N/A' }} years
            </div>

            <!-- Experience -->
            <div class="col-span-6 mt-3">
                <strong>Experience:</strong> {{ $employee->experience ?? 'N/A' }} years
            </div>

            <!-- IFSC Code -->
            <div class="col-span-6 mt-3">
                <strong>IFSC Code:</strong> {{ $employee->ifsc_code }}
            </div>

            <!-- Account Holder Name -->
            <div class="col-span-6 mt-3">
                <strong>Account Holder Name:</strong> {{ $employee->account_holder_name }}
            </div>

            <!-- Account Number -->
            <div class="col-span-6 mt-3">
                <strong>Account Number:</strong> {{ $employee->account_no }}
            </div>

            <!-- Status -->
            <div class="col-span-6 mt-3">
                <strong>Status:</strong>
                <span class="badge {{ $employee->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                    {{ ucfirst($employee->status) }}
                </span>
            </div>

            <!-- Documents -->
            <div class="col-span-12 mt-3">
                <strong>Documents:</strong>
                @if(!empty($employee->documents) && is_array($employee->documents))
                    <ul class="list-disc ml-5">
                        @foreach($employee->documents as $key => $value)
                            <li><strong>{{ ucfirst($key) }}:</strong> {{ $value }}</li>
                        @endforeach
                    </ul>
                @else
                    N/A
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
