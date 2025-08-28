@extends('app')
@section('content')

    <!-- BEGIN: Content -->
    <div class="content">
        <h2 class="intro-y text-lg font-medium mt-10 heading">
            Processing Steps
        </h2>
        <div class="grid grid-cols-12 gap-6 mt-5 grid-updated">
            <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
                <a href="{{ route('processing_steps.create') }}" class="btn btn-primary shadow-md mr-2 btn-hover">
                    Add New Step
                </a>
            </div>

            <!-- BEGIN: Steps Layout -->
            <table id="DataTable" class="display table table-bordered intro-y col-span-12">
                <thead>
                    <tr class="bg-primary font-bold text-white">
                        <th>#</th>
                        <th>Department</th>
                        <th>Step Order</th>
                        <th style="text-align: left;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($steps as $step)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $step->department->name }}</td>
                            <td>{{ $step->step_order }}</td>
                            <td>
                                <div class="flex gap-2 justify-content-left">
                                    <form action="{{ route('processing_steps.destroy', $step->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this step?');"
                                        style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger mr-1 mb-2">Delete</button>
                                    </form>

                                    <a href="{{ route('processing_steps.edit', $step->id) }}" class="btn btn-primary mr-1 mb-2">
                                        Edit
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <!-- END: Steps Layout -->
        </div>
    </div>
@endsection
