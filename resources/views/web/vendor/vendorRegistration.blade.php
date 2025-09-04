@extends('web.include.main2')
@section('content')
<!-- first section start  -->
<section class="registrationspace">
   <div class="container register-section">
      <div class="row align-items-center">
         <div class="col-md-6 text-center position-relative my-4">
            <div class="image-container">
               <div class="green-bg"></div>
               <img src="{{ asset('public/assets/website/images/registerimg2.png')}}" class="main-image" alt="Seller">
               <img src="{{ asset('public/assets/website/images/registerimg1.png')}}" class="fruit-image" alt="Fruits">
               <!-- Info Boxes -->
               <div class="info-box info-box-1">
                  <i class="fa-solid fa-cube"></i>
                  <span><strong>3000+</strong><br>We cover almost every product</span>
               </div>
               <div class="info-box info-box-2">
                  <i class="fa-solid fa-globe"></i>
                  <span><strong>1000+</strong><br>Fastest growing seller network</span>
               </div>
               <div class="info-box info-box-3">
                  <i class="fa fa-credit-card"></i>
                  <span><strong>Quick Payment</strong><br>Quick & trustworthy payments</span>
               </div>
            </div>
         </div>
         <div class="col-md-6 my-4">
            <div class="form-section">
               <h3 id="form-heading">Register Today</h3>
               <form id="multiStepForm" enctype="multipart/form-data">
                  <input type="hidden" name="_token" value="{{ csrf_token() }}">
                  <input type="text" id="form-input" class="form-control mb-3" placeholder="Enter Landmark" hidden>
                  <!-- Dynamic Content (Map or Forms) -->
                  <div id="dynamic-container"></div>
                  <!-- Buttons -->
                  <div class="d-flex justify-content-between mt-3">
                     <button type="button" id="confirm-btn" class="confirm-btn btn btn-danger" onclick="nextStep()">Confirm and Proceed</button>
                  </div>
                  <div class=" mt-3 text-center">
                     <button type="button" id="back-btn" class="back-btn btn btn-outline-secondary" onclick="prevStep()" style="display: none;">
                     Go Back
                     </button>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>
</section>
<!-- first section end  -->
<!-- second section start  -->
<section class="followpadding">
   <div class="steps-container">
      <h2><strong>Follow These 4 Simple Steps to Get Started.</strong></h2>
      <div class="row mt-4">
         <div class="col-md-6 mt-5">
            <div class="step-box">
               <div class="step-number">01</div>
               <div class="step-title py-3">Vendor Registration</div>
               <p>Let us know what are you selling e.g. products or services like teach me</p>
               <ul>
                  <li>List your products</li>
                  <li>Uploading the products information</li>
                  <li>List your services</li>
                  <li>24*7 help</li>
               </ul>
            </div>
         </div>
         <div class="col-md-6 mt-5">
            <div class="step-box">
               <div class="step-number">02</div>
               <div class="step-title py-3">Verification of Documents</div>
               <p>Let us know what are you selling e.g. products or services like teach me</p>
               <ul>
                  <li>List your products</li>
                  <li>Uploading the products information</li>
                  <li>List your services</li>
                  <li>24*7 help</li>
               </ul>
            </div>
         </div>
         <div class="col-md-6 mt-5">
            <div class="step-box">
               <div class="step-number">03</div>
               <div class="step-title py-3">Verification of Documents</div>
               <p>Let us know what are you selling e.g. products or services like teach me</p>
               <ul>
                  <li>List your products</li>
                  <li>Uploading the products information</li>
                  <li>List your services</li>
                  <li>24*7 help</li>
               </ul>
            </div>
         </div>
         <div class="col-md-6 mt-5">
            <div class="step-box">
               <div class="step-number">04</div>
               <div class="step-title py-3">Verification of Documents</div>
               <p>Let us know what are you selling e.g. products or services like teach me</p>
               <ul>
                  <li>List your products</li>
                  <li>Uploading the products information</li>
                  <li>List your services</li>
                  <li>24*7 help</li>
               </ul>
            </div>
         </div>
      </div>
   </div>
</section>
<!-- second section end  -->
<!-- third section start  -->
<section>
   <div class="container">
      <div class="row">
         <div class="col-md-12 col-12">
            <div class="cta-section">
               <div class="cta-text">
                  <h2>Start Selling Now</h2>
                  <p>For any questions or concerns, feel free to contact us.</p>
                  <button class="cta-btn">Contact Us</button>
               </div>
               <div class="cta-image">
                  <img src="{{ asset('public/assets/website/images/ctaimage.png')}}" alt="Fruits Basket">
               </div>
            </div>
         </div>
      </div>
   </div>
</section>
<!-- third section end  -->
<!-- fourth  section start  -->
<section>
   <div class="container mt-5">
      <!-- Heading and Subheading -->
      <h2 class="why">Why Choose Us</h2>
      <div class="row mt-4">
         <!-- Row 1 -->
         <div class="col-md-4 col-6 mt-4">
            <div class="feature-box1">
               <i class="fa fa-users"></i>
            </div>
            <div class="feature-heading">Wider Customer Reach</div>
            <p class="feature-description">Constantly connect with local customers looking for fast and reliable deliveries.</p>
         </div>
         <div class="col-md-4 col-6 mt-4">
            <div class="feature-box1">
               <i class="fa-solid fa-truck"></i>
            </div>
            <div class="feature-heading">Fast & Efficient Deliveries</div>
            <p class="feature-description">Our optimized logistics ensure speedy and hassle-free order fulfillment.</p>
         </div>
         <div class="col-md-4 col-6 mt-4">
            <div class="feature-box1">
               <i class="fa-solid fa-gears"></i>
            </div>
            <div class="feature-heading">Seamless Integration</div>
            <p class="feature-description">Easily integrate your business with our platform for smooth order management.</p>
         </div>
         <div class="col-md-4 col-6 mt-4">
            <div class="feature-box1">
               <i class="fa-solid fa-money-bills"></i>
            </div>
            <div class="feature-heading">Cost-Effective Solution</div>
            <p class="feature-description">Reduce operational costs with our affordable and scalable delivery network.</p>
         </div>
         <div class="col-md-4 col-6 mt-4">
            <div class="feature-box1">
               <i class="fa-solid fa-clock"></i>
            </div>
            <div class="feature-heading">Real-Time Tracking</div>
            <p class="feature-description">Keep track of deliveries in real-time for better transparency and customer trust.</p>
         </div>
         <div class="col-md-4 col-6 mt-4">
            <div class="feature-box1">
               <i class="fa-solid fa-handshake-angle"></i>
            </div>
            <div class="feature-heading">24/7 Support</div>
            <p class="feature-description">Get dedicated assistance whenever you need it for a smooth business experienc</p>
         </div>
      </div>
   </div>
</section>
<!-- third section end  -->

@endsection
