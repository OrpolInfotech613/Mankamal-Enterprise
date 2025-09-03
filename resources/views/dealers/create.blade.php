@extends('app')
@section('content')
    <div class="content">
        <h2 class="intro-y text-lg font-medium mt-10 heading">
            Dealer
        </h2>
        <form action="{{ route('dealers.store') }}" method="POST" class="form-updated">
            @csrf
            <div class="grid grid-cols-12 gap-2 grid-updated">
                
                <!-- Name -->
                <div class="col-span-6 mt-3">
                    <label for="name" class="form-label">Name <span style="color: red;margin-left: 3px;">*</span></label>
                    <input type="text" name="name" placeholder="Enter Dealer Name.." id="name" class="form-control field-new" required>
                </div>

                <!-- Email -->
                <div class="col-span-6 mt-3">
                    <label for="email" class="form-label">Email <span style="color: red;margin-left: 3px;">*</span></label>
                    <input type="email" name="email" placeholder="Enter Email Name.." id="email" class="form-control field-new" required>
                </div>

                <!-- Phone -->
                <div class="col-span-6 mt-3">
                    <label for="phone_no" class="form-label">Phone No <span style="color: red;margin-left: 3px;">*</span></label>
                    <input type="text" name="phone_no" placeholder="Enter Phone No.." id="phone_no" class="form-control field-new" required>
                </div>

                <!-- Address -->
                <div class="col-span-6 mt-3">
                    <label for="address" class="form-label">Address <span style="color: red;margin-left: 3px;">*</span></label>
                    <input type="text" name="address" placeholder="Enter Address.." id="address" class="form-control field-new" required>
                </div>

                <!-- GST Number -->
                <div class="col-span-6 mt-3">
                    <label for="gst_number" class="form-label">GST Number</label>
                    <input type="text" name="gst_number" placeholder="Enter GST Number.." id="gst_number" class="form-control field-new">
                </div>

                <!-- Notes -->
                <div class="col-span-6 mt-3">
                    <label for="notes" class="form-label">Notes</label>
                    <textarea name="notes" placeholder="Enter Notes.." id="notes" class="form-control field-new"></textarea>
                </div>
            </div>

            <a href="{{ route('dealers.index') }}" class="btn btn-outline-primary shadow-md mr-2">Back</a>
            <button type="submit" class="btn btn-primary mt-5 btn-hover">Save</button>
        </form>

        <!-- Success Notification -->
        <div id="success-notification-content" class="toastify-content hidden flex">
            <i class="text-success" data-lucide="check-circle"></i>
            <div class="ml-4 mr-4">
                <div class="font-medium">Dealer created successfully!</div>
                <div class="text-slate-500 mt-1">You can manage dealers in the list.</div>
            </div>
        </div>

        <!-- Failed Notification -->
        <div id="failed-notification-content" class="toastify-content hidden flex">
            <i class="text-danger" data-lucide="x-circle"></i>
            <div class="ml-4 mr-4">
                <div class="font-medium">Creation failed!</div>
                <div class="text-slate-500 mt-1">Please check the filled form.</div>
            </div>
        </div>
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