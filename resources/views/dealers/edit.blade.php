@extends('app')

@section('content')
    <div class="content">
        <h2 class="intro-y text-lg font-medium mt-10 heading">
            Edit Dealer
        </h2>

        <form action="{{ route('dealers.update', $dealer->id) }}" method="POST" class="form-updated validate-form">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-12 gap-2 grid-updated">
                <!-- Name -->
                <div class="col-span-6 mt-3">
                    <label for="name" class="form-label w-full flex flex-col sm:flex-row">
                        Name <p style="color: red;margin-left: 3px;">*</p>
                    </label>
                    <input id="name" type="text" name="name" class="form-control field-new"
                           value="{{ old('name', $dealer->name) }}" placeholder="Enter dealer name" required maxlength="255">
                </div>

                <!-- Email -->
                <div class="col-span-6 mt-3">
                    <label for="email" class="form-label w-full flex flex-col sm:flex-row">
                        Email <p style="color: red;margin-left: 3px;">*</p>
                    </label>
                    <input id="email" type="email" name="email" class="form-control field-new"
                           value="{{ old('email', $dealer->email) }}" placeholder="Enter dealer email" required>
                </div>

                <!-- Phone No -->
                <div class="col-span-6 mt-3">
                    <label for="phone_no" class="form-label w-full flex flex-col sm:flex-row">
                        Phone No <p style="color: red;margin-left: 3px;">*</p>
                    </label>
                    <input id="phone_no" type="text" name="phone_no" class="form-control field-new"
                           value="{{ old('phone_no', $dealer->phone_no) }}" placeholder="Enter phone number" required>
                </div>

                <!-- Address -->
                <div class="col-span-6 mt-3">
                    <label for="address" class="form-label w-full flex flex-col sm:flex-row">
                        Address <p style="color: red;margin-left: 3px;">*</p>
                    </label>
                    <input id="address" type="text" name="address" class="form-control field-new"
                           value="{{ old('address', $dealer->address) }}" placeholder="Enter address" required>
                </div>

                <!-- GST Number -->
                <div class="col-span-6 mt-3">
                    <label for="gst_number" class="form-label w-full flex flex-col sm:flex-row">
                        GST Number
                    </label>
                    <input id="gst_number" type="text" name="gst_number" class="form-control field-new"
                           value="{{ old('gst_number', $dealer->gst_number) }}" placeholder="Enter GST number">
                </div>

                <!-- Notes -->
                <div class="col-span-6 mt-3">
                    <label for="notes" class="form-label w-full flex flex-col sm:flex-row">
                        Notes
                    </label>
                    <textarea id="notes" name="notes" class="form-control field-new"
                              placeholder="Enter notes">{{ old('notes', $dealer->notes) }}</textarea>
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
                selector: '#name',
                type: 'input'
            },
            {
                selector: '#email',
                type: 'input'
            },
            {
                selector: '#phone_no',
                type: 'select'
            },
            {
                selector: '#address',
                type: 'input'
            },
            {
                selector: '#gst_number',
                type: 'input'
            },
            {
                selector: '#notes',
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