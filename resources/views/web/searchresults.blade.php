@extends('web.include.main')
@section('content')

<section class="extrapadding">
    <div class="container mt-4">
        <div class="row align-items-center mb-3">
            <div class="col-md-6 col-12 searchtext">
                <h4 class="mb-0">Search Result "{{ $query }}"</h4>
                <p class="text-muted">{{ $storeCount }} stores near you</p>
            </div>
        </div>

        @if($products->isNotEmpty())
            <div class="row">
                @foreach($products as $product)
                    @php
                        $defaultVariant = $product->variants->firstWhere('stock', '>', 0) ?? $product->variants->first();
                        $variantInStock = $defaultVariant && $defaultVariant->stock > 0;
                    @endphp
                    @if($defaultVariant)
                        <div class="col-md-3 pb-4 col-6">
                            <div class="item">
                                <div class="product-card p-0">
                                    @if ($defaultVariant->variant_save_price_in_percent > 0)
                                        <span class="discount-label">{{ (int) round($defaultVariant->variant_save_price_in_percent) }}% Off</span>
                                    @endif

                                    @if ($product->is_physical)
                                        <a href="{{ route('productdetails', $product->slug) }}" onclick="return redirectWithLocation(this.href)">
                                            <img src="{{ $product->featureImage ? asset('public/' . $product->featureImage->feature_image) : asset('public/assets/website/images/default.png') }}"
                                                class="product-image" alt="{{ $product->title }}">
                                        </a>
                                    @else
                                        <a href="javascript:void(0);">
                                            <img src="{{ $product->featureImage ? asset('public/' . $product->featureImage->feature_image) : asset('public/assets/website/images/default.png') }}"
                                                class="product-image" alt="{{ $product->title }}">
                                        </a>
                                    @endif

                                    <div class="product-title cardpadding" title="{{ $product->title }}">{{ $product->title }}</div>
                                    @php $attributes = json_decode($defaultVariant->attributes, true); @endphp
                                    @if (!empty($attributes))
                                        <div class="product-info cardpadding">{{ collect($attributes)->first() }}</div>
                                    @endif

                                    <div class="price-container cardpadding">
                                        <div class="price-wrapper">
                                        <span class="price">
                                            <span class="rupee-symbol">₹</span> {{ intval($defaultVariant->variant_selling_price) }}
                                        </span>
                                        @if($defaultVariant->variant_selling_price < $defaultVariant->variant_actual_price)
                                            <span class="original-price">
                                                <span class="rupee-symbol2">₹</span> {{ intval($defaultVariant->variant_actual_price) }}
                                            </span>
                                        @endif
                                        </div>

                                        @php
                                            $hasMultipleVariants = $product->variants->count() > 1;
                                            $firstVariant = $product->variants->first();
                                            $key = $product->id . '_' . ($firstVariant->id ?? 0);

                                            if (auth()->check()) {
                                                $cartItem = \App\Models\CartItem::where([
                                                    'user_id' => auth()->id(),
                                                    'product_id' => $product->id,
                                                    'variant_id' => $firstVariant->id,
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
                                            data-variant-id="{{ $firstVariant->id }}"
                                            data-key="{{ $key }}">

                                            @php
                                                $isOpen = \App\Helpers\StoreHelper::isStoreOpen($product->vendor->store_time);
                                            @endphp

                                            @if ($isOpen)
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
                                                    @if(!$variantInStock)
                                                        <span class="add-btn btn disabled text-muted">Out of Stock</span>
                                                    @elseif (!$inCart)
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
                    @endif
                @endforeach
            </div>
        @else
            <p class="text-muted">No products found for "{{ $query }}"</p>
        @endif
    </div>
</section>

@endsection
