@extends('app')
@section('content')

<div class="content">
    <h2 class="intro-y text-lg font-medium mt-10 heading">Add New Processing Step</h2>

    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12">
            <form action="{{ route('processing_steps.store') }}" method="POST">
                @csrf
                <div class="form-group mb-3">
                    <label for="department_id">Select Department</label>
                    <select name="department_id" id="department_id" class="form-control" required>
                        <option value="">-- Select Department --</option>
                        @foreach ($departments as $dept)
                            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label for="step_order">Step Order</label>
                    <input type="number" name="step_order" id="step_order" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary">Save Step</button>
                <a href="{{ route('processing_steps.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>

@endsection
