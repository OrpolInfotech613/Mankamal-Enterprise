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
                    <div class="input-form ml-auto">
                        <form method="GET" action="{{ route('dealers.index') }}" class="flex gap-2">
                            <input type="text" name="search" id="search" placeholder="Search by name"
                            value="{{ request('search') }}" class="form-control flex-1">
                            <button type="submit" class="btn btn-primary shadow-md btn-hover">Search</button>
                        </form>
                    </div>
                </div>
            </div>
            <!-- BEGIN: Dealers Layout -->
            <div class="intro-y col-span-12 overflow-auto">
                <div id="scrollable-table"
                    style="max-height: calc(100vh - 200px); overflow-y: auto; border: 1px solid #ddd;" class="mt-5">
                    <table id="DataTable" class="display table table-bordered w-full">
                        <thead style="position: sticky; top: 0; z-index: 10;">
                            <tr>
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
                        <tbody id="body">
                            @include('dealers.rows', ['page' => 1])
                        </tbody>
                    </table>
                </div>
                <div id="loading" style="display: none; text-align: center; padding: 10px;">
                    <p>Loading more...</p>
                </div>
                <!-- END: Dealers Layout -->
            </div>
        @endsection

        @push('scripts')
            <script>
                let page = 1;
                let loading = false;
                let hasMorePages = true;
                let currentSearch = '';

                const scrollContainer = document.getElementById('scrollable-table');
                const loadingIndicator = document.getElementById('loading');
                const searchInput = document.getElementById('search');
                const tableBody = document.querySelector('#body');

                // 🔍 Live Search
                searchInput.addEventListener('keyup', function() {
                    currentSearch = this.value;
                    page = 1;
                    hasMorePages = true;
                    tableBody.innerHTML = ''; // reset table

                    fetchMoreData(page, true);
                });

                // 📜 Infinite Scroll
                scrollContainer.addEventListener('scroll', function() {
                    if (scrollContainer.scrollTop + scrollContainer.clientHeight >= scrollContainer.scrollHeight - 10 &&
                        !loading && hasMorePages) {
                        page++;
                        fetchMoreData(page, false);
                    }
                });

                // 📦 Fetch Data
                function fetchMoreData(pageNum, replace = false) {
                    loading = true;
                    loadingIndicator.style.display = 'block';

                    const url = new URL("{{ route('dealers.index') }}");
                    url.searchParams.set('page', pageNum);
                    if (currentSearch) url.searchParams.set('search', currentSearch);

                    fetch(url, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'text/html'
                            }
                        })
                        .then(response => response.text())
                        .then(html => {
                            if (!html.trim()) {
                                hasMorePages = false;
                                loadingIndicator.innerHTML = '<p>No more data to load.</p>';
                                return;
                            }

                            const parser = new DOMParser();
                            const doc = parser.parseFromString(html, 'text/html');
                            const newRows = doc.querySelectorAll('tr');

                            // if (replace) {
                            //     tableBody.innerHTML = ''; // reset on new search
                            // }

                            newRows.forEach(row => {
                                tableBody.appendChild(row);
                            });

                            loadingIndicator.style.display = 'none';
                            loading = false;
                        })
                        .catch(error => {
                            console.error('Error fetching data:', error);
                            loading = false;
                            hasMorePages = false;
                            loadingIndicator.innerHTML = '<p>Error loading data.</p>';
                        });
                }
            </script>
        @endpush
