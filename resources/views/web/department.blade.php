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
                        <img src="{{ asset('public/assets/website/images/small1.png') }}">
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

<!-- pop up of add button  -->
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productModalLabel">Product Variants</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6>Select Unit</h6>
                <div class="unit-list" id="variant-list">
                    <!-- Variants will be loaded here dynamically -->
                </div>
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

    // Open variant popup
    function openPopup(productId) {
        fetch(`/product/${productId}/variants`)
            .then(response => response.json())
            .then(variants => {
                const variantList = document.getElementById('variant-list');
                variantList.innerHTML = '';

                variants.forEach(variant => {
                    const variantItem = document.createElement('div');
                    variantItem.className = 'unit-item';
                    variantItem.innerHTML = `
                        <img src="${variant.image_url || `{{ asset('public/assets/website/images/category1.png') }}`}" class="unit-image" alt="Product Variant">
                        <span>${variant.weight || ''}${variant.unit || ''}</span>
                        <span class="pricepopup"><span class="rupee-symbol">₹</span> ${variant.variant_selling_price}</span>
                        ${Number(variant.variant_selling_price) < Number(variant.variant_actual_price)
                            ? `<span class="original-price"><span class="rupee-symbol2">₹</span> ${variant.variant_actual_price}</span>` : ''}
                        <button class="add-btn" onclick="addToCartVariant(${variant.id})">Add
                            <img src="{{ asset('public/assets/website/images/cart.svg') }}" alt="" class="ms-1">
                        </button>
                    `;
                    variantList.appendChild(variantItem);
                });

                const modal = new bootstrap.Modal(document.getElementById('productModal'));
                modal.show();
            })
            .catch(err => console.error('Error fetching variants:', err));
    }

    // Add product to cart (single-variant items)
    function addToCart(button) {
        const productId = button.getAttribute('data-product-id');
        const variantId = button.getAttribute('data-variant-id');
        // TODO: AJAX add-to-cart endpoint
        console.log('Adding product to cart:', productId, variantId);
    }

    // Add variant to cart (from popup)
    function addToCartVariant(variantId) {
        // TODO: AJAX add-to-cart endpoint
        console.log('Adding variant to cart:', variantId);
    }
</script>

@endsection

