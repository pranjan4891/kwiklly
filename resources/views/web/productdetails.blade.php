
@extends('web.include.main')
@section('content')
<!-- first section start  -->
<section class="extrapadding">
<div class="container my-4">
    <div class="row">
    <p class="text-black">Home > {{ $product->category->name ?? 'Category' }} > {{ $product->subcategory->sub_cat_name ?? 'Subcategory' }} > {{ $product->title }}</p>
      <!-- Product Image and Thumbnails -->
      <div class="col-md-5 py-3">
        <div class="main-img-det">
          <img src="{{ $productImages && $productImages->feature_image ? asset('public/' . $productImages->feature_image) : asset('public/assets/website/images/default.png') }}" class="img-fluid main-product-det" alt="{{ $product->title }}">
        </div>

        <!-- Thumbnail slider with arrows -->
        <div class="thumb-container-det position-relative">
          <div class="arrow-det arrow-left-det"><i class="fa fa-chevron-left"></i></div>
          <div class="thumb-det">
            @if($productImages && $productImages->product_images && is_array($productImages->product_images))
              @foreach($productImages->product_images as $index => $image)
                <img src="{{ asset('public/' . $image) }}" class="{{ $index === 0 ? 'active' : '' }}" alt="{{ $product->title }}">
              @endforeach
            @else
              <img src="{{ $productImages && $productImages->feature_image ? asset('public/' . $productImages->feature_image) : asset('public/assets/website/images/default.png') }}" class="active" alt="{{ $product->title }}">
            @endif
          </div>
          <div class="arrow-det arrow-right-det"><i class="fa fa-chevron-right"></i></div>
        </div>
      </div>

      <!-- Product Details -->
        <div class="col-md-7 py-3">
            <div class="d-flex justify-content-between">
            <div class="prodetailheading">
                <h4>{{ $product->title }}</h4>
            </div>
            <div class="detailicon"><img src="{{ asset('public/assets/website/images/share.png')}}" alt=""></div>
            </div>
            <div class="prodetailheading">
             <p>by <span class="text-danger">{{ $product->vendor->business_name ?? 'Store' }}</span></p>
            </div>

            <!-- Share and Stock -->
            <div class="d-flex justify-content-between mt-4 mb-2">
             <div class="prodetailheading"><h6 class="mt-1">Net Quantity : 1 unit</h6></div>
             <div><p>Availability : {{ $product->variants->first() && $product->variants->first()->stock > 0 ? 'In Stock' : 'Out of Stock' }}</p></div>
            </div>

            @php
                $selectedVariant = $product->variants->first();
                $sellingPrice = $selectedVariant ? $selectedVariant->variant_selling_price : 0;
                $actualPrice = $selectedVariant ? $selectedVariant->variant_actual_price : 0;
                $discountPercent = ($actualPrice > $sellingPrice && $actualPrice > 0) ? round((($actualPrice - $sellingPrice) / $actualPrice) * 100) : 0;
            @endphp

            <div class="d-flex justify-content-between align-items-center">
                <div class="">
                    <span class="pricedetail">
                        <span class="rupee-symbol">₹</span> {{ number_format($sellingPrice, 0) }}
                    </span>
                    @if($actualPrice > $sellingPrice)
                    <span class="original-price ms-1">
                        <span class="rupee-symbol2">₹</span> {{ number_format($actualPrice, 0) }}
                    </span>
                    @endif
                    @if($discountPercent > 0)
                    <span class="badge bg-successs ms-2">{{ $discountPercent }}% Off</span>
                    @endif
                    <p>(incl. of all tax)</p>
                </div>
            </div>

            @php
                $colorVariants = collect();
                $memoryVariants = collect();
                $ramVariants = collect();

                foreach($product->variants as $variant) {
                    $attributes = json_decode($variant->attributes ?? '{}', true);
                    if(isset($attributes['Color'])) {
                        $colorVariants->push($attributes['Color']);
                    }
                    if(isset($attributes['Memory Size'])) {
                        $memoryVariants->push($attributes['Memory Size']);
                    }
                    if(isset($attributes['RAM'])) {
                        $ramVariants->push($attributes['RAM']);
                    }
                }

                $uniqueColors = $colorVariants->unique();
                $uniqueMemories = $memoryVariants->unique();
                $uniqueRams = $ramVariants->unique();
            @endphp

            @if($uniqueColors->isNotEmpty())
            <h5 class="detailheading">Select Color</h5>
            <div class="d-flex my-3">
                @foreach($uniqueColors as $color)
                <span class="color-option-det {{ $loop->first ? 'active' : '' }}">{{ $color }}</span>
                @endforeach
            </div>
            @endif

            @if($uniqueMemories->isNotEmpty())
            <h5 class="detailheading">Select Memory Size</h5>
            <div class="my-3">
                @foreach($uniqueMemories as $memory)
                <span class="btn-option-det {{ $loop->first ? 'active' : '' }}">{{ $memory }}</span>
                @endforeach
            </div>
            @endif

            @if($uniqueRams->isNotEmpty())
            <h5 class="detailheading">Select RAM</h5>
            <div class="my-3">
                @foreach($uniqueRams as $ram)
                <span class="btn-option-det {{ $loop->first ? 'active' : '' }}">{{ $ram }}</span>
                @endforeach
            </div>
            @endif

            @php
                $defaultVariant = $product->variants->first();
                $hasMultipleVariants = $product->variants->count() > 1;
                $firstVariant = $defaultVariant;
                $key = $product->id . '_' . ($firstVariant->id ?? 0);

                if (auth()->check()) {
                    $cartItem = \App\Models\CartItem::where([
                        'user_id' => auth()->id(),
                        'product_id' => $product->id,
                        'variant_id' => $firstVariant->id ?? null,
                    ])->first();
                    $inCart = $cartItem !== null;
                    $quantity = $inCart ? $cartItem->quantity : 1;
                } else {
                    $cart = session('cart', []);
                    $inCart = isset($cart[$key]);
                    $quantity = $inCart ? $cart[$key]['quantity'] : 1;
                }

                $isOpen = \App\Helpers\StoreHelper::isStoreOpen($product->vendor->store_time);
            @endphp

            <div class="qty-box"
                data-product-id="{{ $product->id }}"
                data-variant-id="{{ $firstVariant->id ?? '' }}"
                data-key="{{ $key }}">

                @if ($isOpen)
                    {{-- ✅ Store is open --}}
                    @if ($hasMultipleVariants)
                        <button class="add-btn-detail d-flex flex-column align-items-center position-relative"
                                onclick="openPopup({{ $product->id }})">
                            <div class="d-flex align-items-center">
                                Add
                                <img src="{{ asset('public/assets/website/images/cart.svg') }}" class="ms-2">
                            </div>
                            <div class="cart-options text-black">{{ $product->variants->count() }} Options</div>
                        </button>
                    @else
                        @if (!$defaultVariant)
                            <button class="add-btn-detail" disabled>Unavailable</button>
                        @else
                            @if (!$inCart)
                                <button class="add-btn-detail"
                                        data-product-id="{{ $product->id }}"
                                        data-variant-id="{{ $firstVariant->id }}"
                                        onclick="addToCart(this)">
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
                    @endif
                @else
                    {{-- ❌ Store is closed --}}
                    <button class="add-btn-detail disabled" disabled>
                        Store Closed
                    </button>
                @endif
            </div>

            <!-- Coupon and Offers -->
            @php
                $vendorCoupons = \App\Models\Coupon::where('created_by_id', $product->vendor_id)
                    ->where('is_active', 1)
                    ->where('is_deleted', 0)
                    ->limit(3)
                    ->get();
            @endphp

            @if($vendorCoupons->count() > 0)
            <h5 class="detailheading">Coupon & Offers</h5>
                @foreach($vendorCoupons as $coupon)
                <div class="coupon-det">
                    <i class="fa-solid fa-tags text-danger"></i>
                    {{ $coupon->discount_type === 'percentage' ? $coupon->discount_value . '% off' : '₹' . $coupon->discount_value . ' off' }}
                    above ₹{{ $coupon->min_order_amount }}
                </div>
                @endforeach
            @endif

            <!-- Description -->
            <h5 class="detailheading">Products Description</h5>
            <div class="desc-det">
                {!! $product->description ?? 'No description available.'!!}
                @if($product->disclaimer)
                <span class="extra-det">{! $product->disclaimer !}</span>
                @endif
                <span class="show-more-det">Show More +</span>
            </div>
        </div>
     </div>
  </div>
  <!-- for mobile  -->
  <div class="fixed-bottom-mobile footerpricemobile">
    <div class="d-flex align-items-center justify-content-between w-100 price-container-mobile">
        <div>
             <span class="pricedetail">
                <span class="rupee-symbol">₹</span> {{ number_format($sellingPrice, 0) }}
            </span>
            @if($actualPrice > $sellingPrice)
            <span class="original-price ms-1">
                <span class="rupee-symbol2">₹</span> {{ number_format($actualPrice, 0) }}
            </span>
            @endif
            @if($discountPercent > 0)
            <span class="discount-badge-mobile">{{ $discountPercent }}% Off</span>
            @endif
            <p class="tax-info-mobile">(incl. of all tax)</p>
        </div>
        @if ($isOpen)
            {{-- ✅ Store is open --}}
            @if ($hasMultipleVariants)
                <button class="add-btn-mobile d-flex flex-column align-items-center position-relative"
                        onclick="openPopup({{ $product->id }})">
                    <div class="d-flex align-items-center">
                        Add
                        <img src="{{ asset('public/assets/website/images/cart.svg') }}" class="ms-2">
                    </div>
                    <div class="cart-options text-black">{{ $product->variants->count() }} Options</div>
                </button>
            @else
                @if (!$defaultVariant)
                    <button class="add-btn-mobile" disabled>Unavailable</button>
                @else
                    @if (!$inCart)
                        <button class="add-btn-mobile"
                                data-product-id="{{ $product->id }}"
                                data-variant-id="{{ $firstVariant->id }}"
                                onclick="addToCart(this)">
                            Add
                            <img src="{{ asset('public/assets/website/images/cart.svg') }}" class="ms-2">
                        </button>
                    @else
                        <div class="qty-container-mobile">
                            <button class="qty-btn-mobile minus decrement-btn" data-key="{{ $key }}">−</button>
                            <input type="text" class="qty-input-mobile quantity-input" value="{{ $quantity }}" readonly>
                            <button class="qty-btn-mobile plus increment-btn" data-key="{{ $key }}">+</button>
                        </div>
                    @endif
                @endif
            @endif
        @else
            {{-- ❌ Store is closed --}}
            <button class="add-btn-mobile disabled" disabled>
                Store Closed
            </button>
        @endif

    </div>
