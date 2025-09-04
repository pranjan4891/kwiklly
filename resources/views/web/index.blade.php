@extends('web.include.main')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    .top-banner {
    padding: 15px;
}
.bannerimg .img-fluid {
    border-radius: 10px;
    width: 100% !important;
    height: auto;
}
</style>
    <!-- first section start  -->
    <section class="extrapadding2">
        <div class="container">
            <div class="row">
                <div class="col-md-12 bannerimg p-0">
                    <!-- Show top banner (banner_cat_id = 1)  -->
                    @php
                        $topBanner = collect($banners)->firstWhere('banner_cat_id', 1);
                    @endphp
                    @if ($topBanner)
                        <div class="top-banner">
                            <img src="{{ asset('public/' . $topBanner['desktop_image']) }}" alt="" class="img-fluid">
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
    <!-- first section end  -->
    <!-- second section start  -->
    <section>
        <div class="container">
            <div class="row d-none d-md-flex text-center">
                <!-- Show other banners in loop (banner_cat_id = 2) -->
                @php
                    $secondBanners = collect($banners)->where('banner_cat_id', 2);
                @endphp
                @foreach ($secondBanners as $banner)
                    <div class="col-md-4 bannerimg">
                        <img src="{{ asset('public/' . $banner['desktop_image']) }}" alt="" class="img-fluid">
                    </div>
                @endforeach
            </div>

            <!-- Mobile Slider -->
            <div id="mobileSlider" class="carousel slide d-md-none" data-bs-ride="carousel" data-bs-interval="2000">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="{{ asset('public/assets/website/images/banner2.png') }}" class="d-block w-100 img-fluid"
                            alt="">
                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('public/assets/website/images/banner3.png') }}" class="d-block w-100 img-fluid"
                            alt="">
                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('public/assets/website/images/banner4.png') }}" class="d-block w-100 img-fluid"
                            alt="">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- second section end  -->
    <!-- third section start  -->
    <section>

        <div class="container mt-4">
            <h4 class="pb-3 pt-4 headingclass">Trending Products</h4>
            <div class="owl-carousel owl-theme mb-4">
                @foreach ($trending_products as $product)
                    @php
                        $defaultVariant = $product->variants->first(); // pick first variant by default
                    @endphp

                    @if ($defaultVariant)
                        <div class="item">
                            <div class="product-card p-0">
                                @if ($defaultVariant->variant_save_price_in_percent > 0)
                                    <span class="discount-label">{{ $defaultVariant->variant_save_price_in_percent }}%
                                        Off</span>
                                @endif
                                        @if ($product->is_physical)
                                              <a href="{{ route('productdetails', $product->slug) }}">
                                    <img src="{{ asset('public/' . $product->featureImage->feature_image) }}"
                                        class="product-image" alt="{{ $product->title }}">
                                </a>
                                        @else
                                        <a href="javascript:void(0);">
                                    <img src="{{ asset('public/' . $product->featureImage->feature_image) }}"
                                        class="product-image" alt="{{ $product->title }}">
                                </a>
                                        @endif


                                <div class="product-title cardpadding">{{ $product->title }}</div>
                                @php
                                    $attributes = json_decode($defaultVariant->attributes, true);
                                @endphp

                                @if (!empty($attributes))
                                    @php
                                        $firstAttr = collect($attributes)->first();
                                    @endphp
                                    <div class="product-info cardpadding">
                                        {{ $firstAttr }}
                                    </div>
                                @endif

                                <div class="price-container cardpadding">
                                    <span class="price">
                                        <span class="rupee-symbol">₹</span> {{ $defaultVariant->variant_selling_price }}
                                    </span>
                                    @if ($defaultVariant->variant_selling_price < $defaultVariant->variant_actual_price)
                                        <span class="original-price">
                                            <span class="rupee-symbol2">₹</span>
                                            {{ $defaultVariant->variant_actual_price }}
                                        </span>
                                    @endif


