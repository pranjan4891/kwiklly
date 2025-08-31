@if($products->count())
    @foreach ($products as $product)
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

            $attributes = $defaultVariant ? json_decode($defaultVariant->attributes ?? '{}', true) : [];
            $firstAttr = $attributes ? collect($attributes)->first() : null;
        @endphp

        <div class="col-md-4 pb-4 col-6 product-item" data-subcategory="{{ $product->sub_category_id }}">
            <div class="item">
                <div class="product-card2 p-0">
                    @if ($defaultVariant && ($defaultVariant->variant_save_price_in_percent ?? 0) > 0)
                        <span class="discount-label">{{ (int) $defaultVariant->variant_save_price_in_percent }}% Off</span>
                    @endif
                    @if($product->is_physical)
                    <a href="{{ route('productdetails', $product->slug) }}">
                        @if($product->featureImage && $product->featureImage->feature_image)
                            <img src="{{ asset('public/' . $product->featureImage->feature_image) }}" class="product-image" alt="{{ $product->title }}">
                        @else
                            <img src="{{ asset('public/assets/website/images/product11.png') }}" class="product-image" alt="{{ $product->title }}">
                        @endif
                    </a>
                    @else
                        <a href="javascript:void(0)">
                        @if($product->featureImage && $product->featureImage->feature_image)
                            <img src="{{ asset('public/' . $product->featureImage->feature_image) }}" class="product-image" alt="{{ $product->title }}">
                        @else
                            <img src="{{ asset('public/assets/website/images/product11.png') }}" class="product-image" alt="{{ $product->title }}">
                        @endif
                    </a>
                    @endif

                    <div class="product-title cardpadding">{{ $product->title }}</div>

                    @if (!empty($firstAttr))
                        <div class="product-info cardpadding">{{ $firstAttr }}</div>
                    @elseif($defaultVariant)
                        <div class="product-info cardpadding">
                            {{ ($defaultVariant->weight ?? '') . ($defaultVariant->unit ?? '') }}
                        </div>
                    @endif

                    <div class="price-container cardpadding">
                        <span class="price">
                            ₹ {{ $defaultVariant->variant_selling_price ?? '--' }}
                        </span>

                        @if ($defaultVariant && ($defaultVariant->variant_selling_price < $defaultVariant->variant_actual_price))
                            <span class="original-price">
                                ₹ {{ $defaultVariant->variant_actual_price }}
                            </span>
                        @endif

                        <div class="qty-box"
                             data-product-id="{{ $product->id }}"
                             data-variant-id="{{ $firstVariant->id ?? '' }}"
                             data-key="{{ $key }}">
                            @if ($hasMultipleVariants)
                                <button class="add-btn d-flex flex-column align-items-center position-relative"
                                        onclick="openPopup({{ $product->id }})">
                                    <div class="d-flex align-items-center">
                                        Add
                                        <img src="{{ asset('public/assets/website/images/cart.svg') }}" class="ms-2">
                                    </div>
                                    <div class="cart-options text-black">{{ $product->variants->count() }} Options</div>
                                </button>
                            @else
                                @if (!$defaultVariant)
                                    <button class="add-btn" disabled>Unavailable</button>
                                @else
                                    @if (!$inCart)
                                        <button class="add-btn"
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
                        </div>
                    </div>

                    <div class="store-info">
                        <span>Ad</span>
                        <span>{{ $selectedVendor->business_name ?? 'Chandrash Grocery' }}</span>
                        <span>5 min away</span>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@else
    <div class="col-12">
        <div class="no-products text-center py-5">
            <h5 class="mt-3">Product not available</h5>
        </div>
    </div>
@endif
