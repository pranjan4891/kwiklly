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
    .desktop-menu a {
    font-size: 16px;
    font-weight: 400;
    margin: 0px 20px 0px 20px;
    text-decoration: none;
    color: #ffffff;
    transition: color 0.3s;
}
@media (max-width: 768px) {
    .cart-btn a {
    font-size: 16px;
    font-weight: 400;
    margin: 0px 0px 0px a0px;
    text-decoration: none;
    color: #ffffff;
    transition: color 0.3s;
}
 }
</style>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg fixed-top">
    <div class="container-fluid">
        <!-- Desktop: Logo + Location & Search -->
        <div class="d-flex align-items-center w-100 d-none d-md-flex">
            <a class="navbar-brand" href="index.php">
                <img src="{{ asset('public/assets/website/images/logo.png')}}" alt="Logo">
            </a>
        </div>
        <!-- Desktop Menu (Hidden in Mobile) -->
        <div class="d-flex align-items-center desktop-menu">
            <div class="cart-btn">
              <a href="{{route('vendor.signup')}}">Vendor Sign Up</a>
            </div>
        </div>
    </div>
    <div class="mobile-top d-md-none">
            <a class="navbar-brand" href="index.php">
                <img src="{{ asset('public/assets/website/images/logo.png')}}" alt="Logo">
            </a>
            <div class="cart-btn">
              <a href="{{route('vendor.signup')}}">Vendor Sign Up</a>
            </div>
        </div>
</nav>

<!-- first section start  -->
<section>
<div class="container log-in-container form-section">
    <div class="log-in-box">
      <h4 class="mb-4 fw-bold">Vendor LogIn  Here</h4>
      <form method="POST" action="{{ route('vendor.login.submit') }}">
          @csrf
          <div class="mb-3">
            <input type="email" name="email" class="form-control log-in-form-control" placeholder="Email id" required>
          </div>
          <div class="mb-2">
            <input type="password" name="password" class="form-control log-in-form-control" placeholder="Password" required>
          </div>
          <button type="submit" class="btn log-in-btn w-100 py-2 mb-3">Log In</button>
          {{-- <p class="mb-0">
                Forgotten Password ?
            <a href="" class="log-in-signup-link">Click Here</a>
          </p> --}}
        </form>

    </div>
  </div>
</section>
<!-- first section end  -->

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
                            <li><a href="javascript:void(0)">Privacy Policy</a></li>
                            <li><a href="javascript:void(0)">Terms & Condition</a></li>
                            <li><a href="javascript:void(0)">Return Policy</a></li>
                        </ul>
                    </div>

                    <!-- About -->
                    <div class="col-md-4 col-12 footer-links">
                        <h5>About</h5>
                        <ul>
                            <li><a href="javascript:void(0)">About Us</a></li>
                            <li><a href="javascript:void(0)">Contact Us</a></li>
                        </ul>
                    </div>
                    </div>
                    <hr class="breakdown">

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
                                <img src="{{ asset('public/assets/website/images/payment1.png') }}"  alt="Paytm">
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

                    </div>
                <hr>
            <div class="footer-bottom ">
                Copyright &copy; 2000 - 2025 Kwikly. All rights reserved.
            </div>
            </div>
        </section>
    </footer>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   </body>
</html>