@php
    $hasMultipleVariants = $product->variants->count() > 1;
    $firstVariant = $product->variants->first();
    $key = $product->id . '_' . ($firstVariant->id ?? 0);

    if (auth()->check()) {
        // Logged-in user → check DB
        $cartItem = \App\Models\CartItem::where([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
            'variant_id' => $firstVariant->id,
        ])->first();

        $inCart = $cartItem !== null;
        $quantity = $inCart ? $cartItem->quantity : 1;
    } else {
        // Guest user → check session
        $cart = session('cart', []);
        $inCart = isset($cart[$key]);
        $quantity = $inCart ? $cart[$key]['quantity'] : 1;
    }
@endphp


<div class="qty-box"
     data-product-id="{{ $product->id }}"
     data-variant-id="{{ $firstVariant->id }}"
     data-key="{{ $key }}">

    @if ($hasMultipleVariants)
                     <button class="add-btn d-flex position-relative" onclick="openPopup({{ $product->id }})">

        {{-- <button class="add-btn  position-relative" onclick="{{ $hasMultipleVariants ? "openPopup($product->id)" : 'convertToQty(this)' }}"> --}}
            Add
            <img src="{{ asset('public/assets/website/images/cart.svg') }}" class="ms-2">
            <div class="cart-options text-black">{{ $product->variants->count() }} Options</div>
        </button>
    @else
       @if (!$inCart)
            <button class="add-btn" data-product-id="{{ $product->id }}" data-variant-id="{{ $firstVariant->id }}">
                Add
                <img src="{{ asset('public/assets/website/images/cart.svg') }}" class="ms-2">
            </button>
        @else
            <div class="qty-container">
                <button class="qty-btn minus decrement-btn" data-key="{{ $key }}">−</button>
                <input type="text" class="qty-input quantity-input" value="{{ $quantity }}" readonly>
                <button class="qty-btn plus increment-btn" data-key="{{ $key }}">+</button>
            </div>
        @endif

    @endif
