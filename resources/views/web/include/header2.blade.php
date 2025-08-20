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
    <link rel="stylesheet" href="{{ asset('public/web/CSS/style.css')}}">
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
        <img src="{{ asset('public/web/images/category1.png')}}"  alt="..." >
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
      <img src="{{ asset('public/web/images/category3.png')}}"  alt="...">
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
  <div class="border-top pt-2 mt-2 p-3 pb-0" id="billSummary" style="display: none;">
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
    <div class="grand-total-box p-3 pt-0">
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
            <div class="grand-total-box p-3 pb-0">
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
         <img src="{{ asset('public/web/images/category1.png')}}"  alt="..." >
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
      <img src="{{ asset('public/web/images/category3.png')}}"  alt="...">
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
    <div class="grand-total-box p-3 pt-0">
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
            <div class="grand-total-box p-3 pb-0">
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
               <img src="{{ asset('public/web/images/logo.png')}}" alt="Logo">
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
<div class="bottom-nav d-flex justify-content-around d-md-none">
    <a href="index.php" class="nav-item nav-link">
        <img src="{{ asset('public/web/images/footlogo.png')}}" alt="" style="height:25px; margin-bottom:6px;">&nbsp;Kwiklly&nbsp;
    </a>
    <a href="department.php" class="nav-item nav-link">
    <img src="{{ asset('public/web/images/departmenticon.png')}}" alt="" style="height:25px; margin-bottom:6px;">Department
    </a>
    <a href="store.php" class="nav-item nav-link">
    <img src="{{ asset('public/web/images/storeicon.png')}}" alt="" style="height:25px; margin-bottom:6px;"> &nbsp;&nbsp;Store&nbsp;&nbsp;
    </a>
    <a href="login.php" class="nav-item nav-link">
    <img src="{{ asset('public/web/images/joinicon.png')}}" alt="" style="height:25px; margin-bottom:6px;"> Join&nbsp;Us
    </a>
</div>

<script>
    function showModal() {
      document.getElementById('couponModalxyz').style.display = 'flex';
    }
  
    function hideModal() {
      document.getElementById('couponModalxyz').style.display = 'none';
    }
  </script>                                                                                                                                                                                                                                                                                                        

<script>
  document.addEventListener("DOMContentLoaded", function () {
    const homeBtn = document.getElementById("pataHomeBtn");
    const workBtn = document.getElementById("pataWorkBtn");
    const addressType = document.getElementById("addressType");

    // Handle home button click
    homeBtn.addEventListener("click", function () {
      homeBtn.classList.add("active");
      workBtn.classList.remove("active");
      addressType.value = "home";
    });

    // Handle work button click
    workBtn.addEventListener("click", function () {
      workBtn.classList.add("active");
      homeBtn.classList.remove("active");
      addressType.value = "work";
    });

    // Submit the form
    document.getElementById('addressForm').addEventListener('submit', function (e) {
      e.preventDefault();

      const formData = new FormData(this);

      fetch("{{ route('address.store') }}", {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: formData
      })
      .then(res => {
        if (!res.ok) {
          return res.text().then(text => { throw new Error(text) });
        }
        return res.json();
      })
      .then(data => {
        alert(data.message);
        closePataSidebar()
        openSidebar()
        // Optionally reload or close sidebar
      })
      .catch(err => {
        console.error('Submission error:', err);
        alert('Something went wrong!');
      });
    });
  });
</script>



{{-- <script>
 document.addEventListener("DOMContentLoaded", function () {
    const homeBtn = document.getElementById("pataHomeBtn");
    const workBtn = document.getElementById("pataWorkBtn");

    homeBtn.addEventListener("click", function () {
      homeBtn.classList.add("active");
      workBtn.classList.remove("active");
    });

    workBtn.addEventListener("click", function () {
      workBtn.classList.add("active");
      homeBtn.classList.remove("active");
    });
  });

</script> --}}
<script>
  function openCart() {
  document.getElementById("sideCart").classList.add("active");
  document.getElementById("overlay").classList.add("active");
}

function closeCart() {
  document.getElementById("sideCart").classList.remove("active");
  document.getElementById("overlay").classList.remove("active");
}
</script>


