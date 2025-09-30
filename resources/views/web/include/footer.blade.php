   @include('web.include.variant_modal')

   <footer class="footer-section extramarginfooter" >
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="row">
                      <!-- Logo & Social Links -->
                        <div class="col-md-12">
                            <div class="footer-logo">
                                <img src="{{ asset('public/assets/website/images/logo.png')}}" alt="Kwikly Logo">
                            </div>
                            <div class="footer-social">
                                <a href="javascript:void(0)"><i class="fab fa-linkedin"></i></a>
                                <a href="javascript:void(0)"><i class="fab fa-facebook"></i></a>
                                <a href="javascript:void(0)"><i class="fab fa-instagram"></i></a>
                                <a href="javascript:void(0)"><img src="{{ asset('public/assets/website/images/logotwiter.png')}}" alt="" style="height: 22px;
                                margin-bottom: 5px;"></a>
                            </div>
                        </div>
                    </div>
              </div>
              <div class="col-md-8">
                <div class="row categorypadding">
                    <div class="col-md-4 col-6 footer-links">
                        <h5>Resources</h5>
                        <ul>
                            <li><a href="{{route('vendor.login')}}">Vendor Login</a></li>
                            <li><a href="{{route('vendor.signup')}}">Vendor Registration</a></li>
                        </ul>
                    </div>

                    <!-- Company -->
                    <div class="col-md-4 col-6 footer-links">
                        <h5>Company</h5>
                        <ul>
                            <li><a href="{{ route('policy.show', 'privacy-policy') }}">Privacy Policy</a></li>
                            <li><a href="{{ route('policy.show', 'terms-condition') }}">Terms & Condition</a></li>
                            <li><a href="{{ route('policy.show', 'return-policy') }}">Return Policy</a></li>
                        </ul>
                    </div>

                    <!-- About -->
                    <div class="col-md-4 col-12 footer-links">
                        <h5>About</h5>
                        <ul>
                            <li><a href="{{route('aboutus')}}">About Us</a></li>
                           <li><a href="#" id="newconOpen">Contact Us</a></li>
                        </ul>
                    </div>
                    </div>
                    <hr class="breakdown">

                    @php
                        $totalCategories = $footerCategories->count();

                        // If more than 9 categories:
                        $visibleCategories = $totalCategories > 9
                            ? $footerCategories->take(8)   // show only first 8
                            : $footerCategories;           // else show all

                        // From 9th onward goes into dropdown
                        $moreCategories = $totalCategories > 9
                            ? $footerCategories->slice(8)
                            : collect();
                    @endphp

                    <div class="row mt-4 footer-links hidecat">
                        <h5>Category</h5>

                        <div class="row w-100">
                            {{-- 8 visible categories (or fewer if total < 9) --}}
                            @foreach($visibleCategories as $category)
                                <div class="col-md-4 col-6 footer-links">
                                    <ul>
                                        <li>
                                            <a href="{{ route('allcategorywiseproduct', $category->id) }}" onclick="return redirectWithLocation(this.href)">
                                                {{ $category->name }}
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            @endforeach

                            {{-- Dropdown in 9th slot if needed --}}
                            @if($moreCategories->isNotEmpty())
                                <div class="col-md-4 col-6 footer-links">
                                    <ul>
                                        <li>
                                            <select class="shopmore" onchange="if(this.value) window.location.href=this.value">
                                                <option>Show More</option>
                                                @foreach($moreCategories as $category)
                                                    <option value="{{ route('allcategorywiseproduct', $category->id) }}">
                                                        <a onclick="return redirectWithLocation(this.href)">{{ $category->name }}</a>
                                                    </option>
                                                @endforeach
                                            </select>
                                        </li>
                                    </ul>
                                </div>
                            @endif

                            {{-- Mobile Show More (visible on small screens) --}}
                            @if($moreCategories->isNotEmpty())
                                <div class="col-12 d-md-none text-center py-3 shopmore2">
                                    <select class="shopmore-mobile" onchange="if(this.value) window.location.href=this.value">
                                        <option>Show More</option>
                                        @foreach($moreCategories as $category)
                                            <option value="{{ route('allcategorywiseproduct', $category->id) }}">
                                                <a onclick="return redirectWithLocation(this.href)">{{ $category->name }}</a>
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                        </div>
                    </div>




              </div>
        </div>
        <section>
            <div class="container p-0">
            <div class="row paymentspacing">
                        <!-- Payment Options -->
                        <div class="col-md-8 footer-links ">
                            <h5>We accept payment by</h5>
                            <div class="footer-payments mt-3">
                                <img src="{{ asset('public/assets/website/images/payment1.png')}}" alt="Paytm">
                                <img src="{{ asset('public/assets/website/images/payment2.png')}}" alt="RuPay">
                                <img src="{{ asset('public/assets/website/images/payment3.png')}}" alt="Visa">
                                <img src="{{ asset('public/assets/website/images/payment4.png')}}" alt="Mastercard">
                                <img src="{{ asset('public/assets/website/images/payment5.png')}}" alt="American Express">
                            </div>
                            <div class="footer-payments ">
                                <img src="{{ asset('public/assets/website/images/payment6.png')}}" alt="Paytm">
                                <img src="{{ asset('public/assets/website/images/payment7.png')}}" alt="RuPay">
                                <img src="{{ asset('public/assets/website/images/payment8.png')}}" alt="Visa">
                                <img src="{{ asset('public/assets/website/images/payment9.png')}}" alt="Mastercard">
                                <img src="{{ asset('public/assets/website/images/payment10.png')}}" alt="American Express">
                            </div>
                        </div>

                        <!-- Subscription Section -->
                        <div class="col-md-4 extrafootermargin">
                            <h5 class="py-2 textnewsletter">Get offers, discount codes and deals from Kwikly</h5>
                            <div class="footer-subscribe newformcontrol position-relative">
                                <input type="email" class="form-control" placeholder="Your Email">
                                <button class="btn btn-subscribe">Subscribe</button>
                            </div>
                        </div>
                    </div>
                <hr>
            <div class="footer-bottom ">
                Copyright &copy; 2000 - 2025 Kwikly. All rights reserved.
            </div>
            </div>
        </section>
    </footer>
     <!-- Trigger link -->

    <!-- Popup START-->
    <style>
        /* Overlay */
        .newcon-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.6);
            z-index: 999999 !important;
            justify-content: center;
            align-items: center;
        }

        /* Popup box */
        .newcon-popup {
            background: linear-gradient(135deg, #e8f8ee, #fdfdfd);
            padding: 25px;
            border-radius: 10px;
            width: 350px;
            max-width: 90%;
            position: relative;
            text-align: center;
            border: 2px solid #c9a9f1;
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
            animation: newconFadeIn 0.3s ease-in-out;
        }

        @keyframes newconFadeIn {
            from {opacity: 0; transform: scale(0.9);}
            to {opacity: 1; transform: scale(1);}
        }

        /* Heading */
        .newcon-popup h2 {
            margin-bottom: 15px;
            font-size: 22px;
            font-weight: bold;
        }

        .newcon-popup hr {
            margin: 10px 0 20px;
            border: 0;
            height: 1px;
            background: #bbb;
        }

        /* Inputs */
        .newcon-popup input,
        .newcon-popup textarea {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
        }

        .newcon-popup textarea {
            resize: none;
            height: 80px;
        }

        /* Buttons */
        .newcon-submit {
            width: 100%;
            padding: 12px;
            margin-top: 10px;
            border: none;
            border-radius: 30px;
            background: #e63912;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
        }

        .newcon-cancel {
            margin-top: 10px;
            display: block;
            background: none;
            border: none;
            color: #e63912;
            font-weight: bold;
            cursor: pointer;
            font-size: 15px;
        }

        /* Close button (top right) */
        .newcon-close {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 20px;
            color: #555;
            cursor: pointer;
        }
    </style>

    <div class="newcon-overlay" id="newconPopup">
        <div class="newcon-popup">
            <span class="newcon-close" id="newconClose">&times;</span>
            <h2>Contact Us</h2>
            <hr>
            <form id="newconForm" method="POST" action="{{ route('send.enquiry') }}">
                @csrf
                <input type="text" id="newconName" name="name" placeholder="Name" required>
                <input type="email" id="newconEmail" name="email" placeholder="Email id" required>
                <input type="text" id="newconSubject" name="subject" placeholder="Subject" required>
                <textarea id="newconMessage" name="message" placeholder="Message" required></textarea>
                <button type="submit" class="newcon-submit">Submit</button>
                <button type="button" class="newcon-cancel" id="newconCancel">Cancel</button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const openBtn   = document.getElementById('newconOpen');
            const popup     = document.getElementById('newconPopup');
            const closeBtn  = document.getElementById('newconClose');
            const cancelBtn = document.getElementById('newconCancel');

            function openPopup(e) {
                e.preventDefault();
                popup.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            }

            function closePopup() {
                popup.style.display = 'none';
                document.body.style.overflow = '';
            }

            openBtn.addEventListener('click', openPopup);
            closeBtn.addEventListener('click', closePopup);
            cancelBtn.addEventListener('click', closePopup);

            popup.addEventListener('click', function (ev) {
                if (ev.target === popup) closePopup();
            });

            document.addEventListener('keydown', function (ev) {
                if (ev.key === 'Escape' && popup.style.display === 'flex') {
                    closePopup();
                }
            });
        });
    </script>
    <!-- Popup END-->


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!--------------- CUSTOM JAVASCRIPT START ----------------->
    <script src="{{ asset('public/assets/website/JS/custom.js')}}"></script>
    <!--------------- CUSTOM JAVASCRIPT END ----------------->

    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: '{{ session('success') }}',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
            });
        </script>
    @endif

    @if($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    html: `{!! implode('<br>', $errors->all()) !!}`,
                    confirmButtonColor: '#d33',
                });
            });
        </script>
    @endif
    <script>
        document.querySelectorAll('.shopmore').forEach(function(select) {
            select.addEventListener('change', function() {
                if (this.value) {
                    window.location.href = this.value;
                }
            });
        });
    </script>

    <!-- Cart Operations -->
    <script type="text/javascript">
         $(document).ready(function () {

            // Load cart initially
            $.get("{{ route('cart.data') }}", function (res) {
                $('.cart-count').text(res.count);
                loadSideCartItems(res.cart);
            });

            // ADD
            $(document).on('click', '.add-btn', function () {
                let productId = $(this).data('product-id');
                let variantId = $(this).data('variant-id');

                if (!variantId) return;

                let parent = $(this).closest('.qty-box');
                let key = productId + '_' + variantId;

                $.ajax({
                    url: "{{ route('cart.add') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        product_id: productId,
                        variant_id: variantId,
                        quantity: 1
                    },
                    success: function (res) {
                        $('.cart-count').text(res.count);
                        loadSideCartItems(res.cart);
                        let qtyContainer = `
                            <div class="qty-container">
                                <button class="qty-btn minus decrement-btn" data-key="${key}">−</button>
                                <input type="text" class="qty-input quantity-input" value="1" readonly>
                                <button class="qty-btn plus increment-btn" data-key="${key}">+</button>
                            </div>
                        `;
                        parent.html(qtyContainer);
                        openCart();
                    }
                });
            });

            // INCREMENT
            $(document).on('click', '.increment-btn', function () {
                let key = $(this).data('key');
                $.post("{{ route('cart.increment') }}", {
                    _token: "{{ csrf_token() }}",
                    key: key
                }, function (res) {
                    $('.cart-count').text(res.count);
                    loadSideCartItems(res.cart);

                    // Find quantity from grouped cart
                    let updatedQty = null;
                    $.each(res.cart, function (businessName, items) {
                        if (items[key]) {
                            updatedQty = items[key].quantity;
                        }
                    });

                    if (updatedQty !== null) {
                        $(`[data-key="${key}"]`).find('.quantity-input').val(updatedQty);
                    }
                });
            });

            // DECREMENT
            $(document).on('click', '.decrement-btn', function () {
                let key = $(this).data('key');
                $.post("{{ route('cart.decrement') }}", {
                    _token: "{{ csrf_token() }}",
                    key: key
                }, function (res) {
                    $('.cart-count').text(res.count);
                    loadSideCartItems(res.cart);

                    let [productId, variantId] = key.split('_');

                    // Find product's qty box
                    let productQtyBox = $(`.qty-box[data-product-id="${productId}"][data-variant-id="${variantId}"]`);

                    // If item removed completely, reset to "Add" button
                    let stillExists = false;
                    $.each(res.cart, function (business, items) {
                        if (items[key]) stillExists = true;
                    });

                    if (!stillExists) {
                        productQtyBox.html(`
                            <button class="add-btn" data-product-id="${productId}" data-variant-id="${variantId}">
                                Add <img src="{{ asset('public/assets/website/images/cart.svg') }}" class="ms-2">
                            </button>
                        `);
                    } else {
                        // Update qty in UI
                        $.each(res.cart, function (business, items) {
                            if (items[key]) {
                                $(`[data-key="${key}"] .quantity-input`).val(items[key].quantity);
                            }
                        });
                    }
                });
            });

        });

        // Render cart items in sidebar
        function loadSideCartItems(cartGroups) {
            let html = '';
            let total = 0;

            if (cartGroups && Object.keys(cartGroups).length > 0) {
                console.log(cartGroups);
                $.each(cartGroups, function (businessName, items) {
                    // ✅ Pick vendor_id from the first item of the group
                    let firstItemKey = Object.keys(items)[0];
                    let vendorId = items[firstItemKey].business_id || '#';
                    console.log("Vendor ID:", vendorId);

                    html += `<div class="cart-business-group mb-3">
                                <h6 class="mb-1">${businessName}</h6>
                                <a href="/explorestore/${vendorId}/0"
                                class="small text-primary mb-2 d-block"
                                onclick="return redirectWithLocation(this.href)">
                                Go to store
                                </a>
                            `;

                    $.each(items, function (key, item) {
                        let price = parseFloat(item.price);
                        let quantity = parseInt(item.quantity);
                        let originalPrice = item.original_price || price;
                        let subtotal = price * quantity;
                        total += subtotal;

                        html += `
                            <div class="cart-item d-flex align-items-center justify-content-between border-bottom py-2 p-3">
                                <div class="d-flex align-items-center totalimg">
                                    <img src="${item.image}" alt="${item.title}" style="width:50px;">
                                    <div class="mx-3">
                                        <p class="mb-0">${item.title}</p>
                                        <small class="text-success">
                                            ₹${price} ${price < originalPrice ? `<s class="text-muted">₹${originalPrice}</s>` : ''}
                                        </small>
                                    </div>
                                </div>
                                <div class="input-group input-group-sm sidecartbutton" style="width: 90px;">
                                    <button class="btn btn-danger decrement-btn" data-key="${key}">-</button>
                                    <input type="text" class="form-control text-center quantity-input" value="${quantity}" disabled>
                                    <button class="btn btn-danger increment-btn" data-key="${key}">+</button>
                                </div>
                            </div>
                        `;
                    });

                    html += `</div>`; // close business group
                });
            } else {
                html = `<p class="text-center">Your cart is empty.</p>`;
            }

            $('.cartItemsWrapper').html(html);

            // update grand total
            updateBillSummary(total);
        }

        function updateBillSummary(total) {
            // Static values for demonstration
            let deliveryCharge = 0;
            let couponDiscount = 0;
            let walletDiscount = 0;

            let grandTotal = total + deliveryCharge - couponDiscount - walletDiscount;

            $('.billSummary').html(`
                <ul class="list-unstyled small">
                <li class="d-flex justify-content-between">
                    <span>Item charge</span><span>₹${total}</span>
                </li>
                <li class="d-flex justify-content-between">
                    <span>Delivery Charges</span><span>₹${deliveryCharge}</span>
                </li>
                <li class="d-flex justify-content-between">
                    <span>Coupon Discount</span><span class="text-success">- ₹${couponDiscount}</span>
                </li>
                <li class="d-flex justify-content-between">
                    <span>Wallet Discount</span><span class="text-success">- ₹${walletDiscount}</span>
                </li>
                </ul>
            `);

            $('.proceed-btn').html(`Login to Proceed`);
            $('.grand-total-box strong').html(`₹${grandTotal}`);

            // ✅ Update top rupee symbol box also
            $('#rupee-symbol-sidecart').text(`₹ ${grandTotal}/-`);
        }

        // Sidebar Controls
        function openCart() {
            $('#cartSidebar').css('right', '0');
        }
        function closeCart() {
            $('#cartSidebar').css('right', '-100%');
        }
    </script>

    <!-- Product Variant Modal & Cart Operations -->
    <script type="text/javascript">
        const getVariantUrl = "{{ url('/get-product-variants') }}";

        // 🔹 Keep global cart data
        let currentCart = {};

        // Load cart initially
        $.get("{{ route('cart.data') }}", function (res) {
            $('.cart-count').text(res.count);
            loadSideCartItems(res.cart);
            currentCart = res.cart; // store globally
        });

        // Open product popup
        function openPopup(productId) {
            const modal = new bootstrap.Modal(document.getElementById('productModal'));
            const finalUrl = `${getVariantUrl}/${productId}`;

            $('#productModalLabel').text("Loading...");
            $('#variantList').html('<p>Loading...</p>');

            fetch(finalUrl)
                .then(res => res.json())
                .then(data => {
                    $('#productModalLabel').text(data.product_name);

                    let html = '';
                    data.variants.forEach(variant => {
                        const attr = JSON.parse(variant.attributes || '{}');
                        const volume = attr.Volume || '';
                        const actual = variant.variant_actual_price;
                        const selling = variant.variant_selling_price;

                        const key = `${productId}_${variant.id}`;
                        let qtyBoxHtml = '';

                        // 🔹 Check if this variant exists in cart
                        let qty = findQtyInCart(key);

                        if (qty > 0) {
                            qtyBoxHtml = `
                                <div class="qty-container">
                                    <button class="qty-btn minus decrement-btn" data-key="${key}">−</button>
                                    <input type="text" class="qty-input quantity-input" value="${qty}" readonly>
                                    <button class="qty-btn plus increment-btn" data-key="${key}">+</button>
                                </div>
                            `;
                        } else {
                            qtyBoxHtml = `
                                <button class="add-btn btn btn-sm btn-outline-success"
                                    data-product-id="${productId}"
                                    data-variant-id="${variant.id}">
                                    Add <i class="fas fa-shopping-cart ms-1"></i>
                                </button>
                            `;
                        }

                        html += `
                            <div class="unit-item d-flex align-items-center justify-content-between border-bottom py-2">
                                <div class="d-flex align-items-start">
                                    <img src="${data.image}" class="unit-image me-3" style="width:60px;height:60px;" alt="${data.product_name}">
                                    <div>
                                        <div class="fw-bold text-dark">${data.product_name}</div>
                                        <div class="text-muted small">${volume}</div>
                                        <div class="d-flex align-items-baseline gap-2">
                                            <span class="original-price text-decoration-line-through">₹ ${actual}</span>
                                            <span class="price fw-bold">₹ ${selling}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="qty-box" data-product-id="${productId}" data-variant-id="${variant.id}">
                                    ${qtyBoxHtml}
                                </div>
                            </div>
                        `;
                    });

                    $('#variantList').html(html);
                    modal.show();
                })
                .catch(() => {
                    $('#variantList').html('<p class="text-danger">Failed to load variants.</p>');
                });
        }

        // 🔹 Helper: find qty in currentCart
        function findQtyInCart(key) {
            let qty = 0;
            $.each(currentCart, function (business, items) {
                if (items[key]) {
                    qty = items[key].quantity;
                }
            });
            return qty;
        }

        // 🔹 Update global cart on every AJAX success
        $(document).ajaxSuccess(function (event, xhr, settings, response) {
            if (response && response.cart) {
                currentCart = response.cart;
            }
        });

        // 🔹 Increment inside popup
        $(document).on("click", "#variantList .increment-btn", function () {
            let key = $(this).data("key");
            let input = $(this).siblings(".quantity-input");

            $.ajax({
                url: "{{ route('cart.increment') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    key: key
                },
                success: function (res) {
                    $('.cart-count').text(res.count);
                  //  loadSideCartItems(res.cart);

                    // Always update from server response
                    let updatedQty = getQtyFromResponse(res.cart, key);

                    if (updatedQty !== null) {
                        input.val(updatedQty);
                    }

                    currentCart = res.cart;
                }
            });
        });

        // 🔹 Decrement inside popup
        $(document).on("click", "#variantList .decrement-btn", function () {
            let key = $(this).data("key");
            let input = $(this).siblings(".quantity-input");

            $.ajax({
                url: "{{ route('cart.decrement') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    key: key
                },
                success: function (res) {
                    $('.cart-count').text(res.count);
                   // loadSideCartItems(res.cart);

                    let updatedQty = getQtyFromResponse(res.cart, key);

                    if (updatedQty !== null && updatedQty > 0) {
                        input.val(updatedQty);
                    } else {
                        // If removed, show Add button again
                        let parent = input.closest(".qty-box");
                        parent.html(`
                            <button class="add-btn btn btn-sm btn-outline-success"
                                data-product-id="${parent.data("product-id")}"
                                data-variant-id="${parent.data("variant-id")}">
                                Add <i class="fas fa-shopping-cart ms-1"></i>
                            </button>
                        `);
                    }

                    currentCart = res.cart;
                }
            });
        });

        // 🔹 Helper: safely extract qty from response
        function getQtyFromResponse(cart, key) {
            let qty = null;
            $.each(cart, function (business, items) {
                if (items[key]) {
                    qty = items[key].quantity;
                }
            });
            return qty;
        }

    </script>

    <!-- Proceed to checkout -->
    <Script type="text/javascript">
        // Proceed to checkout
        $('.proceed-btn').on('click', function () {
            $.ajax({
                url: "{{ route('check.auth.status') }}", // Create this route
                method: "GET",
                success: function (res) {
                    if (res.logged_in) {
                        window.location.href = "{{ route('checkout.page') }}";
                        window.location.href = "{{ route('cart.view') }}";
                    } else {
                        // Option 1: Redirect to login page
                        window.location.href = "{{ route('login') }}";

                        // Option 2 (if popup):
                         //$('#loginModal').modal('show');
                    }
                }
            });
        });

        // Auto open login popup on mobile

        document.addEventListener("DOMContentLoaded", function () {
            if (window.innerWidth <= 576) {
                setTimeout(() => {
                const loginBox = document.querySelector('.log-in-box');
                if (loginBox) {
                    loginBox.classList.add('show');
                }
                }, 200);
            }
        });

    </Script>


    <!-- Google Maps API (for location autocomplete) -->
    <script type="text/javascript">
        const popup = document.getElementById("addpopPopup");
        const overlay = document.getElementById("addpopOverlay");
        const trigger = document.querySelector(".location-boxs");
        const headerLocationDesktop = document.querySelector(".location-text");
        const headerLocationMobile = document.querySelector(".locations-text");
        const selectedLocationEl = document.getElementById("selected-location");

        let googlemapkey = "{{ env('GOOGLE_MAPS_API_KEY') }}";

        // --- Popup Handling ---
        function toggleAddpop(e){
            e.stopPropagation();
            const isMobile = window.innerWidth <= 768;

            if (isMobile) {
                popup.classList.add("addpop-mobile-active");
            } else {
                const rect = trigger.getBoundingClientRect();
                popup.style.left = rect.left + "px";
                popup.style.top = rect.bottom + window.scrollY + "px";
                popup.classList.add("addpop-desktop-active");
                overlay.classList.add("addpop-visible");
            }
            document.body.style.overflow = 'hidden';
        }

        function closeAddpop(){
            popup.classList.remove("addpop-mobile-active", "addpop-desktop-active");
            overlay.classList.remove("addpop-visible");
            document.body.style.overflow = 'auto';
        }

        window.addEventListener("click", () => closeAddpop());
        popup.addEventListener("click", (e) => e.stopPropagation());

        // --- Detect Current Location (with reverse geocoding) ---
        function detectLocation(){
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition((position) => {
                    let lat = position.coords.latitude;
                    let lng = position.coords.longitude;

                    reverseGeocode(lat, lng);
                }, (err) => {
                    console.warn("Geolocation error:", err);
                });
            } else {
                console.warn("Geolocation not supported by this browser.");
            }
        }

        async function reverseGeocode(lat, lng){
            let geocodeUrl = `https://maps.googleapis.com/maps/api/geocode/json?latlng=${lat},${lng}&key=${googlemapkey}`;
            try {
                let response = await fetch(geocodeUrl);
                let data = await response.json();
                if (data.status === "OK" && data.results.length) {
                    let result = data.results[0];
                    let address = result.formatted_address;
                    updateLocation(address, result, lat, lng);
                } else {
                    console.warn("Unable to detect location. Try searching manually.");
                }
            } catch (error) {
                console.error(error);
            }
        }

        // --- Initialize Google Autocomplete ---
        function initAutocomplete() {
            let input = document.getElementById("autocomplete");
            if (!input) return;

            let autocomplete = new google.maps.places.Autocomplete(input);
            autocomplete.addListener("place_changed", function () {
                let place = autocomplete.getPlace();
                if (place.geometry && place.geometry.location) {
                    let lat = place.geometry.location.lat();
                    let lng = place.geometry.location.lng();
                    let fullAddress = place.formatted_address || place.name;
                    updateLocation(fullAddress, place, lat, lng);
                }
            });
        }

        // --- Helper: shorten address for header ---
        function getShortAddress(fullAddress, place = null) {
            if (place && place.address_components) {
                let components = place.address_components;
                let sublocality = components.find(c =>
                    c.types.includes("sublocality") || c.types.includes("sublocality_level_1")
                );
                let neighborhood = components.find(c => c.types.includes("neighborhood"));
                let city = components.find(c => c.types.includes("locality"));
                let state = components.find(c => c.types.includes("administrative_area_level_1"));

                let area = sublocality ? sublocality.long_name : (neighborhood ? neighborhood.long_name : "");

                if (area || city || state) {
                    return `${area ? area + ", " : ""}${city ? city.long_name + ", " : ""}${state ? state.long_name : ""}`;
                }
            }
            return fullAddress.length > 40 ? fullAddress.substring(0, 40) + "..." : fullAddress;
        }

        // --- Update Location Everywhere + Reload Products ---
        function updateLocation(fullAddress, place = null, lat = null, lng = null){
            let shortAddress = getShortAddress(fullAddress, place);

            // Get old location
            let oldLocation = localStorage.getItem("userLocation");
            let newLocation = JSON.stringify({
                fullAddress: fullAddress,
                shortAddress: shortAddress,
                lat: lat,
                lng: lng
            });

            // Save new location
            localStorage.setItem("userLocation", newLocation);

            // Hidden inputs
            document.getElementById("latitude").value = lat || "";
            document.getElementById("longitude").value = lng || "";
            // Set search form hidden fields
            document.getElementById("search-latitude").value = lat || "";
            document.getElementById("search-longitude").value = lng || "";
            document.getElementById("mobile-search-latitude").value = lat || "";
            document.getElementById("mobile-search-longitude").value = lng || "";

            // Show full in popup
            if (selectedLocationEl) {
                selectedLocationEl.innerText = "📍 " + fullAddress;
            }

            // Show short in header
            if (headerLocationDesktop) headerLocationDesktop.innerHTML = "Current Location <br>" + shortAddress;
            if (headerLocationMobile) headerLocationMobile.innerHTML = "Current Location <br>" + shortAddress;

            closeAddpop();

            // 👉 If location changed (not same as old one), clear cart + redirect home
            if (oldLocation !== newLocation) {
                $.post("{{ route('cart.clear') }}", {
                    _token: "{{ csrf_token() }}"
                }, function () {
                    // Reset cart count/UI
                    $('.cart-count').text(0);
                    if (typeof loadSideCartItems === "function") loadSideCartItems({});
                    if (typeof currentCart !== "undefined") currentCart = {};

                    // Redirect to home
                    window.location.href = "{{ url('/') }}";
                });
                return; // prevent further execution (avoid AJAX reload here)
            }

            // --- If same location, just reload products if coords exist ---
            if (lat && lng) {
                $.ajax({
                    url: "{{ route('location.products') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        latitude: lat,
                        longitude: lng
                    },
                    success: function(res){
                        $('#trending-products-section').html(res.trending_html);
                        $('#stores-section').html(res.stores_html);
                        $('#categories-section').html(res.categories_html);

                        initializeOwlCarousels();
                    }
                });
            }
        }

        // --- On Page Load ---
        window.addEventListener("DOMContentLoaded", () => {
            let savedLocation = localStorage.getItem("userLocation");

            if (savedLocation) {
                // ✅ Only restore UI, no redirect, no cart clear
                let loc = JSON.parse(savedLocation);

                document.getElementById("latitude").value = loc.lat || "";
                document.getElementById("longitude").value = loc.lng || "";
                // Set search form hidden fields
                document.getElementById("search-latitude").value = loc.lat || "";
                document.getElementById("search-longitude").value = loc.lng || "";
                document.getElementById("mobile-search-latitude").value = loc.lat || "";
                document.getElementById("mobile-search-longitude").value = loc.lng || "";

                if (headerLocationDesktop) headerLocationDesktop.innerHTML = "Current Location <br>" + getShortAddress(loc.fullAddress);
                if (headerLocationMobile) headerLocationMobile.innerHTML = "Current Location <br>" + getShortAddress(loc.fullAddress);
                if (selectedLocationEl) selectedLocationEl.innerText = "📍 " + loc.fullAddress;

                // ✅ Load products for saved location
                if (loc.lat && loc.lng) {
                    $.ajax({
                        url: "{{ route('location.products') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            latitude: loc.lat,
                            longitude: loc.lng
                        },
                        success: function(res){
                            $('#trending-products-section').html(res.trending_html);
                            $('#stores-section').html(res.stores_html);
                            $('#categories-section').html(res.categories_html);

                            initializeOwlCarousels();
                        }
                    });
                }

            } else {
                detectLocation();
            }
        });
        // --- Function to initialize/reinitialize Owl Carousels ---
        function initializeOwlCarousels() {
            $('.owl-carousel').trigger('destroy.owl.carousel');
            $('.owl-carousel').removeClass('owl-loaded owl-hidden');
            $('.owl-carousel').find('.owl-stage-outer, .owl-stage, .owl-item').remove();

            $('.owl-carousel').owlCarousel({
                loop: false,
                margin: 10,
                nav: true,
                responsive: {
                    0: { items: 2 },
                    600: { items: 3 },
                    1000: { items: 4 }
                }
            });
        }


        // --- Redirect helper ---
        function redirectWithLocation(baseUrl) {
            let savedLocation = localStorage.getItem("userLocation");

            if (savedLocation) {
                try {
                    let loc = JSON.parse(savedLocation);

                    if (loc.lat && loc.lng) {
                        window.location.href = `${baseUrl}?latitude=${encodeURIComponent(loc.lat)}&longitude=${encodeURIComponent(loc.lng)}`;
                        return false;
                    }
                } catch (e) {
                    console.error("Invalid saved location:", e);
                }
            }

            window.location.href = baseUrl;
            return false;
        }
    </script>

    <input type="hidden" id="latitude" name="latitude">
    <input type="hidden" id="longitude" name="longitude">

    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places&callback=initAutocomplete" async defer></script>
    <script>
        const searchSuggestionsUrl = "{{ route('search.suggestions') }}";

        // Helper to get location params
        function getSuggestionLocationParams() {
            let params = '';
            let lat = document.getElementById("search-latitude").value || document.getElementById("mobile-search-latitude").value;
            let lng = document.getElementById("search-longitude").value || document.getElementById("mobile-search-longitude").value;
            if (lat) params += `&latitude=${encodeURIComponent(lat)}`;
            if (lng) params += `&longitude=${encodeURIComponent(lng)}`;
            return params;
        }
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let searchBox = document.getElementById("search-box");
            let suggestionsBox = document.getElementById("suggestions-box");
            let form = document.getElementById("search-form");

            // Load last searches from localStorage
            function showLastSearches() {
                let lastSearches = JSON.parse(localStorage.getItem("lastSearches")) || [];
                suggestionsBox.innerHTML = "";
                if (lastSearches.length > 0) {
                    lastSearches.forEach(item => {
                        let li = document.createElement("li");
                        li.classList.add("list-group-item", "d-flex", "align-items-center");
                        li.innerHTML = `<img src="{{ asset('public/marker.png') }}"
                                            class="me-2" style="width:30px; height:30px; object-fit:cover; border-radius:5px;">
                                        <span>${item}</span>`;
                        li.addEventListener("click", () => {
                            searchBox.value = item;
                            suggestionsBox.classList.add("d-none");
                        });
                        suggestionsBox.appendChild(li);
                    });
                    suggestionsBox.classList.remove("d-none");
                } else {
                    suggestionsBox.classList.add("d-none");
                }
            }

            // On focus show last searches
            searchBox.addEventListener("focus", showLastSearches);

            // On keyup fetch suggestions
            searchBox.addEventListener("keyup", function() {
                let query = this.value.trim();
                if (query.length < 2) {
                    showLastSearches();
                    return;
                }

                fetch(`${searchSuggestionsUrl}?q=${encodeURIComponent(query)}${getSuggestionLocationParams()}`)
                    .then(res => res.json())
                    .then(data => {
                        suggestionsBox.innerHTML = "";
                        if (data.length > 0) {
                           data.forEach(item => {
                                let li = document.createElement("li");
                                li.classList.add("list-group-item", "d-flex", "align-items-center");
                                li.style.cursor = "pointer";
                                li.innerHTML = `
                                    <img src="${item.image}"
                                        class="me-2" style="width:40px; height:40px; object-fit:cover; border-radius:5px;">
                                    <span>${item.title}</span>
                                `;
                                li.addEventListener("click", () => {
                                    searchBox.value = item.title;
                                    form.submit(); // ✅ redirect to searchresults with ?q=item.title
                                    suggestionsBox.classList.add("d-none");
                                });
                                suggestionsBox.appendChild(li);
                            });

                            suggestionsBox.classList.remove("d-none");
                        } else {
                            suggestionsBox.classList.add("d-none");
                        }
                    })
                    .catch(err => {
                        console.error("Search error:", err);
                        suggestionsBox.classList.add("d-none");
                    });
            });

            // Save searches on form submit
            form.addEventListener("submit", function() {
                let query = searchBox.value.trim();
                if (query) {
                    let lastSearches = JSON.parse(localStorage.getItem("lastSearches")) || [];
                    if (!lastSearches.includes(query)) {
                        lastSearches.unshift(query); // add to top
                        if (lastSearches.length > 5) lastSearches.pop(); // keep max 5
                        localStorage.setItem("lastSearches", JSON.stringify(lastSearches));
                    }
                }
            });

            // Hide suggestions when clicking outside
            document.addEventListener("click", function(e) {
                if (!form.contains(e.target)) {
                    suggestionsBox.classList.add("d-none");
                }
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
