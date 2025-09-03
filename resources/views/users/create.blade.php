@extends('app')
@section('content')
    <div class="content">
        <h2 class="intro-y text-lg font-medium mt-10 heading">
            Create User
        </h2>
        <form action="{{ route('users.store') }}" method="POST" class="form-updated validate-form">
            @csrf <!-- CSRF token for security -->
            <!-- Name -->
            <div class="grid grid-cols-12 gap-2 grid-updated">
                <div class="input-form col-span-3 mt-3">
                    <label for="name" class="form-label w-full flex flex-col sm:flex-row">
                        Name<span style="color: red;margin-left: 3px;"> *</span>
                    </label>
                    <input id="name" type="text" name="name" class="form-control field-new"
                        placeholder="Enter customer name" required maxlength="255">
                </div>

                <!-- Email -->
                <div class="input-form col-span-3 mt-3">
                    <label for="email" class="form-label w-full flex flex-col sm:flex-row">
                        Email<span style="color: red;margin-left: 3px;"> *</span>
                    </label>
                    <input id="email" type="email" name="email" class="form-control field-new"
                        placeholder="Enter customer email" maxlength="255">
                </div>
                <!-- Role -->
                <div class="input-form col-span-3 mt-3">
                    <label for="role_id" class="form-label w-full flex flex-col sm:flex-row">
                        Role<p style="color: red;margin-left: 3px;"> *</p>
                    </label>
                    <select id="role_id" name="role_id" class="form-control field-new" required>
                        <option value="" selected>Choose...</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->role_name }}</option>
                        @endforeach
                    </select>
                </div>
                <!-- Password -->
                <div class="input-form col-span-3 mt-3">
                    <label for="preferred_payment_method" class="form-label w-full flex flex-col sm:flex-row">
                        Password<span style="color: red;margin-left: 3px;"> *</span>
                    </label>
                    <input id="preferred_payment_method" type="password" name="password" class="form-control field-new"
                        placeholder="Enter password">
                </div>


                <!-- Submit Button -->

            </div>
            <a onclick="goBack()" class="btn btn-outline-primary shadow-md mr-2">Back</a>
            <button type="submit" class="btn btn-primary mt-5 btn-hover">Submit</button>
        </form>
        <!-- END: Validation Form -->
        <!-- BEGIN: Success Notification Content -->
        <div id="success-notification-content" class="toastify-content hidden flex">
            <i class="text-success" data-lucide="check-circle"></i>
            <div class="ml-4 mr-4">
                <div class="font-medium">Registration success!</div>
                <div class="text-slate-500 mt-1"> Please check your e-mail for further info! </div>
            </div>
        </div>
        <!-- END: Success Notification Content -->
        <!-- BEGIN: Failed Notification Content -->
        <div id="failed-notification-content" class="toastify-content hidden flex">
            <i class="text-danger" data-lucide="x-circle"></i>
            <div class="ml-4 mr-4">
                <div class="font-medium">Registration failed!</div>
                <div class="text-slate-500 mt-1"> Please check the fileld form. </div>
            </div>
        </div>
        <!-- END: Failed Notification Content -->
    </div>
@endsection
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        setupEnterNavigation();
    });

    function setupEnterNavigation() {
        let currentFieldIndex = 0;

        // Define field sequence for category form
        const formFields = [
            {
                selector: '#name',
                type: 'input'
            },
            {
                selector: '#email',
                type: 'input'
            },
            {
                selector: '#role_id',
                type: 'select'
            },
            {
                selector: '#preferred_payment_method',
                type: 'input'
            },
        ];

        function focusField(selector) {
            const element = document.querySelector(selector);
            if (element) {
                element.focus();
                if (element.tagName === 'SELECT') {
                    setTimeout(() => {
                        if (element.size <= 1) {
                            element.click();
                        }
                    }, 100);
                }
            }
        }

        function handleFormFieldNavigation(e, fieldIndex) {
            if (e.key === 'Enter') {
                e.preventDefault();

                if (fieldIndex < formFields.length - 1) {
                    currentFieldIndex = fieldIndex + 1;
                    focusField(formFields[currentFieldIndex].selector);
                } else {
                    const submitButton = document.querySelector('button[type="submit"]');
                    if (submitButton) {
                        submitButton.focus();
                    }
                }
            }
        }

        formFields.forEach((field, index) => {
            const element = document.querySelector(field.selector);
            if (element) {
                element.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter') {
                        handleFormFieldNavigation(e, index);
                    }
                });
            }
        });

        setTimeout(() => {
            focusField(formFields[0].selector);
        }, 500);
    }
</script>
@endpush
