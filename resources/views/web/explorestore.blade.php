@extends('web.include.main')
@section('content')
<!-- first section start  -->
<style>
   .coupontext{
   font-size:16px;
   }
   .xyz-banner {
   color: white;
   position: relative;
   }
   .xyz-gradient
   {
   background: linear-gradient(to bottom, #00000008, #3b6939cf, #3b6939);
   padding: 30px 20px;
   }
   .xyz-white-button {
   background: white;
   color: red;
   border: none;
   border-radius: 5px;
   padding: 5px 15px;
   }
   .xyz-location-text {
   display: flex;
   align-items: center;
   gap: 10px;
   }
   .xyz-time-box {
   border: 1px solid white;
   border-radius: 20px;
   display: inline-block;
   padding: 5px 15px;
   margin-top: 10px;
   }
   .xyz-info-box {
   background: white;
   color: black;
   border-radius: 10px;
   padding: 15px;
   }
   .xyz-info-box img {
   width: 52px;
   height: 52px;
   border-radius: 50%;
   object-fit: cover;
   }
   .xyz-progress {
   height: 6px;
   background-color: #ccc;
   }
   .xyz-progress-bar {
   background-color: #3b6939;
   height: 100%;
   }
   .xyz-clickable-div {
   cursor: pointer;
   margin-top: 10px;
   }
   .xyz-right-text {
   text-align: right;
   font-size: 12px;
   margin-top: 10px;
   }
   .xyz-modal-overlay {
   position: fixed;
   top: 0;
   left: 0;
   right: 0;
   bottom: 0;
   background: rgba(0,0,0,0.5);
   display: none;
   justify-content: center;
   align-items: center;
   z-index: 9999;
   }
   /* Modal container */
   .xyz-modal {
   background: #fff;
   border-radius: 15px;
   max-width: 500px;
   width: 90%;
   padding: 20px;
   animation: slideDown 0.3s ease-out;
   max-height: 90vh;
   overflow-y: auto;
   }
   /* Coupon row styling */
   .xyz-coupon-row {
   background-color: #f2f7ff;
   border-radius: 10px;
   padding: 15px;
   margin-bottom: 15px;
   }
   .xyz-coupon-logo {
   height: 30px;
   margin-bottom: 5px;
   }
   .xyz-close {
   background: none;
   border: none;
   font-size: 24px;
   cursor: pointer;
   }
   .col7xyz
   {
   margin-top:40px;
   }
   .border12
   {
   border-radius:20px;
   }
   /* Mobile animation */
   @media (max-width: 768px) {
   .xyz-gradient {
   background: linear-gradient(to bottom, #00000008, #3b6939cf, #3b6939);
   padding: 15px 2px;
   }
   .coupontext{
   font-size:12px;
   }
   .xyz-modal {
   position: fixed;
   bottom: 0;
   width: 100%;
   border-radius: 20px 20px 0 0;
   animation: slideUpMobile 0.3s ease-out;
   margin-top: auto;
   max-height: 90%;
   }
   .col7xyz
   {
   margin-top:150px;
   }
   @keyframes slideUpMobile {
   from { transform: translateY(100%); }
   to { transform: translateY(0); }
   }
   .xyz-modal-overlay {
   align-items: flex-end;
   }
   }
   /* Desktop animation */
   @keyframes slideDown {
   from { opacity: 0; transform: translateY(-20px); }
   to { opacity: 1; transform: translateY(0); }
   }
</style>
<!-- Add this script to handle the progress bar -->
<script>
   document.addEventListener('DOMContentLoaded', function() {
       let cartTotal = {{ $cartTotal ?? 0 }};
       const minimumOrderValue = {{ $vendor->minimum_order_value ?? 0 }};

       let progressPercentage = 0;
       if (minimumOrderValue > 0) {
           progressPercentage = Math.min((cartTotal / minimumOrderValue) * 100, 100);
       }

       const progressBar = document.querySelector('.xyz-progress-bar.delivery-progress');
       if (progressBar) {
           progressBar.style.width = progressPercentage + '%';
       }

       const progressText = document.querySelector('.delivery-progress-text');
       if (progressText && minimumOrderValue > 0) {
           const amountNeeded = Math.max(minimumOrderValue - cartTotal, 0);
           progressText.innerHTML = `Add item worth ₹<b>${amountNeeded.toFixed(2)}</b> more to get free delivery`;
       }
   });
</script>

<section class="extrapadding">
   <div class="xyz-banner"
        style="background: url('{{ $vendor && $vendor->business_banner
                                    ? asset('public/' . $vendor->business_banner)
                                    : asset('public/assets/website/images/default-banner.jpg') }}')
               no-repeat center center / cover;">
      <div class="xyz-gradient">
         <div class="container">
            <div class="d-flex justify-content-between align-items-start mb-3 mt-0 mt-md-5">
               <img src="{{ asset('public/assets/website/images/fssai.png')}}" alt="Logo" height="40">
               <div class="text-end">
                  <button class="btn btn-light text-danger border12" onclick="showModal()"><b>Coupons</b></button>
               </div>
            </div>

            <div class="row">
               <!-- Left column -->
               <div class="col-md-7 pb-4 col7xyz">
                  <h3>{{ $vendor->business_name ?? 'No Store Found' }}</h3>

                  <div class="xyz-location-text">
                     <i class="fas fa-map-marker-alt"></i>
                     <span>{{ $vendor->business_address ?? 'Address not available' }}</span>
                  </div>

                  @php
                     $storeTime = [];
                     $currentTimeData = null;
                     $currentDay = date('l');

                     if ($vendor && $vendor->store_time) {
                         $decoded = json_decode($vendor->store_time, true);
                         if (is_array($decoded)) {
                             $storeTime = $decoded;
                             foreach ($storeTime as $time) {
                                 if (($time['day_name'] ?? '') === $currentDay) {
                                     $currentTimeData = $time;
                                     break;
                                 }
                             }
                         }
                     }
                  @endphp

                  <div class="xyz-time-box">
                     @if($currentTimeData && ($currentTimeData['status'] ?? "0") === "1")
                        {{ $currentDay }} {{ $currentTimeData['startTime'] ?? '' }} - {{ $currentTimeData['endTime'] ?? '' }}
                     @else
                        {{ $currentDay }} Closed
                     @endif
                  </div>
               </div>

               <!-- Right column -->
               <div class="col-md-5">
                  <div class="xyz-info-box">
                     <div class="coupontext delivery-progress-text">
                        @php
                           $cartTotal = $cartTotal ?? 0;
                           $minimumOrderValue = $vendor->minimum_order_value ?? 0;
                           $amountNeeded = max($minimumOrderValue - $cartTotal, 0);
                        @endphp
                        Add item worth ₹<b>{{ number_format($amountNeeded, 2) }}</b> more to get free delivery
                     </div>
                     <div class="xyz-progress mt-1">
                        <div class="xyz-progress-bar delivery-progress"
                             style="width: {{ $minimumOrderValue > 0 ? min(($cartTotal / $minimumOrderValue) * 100, 100) : 0 }}%">
                        </div>
                     </div>
                     <div class="xyz-right-text">*Progress Bar will reset in next order</div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>

