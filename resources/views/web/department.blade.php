@extends('web.include.main')

@section('content')
<!-- first section start  -->
<section>
    <div id="vendor-header">
        {{-- Initial render of the header via partial --}}
        @include('web.partials.vendor_header', [
            'branches' => $branches,
            'selectedVendor' => $selectedVendor,
            'currentDay' => $currentDay ?? now()->format('l'),
            'currentTime' => $currentTime ?? null,
            'isOpen' => $isOpen ?? false,
        ])
    </div>
</section>
<!-- first section end  -->

<!-- second section start  -->
<section>
<div class="container mt-4 headingde">
    <h3>Inspiration for your order</h3>
    <div class="row">
        <div class="col-md-3">
            <div class="sidebarde">
                <ul>
                    <li class="sidebar-itemde active" data-subcategory="all" onclick="filterBySubcategory('all')">All Categories</li>
                    @foreach($subcategories as $subcategory)
                    <li class="sidebar-itemde" data-subcategory="{{ $subcategory->id }}">
                        <img src="{{ asset('public/uploads/subcategories/'.$subcategory->image) }}">
                        <a href="javascript:void(0)" onclick="filterBySubcategory({{ $subcategory->id }})" class="text-decoration-none text-dark">
                            {{ $subcategory->sub_cat_name }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- Mobile Sidebar as Horizontal Slider -->
        <div class="mobile-sidebar d-block d-md-none" style="overflow-x: auto; white-space: nowrap;">
            <div class="sidebar-itemde active" data-subcategory="all" onclick="filterBySubcategory('all')">All</div>
            @foreach($subcategories as $subcategory)
            <div class="sidebar-itemde" data-subcategory="{{ $subcategory->id }}" onclick="filterBySubcategory({{ $subcategory->id }})">
                <img src="{{ asset('public/uploads/subcategories/'.$subcategory->image) }}" alt="" style="width: 50px; height: 50px;">
                <div style="font-size: 12px;">{{ $subcategory->sub_cat_name }}</div>
            </div>
            @endforeach
        </div>

        <div class="col-md-9 fixedheight">
            <div class="row pt-3" id="products-container">
                {{-- Initial render of products via partial --}}
                @include('web.partials.products', [
                    'products' => $products,
                    'selectedVendor' => $selectedVendor
                ])
            </div>
        </div>
    </div>
</div>

</section>
<!-- second section end  -->

<script>
    // Change branch: refresh header + products; keep current subcategory filter
    function changeBranch(branchId) {
        const productsContainer = document.getElementById('products-container');
        const vendorHeader = document.getElementById('vendor-header');

        // remember active subcategory
        const activeEl = document.querySelector('.sidebar-itemde.active');
        const activeSubcategory = activeEl ? activeEl.getAttribute('data-subcategory') : 'all';

        // loader
        productsContainer.innerHTML =
            '<div class="col-12 text-center py-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>';

        fetch(`{{ route('department.products') }}?branch=${encodeURIComponent(branchId)}`)
            .then(r => r.json())
            .then(data => {
                if (!data.success) {
                    productsContainer.innerHTML = '<div class="col-12"><div class="alert alert-danger">Error loading products</div></div>';
                    return;
                }

                // Swap header and products
                vendorHeader.innerHTML = data.header;
                productsContainer.innerHTML = data.html;

                // Reapply the current subcategory filter to new DOM
                if (activeSubcategory) {
                    filterBySubcategory(activeSubcategory);
                }
            })
            .catch(() => {
                productsContainer.innerHTML = '<div class="col-12"><div class="alert alert-danger">Error loading products</div></div>';
            });
    }

    // Filter by subcategory (client-side show/hide)
    function filterBySubcategory(subcategoryId) {
        // Update active on both desktop + mobile lists
        document.querySelectorAll('.sidebar-itemde').forEach(i => i.classList.remove('active'));
        document.querySelectorAll(`.sidebar-itemde[data-subcategory="${subcategoryId}"]`).forEach(i => i.classList.add('active'));

        document.querySelectorAll('.product-item').forEach(product => {
            if (subcategoryId === 'all' || String(product.getAttribute('data-subcategory')) === String(subcategoryId)) {
                product.style.display = '';
            } else {
                product.style.display = 'none';
            }
        });
    }


</script>

@endsection

