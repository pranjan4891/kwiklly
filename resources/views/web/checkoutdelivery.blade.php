<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Kwiklly</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
        <link rel="stylesheet" href="{{ asset('public/assets/website/CSS/checkoutdelivery.css')}}">
        <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
        <style>
            /* Ensure SweetAlert appears above coupon modal */
            .swal2-container {
                z-index: 99999999 !important;
            }
            .swal2-popup {
                z-index: 99999999 !important;
            }
            .swal2-backdrop-show {
                z-index: 99999998 !important;
            }
            /* Ensure coupon modal has lower z-index than SweetAlert */
            .xyz-modal-overlay {
                z-index: 9999999 !important;
            }
            
            /* Coupon Modal Styles - Match Home Page Design */
            .xyz-modal-overlay {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                display: none;
                justify-content: center;
                align-items: center;
                z-index: 9999999;
            }
            
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
            
            .xyz-modal h5 {
                font-size: 20px;
                font-weight: 700;
                margin-bottom: 20px;
                color: #000;
            }
            
            /* Close Button */
            .xyz-close {
                background: none;
                border: none;
                font-size: 24px;
                cursor: pointer;
            }
            
            /* Coupon Row Styling - Match Home Page */
            .xyz-coupon-row {
                background-color: #f2f7ff;
                border-radius: 10px;
                padding: 15px;
                margin-bottom: 15px;
            }
            
            .xyz-coupon-row h6 {
                font-size: 18px;
                font-weight: 700;
                color: #000;
                margin-bottom: 8px;
            }
            
            .xyz-coupon-row h6 strong {
                font-size: 20px;
                color: #000;
            }
            
            .xyz-coupon-row .col-7 {
                padding-right: 10px;
            }
            
            .xyz-coupon-row .col-5 {
                padding-left: 10px;
            }
            
            .xyz-coupon-row div[style*="color: #3B6939"],
            .xyz-coupon-row div[style*="color: green"] {
                color: #3B6939 !important;
                font-size: 13px;
                font-weight: 500;
                margin-bottom: 6px;
            }
            
            .xyz-coupon-row small {
                font-size: 12px;
                color: #666;
                display: block;
                margin-bottom: 4px;
            }
            
            .xyz-coupon-row small b {
                font-weight: 600;
                color: #000;
            }
            
            .xyz-coupon-row .text-end {
                text-align: right;
            }
            
            .xyz-coupon-row .text-end small {
                color: #333;
                font-weight: 500;
                font-size: 11px;
            }
            
            .xyz-coupon-logo {
                height: auto;
                max-width: 50px;
                margin-bottom: 10px;
                object-fit: contain;
            }
            
            .apply-coupon-btn {
                text-decoration: none !important;
                background-color: #E94412 !important;
                color: #fff !important;
                font-size: 14px !important;
                font-weight: 700 !important;
                padding: 10px 24px !important;
                border: none !important;
                border-radius: 50px !important;
                transition: all 0.3s ease !important;
                box-shadow: 0 2px 8px rgba(233, 68, 18, 0.2) !important;
                display: inline-block !important;
                cursor: pointer !important;
                pointer-events: auto !important;
                z-index: 99999999 !important;
                position: relative !important;
            }
            
            .apply-coupon-btn:hover {
                background-color: #d63a0f !important;
                transform: translateY(-1px) !important;
                box-shadow: 0 4px 12px rgba(233, 68, 18, 0.4) !important;
            }
            
            .apply-coupon-btn:active {
                transform: translateY(0) !important;
                box-shadow: 0 2px 6px rgba(233, 68, 18, 0.3) !important;
            }
            
            .xyz-coupon-applied-on {
                margin-top: 12px;
                padding-top: 10px;
                border-top: 1px solid #e0e0e0;
            }
            
            /* Modal Animation */
            @keyframes slideDown {
                from {
                    opacity: 0;
                    transform: translateY(-20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            
            /* Mobile improvements for coupon modal */
            @media (max-width: 768px) {
                .xyz-modal {
                    width: 95% !important;
                    max-width: 100% !important;
                    padding: 16px 14px !important;
                    margin: 10px !important;
                }
                
                .xyz-modal h5 {
                    font-size: 18px !important;
                    margin-bottom: 12px !important;
                }
                
                .xyz-coupon-row {
                    padding: 14px 12px !important;
                    margin-bottom: 12px !important;
                }
                
                .xyz-coupon-row .row {
                    margin: 0 !important;
                }
                
                .xyz-coupon-row .col-7,
                .xyz-coupon-row .col-5 {
                    padding-left: 8px !important;
                    padding-right: 8px !important;
                }
                
                .xyz-coupon-row h6 {
                    font-size: 18px !important;
                    margin-bottom: 6px !important;
                }
                
                .xyz-coupon-row h6 strong {
                    font-size: 20px !important;
                }
                
                .xyz-coupon-row div[style*="color: #3B6939"] {
                    font-size: 12px !important;
                }
                
                .xyz-coupon-row small {
                    font-size: 11px !important;
                }
                
                .xyz-coupon-logo {
                    max-width: 45px !important;
                    height: auto !important;
                    margin-bottom: 8px !important;
                }
                
                .apply-coupon-btn {
                    font-size: 13px !important;
                    padding: 10px 20px !important;
                    border-radius: 50px !important;
                }
                
                .apply-coupon-btn:hover {
                    background-color: #d63a0f !important;
                    transform: translateY(-1px) !important;
                    box-shadow: 0 4px 12px rgba(233, 68, 18, 0.4) !important;
                }
                
                .xyz-coupon-applied-on {
                    margin-top: 10px !important;
                    padding-top: 8px !important;
                }
                
                .xyz-coupon-applied-on small {
                    font-size: 10px !important;
                    line-height: 1.5 !important;
                }
            }
            
            @media (max-width: 480px) {
                .xyz-modal {
                    width: 98% !important;
                    padding: 14px 12px !important;
                }
                
                .xyz-coupon-row {
                    padding: 12px 10px !important;
                }
                
                .xyz-coupon-row .col-7 {
                    width: 100% !important;
                    margin-bottom: 12px;
                }
                
                .xyz-coupon-row .col-5 {
                    width: 100% !important;
                    text-align: left !important;
                }
                
                .xyz-coupon-row h6 strong {
                    font-size: 18px !important;
                }
                
                .xyz-coupon-logo {
                    max-width: 40px !important;
                }
                
                .apply-coupon-btn {
                    font-size: 12px !important;
                    padding: 8px 18px !important;
                    border-radius: 50px !important;
                }
            }
        </style>
       
        <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    </head>
  
    <body>

        <section>
            <div class="container px-3 px-md-4">
                <div class="row g-3">
                <!-- Steps Navigation -->
                <div class="col-12">
                <div class="d-flex justify-content-between align-items-center extracartmargin">
                    <div class="d-flex gap-4">
                        <div class="d-flex align-items-center step-box2 active-step">
                            <div class="step-circle">1</div>
                            <span class="ms-md-2 step-label fw-bold">Shopping Details</span>
                        </div>
                        <div class="d-flex align-items-center step-box2">
                            <div class="step-circle inactive">2</div>
                            <span class="ms-md-2 step-label text-secondary">Delivery Address</span>
                        </div>
                        <div class="d-flex align-items-center step-box2">
                            <div class="step-circle inactive">3</div>
                            <span class="ms-md-2 step-label text-secondary">Payment Details</span>
                        </div>
                    </div>
                    <!-- <div class="wallet-box d-flex align-items-center py-1 rounded text-white">
                        <i class="fa fa-wallet text-white me-2"></i> ₹<span id="wallet-balance">55</span>
                    </div> -->
                </div>
                </div>
                <hr style="border: 1px solid #D8C2BC;" class="my-3">

                <!-- LEFT SIDE: CART ITEMS, DELIVERY OPTIONS, COUPONS, BILL SUMMARY -->
                <div class="col-12 col-md-7 p-3 main-content-box">
                    {{-- Global Cook Progress (removed, now per vendor) --}}
                    <div class="cartItemsWrapper" id="cartItemsWrapper">
                        @foreach($groupedCart as $vendorKey => $vendorData)
                            @if(isset($vendorData['items']) && !empty($vendorData['items']))
                            @php
                                // Get the business_id from the correct location
                                $businessId = 0;

                                // Method 1: Check if business_id is directly in vendorData
                                if (isset($vendorData['business_id']) && $vendorData['business_id'] > 0) {
                                    $businessId = $vendorData['business_id'];
                                }
                                // Method 2: Get from first item
                                else if (isset($vendorData['items']) && count($vendorData['items']) > 0) {
                                    $firstItem = reset($vendorData['items']);
                                    $businessId = $firstItem['business_id'] ?? 0;
                                }

                                // Debug output
                                // echo "<!-- Vendor: $vendorKey, Business ID: $businessId -->";
                            @endphp

                            @if($businessId > 0)
                                <div class="vendor-section mb-4" id="vendor-{{ Str::slug($vendorKey) }}" data-vendor-id="{{ $businessId }}">
                            @else
                                <div class="vendor-section mb-4" id="vendor-{{ Str::slug($vendorKey) }}" data-vendor-id="0">
                            @endif

                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="gotostore mx-4">
                                        <h6 class="mb-0 fw-semibold">{{ $vendorKey }}</h6>
                                        <button class="toggle-cart-items-btn" data-vendor="{{ Str::slug($vendorKey) }}">
                                            Hide Items <i class="fa fa-chevron-up"></i>
                                        </button>
                                    </div>
                                    <button class="toggle-bill-summary-btn btn btn-sm" data-vendor="{{ Str::slug($vendorKey) }}">
                                        <i class="fa fa-chevron-down"></i>
                                    </button>
                                </div>

                                <div id="cartItems-{{ Str::slug($vendorKey) }}">
                                    {{-- Loop and show items --}}
                                    @foreach($vendorData['items'] as $key => $item)
                                    <div class="cart-item d-flex align-items-center justify-content-between border-bottom py-2 p-3">
                                        <div class="d-flex align-items-center totalimg">
                                            <img src="{{ $item['image'] }}" alt="{{ $item['title'] }}" style="width:50px;">
                                            <div class="mx-3">
                                                <p class="mb-0">{{ $item['title'] }}</p>
                                                <small class="text-success">
                                                    ₹{{ $item['price'] }}
                                                    @if($item['original_price'] > $item['price'])
                                                    <s class="text-muted">₹{{ $item['original_price'] }}</s>
                                                    @endif
                                                </small>
                                            </div>
                                        </div>
                                        <div class="input-group input-group-sm sidecartbutton" style="width: 90px;">
                                            <button class="btn btn-danger decrement-btn" data-key="{{ $key }}">-</button>
                                            <input type="text" class="form-control text-center quantity-input" value="{{ $item['quantity'] }}" disabled>
                                            <button class="btn btn-danger increment-btn" data-key="{{ $key }}">+</button>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>

                                {{-- Rest of your vendor section content --}}
                                <div class="my-3 p-3" id="progress-section-{{ $businessId }}" style="display: none;">
                                    <div class="xyz-info-box">
                                        <!--- Cook Progress (Conditional) --->
                                        <div id="cook-progress-section-{{ $businessId }}" style="display: none;">
                                            <div class="d-flex align-items-center mb-2">
                                                <img src="{{ asset('public/demo.png') }}" alt="Icon">
                                                <div class="ms-3 w-100">
                                                    <div id="cook-text-{{ $businessId }}">Checking eligibility for free Gift...</div>
                                                </div>
                                            </div>
                                            <div id="cook-clickable-div-{{ $businessId }}" class="xyz-clickable-div">
                                                <div id="cook-progress-text-{{ $businessId }}">Add item worth more to get service</div>
                                                <div class="xyz-progress mt-1">
                                                    <div class="xyz-progress-bar" id="cook-progress-bar-{{ $businessId }}" style="width: 0%"></div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!--- Delivery Progress (Conditional) --->
                                        <div id="delivery-progress-section-{{ $businessId }}" style="display: none;">
                                            <div class="d-flex align-items-center mb-2" style="margin-top: {{ isset($businessId) ? '12px' : '0' }};">
                                                <img src="{{ asset('public/demo.png') }}" alt="Icon">
                                                <div class="ms-3 w-100">
                                                    <div id="delivery-progress-title-{{ $businessId }}">Add item worth to get free delivery</div>
                                                </div>
                                            </div>
                                            <div class="xyz-clickable-div progress-text-{{ $businessId }}">
                                                <div id="delivery-progress-text-{{ $businessId }}">Add item worth ₹ more to get free delivery<i class="fa fa-angle-right" style="position: absolute; left: 50%;color:#4caf50;"></i></div>
                                                <div class="xyz-progress mt-1">
                                                    <div class="xyz-progress-bar" id="delivery-progress-bar-{{ $businessId }}" style="width: 0%"></div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="xyz-right-text" id="progress-footer-{{ $businessId }}" style="display: none; margin-top: 12px;">*Progress Bar will reset in next order</div>
                                    </div>
                                </div>

                                @php
                                    // Check if this vendor has coupons
                                    $vendorHasCoupons = false;
                                    if (isset($vendorData['business_id']) && $vendorData['business_id'] > 0) {
                                        $vendorCouponsCheck = \App\Models\Coupon::where('created_by_id', $vendorData['business_id'])
                                            ->where('created_by_type', 'vendor')
                                            ->where('is_active', 1)
                                            ->where('is_deleted', 0)
                                            ->exists();
                                        $vendorHasCoupons = $vendorCouponsCheck || (isset($vendorCoupons[$vendorData['business_id']]) && !empty($vendorCoupons[$vendorData['business_id']]));
                                    }
                                @endphp
                                @if($vendorHasCoupons)
                                @php
                                    $vendorBusinessId = $vendorData['business_id'] ?? 0;
                                @endphp
                                <div class="mb-3" id="coupons-section-{{ $vendorBusinessId }}">
                                    <button id="coupon-btn-{{ $vendorBusinessId }}" class="coupon-view-btn w-100" type="button" data-vendor-id="{{ $vendorBusinessId }}" onclick="showModal({{ $vendorBusinessId }})" style="background: #f5f5f5; border: none; padding: 12px 15px; border-radius: 8px; display: flex; align-items: center; justify-content: space-between; cursor: pointer;">
                                        <div class="d-flex align-items-center">
                                            <i class="fa-solid fa-money-bill me-2" style="color: #666;"></i>
                                            <span style="color: #333; font-weight: 500;">View Coupons & Offers</span>
                                        </div>
                                        <i class="fa fa-angle-right" style="color: #E94412;"></i>
                                    </button>
                                </div>
                                @endif
                                
                                {{-- Applied Coupon Details - Separate div below the button --}}
                                @php
                                    $appliedCoupon = isset($vendorCoupons[$vendorData['business_id']]) && !empty($vendorCoupons[$vendorData['business_id']]) ? $vendorCoupons[$vendorData['business_id']] : null;
                                @endphp
                                @if($appliedCoupon)
                                <div class="mb-3" id="applied-coupon-section-{{ $vendorData['business_id'] ?? 0 }}">
                                    <div class="applied-coupon-card" style="background: #fff; border: 1px solid #e0e0e0; border-radius: 12px; padding: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                                        <div class="row g-2">
                                            <div class="col-12 col-md-7">
                                                <div class="d-flex align-items-center mb-2">
                                                    <span style="color: #4caf50; font-weight: 600; font-size: 14px;">Discount</span>
                                                    <span style="color: #4caf50; font-weight: 700; font-size: 18px; margin-left: 8px;">₹{{ $appliedCoupon['discount'] ?? 0 }}</span>
                                                </div>
                                                <div style="font-weight: 600; color: #333; margin-bottom: 3px; font-size: 14px;">{{ $appliedCoupon['code'] ?? '' }}</div>
                                                @if(isset($appliedCoupon['coupon_type']) && $appliedCoupon['coupon_type'])
                                                    <div style="color: #666; font-size: 11px; margin-bottom: 2px;">{{ $appliedCoupon['coupon_type'] }} discount</div>
                                                @endif
                                                @if(isset($appliedCoupon['applicable_items']) && $appliedCoupon['applicable_items'])
                                                    <div style="color: #666; font-size: 11px;">Applied to {{ $appliedCoupon['applicable_items'] }} items</div>
                                                @endif
                                            </div>
                                            <div class="col-12 col-md-5">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <img src="{{ asset('public/assets/website/images/logo.png') }}" alt="logo" style="width: 40px; height: 40px; object-fit: contain;">
                                                    <button type="button" onclick="clearCoupon({{ $vendorData['business_id'] ?? 0 }})" class="btn btn-sm" style="background: #fff; border: 1.5px solid #dc3545; color: #dc3545; border-radius: 6px; padding: 5px 14px; font-size: 11px; font-weight: 500; white-space: nowrap; flex-shrink: 0;">Remove</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @else
                                <div class="mb-3" id="applied-coupon-section-{{ $vendorData['business_id'] ?? 0 }}" style="display: none;"></div>
                                @endif

                                <div class="d-flex justify-content-between align-items-center bg-light p-2 rounded mb-3 wallet-box p-3" style="display: none !important;">
                                    <div>
                                        <span><i class="fa fa-wallet me-1"></i> Kwiklly Points</span>
                                    </div>
                                    <button class="btn btn-outline-success btn-sm">Use ₹5</button>
                                </div>

                                <div class="d-flex align-items-center p-3">
                                    <h6 class="fw-bold mb-0">Bill Summary</h6>
                                    <button class="toggle-bill-summary-btn btn btn-sm ms-2" data-vendor="{{ Str::slug($vendorKey) }}">
                                        <i class="fa fa-chevron-down"></i>
                                    </button>
                                </div>

                                <div class="pt-2 mt-2 p-3 d-none" id="billSummary-{{ Str::slug($vendorKey) }}">
                                    <ul class="list-unstyled small">
                                        <li class="d-flex justify-content-between">
                                            <span>Item charge</span><span>₹380 <s class="text-muted">₹332</s></span>
                                        </li>
                                        <li class="d-flex justify-content-between">
                                            <span>Delivery Charges</span><span>₹20</span>
                                        </li>
                                        <li class="d-flex justify-content-between">
                                            <span>Coupon Discount</span><span class="text-success">- ₹40</span>
                                        </li>
                                        <li class="d-flex justify-content-between" style="display: none !important;">
                                            <span>Wallet Discount</span><span class="text-success">- ₹5</span>
                                        </li>
                                        <li>
                                            <div class="ordersummary-row grandtotal-row">
                                                <span><strong>Grand Total</strong></span>
                                                <div class="text-end">
                                                    <strong>₹400 <span class="final-price">₹347</span></strong><br>
                                                    <span class="save-box">Saved ₹53</span>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <hr class="vendor-divider my-4" style="border: 1px solid #e0e0e0; margin: 20px 0;">
                            </div>
                            @endif
                            @endforeach
                    </div>
                </div>
                <!-- RIGHT SIDE: ORDER SUMMARY -->
                <div class="col-12 col-md-5">
                    <div class="ordersummary-box">
                        <h6 class="ordersummary-title">Order Summary</h6>
                        <div class="ordersummary-row">
                            <span>Items</span>
                            <span id="summary-items-count">0</span>
                        </div>
                        <div class="ordersummary-row">
                            <span>Sub Total</span>
                            <span id="summary-subtotal">₹0</span>
                        </div>
                        <div class="ordersummary-row">
                            <span>Delivery Fee</span>
                            <span id="summary-delivery">₹0</span>
                        </div>
                        <div class="ordersummary-row">
                            <span>Coupon Discount</span>
                            <span id="summary-coupon" class="text-green">-₹0</span>
                        </div>
                        <div class="ordersummary-row" style="display: none !important;">
                            <span>Wallet Discount</span>
                            <span id="summary-wallet" class="text-green">-₹0</span>
                        </div>
                        <div class="ordersummary-row grandtotal-row">
                            <span><strong>Grand Total</strong></span>
                            <div class="text-end">
                            <strong><span class="final-price">0</span></strong><br>
                            <span class="save-box">Saved <span id="saved-amount">0</span></span>
                            </div>
                        </div>
                        <!-- <div class="congrats-box" id="congrats-free-cook" style="display: block;">
                            <p><strong>Congratulations!! You got free cook </strong></p>
                            <small>by Aryan Grocery</small>
                        </div>
                        <div class="congrats-box" id="congrats-free-gift" style="display: block;">
                            <p><strong>Congratulations!! You got free gift</strong></p>
                            <small>by Chandresh Grocery</small>
                        </div> -->

                        {{-- <button onclick="window.location.href='{{ route('delivery.address') }}'" class="delivery-btn">
                        Choose Delivery Address
                        </button> --}}
                        <button onclick="processOrder()" class="delivery-btn">
                            Choose Delivery Address
                        </button>
                    </div>
                </div>
                </div>
            </div>
        </section>
        <div id="couponModalxyz" style="display: none;"></div>
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg fixed-top">
            <div class="container-fluid">
                <!-- Desktop: Logo + Location & Search -->
                <div class="d-flex align-items-center w-100 d-md-flex">
                <a class="navbar-brand" href="{{ route('home')}}">
                <img src="{{ asset('public/assets/website/images/logo.png')}}" alt="Logo">
                </a>
                </div>
            </div>
        </nav>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
        <!--------------- CUSTOM JAVASCRIPT START ----------------->
        {{-- <script src="{{asset('public/assets/website/JS/custom.js')}}"></script> --}}
        <!--------------- CUSTOM JAVASCRIPT END ----------------->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <!-- TOGGLE CART ITEMS -->
        <script type="text/javascript">

            document.addEventListener("DOMContentLoaded", function () {
                const toggleBtn = document.getElementById("toggleBillBtn4");
                const cartItems = document.getElementById("cartItems");

                // prevent error if elements are not found
                if (!toggleBtn || !cartItems) {
                    console.warn("Toggle button or cart items not found in DOM");
                    return;
                }

                toggleBtn.addEventListener("click", function () {
                    cartItems.classList.toggle("hidden");

                    if (cartItems.classList.contains("hidden")) {
                        toggleBtn.innerHTML = 'show items <i class="fa fa-chevron-up"></i>';
                    } else {
                        toggleBtn.innerHTML = 'hide items <i class="fa fa-chevron-down"></i>';
                    }
                });
            });

            // express time change js
            document.addEventListener("DOMContentLoaded", function () {
                const expressBtn = document.getElementById("expressBtn3");

                if (!expressBtn) {
                    console.warn("expressBtn3 not found in DOM");
                    return;
                }

                expressBtn.addEventListener("click", function () {
                    const isExpress = expressBtn.classList.contains("btn-success");

                    if (isExpress) {
                        expressBtn.classList.remove("btn-success");
                        expressBtn.classList.add("btn-outline-success");
                        expressBtn.innerHTML = '<i class="fa fa-times me-1"></i> Remove Express Delivery';
                    } else {
                        expressBtn.classList.remove("btn-outline-success");
                        expressBtn.classList.add("btn-success");
                        expressBtn.innerHTML = '<i class="fa fa-bolt me-1"></i> Express Delivery in 20 mins';
                    }
                });
            });

            document.addEventListener("DOMContentLoaded", function () {
                const toggleBtn = document.getElementById("deliveryToggle3");
                const deliveryOptions = document.getElementById("deliveryOptions3");

                if (!toggleBtn || !deliveryOptions) {
                    console.warn("deliveryToggle3 or deliveryOptions3 not found in DOM");
                    return;
                }

                toggleBtn.addEventListener("click", function () {
                    alert("jj");
                    deliveryOptions.style.display =
                        (deliveryOptions.style.display === "none" || deliveryOptions.style.display === "")
                            ? "block"
                            : "none";
                });
            });


            document.addEventListener("DOMContentLoaded", function () {
                const toggleBtn = document.getElementById("toggleBillBtn3");
                const bill = document.getElementById("billSummary3");

                if (!toggleBtn || !bill) {
                    console.warn("toggleBillBtn3 or billSummary3 not found in DOM");
                    return;
                }

                toggleBtn.addEventListener("click", function () {
                    if (bill.style.display === "none" || bill.style.display === "") {
                        bill.style.display = "block";
                        this.innerHTML = "<i class='fa fa-chevron-up'></i>";
                    } else {
                        bill.style.display = "none";
                        this.innerHTML = "<i class='fa fa-chevron-down'></i>";
                    }
                });
            });

            //   wallet
            document.addEventListener("DOMContentLoaded", function () {
                const walletBtn = document.getElementById("walletBtn3");
                const walletText = document.getElementById("walletText3");

                if (!walletBtn || !walletText) {
                    console.warn("walletBtn3 or walletText3 not found in DOM");
                    return;
                }

                walletBtn.addEventListener("click", function () {
                    if (walletBtn.textContent.includes("Use")) {
                        walletBtn.textContent = "Remove";
                        walletText.innerHTML = '<i class="fa fa-wallet me-1"></i> Added ₹5 in your wallet';
                    } else {
                        walletBtn.textContent = "Use ₹5";
                        walletText.innerHTML = '<i class="fa fa-wallet me-1"></i> Save money by kwikily wallet';
                    }
                });
            });

        </script>
        <!-- CART FUNCTIONALITY -->
        <script type="text/javascript">
            let vendorCoupons = @json($vendorCoupons);
            let vendorDeliveryCharges = {}; // Store delivery charges per vendor

            $(document).ready(function () {
                // Load cart data initially
                $.get("{{ route('cart.data') }}", function (res) {
                    vendorCoupons = res.vendor_coupons || {};
                    $('#cart-count').text(res.count);
                    renderCartSections(res.cart, vendorCoupons);
                    updateOrderSummary(res.cart, vendorCoupons);
                });

                // Increment Quantity
                $(document).on('click', '.increment-btn', function () {
                    const key = $(this).data('key');
                    $.post("{{ route('cart.increment') }}", {
                        _token: "{{ csrf_token() }}",
                        key: key
                    }, function (res) {
                        vendorCoupons = res.vendor_coupons || {};
                        $('#cart-count').text(res.count);
                        // Use selective update instead of full re-render
                        updateCartQuantities(res.cart, vendorCoupons);
                        
                        // Check if coupon was auto-removed
                        if (res.coupon_auto_removed) {
                            Swal.fire({
                                icon: 'info',
                                title: 'Coupon Removed',
                                text: res.coupon_removal_message || 'Coupon has been removed as it no longer meets the minimum order requirement.',
                                confirmButtonColor: '#E94412',
                                timer: 3000
                            });
                        }
                    }).fail(function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to update cart. Please try again.',
                            confirmButtonColor: '#E94412'
                        });
                    });
                });

                // Decrement Quantity
                $(document).on('click', '.decrement-btn', function () {
                    const key = $(this).data('key');

                    $.post("{{ route('cart.decrement') }}", {
                        _token: "{{ csrf_token() }}",
                        key: key
                    }, function (res) {
                        if (res.coupon_removal_warning) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Coupon Will Be Removed',
                                html: `The coupon "<strong>${res.coupon_code}</strong>" will be removed because subtotal for <strong>${res.vendor_name}</strong> will fall below ₹<strong>${res.min_order_amount}</strong>.<br><br>Do you want to proceed?`,
                                showCancelButton: true,
                                confirmButtonColor: '#E94412',
                                cancelButtonColor: '#6c757d',
                                confirmButtonText: 'Yes, Proceed',
                                cancelButtonText: 'Cancel',
                                reverseButtons: true
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // User confirmed, resend request with confirm=true
                                    $.post("{{ route('cart.decrement') }}", {
                                        _token: "{{ csrf_token() }}",
                                        key: key,
                                        confirm: true
                                    }, function (res2) {
                                        vendorCoupons = res2.vendor_coupons || {};
                                        $('#cart-count').text(res2.count);
                                        // Use selective update instead of full re-render
                                        updateCartQuantities(res2.cart, vendorCoupons);
                                        
                                        // Remove coupon display from page if coupon was removed
                                        const vendorId = res2.vendor_id || res.vendor_id;
                                        if (vendorId && (!vendorCoupons[vendorId] || !vendorCoupons[vendorId].code)) {
                                            const $appliedCouponSection = $(`#applied-coupon-section-${vendorId}`);
                                            if ($appliedCouponSection.length) {
                                                $appliedCouponSection.hide().html('');
                                            }
                                        }
                                        
                                        // Show coupon removed message
                                        Swal.fire({
                                            icon: 'info',
                                            title: 'Coupon Removed',
                                            text: `Coupon "${res.coupon_code}" has been removed.`,
                                            confirmButtonColor: '#E94412',
                                            timer: 3000
                                        });
                                    }).fail(function(xhr) {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error',
                                            text: 'Failed to update cart. Please try again.',
                                            confirmButtonColor: '#E94412'
                                        });
                                    });
                                }
                            });
                            return; // stop here if user cancels
                        }

                        // Normal update
                        vendorCoupons = res.vendor_coupons || {};
                        $('#cart-count').text(res.count);
                        // Use selective update instead of full re-render
                        updateCartQuantities(res.cart, vendorCoupons);
                        
                        // Check if coupon was auto-removed
                        if (res.coupon_auto_removed) {
                            // Remove coupon display from page
                            const vendorId = res.vendor_id || (Object.keys(vendorCoupons).length > 0 ? Object.keys(vendorCoupons)[0] : null);
                            if (vendorId) {
                                const $appliedCouponSection = $(`#applied-coupon-section-${vendorId}`);
                                if ($appliedCouponSection.length) {
                                    $appliedCouponSection.hide().html('');
                                }
                            }
                            
                            Swal.fire({
                                icon: 'info',
                                title: 'Coupon Removed',
                                text: res.coupon_removal_message || 'Coupon has been removed as it no longer meets the minimum order requirement.',
                                confirmButtonColor: '#E94412',
                                timer: 3000
                            });
                        }
                    }).fail(function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to update cart. Please try again.',
                            confirmButtonColor: '#E94412'
                        });
                    });
                });

                // Toggle vendor cart items
                $(document).on('click', '.toggle-cart-btn', function () {
                    const vendor = $(this).data('vendor');
                    $(`#cartItems-${vendor}`).toggleClass('d-none');
                    const visible = !$(`#cartItems-${vendor}`).hasClass('d-none');
                    $(this).html(visible ? 'Hide Items <i class="fa fa-chevron-up"></i>' : 'Show Items <i class="fa fa-chevron-down"></i>');
                });

                // Toggle bill summary
                $(document).on('click', '.toggle-bill-btn', function () {
                    const vendor = $(this).data('vendor');
                    $(`#billSummary-${vendor}`).toggleClass('d-none');
                });

                // Apply coupon handler function (can be called directly)
                window.applyCouponHandler = function(couponCode, vendorId) {
                    console.log('applyCouponHandler called:', { couponCode, vendorId });
                    
                    if (!vendorId || vendorId <= 0) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Invalid vendor ID. Please try again.',
                            confirmButtonColor: '#E94412'
                        });
                        return;
                    }
                    
                    if (!couponCode || couponCode.trim() === '') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Invalid coupon code. Please try again.',
                            confirmButtonColor: '#E94412'
                        });
                        return;
                    }
                    
                    vendorId = parseInt(vendorId);
                    couponCode = couponCode.trim();
                    
                    // Close modal
                    if (typeof window.hideModal === 'function') {
                        window.hideModal(vendorId);
                    } else {
                        $(`#couponModalxyz-${vendorId}`).fadeOut(200, function() {
                            $(this).css('display', 'none');
                        });
                    }
                    
                    // Show loading
                    Swal.fire({
                        title: 'Applying Coupon...',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    $.ajax({
                        url: '{{ route("coupon.apply") }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            code: couponCode,
                            vendor_id: vendorId
                        },
                        success: function (response) {
                            console.log('Coupon apply response:', response);
                            Swal.close();
                            
                            if (response.success) {
                                vendorCoupons = response.vendor_coupons || {};
                                
                                if (response.updated_cart && response.updated_cart.cart) {
                                    renderCartSections(response.updated_cart.cart, vendorCoupons);
                                    updateOrderSummary(response.updated_cart.cart, vendorCoupons);
                                } else {
                                    $.get("{{ route('cart.data') }}", function (res) {
                                        vendorCoupons = res.vendor_coupons || {};
                                        renderCartSections(res.cart, vendorCoupons);
                                        updateOrderSummary(res.cart, vendorCoupons);
                                    });
                                }
                                
                                setTimeout(() => {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Coupon Applied!',
                                        text: response.discount_text || response.message || 'Coupon has been applied successfully.',
                                        confirmButtonColor: '#E94412',
                                        timer: 3000
                                    });
                                }, 300);
                            } else {
                                setTimeout(() => {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Coupon Not Applied',
                                        text: response.message || 'Unable to apply coupon. Please check the coupon code and try again.',
                                        confirmButtonColor: '#E94412'
                                    });
                                }, 300);
                            }
                        },
                        error: function (xhr) {
                            console.error('Error applying coupon:', xhr);
                            Swal.close();
                            
                            let errorMessage = 'Error applying coupon. Please try again.';
                            
                            if (xhr.status === 401) {
                                errorMessage = 'Please login to apply coupons.';
                            } else if (xhr.status === 422) {
                                try {
                                    const response = JSON.parse(xhr.responseText);
                                    if (response.errors) {
                                        errorMessage = Object.values(response.errors).flat().join(', ');
                                    } else if (response.message) {
                                        errorMessage = response.message;
                                    }
                                } catch(e) {}
                            } else {
                                try {
                                    const response = JSON.parse(xhr.responseText);
                                    errorMessage = response.message || errorMessage;
                                } catch(e) {}
                            }
                            
                            setTimeout(() => {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: errorMessage,
                                    confirmButtonColor: '#E94412'
                                });
                            }, 300);
                        }
                    });
                };
                
                // Apply coupon button - FIXED (using event delegation)
                $(document).on('click', '.apply-coupon-btn', function (e) {
                    console.log('Apply button clicked - handler triggered');
                    e.preventDefault();
                    e.stopPropagation();

                    // Get coupon code and vendor ID from data attributes
                    let couponCode = $(this).attr('data-code') || $(this).data('code');
                    let vendorId = $(this).attr('data-vendor-id') || $(this).data('vendor-id');
                    
                    // Try to get vendorId from parent modal if not found
                    if (!vendorId) {
                        const $modal = $(this).closest('[id^="couponModalxyz-"]');
                        if ($modal.length) {
                            const modalId = $modal.attr('id');
                            const match = modalId.match(/couponModalxyz-(\d+)/);
                            if (match) {
                                vendorId = parseInt(match[1]);
                            }
                        }
                    }
                    
                    console.log('Apply coupon clicked:', { couponCode, vendorId, element: this });

                    // Validate vendorId and couponCode
                    if (!vendorId || vendorId <= 0) {
                        console.error('Invalid vendorId:', vendorId);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Invalid vendor ID. Please try again.',
                            confirmButtonColor: '#E94412'
                        });
                        return;
                    }
                    
                    if (!couponCode || couponCode.trim() === '') {
                        console.error('Invalid couponCode:', couponCode);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Invalid coupon code. Please try again.',
                            confirmButtonColor: '#E94412'
                        });
                        return;
                    }

                    // Convert vendorId to integer
                    vendorId = parseInt(vendorId);
                    couponCode = couponCode.trim();

                    // ✅ Close coupon modal immediately when apply button is clicked
                    if (typeof window.hideModal === 'function') {
                        window.hideModal(vendorId);
                    } else {
                        // Fallback: hide modal directly
                        $(`#couponModalxyz-${vendorId}`).fadeOut(200, function() {
                            $(this).css('display', 'none');
                        });
                    }

                    // Show loading indicator
                    Swal.fire({
                        title: 'Applying Coupon...',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    $.ajax({
                        url: '{{ route("coupon.apply") }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            code: couponCode,
                            vendor_id: vendorId
                        },
                        beforeSend: function() {
                            console.log('Sending coupon apply request...', { code: couponCode, vendor_id: vendorId });
                        },
                        success: function (response) {
                            console.log('Coupon apply response:', response);
                            Swal.close();
                            
                            if (response.success) {
                                // ✅ Update global coupon state with the new structure
                                vendorCoupons = response.vendor_coupons || {};

                                // ✅ Re-render sections with updated cart data
                                if (response.updated_cart && response.updated_cart.cart) {
                                    renderCartSections(response.updated_cart.cart, vendorCoupons);
                                    updateOrderSummary(response.updated_cart.cart, vendorCoupons);
                                } else {
                                    // Fallback: reload cart data
                                    $.get("{{ route('cart.data') }}", function (res) {
                                        vendorCoupons = res.vendor_coupons || {};
                                        renderCartSections(res.cart, vendorCoupons);
                                        updateOrderSummary(res.cart, vendorCoupons);
                                    });
                                }

                                // ✅ Show success message after modal closes
                                setTimeout(() => {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Coupon Applied!',
                                        text: response.discount_text || response.message || 'Coupon has been applied successfully.',
                                        confirmButtonColor: '#E94412',
                                        timer: 3000
                                    });
                                }, 300);
                            } else {
                                // Show error message after modal closes
                                setTimeout(() => {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Coupon Not Applied',
                                        text: response.message || 'Unable to apply coupon. Please check the coupon code and try again.',
                                        confirmButtonColor: '#E94412'
                                    });
                                }, 300);
                            }
                        },
                        error: function (xhr) {
                            console.error('Error applying coupon:', xhr);
                            console.error('Response text:', xhr.responseText);
                            Swal.close();
                            
                            let errorMessage = 'Error applying coupon. Please try again.';
                            
                            if (xhr.status === 401) {
                                errorMessage = 'Please login to apply coupons.';
                            } else if (xhr.status === 422) {
                                try {
                                    const response = JSON.parse(xhr.responseText);
                                    if (response.errors) {
                                        errorMessage = Object.values(response.errors).flat().join(', ');
                                    } else if (response.message) {
                                        errorMessage = response.message;
                                    }
                                } catch(e) {
                                    console.error('Error parsing validation response:', e);
                                }
                            } else {
                                try {
                                    const response = JSON.parse(xhr.responseText);
                                    errorMessage = response.message || errorMessage;
                                } catch(e) {
                                    console.error('Error parsing response:', e);
                                }
                            }
                            
                            // Show error message after modal closes
                            setTimeout(() => {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: errorMessage,
                                    confirmButtonColor: '#E94412',
                                    allowOutsideClick: true,
                                    allowEscapeKey: true
                                });
                            }, 300);
                        }
                    });
                });
            });

            window.timeSlots = @json($timeSlots);

            // ---------------------------
            // Delivery Toggle per Vendor
            // ---------------------------

            $(document).on('click', '.delivery-toggle-btn', function () {
                const vendorId = $(this).data('vendor-id');
                const $container = $(`#deliveryOptions-${vendorId}`);
                const $btn = $(this);

                // Close other vendors' slots
                $('.delivery-options-container').not($container).slideUp();
                // Reset other buttons text
                $('.delivery-toggle-btn').not($btn).html('<i class="fa fa-clock me-1"></i> Choose Delivery Time');

                if ($container.data('loaded')) {
                    const isVisible = $container.is(':visible');
                    $container.slideToggle();
                    // Update button text based on current state (before toggle)
                    if (isVisible) {
                        // Was visible, now hiding - show "Choose Delivery Time"
                        $btn.html('<i class="fa fa-clock me-1"></i> Choose Delivery Time');
                    } else {
                        // Was hidden, now showing - show "Hide Delivery Time"
                        $btn.html('<i class="fa fa-clock me-1"></i> Hide Delivery Time');
                    }
                    return;
                }

                // Build options dynamically from DB slots
                let slotOptions = `<option value="">Select Time</option>`;
                timeSlots.forEach(slot => {
                    slotOptions += `<option value="${slot.slot_time}">${slot.slot_time}</option>`;
                });

                // Express option (3rd radio) — initially disabled
                let expressHtml = `
                    <div class="form-check mb-2 mt-3">
                        <input type="radio"
                            name="deliveryOption-${vendorId}"
                            class="form-check-input me-2 express-radio"
                            id="express20-${vendorId}"
                            value="express"
                            disabled>
                        <label for="express20-${vendorId}" class="form-check-label fw-semibold express-label-${vendorId}">
                            Get order in 20 min
                        </label>
                    </div>
                `;

                let html = `
                    <div class="mt-3">

                        <!-- Option 1: Delivery in 30 minutes -->
                        <div class="form-check mb-2">
                            <input type="radio"
                                name="deliveryOption-${vendorId}"
                                class="form-check-input me-2 standard-delivery-radio"
                                id="delivery30-${vendorId}"
                                value="30min" required>
                            <label for="delivery30-${vendorId}" class="form-check-label fw-semibold">
                                Delivery in 30 min
                            </label>
                        </div>

                        <!-- Option 2: Custom Delivery Time -->
                        <div class="form-check mb-2">
                            <input type="radio"
                                name="deliveryOption-${vendorId}"
                                class="form-check-input me-2 custom-delivery-radio"
                                id="customDelivery-${vendorId}"
                                value="custom" required>
                            <label for="customDelivery-${vendorId}" class="form-check-label fw-semibold">
                                Custom Delivery Time
                            </label>
                        </div>
                        <div id="customInput-${vendorId}" class="mt-2" style="display:none;">
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <label class="form-label mb-1">Date</label>
                                    <input type="date"
                                        class="form-control form-control-sm custom-date"
                                        name="custom_date_${vendorId}"
                                        data-vendor-id="${vendorId}"
                                        min="${new Date().toISOString().split('T')[0]}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label mb-1">From</label>
                                    <select class="form-select form-select-sm custom-from"
                                            name="custom_start_time_${vendorId}"
                                            data-vendor-id="${vendorId}">
                                        ${slotOptions}
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label mb-1">To</label>
                                    <select class="form-select form-select-sm custom-to"
                                            name="custom_end_time_${vendorId}"
                                            data-vendor-id="${vendorId}">
                                        ${slotOptions}
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Option 3: Express 20 min -->
                        ${expressHtml}

                    </div>
                `;

                $container.html(html).data('loaded', true).show();
                // Update button text to "Hide Delivery Time" when shown by default
                $btn.html('<i class="fa fa-clock me-1"></i> Hide Delivery Time');

                // ✅ Fetch vendor's min_order_amount + delivery_charge dynamically
                $.ajax({
                    url: "{{ route('minimum.order.amount') }}",
                    type: "GET",
                    data: {
                        vendor_id: vendorId,
                        cart_total: parseFloat($(`#cartTotal-${vendorId}`).val()) || 0
                    },
                    success: function (res) {
                        if (res.success) {
                            const minOrder = parseFloat(res.min_order_amount);
                            let deliveryCharge = parseFloat(res.delivery_charge);
                            if (isNaN(deliveryCharge)) deliveryCharge = 0;

                            // update Express label
                            $(`.express-label-${vendorId}`).text(
                                `Get order in 20 min (Min ₹${minOrder}, Delivery ₹${deliveryCharge.toFixed(2)})`
                            );

                            const cartTotal = parseFloat($(`#cartTotal-${vendorId}`).val()) || 0;
                            const $expressRadio = $(`#express20-${vendorId}`);
                            const $delivery30 = $(`#delivery30-${vendorId}`);

                            // Always default to standard delivery (30 min)
                            $delivery30.prop("checked", true).prop("disabled", false);

                            if (cartTotal >= minOrder && minOrder > 0) {
                                // Free delivery, enable express option but don't auto-select
                                $expressRadio.prop("disabled", false).prop("checked", false);
                                deliveryCharge = 0; // free delivery
                            } else {
                                // keep express disabled
                                $expressRadio.prop("disabled", true).prop("checked", false);
                            }

                            // Store delivery charge for this vendor
                            vendorDeliveryCharges[vendorId] = deliveryCharge;

                            // update Delivery Charge in Bill Summary
                            $(`#deliveryCharge-${vendorId}`).text(`₹${deliveryCharge.toFixed(2)}`);

                            // ✅ recalc total charges
                            const subtotal = parseFloat($(`#subtotal-${vendorId}`).val()) || 0;
                            const coupon = parseFloat($(`#coupon-${vendorId}`).val()) || 0;
                            const totalCharges = subtotal + deliveryCharge - coupon;
                            $(`#totalCharge-${vendorId}`).text(`₹${totalCharges.toFixed(2)}`);

                            // Update the order summary with new delivery charges
                            updateOrderSummary(window.currentCart || {}, vendorCoupons);

                            // ✅ Conditionally show/hide progress sections
                            const hasMinOrder = minOrder && minOrder > 0;
                            const hasMinOrderForCook = res.min_order_for_cook && parseFloat(res.min_order_for_cook) > 0;
                            
                            // Show/hide delivery progress section
                            if (hasMinOrder) {
                                $(`#delivery-progress-section-${vendorId}`).show();
                            } else {
                                $(`#delivery-progress-section-${vendorId}`).hide();
                            }
                            
                            // Show/hide cook progress section
                            if (hasMinOrderForCook) {
                                const cartTotal = parseFloat($(`#cartTotal-${vendorId}`).val()) || 0;
                                updateCookProgress(vendorId, cartTotal, parseFloat(res.min_order_for_cook), res.dy_text);
                                $(`#cook-progress-section-${vendorId}`).show();
                            } else {
                                $(`#cook-progress-section-${vendorId}`).hide();
                            }
                            
                            // Show/hide main progress section and footer
                            if (hasMinOrder || hasMinOrderForCook) {
                                $(`#progress-section-${vendorId}`).show();
                                $(`#progress-footer-${vendorId}`).show();
                            } else {
                                $(`#progress-section-${vendorId}`).hide();
                            }
                            
                            // Update delivery progress if min order exists
                            if (hasMinOrder) {
                                const cartTotal = parseFloat($(`#cartTotal-${vendorId}`).val()) || 0;
                                const remaining = Math.max(minOrder - cartTotal, 0);
                                const progressPercent = minOrder > 0 ? Math.min((cartTotal / minOrder) * 100, 100) : 0;
                                
                                let textHtml = remaining > 0
                                    ? `Add items worth ₹<b>${remaining.toFixed(2)}</b> more to get free delivery`
                                    : `<span class="text-success fw-semibold">You unlocked FREE delivery 🎉</span>`;
                                
                                $(`#delivery-progress-text-${vendorId}`).html(textHtml);
                                $(`#delivery-progress-bar-${vendorId}`).css("width", progressPercent + "%");
                            }
                        }
                    },
                    error: function (xhr) {
                        console.error("Error fetching min order amount:", xhr.responseText);
                    }
                });
            });

            // ---------------------------
            // Selective Update Function (only updates quantities and amounts)
            // ---------------------------
            function updateCartQuantities(groupedCart, vendorCoupons = {}) {
                // Store the current cart for later use
                window.currentCart = groupedCart;
                
                // Store current cart structure
                const currentCartKeys = {};
                $('.vendor-section').each(function() {
                    const vendorId = $(this).data('vendor-id');
                    const keys = [];
                    $(this).find('.increment-btn').each(function() {
                        keys.push($(this).data('key'));
                    });
                    currentCartKeys[vendorId] = keys;
                });

                // Check if cart structure changed
                const newCartKeys = {};
                let structureChanged = false;
                
                $.each(groupedCart, function(businessName, items) {
                    const firstKey = Object.keys(items)[0];
                    const vendorId = items[firstKey]?.business_id;
                    newCartKeys[vendorId] = Object.keys(items);
                    
                    if (!currentCartKeys[vendorId] || 
                        JSON.stringify(currentCartKeys[vendorId].sort()) !== JSON.stringify(newCartKeys[vendorId].sort())) {
                        structureChanged = true;
                    }
                });

                // If structure changed, do full re-render
                if (structureChanged) {
                    renderCartSections(groupedCart, vendorCoupons);
                    return;
                }

                // Otherwise, do selective updates
                $.each(groupedCart, function(businessName, items) {
                    const firstKey = Object.keys(items)[0];
                    const vendorId = items[firstKey]?.business_id;
                    const vendorSlug = businessName.toLowerCase().replace(/\s+/g, '-').replace(/[^a-z0-9-]/g, '');
                    
                    $.each(items, function(key, item) {
                        // Update quantity input - find input that is sibling of increment/decrement button with same data-key
                        // Try multiple selectors to ensure we find the input
                        const $qtyInput = $(`.increment-btn[data-key="${key}"]`).siblings('.quantity-input')
                            .add($(`.decrement-btn[data-key="${key}"]`).siblings('.quantity-input'))
                            .first();
                        
                        if ($qtyInput.length) {
                            $qtyInput.val(item.quantity);
                        } else {
                            // Fallback: find by parent container
                            const $container = $(`.increment-btn[data-key="${key}"], .decrement-btn[data-key="${key}"]`).closest('.input-group');
                            const $fallbackInput = $container.find('.quantity-input');
                            if ($fallbackInput.length) {
                                $fallbackInput.val(item.quantity);
                            }
                        }
                    });

                    // Calculate vendor totals
                    let subtotal = 0;
                    let originalTotal = 0;
                    $.each(items, function(key, item) {
                        subtotal += item.price * item.quantity;
                        originalTotal += (item.original_price || item.price) * item.quantity;
                    });

                    const couponDiscount = vendorCoupons[vendorId]?.discount ?? 0;
                    const cartTotal = subtotal - couponDiscount;
                    
                    // Calculate saved amount
                    const savedAmount = originalTotal - subtotal;
                    
                    // Update hidden inputs
                    $(`#cartTotal-${vendorId}`).val(cartTotal.toFixed(2));
                    $(`#subtotal-${vendorId}`).val(subtotal.toFixed(2));
                    $(`#coupon-${vendorId}`).val(couponDiscount.toFixed(2));

                    // Update bill summary display - find the strong tag that shows total
                    const $vendorSection = $(`.vendor-section[data-vendor-id="${vendorId}"]`);
                    $vendorSection.find('.d-flex.align-items-center.p-3.mb-2 .fw-bold.ms-2').text(`₹${cartTotal.toFixed(2)}`);
                    
                    // Update item charge in bill summary
                    const $billSummary = $(`#billSummary-${vendorSlug}`);
                    if ($billSummary.length) {
                        // Update item charge
                        const itemChargeHtml = savedAmount > 0 
                            ? `₹${subtotal.toFixed(2)} <s class="text-muted">₹${originalTotal.toFixed(2)}</s>`
                            : `₹${subtotal.toFixed(2)}`;
                        $billSummary.find('li:first-child span:last-child').html(itemChargeHtml);
                        
                        // Update coupon discount (hide if 0 or not applied)
                        // Find coupon li - it could be at different positions depending on saved amount
                        let $couponLi = $billSummary.find('li').filter(function() {
                            return $(this).find('span:first-child').text().trim() === 'Coupon Discount';
                        });
                        if ($couponLi.length === 0) {
                            // Fallback: try index 2 (3rd li)
                            $couponLi = $billSummary.find('li').eq(2);
                        }
                        if (couponDiscount > 0 && vendorCoupons[vendorId]) {
                            $couponLi.show().find('span.text-success').text(`- ₹${couponDiscount.toFixed(2)}`);
                        } else {
                            $couponLi.hide();
                        }
                        
                        // Update applied coupon display section (separate from button)
                        const $appliedCouponSection = $(`#applied-coupon-section-${vendorId}`);
                        if ($appliedCouponSection.length) {
                            if (vendorCoupons[vendorId] && couponDiscount > 0) {
                                // Coupon is applied - show/update the coupon display
                                const coupon = vendorCoupons[vendorId];
                                const couponHtml = `
                                    <div class="applied-coupon-card" style="background: #fff; border: 1px solid #e0e0e0; border-radius: 12px; padding: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                                        <div class="row g-2">
                                            <div class="col-12 col-md-7">
                                                <div class="d-flex align-items-center mb-2">
                                                    <span style="color: #4caf50; font-weight: 600; font-size: 14px;">Discount</span>
                                                    <span style="color: #4caf50; font-weight: 700; font-size: 18px; margin-left: 8px;">₹${coupon.discount}</span>
                                                </div>
                                                <div style="font-weight: 600; color: #333; margin-bottom: 3px; font-size: 14px;">${coupon.code}</div>
                                                ${coupon.coupon_type ? `<div style="color: #666; font-size: 11px; margin-bottom: 2px;">${coupon.coupon_type} discount</div>` : ''}
                                                ${coupon.applicable_items ? `<div style="color: #666; font-size: 11px;">Applied to ${coupon.applicable_items} items</div>` : ''}
                                            </div>
                                            <div class="col-12 col-md-5">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <img src="{{ asset('public/assets/website/images/logo.png') }}" alt="logo" style="width: 40px; height: 40px; object-fit: contain; flex-shrink: 0;">
                                                    <button type="button" onclick="clearCoupon(${vendorId})" class="btn btn-sm" style="background: #fff; border: 1.5px solid #dc3545; color: #dc3545; border-radius: 6px; padding: 5px 14px; font-size: 11px; font-weight: 500; white-space: nowrap; flex-shrink: 0; margin-left: 10px;">Remove</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                `;
                                $appliedCouponSection.html(couponHtml).show();
                            } else {
                                // Coupon is removed - hide the coupon display
                                $appliedCouponSection.hide().html('');
                            }
                        }
                        
                        // Update saved amount
                        const $saveBox = $billSummary.find('.save-box');
                        if (savedAmount > 0) {
                            $saveBox.text(`Saved ₹${savedAmount.toFixed(0)}`).show();
                        } else {
                            $saveBox.hide();
                        }
                    }
                    
                    // Update grand total in bill summary
                    $(`#totalCharge-${vendorId}`).text(`₹${cartTotal.toFixed(2)}`);
                    
                    // Update delivery charges via AJAX (to check minimum order)
                    // Use IIFE to capture current values properly
                    (function(currentVendorId, currentCartTotal, currentVendorSlug, currentBusinessName) {
                        $.ajax({
                            url: "{{ route('minimum.order.amount') }}",
                            type: "GET",
                            data: {
                                vendor_id: currentVendorId,
                                cart_total: currentCartTotal
                            },
                            success: function (res) {
                                if (res.success) {
                                    // Get latest cart total from hidden input (in case it changed)
                                    const latestCartTotal = parseFloat($(`#cartTotal-${currentVendorId}`).val()) || currentCartTotal;
                                    
                                    let deliveryCharge = parseFloat(res.delivery_charge) || 0;
                                    const minOrderValue = parseFloat(res.min_order_amount) || 0;
                                    
                                    // If cart total >= min order, delivery is free
                                    if (latestCartTotal >= minOrderValue) {
                                        deliveryCharge = 0;
                                    }
                                    
                                    // Update delivery charge display
                                    $(`#deliveryCharge-${currentVendorId}`).text(`₹${deliveryCharge.toFixed(2)}`);
                                    
                                    // Update grand total with delivery charge
                                    const finalTotal = latestCartTotal + deliveryCharge;
                                    $(`#totalCharge-${currentVendorId}`).text(`₹${finalTotal.toFixed(2)}`);
                                    
                                    // Update saved amount after delivery charge calculation
                                    const $vendorSection = $(`.vendor-section[data-vendor-id="${currentVendorId}"]`);
                                    const $billSummary = $vendorSection.find(`#billSummary-${currentVendorSlug}`);
                                    if ($billSummary.length) {
                                        // Recalculate saved amount
                                        let originalTotal = 0;
                                        let subtotal = 0;
                                        $.each(groupedCart[currentBusinessName] || {}, function(key, item) {
                                            subtotal += item.price * item.quantity;
                                            originalTotal += (item.original_price || item.price) * item.quantity;
                                        });
                                        const savedAmount = originalTotal - subtotal;
                                        const $saveBox = $billSummary.find('.save-box');
                                        if (savedAmount > 0) {
                                            $saveBox.text(`Saved ₹${savedAmount.toFixed(0)}`).show();
                                        } else {
                                            $saveBox.hide();
                                        }
                                    }
                                    
                                    // Update delivery progress bar
                                    const remaining = Math.max(minOrderValue - latestCartTotal, 0);
                                    const progressPercent = minOrderValue > 0 ? Math.min((latestCartTotal / minOrderValue) * 100, 100) : 0;
                                    
                                    let textHtml = remaining > 0
                                        ? `Add items worth ₹<b>${remaining.toFixed(2)}</b> more to get free delivery`
                                        : `<span class="text-success fw-semibold">You unlocked FREE delivery 🎉</span>`;
                                    
                                    $(`#delivery-progress-text-${currentVendorId}`).html(textHtml);
                                    $(`#delivery-progress-bar-${currentVendorId}`).css("width", progressPercent + "%");
                                    
                                    // Update cook progress bar if data is available
                                    if (res.min_order_for_cook) {
                                        const cookThreshold = parseFloat(res.min_order_for_cook);
                                        const dyText = res.dy_text || 'free Gift';
                                        updateCookProgress(currentVendorId, latestCartTotal, cookThreshold, dyText);
                                    }
                                    
                                    // Update express delivery label
                                    $(`.express-label-${currentVendorId}`).text(
                                        `Get order in 20 min (Min ₹${minOrderValue}, Delivery ₹${deliveryCharge.toFixed(2)})`
                                    );
                                    
                                    // Enable/disable express delivery radio based on cart total
                                    const $expressRadio = $(`#express20-${currentVendorId}`);
                                    if ($expressRadio.length) {
                                        if (latestCartTotal >= minOrderValue) {
                                            $expressRadio.prop('disabled', false);
                                        } else {
                                            $expressRadio.prop('disabled', true);
                                        }
                                    }
                                    
                                }
                            },
                            error: function(xhr) {
                                console.error("Error updating progress bars for vendor " + currentVendorId + ":", xhr.responseText);
                            }
                        });
                    })(vendorId, cartTotal, vendorSlug, businessName);
                });

                // Update order summary initially (delivery charges will update via AJAX)
                updateOrderSummary(groupedCart, vendorCoupons);
            }

            // ---------------------------
            // Cart Section Render
            // ---------------------------
            function renderCartSections(groupedCart, vendorCoupons = {}) {
                // Store the current cart for later use
                window.currentCart = groupedCart;

                let html = '';

                if (groupedCart && Object.keys(groupedCart).length > 0) {
                    $.each(groupedCart, function (businessName, items) {
                        const vendorSlug = businessName.toLowerCase().replace(/\s+/g, '-');
                        let subtotal = 0;
                        let totalOriginal = 0;
                        const firstKey = Object.keys(items)[0];
                        const vendorId = items[firstKey]?.business_id;

                        html += `
                            <div class="vendor-section my-4 rounded p-3" data-vendor-id="${vendorId}">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0 fw-semibold">${businessName}</h6>
                                        <button class="toggle-cart-btn btn btn-sm text-primary" data-vendor="${vendorSlug}">
                                            Hide Items <i class="fa fa-chevron-down"></i>
                                        </button>
                                    </div>
                                    <div>
                                        <button class="btn btn-sm delivery-toggle-btn" data-vendor-id="${vendorId}">
                                            <i class="fa fa-clock me-1"></i> Choose Delivery Time
                                        </button>
                                    </div>
                                </div>
                                <div id="cartItems-${vendorSlug}" class="mt-3">

                                    <div id="deliveryOptions-${vendorId}" class="delivery-options-container mt-2"></div>
                        `;

                        // Cart items
                        $.each(items, function (key, item) {
                            subtotal += item.price * item.quantity;
                            totalOriginal += item.original_price * item.quantity;
                            html += `
                                <div class="cart-item d-flex align-items-center justify-content-between border-bottom py-2">
                                    <div class="d-flex align-items-center totalimg">
                                        <img src="${item.image}" alt="${item.title}" width="50">
                                        <div class="mx-3">
                                            <p class="mb-0">${item.title}</p>
                                            <small class="text-success">₹${item.price}</small>
                                            ${item.price < item.original_price ? `<s class="text-muted">₹${item.original_price}</s>` : ''}
                                        </div>
                                    </div>
                                    <div class="input-group input-group-sm sidecartbutton" style="width: 90px;">
                                        <button class="btn btn-danger decrement-btn" data-key="${key}">-</button>
                                        <input type="text" class="form-control text-center quantity-input" value="${item.quantity}" readonly>
                                        <button class="btn btn-danger increment-btn" data-key="${key}">+</button>
                                    </div>
                                </div>
                            `;
                        });

                        const couponDiscount = vendorCoupons[vendorId]?.discount ?? 0;

                        // Hidden inputs for vendor cart totals
                        html += `
                            <input type="hidden" id="cartTotal-${vendorId}" value="${(subtotal - couponDiscount).toFixed(2)}">
                            <input type="hidden" id="subtotal-${vendorId}" value="${subtotal.toFixed(2)}">
                            <input type="hidden" id="coupon-${vendorId}" value="${couponDiscount.toFixed(2)}">
                        `;

                        // Progress section (initially hidden, will be shown based on backend response)
                        html += `
                                </div>
                                <div class="my-3" id="progress-section-${vendorId}" style="display: none;">
                                    <div class="xyz-info-box">
                                        <!--- Cook Progress (Conditional) --->
                                        <div id="cook-progress-section-${vendorId}" style="display: none;">
                                            <div class="d-flex align-items-center mb-2">
                                                <img src="{{ asset('public/demo.png') }}" alt="Icon" style="width: 24px; height: 24px;">
                                                <div class="ms-3 w-100">
                                                    <div id="cook-text-${vendorId}">Checking eligibility for free Gift...</div>
                                                </div>
                                            </div>
                                            <div id="cook-clickable-div-${vendorId}" class="xyz-clickable-div">
                                                <div id="cook-progress-text-${vendorId}">Add item worth more to get service</div>
                                                <div class="xyz-progress mt-1">
                                                    <div class="xyz-progress-bar" id="cook-progress-bar-${vendorId}" style="width: 0%"></div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!--- Delivery Progress (Conditional) --->
                                        <div id="delivery-progress-section-${vendorId}" style="display: none;">
                                            <div class="d-flex align-items-center mb-2" style="margin-top: 12px;">
                                                <img src="{{ asset('public/demo.png') }}" alt="Icon" style="width: 24px; height: 24px;">
                                                <div class="ms-3 w-100">
                                                    <div id="delivery-progress-title-${vendorId}">Add item worth to get free delivery</div>
                                                </div>
                                            </div>
                                            <div class="xyz-clickable-div progress-text-${vendorId}">
                                                <div id="delivery-progress-text-${vendorId}">Add item worth ₹ more to get free delivery<i class="fa fa-angle-right" style="position: absolute; left: 50%;color:#4caf50;"></i></div>
                                                <div class="xyz-progress mt-1">
                                                    <div class="xyz-progress-bar" id="delivery-progress-bar-${vendorId}" style="width: 0%"></div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="xyz-right-text" id="progress-footer-${vendorId}" style="display: none; margin-top: 12px;">*Progress Bar will reset in next order</div>
                                    </div>
                                </div>
                        `;

                        // Coupons section - View Coupons button (separate from applied coupon)
                        html += `
                            <div class="mb-3" id="coupons-section-${vendorId}" style="display: none;">
                                <button id="coupon-btn-${vendorId}" class="coupon-view-btn w-100" type="button" data-vendor-id="${vendorId}" onclick="showModal(${vendorId})" style="background: #f5f5f5; border: none; padding: 12px 15px; border-radius: 8px; display: flex; align-items: center; justify-content: space-between; cursor: pointer;">
                                    <div class="d-flex align-items-center">
                                        <i class="fa-solid fa-money-bill me-2" style="color: #666;"></i>
                                        <span style="color: #333; font-weight: 500;">View Coupons & Offers</span>
                                    </div>
                                    <i class="fa fa-angle-right" style="color: #E94412;"></i>
                                </button>
                            </div>
                        `;
                        
                        // Applied Coupon Details - Separate div below the button
                        html += `
                            <div class="mb-3" id="applied-coupon-section-${vendorId}" style="display: ${vendorCoupons[vendorId] ? 'block' : 'none'};">
                                ${vendorCoupons[vendorId] ? `
                                    <div class="applied-coupon-card" style="background: #fff; border: 1px solid #e0e0e0; border-radius: 12px; padding: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                                        <div class="row g-2">
                                            <div class="col-12 col-md-7">
                                                <div class="d-flex align-items-center mb-2">
                                                    <span style="color: #4caf50; font-weight: 600; font-size: 14px;">Discount</span>
                                                    <span style="color: #4caf50; font-weight: 700; font-size: 18px; margin-left: 8px;">₹${vendorCoupons[vendorId].discount}</span>
                                                </div>
                                                <div style="font-weight: 600; color: #333; margin-bottom: 3px; font-size: 14px;">${vendorCoupons[vendorId].code}</div>
                                                ${vendorCoupons[vendorId].coupon_type ? `<div style="color: #666; font-size: 11px; margin-bottom: 2px;">${vendorCoupons[vendorId].coupon_type} discount</div>` : ''}
                                                ${vendorCoupons[vendorId].applicable_items ? `<div style="color: #666; font-size: 11px;">Applied to ${vendorCoupons[vendorId].applicable_items} items</div>` : ''}
                                            </div>
                                            <div class="col-12 col-md-5">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <img src="{{ asset('public/assets/website/images/logo.png') }}" alt="logo" style="width: 40px; height: 40px; object-fit: contain;">
                                                    <button type="button" onclick="clearCoupon(${vendorId})" class="btn btn-sm" style="background: #fff; border: 1.5px solid #dc3545; color: #dc3545; border-radius: 6px; padding: 5px 14px; font-size: 11px; font-weight: 500; white-space: nowrap; flex-shrink: 0;">Remove</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                ` : ''}
                            </div>
                        `;


                            html += `
                            <div class="d-flex justify-content-between align-items-center bg-light p-2 rounded mb-3 wallet-box p-3" style="display: none !important;">
                                    <div>
                                     <span id="walletText3"><i class="fa fa-wallet me-1"></i> Kwiklly Points</span>
                                    </div>
                                    <button class="btn btn-outline-success btn-sm" id="walletBtn3">Use ₹5</button>
                                    </div>
                                `;

                        // Bill summary
                        html += `
                            <div class="d-flex align-items-center p-3 mb-2">
                               <h6 class="fw-bold mb-0">Bill Summary</h6> <strong class="ms-2">₹${(subtotal - couponDiscount).toFixed(2)}</strong>
                                <button class="toggle-bill-btn btn btn-sm btn-link" data-vendor="${vendorSlug}">
                                   <i class="fa fa-chevron-down"></i>
                                </button>

                            </div>
                            <div id="billSummary-${vendorSlug}" class="p-3 bg-light rounded">
                                <ul class="list-unstyled small">
                                    ${(() => {
                                        const savedAmount = totalOriginal - subtotal;
                                        if (savedAmount > 0) {
                                            return `<li class="d-flex justify-content-between"><span>Item charge</span><span>₹${subtotal.toFixed(2)} <s class="text-muted">₹${totalOriginal.toFixed(2)}</s></span></li>`;
                                        } else {
                                            return `<li class="d-flex justify-content-between"><span>Item charge</span><span>₹${subtotal.toFixed(2)}</span></li>`;
                                        }
                                    })()}
                                    <li class="d-flex justify-content-between"><span>Delivery Charges</span><span id="deliveryCharge-${vendorId}">₹0</span></li>
                                    ${couponDiscount > 0 ? `<li class="d-flex justify-content-between"><span>Coupon Discount</span><span class="text-success">- ₹${couponDiscount.toFixed(2)}</span></li>` : ''}
                                    <li class="d-flex justify-content-between" style="display: none !important;"><span>Wallet Discount</span><span class="text-success">-₹0</span></li>
                                    <li> <div class="ordersummary-row grandtotal-row">
                                                <span><strong>Grand Total</strong></span>
                                             <div class="text-end"><strong id="totalCharge-${vendorId}">₹${(subtotal - couponDiscount).toFixed(2)}</strong><br>
                                             ${(() => {
                                                const savedAmount = totalOriginal - subtotal;
                                                return savedAmount > 0 ? `<span class="save-box">Saved ₹${savedAmount.toFixed(0)}</span>` : '';
                                             })()}
                                              </div>
                                            </div></li>
                                  </ul>
                                                     </div>
                                <hr class="vendor-divider my-4" style="border: 1px solid #e0e0e0; margin: 20px 0;">
                        </div>
                        `;

                        // ✅ Fetch vendor's minimum order dynamically for progress bar
                        $.ajax({
                            url: "{{ route('minimum.order.amount') }}",
                            type: "GET",
                            data: {
                                vendor_id: vendorId,
                                cart_total: subtotal - couponDiscount
                            },
                            success: function (res) {
                                if (res.success) {
                                    const minOrderValue = parseFloat(res.min_order_amount) || 0;
                                    let deliveryCharge = parseFloat(res.delivery_charge) || 0;
                                    const minOrderForCook = parseFloat(res.min_order_for_cook) || 0;
                                    const cartTotal = subtotal - couponDiscount;
                                    
                                    // ✅ Conditionally show/hide progress sections
                                    const hasMinOrder = minOrderValue > 0;
                                    const hasMinOrderForCook = minOrderForCook > 0;
                                    
                                    // Show/hide delivery progress section
                                    if (hasMinOrder) {
                                        $(`#delivery-progress-section-${vendorId}`).show();
                                        const remaining = Math.max(minOrderValue - cartTotal, 0);
                                        const progressPercent = minOrderValue > 0 ? Math.min((cartTotal / minOrderValue) * 100, 100) : 0;

                                        let textHtml = remaining > 0
                                            ? `Add items worth ₹<b>${remaining.toFixed(2)}</b> more to get free delivery`
                                            : `<span class="text-success fw-semibold">You unlocked FREE delivery 🎉</span>`;

                                        $(`#delivery-progress-text-${vendorId}`).html(textHtml);
                                        $(`#delivery-progress-bar-${vendorId}`).css("width", progressPercent + "%");
                                    } else {
                                        $(`#delivery-progress-section-${vendorId}`).hide();
                                    }
                                    
                                    // Show/hide cook progress section
                                    if (hasMinOrderForCook) {
                                        $(`#cook-progress-section-${vendorId}`).show();
                                        updateCookProgress(vendorId, cartTotal, minOrderForCook, res.dy_text || 'free Gift');
                                    } else {
                                        $(`#cook-progress-section-${vendorId}`).hide();
                                    }
                                    
                                    // Show/hide main progress section and footer
                                    if (hasMinOrder || hasMinOrderForCook) {
                                        $(`#progress-section-${vendorId}`).show();
                                        $(`#progress-footer-${vendorId}`).show();
                                    } else {
                                        $(`#progress-section-${vendorId}`).hide();
                                    }

                                    // update express label
                                    $(`.express-label-${vendorId}`).text(
                                        `Get order in 20 min (Min ₹${minOrderValue}, Delivery ₹${deliveryCharge.toFixed(2)})`
                                    );

                                    // Store delivery charge for this vendor
                                    vendorDeliveryCharges[vendorId] = deliveryCharge;

                                    // update Delivery & Total Charges
                                    if (cartTotal >= minOrderValue && minOrderValue > 0) {
                                        deliveryCharge = 0;
                                        vendorDeliveryCharges[vendorId] = 0;
                                    }
                                    $(`#deliveryCharge-${vendorId}`).text(`₹${deliveryCharge.toFixed(2)}`);

                                    const totalCharges = subtotal + deliveryCharge - couponDiscount;
                                    $(`#totalCharge-${vendorId}`).text(`₹${totalCharges.toFixed(2)}`);

                                    // Update the order summary with new delivery charges
                                    updateOrderSummary(window.currentCart || {}, vendorCoupons);
                                }
                            }
                        });
                    });
                    
                    // Add "Proudly sponsored by BLUE DART" section only once at the end
                    html += `
                        <div class="delivery-card mt-4">
                            <div class="delivery-info">
                                <div class="details">
                                    <div class="name">Proudly sponsored by</div>
                                </div>
                            </div>
                            <div class="company"><span style="color:green;">KWIK</span><span style="color:#0047ab;">LLY</span></div>
                        </div>
                    `;
                } else {
                    html = `<p class="text-center">Your cart is empty.</p>`;
                }

                $('#cartItemsWrapper').html(html);

                // Check and show coupon sections only for vendors that have coupons
                $('.vendor-section').each(function() {
                    const vendorId = $(this).data('vendor-id');
                    if (vendorId && vendorId > 0) {
                        // If coupon is already applied, show the section
                        if (vendorCoupons[vendorId]) {
                            $(`#coupons-section-${vendorId}`).show();
                        } else {
                            // Check if vendor has coupons available
                            $.ajax({
                                url: '{{ route("coupon.vendorwise.checkout") }}',
                                method: 'GET',
                                data: { vendor_id: vendorId },
                                success: function(res) {
                                    // Check if response contains coupons (not "No coupons available" message)
                                    const tempDiv = $('<div>').html(res.html);
                                    const hasCoupons = tempDiv.find('.xyz-coupon-row').length > 0;
                                    
                                    if (hasCoupons) {
                                        $(`#coupons-section-${vendorId}`).show();
                                    }
                                },
                                error: function() {
                                    // On error, keep section hidden
                                }
                            });
                        }
                    }
                });

                // Auto-load and show delivery options for all vendors on page load
                $('.delivery-toggle-btn').each(function() {
                    const vendorId = $(this).data('vendor-id');
                    const $container = $(`#deliveryOptions-${vendorId}`);
                    const $btn = $(this);
                    
                    if (!$container.data('loaded')) {
                        // Load and show delivery options automatically
                        // Build options dynamically from DB slots
                        let slotOptions = `<option value="">Select Time</option>`;
                        timeSlots.forEach(slot => {
                            slotOptions += `<option value="${slot.slot_time}">${slot.slot_time}</option>`;
                        });

                        // Express option (3rd radio) — initially disabled
                        let expressHtml = `
                            <div class="form-check mb-2 mt-3">
                                <input type="radio"
                                    name="deliveryOption-${vendorId}"
                                    class="form-check-input me-2 express-radio"
                                    id="express20-${vendorId}"
                                    value="express"
                                    disabled>
                                <label for="express20-${vendorId}" class="form-check-label fw-semibold express-label-${vendorId}">
                                    Get order in 20 min
                                </label>
                            </div>
                        `;

                        let html = `
                            <div class="mt-3">
                                <!-- Option 1: Delivery in 30 minutes -->
                                <div class="form-check mb-2">
                                    <input type="radio"
                                        name="deliveryOption-${vendorId}"
                                        class="form-check-input me-2 standard-delivery-radio"
                                        id="delivery30-${vendorId}"
                                        value="30min" required>
                                    <label for="delivery30-${vendorId}" class="form-check-label fw-semibold">
                                        Delivery in 30 min
                                    </label>
                                </div>

                                <!-- Option 2: Custom Delivery Time -->
                                <div class="form-check mb-2">
                                    <input type="radio"
                                        name="deliveryOption-${vendorId}"
                                        class="form-check-input me-2 custom-delivery-radio"
                                        id="customDelivery-${vendorId}"
                                        value="custom" required>
                                    <label for="customDelivery-${vendorId}" class="form-check-label fw-semibold">
                                        Custom Delivery Time
                                    </label>
                                </div>
                                <div id="customInput-${vendorId}" class="mt-2" style="display:none;">
                                    <div class="row g-2">
                                        <div class="col-md-4">
                                            <label class="form-label mb-1">Date</label>
                                            <input type="date"
                                                class="form-control form-control-sm custom-date"
                                                name="custom_date_${vendorId}"
                                                data-vendor-id="${vendorId}"
                                                min="${new Date().toISOString().split('T')[0]}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label mb-1">From</label>
                                            <select class="form-select form-select-sm custom-from"
                                                    name="custom_start_time_${vendorId}"
                                                    data-vendor-id="${vendorId}">
                                                ${slotOptions}
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label mb-1">To</label>
                                            <select class="form-select form-select-sm custom-to"
                                                    name="custom_end_time_${vendorId}"
                                                    data-vendor-id="${vendorId}">
                                                ${slotOptions}
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Option 3: Express 20 min -->
                                ${expressHtml}
                            </div>
                        `;

                        $container.html(html).data('loaded', true).show();
                        $btn.html('<i class="fa fa-clock me-1"></i> Hide Delivery Time');
                        
                        // Fetch vendor's min_order_amount + delivery_charge dynamically
                        $.ajax({
                            url: "{{ route('minimum.order.amount') }}",
                            type: "GET",
                            data: {
                                vendor_id: vendorId,
                                cart_total: parseFloat($(`#cartTotal-${vendorId}`).val()) || 0
                            },
                            success: function (res) {
                                if (res.success) {
                                    const minOrder = parseFloat(res.min_order_amount);
                                    let deliveryCharge = parseFloat(res.delivery_charge);
                                    if (isNaN(deliveryCharge)) deliveryCharge = 0;

                                    // update Express label
                                    $(`.express-label-${vendorId}`).text(
                                        `Get order in 20 min (Min ₹${minOrder}, Delivery ₹${deliveryCharge.toFixed(2)})`
                                    );

                                    const cartTotal = parseFloat($(`#cartTotal-${vendorId}`).val()) || 0;
                                    const $expressRadio = $(`#express20-${vendorId}`);
                                    const $delivery30 = $(`#delivery30-${vendorId}`);

                                    // Always default to standard delivery (30 min)
                                    $delivery30.prop("checked", true).prop("disabled", false);

                                    if (cartTotal >= minOrder && minOrder > 0) {
                                        // Free delivery, enable express option but don't auto-select
                                        $expressRadio.prop("disabled", false).prop("checked", false);
                                        deliveryCharge = 0; // free delivery
                                    } else {
                                        // keep express disabled
                                        $expressRadio.prop("disabled", true).prop("checked", false);
                                    }

                                    // Store delivery charge for this vendor
                                    vendorDeliveryCharges[vendorId] = deliveryCharge;

                                    // update Delivery Charge in Bill Summary
                                    $(`#deliveryCharge-${vendorId}`).text(`₹${deliveryCharge.toFixed(2)}`);

                                    // ✅ recalc total charges
                                    const subtotal = parseFloat($(`#subtotal-${vendorId}`).val()) || 0;
                                    const coupon = parseFloat($(`#coupon-${vendorId}`).val()) || 0;
                                    const totalCharges = subtotal + deliveryCharge - coupon;
                                    $(`#totalCharge-${vendorId}`).text(`₹${totalCharges.toFixed(2)}`);

                                    // Update the order summary with new delivery charges
                                    updateOrderSummary(window.currentCart || {}, vendorCoupons);

                                    // ✅ Conditionally show/hide progress sections
                                    const hasMinOrder = minOrder && minOrder > 0;
                                    const hasMinOrderForCook = res.min_order_for_cook && parseFloat(res.min_order_for_cook) > 0;
                                    
                                    // Show/hide delivery progress section
                                    if (hasMinOrder) {
                                        $(`#delivery-progress-section-${vendorId}`).show();
                                        const cartTotal = parseFloat($(`#cartTotal-${vendorId}`).val()) || 0;
                                        const remaining = Math.max(minOrder - cartTotal, 0);
                                        const progressPercent = minOrder > 0 ? Math.min((cartTotal / minOrder) * 100, 100) : 0;
                                        
                                        let textHtml = remaining > 0
                                            ? `Add items worth ₹<b>${remaining.toFixed(2)}</b> more to get free delivery`
                                            : `<span class="text-success fw-semibold">You unlocked FREE delivery 🎉</span>`;
                                        
                                        $(`#delivery-progress-text-${vendorId}`).html(textHtml);
                                        $(`#delivery-progress-bar-${vendorId}`).css("width", progressPercent + "%");
                                    } else {
                                        $(`#delivery-progress-section-${vendorId}`).hide();
                                    }
                                    
                                    // Show/hide cook progress section
                                    if (hasMinOrderForCook) {
                                        const cartTotal = parseFloat($(`#cartTotal-${vendorId}`).val()) || 0;
                                        updateCookProgress(vendorId, cartTotal, parseFloat(res.min_order_for_cook), res.dy_text);
                                        $(`#cook-progress-section-${vendorId}`).show();
                                    } else {
                                        $(`#cook-progress-section-${vendorId}`).hide();
                                    }
                                    
                                    // Show/hide main progress section and footer
                                    if (hasMinOrder || hasMinOrderForCook) {
                                        $(`#progress-section-${vendorId}`).show();
                                        $(`#progress-footer-${vendorId}`).show();
                                    } else {
                                        $(`#progress-section-${vendorId}`).hide();
                                    }
                                }
                            },
                            error: function (xhr) {
                                console.error("Error fetching min order amount:", xhr.responseText);
                            }
                        });
                    }
                });
            }

            // Show/hide custom input when selecting "Custom Delivery Time"
            $(document).on('change', '.custom-delivery-radio', function () {
                const vendorId = $(this).attr('id').split('-')[1];
                $(`#customInput-${vendorId}`).toggle(this.checked);
            });

            // If switching back from custom, hide input
            $(document).on('change', `input[name^="deliveryOption-"]`, function () {
                const vendorId = $(this).attr('name').split('-')[1];
                if ($(this).val() !== "custom") {
                    $(`#customInput-${vendorId}`).hide();
                }

                // Update delivery charges when user changes selection
                if ($(this).hasClass('standard-delivery-radio') && $(this).is(':checked')) {
                    // Recalculate delivery charges for standard delivery
                    const vendorId = $(this).attr('id').split('-')[1];
                    updateVendorDeliveryCharge(vendorId);
                }
            });

            // Filter "To" times based on "From" selection
            $(document).on('change', '.custom-from', function () {
                const vendorId = $(this).data('vendor-id');
                const fromTime = $(this).val();
                const $toSelect = $(`select[name="custom_end_time_${vendorId}"]`);

                let options = `<option value="">Select Time</option>`;
                let fromIndex = timeSlots.findIndex(slot => slot.slot_time === fromTime);

                timeSlots.forEach((slot, i) => {
                    if (i > fromIndex) { // only greater than selected
                        options += `<option value="${slot.slot_time}">${slot.slot_time}</option>`;
                    }
                });

                $toSelect.html(options);
            });

            // ✅ Extra Validation: Ensure "From" slot is at least 2 hours later than current time if date = today
            $(document).on('change', '.custom-from, .custom-date', function () {
                const vendorId = $(this).data('vendor-id');
                const selectedDate = $(`input[name="custom_date_${vendorId}"]`).val();
                const fromTime = $(`select[name="custom_start_time_${vendorId}"]`).val();

                if (!selectedDate || !fromTime) return;

                let now = new Date();
                let todayStr = now.toISOString().split('T')[0];

                if (selectedDate === todayStr) {
                    let [h, m] = fromTime.split(":");
                    let selected = new Date(selectedDate + " " + h + ":" + m);
                    let minAllowed = new Date(now.getTime() + 2 * 60 * 60 * 1000); // 2 hours later

                    if (selected < minAllowed) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Invalid Time Selection',
                            text: "Please select a 'From' time at least 2 hours later than the current time.",
                            confirmButtonColor: '#E94412'
                        });
                        $(`select[name="custom_start_time_${vendorId}"]`).val(""); // reset
                        $(`select[name="custom_end_time_${vendorId}"]`).html(`<option value="">Select Time</option>`); // reset To
                    }
                }
            });

            // Calculate total delivery charges across all vendors
            function calculateTotalDeliveryCharges(groupedCart, vendorCoupons = {}) {
                let totalDeliveryCharge = 0;

                $.each(groupedCart, function (businessName, items) {
                    const firstKey = Object.keys(items)[0];
                    const vendorId = items[firstKey]?.business_id;

                    // Use stored delivery charge if available, otherwise default to 0
                    if (vendorId && vendorDeliveryCharges.hasOwnProperty(vendorId)) {
                        totalDeliveryCharge += vendorDeliveryCharges[vendorId];
                    }
                });

                return totalDeliveryCharge;
            }

            // Update delivery charge for a specific vendor
            function updateVendorDeliveryCharge(vendorId) {
                $.ajax({
                    url: "{{ route('minimum.order.amount') }}",
                    type: "GET",
                    data: {
                        vendor_id: vendorId,
                        cart_total: parseFloat($(`#cartTotal-${vendorId}`).val()) || 0
                    },
                    success: function (res) {
                        if (res.success) {
                            const minOrder = parseFloat(res.min_order_amount);
                            let deliveryCharge = parseFloat(res.delivery_charge);
                            const cartTotal = parseFloat($(`#cartTotal-${vendorId}`).val()) || 0;

                            // Check if cart total meets minimum for free delivery
                            if (cartTotal >= minOrder && minOrder > 0) {
                                deliveryCharge = 0; // free delivery
                            }

                            // Store delivery charge for this vendor
                            vendorDeliveryCharges[vendorId] = deliveryCharge;

                            // update Delivery Charge in Bill Summary
                            $(`#deliveryCharge-${vendorId}`).text(`₹${deliveryCharge.toFixed(2)}`);

                            // ✅ recalc total charges
                            const subtotal = parseFloat($(`#subtotal-${vendorId}`).val()) || 0;
                            const coupon = parseFloat($(`#coupon-${vendorId}`).val()) || 0;
                            const totalCharges = subtotal + deliveryCharge - coupon;
                            $(`#totalCharge-${vendorId}`).text(`₹${totalCharges.toFixed(2)}`);

                            // Update the order summary with new delivery charges
                            updateOrderSummary(window.currentCart || {}, vendorCoupons);
                        }
                    },
                    error: function (xhr) {
                        console.error("Error fetching min order amount:", xhr.responseText);
                    }
                });
            }

            function updateOrderSummary(groupedCart, vendorCoupons = {}) {
                let itemCount = 0;
                let subtotal = 0;
                let originalTotal = 0;
                let totalCouponDiscount = 0;

                $.each(groupedCart, function (_, items) {
                    $.each(items, function (_, item) {
                        itemCount += item.quantity;
                        subtotal += item.quantity * item.price;
                        originalTotal += item.quantity * item.original_price;
                    });

                    const firstKey = Object.keys(items)[0];
                    const vendorId = items[firstKey]?.business_id;
                    if (vendorCoupons[vendorId]) {
                        totalCouponDiscount += parseFloat(vendorCoupons[vendorId].discount || 0);
                    }
                });

                // Calculate delivery charges
                const deliveryCharge = calculateTotalDeliveryCharges(groupedCart, vendorCoupons);
                const walletDiscount = 0;

                let grandTotal = subtotal + deliveryCharge - totalCouponDiscount - walletDiscount;
                if (grandTotal < 0) grandTotal = 0;

                const savedAmount = originalTotal - subtotal;
                const savedPercent = originalTotal > 0 ? Math.round((savedAmount / originalTotal) * 100) : 0;

                $('#summary-items-count').text(itemCount);
                $('#summary-subtotal').text('₹' + subtotal.toFixed(2));
                $('#summary-delivery').text('₹' + deliveryCharge.toFixed(2));
                $('#summary-coupon').text('-₹' + totalCouponDiscount.toFixed(2));
                $('#summary-wallet').text('-₹' + walletDiscount.toFixed(2));
                $('.final-price').text('₹' + grandTotal.toFixed(2));
                $('#saved-amount').text(savedAmount > 0 ? `₹${savedAmount.toFixed(2)} (${savedPercent}%)` : '₹0');


            }

            function updateCookProgress(vendorId, subtotal, cookThreshold, dyText = 'free cook') {
                const remaining = Math.max(cookThreshold - subtotal, 0);
                const progressPercent = Math.min((subtotal / cookThreshold) * 100, 100);

                let text = '';
                if (remaining > 0) {
                    text = `Add item worth <b>₹${remaining}</b> to get free ${dyText}`;
                } else {
                    text = `Congratulations! You have unlocked ${dyText} 🎉`;
                }

                $(`#cook-progress-text-${vendorId}`).html(text);
                $(`#cook-progress-bar-${vendorId}`).css('width', progressPercent + '%');
            }

            function showModal(vendorId) {
                // Validate vendorId
                vendorId = parseInt(vendorId);
                if (!vendorId || vendorId <= 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Invalid vendor ID. Cannot load coupons.',
                        confirmButtonColor: '#E94412'
                    });
                    return;
                }

                console.log('Loading coupons for vendor ID:', vendorId);

                // Get the applied coupon code for this vendor (if any)
                // Check if vendorCoupons[vendorId] exists and has a valid code
                let appliedCouponCode = null;
                if (vendorCoupons[vendorId] && vendorCoupons[vendorId].code && vendorCoupons[vendorId].code.trim() !== '') {
                    appliedCouponCode = vendorCoupons[vendorId].code;
                }

                $.ajax({
                    url: '{{ route("coupon.vendorwise.checkout") }}',
                    method: 'GET',
                    data: { 
                        vendor_id: vendorId,
                        applied_coupon_code: appliedCouponCode
                    },
                    success: function(res) {
                        console.log('Coupons loaded for vendor ID:', vendorId);
                        // Remove any existing modal with this vendor ID
                        $('#couponModalxyz-' + vendorId).remove();
                        // Add the new modal HTML to body
                        $('body').append(res.html);
                        // Show the modal with flex display
                        const modal = $('#couponModalxyz-' + vendorId);
                        if (modal.length) {
                            modal.css({
                                'display': 'flex',
                                'opacity': '0'
                            }).animate({
                                'opacity': '1'
                            }, 300);
                            
                            // Bind click handlers directly to buttons after modal loads
                            setTimeout(function() {
                                const applyButtons = modal.find('.apply-coupon-btn');
                                console.log('Apply buttons found in modal:', applyButtons.length);
                                
                                applyButtons.each(function() {
                                    const $btn = $(this);
                                    const code = $btn.attr('data-code') || $btn.data('code');
                                    const vid = $btn.attr('data-vendor-id') || $btn.data('vendor-id') || vendorId;
                                    
                                    console.log('Binding button click handler:', { code, vendorId: vid });
                                    
                                    // Remove any existing handlers and bind new one
                                    $btn.off('click.applyCoupon').on('click.applyCoupon', function(e) {
                                        e.preventDefault();
                                        e.stopPropagation();
                                        e.stopImmediatePropagation();
                                        
                                        console.log('Button clicked directly:', { code, vendorId: vid });
                                        
                                        // Call the handler function
                                        if (typeof window.applyCouponHandler === 'function') {
                                            window.applyCouponHandler(code, vid);
                                        } else {
                                            console.error('applyCouponHandler function not found!');
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Error',
                                                text: 'Handler function not found. Please refresh the page.',
                                                confirmButtonColor: '#E94412'
                                            });
                                        }
                                        
                                        return false;
                                    });
                                    
                                    // Ensure button is clickable
                                    $btn.css({
                                        'pointer-events': 'auto',
                                        'z-index': '99999999',
                                        'position': 'relative',
                                        'cursor': 'pointer'
                                    });
                                });
                            }, 100);
                        }
                    },
                    error: function(xhr) {
                        console.error('Error loading coupons for vendor ID:', vendorId, xhr);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Could not load coupons for this vendor.',
                            confirmButtonColor: '#E94412'
                        });
                    }
                });
            }

            // Make hideModal globally accessible
            window.hideModal = function(vendorId) {
                if (vendorId) {
                    const modalId = '#couponModalxyz-' + vendorId;
                    $(modalId).fadeOut(200, function() {
                        $(this).css('display', 'none');
                    });
                } else {
                    // Fallback for all modals
                    $('[id^="couponModalxyz-"]').fadeOut(200, function() {
                        $(this).css('display', 'none');
                    });
                }
            };
            
            // Also keep the local function for backward compatibility
            function hideModal(vendorId) {
                window.hideModal(vendorId);
            }

            // Clear coupon function - UPDATED
            function clearCoupon(vendorId) {
                $.ajax({
                    url: '{{ route("coupon.clear") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        vendor_id: vendorId
                    },
                    success: function (response) {
                        if (response.success) {
                            // ✅ Update global coupon state
                            vendorCoupons = response.vendor_coupons || {};
                            
                            // ✅ Ensure the vendor entry is completely removed if no coupon
                            if (!vendorCoupons[vendorId] || !vendorCoupons[vendorId].code) {
                                delete vendorCoupons[vendorId];
                            }

                            // ✅ Re-render sections with updated cart data
                            renderCartSections(response.updated_cart.cart, vendorCoupons);
                            updateOrderSummary(response.updated_cart.cart, vendorCoupons);
                            
                            // Remove coupon display from page
                            const $appliedCouponSection = $(`#applied-coupon-section-${vendorId}`);
                            if ($appliedCouponSection.length) {
                                $appliedCouponSection.hide().html('');
                            }

                            Swal.fire({
                                icon: 'success',
                                title: 'Coupon Removed',
                                text: response.message || 'Coupon has been removed successfully.',
                                confirmButtonColor: '#E94412',
                                timer: 3000
                            });
                        }
                    },
                    error: function () {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to remove coupon. Please try again.',
                            confirmButtonColor: '#E94412'
                        });
                    }
                });
            }

            // Express delivery button
            $(document).on('click', '.express-btn', function () {
                const vendorId = $(this).data('vendor-id');
                Swal.fire({
                    icon: 'info',
                    title: 'Express Delivery',
                    text: `Express delivery selected for vendor ID: ${vendorId}`,
                    confirmButtonColor: '#E94412',
                    timer: 2000
                });
                // You can add actual logic here, e.g. update charges or mark express
            });

        </script>
         <!-- Wallet transaction -->
        <script type="text/javascript">
            // Wallet functionality
            $(document).ready(function() {
                // Update wallet balance on page load
                updateWalletBalance();

                // Handle wallet usage toggle
                $(document).on('click', '#use-wallet-btn', function() {
                    const useWallet = $(this).hasClass('active');
                    const walletBalance = parseFloat($('#wallet-balance').text());

                    if (useWallet) {
                        // Use full wallet balance
                        applyWallet(walletBalance);
                    } else {
                        // Remove wallet usage
                        removeWallet();
                    }
                });

                // Handle custom wallet amount
                $(document).on('click', '#apply-wallet-amount', function() {
                    const customAmount = parseFloat($('#custom-wallet-amount').val());
                    const walletBalance = parseFloat($('#wallet-balance').text());

                    if (isNaN(customAmount) || customAmount <= 0) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Invalid Amount',
                            text: 'Please enter a valid amount',
                            confirmButtonColor: '#E94412'
                        });
                        return;
                    }

                    if (customAmount > walletBalance) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Insufficient Balance',
                            text: 'Amount exceeds wallet balance',
                            confirmButtonColor: '#E94412'
                        });
                        return;
                    }

                    applyWallet(customAmount);
                });
            });

            function updateWalletBalance() {
                $.ajax({
                    url: "{{ route('cart.wallet.balance') }}",
                    method: 'GET',
                    success: function(response) {
                        if (response.success) {
                            $('#wallet-balance').text(response.balance.toFixed(2));
                        }
                    }
                });
            }

            function applyWallet(amount) {
                $.ajax({
                    url: "{{ route('cart.apply.wallet') }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        use_wallet: true,
                        wallet_amount: amount
                    },
                    success: function(response) {
                        if (response.success) {
                            // Update UI to show wallet is applied
                            $('#use-wallet-btn').addClass('active').text('Remove Wallet');
                            $('#wallet-amount-display').text('₹' + amount.toFixed(2));
                            $('#wallet-discount').text('-₹' + amount.toFixed(2));

                            // Update order summary
                            updateOrderSummary(response.updated_cart.cart, response.updated_cart.vendor_coupons);
                        }
                    }
                });
            }

            function removeWallet() {
                $.ajax({
                    url: "{{ route('cart.apply.wallet') }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        use_wallet: false
                    },
                    success: function(response) {
                        if (response.success) {
                            // Update UI to show wallet is removed
                            $('#use-wallet-btn').removeClass('active').text('Use Wallet');
                            $('#wallet-amount-display').text('₹0.00');
                            $('#wallet-discount').text('-₹0.00');

                            // Update order summary
                            updateOrderSummary(response.updated_cart.cart, response.updated_cart.vendor_coupons);
                        }
                    }
                });
            }
        </script>
        <!-- Process Order -->
        <script type="text/javascript">
            function processOrder() {
                // Show confirmation dialog
                Swal.fire({
                    title: 'Confirm Delivery Address',
                    text: 'Are you sure you want to proceed to the delivery address page?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#E94412',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, Proceed',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // User confirmed, proceed with order processing
                        proceedWithOrder();
                    }
                });
            }
            
            function proceedWithOrder() {
                console.log("proceedWithOrder() called");

                const data = {
                    _token: '{{ csrf_token() }}',
                    vendors: {}
                };

                // Method 1: Try to get vendor IDs from DOM elements
                let vendorSectionsFound = 0;
                let validVendorSections = 0;

                $('.vendor-section').each(function() {
                    vendorSectionsFound++;
                    const vendorId = $(this).data('vendor-id');
                    console.log("Found vendor section:", vendorId);

                    if (vendorId && vendorId > 0) {
                        validVendorSections++;
                        const $vendorDiv = $(this);

                        // Determine selected delivery option
                        const selectedRadio = $vendorDiv.find(`input[name="deliveryOption-${vendorId}"]:checked`);
                        let deliveryData = {
                            delivery_type: 'standard' // default
                        };

                        if (selectedRadio.length > 0) {
                            const deliveryType = selectedRadio.val();
                            if (deliveryType === 'express') {
                                deliveryData.delivery_type = 'express';
                            } else if (deliveryType === 'custom') {
                                const customDate = $vendorDiv.find(`input[name="custom_date_${vendorId}"]`).val();
                                const customStartTime = $vendorDiv.find(`select[name="custom_start_time_${vendorId}"]`).val();
                                const customEndTime = $vendorDiv.find(`select[name="custom_end_time_${vendorId}"]`).val();

                                if (customDate && customStartTime && customEndTime) {
                                    deliveryData.delivery_type = 'custom';
                                    deliveryData.custom_delivery = {
                                        date: customDate,
                                        start_time: customStartTime,
                                        end_time: customEndTime
                                    };
                                } else {
                                    // Invalid custom data, fallback to standard
                                    deliveryData.delivery_type = 'standard';
                                }
                            } else {
                                deliveryData.delivery_type = 'standard';
                            }
                        }

                        data.vendors[vendorId] = deliveryData;
                        console.log("Added vendor with delivery:", vendorId, deliveryData);
                    }
                });

                console.log("Vendor sections found:", vendorSectionsFound);
                console.log("Valid vendor sections:", validVendorSections);

                // If no vendors found in DOM, try alternative approach
                if (Object.keys(data.vendors).length === 0) {
                    console.log("No vendors found in DOM, trying alternative approach");

                    // Method 2: Use PHP data directly (fallback)
                    @if(isset($groupedCart) && is_array($groupedCart) && count($groupedCart) > 0)
                        @foreach($groupedCart as $vendorKey => $vendorData)
                            @php
                                $businessId = 0;
                                if (isset($vendorData['business_id']) && $vendorData['business_id'] > 0) {
                                    $businessId = $vendorData['business_id'];
                                } elseif (isset($vendorData['items']) && count($vendorData['items']) > 0) {
                                    $firstItem = reset($vendorData['items']);
                                    $businessId = $firstItem['business_id'] ?? 0;
                                }
                            @endphp

                            @if($businessId > 0)
                                data.vendors['{{ $businessId }}'] = {
                                    delivery_slot: null,
                                    delivery_type: 'standard'
                                };
                                console.log("PHP fallback added vendor:", {{ $businessId }});
                            @endif
                        @endforeach
                    @endif
                }

                console.log("Final vendors data:", data.vendors);

                // Check if vendors object is still empty
                if (Object.keys(data.vendors).length === 0) {
                    // Last resort: Manual extraction from page content
                    console.log("Trying manual extraction as last resort");

                    // Look for any elements that might contain vendor IDs
                    $('[data-vendor-id], [vendor-id], [data-business-id]').each(function() {
                        const vendorId = $(this).data('vendor-id') ||
                                        $(this).data('business-id') ||
                                        $(this).attr('vendor-id');

                        if (vendorId && vendorId > 0) {
                            data.vendors[vendorId] = {
                                delivery_slot: null,
                                delivery_type: 'standard'
                            };
                            console.log("Manual extraction found vendor:", vendorId);
                        }
                    });

                    // If still empty, show detailed error
                    if (Object.keys(data.vendors).length === 0) {
                        Swal.fire({
                            icon: 'error',
                            title: 'No Vendors Found',
                            html: 'Error: No vendors found in the cart. This could be due to:<br><br>1. The cart is empty<br>2. Vendor ID data is missing from the page<br>3. There\'s a technical issue<br><br>Please add items to your cart first or contact support.',
                            confirmButtonColor: '#E94412'
                        });
                        return;
                    }
                }

                console.log("Sending order data:", data);

                // Submit the data via AJAX
                $.ajax({
                    url: "{{ route('checkout.process.order') }}",
                    method: 'POST',
                    data: data,
                    success: function(response) {
                        console.log("Success response:", response);
                        if (response.success) {
                            window.location.href = "{{ route('delivery.address') }}";
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message || 'Unknown error occurred',
                                confirmButtonColor: '#E94412'
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error:", xhr.responseText);
                        let errorMessage = 'An error occurred while processing your order.';

                        try {
                            const response = JSON.parse(xhr.responseText);
                            errorMessage = response.message || errorMessage;

                            // Check for validation errors
                            if (response.errors) {
                                errorMessage = Object.values(response.errors).join('\n');
                            }
                        } catch (e) {
                            if (xhr.responseText) {
                                errorMessage = xhr.responseText;
                            }
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: errorMessage,
                            confirmButtonColor: '#E94412'
                        });
                    }
                });
            }
        </script>


    </body>

</html>
