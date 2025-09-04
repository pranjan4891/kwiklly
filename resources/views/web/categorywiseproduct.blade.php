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
                     <a href="{{ route('categorywiseproduct', [$category->id, $subcategory->id]) }}" class="text-decoration-none text-dark">
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
         <div class="col-md-9 fixedheight ">
            <div class="row pt-3">
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
               'variant_id' => $firstVariant->id,
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
                        @if ($product->is_physical)
                        <a href="{{ route('productdetails', $product->slug) }}">
                        <img src="{{ asset('public/' . $product->featureImage->feature_image) }}"
                           class="product-image" alt="{{ $product->title }}">
                        </a>
                        @else
                        <a href="javascript:void(0);">
                        <img src="{{ asset('public/' . $product->featureImage->feature_image) }}"
                           class="product-image" alt="{{ $product->title }}">
                        </a>
                        @endif
                        <div class="product-title cardpadding">{{ $product->title }}</div>
                        @if (!empty($firstAttr))
                        <div class="product-info cardpadding">{{ $firstAttr }}</div>
                        @endif
                        <div class="price-container cardpadding">
                           <span class="price">
                           ₹ {{ $defaultVariant->variant_selling_price ?? '--' }}
                           </span>
                           @if ($defaultVariant->variant_selling_price < $defaultVariant->variant_actual_price)
                           <span class="original-price">
                           ₹ {{ $defaultVariant->variant_actual_price }}
                           </span>
                           @endif
                           <div class="qty-box" data-product-id="{{ $product->id }}"
                              data-variant-id="{{ $firstVariant->id }}" data-key="{{ $key }}">
                              @if ($hasMultipleVariants)
                              <button class="add-btn" onclick="openPopup({{ $product->id }})">
                                 Add
                                 <img src="{{ asset('public/assets/website/images/cart.svg') }}" class="ms-2">
                                 <div class="cart-options text-black">{{ $product->variants->count() }} Options</div>
                              </button>
                              @else
                              @if (!$inCart)
                              <button class="add-btn" data-product-id="{{ $product->id }}"
                                 data-variant-id="{{ $firstVariant->id }}">
                              Add
                              <img src="{{ asset('public/assets/website/images/cart.svg') }}" class="ms-2">
                              </button>
                              @else
                              <div class="qty-container">
                                 <button class="qty-btn minus decrement-btn" data-key="{{ $key }}">−</button>
                                 <input type="text" class="qty-input quantity-input"
                                    value="{{ $quantity }}" readonly>
                                 <button class="qty-btn plus increment-btn" data-key="{{ $key }}">+</button>
                              </div>
                              @endif
                              @endif
                           </div>
                        </div>
                        <div class="store-info ">
                        </div>
                     </div>
                  </div>
               </div>
               @endforeach
               @else
               <div class="no-products text-center py-5">
                  {{-- <img src="{{ asset('public/assets/website/images/empty-box.png') }}" alt="No Products" width="100"> --}}
                  <h5 class="mt-3">Product not available</h5>
               </div>
               @endif
            </div>
         </div>
      </div>
   </div>
   <!-- pop up of add button  -->
   <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
      <div class="modal-dialog">
         <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title" id="productModalLabel">Mother Dairy Milk</h5>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
               <h6>Select Unit</h6>
               <div class="unit-list">
                  <div class="unit-item">
                     <img src="{{ asset('public/assets/website/images/category1.png')}}" class="unit-image" alt="Milk">
                     <span>100 ml</span>
                     <span class="pricepopup">
                     <span class="rupee-symbol">₹</span> 30
                     </span>
                     <span class="original-price">
                     <span class="rupee-symbol2">₹</span> 38
                     </span>
                     <button class="add-btn" onclick="convertToQty(this)">Add <img src="{{ asset('public/assets/website/images/cart.svg')}}" alt="" class="ms-1"></button>
                  </div>
                  <div class="unit-item">
                     <img src="{{ asset('public/assets/website/images/category1.png')}}" class="unit-image" alt="Milk">
                     <span>500 ml</span>
                     <span class="pricepopup">
                     <span class="rupee-symbol">₹</span> 30
                     </span>
                     <span class="original-price">
                     <span class="rupee-symbol2">₹</span> 38
                     </span>
                     <button class="add-btn" onclick="convertToQty(this)">Add <img src="{{ asset('public/assets/website/images/cart.svg')}}" alt="" class="ms-1"></button>
                  </div>
                  <div class="unit-item">
                     <img src="{{ asset('public/assets/website/images/category1.png')}}" class="unit-image" alt="Milk">
                     <span>500 ml</span>
                     <span class="pricepopup">
                     <span class="rupee-symbol">₹</span> 30
                     </span>
                     <span class="original-price">
                     <span class="rupee-symbol2">₹</span> 38
                     </span>
                     <button class="add-btn" onclick="convertToQty(this)">Add <img src="{{ asset('public/assets/website/images/cart.svg')}}" alt="" class="ms-1"></button>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>
<!-- second section end  -->
@endsection