</div>
   <!-- for mobile  -->
</section>
<!-- first section end  -->
<!-- second section start  -->
@if($similarProducts->count() > 0)
<section>
<div class="container mt-4">
    <h4 class="pb-3 pt-4 headingclass">Similar Products</h4>
        <div class="owl-carousel owl-theme">
        @foreach($similarProducts as $similarProduct)
        @php
            $variant = $similarProduct->variants->first();
            $sellingPrice = $variant ? $variant->variant_selling_price : 0;
            $actualPrice = $variant ? $variant->variant_actual_price : 0;
            $discountPercent = ($actualPrice > $sellingPrice && $actualPrice > 0) ? round((($actualPrice - $sellingPrice) / $actualPrice) * 100) : 0;

            $attributes = json_decode($variant->attributes ?? '{}', true);
            $variantInfo = array_values($attributes)[0] ?? '';
        @endphp
        <div class="item">
                <div class="product-card">
                    @if($discountPercent > 0)
                    <span class="discount-label">{{ $discountPercent }}% Off</span>
                    @endif
                    <a href="{{ route('productdetails', ['slug' => $similarProduct->slug]) }}">
                        <img src="{{ $similarProduct->featureImage ? asset('public/' . $similarProduct->featureImage->feature_image) : asset('public/assets/website/images/default.png') }}" class="product-image" alt="{{ $similarProduct->title }}">
                    </a>
                    <div class="product-title">{{ $similarProduct->title }}</div>
                    <div class="product-info">{{ $variantInfo }}</div>
                    <div class="">
                        <span class="pricedetail">
                            <span class="rupee-symbol">₹</span> {{ number_format($sellingPrice, 0) }}
                        </span>
                        @if($actualPrice > $sellingPrice)
                        <span class="original-price">
                            <span class="rupee-symbol2">₹</span> {{ number_format($actualPrice, 0) }}
                        </span>
                        @endif

                        @php
                            $defaultVariantSimilar = $similarProduct->variants->first();
                            $hasMultipleVariantsSimilar = $similarProduct->variants->count() > 1;
                            $firstVariantSimilar = $defaultVariantSimilar;
                            $keySimilar = $similarProduct->id . '_' . ($firstVariantSimilar->id ?? 0);

                            if (auth()->check()) {
                                $cartItemSimilar = \App\Models\CartItem::where([
                                    'user_id' => auth()->id(),
                                    'product_id' => $similarProduct->id,
                                    'variant_id' => $firstVariantSimilar->id ?? null,
                                ])->first();
                                $inCartSimilar = $cartItemSimilar !== null;
                                $quantitySimilar = $inCartSimilar ? $cartItemSimilar->quantity : 1;
                            } else {
                                $cartSimilar = session('cart', []);
                                $inCartSimilar = isset($cartSimilar[$keySimilar]);
                                $quantitySimilar = $inCartSimilar ? $cartSimilar[$keySimilar]['quantity'] : 1;
                            }

                            $isOpenSimilar = \App\Helpers\StoreHelper::isStoreOpen($similarProduct->vendor->store_time);
                        @endphp

                        <div class="qty-box"
                            data-product-id="{{ $similarProduct->id }}"
                            data-variant-id="{{ $firstVariantSimilar->id ?? '' }}"
                            data-key="{{ $keySimilar }}">

                            @if ($isOpenSimilar)
                                {{-- ✅ Store is open --}}
                                @if ($hasMultipleVariantsSimilar)
                                    <button class="add-btn d-flex flex-column align-items-center position-relative"
                                            onclick="openPopup({{ $similarProduct->id }})">
                                        <div class="d-flex align-items-center">
                                            Add
                                            <img src="{{ asset('public/assets/website/images/cart.svg') }}" class="ms-2">
                                        </div>
                                        <div class="cart-options text-black">{{ $similarProduct->variants->count() }} Options</div>
                                    </button>
                                @else
                                    @if (!$defaultVariantSimilar)
                                        <button class="add-btn" disabled>Unavailable</button>
                                    @else
                                        @if (!$inCartSimilar)
                                            <button class="add-btn"
                                                    data-product-id="{{ $similarProduct->id }}"
                                                    data-variant-id="{{ $firstVariantSimilar->id }}"
                                                    onclick="addToCart(this)">
                                                Add
                                                <img src="{{ asset('public/assets/website/images/cart.svg') }}" class="ms-2">
                                            </button>
                                        @else
                                            <div class="qty-container">
                                                <button class="qty-btn minus decrement-btn" data-key="{{ $keySimilar }}">−</button>
                                                <input type="text" class="qty-input quantity-input" value="{{ $quantitySimilar }}" readonly>
                                                <button class="qty-btn plus increment-btn" data-key="{{ $keySimilar }}">+</button>
                                            </div>
                                        @endif
                                    @endif
                                @endif
                            @else
                                {{-- ❌ Store is closed --}}
                                <button class="add-btn disabled" disabled>
                                    Store Closed
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        </div>
    </div>
