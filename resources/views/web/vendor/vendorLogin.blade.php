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
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
            <a class="navbar-brand" href="{{ route('home')}}">
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
        <a class="navbar-brand" href="{{ route('home')}}">
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
    @if (session('success') || session('error'))
        <div class="alert alert-{{ session('success') ? 'success' : 'danger' }}">
            {{ session('success') ?? session('error') }}
        </div>
    @endif
    <div class="log-in-box">
      <h4 class="mb-4 fw-bold">Vendor LogIn  Here</h4>
      <form method="POST" action="{{ route('vendor.login.submit') }}">
          @csrf
          <div class="mb-3">
            <input type="email" name="email" class="form-control log-in-form-control" placeholder="Email id" required>
            @error('email')
                <div class="text-danger mb-2">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-2">
            <input type="password" name="password" class="form-control log-in-form-control" placeholder="Password" required>
            @error('password')
                <div class="text-danger mb-2">{{ $message }}</div>
            @enderror
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
    <script type="text/javascript">
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

    </script>
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
       <!-- Popup -->
    <div class="newcon-overlay" id="newconPopup" style="display:none;">
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
      // grab elements
      const openBtn = document.getElementById('newconOpen');
      const popup = document.getElementById('newconPopup');
      const closeBtn = document.getElementById('newconClose');
      const cancelBtn = document.getElementById('newconCancel');
      const form = document.getElementById('newconForm');

      // safety checks
      if (!openBtn) { console.warn('newcon: open button #newconOpen not found'); return; }
      if (!popup)     { console.warn('newcon: popup container #newconPopup not found'); return; }

      // open function
      function openPopup(e) {
        if (e) e.preventDefault();
        popup.style.display = 'flex';
        popup.setAttribute('aria-hidden', 'false');
        // prevent background scroll
        document.documentElement.style.overflow = 'hidden';
        document.body.style.overflow = 'hidden';
        // focus first field for accessibility
        const first = popup.querySelector('.newcon-input, .newcon-textarea');
        if (first) first.focus();
        console.log('newcon: opened');
      }

      // close function
      function closePopup() {
        popup.style.display = 'none';
        popup.setAttribute('aria-hidden', 'true');
        document.documentElement.style.overflow = '';
        document.body.style.overflow = '';
        openBtn.focus(); // return focus
        console.log('newcon: closed');
      }

      // bind events (use addEventListener)
      openBtn.addEventListener('click', openPopup);
      closeBtn && closeBtn.addEventListener('click', closePopup);
      cancelBtn && cancelBtn.addEventListener('click', closePopup);

      // close when clicking outside dialog
      popup.addEventListener('click', function (ev) {
        if (ev.target === popup) closePopup();
      });

      // keyboard: ESC to close
      document.addEventListener('keydown', function (ev) {
        if (ev.key === 'Escape' && popup.style.display === 'flex') closePopup();
      });

      // sample form submit handler (replace with AJAX or normal submit)
      form.addEventListener('submit', function (ev) {
        ev.preventDefault();
        // small validation example
        const email = document.getElementById('newconEmail');
        const name = document.getElementById('newconName');
        if (!name.value.trim() || !email.value.trim()) {
          alert('Please fill at least name and email.');
          return;
        }
        console.log('newcon: form submitted', {
          name: name.value,
          email: email.value,
          subject: document.getElementById('newconSubject').value,
          message: document.getElementById('newconMessage').value
        });
        // simulate success then close
        alert('Message sent (demo).');
        closePopup();
      });
    });
  </script>

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
   </body>
</html>

