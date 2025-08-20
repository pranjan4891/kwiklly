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
    <link rel="stylesheet" href="CSS/style.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
</head>
<body>
<!-- Desktop side cart html start  -->
<div class="cart-sidebar position-fixed top-0 end-0 bg-white shadow" style="width: fit-content; height: 100vh;  -index: 1050; overflow-y: auto;" id="cartSidebar">
<div class="d-flex justify-content-between align-items-center p-3" style="box-shadow: 0 2px 2px rgb(0 0 0 / 25%);position: sticky;top: 0;background-color: white;z-index: 999;">
    <h5 class="fw-bold">My Cart</h5>
    <div class="">      
      <button class="btn btn-success btn-sm"><i class="fa fa-wallet"></i> <span class="rupee-symbol-sidecart ms-2">₹</span> 30</button>
      <button class="btn-close mx-2" onclick="document.getElementById('cartSidebar').classList.remove('show')"></button>
    </div>
  </div>

  <div class="grocery-header p-3 rounded my-3 p-3">
    <div class="d-flex justify-content-between align-items-center">
      <div class="gotostore">
        <h6 class="mb-0 fw-semibold">Aryan Grocery</h6>
        <a href=""><small class="text-success">Go to store</small></a>
      </div>
      <button class="btn btn-sm delivery-toggle-btn border" id="deliveryToggle">
        <i class="fa fa-clock me-1"></i> Delivery in 30 min
      </button>
    </div>

    <div id="deliveryOptions" class="mt-3" style="display: none;">
      <div class="row mb-2">
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
      <div class="text-center">
        <span class="text-muted d-block mb-2">or</span>
        <button class="btn btn-success w-100" id="expressBtn">
          <i class="fa fa-bolt me-1"></i> Express Delivery in 20 mins
        </button>
      </div>
    </div>
  </div>

  <!-- Cart Items -->
  <div>
      <div class="d-flex justify-content-between align-items-center px-3">
        <div>
          <small class="text-muted">Shipment of 2 items</small>
        </div>
        <div>
        <i class="fa-solid fa-xmark m-1"></i><small class="mb-1">clear all </small>
        </div>
      </div>
    <div class="cart-item d-flex align-items-center justify-content-between border-bottom py-2 p-3">  
      <div class="d-flex align-items-center totalimg">
        <img src="images/category1.png"  alt="..." >
        <div class="mx-3">
          <p class="mb-0">Jackpot Cheese Balls</p>
          <small class="text-success">₹55 <s class="text-muted">₹60</s></small>
        </div>
      </div>
      <div class="input-group input-group-sm sidecartbutton" style="width: 90px;">
          <button class="btn btn-danger decrement-btn">-</button>
          <input type="text" class="form-control text-center quantity-input" value="1">
          <button class="btn btn-danger increment-btn">+</button>
      </div>
    </div>
  </div>

  <div class="cart-item d-flex align-items-center justify-content-between border-bottom py-2 p-3">
    <div class="d-flex align-items-center totalimg">
      <img src="images/category3.png"  alt="...">
      <div class="mx-3">
        <p class="mb-0">Coca Cola - 250 ml</p>
        <small class="text-success">₹55 <s class="text-muted">₹60</s></small>
      </div>
    </div>
    <div class="input-group input-group-sm sidecartbutton" style="width: 90px;">
        <button class="btn btn-danger decrement-btn">-</button>
        <input type="text" class="form-control text-center quantity-input" value="1">
        <button class="btn btn-danger increment-btn">+</button>
    </div>
  </div>

  <!-- Free delivery notice -->
  <div class="my-3 free-delivery-alert p-3">
    <small class="text-dark fw-semibold">Add item worth ₹60 to get free delivery</small>
    <div class="progress mt-2" style="height: 10px;">
      <div class="progress-bar" style="width: 60%"></div>
    </div>
  </div>

  <div class="accordion mb-3 px-2" id="coupons">
    <div class="accordion-item">
      <h2 class="accordion-header" id="headingOne">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
          View Coupons & Offers
        </button>
      </h2>
      <div id="collapseOne" class="accordion-collapse collapse">
        <div class="accordion-body">
          No coupons available.
        </div>
      </div>
    </div>
  </div>

  <div class="d-flex justify-content-between align-items-center bg-light p-2 rounded mb-3 wallet-box p-3">
    <div>
      <span id="walletText"><i class="fa fa-wallet me-1"></i> Kwiklly Points</span>
    </div>
    <button class="btn btn-outline-success btn-sm" id="walletBtn">Use ₹5</button>
  </div>

  <!-- Toggle Button -->
  <div class="d-flex align-items-center p-3">
    <h6 class="fw-bold mb-0">Bill Summary</h6>
    <button class="toggle-bill-btn" id="toggleBillBtn"><i class="fa fa-chevron-down"></i></button>
  </div>

  <!-- Bill Summary -->
  <div class="border-top pt-2 mt-2 p-3" id="billSummary" style="display: none;">
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
    </ul>
    </div>
    <hr>
    <div class="grand-total-box p-3">
            <h6>Total charge</h6>
                <div class="total-row">
                    <div class="price-wrapper">
                    <div class="prices">
                        <del>₹400</del>
                        <strong>₹347</strong>
                    </div>
                    <div class="saved-tag">Saved ₹53</div>
                    </div>
                </div>
            </div>
            <!-- Grand Total Section -->
            <div class="grand-total-box p-3">
            <h3>Grand Total</h3>
                <div class="total-row">
                    <div class="price-wrapper">
                    <div class="prices">
                        <del>₹400</del>
                        <strong>₹347</strong>
                    </div>
                    <div class="saved-tag">Saved ₹53</div>
                    </div>
                </div>
            </div>

        <!-- Bottom Fixed Section -->
        <div class="bottom-bar">
            <div class="delivery-section">
            <div class="delivery-left">
                <i class="fas fa-home"></i>
                <div class="delivery-text">
                <div class="label">Delivering to</div>
                <div>Comfort Pg, Radhe Krishna Mandir, Saket, 201015</div>
                </div>
            </div>
            <div class="change-link">Change</div>
            </div>
            <div class="proceed-btn">Proceed to Pay ₹347</div>
        </div>
  </div>    
