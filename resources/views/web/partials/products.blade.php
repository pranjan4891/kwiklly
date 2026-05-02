@if($products->count())
    @foreach ($products as $product)
        @php
            $defaultVariant = $product->variants->firstWhere('stock', '>', 0) ?? $product->variants->first();
            $hasMultipleVariants = $product->variants->count() > 1;
            $firstVariant = $defaultVariant;
            $variantInStock = $firstVariant && $firstVariant->stock > 0;
            $productIsActive = $product->is_active == 1 || $product->is_active == true;
            $canAdd = $productIsActive && $variantInStock;
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

            $attributes = $defaultVariant ? json_decode($defaultVariant->attributes ?? '{}', true) : [];
            $firstAttr = $attributes ? collect($attributes)->first() : null;
            $listingProductImg = $defaultVariant
                ? $defaultVariant->displayImageUrlForProduct($product)
                : ($product->featureImage && $product->featureImage->feature_image
                    ? asset('public/' . $product->featureImage->feature_image)
                    : asset('public/assets/website/images/product11.png'));
        @endphp

        <div class="col-md-3 pb-4 col-6 product-item" data-subcategory="{{ $product->sub_category_id }}">
            <div class="item">
                <div class="product-card p-0">

                    @if ($defaultVariant && ($defaultVariant->variant_save_price_in_percent ?? 0) > 0)
                        <span class="discount-label">{{ (int) round($defaultVariant->variant_save_price_in_percent) }}% Off</span>
                    @endif
                    @if($product->is_physical)
                    <a href="{{ route('productdetails', $product->slug) }}"  onclick="return redirectWithLocation(this.href)">
                        <img src="{{ $listingProductImg }}" class="product-image" alt="{{ $product->title }}">
                    </a>
                    @else
                        <div class="product-image-wrap">
                        <img src="{{ $listingProductImg }}" class="product-image" alt="{{ $product->title }}">
                    </div>
                    @endif
                    @if ($product->is_physical)
                    <a href="{{ route('productdetails', $product->slug) }}" onclick="return redirectWithLocation(this.href)">
                        <div class="product-title cardpadding" title="{{ $product->title }}">{{ $product->title }}</div>
                    </a>
                    @else
                        <div class="product-title cardpadding" title="{{ $product->title }}">{{ $product->title }}</div>
                    @endif

                    @if (!empty($firstAttr))
                        <div class="product-info cardpadding">{{ $firstAttr }}</div>
                    @else
                        <div class="product-info cardpadding">{{ $defaultVariant->variant_name ?? '' }}</div>
                    @endif

                    <div class="price-container cardpadding">
                        <div class="price-wrapper">
                        <span class="price">
                            <span class="rupee-symbol">₹</span> {{ $defaultVariant ? intval($defaultVariant->variant_selling_price) : '--' }}
                        </span>
                        @if ($defaultVariant && $defaultVariant->variant_selling_price < $defaultVariant->variant_actual_price)
                            <span class="original-price">
                                <span class="rupee-symbol2">₹</span> {{ intval($defaultVariant->variant_actual_price) }}
                            </span>
                        @endif
                        </div>
                        <div class="qty-box"
                            data-product-id="{{ $product->id }}"
                            data-variant-id="{{ $firstVariant->id ?? '' }}"
                            data-key="{{ $key }}">

                            @php

                                $isOpen = \App\Helpers\StoreHelper::isStoreOpen($product->vendor->store_time);
                            @endphp

                            @if ($isOpen)
                                {{-- ✅ Store is open --}}
                                @if (!$canAdd)
                                    <span class="add-btn btn disabled text-muted">Out of Stock</span>
                                @elseif ($hasMultipleVariants)
                                    <button type="button" class="add-btn d-flex flex-column align-items-center position-relative"
                                            onclick="openPopup({{ $product->id }}, event)">
                                        <div class="d-flex align-items-center">
                                            Add
                                            <img src="{{ asset('public/assets/website/images/cart.svg') }}" class="ms-2">
                                        </div>
                                        <div class="cart-options text-black">{{ $product->variants->count() }} Options</div>
                                    </button>
                                @else
                                    @if (!$inCart)
                                        <button type="button" class="add-btn"
                                                data-product-id="{{ $product->id }}"
                                                data-variant-id="{{ $firstVariant->id }}">
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
                            @else
                                {{-- ❌ Store is closed --}}
                                <button class="add-btn disabled" disabled>
                                    Add <img src="{{ asset('public/assets/website/images/cart.svg') }}" class="ms-2">
                                </button>
                            @endif
                        </div>
                    </div>


                    <div class="store-info">
                        <span><a href="{{ route('explorestore', ['vendor_id' => $product->vendor_id,'cat_id'=>$product->category_id]) }}" onclick="return redirectWithLocation(this.href)">{{ $product->vendor->business_name ?? '' }}</a></span>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

@endif
