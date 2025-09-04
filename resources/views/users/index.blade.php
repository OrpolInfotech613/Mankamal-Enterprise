@extends('app')
@section('content')
    <!-- BEGIN: Content -->
    <div class="content">
        <h2 class="intro-y text-lg font-medium mt-10 heading">
            Users
        </h2>
        @if (session('success'))
            <div id="success-alert" class="alert alert-success" style="background-color: #d4edda; color: #155724; padding: 10px; margin-bottom: 10px;">
                {{ session('success') }}
            </div>
        @endif
    
        @if (session('error'))
            <div id="error-alert" class="alert alert-danger" style="background-color: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 10px;">
                {{ session('error') }}
            </div>
        @endif
        <div class="grid grid-cols-12 gap-6 mt-5 grid-updated">
            <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
                <a href="{{ Route('users.create') }}" class="btn btn-primary shadow-md mr-2 btn-hover">Add New User</a>
                <div class="input-form ml-auto">
                    <form method="GET" action="{{ route('users.index') }}" class="flex gap-2">
                        <input type="text" name="search" id="search-user" placeholder="Search by name"
                            value="{{ request('search') }}" class="form-control flex-1">
                        <button type="submit" class="btn btn-primary shadow-md btn-hover">Search</button>
                    </form>
                </div>
            </div>

            <!-- BEGIN: Users Layout -->
            <!-- DataTable: Add class 'datatable' to your table -->
            <div class="intro-y col-span-12 ">
                <div id="scrollable-table" style="max-height: calc(100vh - 200px); overflow-y: auto; border: 1px solid #ddd;">
                    <table class="table table-bordered w-full" style="border-collapse: collapse;">
                        <thead style="position: sticky; top: 0; z-index: 10;">
                            <tr class="bg-primary font-bold text-white">
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th style="TEXT-ALIGN: left;">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="user-data">
                            @include('users.rows', ['page' => 1])
                        </tbody>
                    </table>
                </div>
                <!-- END: Categories Layout -->
                <div id="loading" style="display: none; text-align: center; padding: 10px;">
                    <p>Loading more users...</p>
                </div>
            </div>

            <!-- END: Users Layout -->
        </div>
    </div>
@endsection
@push('scripts')
<script>
    let page = 1;
        let loading = false;
        let currentSearch = '';

        const scrollContainer = document.getElementById('scrollable-table');
        const userData = document.getElementById('user-data');
        const loadingIndicator = document.getElementById('loading');
        const searchInput = document.getElementById('search-user');
        let searchTimer;

        // Search on keyup with debounce
        searchInput.addEventListener('keyup', function () {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(() => {
                currentSearch = searchInput.value.trim();
                page = 1;
                loadMoreData(page, false);
            }, 300);
        });

        // Infinite scroll event
        scrollContainer.addEventListener('scroll', function () {
            const scrollBottom = scrollContainer.scrollTop + scrollContainer.clientHeight;
            const scrollHeight = scrollContainer.scrollHeight;

            if (scrollBottom >= scrollHeight - 100 && !loading) {
                page++;
                loadMoreData(page, true);
            }
        });

        // Load data (append or replace)
        function loadMoreData(pageToLoad, append = false) {
            loading = true;
            loadingIndicator.style.display = 'block';

            // Build fetch URL with params: page, search, branch_id
            let url = new URL(window.location.href);
            url.searchParams.set('page', pageToLoad);

            if (currentSearch) {
                url.searchParams.set('search', currentSearch);
            } else {
                url.searchParams.delete('search');
            }

            fetch(url.toString(), {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.text())
            .then(data => {
                if (data.trim().length == 0) {
                    loadingIndicator.innerHTML = '';
                    return;
                }

                if (append) {
                    userData.insertAdjacentHTML('beforeend', data);
                } else {
                    userData.innerHTML = data;
                }

                loadingIndicator.style.display = 'none';
                loading = false;
            })
            .catch(error => {
                console.error("Error fetching companies:", error);
                loadingIndicator.style.display = 'none';
                loading = false;
            });
        }
</script>
@endpush