</div>
<!--Desktop side cart html end  -->

<!-- mobile side cart html start  -->
<div class="cart-sidebar position-fixed top-0 end-0 bg-white shadow" style="width: fit-content; height: 100vh;  -index: 1050; overflow-y: auto;" id="cartSidebar2">
<div class="d-flex justify-content-between align-items-center p-3" style="box-shadow: 0 2px 2px rgb(0 0 0 / 25%);position: sticky;top: 0;background-color: white;z-index: 999;">
    <h5 class="fw-bold">My Cart</h5>
    <div class="">      
      <button class="btn btn-success btn-sm"><i class="fa fa-wallet"></i> <span class="rupee-symbol-sidecart ms-2">₹</span> 30</button>
      <button class="btn-close mx-2" onclick="document.getElementById('cartSidebar2').classList.remove('show')"></button>
    </div>
  </div>

  <div class="grocery-header p-3 rounded my-3 p-3">
    <div class="d-flex justify-content-between align-items-center">
      <div class="gotostore">
        <h6 class="mb-0 fw-semibold">Aryan Grocery</h6>
        <a href=""><small class="text-success">Go to store</small></a>
      </div>
      <button class="btn btn-sm delivery-toggle-btn border" id="deliveryToggle2">
        <i class="fa fa-clock me-1"></i> Delivery in 30 min
      </button>
    </div>

    <div id="deliveryOptions2" class="mt-3" style="display: none;">
      <div class="row mb-2">
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
      <div class="text-center">
        <span class="text-muted d-block mb-2">or</span>
        <button class="btn btn-success w-100" id="expressBtn2">
          <i class="fa fa-bolt me-1"></i> Express Delivery in 20 mins
        </button>
      </div>
    </div>
  </div>

  <!-- Cart Items -->
  <div>
    <div class="d-flex justify-content-between align-items-center px-3">
      <div>
        <small class="text-muted">Shipment of 2 items</small>
      </div>
      <div>
      <i class="fa-solid fa-xmark m-1"></i><small class="mb-1">clear all </small>
      </div>
    </div>
    <div class="cart-item d-flex align-items-center justify-content-between border-bottom py-2 p-3">
      <div class="d-flex align-items-center totalimg">
        <img src="images/category1.png"  alt="..." >
        <div class="mx-3">
          <p class="mb-0">Jackpot Cheese Balls</p>
          <small class="text-success">₹55 <s class="text-muted">₹60</s></small>
        </div>
      </div>
      <div class="input-group input-group-sm sidecartbutton" style="width: 90px;">
          <button class="btn btn-danger decrement-btn">-</button>
          <input type="text" class="form-control text-center quantity-input" value="1">
          <button class="btn btn-danger increment-btn">+</button>
      </div>
    </div>
  </div>

  <div class="cart-item d-flex align-items-center justify-content-between border-bottom py-2 p-3">
    <div class="d-flex align-items-center totalimg">
      <img src="images/category3.png"  alt="...">
      <div class="mx-3">
        <p class="mb-0">Coca Cola - 250 ml</p>
        <small class="text-success">₹55 <s class="text-muted">₹60</s></small>
      </div>
    </div>
    <div class="input-group input-group-sm sidecartbutton" style="width: 90px;">
        <button class="btn btn-danger decrement-btn">-</button>
        <input type="text" class="form-control text-center quantity-input" value="1">
        <button class="btn btn-danger increment-btn">+</button>
    </div>
  </div>

  <!-- Free delivery notice -->
  <div class="my-3 free-delivery-alert p-3">
    <small class="text-dark fw-semibold">Add item worth ₹60 to get free delivery</small>
    <div class="progress mt-2" style="height: 10px;">
      <div class="progress-bar bg-warning" style="width: 60%"></div>
    </div>
  </div>

  <div class="accordion mb-3 px-2" id="coupons">
    <div class="accordion-item">
      <h2 class="accordion-header" id="headingOne">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
          View Coupons & Offers
        </button>
      </h2>
      <div id="collapseOne" class="accordion-collapse collapse">
        <div class="accordion-body">
          No coupons available.
        </div>
      </div>
    </div>
  </div>

  <div class="d-flex justify-content-between align-items-center bg-light p-2 rounded mb-3 wallet-box p-3">
    <div>
      <span id="walletText2"><i class="fa fa-wallet me-1"></i> Kwiklly Points</span>
    </div>
    <button class="btn btn-outline-success btn-sm" id="walletBtn2">Use ₹5</button>
  </div>

  <!-- Toggle Button -->
  <div class="d-flex align-items-center p-3">
    <h6 class="fw-bold mb-0">Bill Summary</h6>
    <button class="toggle-bill-btn" id="toggleBillBtn2"><i class="fa fa-chevron-down"></i></button>
  </div>

  <!-- Bill Summary -->
  <div class="border-top pt-2 mt-2 p-3" id="billSummary2" style="display: none;">
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
    </ul>
    </div>
    <hr>
    <div class="grand-total-box p-3">
            <h6>Total charge</h6>
                <div class="total-row">
                    <div class="price-wrapper">
                    <div class="prices">
                        <del>₹400</del>
                        <strong>₹347</strong>
                    </div>
                    <div class="saved-tag">Saved ₹53</div>
                    </div>
                </div>
            </div>
            <!-- Grand Total Section -->
            <div class="grand-total-box p-3">
            <h3>Grand Total</h3>
                <div class="total-row">
                    <div class="price-wrapper">
                    <div class="prices">
                        <del>₹400</del>
                        <strong>₹347</strong>
                    </div>
                    <div class="saved-tag">Saved ₹53</div>
                    </div>
                </div>
            </div>

        <!-- Bottom Fixed Section -->
        <div class="bottom-bar">
            <div class="delivery-section">
            <div class="delivery-left">
                <i class="fas fa-home"></i>
                <div class="delivery-text">
                <div class="label">Delivering to</div>
                <div>Comfort Pg, Radhe Krishna Mandir, Saket, 201015</div>
                </div>
            </div>
            <div class="change-link">Change</div>
            </div>
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
            <a class="navbar-brand" href="index.php">
                <img src="images/logo.png" alt="Logo">
            </a>
            <div class="location-box mx-5">
                <i class="fas fa-map-marker-alt"></i>
                <span class="location-text">Current Location <br>Radhe Krishna Mandir, Sa...</span>
                <i class="fas fa-chevron-down dropdown-icon"></i>
            </div>
            <div class="search-container ms-3">
                <input type="text" class="form-control search-box" placeholder='Search "Banana"' />
                <button class="search-btn">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
        <!-- Desktop Menu (Hidden in Mobile) -->
        <div class="d-flex align-items-center desktop-menu">
            <a href="department.php">Department</a>
            <a href="store.php">Store</a>
            <a href="login.php">Join&nbsp;Us</a>
            <button class="cart-btn d-none d-md-flex" id="openCart">
                <i class="fa fa-shopping-cart"></i> Cart (4)
            </button>
        </div>
        <!-- Mobile: Location and Cart in one row -->
        <div class="mobile-top d-md-none">
            <div class="header2icon">
               <i class="fa-solid fa-arrow-left"></i>
            </div>
            <div class="search-container d-md-none">
                <input type="text" class="form-control search-box" placeholder='Search "Banana"' />
                <button class="search-btn">
                    <i class="fas fa-search"></i>
                </button>
            </div>
            <div class="header2cart">
                <button class="cart-btn" id="openCart2">
                    <i class="fas fa-shopping-cart"></i>(4)
                </button>
            </div>
        </div>        
    </div>
</nav>
<!-- Bottom Navigation (Only for Mobile) -->
<div class="bottom-nav d-flex justify-content-around d-md-none d-none">
    <a href="index.php" class="nav-item nav-link">
        <img src="images/footlogo.png" alt="" style="height:25px; margin-bottom:6px;">&nbsp;Kwiklly&nbsp;
    </a>
    <a href="department.php" class="nav-item nav-link">
    <img src="images/departmenticon.png" alt="" style="height:25px; margin-bottom:6px;">Department
    </a>
    <a href="store.php" class="nav-item nav-link">
    <img src="images/storeicon.png" alt="" style="height:25px; margin-bottom:6px;"> &nbsp;&nbsp;Store&nbsp;&nbsp;
    </a>
    <a href="login.php" class="nav-item nav-link">
    <img src="images/joinicon.png" alt="" style="height:25px; margin-bottom:6px;"> Join&nbsp;Us
    </a>
</div>

