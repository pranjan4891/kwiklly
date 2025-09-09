
<div class="container mt-4">
      <h4 class="pb-3 pt-4 headingclass">Trending Products</h4>
      <div class="owl-carousel owl-theme mb-4">
         @foreach ($trending_products as $product)
            @php $defaultVariant = $product->variants->first(); @endphp
            @if ($defaultVariant)
               <div class="item">
                  <div class="product-card p-0">
                     @if ($defaultVariant->variant_save_price_in_percent > 0)
                        <span class="discount-label">{{ $defaultVariant->variant_save_price_in_percent }}% Off</span>
                     @endif

                     @if ($product->is_physical)
                        <a href="{{ route('productdetails', $product->slug) }}">
                           <img src="{{ asset('public/' . $product->featureImage->feature_image) }}" class="product-image" alt="{{ $product->title }}">
                        </a>
                     @else
                        <a href="javascript:void(0);">
                           <img src="{{ asset('public/' . $product->featureImage->feature_image) }}" class="product-image" alt="{{ $product->title }}">
                        </a>
                     @endif

                     <div class="product-title cardpadding">{{ $product->title }}</div>
                     @php $attributes = json_decode($defaultVariant->attributes, true); @endphp
                     @if (!empty($attributes))
                        <div class="product-info cardpadding">{{ collect($attributes)->first() }}</div>
                     @endif

                     {{-- ✅ Same cart logic --}}
                     <div class="price-container cardpadding">
                        <span class="price"><span class="rupee-symbol">₹</span> {{ $defaultVariant->variant_selling_price }}</span>
                        @if ($defaultVariant->variant_selling_price < $defaultVariant->variant_actual_price)
                           <span class="original-price"><span class="rupee-symbol2">₹</span> {{ $defaultVariant->variant_actual_price }}</span>
                        @endif

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
                           @if ($hasMultipleVariants)
                              <button class="add-btn d-flex position-relative" onclick="openPopup({{ $product->id }})">
                                 Add <img src="{{ asset('public/assets/website/images/cart.svg') }}" class="ms-2">
                                 <div class="cart-options text-black">{{ $product->variants->count() }} Options</div>
                              </button>
                           @else
                              @if (!$inCart)
                                 <button class="add-btn" data-product-id="{{ $product->id }}" data-variant-id="{{ $firstVariant->id }}">
                                    Add <img src="{{ asset('public/assets/website/images/cart.svg') }}" class="ms-2">
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
   </div>
