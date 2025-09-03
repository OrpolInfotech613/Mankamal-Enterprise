@extends('app')

@section('content')
    <div class="content">
        <h2 class="intro-y text-lg font-medium mt-10 heading">
            Edit User
        </h2>

        <form action="{{ route('roles.update', $role->id) }}" method="POST" class="form-updated validate-form">
            @csrf
            @method('PUT')

            <!-- Name -->
            <div class="grid grid-cols-12 gap-2 grid-updated">
                <div class="col-span-6 mt-3">
                    <label for="name" class="form-label w-full flex flex-col sm:flex-row">
                        Name <p style="color: red;margin-left: 3px;">*</p>
                    </label>
                    <input id="role_name" type="text" name="role_name" class="form-control field-new"
                        value="{{ old('role_name', $role->role_name) }}" placeholder="Enter Role name" required
                        maxlength="255">
                </div>

            </div>

            <a onclick="goBack()" class="btn btn-outline-primary shadow-md mr-2 mt-5">Back</a>
            <button type="submit" class="btn btn-primary mt-5 btn-hover">Update</button>
        </form>
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
                selector: '#role_name',
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