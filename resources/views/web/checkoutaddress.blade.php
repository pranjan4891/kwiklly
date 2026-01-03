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
      <link rel="stylesheet" href="{{ asset('public/assets/website/CSS/checkoutdelivery.css')}}">
      <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
      <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
      <style>
         /* Fix dropdown positioning */
         .address-section {
            position: relative;
            overflow: visible !important;
         }
         
         .pataoverflow {
            overflow: visible !important;
         }
         
         .address-card {
            position: relative;
            overflow: visible !important;
            opacity: 0;
            transform: translateY(20px);
            animation: slideInUp 0.4s ease-out forwards;
            transition: all 0.3s ease;
         }
         
         .address-card.selected-address {
            border-color: #28a745 !important;
            background-color: #f8fff9 !important;
            box-shadow: 0 2px 8px rgba(40, 167, 69, 0.2) !important;
         }
         
         @keyframes slideInUp {
            from {
               opacity: 0;
               transform: translateY(20px);
            }
            to {
               opacity: 1;
               transform: translateY(0);
            }
         }
         
         .address-card:nth-child(1) {
            animation-delay: 0.1s;
         }
         
         .address-card:nth-child(2) {
            animation-delay: 0.2s;
         }
         
         .address-card:nth-child(3) {
            animation-delay: 0.3s;
         }
         
         .address-card:nth-child(4) {
            animation-delay: 0.4s;
         }
         
         .address-card:nth-child(5) {
            animation-delay: 0.5s;
         }
         
         .address-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
         }
         
         .dropdown-wrapper {
            position: relative !important;
            display: inline-block !important;
            z-index: 10;
         }
         
         .dropdown-menu {
            position: absolute !important;
            top: 100% !important;
            right: 0 !important;
            background: white !important;
            border: 1px solid #ddd !important;
            border-radius: 4px !important;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15) !important;
            z-index: 1000 !important;
            min-width: 120px !important;
            margin-top: 5px !important;
            white-space: nowrap;
         }
         
         .dropdown-menu.show {
            display: block !important;
         }
         
         .dropdown-menu div {
            padding: 10px 15px !important;
            cursor: pointer !important;
            font-size: 14px !important;
            transition: background-color 0.2s;
         }
         
         .dropdown-menu div:first-child {
            border-bottom: 1px solid #eee;
         }
         
         .dropdown-menu div:hover {
            background-color: #f8f9fa !important;
         }
         
         .dropdown-menu div:last-child {
            color: #dc3545;
         }
         
         .options {
            cursor: pointer !important;
            font-size: 20px !important;
            color: #666 !important;
            padding: 5px !important;
            display: inline-block !important;
            user-select: none;
         }
         
         .options:hover {
            color: #333 !important;
         }
         
         /* Ensure address-right doesn't overflow */
         .address-right {
            position: relative;
            overflow: visible !important;
         }
         
         /* Fade in animation */
         @keyframes fadeIn {
            from {
               opacity: 0;
            }
            to {
               opacity: 1;
            }
         }
         
         /* Loading spinner */
         .spinner-border {
            width: 2rem;
            height: 2rem;
            border: 0.25em solid currentColor;
            border-right-color: transparent;
            border-radius: 50%;
            animation: spinner-border 0.75s linear infinite;
         }
         
         @keyframes spinner-border {
            to {
               transform: rotate(360deg);
            }
         }
         
         /* Proceed button animation */
         #proceedToPayBtn {
            transition: all 0.3s ease;
         }
         
         #proceedToPayBtn:not(:disabled):hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(233, 68, 18, 0.4);
         }
         
         /* Mobile responsive */
         @media (max-width: 768px) {
            .dropdown-menu {
               right: 0 !important;
               min-width: 100px !important;
            }
            
            .address-card {
               animation-duration: 0.3s;
            }
         }
      </style>
   </head>
   <body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top">
          <div class="container-fluid">
              <!-- Desktop: Logo + Location & Search -->
              <div class="d-flex align-items-center w-100 d-md-flex">
              <a class="navbar-brand" href="{{ route('home')}}">
              <img src="{{ asset('public/assets/website/images/logo.png')}}" alt="Logo">
              </a>
              </div>
          </div>
      </nav>
      <section>
         <div class="container px-3 px-md-4">
            <div class="row g-3">
               <!-- Steps Navigation -->
               <div class="col-12">
               <div class="d-flex justify-content-between align-items-center extracartmargin">
                  <!-- Steps -->
                  <div class="d-flex gap-4">
                     <!-- Step 1 -->
                     <div class="d-flex align-items-center step-box2 active-step">
                        <div class="step-circle inactive" style="background-color: #28a745;"><span
                           style="color: white;">&#10003;</span> </div>
                        <span class="ms-md-2 step-label text-secondary ">Shopping Details</span>
                     </div>
                     <!-- Step 2 -->
                     <div class="d-flex align-items-center step-box2 active-step">
                        <div class="step-circle ">2</div>
                        <span class="ms-md-2 step-label fw-bold">Delivery Address</span>
                     </div>
                     <!-- Step 3 -->
                     <div class="d-flex align-items-center step-box2">
                        <div class="step-circle inactive">3</div>
                        <span class="ms-md-2 step-label text-secondary">Payment Details</span>
                     </div>
                  </div>
               </div>
               </div>
               <hr style="border: 1px solid #D8C2BC;" class="my-3">
            </div>    
            <div class="row g-3">
            
                <div class="col-12 col-md-6 main-content-box address-box ">
                  <div class="p-3 ">
                     <!-- Location address text -->
                     <div class="pata-location-title">Your Location</div>
                     <div class="pata-location-desc" id="current-location-display">
                        Cisf ground, gali no 2, near metro station gate no 3, saket, Delhi
                     </div>
                     <!-- Buttons: Home / Work -->
                     <div class="d-flex justify-content-between pata-tag-buttons mb-3">
                        <button type="button" id="pataHomeBtn" class="pata-home active">🏠 Home</button>
                        <button type="button" id="pataWorkBtn" class="pata-work">🏢 Work</button>
                     </div>
                     <!-- Address Form -->
                     <form id="addressForm">
                        <input type="hidden" id="addressId" name="id" value="">
                        <input type="hidden" name="type" id="addressType" value="home">
                        <div class="pata-input">
                           <input type="text" id="autocomplete" name="area" placeholder="Area / Sector / Locality*" class="form-control" required>
                        </div>
                        <div class="pata-input"><input type="text" name="flat" placeholder="Flat / Building no*" class="form-control" required></div>
                        <div class="pata-input"><input type="text" name="landmark" placeholder="Landmark (optional)" class="form-control"></div>
                        <div class="pata-input"><input type="text" name="pincode" placeholder="Pincode*" class="form-control" required></div>
                        <div class="pata-input"><input type="text" name="name" placeholder="Name*" class="form-control" required></div>
                        <div class="pata-input">
                           <input type="tel" name="phone" id="phone" placeholder="Phone Number*" class="form-control" required maxlength="10" pattern="[0-9]{10}" title="Please enter a valid 10-digit phone number">
                           <small class="text-danger" id="phone-error" style="display: none;">Phone number must be exactly 10 digits</small>
                        </div>
                        <div class="pata-input">
                           <input type="tel" name="alt_phone" id="alt_phone" placeholder="Alternate Phone Number (optional)" class="form-control" maxlength="10" pattern="[0-9]{10}" title="Please enter a valid 10-digit phone number">
                           <small class="text-danger" id="alt-phone-error" style="display: none;">Alternate phone number must be exactly 10 digits</small>
                        </div>

                        <!-- Use Current Location Button -->
                        <div class="location-detect-btn" id="use-current-location">
                           <i class="fas fa-location-arrow"></i> Use my current location
                        </div>

                        <button type="submit" class="pata-save-btn mt-3 w-100">Save Address</button>
                     </form>
                  </div>
               </div>
               <!-- RIGHT: Address List -->
                <div class="col-12 col-md-6 main-content-box address-box mb-3">
                  <div class="address-section pataoverflow">
                     <div class="section-title text-center pt-3">
                        <h4>Your saved address for your location</h4>
                     </div>
                     <!-- Dynamic Address List Here -->
                     <div id="savedAddressList"></div>
                  </div>

                  <!-- Proceed button -->
                  <div class="text-center p-3 mt-3">
                     <button id="proceedToPayBtn" class="btn proceed-btn2" disabled>Proceed to Pay ₹{{ number_format($order->final_amount, 2) }}</button>
               </div>
            </div>
         </div>
         </div>
      </section>
      

      <!-- Hidden form for proceeding to payment -->
      <form id="proceedToPaymentForm" method="POST" action="{{ route('order.updateAddress') }}">
         @csrf
         <input type="hidden" name="order_id" value="{{ $order->id }}">
         <input type="hidden" name="address_id" id="selectedAddressId" value="">
      </form>

      <!-- Hidden inputs for location data -->
      <input type="hidden" id="latitude" name="latitude">
      <input type="hidden" id="longitude" name="longitude">

      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
      <script type="text/javascript">
         let selectedAddressId = null;
         let googlemapkey = "{{ env('GOOGLE_MAPS_API_KEY') }}";
         let autocomplete;

         document.addEventListener("DOMContentLoaded", function () {
           const homeBtn = document.getElementById("pataHomeBtn");
           const workBtn = document.getElementById("pataWorkBtn");
           const addressType = document.getElementById("addressType");
           const form = document.getElementById('addressForm');
           const saveBtn = document.querySelector('.pata-save-btn');
           const proceedBtn = document.getElementById('proceedToPayBtn');
           const useCurrentLocationBtn = document.getElementById('use-current-location');
           const currentLocationDisplay = document.getElementById('current-location-display');

           // Initialize location from localStorage
           initLocation();

           // Initialize Google Places Autocomplete
           initAutocomplete();

           // Toggle Home/Work button
           homeBtn.addEventListener("click", function () {
             homeBtn.classList.add("active");
             workBtn.classList.remove("active");
             addressType.value = "home";
           });

           workBtn.addEventListener("click", function () {
             workBtn.classList.add("active");
             homeBtn.classList.remove("active");
             addressType.value = "work";
           });

           // Use Current Location button
           useCurrentLocationBtn.addEventListener('click', function() {
             detectLocation();
           });

           // Phone number validation
           const phoneInput = document.querySelector('input[name="phone"]');
           const altPhoneInput = document.querySelector('input[name="alt_phone"]');
           const phoneError = document.getElementById('phone-error');
           const altPhoneError = document.getElementById('alt-phone-error');

           // Validate phone number (only digits, exactly 10)
           function validatePhone(phone) {
             const phoneRegex = /^[0-9]{10}$/;
             return phoneRegex.test(phone);
           }

           // Real-time validation for phone
           phoneInput.addEventListener('input', function() {
             const value = this.value.replace(/\D/g, ''); // Remove non-digits
             this.value = value; // Update input with only digits
             
             if (value.length > 0 && !validatePhone(value)) {
               phoneError.style.display = 'block';
               this.classList.add('is-invalid');
             } else {
               phoneError.style.display = 'none';
               this.classList.remove('is-invalid');
             }
           });

           // Real-time validation for alternate phone
           altPhoneInput.addEventListener('input', function() {
             const value = this.value.replace(/\D/g, ''); // Remove non-digits
             this.value = value; // Update input with only digits
             
             if (value.length > 0 && !validatePhone(value)) {
               altPhoneError.style.display = 'block';
               this.classList.add('is-invalid');
             } else {
               altPhoneError.style.display = 'none';
               this.classList.remove('is-invalid');
             }
           });

           // Prevent non-numeric input
           phoneInput.addEventListener('keypress', function(e) {
             if (!/[0-9]/.test(e.key) && !['Backspace', 'Delete', 'Tab', 'Escape', 'Enter', 'ArrowLeft', 'ArrowRight'].includes(e.key)) {
               e.preventDefault();
             }
           });

           altPhoneInput.addEventListener('keypress', function(e) {
             if (!/[0-9]/.test(e.key) && !['Backspace', 'Delete', 'Tab', 'Escape', 'Enter', 'ArrowLeft', 'ArrowRight'].includes(e.key)) {
               e.preventDefault();
             }
           });

           // Submit form (Add/Update)
           form.addEventListener('submit', function (e) {
             e.preventDefault();

             // Validate phone number before submission
             const phoneValue = phoneInput.value.trim();
             const altPhoneValue = altPhoneInput.value.trim();

             // Clear previous errors
             phoneError.style.display = 'none';
             altPhoneError.style.display = 'none';
             phoneInput.classList.remove('is-invalid');
             altPhoneInput.classList.remove('is-invalid');

             let hasError = false;

             // Validate main phone number
             if (!phoneValue) {
               phoneError.textContent = 'Phone number is required';
               phoneError.style.display = 'block';
               phoneInput.classList.add('is-invalid');
               hasError = true;
             } else if (!validatePhone(phoneValue)) {
               phoneError.textContent = 'Phone number must be exactly 10 digits';
               phoneError.style.display = 'block';
               phoneInput.classList.add('is-invalid');
               hasError = true;
             }

             // Validate alternate phone number (if provided)
             if (altPhoneValue && !validatePhone(altPhoneValue)) {
               altPhoneError.textContent = 'Alternate phone number must be exactly 10 digits';
               altPhoneError.style.display = 'block';
               altPhoneInput.classList.add('is-invalid');
               hasError = true;
             }

             if (hasError) {
               Swal.fire({
                 icon: 'error',
                 title: 'Validation Error',
                 text: 'Please enter valid 10-digit phone numbers',
                 confirmButtonColor: '#E94412'
               });
               return;
             }

             const formData = new FormData(this);
             const editId = form.getAttribute('data-edit-id');
             const url = editId
               ? `{{ url('address/update') }}/${editId}`
               : `{{ route('address.store') }}`;
             const method = 'POST';

             fetch(url, {
               method: method,
               headers: {
                 'X-CSRF-TOKEN': '{{ csrf_token() }}',
                 'Accept': 'application/json'
               },
               body: formData
             })
             .then(res => res.json())
             .then(data => {
               if (data.errors) {
                 let errors = Object.values(data.errors).flat().join('\n');
                 Swal.fire({
                   icon: 'error',
                   title: 'Validation Error',
                   text: errors,
                   confirmButtonColor: '#E94412'
                 });
               } else if (data.success) {
                 Swal.fire({
                   icon: 'success',
                   title: 'Success',
                   text: data.message,
                   confirmButtonColor: '#E94412',
                   timer: 2000
                 });
                 resetForm();
                 loadSavedAddresses();
               } else {
                 Swal.fire({
                   icon: 'error',
                   title: 'Error',
                   text: data.message || 'Something went wrong',
                   confirmButtonColor: '#E94412'
                 });
               }
             })
             .catch(err => {
               console.error('Submission error:', err);
               Swal.fire({
                 icon: 'error',
                 title: 'Network Error',
                 text: 'Please check your internet connection and try again',
                 confirmButtonColor: '#E94412'
               });
             });
           });

           // Reset form after save/update
           function resetForm() {
             form.reset();
             form.removeAttribute('data-edit-id');
             saveBtn.textContent = 'Save Address';
             addressType.value = "home";
             homeBtn.classList.add("active");
             workBtn.classList.remove("active");
             
             // Clear validation errors
             if (phoneError) phoneError.style.display = 'none';
             if (altPhoneError) altPhoneError.style.display = 'none';
             if (phoneInput) phoneInput.classList.remove('is-invalid');
             if (altPhoneInput) altPhoneInput.classList.remove('is-invalid');
           }

           // Load saved addresses
           function loadSavedAddresses() {
             const section = document.getElementById("savedAddressList");
             
             // Show loading state
             section.innerHTML = '<div class="text-center py-3"><div class="spinner-border text-primary" role="status" style="width: 2rem; height: 2rem; border: 0.25em solid currentColor; border-right-color: transparent; border-radius: 50%; animation: spinner-border 0.75s linear infinite;"><span class="visually-hidden">Loading...</span></div></div>';
             
             fetch("{{ route('address.list') }}")
               .then(res => res.json())
               .then(data => {
                 section.innerHTML = '';

                 if (!data.length) {
                   section.innerHTML = '<p class="text-center py-4" style="color: #666; animation: fadeIn 0.3s ease-in;">No saved addresses</p>';
                   return;
                 }

                 // Create address cards with staggered animation
                 data.forEach((addr, index) => {
                   const iconEmoji = addr.type === 'work' ? '🏢' : '🏠';
                   const card = document.createElement('div');
                   card.className = 'address-card';
                   card.id = `address-${addr.id}`;
                   card.style.animationDelay = `${index * 0.1}s`;
                   
                   card.innerHTML = `
                     <div class="address-left" onclick="selectAddress(${addr.id})" style="cursor: pointer; flex: 1;">
                       <span class="address-icon-emoji">${iconEmoji}</span>
                       <div>
                         <strong>${addr.type.charAt(0).toUpperCase() + addr.type.slice(1)}</strong>
                         <p>${addr.name}, ${addr.flat}, ${addr.area}, ${addr.landmark || ''}, ${addr.pincode}</p>
                       </div>
                     </div>
                     <div class="address-right" onclick="event.stopPropagation();">
                       <label class="fancy-checkbox" onclick="selectAddress(${addr.id})" style="cursor: pointer;">
                         <input type="radio" name="selected_address" value="${addr.id}">
                         <span class="custom-checkmark">&#10003;</span>
                       </label>
                       <div class="dropdown-wrapper" onclick="event.stopPropagation();" style="position: relative; display: inline-block;">
                         <span class="options" onclick="event.stopPropagation(); toggleDropdown(this); return false;" style="cursor: pointer; font-size: 20px; color: #666; padding: 5px; display: inline-block;">&#8942;</span>
                         <div class="dropdown-menu" onclick="event.stopPropagation();" style="position: absolute; top: 100%; right: 0; background: white; border: 1px solid #ddd; border-radius: 4px; box-shadow: 0 2px 8px rgba(0,0,0,0.15); z-index: 1000; min-width: 120px; display: none; margin-top: 5px;">
                           <div onclick="event.stopPropagation(); editAddress(${addr.id}); closeAllDropdowns();" style="padding: 10px 15px; cursor: pointer; font-size: 14px; border-bottom: 1px solid #eee;">Edit</div>
                           <div onclick="event.stopPropagation(); deleteAddress(${addr.id}); closeAllDropdowns();" style="padding: 10px 15px; cursor: pointer; font-size: 14px; color: #dc3545;">Delete</div>
                         </div>
                       </div>
                     </div>
                   `;
                   
                   section.appendChild(card);
                 });
                 
                 // Restore selected address if exists
                 if (selectedAddressId) {
                   setTimeout(() => {
                     const selectedCard = document.getElementById(`address-${selectedAddressId}`);
                     if (selectedCard) {
                       selectAddress(selectedAddressId);
                     }
                   }, 100);
                 }
               })
               .catch(err => {
                 console.error('Error loading addresses:', err);
                 section.innerHTML = '<p class="text-center py-4 text-danger" style="animation: fadeIn 0.3s ease-in;">Error loading addresses. Please refresh the page.</p>';
               });
           }

           // Initialize location from localStorage
           function initLocation() {
             let savedLocation = localStorage.getItem("userLocation");
             if (savedLocation) {
               try {
                 let loc = JSON.parse(savedLocation);
                 if (loc.fullAddress) {
                   currentLocationDisplay.textContent = loc.fullAddress;
                 }
               } catch (e) {
                 console.error("Error parsing saved location:", e);
               }
             }
           }

           // Initialize Google Places Autocomplete
           function initAutocomplete() {
             const input = document.getElementById("autocomplete");
             if (!input || typeof google === 'undefined') return;

             autocomplete = new google.maps.places.Autocomplete(input, {
               types: ['geocode'],
               componentRestrictions: { country: 'in' }
             });

             autocomplete.addListener('place_changed', function() {
               const place = autocomplete.getPlace();
               if (!place.geometry) {
                 console.warn("No details available for input: '" + place.name + "'");
                 return;
               }

               // Extract address components
               extractAddressComponents(place);
             });
           }

           // Extract address components from Google Places result
           function extractAddressComponents(place) {
             let streetNumber = '';
             let route = '';
             let locality = '';
             let postalCode = '';
             let administrativeArea = '';

             // Get each component of the address
             for (const component of place.address_components) {
               const componentType = component.types[0];

               switch (componentType) {
                 case "street_number":
                   streetNumber = component.long_name;
                   break;
                 case "route":
                   route = component.long_name;
                   break;
                 case "locality":
                   locality = component.long_name;
                   break;
                 case "postal_code":
                   postalCode = component.long_name;
                   break;
                 case "administrative_area_level_1":
                   administrativeArea = component.long_name;
                   break;
               }
             }

             // Populate form fields
             if (streetNumber || route) {
               document.querySelector('input[name="flat"]').value = [streetNumber, route].filter(Boolean).join(' ');
             }

             if (locality) {
               document.querySelector('input[name="area"]').value = locality;
             }

             if (postalCode) {
               document.querySelector('input[name="pincode"]').value = postalCode;
             }
           }

           // Detect current location
           function detectLocation() {
             useCurrentLocationBtn.innerHTML = '<span class="location-loading"></span> Detecting location...';

             if (navigator.geolocation) {
               navigator.geolocation.getCurrentPosition(
                 (position) => {
                   let lat = position.coords.latitude;
                   let lng = position.coords.longitude;

                   document.getElementById('latitude').value = lat;
                   document.getElementById('longitude').value = lng;

                   reverseGeocode(lat, lng);
                 },
                 (error) => {
                   console.warn("Geolocation error:", error);
                   useCurrentLocationBtn.innerHTML = '<i class="fas fa-location-arrow"></i> Use my current location';
                   alert('Unable to detect your location. Please try again or enter manually.');
                 }
               );
             } else {
               useCurrentLocationBtn.innerHTML = '<i class="fas fa-location-arrow"></i> Use my current location';
               alert('Geolocation is not supported by this browser.');
             }
           }

           // Reverse geocode coordinates to address
           async function reverseGeocode(lat, lng) {
             let geocodeUrl = `https://maps.googleapis.com/maps/api/geocode/json?latlng=${lat},${lng}&key=${googlemapkey}`;

             try {
               let response = await fetch(geocodeUrl);
               let data = await response.json();

               if (data.status === "OK" && data.results.length) {
                 let result = data.results[0];
                 let address = result.formatted_address;

                 // Update location display
                 currentLocationDisplay.textContent = address;

                 // Extract and populate address components
                 extractAddressComponents(result);

                 // Save to localStorage
                 let shortAddress = getShortAddress(address, result);
                 localStorage.setItem("userLocation", JSON.stringify({
                   fullAddress: address,
                   shortAddress: shortAddress,
                   lat: lat,
                   lng: lng
                 }));

                 useCurrentLocationBtn.innerHTML = '<i class="fas fa-location-arrow"></i> Use my current location';
               } else {
                 throw new Error("No results found");
               }
             } catch (error) {
               console.error("Reverse geocoding error:", error);
               useCurrentLocationBtn.innerHTML = '<i class="fas fa-location-arrow"></i> Use my current location';
               alert('Unable to get address from your location. Please try again or enter manually.');
             }
           }

           // Helper: shorten address for display
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
             return fullAddress.length > 40 ? fullAddress.substring(0, 40) + "..." : fullAddress;
           }

           // Load on page load
           loadSavedAddresses();
           window.loadSavedAddresses = loadSavedAddresses;
           window.resetForm = resetForm;
         });

         // Toggle dropdown menu
         function toggleDropdown(el) {
           event.stopPropagation();
           // Close all other dropdowns first
           document.querySelectorAll('.dropdown-menu').forEach(menu => {
             if (menu !== el.nextElementSibling) {
               menu.style.display = 'none';
               menu.classList.remove('show');
             }
           });
           // Toggle current dropdown
           const dropdown = el.nextElementSibling;
           if (dropdown.style.display === 'block' || dropdown.classList.contains('show')) {
             dropdown.style.display = 'none';
             dropdown.classList.remove('show');
           } else {
             dropdown.style.display = 'block';
             dropdown.classList.add('show');
           }
         }
         
         // Close all dropdowns
         function closeAllDropdowns() {
           document.querySelectorAll('.dropdown-menu').forEach(menu => {
             menu.style.display = 'none';
             menu.classList.remove('show');
           });
         }

         // Delete address
         function deleteAddress(id) {
           Swal.fire({
             title: 'Delete Address?',
             text: 'Are you sure you want to delete this address?',
             icon: 'warning',
             showCancelButton: true,
             confirmButtonColor: '#E94412',
             cancelButtonColor: '#6c757d',
             confirmButtonText: 'Yes, Delete',
             cancelButtonText: 'Cancel',
             reverseButtons: true
           }).then((result) => {
             if (result.isConfirmed) {
               fetch(`{{ url('address/delete') }}/${id}`, {
                 method: 'DELETE',
                 headers: {
                   'X-CSRF-TOKEN': '{{ csrf_token() }}'
                 }
               })
               .then(res => res.json())
               .then(data => {
                 // Animate card removal
                 const cardToRemove = document.getElementById(`address-${id}`);
                 if (cardToRemove) {
                   cardToRemove.style.transition = 'all 0.3s ease-out';
                   cardToRemove.style.opacity = '0';
                   cardToRemove.style.transform = 'translateX(-100%)';
                   cardToRemove.style.maxHeight = cardToRemove.offsetHeight + 'px';
                   
                   setTimeout(() => {
                     if (selectedAddressId === id) {
                       selectedAddressId = null;
                       document.getElementById('proceedToPayBtn').disabled = true;
                     }
                     loadSavedAddresses();
                   }, 300);
                 } else {
                   if (selectedAddressId === id) {
                     selectedAddressId = null;
                     document.getElementById('proceedToPayBtn').disabled = true;
                   }
                   loadSavedAddresses();
                 }
                 
                 Swal.fire({
                   icon: 'success',
                   title: 'Deleted',
                   text: data.message,
                   confirmButtonColor: '#E94412',
                   timer: 2000
                 });
               })
               .catch(err => {
                 Swal.fire({
                   icon: 'error',
                   title: 'Error',
                   text: 'Failed to delete address. Please try again.',
                   confirmButtonColor: '#E94412'
                 });
               });
             }
           });
         }

         // Edit address
         function editAddress(id) {
           // Close dropdown first
           if (typeof window.closeAllDropdowns === 'function') {
             window.closeAllDropdowns();
           }
           
           fetch(`{{ url('customer/address') }}/${id}`)
             .then(res => res.json())
             .then(data => {
               const address = data.address;

               if (!address) {
                 Swal.fire({
                   icon: 'error',
                   title: 'Error',
                   text: 'Address not found',
                   confirmButtonColor: '#E94412'
                 });
                 return;
               }

               $('#addressForm').attr('data-edit-id', address.id);
               $('#addressType').val(address.type);
               $('input[name="area"]').val(address.area);
               $('input[name="flat"]').val(address.flat);
               $('input[name="landmark"]').val(address.landmark);
               $('input[name="pincode"]').val(address.pincode);
               $('input[name="name"]').val(address.name);
               $('input[name="phone"]').val(address.phone);
               $('input[name="alt_phone"]').val(address.alt_phone);

               // Toggle active button
               if (address.type === 'work') {
                 $('#pataHomeBtn').removeClass('active');
                 $('#pataWorkBtn').addClass('active');
               } else {
                 $('#pataWorkBtn').removeClass('active');
                 $('#pataHomeBtn').addClass('active');
               }

               $('.pata-save-btn').text('Update Address');
               
               // Scroll to form
               setTimeout(() => {
                 document.querySelector('#addressForm').scrollIntoView({ behavior: 'smooth', block: 'nearest' });
               }, 100);
             })
             .catch(err => {
               Swal.fire({
                 icon: 'error',
                 title: 'Error',
                 text: 'Failed to load address. Please try again.',
                 confirmButtonColor: '#E94412'
               });
               console.error(err);
             });
         }

         // Select address
         function selectAddress(id) {
           selectedAddressId = id;
           document.getElementById('selectedAddressId').value = id;

           // Update UI to show selected address with animation
           document.querySelectorAll('.address-card').forEach(card => {
             card.classList.remove('selected-address');
             card.style.transform = 'scale(1)';
           });
           
           const selectedCard = document.getElementById(`address-${id}`);
           if (selectedCard) {
             selectedCard.classList.add('selected-address');
             // Add subtle scale animation
             selectedCard.style.transform = 'scale(1.02)';
             setTimeout(() => {
               selectedCard.style.transform = 'scale(1)';
             }, 200);
           }

           // Enable proceed button with animation
           const proceedBtn = document.getElementById('proceedToPayBtn');
           if (proceedBtn) {
             proceedBtn.disabled = false;
             proceedBtn.style.transform = 'scale(1.05)';
             setTimeout(() => {
               proceedBtn.style.transform = 'scale(1)';
             }, 200);
           }
         }

         // Proceed to payment
         document.getElementById('proceedToPayBtn').addEventListener('click', function() {
           if (!selectedAddressId) {
             Swal.fire({
               icon: 'warning',
               title: 'Select Address',
               text: 'Please select an address to proceed',
               confirmButtonColor: '#E94412'
             });
             return;
           }

           // Show confirmation dialog
           Swal.fire({
               title: 'Proceed to Payment?',
               text: 'Are you sure you want to proceed to the payment page?',
               icon: 'question',
               showCancelButton: true,
               confirmButtonColor: '#E94412',
               cancelButtonColor: '#6c757d',
               confirmButtonText: 'Yes, Proceed',
               cancelButtonText: 'Cancel',
               reverseButtons: true
           }).then((result) => {
               if (result.isConfirmed) {
                   // User confirmed, submit the form
           document.getElementById('proceedToPaymentForm').submit();
               }
           });
         });
      </script>

      <script type="text/javascript">
         // Global dropdown toggle function (if not already defined)
         if (typeof window.toggleDropdown === 'undefined') {
           window.toggleDropdown = function(el) {
             event.stopPropagation();
             // Close all other dropdowns first
             document.querySelectorAll('.dropdown-menu').forEach(menu => {
               if (menu !== el.nextElementSibling) {
                 menu.style.display = 'none';
                 menu.classList.remove('show');
               }
             });
             // Toggle current dropdown
             const dropdown = el.nextElementSibling;
             if (dropdown.style.display === 'block' || dropdown.classList.contains('show')) {
               dropdown.style.display = 'none';
               dropdown.classList.remove('show');
             } else {
               dropdown.style.display = 'block';
               dropdown.classList.add('show');
             }
           };
         }
         
         // Global close all dropdowns function
         if (typeof window.closeAllDropdowns === 'undefined') {
           window.closeAllDropdowns = function() {
             document.querySelectorAll('.dropdown-menu').forEach(menu => {
               menu.style.display = 'none';
               menu.classList.remove('show');
             });
           };
         }

         // Close dropdowns when clicking outside
         document.addEventListener('click', function (event) {
           const isDropdown = event.target.closest('.dropdown-wrapper');
           const isDropdownMenu = event.target.closest('.dropdown-menu');
           const isOptionsButton = event.target.closest('.options');

           if (!isDropdown && !isDropdownMenu && !isOptionsButton) {
             // Clicked outside any dropdown → close all dropdowns
             document.querySelectorAll('.dropdown-menu').forEach(menu => {
               menu.style.display = 'none';
               menu.classList.remove('show');
             });
           }
         });
      </script>

      <!-- Google Maps API (for location autocomplete) -->
      <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places&callback=initAutocomplete" async defer></script>
   </body>
</html>