</div>




                                </div>

                                <div class="store-info">
                                    <span>Ad </span>
                                    <span>{{ $product->vendor->business_name ?? '' }}</span>
                                    <span>5 min</span>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            <div class="text-center">
                <button class="view-all-btn mt-3">
                    View All <i class="fa fa-angles-down ms-2"></i>
                </button>
            </div>
        </div>


    </section>
    <!-- third section end  -->



    <!-- fourth section start  -->
    <section class="categoryfordesktop">
        <div class="container new-cate-grocery-section">
            <div class="row align-items-center m-0">
                <!-- Left Promo Section -->
                <div class="col-md-4">
                    <div class="new-cate-promo-box">
                        <h2>Grocery at your doorstep</h2>
                        <p>Your favorite vegetables, fruits & more</p>
                        <button class="btn btn-light">Order Now</button>
                        @php
                            $categoryBanner = collect($banners)->firstWhere('banner_cat_id', 3);
                        @endphp

                        @if ($categoryBanner)
                            <img src="{{ asset('public/' . $categoryBanner['desktop_image']) }}" alt="Vegetables">
                        @endif

                    </div>
                </div>

                <!-- Right Category Slider -->
                <div class="col-md-8 new-cate">
                    <h4 class="py-3 m-0 new-cate-category-title headingclass">Categories</h4>
                    <div class="new-cate-owl-carousel owl-carousel owl-theme">
                        @foreach ($categories->chunk(2) as $chunk)
                            <div class="new-cate-item p-0">
                                @foreach ($chunk as $cat)
                                    <a href="{{route('stores', ['slug' => $cat->slug])}}" class="text-decoration-none text-dark">

                                        <div class="product-carded">
                                            <img src="{{ asset('public/' . $cat->image) }}" alt="{{ $cat['name'] }}">
                                        </div>
                                        <div class="py-2 text-center catename"><b>{{ $cat['name'] }}</b></div>
                                    </a>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>




    <!-- category section for mobile screen  -->
    <section class="categoryformobile">
        <div class="container new-cate-grocery-section">
            <div class="row align-items-center m-0">
                <!-- Left Promo Section -->
                <div class="col-md-4">
                    <div class="new-cate-promo-box">
                        <h2>Grocery at your doorstep</h2>
                        <p>Your favorite vegetables, fruits & more</p>
                        <button class="btn btn-light">Order Now</button>
                       @if ($categoryBanner)
                            <img src="{{ asset('public/' . $categoryBanner['desktop_image']) }}" alt="Vegetables">
                        @endif
                    </div>
                </div>

                <!-- Right Category Slider -->
                <div class="col-md-8 new-cate">
                    <h4 class="pb-3 new-cate-category-title headingclass">Categories</h4>
                    <div class="row" id="category-container">
                        <!-- Repeat this block for all your categories (add as many as you want, for demo 18) -->
                        @if ($categories->count() > 0)
                            @foreach ($categories as $cat)
                                <div class="col-md-4 col-4 new-cate-item-wrap">
                                    <div class="new-cate-item">
                                        <a href="{{route('stores', ['slug' => $cat->slug])}}" class="text-decoration-none text-dark">
                                        <div class="product-carded">
                                            <img src="{{ asset('public/' . $cat->image) }}" alt="{{ $cat['name'] }}">
                                        </div>
                                        <div class="py-2 text-center catename"><b>{{ $cat['name'] }}</b></div>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p>No categories found.</p>
                        @endif



                    </div>
                </div>
                <div class="text-center">
                    <button class="view-all-btn mt-3" id="loadMoreBtn">
                        Load More <i class="fa fa-angles-down ms-2"></i>
                    </button>
                </div>
            </div>
    </section>
    <!-- fourth section end  -->
    <!-- fifth section start  -->
    <section class="">
        <div class="container">
            <h4 class="pb-3 pt-4 headingclass">Stores</h4>
            <div class="row justify-content-center">
                <!-- Store Item -->
                @if ($stores)
                    @foreach ($stores as $store)
                        <div class="col-md-2 col-4 store-item">
                            <div class="store-image">
                                @php
                                    $imagePath = public_path($store->business_logo);
                                    $imageUrl = file_exists($imagePath)
                                        ? asset('public/' . $store->business_logo)
                                        : asset('public/uploads/no-image.jpg');
                                @endphp

                                <img src="{{ $imageUrl }}" alt="Store">
                            </div>

                            <div class="store-name">{{ $store->business_name }}</div>

                            @foreach($store->categories as $cat)
                                <a href="{{ route('stores', ['slug' => $cat->slug])}}">{{ $cat->name }}
                               </a>
                            @endforeach

                            <div class="store-discount">Upto 28% Off</div>
                        </div>
                    @endforeach
                @endif

            </div>

            <!-- View All Button -->
            <div class="text-center">
                <button class="view-all-btn mt-3" onclick="window.location='{{ route('stores') }}'">
                    View All <i class="fa fa-angles-down ms-2"></i>
                </button>
            </div>
        </div>
    </section>

    <!-- fifth section end   -->
    <!-- sixth section start  -->
    <section>
    @if ($categorywiseproducts)
        @foreach ($categorywiseproducts as $categoryData)
            <div class="container mt-4">
                <h4 class="pb-3 pt-4 headingclass">{{ $categoryData['name'] }}</h4>

                {{-- First Carousel - First 8 products --}}
                <div class="owl-carousel owl-theme mb-4">
                    @foreach ($categoryData['products']->take(8) as $product)
                        @php
                            $defaultVariant = $product->variants->first(); // pick first variant
                        @endphp
                        @if ($defaultVariant)
                            <div class="item">
                                <div class="product-card p-0">
                                    @if ($defaultVariant->variant_save_price_in_percent > 0)
                                        <span class="discount-label">{{ $defaultVariant->variant_save_price_in_percent }}% Off</span>
                                    @endif
                                    @if ($product->is_physical)
                                        <a href="{{ route('productdetails', $product->slug) }}">
                                            <img src="{{ asset('public/' . $product->featureImage->feature_image) }}"
                                                class="product-image" alt="{{ $product->title }}">
                                        </a>
                                    @else
                                        <a href="javascript:void(0);">
                                            <img src="{{ asset('public/' . $product->featureImage->feature_image) }}"
                                                class="product-image" alt="{{ $product->title }}">
                                        </a>
                                    @endif


                                    <div class="product-title cardpadding">{{ $product->title }}</div>

                                    @php
                                        $attributes = json_decode($defaultVariant->attributes, true);
                                        $firstAttr = !empty($attributes) ? collect($attributes)->first() : '';
                                    @endphp
                                    @if ($firstAttr)
                                        <div class="product-info cardpadding">{{ $firstAttr }}</div>
                                    @endif

                                    <div class="price-container cardpadding">
                                        <span class="price">
                                            <span class="rupee-symbol">₹</span> {{ $defaultVariant->variant_selling_price }}
                                        </span>
                                        @if ($defaultVariant->variant_selling_price < $defaultVariant->variant_actual_price)
                                            <span class="original-price">
                                                <span class="rupee-symbol2">₹</span> {{ $defaultVariant->variant_actual_price }}
                                            </span>
                                        @endif

                                        @php $hasMultipleVariants = $product->variants->count() > 1; @endphp
                                        <button class="add-btn d-flex flex-column align-items-center position-relative"
                                            onclick="{{ $hasMultipleVariants ? "openPopup($product->id)" : 'convertToQty(this)' }}">
                                            <div class="d-flex align-items-center">
                                                Add
                                                <img src="{{ asset('public/assets/website/images/cart.svg') }}" alt="" class="ms-2">
                                            </div>
                                            <div class="cart-options text-black">
                                                @if ($hasMultipleVariants)
                                                    {{ $product->variants->count() }} Options
                                                @endif
                                            </div>
                                        </button>
                                    </div>
                                    <div class="store-info ">
                                        <span>Ad </span>
                                        <span>{{ $product->vendor->business_name ?? 'Chandrash Grocery' }}</span>
                                        <span>5 min </span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>

                {{-- Second Carousel - Remaining products (after first 8) --}}
                @if ($categoryData['products']->count() > 8)
                    <div class="owl-carousel owl-theme">
                        @foreach ($categoryData['products']->skip(8)->take(8) as $product)
                            @php
                                $defaultVariant = $product->variants->first();
                            @endphp
                            @if ($defaultVariant)
                                <div class="item">
                                    <div class="product-card p-0">
                                        @if ($defaultVariant->variant_save_price_in_percent > 0)
                                            <span class="discount-label">{{ $defaultVariant->variant_save_price_in_percent }}% Off</span>
                                        @endif
                                        @if ($product->is_physical)
                                            <a href="{{ route('productdetails', $product->slug) }}">
                                                <img src="{{ asset('public/' . $product->featureImage->feature_image) }}"
                                                    class="product-image" alt="{{ $product->title }}">
                                            </a>
                                        @else
                                            <a href="javascript:void(0);">
                                                <img src="{{ asset('public/' . $product->featureImage->feature_image) }}"
                                                    class="product-image" alt="{{ $product->title }}">
                                            </a>
                                        @endif


                                        <div class="product-title cardpadding">{{ $product->title }}</div>

                                        @php
                                            $attributes = json_decode($defaultVariant->attributes, true);
                                            $firstAttr = !empty($attributes) ? collect($attributes)->first() : '';
                                        @endphp
                                        @if ($firstAttr)
                                            <div class="product-info cardpadding">{{ $firstAttr }}</div>
                                        @endif

                                        <div class="price-container cardpadding">
                                            <span class="price">
                                                <span class="rupee-symbol">₹</span> {{ $defaultVariant->variant_selling_price }}
                                            </span>
                                            @if ($defaultVariant->variant_selling_price < $defaultVariant->variant_actual_price)
                                                <span class="original-price">
                                                    <span class="rupee-symbol2">₹</span> {{ $defaultVariant->variant_actual_price }}
                                                </span>
                                            @endif

                                            @php $hasMultipleVariants = $product->variants->count() > 1; @endphp
                                            <button class="add-btn d-flex flex-column align-items-center position-relative"
                                                onclick="{{ $hasMultipleVariants ? "openPopup($product->id)" : 'convertToQty(this)' }}">
                                                <div class="d-flex align-items-center">
                                                    Add
                                                    <img src="{{ asset('public/assets/website/images/cart.svg') }}" alt="" class="ms-2">
                                                </div>
                                                <div class="cart-options text-black">
                                                    @if ($hasMultipleVariants)
                                                        {{ $product->variants->count() }} Options
                                                    @endif
                                                </div>
                                            </button>
                                        </div>
                                        <div class="store-info ">
                                            <span>Ad </span>
                                            <span>{{ $product->vendor->business_name ?? 'Chandrash Grocery' }}</span>
                                            <span>5 min </span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif

                {{-- View All Button --}}
                @if($categoryData['products']->count() > 0)
                    @php
                        $firstProduct = $categoryData['products']->first();
                    @endphp
                    <div class="text-center mt-5">
                        <a href="{{ route('categorywiseproduct', ['category_id' => $firstProduct->category_id, 'subcategory_id' => $firstProduct->sub_category_id]) }}"
                        class="view-all-btn mt-3">
                            View All <i class="fa fa-angles-down ms-2"></i>
                        </a>
                    </div>
                @endif
            </div>
        @endforeach
    @else
        <p>No category-wise products found.</p>
    @endif


        <!-- Popup Modal -->
        <!-- Dynamic Popup Modal for Variants -->
        <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="productModalLabel">Loading...</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <h6>Select Unit</h6>
                        <div class="unit-list" id="variantList">
                            <p>Loading...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </section>
    <!-- sixth section end  -->


    <script>
        const getVariantUrl = "{{ url('/get-product-variants') }}"; // No id here
    </script>
    <script>
        function openPopup(productId) {
            const modal = new bootstrap.Modal(document.getElementById('productModal'));
            const finalUrl = `${getVariantUrl}/${productId}`;

            // Reset content
            document.getElementById('productModalLabel').innerText = "Loading...";
            document.getElementById('variantList').innerHTML = '<p>Loading...</p>';

            fetch(finalUrl)
                .then(res => res.json())
                .then(data => {
                    document.getElementById('productModalLabel').innerText = data.product_name;

                    let html = '';
                    data.variants.forEach(variant => {
                        const attr = JSON.parse(variant.attributes || '{}');
                        const volume = attr.Volume || '';
                        const actual = variant.variant_actual_price;
                        const selling = variant.variant_selling_price;

                        html += `
                            <div class="unit-item d-flex align-items-center justify-content-between border-bottom py-2">
                                <div class="d-flex align-items-start">
                                    <img src="${data.image}" class="unit-image me-3" style="width:60px;height:60px;" alt="${data.product_name}">
                                    <div>
                                        <div class="fw-bold text-dark">${data.product_name}</div>
                                        <div class="text-muted small">${volume}</div>
                                        <div class="d-flex align-items-baseline gap-2">
                                            <span class="original-price text-decoration-line-through">₹ ${actual}</span>
                                            <span class="price fw-bold">₹ ${selling}</span>
                                        </div>
                                    </div>
                                </div>
                                <button class="add-btn btn btn-sm btn-outline-success" onclick="convertToQty(this)">
                                    Add <i class="fas fa-shopping-cart ms-1"></i>
                                </button>
                            </div>
                        `;
                    });

                    document.getElementById('variantList').innerHTML = html;
                    modal.show();
                })
                .catch(error => {
                    document.getElementById('variantList').innerHTML =
                        '<p class="text-danger">Failed to load variants.</p>';
                });
        }
    </script>



@endsection
