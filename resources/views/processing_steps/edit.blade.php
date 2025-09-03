@extends('app')
@section('content')

<div class="content">
    <h2 class="intro-y text-lg font-medium mt-10 heading">Edit Processing Step</h2>

    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12">
            <form action="{{ route('processing-steps.update', $processing_step->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group mb-3">
                    <label for="department_id">Select Department</label>
                    <select name="department_id" id="department_id" class="form-control" required>
                        @foreach ($departments as $dept)
                            <option value="{{ $dept->id }}" {{ $processing_step->department_id == $dept->id ? 'selected' : '' }}>
                                {{ $dept->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label for="step_order">Step Order</label>
                    <input type="number" name="step_order" id="step_order" class="form-control"
                           value="{{ $processing_step->step_order }}" required>
                </div>

                <button type="submit" class="btn btn-primary">Update Step</button>
                <a href="{{ route('processing-steps.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>

@endsection
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
      function setupEnterNavigation() {
            let currentFieldIndex = 0;

            const formFields = [
                { selector: '#department_id', type: 'select' },
                { selector: '#step_order', type: 'input' },
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

        setupEnterNavigation();
    });
    </script>
@endpush
