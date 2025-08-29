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
        <link rel="stylesheet" href="{{ asset('public/assets/website/CSS/style.css')}}">
        <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
        <link rel="stylesheet" href="{{ asset('public/assets/website/assets/css/checkoutdelivery.css')}}">
        <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    </head>
    <body>
        <section>
            <div class="container">
                <div class="row">
                <!-- Steps Navigation -->
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
                    <div class="wallet-box d-flex align-items-center py-1 rounded text-white">
                        <i class="fa fa-wallet text-white me-2"></i> ₹<span id="wallet-balance">55</span>
                    </div>
                </div>
                <hr style="border: 1px solid #D8C2BC;">
                <!-- LEFT SIDE: CART ITEMS, DELIVERY OPTIONS, COUPONS, BILL SUMMARY -->
                <div class="col-md-7 main-content-box">
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
                                <div class="my-3 p-3">
                                    <div class="xyz-info-box">
                                        <div class="d-flex align-items-center mb-2">
                                            <img src="{{ asset('public/demo.png') }}" alt="Icon">
                                            <div class="ms-3 w-100">
                                                <div>Add item worth ₹<b>5000</b> to get free cook</div>
                                                <p class="text-danger p-0 m-0">Accept</p>
                                            </div>
                                        </div>
                                        <div class="xyz-clickable-div">
                                            <div>Add item worth ₹<b>60</b> more to get free delivery<i class="fa fa-angle-right" style="position: absolute; left: 50%;color:#4caf50;"></i></div>
                                            <div class="xyz-progress mt-1">
                                                <div class="xyz-progress-bar" style="width: 80%"></div>
                                            </div>
                                        </div>
                                        <div class="xyz-right-text">*Progress Bar will reset in next order</div>
                                    </div>
                                </div>

                                <div class="mb-3 px-2" id="couponsxyz">
                                    <div class="">
                                        <h2 class="" id="">
                                            <button class="couponbutton" type="button" onclick="showModal({{ $vendorData['business_id'] ?? 0 }})">
                                                <i class="fa-solid fa-badge-percent"></i>View Coupons & Offers
                                                <i class="fa fa-angle-right" style="position: absolute; left: 50%;color:red;"></i>
                                            </button>
                                        </h2>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center bg-light p-2 rounded mb-3 wallet-box p-3">
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
                                        <li class="d-flex justify-content-between">
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
                            </div>
                            @endif
                            @endforeach
                    </div>
                </div>
                <!-- RIGHT SIDE: ORDER SUMMARY -->
                <div class="col-md-5">
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
                        <div class="ordersummary-row">
                            <span>Wallet Discount</span>
                            <span id="summary-wallet" class="text-green">-₹0</span>
                        </div>
                        <div class="ordersummary-row grandtotal-row">
                            <span><strong>Grand Total</strong></span>
                            <div class="text-end">
                            <strong>₹<span class="final-price">0</span></strong><br>
                            <span class="save-box">Saved <span id="saved-amount">0</span></span>
                            </div>
                        </div>
                        <div class="congrats-box" id="congrats-free-cook" style="display: none">
                            <p><strong>Congratulations!! You got free cook </strong></p>
                            <small>by Aryan Grocery</small>
                        </div>
                        <div class="congrats-box" id="congrats-free-gift" style="display: none">
                            <p><strong>Congratulations!! You got free gift</strong></p>
                            <small>by Chandresh Grocery</small>
                        </div>
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
                        renderCartSections(res.cart, vendorCoupons);
                        updateOrderSummary(res.cart, vendorCoupons);
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
                            const msg = `⚠️ The coupon "${res.coupon_code}" will be removed because subtotal for ${res.vendor_name} will fall below ₹${res.min_order_amount}.\n\nDo you want to proceed?`;
                            if (confirm(msg)) {
                                // User confirmed, resend request with confirm=true
                                $.post("{{ route('cart.decrement') }}", {
                                    _token: "{{ csrf_token() }}",
                                    key: key,
                                    confirm: true
                                }, function (res2) {
                                    vendorCoupons = res2.vendor_coupons || {};
                                    $('#cart-count').text(res2.count);
                                    renderCartSections(res2.cart, vendorCoupons);
                                    updateOrderSummary(res2.cart, vendorCoupons);
                                });
                            }
                            return; // stop here if user cancels
                        }

                        // Normal update
                        vendorCoupons = res.vendor_coupons || {};
                        $('#cart-count').text(res.count);
                        renderCartSections(res.cart, vendorCoupons);
                        updateOrderSummary(res.cart, vendorCoupons);
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

                // Apply coupon button - ADDED BACK
                $(document).on('click', '.apply-coupon-btn', function (e) {
                    e.preventDefault();

                    let couponCode = $(this).data('code');
                    let vendorId = $(this).data('vendor-id');

                    $.ajax({
                        url: '{{ route("coupon.apply") }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            code: couponCode,
                            vendor_id: vendorId
                        },
                        success: function (response) {
                            if (response.success) {
                                // ✅ Update global coupon state with the new structure
                                vendorCoupons = response.vendor_coupons || {};

                                // ✅ Re-render sections with updated cart data
                                renderCartSections(response.updated_cart.cart, vendorCoupons);
                                updateOrderSummary(response.updated_cart.cart, vendorCoupons);

                                // ✅ Show success message with discount info
                                alert('Coupon Applied: ' + response.discount_text);

                                // ✅ Auto close the modal
                                hideModal();
                            } else {
                                alert(response.message);
                            }
                        },
                        error: function (xhr) {
                            console.error('Error applying coupon:', xhr.responseText);
                            alert('Error applying coupon. Please try again.');
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

                // Close other vendors' slots
                $('.delivery-options-container').not($container).slideUp();

                if ($container.data('loaded')) {
                    $container.slideToggle();
                    return;
                }

                // Build options dynamically from DB slots
                let slotOptions = `<option value="">Select Time</option>`;
                timeSlots.forEach(slot => {
                    slotOptions += `<option value="${slot.slot_time}">${slot.slot_time}</option>`;
                });

                // Express option (3rd radio) — initially disabled
                let expressHtml = `
                    <div class="form-check mb-3 mt-3">
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

                $container.html(html).data('loaded', true).slideDown();

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

                            // update Express label
                            $(`.express-label-${vendorId}`).text(
                                `Get order in 20 min (Min ₹${minOrder}, Delivery ₹${deliveryCharge})`
                            );

                            const cartTotal = parseFloat($(`#cartTotal-${vendorId}`).val()) || 0;
                            const $expressRadio = $(`#express20-${vendorId}`);
                            const $delivery30 = $(`#delivery30-${vendorId}`);

                            if (cartTotal >= minOrder && minOrder > 0) {
                                // Free delivery, enable & auto-select express
                                $expressRadio.prop("disabled", false).prop("checked", true);
                                $delivery30.prop("checked", false);
                                deliveryCharge = 0; // free delivery
                            } else {
                                // keep express disabled, auto-select standard delivery
                                $expressRadio.prop("disabled", true).prop("checked", false);
                                $delivery30.prop("checked", true);
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
            });

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
                            <div class="vendor-cart-block my-4 border rounded p-3">
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

                                    <div id="deliveryOptions-${vendorId}" class="delivery-options-container mt-2" style="display: none;"></div>
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

                        // Progress section
                        html += `
                                </div>
                                <div class="my-3" id="progress-section-${vendorId}">
                                    <div class="xyz-info-box">
                                        <div class="xyz-clickable-div">
                                            <div class="progress-text-${vendorId}">
                                                Checking free delivery eligibility...
                                            </div>
                                            <div class="xyz-progress mt-1">
                                                <div class="xyz-progress-bar progress-bar-${vendorId}" style="width: 0%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        `;

                        // Coupons section
                        html += `
                            <div class="mb-3 px-2" id="couponsxyz">
                                <h2>
                                    <button class="couponbutton" type="button" onclick="showModal(${vendorId})">
                                        <i class="fa-solid fa-badge-percent"></i>View Coupons & Offers
                                        <i class="fa fa-angle-right" style="position: absolute; left: 50%;color:red;"></i>
                                    </button>
                                </h2>
                                ${vendorCoupons[vendorId] ? `
                                    <div class="xyz-coupon-row row m-2" style="border: 1px solid #ddd; padding: 10px; border-radius: 8px;">
                                        <div class="col-6">
                                            <div style="color: green;">Discount ₹${vendorCoupons[vendorId].discount}</div>
                                            <small>${vendorCoupons[vendorId].code}</small>
                                            <div><small>${vendorCoupons[vendorId].coupon_type ? vendorCoupons[vendorId].coupon_type + ' discount' : ''}</small></div>
                                            ${vendorCoupons[vendorId].applicable_items ? `<div><small>Applied to ${vendorCoupons[vendorId].applicable_items} items</small></div>` : ''}
                                        </div>
                                        <div class="col-6 text-end">
                                            <img src="{{ asset('public/assets/website/images/logo.png') }}" alt="logo" class="xyz-coupon-logo" style="width: 40px;">
                                            <div><small>COUPON: ${vendorCoupons[vendorId].code}</small></div>
                                            <button type="button" onclick="clearCoupon(${vendorId})" class="btn btn-sm btn-outline-danger mt-2">Remove</button>
                                        </div>
                                    </div>` : ''}
                            </div>
                        `;

                        // Bill summary
                        html += `
                            <div class="d-flex align-items-center mb-2">
                                <h6 class="fw-bold mb-0">Bill Summary</h6> <strong class="ms-2">₹${(subtotal - couponDiscount).toFixed(2)}</strong>
                                <button class="toggle-bill-btn btn btn-sm btn-link" data-vendor="${vendorSlug}">
                                    <i class="fa fa-chevron-down"></i>
                                </button>

                            </div>
                            <div id="billSummary-${vendorSlug}" class="p-3 bg-light rounded">
                                <ul class="list-unstyled small">
                                    <li class="d-flex justify-content-between"><span>Item charge</span><span>₹${subtotal.toFixed(2)}</span></li>
                                    <li class="d-flex justify-content-between"><span>Delivery Charges</span><span id="deliveryCharge-${vendorId}">₹0</span></li>
                                    <li class="d-flex justify-content-between"><span>Coupon Discount</span><span class="text-success">- ₹${couponDiscount.toFixed(2)}</span></li>
                                    <li class="d-flex justify-content-between"><strong>Total charges</strong><strong id="totalCharge-${vendorId}">₹${(subtotal - couponDiscount).toFixed(2)}</strong></li>
                                </ul>
                            </div>
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
                                    const minOrderValue = res.min_order_amount;
                                    let deliveryCharge = res.delivery_charge;
                                    const remaining = Math.max(minOrderValue - (subtotal - couponDiscount), 0);
                                    const progressPercent = Math.min(((subtotal - couponDiscount) / minOrderValue) * 100, 100);

                                    let textHtml = remaining > 0
                                        ? `Add items worth ₹<b>${remaining}</b> more to get free delivery`
                                        : `<span class="text-success fw-semibold">You unlocked FREE delivery 🎉</span>`;

                                    $(`.progress-text-${vendorId}`).html(textHtml);
                                    $(`.progress-bar-${vendorId}`).css("width", progressPercent + "%");

                                    // update express label
                                    $(`.express-label-${vendorId}`).text(
                                        `Get order in 20 min (Min ₹${minOrderValue}, Delivery ₹${deliveryCharge})`
                                    );

                                    // Store delivery charge for this vendor
                                    vendorDeliveryCharges[vendorId] = deliveryCharge;

                                    // update Delivery & Total Charges
                                    if ((subtotal - couponDiscount) >= minOrderValue) {
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
                } else {
                    html = `<p class="text-center">Your cart is empty.</p>`;
                }

                $('#cartItemsWrapper').html(html);

                // Auto-open delivery options for all vendors
                $('.delivery-toggle-btn').each(function() {
                    $(this).click();
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
                        alert("⚠️ Please select a 'From' time at least 2 hours later than the current time.");
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

            function showModal(vendorId) {
                $.ajax({
                    url: '{{ route("coupon.vendorwise") }}',
                    method: 'GET',
                    data: { vendor_id: vendorId },
                    success: function(res) {
                        $('#couponModalxyz').html(res.html).fadeIn();
                    },
                    error: function() {
                        alert('Could not load coupons for this vendor.');
                    }
                });
            }

            function hideModal() {
                $('#couponModalxyz').fadeOut();
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

                            // ✅ Re-render sections with updated cart data
                            renderCartSections(response.updated_cart.cart, vendorCoupons);
                            updateOrderSummary(response.updated_cart.cart, vendorCoupons);

                            alert(response.message);
                        }
                    },
                    error: function () {
                        alert('Failed to remove coupon.');
                    }
                });
            }

            // Express delivery button
            $(document).on('click', '.express-btn', function () {
                const vendorId = $(this).data('vendor-id');
                alert(`Express delivery selected for vendor ID: ${vendorId}`);
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
                        alert('Please enter a valid amount');
                        return;
                    }

                    if (customAmount > walletBalance) {
                        alert('Amount exceeds wallet balance');
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
                console.log("processOrder() called");

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
                        data.vendors[vendorId] = {
                            delivery_slot: null,
                            delivery_type: 'standard'
                        };
                        console.log("Added vendor:", vendorId);
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
                        alert('Error: No vendors found in the cart. This could be due to:\n\n1. The cart is empty\n2. Vendor ID data is missing from the page\n3. There\'s a technical issue\n\nPlease add items to your cart first or contact support.');
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
                            alert('Error: ' + (response.message || 'Unknown error occurred'));
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

                        alert(errorMessage);
                    }
                });
            }
        </script>

   </body>

</html>
