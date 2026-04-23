@if ($sponsors_products->isNotEmpty())

<style>
/* ===============================
   PRODUCT CARD BASE
================================ */
.product-card {
    border: 1px solid #eee;
    border-radius: 8px;
    background: #fff;
    overflow: hidden;
    position: relative;
    height: 100%;
}

/* ===============================
   DISCOUNT LABEL
================================ */
.discount-label {
    position: absolute;
    top: 0px;
    left: 0px;
    background: #e6f7ed;
    color: #1a8f4b;
    font-size: 11px;
    font-weight: 600;
    padding: 3px 6px;
    border-radius: 0px;
    border-bottom-right-radius: 7px;
    z-index: 2;
}

/* ===============================
   PRODUCT IMAGE
================================ */
.product-image {
    width: 100%;
    height: 140px;
    object-fit: contain;
    padding: 10px;
}

/* ===============================
   TEXT SPACING
================================ */
.cardpadding {
    padding: 0 10px;
}

/* ===============================
   PRODUCT TITLE
================================ */
.product-title {
    font-size: 14px;
    font-weight: 600;
    color: #222;
    line-height: 1.2;
    margin-top: 4px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* ===============================
   PRODUCT INFO (90 gm etc)
================================ */
.product-info {
    font-size: 12px;
    color: #777;
    margin-top: 2px;
}

/* ===============================
   PRICE SECTION
================================ */
.price-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 6px;
}

.price-wrapper {
    display: flex;
    align-items: center;
    gap: 6px;
}

.price {
    font-size: 15px;
    font-weight: 700;
    color: #4CAF50 !important;
}

.original-price {
    font-size: 12px;
    color: #999;
    text-decoration: line-through;
}

/* ===============================
   ADD / QTY BUTTONS
================================ */


/* ===============================
   QTY BOX
================================ */
.owl-stage{
    padding-left: 0px !important;
}

/* ===============================
   STORE INFO
================================ */
.store-info {
    font-size: 11px;
    color: #777;
    padding: 6px 10px 10px;
}

.store-info a {
    color: black;
    text-decoration: none;
}

/* ===============================
   MOBILE RESPONSIVE
================================ */
@media (max-width: 576px) {

    .product-image {
        height: 120px;
    }

    .product-title {
        font-size: 13px;
    }

    .product-info {
        font-size: 11px;
    }

    .price {
        font-size: 14px;
    }

    .original-price {
        font-size: 11px;
    }

    .add-btn {
        font-size: 12px;
        padding: 3px 8px;
    }

    .qty-btn {
        width: 24px;
        height: 24px;
        font-size: 16px;
    }

    .qty-input {
        width: 26px;
        font-size: 12px;
    }

    .discount-label {
        font-size: 10px;
        padding: 2px 5px;
    }
}
    
    
</style>
{{-- {{dd($sponsors_products)}} --}}
<div class="container mt-1 mt-md-2">
      <h4 class="pb-3  headingclass">Sponsored Products</h4>
      <div class="owl-carousel owl-theme mb-4">
         @foreach ($sponsors_products as $product)
            @php
                $defaultVariant = $product->variants->firstWhere('stock', '>', 0) ?? $product->variants->first();
                $variantInStock = $defaultVariant && $defaultVariant->stock > 0;
            @endphp
            @if ($defaultVariant)
               <div class="item">
                  <div class="product-card p-0">
                     @if ($defaultVariant->variant_save_price_in_percent > 0)
                        <span class="discount-label">{{ (int) round($defaultVariant->variant_save_price_in_percent) }}% Off</span>
                     @endif

                     @if ($product->is_physical)
                        <a href="{{ route('productdetails', $product->slug) }}" onclick="return redirectWithLocation(this.href)">
                           <img src="{{ $defaultVariant->displayImageUrlForProduct($product) }}" class="product-image" alt="{{ $product->title }}">
                        </a>
                     @else
                        <div class="product-image-wrap">
                           <img src="{{ $defaultVariant->displayImageUrlForProduct($product) }}" class="product-image" alt="{{ $product->title }}">
                        </div>
                     @endif

                     <div class="product-title cardpadding" title="{{ $product->title }}">{{ $product->title }}</div>
                     @php $attributes = json_decode($defaultVariant->attributes, true); @endphp
                     @if (!empty($attributes))
                        <div class="product-info cardpadding">{{ collect($attributes)->first() }}</div>
                     @endif

                     {{-- ✅ Same cart logic --}}
                     <div class="price-container cardpadding">
                        <div class="price-wrapper">
                        <span class="price">
                            <span class="rupee-symbol">₹</span> {{ intval($defaultVariant->variant_selling_price) }}
                        </span>

                        @if ($defaultVariant->variant_selling_price < $defaultVariant->variant_actual_price)
                            <span class="original-price">
                                <span class="rupee-symbol2">₹</span> {{ intval($defaultVariant->variant_actual_price) }}
                            </span>
                        @endif
                        </div>

                        @php
                           $hasMultipleVariants = $product->variants->count() > 1;
                           $firstVariant = $defaultVariant;
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
                            data-variant-id="{{ $firstVariant->id ?? '' }}"
                            data-key="{{ $key }}">

                            @php

                                $isOpen = \App\Helpers\StoreHelper::isStoreOpen($product->vendor->store_time);
                            @endphp

                            @if ($isOpen)
                                {{-- ✅ Store is open --}}
                                @if ($hasMultipleVariants)
                                    <button type="button" class="add-btn d-flex flex-column align-items-center position-relative"
                                            onclick="openPopup({{ $product->id }}, event)">
                                        <div class="d-flex align-items-center newimg">
                                            Add
                                            <img src="{{ asset('public/assets/website/images/cart.svg') }}" class="ms-2">
                                        </div>
                                        <div class="cart-options text-black">{{ $product->variants->count() }} Options</div>
                                    </button>
                                @else
                                    @if (!$defaultVariant)
                                        <button class="add-btn" disabled>Unavailable</button>
                                    @elseif(!$variantInStock)
                                        <span class="add-btn btn disabled text-muted">Out of Stock</span>
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
                        {{-- <span>Ad </span> --}}
                        <span><a href="{{ route('explorestore', ['vendor_id' => $product->vendor_id,'cat_id'=>$product->category_id]) }}" onclick="return redirectWithLocation(this.href)">{{ $product->vendor->business_name ?? '' }}</a></span>
                        {{-- <span>5 min</span> --}}
                     </div>
                  </div>
               </div>
            @endif
         @endforeach
      </div>
   </div>
   @endif
