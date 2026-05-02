{{-- Inner grid for category-wise #products-section — shared by view + AJAX --}}
@php $products = $products ?? collect(); @endphp
<div class="row pt-3">
    @if($products->count())
        @foreach ($products as $product)
            @php
                $defaultVariant = $product->variants->firstWhere('stock', '>', 0) ?? $product->variants->first();
                $hasMultipleVariants = $product->variants->count() > 1;
                $variantInStock = $defaultVariant && $defaultVariant->stock > 0;
                $productIsActive = $product->is_active == 1 || $product->is_active == true;
                $canAdd = $productIsActive && $variantInStock;

                $variantId = $defaultVariant->id ?? 0;
                $key = $product->id . '_' . $variantId;

                if (auth()->check() && $defaultVariant) {
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

                $attributes = $defaultVariant ? (array) json_decode($defaultVariant->attributes, true) : [];
                $firstAttr = collect($attributes)->first();
            @endphp

            <div class="col-md-3 pb-4 col-6">
                <div class="item">
                    <div class="product-card p-0">
                        @if ($defaultVariant && ($defaultVariant->variant_save_price_in_percent ?? 0) > 0)
                        <span class="discount-label">{{ (int) round($defaultVariant->variant_save_price_in_percent) }}% Off</span>
                        @endif
                        @if ($product->is_physical)
                        <a href="{{ route('productdetails', $product->slug) }}" onclick="return redirectWithLocation(this.href)">
                        <img src="{{ $defaultVariant ? $defaultVariant->displayImageUrlForProduct($product) : asset('public/assets/website/images/default.png') }}"
                           class="product-image" alt="{{ $product->title }}">
                        </a>
                        @else
                        <div class="product-image-wrap">
                        <img src="{{ $defaultVariant ? $defaultVariant->displayImageUrlForProduct($product) : asset('public/assets/website/images/default.png') }}"
                           class="product-image" alt="{{ $product->title }}">
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
                                 data-variant-id="{{ $variantId }}"
                                 data-key="{{ $key }}">
                                 @php
                                    $isOpen = \App\Helpers\StoreHelper::isStoreOpen($product->vendor->store_time);
                                @endphp
                                @if ($isOpen)
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
                                             data-variant-id="{{ $variantId }}">
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
    @else
        <div class="no-products text-center py-5">
            <h5 class="mt-3">Product not available</h5>
        </div>
    @endif
</div>
