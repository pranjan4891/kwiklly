 <script>
    // js for vendor registration 
    let step = 0;
    let map, marker;
    let mapInitialized = false;

    function updateForm() {
        let container = document.getElementById("dynamic-container");
        let confirmBtn = document.getElementById("confirm-btn");
        let backBtn = document.getElementById("back-btn");
        let heading = document.getElementById("form-heading");
        let input = document.getElementById("form-input");

        if (step === 0) {
            heading.textContent = "Register Today";
            input.placeholder = "Enter Landmark";
            container.innerHTML = `
            <input type="text" id="form-input" class="form-control mb-3" placeholder="Enter Landmark">
                <div id="map" class="map-container">
                    <button class="use-location-btn" onclick="getLocation()">
                        <i class="fas fa-map-marker-alt"></i> Use Current Location
                    </button>
                </div>`;
            confirmBtn.textContent = "Confirm and Proceed";
            backBtn.style.display = "none";
            
            setTimeout(() => { 
                initMap(); 
            }, 100);
        } else {
            backBtn.style.display = "inline-block";
            if (step === 1) {
                heading.textContent = "Register Today";
                input.placeholder = "Enter Landmark";
                container.innerHTML = `
                <h4>Your Location</h4>
                <p>Cisf ground, gali no 2, near metro station gate no 3, <br>saket, Delhi </p>
                <h4>Personal Details</h4>
                <input type="text" id="form-input" class="form-control mb-3" placeholder="Email Id">
                <input type="text" id="form-input" class="form-control mb-3" placeholder="Phone Number">
                <input type="text" id="form-input" class="form-control mb-3" placeholder="Password">
                <input type="text" id="form-input" class="form-control mb-3" placeholder="Confirm Password">
                `;
            } else if (step === 2) {
                heading.textContent = "Register Today";
                input.placeholder = "Enter Phone";
                container.innerHTML = `
                <h4>Company Details</h4>
                <input type="text" id="form-input" class="form-control mb-3" placeholder="Company Name">
                <input type="text" id="form-input" class="form-control mb-3" placeholder="GST Number">
                <input type="text" id="form-input" class="form-control mb-3" placeholder="Address">
                
                `;
            } else if (step === 3) {
                heading.textContent = "Register Today";
                input.placeholder = "Enter Address";
                confirmBtn.textContent = "Submit";
                container.innerHTML = `
                <h4>Bank Details</h4>
                <input type="text" id="form-input" class="form-control mb-3" placeholder="Account Holder Name">
                <input type="text" id="form-input" class="form-control mb-3" placeholder="Account Name">
                <input type="text" id="form-input" class="form-control mb-3" placeholder="IFSC Code">
                <label class="pb-2">Cancelled Cheque</label>
                <input type="file" id="form-input" class="form-control mb-3" placeholder="Cancelled Cheque">
                `;
            } else if (step === 4) {
                Swal.fire({
                    text: "Thank you for your confirmation",
                    icon: "success",
                    confirmButtonText: "Continue"
                }).then(() => {
                    step = 0;
                    updateForm();
                });
            }
        }
    }

    function nextStep() {
        if (step < 4) {
            step++;
            updateForm();
        }
    }

    function prevStep() {
        if (step > 0) {
            step--;
            updateForm();
        }
    }

    function initMap() {
        map = L.map('map').setView([20.5937, 78.9629], 5); // Default India location
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);
        marker = L.marker([20.5937, 78.9629]).addTo(map);
    }

    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var lat = position.coords.latitude;
                var lon = position.coords.longitude;
                map.setView([lat, lon], 13);
                marker.setLatLng([lat, lon]).bindPopup("You are here!").openPopup();
            });
        } else {
            Swal.fire({
                title: "Error!",
                text: "Geolocation is not supported by this browser.",
                icon: "error",
                confirmButtonText: "OK"
            });
        }
    }

    document.addEventListener("DOMContentLoaded", updateForm);
 </script>
<footer class="footer-section extramarginfooter" >
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="row">
                      <!-- Logo & Social Links -->
                        <div class="col-md-12">
                            <div class="footer-logo">
                                <img src="images/logo.png" alt="Kwikly Logo">
                            </div>
                            <div class="footer-social">
                                <a href="#"><i class="fab fa-linkedin"></i></a>
                                <a href="#"><i class="fab fa-facebook"></i></a>
                                <a href="#"><i class="fab fa-instagram"></i></a>
                                <a href="#"><img src="images/logotwiter.png" alt="" style="height: 22px;
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
                            <li><a href="VendorRegistration.php">Vendor Registration</a></li>
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
                                <img src="images/payment1.png" alt="Paytm">
                                <img src="images/payment2.png" alt="RuPay">
                                <img src="images/payment3.png" alt="Visa">
                                <img src="images/payment4.png" alt="Mastercard">
                                <img src="images/payment5.png" alt="American Express">
                            </div>
                            <div class="footer-payments ">
                                <img src="images/payment6.png" alt="Paytm">
                                <img src="images/payment7.png" alt="RuPay">
                                <img src="images/payment8.png" alt="Visa">
                                <img src="images/payment9.png" alt="Mastercard">
                                <img src="images/payment10.png" alt="American Express">
                            </div>
                        </div>

                        <!-- Subscription Section -->
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