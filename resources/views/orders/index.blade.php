@extends('app')
@push('styles')
    <style>
        .table td {
            border-bottom-width: 1px;
            padding: 0.25rem 0.5rem;
        }
    </style>
@endpush
@section('content')

    <!-- BEGIN: Content -->
    <div class="content">
        <h2 class="intro-y text-lg font-medium mt-10 heading">
            Orders
        </h2>
        {{-- Add this at the top of your blade file, before the content --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong> {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        <div class="grid grid-cols-12 gap-6 mt-5 grid-updated">
            <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
                <a href="{{ route('orders.create') }}" class="btn btn-primary shadow-md mr-2 btn-hover">Add New Order</a>
                <div class="input-form ml-auto">
                    <form method="GET" action="{{ route('orders.index') }}" class="flex gap-2">
                        <input type="text" name="search" id="search" placeholder="Search by customer, dealer, or product name"
                        value="{{ request('search') }}" class="form-control flex-1">
                        <button type="submit" class="btn btn-primary shadow-md btn-hover">Search</button>
                    </form>
                </div>
            </div>
        </div>
        <!-- BEGIN: Orders Layout -->
        <div class="intro-y col-span-12 overflow-auto">
            <div id="scrollable-table"
                style="max-height: calc(100vh - 200px); overflow-y: auto; border: 1px solid #ddd;" class="mt-5">
                <table id="DataTable" class="display table table-bordered w-full">
                    <thead style="position: sticky; top: 0; z-index: 10;">
                        <tr class="bg-primary font-bold text-white">
                            <th>ID</th>
                            <th>Dealer Name</th>
                            <th>Customer Name</th>
                            <th>Product Name</th>
                            <th>Production Step</th>
                            <th>Working</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Shade Number</th>
                            <th>Color</th>
                            <th>Delivery Time</th>
                            <th>Status</th>
                            <th style="text-align: left;">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="body" class="text-sm">
                        @include('orders.rows', ['page' => 1])
                    </tbody>
                </table>
            </div>
            <div id="loading" style="display: none; text-align: center; padding: 10px;">
                <p>Loading more...</p>
            </div>
            <!-- END: Orders Layout -->
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let page = 1;
        let loading = false;
        let currentSearch = '';

        const scrollContainer = document.getElementById('scrollable-table');
        const orderData = document.getElementById('body');
        const loadingIndicator = document.getElementById('loading');
        const searchInput = document.getElementById('search');
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

            // Build fetch URL with params: page, search
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
                    orderData.insertAdjacentHTML('beforeend', data);
                } else {
                    orderData.innerHTML = data;
                }

                loadingIndicator.style.display = 'none';
                loading = false;
            })
            .catch(error => {
                console.error("Error fetching orders:", error);
                loadingIndicator.style.display = 'none';
                loading = false;
            });
        }

        function updateOrderStatus(selectElement) {
            const orderId = selectElement.getAttribute('data-order-id');
            const newStatus = selectElement.value;
            const originalStatus = selectElement.getAttribute('data-original-status');

            // Show loading state
            selectElement.disabled = true;
            const originalHtml = selectElement.innerHTML;
            selectElement.innerHTML = '<option>Updating...</option>';
            const url = `/orders/update-status/${orderId}`;

            // Send AJAX request to update status
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    status: newStatus
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    selectElement.setAttribute('data-original-status', newStatus);
                    toastr.success('Status updated successfully');
                } else {
                    selectElement.value = originalStatus;
                    toastr.error(data.message || 'Failed to update status');
                }
            })
            .catch(error => {
                // Revert to original value on error
                selectElement.value = originalStatus;

                // Show error message
                toastr.error('Failed to update status: ' + error.message);
            })
            .finally(() => {
                // Restore select element
                selectElement.disabled = false;
                selectElement.innerHTML = originalHtml;
                selectElement.value = newStatus; // Maintain the selected value if update was successful
            });
        }
    </script>
@endpush
<script>
function updateOrderStatus(selectElement) {
    const orderId = selectElement.getAttribute('data-order-id');
    const newStatus = selectElement.value;
    const originalStatus = selectElement.getAttribute('data-original-status');
    
    // Show loading state
    selectElement.disabled = true;
    const originalHtml = selectElement.innerHTML;
    selectElement.innerHTML = '<option>Updating...</option>';
    const url = `/orders/update-status/${orderId}`;

    // Send AJAX request to update status
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            status: newStatus
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            selectElement.setAttribute('data-original-status', newStatus);
            toastr.success('Status updated successfully');
        } else {
            selectElement.value = originalStatus;
            toastr.error(data.message || 'Failed to update status');
        }
    })
    .catch(error => {
        // Revert to original value on error
        selectElement.value = originalStatus;
        
        // Show error message
        toastr.error('Failed to update status: ' + error.message);
    })
    .finally(() => {
        // Restore select element
        selectElement.disabled = false;
        selectElement.innerHTML = originalHtml;
        selectElement.value = newStatus; // Maintain the selected value if update was successful
    });
}
</script>