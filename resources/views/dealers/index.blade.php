    <!-- BEGIN: Content -->
    @extends('app')
@section('content')
<div class="content">
    <h2 class="intro-y text-lg font-medium mt-10 heading">
        Dealer
    </h2>
    <div class="grid grid-cols-12 gap-6 mt-5 grid-updated">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
            <a href="{{ route('dealers.create') }}" class="btn btn-primary shadow-md mr-2 btn-hover">
                Add New Dealer
            </a>
        </div>

        <!-- BEGIN: Dealers Layout -->
        <table id="DataTable" class="display table table-bordered intro-y col-span-12">
            <thead>
                <tr class="bg-primary font-bold text-white">
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone No</th>
                    <th>Address</th>
                    <th>GST Number</th>
                    <th>Notes</th>
                    <th style="text-align: left;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dealers as $dealer)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $dealer->name }}</td>
                        <td>{{ $dealer->email }}</td>
                        <td>{{ $dealer->phone_no }}</td>
                        <td>{{ $dealer->address }}</td>
                        <td>{{ $dealer->gst_number ?? '-' }}</td>
                        <td>{{ $dealer->notes ?? '-' }}</td>
                        <td>
                            <div class="flex gap-2 justify-content-left">
                                <!-- Delete -->
                                <form action="{{ route('dealers.destroy', $dealer->id) }}" method="POST"
                                      onsubmit="return confirm('Are you sure you want to delete this dealer?');"
                                      style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger mr-1 mb-2"><i
                                    data-lucide="view" class="w-4 h-4"></i></button>
                                </form>

                                <!-- Edit -->
                                <a href="{{ route('dealers.edit', $dealer->id) }}" 
                                   class="btn btn-primary mr-1 mb-2"><i
                                    data-lucide="edit" class="w-4 h-4"></i></a>

                                <!-- View (optional) -->
                                <a href="{{ route('dealers.show', $dealer->id) }}" 
                                   class="btn btn-success mr-1 mb-2"><i
                                    data-lucide="view" class="w-4 h-4"></i></a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <!-- END: Dealers Layout -->
    </div>
</div>
@endsection