<script>
  const popup = document.getElementById("addpopPopup");
  const overlay = document.getElementById("addpopOverlay");
  const trigger = document.querySelector(".location-boxs");

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

    // Lock scroll on open (applies to both views)
    document.body.style.overflow = 'hidden';
  }

  function closeAddpop(){
    popup.classList.remove("addpop-mobile-active", "addpop-desktop-active");
    overlay.classList.remove("addpop-visible");

    // Unlock scroll
    document.body.style.overflow = 'auto';
  }

  window.addEventListener("click", () => {
    closeAddpop();
  });

  popup.addEventListener("click", (e) => e.stopPropagation());
</script>

{{-- <script>
  function openSidebar() {
    document.getElementById("addressSidebar").classList.add("open");
  }

  function closeSidebar() {
    document.getElementById("addressSidebar").classList.remove("open");
  }
</script> --}}

<script>
  function openSidebar() {
    document.getElementById("addressSidebar").classList.add("open");

    // Load dynamic address list
    fetch("{{ route('address.list') }}", {
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      }
    })
    .then(response => response.json())
    .then(data => {
      const container = document.querySelector(".address-section");
      container.innerHTML = `<div class="section-title">Your Saved Address</div>`; // clear old

      if (data.length === 0) {
        container.innerHTML += `<p class="text-muted">No address found.</p>`;
        return;
      }

      data.forEach(item => {
        const icon = item.type === 'home'
          ? 'https://cdn-icons-png.flaticon.com/128/69/69524.png'
          : 'https://cdn-icons-png.flaticon.com/128/609/609803.png';

        container.innerHTML += `
        <div class="address-card" onclick='selectAddress(this)' 
            data-address="${item.flat}, ${item.area}, ${item.landmark ?? ''}, ${item.pincode}" 
            data-name="${item.name}">
          <div class="address-left">
            <img src="${icon}" alt="${item.type}" class="icon">
            <div>
              <strong>${capitalize(item.type)}</strong>
              <p>${item.name}, ${item.flat}, ${item.area}, ${item.landmark ?? ''}, ${item.pincode}</p>
            </div>
          </div>
          <div class="address-right">
            <span class="check">&#x2714;</span>
            <div class="dropdown-wrapper">
              <span class="options" onclick="toggleDropdown(this)">&#8942;</span>
              <div class="dropdown-menu">
                <div onclick="editAddress(${item.id})">Edit</div>
                <div onclick="deleteAddress(${item.id})">Delete</div>
              </div>
            </div>
          </div>
        </div>
        `;

      });
    })
    .catch(err => {
      console.error("Error loading addresses:", err);
    });
  }

  function capitalize(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
  }

  function closeSidebar() {
    document.getElementById("addressSidebar").classList.remove("open");
  }

  function selectAddress(el) {
  // Clear previously selected
  document.querySelectorAll(".address-card").forEach(card => card.classList.remove("selected"));
  el.classList.add("selected");

  const address = el.getAttribute("data-address");
  const name = el.getAttribute("data-name");

  // Update bottom bar
  document.getElementById("selectedAddressText").innerText = `${name}, ${address}`;

  // Optional: store in localStorage/sessionStorage for later use
  sessionStorage.setItem("selectedAddress", JSON.stringify({ name, address }));

  // Close sidebar
  closeSidebar();
}

</script>


<script>
  document.getElementById("deliveryToggle").addEventListener("click", function () {
    const btn = this;
    const isDelivery = btn.textContent.includes("Delivery in 30 min");

    btn.innerHTML = `<i class="fa fa-clock me-1"></i> ${isDelivery ? 'Schedule Delivery' : 'Delivery in 30 min'}`;
  });
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function () {

    // Load cart initially
    $.get("{{ route('cart.data') }}", function (res) {
        $('#cart-count').text(res.count);
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
                $('#cart-count').text(res.count);
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
        $('#cart-count').text(res.count);
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
  // DECREMENT
$(document).on('click', '.decrement-btn', function () {
    let key = $(this).data('key');
    $.post("{{ route('cart.decrement') }}", {
        _token: "{{ csrf_token() }}",
        key: key
    }, function (res) {
        $('#cart-count').text(res.count);
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

    $('#cartItemsWrapper').html(html);

    // update grand total
    updateBillSummary(total);
}


function updateBillSummary(total) {
    // Static values for demonstration
    let deliveryCharge = 0;
    let couponDiscount = 0;
    let walletDiscount = 0;

    let grandTotal = total + deliveryCharge - couponDiscount - walletDiscount;

    $('#billSummary').html(`
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

    $('.proceed-btn').html(`Proceed to Pay ₹${grandTotal}`);
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
                window.location.href = "{{ route('checkout.page') }}"; // Redirect to checkout
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


