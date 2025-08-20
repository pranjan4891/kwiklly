<?php //include("include/header2.php")?>
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
<section class="extrapadding">   
<div class="xyz-banner" style="background: url('{{ asset('public/' . $vendor->business_banner) }}') no-repeat center center / cover;">
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
       <h3>{{$vendor->business_name}} </h3>
      <div class="xyz-location-text">
        <i class="fas fa-map-marker-alt"></i>
        <span>{{$vendor->business_address}}</span>
      </div>

      @php
    $storeTime = $vendor->store_time; // no json_decode needed
    $currentDay = date('l');
    $currentTimeData = collect($storeTime)->firstWhere('day_name', $currentDay);
@endphp

<div class="xyz-time-box">
    @if($currentTimeData && $currentTimeData['status'] == "1")
        {{ $currentDay }} {{ $currentTimeData['startTime'] }} - {{ $currentTimeData['endTime'] }}
    @else
        {{ $currentDay }} Closed
    @endif
</div>


    </div>

    <!-- Right column -->
    <div class="col-md-5">
      <div class="xyz-info-box">
        <div class="d-flex align-items-center mb-2">
          <img src="{{ asset('public/assets/website/images/demo.png')}}" alt="Icon">
          <div class="ms-3 w-100">
            <div class="coupontext">Add item worth ₹<b>5000</b> to get free cook</div>
            <div class="xyz-progress mt-1">
              <div class="xyz-progress-bar" style="width: 40%"></div>
            </div>
            <p class="text-danger pt-2">Remove</p>
          </div>
        </div>

        <div class="xyz-clickable-div" data-state="default" onclick="changeText(this)">
            <div class="coupontext">Add item worth ₹<b>60</b> more to get free delivery</div>
            <div class="xyz-progress mt-1">
              <div class="xyz-progress-bar" style="width: 80%"></div>
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

<div class="xyz-modal-overlay" id="couponModal">
    <div class="xyz-modal">
      <!-- Header -->
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0"><b>Coupons</b></h5>
        <button class="xyz-close" onclick="hideModal()">&times;</button>
      </div>
  
      <!-- Coupon Row 1 -->
      <div class="xyz-coupon-row row m-2">
        <div class="col-6">
          <h6><strong>20% OFF</strong></h6>
          <div style="color: green;">MAX ₹200</div>
          <small>Holi Week Discount</small>
        </div>
        <div class="col-6 text-end">
          <img src="images/klogo.png" alt="logo" class="xyz-coupon-logo">
          <div><small>COUPON EXPIRES 23/05</small></div>
        </div>
      </div>
  
      <!-- Coupon Row 2 -->
      <div class="xyz-coupon-row row m-2">
        <div class="col-6">
          <h6><strong>20% OFF</strong></h6>
          <div style="color: green;">MAX ₹200</div>
          <small>Holi Week Discount</small>
        </div>
        <div class="col-6 text-end">
          <img src="images/klogo.png" alt="logo" class="xyz-coupon-logo">
          <div><small>COUPON EXPIRES 23/05</small></div>
        </div>
      </div>
  
      <!-- Coupon Row 3 -->
      <div class="xyz-coupon-row row m-2">
        <div class="col-6">
          <h6><strong>20% OFF</strong></h6>
          <div style="color: green;">MAX ₹200</div>
          <small>Holi Week Discount</small>
        </div>
        <div class="col-6 text-end">
          <img src="images/klogo.png" alt="logo" class="xyz-coupon-logo">
          <div><small>COUPON EXPIRES 23/05</small></div>
        </div>
      </div>
  
      <!-- Coupon Row 4 -->
      <div class="xyz-coupon-row row m-2">
        <div class="col-6">
          <h6><strong>20% OFF</strong></h6>
          <div style="color: green;">MAX ₹200</div>
          <small>Holi Week Discount</small>
        </div>
        <div class="col-6 text-end">
          <img src="images/klogo.png" alt="logo" class="xyz-coupon-logo">
          <div><small>COUPON EXPIRES 23/05</small></div>
        </div>
      </div>
  
    </div>
  </div>


</section>
<!-- first section end  -->

