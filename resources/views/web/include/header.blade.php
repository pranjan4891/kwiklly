<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Kwiklly</title>
      <meta name="csrf-token" content="{{ csrf_token() }}">

      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" />
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
      <link rel="stylesheet" href="{{ asset('public/assets/website/CSS/style.css')}}">
      <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
      <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
   </head>
   <style>
     .location-box {
            display: flex;
            align-items: center;
            cursor: pointer;
            padding: 7px 11px;
            transition: all 0.3s ease;
        }

        .location-box:hover {
            border-color: #4a89dc;
            box-shadow: 0 2px 8px rgba(74, 137, 220, 0.2);
        }

        .location-box i {
            margin-right: 10px;
            color: #4a89dc;
        }

        .location-text {
            font-size: 14px;
            margin-right: 10px;
            line-height: 1.4;
        }

        .dropdown-icon {
            color: #888;
            font-size: 12px;
        }

        .addpop-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            z-index: 1000;
        }

        .addpop-popup {
            position: absolute;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            padding: 25px;
            width: 90%;
            max-width: 500px;
            z-index: 1001;
            display: none;
        }

        .addpop-desktop-active {
            display: block;
        }

        .addpop-mobile-active {
            display: block;
            bottom: 0;
            left: 0;
            width: 100%;
            max-width: 100%;
            border-radius: 12px 12px 0 0;
            animation: slideUp 0.3s ease;
        }

        @keyframes slideUp {
            from {
                transform: translateY(100%);
            }
            to {
                transform: translateY(0);
            }
        }

        .addpop-visible {
            display: block;
        }

        .addpop-close-btn {
            position: absolute;
            top: 15px;
            right: 15px;
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #888;
        }

        .addpop-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #333;
        }

        .addpop-actions {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .addpop-detect-btn {
            padding: 12px 20px;
            background-color: #4a89dc;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            transition: background-color 0.3s;
        }

        .addpop-detect-btn:hover {
            background-color: #3a76c5;
        }

        .addpop-or-text {
            text-align: center;
            color: #888;
            font-size: 14px;
            position: relative;
        }

        .addpop-or-text:before,
        .addpop-or-text:after {
            content: '';
            position: absolute;
            top: 50%;
            width: 40%;
            height: 1px;
            background: #e0e0e0;
        }

        .addpop-or-text:before {
            left: 0;
        }

        .addpop-or-text:after {
            right: 0;
        }

        .addpop-search-container {
            position: relative;
            z-index: 1003; /* Higher z-index for the container */
        }

        .addpop-search-input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            position: relative;
            z-index: 1004;
        }

        /* Google Autocomplete dropdown styling */
        .pac-container {
            z-index: 1005 !important; /* Higher z-index for suggestions */
            border-radius: 0 0 8px 8px;
            border-top: none;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-top: -5px;
        }

        .pac-item {
            padding: 10px 15px;
            cursor: pointer;
            border-bottom: 1px solid #f0f0f0;
        }

        .pac-item:hover {
            background-color: #f5f9ff;
        }

        .pac-item-query {
            font-size: 14px;
            color: #333;
        }

        .selected-location {
            margin-top: 20px;
            padding: 15px;
            background: #f9f9f9;
            border-radius: 8px;
            font-size: 14px;
            border-left: 3px solid #4a89dc;
        }

        .content {
            padding: 30px 0;
        }

        .store-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .store-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s;
        }

        .store-card:hover {
            transform: translateY(-5px);
        }

        .store-image {
            height: 160px;
            background-color: #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #888;
        }

        .store-details {
            padding: 15px;
        }

        .store-name {
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 16px;
        }

        .store-address {
            color: #666;
            font-size: 14px;
            margin-bottom: 12px;
        }

        .store-tags {
            display: flex;
            gap: 8px;
        }

        .store-tag {
            background: #f0f7ff;
            color: #4a89dc;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
        }

        .powered-by {
            text-align: center;
            margin-top: 30px;
            color: #888;
            font-size: 12px;
        }

        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .location-box {
                width: 100%;
            }

            .addpop-popup {
                width: 100%;
                max-width: 100%;
            }
        }

   </style>
   <body>
      <!-- Desktop side cart html start  -->
      <div class="cart-sidebar position-fixed top-0 end-0 bg-white shadow" style="width: 25%; height: 100vh;  -index: 1050; overflow-y: auto; transition: all 0.3s;" id="cartSidebar">
         <div class="d-flex justify-content-between align-items-center p-3" style="box-shadow: 0 2px 2px rgb(0 0 0 / 25%);position: sticky;top: 0;background-color: white;z-index: 999;">
            <h5 class="fw-bold"> <i class="fa-solid fa-arrow-left me-3" onclick="document.getElementById('cartSidebar').classList.remove('show')"></i>My Cart</h5>

            <div class="">
               <button class="btn btn-success btn-sm"> <span class="rupee-symbol-sidecart ms-2" id="rupee-symbol-sidecart" style="top:0px; margin-right: 0px;">₹ 30/-</span> </button>
            </div>

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
      {{-- <div class="pata-sidebar-overlay" id="pataSidebar">
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
      </div> --}}
      <!-- mobile side cart html start  -->
      <div class="cart-sidebar position-fixed top-0 end-0 bg-white shadow" style="width: fit-content; height: 100vh;  -index: 1050; overflow-y: auto;" id="cartSidebar2">
         <div class="d-flex justify-content-between align-items-center p-3" style="box-shadow: 0 2px 2px rgb(0 0 0 / 25%);position: sticky;top: 0;background-color: white;z-index: 999;">
            <h5 class="fw-bold">My Cart</h5>
            <div class="">
               <button class="btn btn-success btn-sm"><span class="rupee-symbol-sidecart ms-2" id="rupee-symbol-sidecart">₹ 30/-</span></button>
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
               <a class="navbar-brand" href="{{route('home')}}" >
               <img src="{{ asset('public/assets/website/images/logo.png')}}" alt="Logo">
               </a>
               <div class="location-box mx-5"  onclick="toggleAddpop(event)">
                  <i class="fas fa-map-marker-alt"></i>
                  <span class="location-text">Current Location <br>Radhe Krishna Mandir, Sa...</span>
                  <i class="fas fa-chevron-down dropdown-icon"></i>
               </div>
               <div class="search-container ms-3">
                    <form action="{{ route('searchresults') }}" method="GET" class="d-flex w-100 position-relative" id="search-form">
                        <input type="hidden" id="search-latitude" name="latitude">
                        <input type="hidden" id="search-longitude" name="longitude">
                        <input type="text" name="q" id="search-box"
                            class="form-control search-box"
                            placeholder='Search "Banana"' autocomplete="off" required />

                        <button type="submit" class="search-btn my-1">
                            <i class="fas fa-search"></i>
                        </button>

                        <!-- Suggestions Dropdown -->
                        <ul id="suggestions-box"
                            class="list-group position-absolute w-100 d-none"
                            style="top: 100%; z-index: 1000;"></ul>
                    </form>
               </div>
            </div>
            <!-- Desktop Menu (Hidden in Mobile) -->
            <div class="d-flex align-items-center desktop-menu">
               <a href="{{ route('department')}}" onclick="return redirectWithLocation(this.href)">Department</a>
               <a href="{{ route('stores', ['slug' => 'all'])}}" onclick="return redirectWithLocation(this.href)">Store</a>
               @if(auth()->check())
               <a href="{{ route('customer.dashboard') }}">Account</a>
               @else
               <a href="{{ route('login') }}" >Login</a>
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
                  <input type="hidden" id="mobile-search-latitude" name="latitude">
                  <input type="hidden" id="mobile-search-longitude" name="longitude">
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
         <a href="{{url('/')}}" class="nav-item nav-link" onclick="return redirectWithLocation(this.href)">
         <img src="{{ asset('public/assets/website/images/footlogo.png')}}" alt="" style="height:25px; margin-bottom:6px;">&nbsp;Kwiklly&nbsp;
         </a>
         <a href="department.php" class="nav-item nav-link">
         <img src="{{ asset('public/assets/website/images/departmenticon.png')}}" alt="" style="height:25px; margin-bottom:6px;" onclick="return redirectWithLocation(this.href)">Department
         </a>
         <a href="{{ route('stores', ['slug' => 'all'])}}" class="nav-item nav-link" onclick="return redirectWithLocation(this.href)">
         <img src="{{ asset('public/assets/website/images/storeicon.png')}}" alt="" style="height:25px; margin-bottom:6px;"> &nbsp;&nbsp;Store&nbsp;&nbsp;
         </a>
         <a href="{{ route('login') }}" class="nav-item nav-link"  >
         <img src="{{ asset('public/assets/website/images/joinicon.png')}}" alt="" style="height:25px; margin-bottom:6px;">&nbsp;&nbsp; Login&nbsp;&nbsp;
         </a>
      </div>
