@extends('web.include.main')
@section('content')

<!-- Add this script to handle the progress bar -->

<section class="extrapadding">
   <div class="xyz-banner" style="background-color: #3b6939;">
      <div class="xyz-gradient">
         <h3 class="text-center">{{ $category->name }}</h3>
      </div>
   </div>
</section>


<!-- second section start  -->
<section>
   <div class="container mt-4 headingde">
      <h3>Inspiration for your order</h3>
      <div class="row">
         <div class="col-md-3">
            <div class="sidebarde">
               <ul>
                  @foreach($subcategories as $subcategory)
                  <li class="sidebar-itemde">
                     <img src="{{ asset('public/uploads/subcategories/'.$subcategory->image) }}">
                     <a href="{{ route('categorywiseproduct', [$category->id, $subcategory->id]) }}"  onclick="return redirectWithLocation(this.href)" class="text-decoration-none text-dark">
                     {{ $subcategory->sub_cat_name }}
                     </a>
                  </li>
                  @endforeach
               </ul>
            </div>
         </div>
         <!-- Mobile Sidebar as Horizontal Slider -->
         <div class="mobile-sidebar d-block d-md-none" style="overflow-x: auto; white-space: nowrap;">
            @foreach($subcategories as $subcategory)
            <a href="{{ route('categorywiseproduct', [$category->id, $subcategory->id]) }}" class="d-inline-block text-center px-2 text-decoration-none text-dark" style="width: 100px;">
               <div class="sidebar-itemde">
                  <img src="{{ asset('public/uploads/subcategories/'.$subcategory->image) }}" alt="" style="width: 50px; height: 50px;">
                  <div style="font-size: 12px;">{{ $subcategory->sub_cat_name }}</div>
               </div>
            </a>
            @endforeach
         </div>
        <div class="col-md-9 fixedheight">
            <div class="row pt-3">
                @if($products->count())
                    @foreach ($products as $product)
                        @php
                            $defaultVariant = $product->variants->first();
                            $hasMultipleVariants = $product->variants->count() > 1;

                            // safely handle missing variant
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

                        <div class="col-md-4 pb-4 col-6">
                            <div class="item">
                                <div class="product-card2 p-0">

                                    {{-- Discount --}}
                                    @if ($defaultVariant && ($defaultVariant->variant_save_price_in_percent ?? 0) > 0)
                                        <span class="discount-label">
                                            {{ $defaultVariant->variant_save_price_in_percent }}% Off
                                        </span>
                                    @endif

                                    {{-- Image --}}
                                    <a href="{{ $product->is_physical ? route('productdetails', $product->slug) : 'javascript:void(0);' }}">
                                        <img src="{{ $product->featureImage ? asset('public/' . $product->featureImage->feature_image) : asset('public/assets/website/images/no-image.png') }}"
                                            class="product-image"
                                            alt="{{ $product->title }}">
                                    </a>

                                    {{-- Title --}}
                                    <div class="product-title cardpadding">{{ $product->title }}</div>

                                    {{-- Attributes --}}
                                    @if (!empty($firstAttr))
                                        <div class="product-info cardpadding">{{ $firstAttr }}</div>
                                    @endif

                                    {{-- Price + Cart --}}
                                    <div class="price-container cardpadding">
                                        @if ($defaultVariant)
                                            <span class="price">₹ {{ $defaultVariant->variant_selling_price ?? '--' }}</span>
                                            @if (!empty($defaultVariant->variant_actual_price) && $defaultVariant->variant_selling_price < $defaultVariant->variant_actual_price)
                                                <span class="original-price">₹ {{ $defaultVariant->variant_actual_price }}</span>
                                            @endif
                                        @else
                                            <span class="price">--</span>
                                        @endif

                                        <div class="qty-box"
                                            data-product-id="{{ $product->id }}"
                                            data-variant-id="{{ $variantId }}"
                                            data-key="{{ $key }}">

                                            {{-- Multiple Variants --}}
                                            @if ($hasMultipleVariants)
                                                <button class="add-btn d-flex position-relative" onclick="openPopup({{ $product->id }})">
                                                    Add <img src="{{ asset('public/assets/website/images/cart.svg') }}" class="ms-2">
                                                    <div class="cart-options text-black">{{ $product->variants->count() }} Options</div>
                                                </button>
                                            @else
                                                {{-- Single Variant --}}
                                                @if ($defaultVariant)
                                                    @if (!$inCart)
                                                        <button class="add-btn"
                                                                data-product-id="{{ $product->id }}"
                                                                data-variant-id="{{ $variantId }}">
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
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Store Info --}}
                                    <div class="store-info">
                                        <span>Ad </span>
                                        <span>{{ $product->vendor->business_name ?? 'Unknown Vendor' }}</span>
                                        <span>5 min</span>
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
        </div>


      </div>
   </div>

</section>
<!-- second section end  -->
@endsection