<section>
   <div class="container mt-4 headingde">
      <h3>Inspiration for your order</h3>
      <div class="row">
         <div class="col-md-3">
            <div class="sidebarde">
               <ul>
                  @forelse($subcategories as $subcategory)
                     <li class="sidebar-itemde">
                        <img src="{{ asset('public/uploads/subcategories/'.$subcategory->image) }}" >
                        <a href="{{ $vendor && $category
                                    ? route('subcategory.products', [$vendor->id, $category->id, $subcategory->id])
                                    : 'javascript:void(0);' }}"
                           class="text-decoration-none text-dark" onclick="return redirectWithLocation(this.href)">
                           {{ $subcategory->sub_cat_name }}
                        </a>
                     </li>
                  @empty
                     <li>No categories available</li>
                  @endforelse
               </ul>
            </div>
         </div>

         <div class="col-md-9 fixedheight ">
            <div class="row pt-3">
               @if($products && $products->count())
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
                              'variant_id' => $firstVariant->id ?? 0,
                           ])->first();
                           $inCart = $cartItem !== null;
                           $quantity = $inCart ? $cartItem->quantity : 1;
                        } else {
                           $cart = session('cart', []);
                           $inCart = isset($cart[$key]);
                           $quantity = $inCart ? $cart[$key]['quantity'] : 1;
                        }

                        $attributes = json_decode($defaultVariant->attributes ?? '{}', true);
                        $firstAttr = collect($attributes)->first();
                     @endphp

                     <div class="col-md-4 pb-4 col-6">
                        <div class="item">
                           <div class="product-card2 p-0">
                              @if ($defaultVariant && $defaultVariant->variant_save_price_in_percent > 0)
                                 <span class="discount-label">{{ $defaultVariant->variant_save_price_in_percent }}% Off</span>
                              @endif

                              <a href="{{ $product->is_physical ? route('productdetails', $product->slug) : 'javascript:void(0);' }}">
                                 <img src="{{ $product->featureImage
                                                ? asset('public/' . $product->featureImage->feature_image)
                                                : asset('public/assets/website/images/no-image.png') }}"
                                      class="product-image" alt="{{ $product->title }}">
                              </a>

                              <div class="product-title cardpadding">{{ $product->title }}</div>

                              @if (!empty($firstAttr))
                                 <div class="product-info cardpadding">{{ $firstAttr }}</div>
                              @endif

                              <div class="price-container cardpadding">
                                 <span class="price">
                                    ₹ {{ $defaultVariant->variant_selling_price ?? '--' }}
                                 </span>
                                 @if ($defaultVariant && $defaultVariant->variant_selling_price < $defaultVariant->variant_actual_price)
                                    <span class="original-price">
                                       ₹ {{ $defaultVariant->variant_actual_price }}
                                    </span>
                                 @endif
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
