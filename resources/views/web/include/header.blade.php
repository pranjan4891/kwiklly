<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
      <title>Kwiklly</title>
      <meta name="csrf-token" content="{{ csrf_token() }}">

      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" />
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
      <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
      <link rel="stylesheet" href="{{ versioned_asset('public/assets/website/CSS/style.css') }}">
      <style>input.address-locked{background-color:#f0f0f0!important;cursor:not-allowed!important;}</style>
   </head>
   <body class="{{ $bodyClass ?? '' }}">
      <!-- Desktop sidebar cart start  -->
      <div class="cart-sidebar position-fixed top-0 end-0 bg-white shadow" style="width: 27%; height: 100vh; z-index: 1050; display: flex; flex-direction: column; transition: all 0.3s;" id="cartSidebar">
         <div class="d-flex justify-content-between align-items-center p-3" style="box-shadow: 0 2px 2px rgb(0 0 0 / 25%);background-color: white;z-index: 999; flex-shrink: 0;">
            <h5 class="fw-bold"> <i class="fa-solid fa-arrow-left me-3" onclick="document.getElementById('cartSidebar').classList.remove('show')"></i>My Carts</h5>
            <button class="btn-close" onclick="document.getElementById('cartSidebar').classList.remove('show')"></button>

         </div>
         <div class="cart-scrollable-content" style="flex: 1; overflow-y: auto; overflow-x: hidden;">
            <div class="rounded my-1 px-3 py-1">
               <div class="cartItemsWrapper" id="cartItemsWrapper">
                  <!-- items will be injected dynamically -->
               </div>
            </div>
         </div>
         <div class="bottom-bar">
            <div class="proceed-btn"></div>
         </div>
      </div>
      <!--Desktop sidebar cart end -->

      <!-- mobile side cart html start  -->

      <!-- Cart Overlay -->
      <div class="cart-overlay" id="cartOverlay2" onclick="closeMobileCart();" style="display: none;"></div>

      <div class="cart-sidebar position-fixed top-0 end-0 bg-white shadow" style="width: 100%; height: 100vh; z-index: 1051; display: flex; flex-direction: column;" id="cartSidebar2">
         <div class="cart-header-mobile d-flex justify-content-between align-items-center p-3" style="box-shadow: 0 2px 2px rgb(0 0 0 / 25%);background-color: white;z-index: 1000; flex-shrink: 0; width: 100%; min-height: 60px;">
            <h5 class="fw-bold mb-0" style="font-size: 18px; color: #000;">My Cart</h5>
            <button type="button" class="btn btn-close-cart" onclick="closeMobileCart();" style="background: none; border: none; font-size: 24px; color: #000; cursor: pointer; padding: 5px 10px; line-height: 1;">
               <i class="fas fa-times"></i>
            </button>
         </div>
         <!-- Cart Items -->
         <div class="cart-scrollable-content" style="flex: 1; overflow-y: auto; overflow-x: hidden; width: 100%;">
            <div class="cartItemsWrapper" id="cartItemsWrapperMobile" style="padding: 15px; padding-bottom: 10px;">
               <!-- items will be injected dynamically -->
            </div>
         </div>
         <!-- Bottom Fixed Section - Always Visible -->
         <div class="bottom-bar mobile-cart-bottom-bar" style="width: 100%; display: block !important; visibility: visible !important; opacity: 1 !important;">
            <div class="proceed-btn"></div>
         </div>
      </div>
      <!-- mobile side cart html end  -->
      <!-- Navbar -->
      <nav class="navbar navbar-expand-lg fixed-top">
         <div class="container-fluid">
            <!-- Desktop: Logo + Location & Search -->
            <div class="d-flex align-items-center w-100 d-none d-md-flex">
               <a class="navbar-brand" href="{{route('home')}}" onclick="return redirectWithLocation(this.href)">
               <img src="{{ asset('public/assets/website/images/logo.png')}}" alt="Logo">
               </a>
               <div class="location-box mx-5"  onclick="toggleAddpop(event)">
                  <i class="fas fa-map-marker-alt"></i>
                  <span class="location-text">Radhe Krishna Mandir, Sa...</span>
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
            @php
                $headerCartCount = session('cart') ? count(session('cart')) : 0;
            @endphp
            <!-- Desktop Menu (Hidden in Mobile) -->
            <div class="d-flex align-items-center desktop-menu">
               <a href="{{ route('department')}}" onclick="return redirectWithLocation(this.href)">Department</a>
               <a href="{{ route('stores', ['slug' => 'all'])}}" onclick="return redirectWithLocation(this.href)">Store</a>
               @if(auth()->check())
               <a href="{{ route('customer.dashboard') }}">Account</a>
               @else
               <a href="{{ route('loginbyphone') }}" >Login</a>
               @endif
               <button class="cart-btn d-none d-md-flex" id="openCart">
               <i class="fa fa-shopping-cart"></i>
               <span class="cart-count{{ $headerCartCount > 0 ? '' : ' d-none' }}" aria-hidden="{{ $headerCartCount > 0 ? 'false' : 'true' }}">{{ $headerCartCount }}</span>
               </button>
            </div>
            <!-- Mobile: Location and Cart in one row -->
            <div class="mobile-top d-md-none">
               <div class="location-boxs"  onclick="toggleAddpop(event)">
                  <i class="fas fa-map-marker-alt"></i>
                  <span class="locations-text">Radhe Krishna Mandir, Sa...</span>
                  <i class="fas fa-chevron-down dropdown-icon"></i>
               </div>
               <button class="cart-btn" id="openCart2">
               <i class="fas fa-shopping-cart"></i><span class="cart-count{{ $headerCartCount > 0 ? '' : ' d-none' }}" aria-hidden="{{ $headerCartCount > 0 ? 'false' : 'true' }}">{{ $headerCartCount }}</span>
               </button>
            </div>
            <!-- Mobile Search (Separate Row) -->
            <div class="search-container d-md-none">
               <form action="{{ route('searchresults') }}" method="GET" class="d-flex w-100 position-relative" id="mobile-search-form">
                  <input type="hidden" id="mobile-search-latitude" name="latitude">
                  <input type="hidden" id="mobile-search-longitude" name="longitude">
                  <input type="text" name="q" id="mobile-search-box" class="form-control search-box " placeholder='Search "Banana"' autocomplete="off" required />
                  <button type="submit" class="search-btn my-1">
                  <i class="fas fa-search"></i>
                  </button>
                  <!-- Mobile Suggestions Dropdown new-->
                  <ul id="mobile-suggestions-box"
                      class="list-group position-absolute w-100 d-none"
                      style="top: 100%; z-index: 10001; "></ul>
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
         <div class="addpop-actions d-flex flex-md-row">
            <button class="addpop-detect-btn" onclick="detectLocation()">Detect Current Location</button>
            <span class="addpop-or-text">or</span>
            <!-- Desktop autocomplete input -->
            <input type="text" id="autocomplete" class="addpop-search-input w-100 d-none d-md-block" placeholder="Search Location" />
            <!-- Mobile autocomplete input -->
            <input type="text" id="autocomplete-mobile" class="addpop-search-input w-100 d-md-none" placeholder="Search Location" />
         </div>
         <p id="selected-location" class="mt-3 text-sm text-gray-700"></p>
         <div id="location-save-actions" class="mt-3" style="display:none;">
            <button type="button" id="openSaveLocationModalBtn" class="btn btn-success w-100 shadow-sm" style="border-radius: 8px; padding: 10px; font-weight: 600; letter-spacing: 0.3px;">
               <i class="fas fa-bookmark me-2"></i>Save This Location
            </button>
         </div>
         <div id="saved-locations-wrapper" class="mt-4">
            <div class="d-flex align-items-center mb-3 pb-2" style="border-bottom: 1px solid #eee;">
               <i class="fas fa-map-pin me-2 text-danger"></i>
               <h6 class="mb-0 fw-bold text-dark" style="font-size: 15px;">Saved Locations</h6>
            </div>
            <div id="saved-locations-list" style="max-height: 250px; overflow-y: auto; overflow-x: hidden; padding-right: 5px;">
               <div class="small text-muted bg-light p-4 rounded text-center" style="border: 1px dashed #ced4da;">
                  <i class="fas fa-map-marker-alt mb-2" style="font-size: 24px; color: #dee2e6;"></i>
                  <p class="mb-0" style="font-size: 13px;">No saved locations.</p>
               </div>
            </div>
         </div>
      </div>

      <div class="modal fade save-location-modal" id="saveLocationModal" tabindex="-1" aria-labelledby="saveLocationModalLabel" aria-hidden="true">
         <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable save-location-dialog">
            <div class="modal-content save-location-panel border-0">
               <div class="save-location-header mypopup-header">
                  <h2 id="saveLocationModalLabel">Save Location</h2>
                  <button type="button" class="save-location-dismiss mypopup-close" data-bs-dismiss="modal" aria-label="Close">&times;</button>
               </div>
               <div class="modal-body save-location-body">
                  <form id="popupLocationAddressForm" class="save-location-form">
                     <input type="hidden" name="type" id="popupAddressType" value="home">
                     <div class="save-location-type-toggle" role="group" aria-label="Address type">
                        <button type="button" id="popupHomeBtn" class="save-location-type-btn active">Home</button>
                        <button type="button" id="popupWorkBtn" class="save-location-type-btn">Work</button>
                     </div>
                     <div class="pata-input"><input type="text" name="area" id="popupArea" class="form-control" placeholder="Area / Locality*" required autocomplete="off"></div>
                     <div class="pata-input"><input type="text" name="flat" id="popupFlat" class="form-control" placeholder="Flat / Building no*" required></div>
                     <div class="pata-input"><input type="text" name="landmark" id="popupLandmark" class="form-control" placeholder="Landmark (optional)"></div>
                     <div class="pata-input"><input type="text" name="pincode" id="popupPincode" class="form-control" placeholder="Pincode*" required inputmode="numeric"></div>
                     <div class="pata-input"><input type="text" name="name" id="popupName" class="form-control" placeholder="Name*" required autocomplete="name"></div>
                     <div class="pata-input"><input type="tel" name="phone" id="popupPhone" class="form-control" placeholder="Phone Number*" required autocomplete="tel" inputmode="tel"></div>
                     <div class="pata-input"><input type="tel" name="alt_phone" id="popupAltPhone" class="form-control" placeholder="Alternate Phone (optional)" autocomplete="tel" inputmode="tel"></div>
                     <button type="submit" class="pata-save-btn save-location-submit w-100">Save Address</button>
                  </form>
               </div>
            </div>
         </div>
      </div>
      <!-- Bottom Navigation (Only for Mobile) -->
      <div class="bottom-nav d-flex justify-content-around d-md-none">
         <a href="{{url('/')}}" class="nav-item nav-link" onclick="return redirectWithLocation(this.href)">
         <img src="{{ asset('public/assets/website/images/footlogo.png')}}" alt="" style="height:25px; margin-bottom:6px;"><br>Kwiklly
         </a>
         <a href="{{ route('department')}}" class="nav-item nav-link" onclick="return redirectWithLocation(this.href)">
         <img src="{{ asset('public/assets/website/images/departmenticon.png')}}" alt="" style="height:25px; margin-bottom:6px;"><br>Department
         </a>
         <a href="{{ route('stores', ['slug' => 'all'])}}" class="nav-item nav-link" onclick="return redirectWithLocation(this.href)">
         <img src="{{ asset('public/assets/website/images/storeicon.png')}}" alt="" style="height:25px; margin-bottom:6px;"><br>Store
         </a>
         @if(auth()->check())
         <a href="{{ route('customer.dashboard') }}" class="nav-item nav-link" onclick="return redirectWithLocation(this.href)">
         <img src="{{ asset('public/assets/website/images/joinicon.png')}}" alt="" style="height:25px; margin-bottom:6px;"><br>Account
         </a>
         @else
         <a href="{{ route('loginbyphone') }}" class="nav-item nav-link" onclick="return redirectWithLocation(this.href)">
         <img src="{{ asset('public/assets/website/images/joinicon.png')}}" alt="" style="height:25px; margin-bottom:6px;"><br> Login
         </a>
         @endif
      </div>
