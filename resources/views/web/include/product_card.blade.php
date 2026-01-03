@php
    $defaultVariant = $product->variants->first(); // first variant
@endphp

@if ($defaultVariant)
    <div class="item">
        <div class="product-card p-0">

            {{-- 🔹 Discount Label --}}
            @if ($defaultVariant->variant_save_price_in_percent > 0)
                <span class="discount-label">{{ (int) round($defaultVariant->variant_save_price_in_percent) }}% Off</span>
            @endif

            {{-- 🔹 Product Image --}}
            <a href="{{ $product->is_physical ? route('productdetails', $product->slug) : 'javascript:void(0);' }}">
                <img src="{{ asset('public/' . optional($product->featureImage)->feature_image) }}"
                     class="product-image"
                     alt="{{ $product->title }}">
            </a>

            {{-- 🔹 Product Title --}}
            <div class="product-title cardpadding" title="{{ $product->title }}">{{ $product->title }}</div>

            {{-- 🔹 Variant Attributes (e.g. Volume) --}}
            @php
                $attributes = is_array($defaultVariant->attributes) 
                    ? $defaultVariant->attributes 
                    : json_decode($defaultVariant->attributes ?? '{}', true);
            @endphp
            @if (!empty($attributes))
                <div class="product-info cardpadding">{{ collect($attributes)->first() }}</div>
            @endif

            {{-- 🔹 Price + Cart/Qty Box --}}
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
                    @php
                        $isOpen = \App\Helpers\StoreHelper::isStoreOpen($product->vendor->store_time);
                    @endphp

                    @if ($isOpen)
                        {{-- If multiple variants → Open Popup --}}
                        @if ($hasMultipleVariants)
                            <button class="add-btn d-flex position-relative"
                                    onclick="openPopup({{ $product->id }})">
                                Add <img src="{{ asset('public/assets/website/images/cart.svg') }}" class="ms-2">
                                <div class="cart-options text-black">{{ $product->variants->count() }} Options</div>
                            </button>
                        @else
                            {{-- If not in cart → Show Add button --}}
                            @if (!$inCart)
                                <button class="add-btn"
                                        data-product-id="{{ $product->id }}"
                                        data-variant-id="{{ $variantId }}">
                                    Add <img src="{{ asset('public/assets/website/images/cart.svg') }}" class="ms-2">
                                </button>
                            @else
                                {{-- If already in cart → Show Qty Controls --}}
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
                <!-- <span>Ad</span> -->
                <span><a href="{{ route('explorestore', ['vendor_id' => $product->vendor_id,'cat_id'=>$product->category_id]) }}" onclick="return redirectWithLocation(this.href)">{{ $product->vendor->business_name ?? '' }}</a></span>
                <!-- <span>5 min</span> -->
            </div>
        </div>
    </div>
@endif
