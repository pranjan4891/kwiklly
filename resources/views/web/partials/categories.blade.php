    {{-- Category-wise products: sirf delivery location wale vendors ke products (controller se filtered) --}}
    @if ($categorywiseproducts && count($categorywiseproducts) > 0)
        @foreach ($categorywiseproducts as $categoryData)
            @if ($categoryData['products']->count() > 0)
            <div class="container mt-4">
                {{-- 🔹 Category Title --}}
                <h4 class="pb-3 pt-4 headingclass">{{ ucfirst(strtolower($categoryData['name'])) }}</h4>

                {{-- 🔹 First Carousel (first 8 products) --}}
                <div class="owl-carousel owl-theme mb-4">
                    @foreach ($categoryData['products']->take(8) as $product)
                        @php
                            $defaultVariant = $product->variants->first();
                        @endphp
                        @if ($defaultVariant)
                            <div class="item">
                                <div class="product-card p-0">

                                    {{-- 🔹 Discount Label --}}
                                    @if ($defaultVariant->variant_save_price_in_percent > 0)
                                        <span class="discount-label">{{ (int) round($defaultVariant->variant_save_price_in_percent) }}% Off</span>
                                    @endif

                                    {{-- 🔹 Product Image --}}
                                    <a href="{{ $product->is_physical ? route('productdetails', $product->slug) : 'javascript:void(0);' }}" onclick="return redirectWithLocation(this.href)">
                                        <img src="{{ asset('public/' . optional($product->featureImage)->feature_image) }}"
                                             class="product-image"
                                             alt="{{ $product->title }}">
                                    </a>

                                    {{-- 🔹 Product Title --}}
                                    <div class="product-title cardpadding" title="{{ $product->title }}">{{ $product->title }}</div>

                                    {{-- 🔹 Variant Attribute (like volume/size) --}}
                                    @php
                                        $attributes = json_decode($defaultVariant->attributes, true);
                                    @endphp
                                    @if (!empty($attributes))
                                        <div class="product-info cardpadding">{{ collect($attributes)->first() }}</div>
                                    @endif

                                    {{-- 🔹 Price & Cart --}}
                                    <div class="price-container cardpadding">
                                        <div class="price-wrapper">
                                        <span class="price"><span class="rupee-symbol">₹</span> {{ intval($defaultVariant->variant_selling_price) }}</span>
                                        @if ($defaultVariant->variant_selling_price < $defaultVariant->variant_actual_price)
                                            <span class="original-price"><span class="rupee-symbol2">₹</span> {{ intval($defaultVariant->variant_actual_price) }}</span>
                                        @endif
                                        </div>

                                        @php
                                            $hasMultipleVariants = $product->variants->count() > 1;
                                            $variantId = $defaultVariant->id ?? 0;
                                            $key = $product->id . '_' . $variantId;

                                            if (auth()->check()) {
                                                $cartItem = \App\Models\CartItem::where([
                                                    'user_id'   => auth()->id(),
                                                    'product_id'=> $product->id,
                                                    'variant_id'=> $variantId,
                                                ])->first();
                                                $inCart = $cartItem !== null;
                                                $quantity = $inCart ? $cartItem->quantity : 1;
                                            } else {
                                                $cart = session('cart', []);
                                                $inCart = isset($cart[$key]);
                                                $quantity = $inCart ? $cart[$key]['quantity'] : 1;
                                            }
                                        @endphp

                                        <div class="qty-box"
                                             data-product-id="{{ $product->id }}"
                                             data-variant-id="{{ $variantId }}"
                                             data-key="{{ $key }}">
                                            {{-- If multiple variants → Popup --}}
                                             @php

                                                $isOpen = \App\Helpers\StoreHelper::isStoreOpen($product->vendor->store_time);
                                            @endphp

                                            @if ($isOpen)
                                                @if ($hasMultipleVariants)
                                                    <button class="add-btn d-flex position-relative"
                                                            onclick="openPopup({{ $product->id }})">
                                                        Add <img src="{{ asset('public/assets/website/images/cart.svg') }}" class="ms-2">
                                                        <div class="cart-options text-black">{{ $product->variants->count() }} Options</div>
                                                    </button>
                                                @else
                                                    {{-- Not in cart → Add --}}
                                                    @if (!$inCart)
                                                        <button class="add-btn"
                                                                data-product-id="{{ $product->id }}"
                                                                data-variant-id="{{ $variantId }}">
                                                            Add <img src="{{ asset('public/assets/website/images/cart.svg') }}" class="ms-2">
                                                        </button>
                                                    @else
                                                        {{-- Already in cart → Qty Controls --}}
                                                        <div class="qty-container">
                                                            <button class="qty-btn minus decrement-btn" data-key="{{ $key }}">−</button>
                                                            <input type="text" class="qty-input quantity-input" value="{{ $quantity }}" readonly>
                                                            <button class="qty-btn plus increment-btn" data-key="{{ $key }}">+</button>
                                                        </div>
                                                    @endif
                                                @endif
                                            @else
                                                {{-- ❌ Store is closed --}}
                                                <button class="add-btn disabled" disabled>
                                                    Add <img src="{{ asset('public/assets/website/images/cart.svg') }}" class="ms-2">
                                                </button>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- 🔹 Store Info --}}
                                    <div class="store-info">
                                        {{-- <span>Ad</span> --}}
                                        <span><a href="{{ route('explorestore', ['vendor_id' => $product->vendor_id,'cat_id'=>$product->category_id]) }}" onclick="return redirectWithLocation(this.href)">{{ $product->vendor->business_name ?? '' }}</a></span>
                                        {{-- <span>5 min</span> --}}
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>

                {{-- 🔹 Second Carousel (next 8 products) --}}
                @if ($categoryData['products']->count() > 8)
                    <div class="owl-carousel owl-theme">
                        @foreach ($categoryData['products']->skip(8)->take(8) as $product)
                            @include('web.include.product_card', ['product' => $product])
                        @endforeach
                    </div>
                @endif

                {{-- 🔹 View All --}}
                @if ($categoryData['products']->count() > 0)
                    <div class="text-center mt-5">
                        <a href="{{ route('allcategorywiseproduct', ['category_id' => $categoryData['products']->first()->category_id]) }}"
                           class="view-all-btn mt-3" onclick="return redirectWithLocation(this.href)">
                            View All <i class="fa fa-angles-down ms-2"></i>
                        </a>
                    </div>
                @endif
            </div>
            @endif {{-- ✅ end if products exist --}}
        @endforeach
   
    @endif