<!-- second section start  -->
<section>
<div class="container mt-4 headingde">
    <h3>Inspiration for your order</h3>
    <div class="row">
        <div class="col-md-3">
            <div class="sidebarde">
                {{-- <ul>
                    <li class="sidebar-itemde active"><img src="{{ asset('public/assets/website/images/small1.png')}}">Snacks & Branded Foods</li>
                    <li class="sidebar-itemde"><img src="{{ asset('public/assets/website/images/small2.png')}}">Dry Fruits</li>
                    <li class="sidebar-itemde"><img src="{{ asset('public/assets/website/images/small3.png')}}">Meat & Fish</li>
                    <li class="sidebar-itemde"><img src="{{ asset('public/assets/website/images/small4.png')}}">Food Grains & Daily</li>
                    <li class="sidebar-itemde"><img src="{{ asset('public/assets/website/images/small5.png')}}">Bakery</li>
                    <li class="sidebar-itemde"><img src="{{ asset('public/assets/website/images/small6.png')}}">Masala</li>
                    <li class="sidebar-itemde"><img src="{{ asset('public/assets/website/images/small7.png')}}">Cereals & Breakfast</li>
                    <li class="sidebar-itemde"><img src="{{ asset('public/assets/website/images/small8.png')}}">Milk & Bread</li>
                    <li class="sidebar-itemde"><img src="{{ asset('public/assets/website/images/small9.png')}}">Oil & Ghee</li>
                </ul> --}}
                <ul>
                    @foreach($subcategories as $subcategory) 
                      
                    <li class="sidebar-itemde">
                            <img src="{{ asset('public/assets/website/images/small1.png') }}">
                            <a href="{{ route('subcategory.products', [$vendor->id, $category->id, $subcategory->id]) }}" class="text-decoration-none text-dark">
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
                    <a href="{{ route('subcategory.products', [$vendor->id, $category->id, $subcategory->id]) }}" class="d-inline-block text-center px-2 text-decoration-none text-dark" style="width: 100px;">
                        <div class="sidebar-itemde">
                            <img src="{{ asset('public/assets/website/images/small1.png') }}" alt="" style="width: 50px; height: 50px;">
                            <div style="font-size: 12px;">{{ $subcategory->sub_cat_name }}</div>
                        </div>
                    </a>
                @endforeach
            </div>
        {{-- <div class="mobile-sidebar">
            <div class="sidebar-itemde active"><img src="{{ asset('public/assets/website/images/small1.png')}}"> Snacks & Branded Food</div>
            <div class="sidebar-itemde"><img src="{{ asset('public/assets/website/images/small2.png')}}"> Dry Fruits</div>
            <div class="sidebar-itemde"><img src="{{ asset('public/assets/website/images/small3.png')}}"> Meat & Fish</div>
            <div class="sidebar-itemde"><img src="{{ asset('public/assets/website/images/small4.png')}}"> Food Grains & Daily</div>
            <div class="sidebar-itemde"><img src="{{ asset('public/assets/website/images/small5.png')}}"> Bakery</div>
            <div class="sidebar-itemde"><img src="{{ asset('public/assets/website/images/small6.png')}}"> Masala</div>
            <div class="sidebar-itemde"><img src="{{ asset('public/assets/website/images/small7.png')}}"> Cereals & Breakfast</div>
            <div class="sidebar-itemde"><img src="{{ asset('public/assets/website/images/small8.png')}}"> Milk & Bread</div>
            <div class="sidebar-itemde"><img src="{{ asset('public/assets/website/images/small9.png')}}"> Oil & Ghee</div>
        </div> --}}
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

                        <a href="{{ route('productdetails', $product->slug) }}">
                            <img src="{{ asset('public/' . $product->featureImage->feature_image) }}"
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

            {{-- <div class="col-md-4 pb-4 col-6">
                <div class="item">
                    <div class="product-card2 p-0">
                        <span class="discount-label">20% Off</span>
                        <a href="{{route('productdetails')}}"><img src="{{ asset('public/assets/website/images/product11.png')}}" class="product-image" alt="Milk"></a>
                        <div class="product-title cardpadding">Mother Dairy Toned Milk</div>
                        <div class="product-info cardpadding">500 ml</div>
                        <div class="price-container cardpadding">
                            <span class="price">
                                <span class="rupee-symbol">₹</span> 30
                            </span>
                            <span class="original-price">
                                <span class="rupee-symbol2">₹</span> 38
                            </span>                        
                            <button class="add-btn d-flex flex-column align-items-center position-relative" onclick="openPopup()">
                                <div class="d-flex align-items-center">
                                    Add
                                    <img src="{{ asset('public/assets/website/images/cart.svg')}}" alt="" class="ms-2">
                                </div>
                                <div class="cart-options text-black">5 Options</div>
                            </button>
                        </div>
                        <div class="store-info ">
                            
                        </div>
                    </div>
                </div>
            </div> --}}
            {{-- @foreach($products as $product)
            <div class="col-md-4 pb-4 col-6">
                <div class="item">
                    <div class="product-card2 p-0">
                        @if($product->discount > 0)
                            <span class="discount-label">{{ $product->discount }}% Off</span>
                        @endif
                        <a href="{{ route('productdetails', $product->id) }}">
                            <img src="{{ asset('storage/products/' . $product->image) }}" class="product-image" alt="{{ $product->name }}">
                        </a>
                        <div class="product-title cardpadding">{{ $product->name }}</div>
                        <div class="product-info cardpadding">{{ $product->unit }}</div>
                        <div class="price-container cardpadding">
                            <span class="price"><span class="rupee-symbol">₹</span>{{ $product->selling_price }}</span>
                            @if($product->original_price > $product->selling_price)
                                <span class="original-price"><span class="rupee-symbol2">₹</span>{{ $product->original_price }}</span>
                            @endif
                            <button class="add-btn">Add <img src="{{ asset('public/assets/website/images/cart.svg') }}" class="ms-2"></button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach --}}


            {{-- <div class="col-md-4 pb-4 col-6">
                <div class="item">
                    <div class="product-card2 p-0">
                        <span class="discount-label">20% Off</span>
                        <a href="{{route('productdetails')}}"><img src="{{ asset('public/assets/website/images/product12.png')}}" class="product-image" alt="Milk"></a>
                        <div class="product-title cardpadding">Mother Dairy Toned Milk</div>
                        <div class="product-info cardpadding">500 ml</div>
                        <div class="price-container cardpadding">
                        <span class="price">
                                <span class="rupee-symbol">₹</span> 30
                            </span>
                            <span class="original-price">
                                <span class="rupee-symbol2">₹</span> 38
                            </span>                        
                            <button class="add-btn" onclick="convertToQty(this)">Add <img src="{{ asset('public/assets/website/images/cart.svg')}}" alt="" class="ms-2"></button>
                        </div>
                        <div class="store-info ">
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 pb-4 col-6">
                <div class="item">
                    <div class="product-card2 p-0">
                        <span class="discount-label">20% Off</span>
                        <a href="{{route('productdetails')}}"><img src="{{ asset('public/assets/website/images/product11.png')}}" class="product-image" alt="Milk"></a>
                        <div class="product-title cardpadding">Mother Dairy Toned Milk</div>
                        <div class="product-info cardpadding">500 ml</div>
                        <div class="price-container cardpadding">
                            <span class="price">
                                <span class="rupee-symbol">₹</span> 30
                            </span>
                            <span class="original-price">
                                <span class="rupee-symbol2">₹</span> 38
                            </span>                        
                            <button class="add-btn d-flex flex-column align-items-center position-relative" onclick="openPopup()">
                                <div class="d-flex align-items-center">
                                    Add
                                    <img src="{{ asset('public/assets/website/images/cart.svg')}}" alt="" class="ms-2">
                                </div>
                                <div class="cart-options text-black">5 Options</div>
                            </button>
                        </div>
                        <div class="store-info ">
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 pb-4 col-6">
                <div class="item">
                    <div class="product-card2 p-0">
                        <span class="discount-label">20% Off</span>
                        <a href="{{route('productdetails')}}"><img src="{{ asset('public/assets/website/images/product12.png')}}" class="product-image" alt="Milk"></a>
                        <div class="product-title cardpadding">Mother Dairy Toned Milk</div>
                        <div class="product-info cardpadding">500 ml</div>
                        <div class="price-container cardpadding">
                        <span class="price">
                                <span class="rupee-symbol">₹</span> 30
                            </span>
                            <span class="original-price">
                                <span class="rupee-symbol2">₹</span> 38
                            </span>                        
                            <button class="add-btn" onclick="convertToQty(this)">Add <img src="{{ asset('public/assets/website/images/cart.svg')}}" alt="" class="ms-2"></button>
                        </div>
                        <div class="store-info ">
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 pb-4 col-6">
                <div class="item">
                    <div class="product-card2 p-0">
                        <span class="discount-label">20% Off</span>
                        <a href="{{route('productdetails')}}"><img src="{{ asset('public/assets/website/images/product11.png')}}" class="product-image" alt="Milk"></a>
                        <div class="product-title cardpadding">Mother Dairy Toned Milk</div>
                        <div class="product-info cardpadding">500 ml</div>
                        <div class="price-container cardpadding">
                            <span class="price">
                                <span class="rupee-symbol">₹</span> 30
                            </span>
                            <span class="original-price">
                                <span class="rupee-symbol2">₹</span> 38
                            </span>                        
                            <button class="add-btn d-flex flex-column align-items-center position-relative" onclick="openPopup()">
                                <div class="d-flex align-items-center">
                                    Add
                                    <img src="{{ asset('public/assets/website/images/cart.svg')}}" alt="" class="ms-2">
                                </div>
                                <div class="cart-options text-black">5 Options</div>
                            </button>
                        </div>
                        <div class="store-info ">
                            
                        </div>
                    </div>
                </div>
            </div> --}}
            {{-- <div class="col-md-4 pb-4 col-6">
                <div class="item">
                    <div class="product-card2 p-0">
                        <span class="discount-label">20% Off</span>
                        <a href="{{route('productdetails')}}"><img src="{{ asset('public/assets/website/images/product12.png')}}" class="product-image" alt="Milk"></a>
                        <div class="product-title cardpadding">Mother Dairy Toned Milk</div>
                        <div class="product-info cardpadding">500 ml</div>
                        <div class="price-container cardpadding">
                        <span class="price">
                                <span class="rupee-symbol">₹</span> 30
                            </span>
                            <span class="original-price">
                                <span class="rupee-symbol2">₹</span> 38
                            </span>                        
                            <button class="add-btn" onclick="convertToQty(this)">Add <img src="{{ asset('public/assets/website/images/cart.svg')}}" alt="" class="ms-2"></button>
                        </div>
                        <div class="store-info ">
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 pb-4 col-6">
                <div class="item">
                    <div class="product-card2 p-0">
                        <span class="discount-label">20% Off</span>
                        <a href="{{route('productdetails')}}"><img src="{{ asset('public/assets/website/images/product11.png')}}" class="product-image" alt="Milk"></a>
                        <div class="product-title cardpadding">Mother Dairy Toned Milk</div>
                        <div class="product-info cardpadding">500 ml</div>
                        <div class="price-container cardpadding">
                            <span class="price">
                                <span class="rupee-symbol">₹</span> 30
                            </span>
                            <span class="original-price">
                                <span class="rupee-symbol2">₹</span> 38
                            </span>                        
                            <button class="add-btn d-flex flex-column align-items-center position-relative" onclick="openPopup()">
                                <div class="d-flex align-items-center">
                                    Add
                                    <img src="{{ asset('public/assets/website/images/cart.svg')}}" alt="" class="ms-2">
                                </div>
                                <div class="cart-options text-black">5 Options</div>
                            </button>
                        </div>
                        <div class="store-info ">
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 pb-4 col-6">
                <div class="item">
                    <div class="product-card2 p-0">
                        <span class="discount-label">20% Off</span>
                        <a href="{{route('productdetails')}}"><img src="{{ asset('public/assets/website/images/product12.png')}}" class="product-image" alt="Milk"></a>
                        <div class="product-title cardpadding">Mother Dairy Toned Milk</div>
                        <div class="product-info cardpadding">500 ml</div>
                        <div class="price-container cardpadding">
                        <span class="price">
                                <span class="rupee-symbol">₹</span> 30
                            </span>
                            <span class="original-price">
                                <span class="rupee-symbol2">₹</span> 38
                            </span>                        
                            <button class="add-btn" onclick="convertToQty(this)">Add <img src="{{ asset('public/assets/website/images/cart.svg')}}" alt="" class="ms-2"></button>
                        </div>
                        <div class="store-info ">
                            
                        </div>
                    </div>
                </div>
            </div> --}}
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