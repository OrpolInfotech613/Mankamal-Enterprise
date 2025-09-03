@extends('app')

@section('content')
    <div class="content">
        <h2 class="intro-y text-lg font-medium mt-10 heading">
            Edit Employee
        </h2>
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Validation failed!</strong>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('employees.update', $employee->id) }}" method="POST" class="form-updated">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-12 gap-2 grid-updated">
                <!-- Name -->
                <div class="col-span-6 mt-3">
                    <label for="name" class="form-label">Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" class="form-control field-new"
                        placeholder="Enter employee name" required value="{{ old('name', $employee->name) }}">
                </div>

                <!-- Email -->
                <div class="col-span-6 mt-3">
                    <label for="email" class="form-label">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" id="email" class="form-control field-new"
                        placeholder="Enter employee email" required value="{{ old('email', $employee->email) }}">
                </div>

                <!-- Phone -->
                <div class="col-span-6 mt-3">
                    <label for="phone_no" class="form-label">Phone No</label>
                    <input type="text" name="phone_no" id="phone_no" class="form-control field-new"
                        placeholder="Enter phone number" value="{{ old('phone_no', $employee->phone_no) }}">
                </div>

                <!-- Salary -->
                <div class="col-span-6 mt-3">
                    <label for="salary" class="form-label">Salary</label>
                    <input type="number" step="0.01" name="salary" id="salary" class="form-control field-new"
                        placeholder="Enter salary amount" value="{{ old('salary', $employee->salary) }}">
                </div>

                <!-- Date of Joining -->
                <div class="col-span-6 mt-3">
                    <label for="doj" class="form-label">Date of Joining</label>
                    <input type="date" name="doj" id="doj" class="form-control field-new"
                        placeholder="Select joining date" value="{{ old('doj', $employee->doj?->format('Y-m-d')) }}">
                </div>

                <!-- Date of Birth -->
                <div class="col-span-6 mt-3">
                    <label for="dob" class="form-label">Date of Birth</label>
                    <input type="date" name="dob" id="dob" class="form-control field-new"
                        placeholder="Select date of birth" value="{{ old('dob', $employee->dob?->format('Y-m-d')) }}">
                </div>

                <!-- IFSC Code -->
                <div class="col-span-6 mt-3">
                    <label for="ifsc_code" class="form-label">IFSC Code</label>
                    <input type="text" name="ifsc_code" id="ifsc_code" class="form-control field-new"
                        placeholder="Enter IFSC code" value="{{ old('ifsc_code', $employee->ifsc_code) }}">
                </div>

                <!-- Account Holder Name -->
                <div class="col-span-6 mt-3">
                    <label for="account_holder_name" class="form-label">Account Holder Name</label>
                    <input type="text" name="account_holder_name" id="account_holder_name" class="form-control field-new"
                        placeholder="Enter account holder name"
                        value="{{ old('account_holder_name', $employee->account_holder_name) }}">
                </div>

                <!-- Account No -->
                <div class="col-span-6 mt-3">
                    <label for="account_no" class="form-label">Account Number</label>
                    <input type="text" name="account_no" id="account_no" class="form-control field-new"
                        placeholder="Enter account number" value="{{ old('account_no', $employee->account_no) }}">
                </div>

                <!-- Documents -->
                <div class="col-span-6 mt-3">
                    <label for="documents" class="form-label">Documents (JSON format)</label>
                    <textarea name="documents" id="documents" class="form-control field-new"
                        placeholder='Enter documents in JSON format, e.g. {"aadhar":"1234"}'>{{ old('documents', json_encode($employee->documents)) }}</textarea>
                </div>

                <!-- Status -->
                <div class="col-span-6 mt-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-control field-new">
                        <option value="active" {{ old('status', $employee->status) == 'active' ? 'selected' : '' }}>Active
                        </option>
                        <option value="inactive" {{ old('status', $employee->status) == 'inactive' ? 'selected' : '' }}>
                            Inactive</option>
                    </select>
                </div>
            </div>

            <!-- Buttons -->
            <a {{ route('employees.index') }} class="btn btn-outline-primary shadow-md mr-2 mt-5">Back</a>
            <button type="submit" class="btn btn-primary mt-5 btn-hover">Update</button>
        </form>
    </div>
@endsection
