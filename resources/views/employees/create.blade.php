@extends('app')

@section('content')
    <div class="content">
        <h2 class="intro-y text-lg font-medium mt-10 heading">
            Create Employee
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


        <form action="{{ route('employees.store') }}" method="POST" class="form-updated">
            @csrf

            <div class="grid grid-cols-12 gap-2 grid-updated">
                <!-- Name -->
                <div class="col-span-6 mt-3">
                    <label for="name" class="form-label">Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" class="form-control field-new" 
                           placeholder="Enter employee name" required value="{{ old('name') }}">
                </div>

                <!-- Email -->
                <div class="col-span-6 mt-3">
                    <label for="email" class="form-label">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" id="email" class="form-control field-new" 
                           placeholder="Enter employee email" required value="{{ old('email') }}">
                </div>

                <!-- Phone -->
                <div class="col-span-6 mt-3">
                    <label for="phone_no" class="form-label">Phone No</label>
                    <input type="text" name="phone_no" id="phone_no" class="form-control field-new" 
                           placeholder="Enter phone number" value="{{ old('phone_no') }}">
                </div>

                <!-- Salary -->
                <div class="col-span-6 mt-3">
                    <label for="salary" class="form-label">Salary</label>
                    <input type="number" step="0.01" name="salary" id="salary" class="form-control field-new" 
                           placeholder="Enter salary amount" value="{{ old('salary') }}">
                </div>

                <!-- Date of Joining -->
                <div class="col-span-6 mt-3">
                    <label for="doj" class="form-label">Date of Joining</label>
                    <input type="date" name="doj" id="doj" class="form-control field-new" 
                           placeholder="Select joining date" value="{{ old('doj') }}">
                </div>

                <!-- Date of Birth -->
                <div class="col-span-6 mt-3">
                    <label for="dob" class="form-label">Date of Birth</label>
                    <input type="date" name="dob" id="dob" class="form-control field-new" 
                           placeholder="Select date of birth" value="{{ old('dob') }}">
                </div>

                <!-- IFSC Code -->
                <div class="col-span-6 mt-3">
                    <label for="ifsc_code" class="form-label">IFSC Code</label>
                    <input type="text" name="ifsc_code" id="ifsc_code" class="form-control field-new" 
                           placeholder="Enter IFSC code" value="{{ old('ifsc_code') }}">
                </div>

                <!-- Account Holder Name -->
                <div class="col-span-6 mt-3">
                    <label for="account_holder_name" class="form-label">Account Holder Name</label>
                    <input type="text" name="account_holder_name" id="account_holder_name" class="form-control field-new" 
                           placeholder="Enter account holder name" value="{{ old('account_holder_name') }}">
                </div>

                <!-- Account No -->
                <div class="col-span-6 mt-3">
                    <label for="account_no" class="form-label">Account Number</label>
                    <input type="text" name="account_no" id="account_no" class="form-control field-new" 
                           placeholder="Enter account number" value="{{ old('account_no') }}">
                </div>

                <!-- Documents -->
                <div class="col-span-6 mt-3">
                    <label for="documents" class="form-label">Documents (JSON format)</label>
                    <textarea name="documents" id="documents" class="form-control field-new" 
                              placeholder='Enter documents in JSON format, e.g. {"aadhar":"1234"}'>{{ old('documents') }} {"aadhar":"1234-5678-9012","pan":"ABCDE1234F","passport":"N1234567","driving_license":"MH12AB1234"}</textarea>
                </div>

            </div>

            <!-- Buttons -->
            <a href="{{ route('employees.index') }}" class="btn btn-outline-primary shadow-md mr-2 mt-5">Back</a>
            <button type="submit" class="btn btn-primary mt-5 btn-hover">Save</button>
        </form>
    </div>
@endsection
