@extends('app')
@section('content')

    <div class="content">
        <h2 class="intro-y text-lg font-medium mt-10 heading">
            Add Product
        </h2>
        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="form-updated">
            @csrf
            <div class="grid grid-cols-12 gap-2 grid-updated">

                <!-- Product Name -->
                <div class="col-span-6 mt-3">
                    <label for="product_name" class="form-label">Product Name <span style="color: red">*</span></label>
                    <input type="text" name="product_name" id="product_name" class="form-control field-new" required>
                </div>

                <!-- Type Dropdown -->
                <div class="col-span-6 mt-3">
                    <label for="type_id" class="form-label">Type <span style="color: red">*</span></label>
                    <select name="type_id" id="type_id" class="form-control field-new" required>
                        <option value="">-- Select Type --</option>
                        @foreach($types as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Image -->
                <div class="input-form col-span-6 mt-3">
                    <label for="fileInput" class="form-label w-full flex flex-col sm:flex-row">
                        Product Image
                    </label>
                    <div class="input-form mt-3"
                        style="position: relative; border: 2px dashed #ccc; border-radius: 8px; padding: 50px 40px; text-align: center; background-color: #f9f9f9; cursor: pointer;">
                        
                        <!-- File Input -->
                        <input name="image" type="file" id="fileInput" accept="image/*"
                            style="position: absolute; width: 100%; height: 100%; top: 0; left: 0; opacity: 0; cursor: pointer; z-index: 1;"
                            onchange="previewImage(this)" />

                        <!-- Default Message -->
                        <div id="uploadMessage" style="color: #666; font-size: 16px; pointer-events: none;">
                            Drop product image file here or click to upload.
                        </div>

                        <!-- Preview Section -->
                        <div id="imagePreview"
                            style="display: none; max-width: 300px; margin: 0 auto; position: relative;">
                            
                            <!-- Cancel Button -->
                            <button type="button" onclick="removeImage()"
                                style="position: absolute; top: 0; right: 0; background: red; color: white; border: none; border-radius: 50%; width: 24px; height: 24px; line-height: 24px; text-align: center; cursor: pointer; font-weight: bold; z-index: 5;">
                                ×
                            </button>

                            <!-- Preview Image -->
                            <img id="previewImg"
                                style="width: 100%; height: auto; border-radius: 8px; margin-top: 10px;" />

                            <!-- File Name -->
                            <div style="margin-top: 10px; font-size: 14px; color: #666;">
                                <span id="fileName"></span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <a onclick="goBack()" class="btn btn-outline-primary shadow-md mr-2 mt-5">Back</a>
            <button type="submit" class="btn btn-primary mt-5 btn-hover">Save</button>
        </form>
    </div>
@endsection
@push('scripts')
<script>
    function previewImage(input) {
        const file = input.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('previewImg').src = e.target.result;
                document.getElementById('fileName').textContent = file.name;
                document.getElementById('imagePreview').style.display = 'block';
                document.getElementById('uploadMessage').style.display = 'none';
            };
            reader.readAsDataURL(file);
        }
    }

    function removeImage() {
        document.getElementById('fileInput').value = '';
        document.getElementById('imagePreview').style.display = 'none';
        document.getElementById('uploadMessage').style.display = 'block';
    }

    document.addEventListener('DOMContentLoaded', function() {
      function setupEnterNavigation() {
            let currentFieldIndex = 0;

            const formFields = [
                { selector: '#product_name', type: 'select' },
                { selector: '#type_id', type: 'input' },
                { selector: '#fileInput', type: 'file' },
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
