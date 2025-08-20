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
                                <a href="#"><i class="fab fa-linkedin"></i></a>
                                <a href="#"><i class="fab fa-facebook"></i></a>
                                <a href="#"><i class="fab fa-instagram"></i></a>
                                <a href="#"><img src="{{ asset('public/assets/website/images/logotwiter.png')}}" alt="" style="height: 22px;
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
                            <li><a href="{{route('myaccount')}}">My Account</a></li>
                            <li><a href="{{route('vendor.signup')}}">Vendor Registration</a></li>
                        </ul>
                    </div>                

                    <!-- Company -->
                    <div class="col-md-4 col-6 footer-links">
                        <h5>Company</h5>
                        <ul>
                            <li><a href="#">Privacy Policy</a></li>
                            <li><a href="#">Terms & Condition</a></li>
                            <li><a href="#">Return Policy</a></li>
                        </ul>
                    </div>

                    <!-- About -->
                    <div class="col-md-4 col-12 footer-links">
                        <h5>About</h5>
                        <ul>
                            <li><a href="#">About Us</a></li>
                            <li><a href="#">Contact Us</a></li>
                        </ul>
                    </div>
                    </div>
                    <hr class="breakdown">
                    <div class="row mt-4 footer-links hidecat">
                      <h5>Category</h5>
                        <div class="col-md-4 col-6  footer-links">
                            <ul>
                                <li><a href="#">Dry Fruit</a></li>
                                <li><a href="#">Bakery</a></li>
                                <li><a href="#">Masala</a></li>
                            </ul>
                        </div>                

                        <!-- Company -->
                        <div class="col-md-4 col-6 footer-links">        
                            <ul>
                                <li><a href="#">Meat & Sea Food</a></li>
                                <li><a href="#">Biscuit & Cake</a></li>
                                <li><a href="#">Atta Rice & Dal</a></li>
                            </ul>
                        </div>
                  
                    <!-- About -->
                    <div class="col-md-4 col-12 footer-links">
                        <ul>
                            <li><a href="#">Oil & Ghee</a></li>
                            <li><a href="#">Creals & Breakfast </a></li>
                            <select class="shopmore" style="background-color: transparent;padding: 5px 10px; border:1px solid #D8C2BC;">
                                <option>Show More</option>
                                <option>Cake </option>
                                <option>Masala</option>
                            </select>
                            
                        </ul>
                            <!-- mobile shop more  -->
                    <div class="text-center py-3 shopmore2">
                        <select style="background-color: transparent;padding: 5px 10px; border:1px solid #D8C2BC;">
                            <option>Show More</option>
                            <option>Cake </option>
                            <option>Masala</option>
                        </select>
                    </div>
                        <hr class="breakdown">
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
<!--------------- CUSTOM JAVASCRIPT START ----------------->
    <script src="{{ asset('public/assets/website/JS/custom.js')}}"></script>
    <!--------------- CUSTOM JAVASCRIPT END ----------------->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


</body>
</html>