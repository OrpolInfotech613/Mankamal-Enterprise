@extends('app')

@section('content')
    <div class="content">
        <h2 class="intro-y text-lg font-medium mt-10 heading">
            Edit Department
        </h2>

        <form action="{{ route('departments.update', $department->id) }}" method="POST" class="form-updated validate-form">
            @csrf
            @method('PUT')

            <!-- Name -->
            <div class="grid grid-cols-12 gap-2 grid-updated">
                <div class="col-span-6 mt-3">
                    <label for="name" class="form-label w-full flex flex-col sm:flex-row">
                        Name <p style="color: red;margin-left: 3px;">*</p>
                    </label>
                    <input id="name" type="text" name="name" class="form-control field-new"
                        value="{{ old('name', $department->name) }}" placeholder="Enter Department name" required
                        maxlength="255">
                </div>

            </div>

            <a onclick="goBack()" class="btn btn-outline-primary shadow-md mr-2 mt-5">Back</a>
            <button type="submit" class="btn btn-primary mt-5 btn-hover">Update</button>
        </form>
    </div>
@endsection