</section>
@endif
<!-- second section end  -->
 <!-- third section start  -->
@if($otherVendorProducts->count() > 0)
<section>
<div class="container mt-4 otherproductmargin">
    <h4 class="pb-3 pt-4 headingclass">Other Products by {{ $product->vendor->business_name ?? 'Store' }}</h4>
        <div class="owl-carousel owl-theme">
        @foreach($otherVendorProducts as $vendorProduct)
        @php
            $variant = $vendorProduct->variants->first();
            $sellingPrice = $variant ? $variant->variant_selling_price : 0;
            $actualPrice = $variant ? $variant->variant_actual_price : 0;
            $discountPercent = ($actualPrice > $sellingPrice && $actualPrice > 0) ? round((($actualPrice - $sellingPrice) / $actualPrice) * 100) : 0;

            $attributes = json_decode($variant->attributes ?? '{}', true);
            $variantInfo = array_values($attributes)[0] ?? '';
        @endphp
        <div class="item">
                <div class="product-card">
                    @if($discountPercent > 0)
                    <span class="discount-label">{{ $discountPercent }}% Off</span>
                    @endif
                    <a href="{{ route('productdetails', ['slug' => $vendorProduct->slug]) }}">
                        <img src="{{ $vendorProduct->featureImage ? asset('public/' . $vendorProduct->featureImage->feature_image) : asset('public/assets/website/images/default.png') }}" class="product-image" alt="{{ $vendorProduct->title }}">
                    </a>
                    <div class="product-title">{{ $vendorProduct->title }}</div>
                    <div class="product-info">{{ $variantInfo }}</div>
                    <div class="">
                        <span class="pricedetail">
                            <span class="rupee-symbol">₹</span> {{ number_format($sellingPrice, 0) }}
                        </span>
                        @if($actualPrice > $sellingPrice)
                        <span class="original-price">
                            <span class="rupee-symbol2">₹</span> {{ number_format($actualPrice, 0) }}
                        </span>
                        @endif

                        @php
                            $defaultVariantVendor = $vendorProduct->variants->first();
                            $hasMultipleVariantsVendor = $vendorProduct->variants->count() > 1;
                            $firstVariantVendor = $defaultVariantVendor;
                            $keyVendor = $vendorProduct->id . '_' . ($firstVariantVendor->id ?? 0);

                            if (auth()->check()) {
                                $cartItemVendor = \App\Models\CartItem::where([
                                    'user_id' => auth()->id(),
                                    'product_id' => $vendorProduct->id,
                                    'variant_id' => $firstVariantVendor->id ?? null,
                                ])->first();
                                $inCartVendor = $cartItemVendor !== null;
                                $quantityVendor = $inCartVendor ? $cartItemVendor->quantity : 1;
                            } else {
                                $cartVendor = session('cart', []);
                                $inCartVendor = isset($cartVendor[$keyVendor]);
                                $quantityVendor = $inCartVendor ? $cartVendor[$keyVendor]['quantity'] : 1;
                            }

                            $isOpenVendor = \App\Helpers\StoreHelper::isStoreOpen($vendorProduct->vendor->store_time);
                        @endphp

                        <div class="qty-box"
                            data-product-id="{{ $vendorProduct->id }}"
                            data-variant-id="{{ $firstVariantVendor->id ?? '' }}"
                            data-key="{{ $keyVendor }}">

                            @if ($isOpenVendor)
                                {{-- ✅ Store is open --}}
                                @if ($hasMultipleVariantsVendor)
                                    <button class="add-btn d-flex flex-column align-items-center position-relative"
                                            onclick="openPopup({{ $vendorProduct->id }})">
                                        <div class="d-flex align-items-center">
                                            Add
                                            <img src="{{ asset('public/assets/website/images/cart.svg') }}" class="ms-2">
                                        </div>
                                        <div class="cart-options text-black">{{ $vendorProduct->variants->count() }} Options</div>
                                    </button>
                                @else
                                    @if (!$defaultVariantVendor)
                                        <button class="add-btn" disabled>Unavailable</button>
                                    @else
                                        @if (!$inCartVendor)
                                            <button class="add-btn"
                                                    data-product-id="{{ $vendorProduct->id }}"
                                                    data-variant-id="{{ $firstVariantVendor->id }}"
                                                    onclick="addToCart(this)">
                                                Add
                                                <img src="{{ asset('public/assets/website/images/cart.svg') }}" class="ms-2">
                                            </button>
                                        @else
                                            <div class="qty-container">
                                                <button class="qty-btn minus decrement-btn" data-key="{{ $keyVendor }}">−</button>
                                                <input type="text" class="qty-input quantity-input" value="{{ $quantityVendor }}" readonly>
                                                <button class="qty-btn plus increment-btn" data-key="{{ $keyVendor }}">+</button>
                                            </div>
                                        @endif
                                    @endif
                                @endif
                            @else
                                {{-- ❌ Store is closed --}}
                                <button class="add-btn disabled" disabled>
                                    Store Closed
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        </div>
    </div>
</section>
@endif
<!-- third section end  -->
<?php //include("include/footer2.php")?>
@endsection
