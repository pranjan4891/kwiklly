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
      <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
   </head>
   <style>
      .change-link {
      padding: 15px;
      font-weight: bold;
      color: #ff4d4d;
      cursor: pointer;
      }
      .sidebar {
      position: fixed;
      top: 0;
      right: -100%;
      width: 100%;
      max-width: 520px;
      height: 100%;
      background: #fff;
      box-shadow: -3px 0 8px rgba(0, 0, 0, 0.15);
      z-index: 999999;
      transition: right 0.3s ease-in-out;
      border-left: 1px solid #ccc;
      }
      .sidebar.open {
      right: 0;
      }
      .sidebar-header {
      display: flex;
      align-items: center;
      padding: 16px;
      border-bottom: 1px solid #e0e0e0;
      font-size: 18px;
      font-weight: bold;
      }
      .sidebar-header i {
      margin-right: 10px;
      font-size: 20px;
      cursor: pointer;
      }
      .add-new-address {
      text-align: center;
      color: red;
      font-weight: 600;
      padding: 12px;
      border-bottom: 1px solid #f0f0f0;
      }
      .add-new-address:hover{
      text-align: center;
      color: red;
      font-weight: 600;
      padding: 12px;
      border-bottom: 1px solid #f0f0f0;
      cursor:pointer;
      }
      .address-section {
      padding: 16px;
      padding-bottom:45px;
      }
      .section-title {
      font-size: 14px;
      color: #666;
      margin-bottom: 12px;
      }
      .address-card {
      display: flex;
      align-items: flex-start;
      background: #fff;
      border: 1px solid #eee;
      border-radius: 6px;
      padding: 12px;
      margin-bottom: 12px;
      position: relative;
      }
      .address-card.active {
      background: #f3fff3;
      border-color: #c1eac5;
      }
      .icon {
      margin-right: 10px;
      font-size: 20px;
      margin-top: 3px;
      }
      .address-content {
      flex: 1;
      font-size: 14px;
      line-height: 1.4;
      color: #333;
      }
      .address-content b {
      display: block;
      margin-bottom: 4px;
      }
      .tick-icon {
      font-size: 16px;
      color: green;
      margin-left: 8px;
      margin-top: 2px;
      }
      .menu-icon {
      position: absolute;
      right: 12px;
      top: 16px;
      font-size: 18px;
      color: #999;
      cursor: pointer;
      }
      /* Responsive */
      @media screen and (max-width: 480px) {
      .sidebar {
      max-width: 100%;
      }
      }
   </style>
   <body>
      <!-- Desktop side cart html start  -->
      <div class="cart-sidebar position-fixed top-0 end-0 bg-white shadow" style="width: fit-content; height: 100vh;  -index: 1050; overflow-y: auto; transition: all 0.3s;" id="cartSidebar">
         <div class="d-flex justify-content-between align-items-center p-3" style="box-shadow: 0 2px 2px rgb(0 0 0 / 25%);position: sticky;top: 0;background-color: white;z-index: 999;">
            <h5 class="fw-bold"> <i class="fa-solid fa-arrow-left me-3" onclick="document.getElementById('cartSidebar').classList.remove('show')"></i>My Cart</h5>
            {{--
            <div class="">
               <button class="btn btn-success btn-sm"><i class="fa fa-wallet"></i> <span class="rupee-symbol-sidecart ms-2">₹</span> 30</button>
            </div>
            --}}
         </div>
         <div class=" p-3 rounded my-3 p-3">
            <div id="deliveryOptions" class="" style="display: none;">
               <div class="mt-3">
                  <div class="d-flex align-items-center mb-2">
                     <input type="radio" name="deliveryOption" class="form-check-input me-2" checked>
                     <div class="row w-100 gx-2">
                        <div class="col">
                           <select class="form-select form-select-sm">
                              <option>Tomorrow, 11/24</option>
                              <option>Today, 11/23</option>
                           </select>
                        </div>
                        <div class="col">
                           <select class="form-select form-select-sm">
                              <option>10:00 AM - 1:00 PM</option>
                              <option>1:00 PM - 4:00 PM</option>
                           </select>
                        </div>
                     </div>
                  </div>
                  <div class="form-check mb-3">
                     <input type="radio" name="deliveryOption" class="form-check-input me-2" id="expressOption">
                     <label for="expressOption" class="form-check-label fw-semibold">
                     Get order in 20 min for ₹5000
                     </label>
                  </div>
                  <div class="text-center">
                     <span class="text-muted d-block mb-2">or</span>
                     <button class="btn btn-success w-100" id="expressBtn">
                     <i class="fa fa-bolt me-1"></i> Express Delivery in 20 mins
                     </button>
                  </div>
               </div>
            </div>
            <!-- Cart Items -->
            <!-- Cart Items -->
            <div class="cartItemsWrapper" id="cartItemsWrapper">
               <!-- items will be injected dynamically -->
            </div>
            <div class="bottom-bar">
               <div class="proceed-btn"></div>
            </div>
         </div>
      </div>
      <!--Desktop side cart html end  -->
      <!-- Sidebar -->
      <div class="pata-sidebar-overlay" id="pataSidebar">
         <div class="pata-sidebar">
            <!-- Header -->
            <div class="pata-sidebar-header">
               <span class="pata-back-btn" onclick="closePataSidebar()"><i class="fa-solid fa-arrow-left"></i></span>
               <h5 class="mb-0">New Address</h5>
            </div>
            <!-- BODY -->
            <div class="p-3 pataoverflow">
               <!-- Your Location Title -->
               <div class="pata-location-title">Your Location</div>
               <div class="pata-location-desc">
                  Cisf ground, gali no 2, near metro station gate no 3, saket, Delhi
               </div>
               <!-- Buttons: Home / Work -->
               <div class="d-flex justify-content-between pata-tag-buttons mb-3">
                  <button type="button" id="pataHomeBtn" class="pata-home active">🏠 Home</button>
                  <button type="button" id="pataWorkBtn" class="pata-work">🏢 Work</button>
               </div>
               <!-- Form Start -->
            </div>
         </div>
      </div>
      <!-- mobile side cart html start  -->
      <div class="cart-sidebar position-fixed top-0 end-0 bg-white shadow" style="width: fit-content; height: 100vh;  -index: 1050; overflow-y: auto;" id="cartSidebar2">
         <div class="d-flex justify-content-between align-items-center p-3" style="box-shadow: 0 2px 2px rgb(0 0 0 / 25%);position: sticky;top: 0;background-color: white;z-index: 999;">
            <h5 class="fw-bold">My Cart</h5>
            <div class="">
               {{-- <button class="btn btn-success btn-sm"><i class="fa fa-wallet"></i> <span class="rupee-symbol-sidecart ms-2">₹</span> 30</button> --}}
               <button class="btn-close mx-2" onclick="document.getElementById('cartSidebar2').classList.remove('show')"></button>
            </div>
         </div>
         <!-- Cart Items -->
         <div class="cartItemsWrapper" id="cartItemsWrapper">
            <!-- items will be injected dynamically -->
         </div>
         <!-- Bottom Fixed Section -->
         <div class="bottom-bar">
            <div class="proceed-btn">Proceed to Pay ₹347</div>
         </div>
      </div>
      </div>
      <!-- mobile side cart html end  -->
      <!-- Navbar -->
      <nav class="navbar navbar-expand-lg fixed-top">
         <div class="container-fluid">
            <!-- Desktop: Logo + Location & Search -->
            <div class="d-flex align-items-center w-100 d-none d-md-flex">
               <a class="navbar-brand" href="{{url('/')}}">
               <img src="{{ asset('public/assets/website/images/logo.png')}}" alt="Logo">
               </a>
               <div class="location-box mx-5"  onclick="toggleAddpop(event)">
                  <i class="fas fa-map-marker-alt"></i>
                  <span class="location-text">Current Location <br>Radhe Krishna Mandir, Sa...</span>
                  <i class="fas fa-chevron-down dropdown-icon"></i>
               </div>
               <div class="search-container ms-3">
                  <form action="{{ route('searchresults') }}" method="GET" class="d-flex w-100">
                     <input type="text" name="q" class="form-control search-box " placeholder='Search "Banana"' required />
                     <button type="submit" class="search-btn my-1">
                     <i class="fas fa-search"></i>
                     </button>
                  </form>
               </div>
            </div>
            <!-- Desktop Menu (Hidden in Mobile) -->
            <div class="d-flex align-items-center desktop-menu">
               <a href="{{ route('department')}}">Department</a>
               <a href="{{ route('stores')}}">Store</a>
               @if(auth()->check())
               <a href="{{ route('customer.dashboard') }}">{{ auth()->user()->name }}</a>
               @else
               <a href="{{ route('login') }}">Login</a>
               @endif
               <button class="cart-btn d-none d-md-flex" id="openCart">
               <i class="fa fa-shopping-cart"></i>
               Cart (<span class="cart-count">{{ session('cart') ? count(session('cart')) : 0 }}</span>)
               </button>
            </div>
            <!-- Mobile: Location and Cart in one row -->
            <div class="mobile-top d-md-none">
               <div class="location-boxs"  onclick="toggleAddpop(event)">
                  <i class="fas fa-map-marker-alt"></i>
                  <span class="locations-text">Current Location <br>Rache Krishna Mandir, Sa...</span>
                  <i class="fas fa-chevron-down dropdown-icon"></i>
               </div>
               <button class="cart-btn" id="openCart2">
               <i class="fas fa-shopping-cart"></i><span class="cart-count">{{ session('cart') ? count(session('cart')) : 0 }}</span>
               </button>
            </div>
            <!-- Mobile Search (Separate Row) -->
            <div class="search-container d-md-none">
               <form action="{{ route('searchresults') }}" method="GET" class="d-flex w-100">
                  <input type="text" name="q" class="form-control search-box " placeholder='Search "Banana"' required />
                  <button type="submit" class="search-btn my-1">
                  <i class="fas fa-search"></i>
                  </button>
               </form>
            </div>
         </div>
      </nav>
      <!-- Overlay for desktop -->
      <div class="addpop-overlay" id="addpopOverlay" onclick="closeAddpop()"></div>
      <!-- Location Popup -->
      <div class="addpop-popup" id="addpopPopup">
         <button class="addpop-close-btn" onclick="closeAddpop()">×</button>
         <p class="addpop-title">Select Delivery Location</p>
         <div class="addpop-actions">
            <button class="addpop-detect-btn" onclick="detectLocation()">Detect Current Location</button>
            <span class="addpop-or-text">or</span>
            <input type="text" id="autocomplete" class="addpop-search-input" placeholder="Search Location" />
         </div>
         <p id="selected-location" class="mt-3 text-sm text-gray-700"></p>
      </div>
      <!-- Bottom Navigation (Only for Mobile) -->
      <div class="bottom-nav d-flex justify-content-around d-md-none">
         <a href="{{url('/')}}" class="nav-item nav-link">
         <img src="{{ asset('public/assets/website/images/footlogo.png')}}" alt="" style="height:25px; margin-bottom:6px;">&nbsp;Kwiklly&nbsp;
         </a>
         <a href="department.php" class="nav-item nav-link">
         <img src="{{ asset('public/assets/website/images/departmenticon.png')}}" alt="" style="height:25px; margin-bottom:6px;">Department
         </a>
         <a href="{{ route('stores')}}" class="nav-item nav-link">
         <img src="{{ asset('public/assets/website/images/storeicon.png')}}" alt="" style="height:25px; margin-bottom:6px;"> &nbsp;&nbsp;Store&nbsp;&nbsp;
         </a>
         <a href="{{ route('login') }}" class="nav-item nav-link">
         <img src="{{ asset('public/assets/website/images/joinicon.png')}}" alt="" style="height:25px; margin-bottom:6px;"> Join&nbsp;Us
         </a>
      </div>
      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
      <script>
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

                    let productId = key.split('_')[0];
                    let variantId = key.split('_')[1];

                    // Use product grid's qty-box selector
                    let productQtyBox = $(`.qty-box[data-product-id="${productId}"][data-variant-id="${variantId}"]`);

                    if (!res.cart || !Object.values(res.cart).some(group => group[key])) {
                        // Item is removed, show "Add" button
                        productQtyBox.html(`
                            <button class="add-btn" data-product-id="${productId}" data-variant-id="${variantId}">
                                Add
                                <img src="{{ asset('public/assets/website/images/cart.svg') }}" class="ms-2">
                            </button>
                        `);
                    } else {
                        // Still exists, update quantity in UI (if needed)
                        $(`[data-key="${key}"] .quantity-input`).val(
                            Object.values(res.cart).find(group => group[key])[key].quantity
                        );
                    }
                });
            });


        });

        // Render cart items in sidebar
        function loadSideCartItems(cartGroups) {
            let html = '';
            let total = 0;

            if (cartGroups && Object.keys(cartGroups).length > 0) {
                $.each(cartGroups, function (businessName, items) {
                    html += `<div class="cart-business-group mb-3">
                        <h6 class="mb-1">${businessName}</h6>
                        <a href="#" class="small text-primary mb-2 d-block">Go to store</a>
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

            $('.proceed-btn').html(`Proceed To Checkout`);
            $('.grand-total-box strong').html(`₹${grandTotal}`);
        }

        // Sidebar Controls
        function openCart() {
            $('#cartSidebar').css('right', '0');
        }
        function closeCart() {
            $('#cartSidebar').css('right', '-100%');
        }
      </script>
      <Script>
        $('.proceed-btn').on('click', function () {
            $.ajax({
                url: "{{ route('check.auth.status') }}", // Create this route
                method: "GET",
                success: function (res) {
                    if (res.logged_in) {
                        //window.location.href = "{{ route('checkout.page') }}";
                        window.location.href = "{{ route('cart.view') }}";
                    } else {
                        // Option 1: Redirect to login page
                        window.location.href = "{{ route('login') }}";

                        // Option 2 (if popup):
                        // $('#loginModal').modal('show');
                    }
                }
            });
        });

      </Script>
      <script>
         // --- Location Popup Logic ---
         const popup = document.getElementById("addpopPopup");
         const overlay = document.getElementById("addpopOverlay");
         const trigger = document.querySelector(".location-boxs");
         const headerLocationDesktop = document.querySelector(".location-text");
         const headerLocationMobile = document.querySelector(".locations-text");
         const selectedLocationEl = document.getElementById("selected-location");

         let googlemapkey = "{{ env('GOOGLE_MAPS_API_KEY') }}";

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

         // --- Detect Current Location ---
         async function detectLocation(){
             if (navigator.geolocation) {
             navigator.geolocation.getCurrentPosition(async (position) => {
                 let lat = position.coords.latitude;
                 let lng = position.coords.longitude;

                 let geocodeUrl = `https://maps.googleapis.com/maps/api/geocode/json?latlng=${lat},${lng}&key=${googlemapkey}`;
                 try {
                 let response = await fetch(geocodeUrl);
                 let data = await response.json();
                 if (data.status === "OK") {
                     let result = data.results[0];
                     let address = result.formatted_address;
                     updateLocation(address, result);
                 } else {
                     console.warn("Unable to detect location. Try searching manually.");
                 }
                 } catch (error) {
                 console.error(error);
                 }
             });
             } else {
             console.warn("Geolocation not supported by this browser.");
             }
         }

         // --- Initialize Google Autocomplete ---
         function initAutocomplete() {
             let input = document.getElementById("autocomplete");
             if (!input) return;

             let autocomplete = new google.maps.places.Autocomplete(input);
             autocomplete.addListener("place_changed", function () {
             let place = autocomplete.getPlace();
             if (place.formatted_address) {
                 updateLocation(place.formatted_address, place);
             } else if (place.name) {
                 updateLocation(place.name, place);
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
             // fallback: just trim long text
             return fullAddress.length > 40 ? fullAddress.substring(0, 40) + "..." : fullAddress;
         }

         // --- Update Location Everywhere ---
         function updateLocation(fullAddress, place = null){
             let shortAddress = getShortAddress(fullAddress, place);

             // show full in popup
             if (selectedLocationEl) {
             selectedLocationEl.innerText = "📍 " + fullAddress;
             }

             // show short in header
             if (headerLocationDesktop) headerLocationDesktop.innerHTML = "Current Location <br>" + shortAddress;
             if (headerLocationMobile) headerLocationMobile.innerHTML = "Current Location <br>" + shortAddress;

             closeAddpop();
         }

         window.initAutocomplete = initAutocomplete;

         // --- Auto-detect location on page load ---
         window.addEventListener("DOMContentLoaded", () => {
             detectLocation();
         });
      </script>
      <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places&callback=initAutocomplete" async defer></script>
