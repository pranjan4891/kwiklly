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
        input.address-locked { background-color: #f0f0f0 !important; cursor: not-allowed !important; }
      </style>
   </head>
   <body>
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
            @if(session('error'))
            <div class="row g-3">
               <div class="col-12">
                  <div class="alert alert-danger alert-dismissible fade show" role="alert">
                     {{ session('error') }}
                     <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>
               </div>
            </div>
            @endif
            <div class="row g-3">
            
               <div class="col-12 col-md-6 main-content-box address-box ">
                  <div class="p-3 ">
                     <button type="button" id="toggleAddressFormBtn" class="btn btn-outline-primary w-100 mb-3 d-md-none">+ Add Address</button>
                     <div id="addressFormWrapper" class="d-none d-md-block">
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
                        <div class="pata-input"><input type="text" name="pincode" id="pincodeInput" placeholder="Pincode*" class="form-control" required></div>
                        <div class="pata-input"><input type="text" name="name" placeholder="Name*" class="form-control" required></div>
                        <div class="pata-input"><input type="text" name="phone" placeholder="Phone Number*" class="form-control" required></div>
                        <div class="pata-input"><input type="text" name="alt_phone" placeholder="Alternate Phone Number (optional)" class="form-control"></div>

                        <!-- Use Current Location Button -->
                        <div class="location-detect-btn" id="use-current-location">
                           <i class="fas fa-location-arrow"></i> Use my current location
                        </div>

                        <button type="submit" class="pata-save-btn mt-3 w-100">Save Address</button>
                     </form>
                     </div>
                  </div>
               </div>
               <!-- RIGHT: Address List -->
                <div class="col-12 col-md-6 main-content-box address-box mb-3">
                  <div class="address-section pataoverflow">
                     <div class="section-title text-center pt-3">
                        <h4>Your saved address for current location</h4>
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

      <!-- Hidden form for proceeding to payment -->
      <form id="proceedToPaymentForm" method="POST" action="{{ route('order.updateAddress') }}">
         @csrf
         <input type="hidden" name="address_id" id="selectedAddressId" value="">
         <input type="hidden" name="current_latitude" id="currentLatitude" value="">
         <input type="hidden" name="current_longitude" id="currentLongitude" value="">
      </form>

      <!-- Hidden inputs for location data -->
      <input type="hidden" id="latitude" name="latitude">
      <input type="hidden" id="longitude" name="longitude">

      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
      <script src="{{ asset('public/assets/website/JS/location-utils.js') }}"></script>
      <script type="text/javascript">
         window.IS_LOGGED_IN = {{ auth()->check() ? 'true' : 'false' }};
         function checkoutLocationRaw() {
           return typeof getPreferredSavedLocationRaw === 'function'
             ? getPreferredSavedLocationRaw()
             : localStorage.getItem("userLocation");
         }
         function checkoutCoordsApproxEqual(addrLat, addrLng, curLat, curLng) {
           const eps = 0.00015;
           return Math.abs(Number(addrLat) - Number(curLat)) < eps &&
             Math.abs(Number(addrLng) - Number(curLng)) < eps;
         }
         function checkoutDistanceKm(lat1, lon1, lat2, lon2) {
           const R = 6371;
           const dLat = (lat2 - lat1) * Math.PI / 180;
           const dLon = (lon2 - lon1) * Math.PI / 180;
           const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
             Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
             Math.sin(dLon / 2) * Math.sin(dLon / 2);
           return R * (2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a)));
         }
         let selectedAddressId = null;
         let googlemapkey = "{{ env('GOOGLE_MAPS_API_KEY') }}";
         let autocomplete;
         let orderId = {{ $order->id ?? 0 }};

         document.addEventListener("DOMContentLoaded", function () {
           const homeBtn = document.getElementById("pataHomeBtn");
           const workBtn = document.getElementById("pataWorkBtn");
           const addressType = document.getElementById("addressType");
           const form = document.getElementById('addressForm');
           const saveBtn = document.querySelector('.pata-save-btn');
           const proceedBtn = document.getElementById('proceedToPayBtn');
           const useCurrentLocationBtn = document.getElementById('use-current-location');
           const currentLocationDisplay = document.getElementById('current-location-display');
           /** Bumps on each “Use my current location” click so stale geocode cannot repopulate the form. */
           let checkoutGeoGen = 0;
           const formWrapper = document.getElementById('addressFormWrapper');
           const toggleAddressFormBtn = document.getElementById('toggleAddressFormBtn');

           if (toggleAddressFormBtn && formWrapper) {
             toggleAddressFormBtn.addEventListener('click', function () {
               formWrapper.classList.toggle('d-none');
               toggleAddressFormBtn.textContent = formWrapper.classList.contains('d-none') ? '+ Add Address' : 'Hide Address Form';
             });
           }

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

           // Submit form (Add/Update)
           form.addEventListener('submit', function (e) {
             e.preventDefault();

            const formData = new FormData(this);
            formData.set('is_selected', '1');
             // Include current location lat/lng so address is saved for this delivery area
             let savedLocation = checkoutLocationRaw();
             if (savedLocation) {
               try {
                 let loc = JSON.parse(savedLocation);
                 if (loc.lat != null && loc.lng != null) {
                   formData.set('latitude', loc.lat);
                   formData.set('longitude', loc.lng);
                 }
               } catch (e) {}
             }
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
                 alert(`Validation errors:\n${errors}`);
               } else if (data.success) {
                 alert(data.message);
                 resetForm();
                 loadSavedAddresses();
               } else {
                 alert('Something went wrong: ' + (data.message || 'Unknown error'));
               }
             })
             .catch(err => {
               console.error('Submission error:', err);
               alert('Network error!');
             });
           });

           // Reset form after save/update
           function resetForm() {
             unlockAreaAndPincode();
             form.reset();
             form.removeAttribute('data-edit-id');
             saveBtn.textContent = 'Save Address';
             addressType.value = "home";
             homeBtn.classList.add("active");
             workBtn.classList.remove("active");
             initLocation();
             syncCheckoutFormFromSavedUserLocation();
             initAutocomplete();
           }

           // Load saved addresses: full list, then filter by ~same coords or within 50 km (matches server radius)
           function loadSavedAddresses() {
             let loc = null;
             try {
               const savedLocation = checkoutLocationRaw();
               if (savedLocation) loc = JSON.parse(savedLocation);
             } catch (e) {}

             fetch("{{ route('address.list') }}")
               .then(res => res.json())
               .then(data => {
                 const section = document.getElementById("savedAddressList");
                 section.innerHTML = '';

                 let list = Array.isArray(data) ? data : [];
                 if (loc && loc.lat != null && loc.lng != null) {
                   const clat = Number(loc.lat);
                   const clng = Number(loc.lng);
                   list = list.filter(addr => {
                     if (addr.latitude == null || addr.longitude == null) return true;
                     if (checkoutCoordsApproxEqual(addr.latitude, addr.longitude, clat, clng)) return true;
                     return checkoutDistanceKm(clat, clng, Number(addr.latitude), Number(addr.longitude)) <= 50;
                   });
                 }

                 if (!list.length) {
                   section.innerHTML = '<p class="text-center text-muted">No saved addresses for this location. Save an address in your current area (use "Use my current location" or enter address).</p>';
                   return;
                 }

                 list.forEach(addr => {
                  const preferredAddressId = localStorage.getItem("selectedSavedAddressId");
                  const isPreferred = !!addr.is_selected || (preferredAddressId && String(preferredAddressId) === String(addr.id));
                   const iconEmoji = addr.type === 'work' ? '🏢' : '🏠';
                   const card = `
                    <div class="address-card ${isPreferred ? 'selected-address' : ''}" id="address-${addr.id}" onclick="selectAddress(${addr.id})">
                       <div class="address-left">
                         <span class="address-icon-emoji">${iconEmoji}</span>
                         <div>
                           <strong>${addr.type.charAt(0).toUpperCase() + addr.type.slice(1)}</strong>
                           <p>${addr.name}, ${addr.flat}, ${addr.area}, ${addr.landmark || ''}, ${addr.pincode}</p>
                         </div>
                       </div>
                       <div class="address-right">
                         <label class="fancy-checkbox">
                          <input type="radio" name="selected_address" value="${addr.id}" ${isPreferred ? 'checked' : ''}>
                           <span class="custom-checkmark">&#10003;</span>
                         </label>
                         <div class="dropdown-wrapper">
                           <span class="options" onclick="toggleDropdown(this)">&#8942;</span>
                           <div class="dropdown-menu">
                             <div onclick="event.stopPropagation(); editAddress(${addr.id})">Edit</div>
                             <div onclick="event.stopPropagation(); deleteAddress(${addr.id})">Delete</div>
                           </div>
                         </div>
                       </div>
                     </div>
                   `;
                   section.innerHTML += card;
                 });

                let coordMatch = null;
                if (loc && loc.lat != null && loc.lng != null) {
                  const clat = Number(loc.lat);
                  const clng = Number(loc.lng);
                  coordMatch = list.find(function (a) {
                    return a.latitude != null && a.longitude != null &&
                      checkoutCoordsApproxEqual(a.latitude, a.longitude, clat, clng);
                  }) || null;
                }
                const serverSelected = list.find(addr => !!addr.is_selected);
                if (coordMatch) {
                  selectAddress(Number(coordMatch.id));
                } else if (serverSelected) {
                  selectAddress(Number(serverSelected.id));
                } else {
                  const preferredAddressId = localStorage.getItem("selectedSavedAddressId");
                  if (preferredAddressId) {
                    const preferredExists = list.some(addr => String(addr.id) === String(preferredAddressId));
                    if (preferredExists) {
                      selectAddress(Number(preferredAddressId));
                    }
                  }
                }
               });
           }

           // Initialize location from localStorage and set hidden inputs for form submit
           function initLocation() {
             let savedLocation = checkoutLocationRaw();
             if (savedLocation) {
               try {
                 let loc = JSON.parse(savedLocation);
                 if (loc.fullAddress) {
                   currentLocationDisplay.textContent = loc.fullAddress;
                 }
                 if (loc.lat != null && loc.lng != null) {
                   document.getElementById('currentLatitude').value = loc.lat;
                   document.getElementById('currentLongitude').value = loc.lng;
                   // Do not geocode here — background geocode was overwriting area/pincode with coords
                   // that did not match the user’s search result (e.g. wrong pincode on delivery error).
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
             if (input.readOnly && String(input.value || '').trim() !== '') {
               return;
             }

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

           /** Lock area + pincode only (e.g. after loading address for edit). */
           function lockAreaAndPincode() {
             const areaEl = document.querySelector('input[name="area"]');
             const pincodeEl = document.querySelector('input[name="pincode"]');
             if (!areaEl || !pincodeEl) return;
             if (areaEl.value.trim() || pincodeEl.value.trim()) {
               areaEl.readOnly = true;
               pincodeEl.readOnly = true;
               areaEl.classList.add('address-locked');
               pincodeEl.classList.add('address-locked');
             }
           }

           /** After Google search / geocode: area, pincode, landmark non-editable. */
           function lockFieldsFromGoogleSearch() {
             const areaEl = document.querySelector('input[name="area"]');
             const pincodeEl = document.querySelector('input[name="pincode"]');
             const landmarkEl = document.querySelector('input[name="landmark"]');
             if (areaEl && areaEl.value.trim()) {
               areaEl.readOnly = true;
               areaEl.setAttribute('autocomplete', 'off');
               areaEl.classList.add('address-locked');
             }
             if (pincodeEl && pincodeEl.value.trim()) {
               pincodeEl.readOnly = true;
               pincodeEl.classList.add('address-locked');
             }
             if (landmarkEl && landmarkEl.value.trim()) {
               landmarkEl.readOnly = true;
               landmarkEl.classList.add('address-locked');
             }
           }

           /** Bind area / landmark / pincode from saved fullAddress (same line as “Your Location”). */
           function syncCheckoutFormFromSavedUserLocation() {
             let raw = checkoutLocationRaw();
             if (!raw) return;
             try {
               let loc = JSON.parse(raw);
               let full = (loc.fullAddress || "").trim();
               if (!full || typeof window.parseFormattedAddressIndian !== "function") return;
               let fb = window.parseFormattedAddressIndian(full);
               let areaEl = document.querySelector('input[name="area"]');
               let landmarkEl = document.querySelector('input[name="landmark"]');
               let pinEl = document.querySelector('input[name="pincode"]');
               if (areaEl && fb.area) areaEl.value = fb.area;
               if (landmarkEl && fb.landmark) landmarkEl.value = fb.landmark;
               if (pinEl && fb.postalCode) pinEl.value = fb.postalCode;
               if (fb.area || fb.landmark || fb.postalCode) {
                 lockFieldsFromGoogleSearch();
               }
             } catch (e) {
               console.error("syncCheckoutFormFromSavedUserLocation:", e);
             }
           }

           function unlockAreaAndPincode() {
             const areaEl = document.querySelector('input[name="area"]');
             const pincodeEl = document.querySelector('input[name="pincode"]');
             const landmarkEl = document.querySelector('input[name="landmark"]');
             if (areaEl) { areaEl.readOnly = false; areaEl.classList.remove('address-locked'); }
             if (pincodeEl) { pincodeEl.readOnly = false; pincodeEl.classList.remove('address-locked'); }
             if (landmarkEl) { landmarkEl.readOnly = false; landmarkEl.classList.remove('address-locked'); }
           }

           // Extract address from Google Places / Geocode (area = sublocality e.g. Basti Khas; landmark = rest)
           function extractAddressComponents(place) {
             if (!place) return;
             unlockAreaAndPincode();
             const b = typeof window.buildAreaLandmarkFromPlace === 'function'
               ? window.buildAreaLandmarkFromPlace(place)
               : null;
             if (!b) return;

             const areaEl = document.querySelector('input[name="area"]');
             const landmarkEl = document.querySelector('input[name="landmark"]');
             const pinEl = document.querySelector('input[name="pincode"]');
             const flatEl = document.querySelector('input[name="flat"]');

             if (areaEl && b.area) areaEl.value = b.area;
             if (landmarkEl && b.landmark) landmarkEl.value = b.landmark;
             if (pinEl && b.postalCode) pinEl.value = b.postalCode;
             if (flatEl && (b.streetNumber || b.route)) {
               flatEl.value = [b.streetNumber, b.route].filter(Boolean).join(' ').trim();
             }

             lockFieldsFromGoogleSearch();
           }
           window.lockAreaAndPincode = lockAreaAndPincode;
           window.unlockAreaAndPincode = unlockAreaAndPincode;

           // Detect current location
           function detectLocation() {
             checkoutGeoGen++;
             const geoOpGen = checkoutGeoGen;
             useCurrentLocationBtn.innerHTML = '<span class="location-loading"></span> Detecting location...';

             if (navigator.geolocation) {
               navigator.geolocation.getCurrentPosition(
                 async (position) => {
                   if (geoOpGen !== checkoutGeoGen) return;

                   let lat = position.coords.latitude;
                   let lng = position.coords.longitude;

                   // First check if this location is deliverable by all vendors in the order
                   try {
                     const checkRes = await fetch("{{ url('/order/check-delivery-location') }}", {
                       method: 'POST',
                       headers: {
                         'Content-Type': 'application/json',
                         'X-CSRF-TOKEN': '{{ csrf_token() }}',
                         'Accept': 'application/json'
                       },
                       body: JSON.stringify({
                         latitude: lat,
                         longitude: lng
                       })
                     });
                     const checkData = await checkRes.json();

                     if (!checkData.deliverable) {
                       if (geoOpGen === checkoutGeoGen) {
                         useCurrentLocationBtn.innerHTML = '<i class="fas fa-location-arrow"></i> Use my current location';
                         Swal.fire({
                           icon: 'error',
                           title: 'Delivery not available',
                           text: checkData.message || 'Sorry we could not deliver on this address.'
                         });
                       }
                       return;
                     }
                   } catch (err) {
                     console.error('Delivery check error:', err);
                     if (geoOpGen === checkoutGeoGen) {
                       useCurrentLocationBtn.innerHTML = '<i class="fas fa-location-arrow"></i> Use my current location';
                       Swal.fire({
                         icon: 'error',
                         title: 'Error',
                         text: 'Unable to verify delivery for this address. Please try again.'
                       });
                     }
                     return;
                   }

                   if (geoOpGen !== checkoutGeoGen) return;

                   document.getElementById('latitude').value = lat;
                   document.getElementById('longitude').value = lng;

                   reverseGeocode(lat, lng, geoOpGen);
                 },
                 (error) => {
                   if (geoOpGen !== checkoutGeoGen) return;
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

           // Reverse geocode coordinates to address. expectedGen must match checkoutGeoGen or result is discarded (stale).
           async function reverseGeocode(lat, lng, expectedGen) {
             useCurrentLocationBtn.innerHTML = '<span class="location-loading"></span> Detecting location...';
             let geocodeUrl = `https://maps.googleapis.com/maps/api/geocode/json?latlng=${lat},${lng}&key=${googlemapkey}`;

             try {
               let response = await fetch(geocodeUrl);
               let data = await response.json();

               if (expectedGen !== checkoutGeoGen) {
                 return;
               }

               if (data.status === "OK" && data.results.length) {
                 let result = data.results[0];
                 let address = result.formatted_address;

                 currentLocationDisplay.textContent = address;
                 extractAddressComponents(result);

                 let shortAddress = getShortAddress(address, result);
                 const locJson = JSON.stringify({
                   fullAddress: address,
                   shortAddress: shortAddress,
                   lat: lat,
                   lng: lng
                 });
                 if (typeof window.commitGuestServiceableLocation === "function" && !window.IS_LOGGED_IN) {
                   window.commitGuestServiceableLocation(locJson);
                 } else {
                   localStorage.setItem("userLocation", locJson);
                 }
                 useCurrentLocationBtn.innerHTML = '<i class="fas fa-location-arrow"></i> Use my current location';
               } else {
                 throw new Error("No results found");
               }
             } catch (error) {
               if (expectedGen !== checkoutGeoGen) {
                 return;
               }
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

           // Load on page load: location text → form bind (locked) → then Places on editable flow
           initLocation();
           syncCheckoutFormFromSavedUserLocation();
           initAutocomplete();

           loadSavedAddresses();
           window.loadSavedAddresses = loadSavedAddresses;
           window.resetForm = resetForm;
         });

         // Toggle dropdown menu
         function toggleDropdown(el) {
           el.nextElementSibling.classList.toggle("show");
         }

         // Delete address
         function deleteAddress(id) {
           if (!confirm("Are you sure to delete this address?")) return;

           fetch(`{{ url('address/delete') }}/${id}`, {
             method: 'DELETE',
             headers: {
               'X-CSRF-TOKEN': '{{ csrf_token() }}'
             }
           })
           .then(res => res.json())
           .then(data => {
             alert(data.message);
             if (selectedAddressId === id) {
               selectedAddressId = null;
               document.getElementById('proceedToPayBtn').disabled = true;
             }
             loadSavedAddresses();
           })
           .catch(err => {
             alert('Delete failed');
           });
         }

         // Edit address
         function editAddress(id) {
           fetch(`{{ url('customer/address') }}/${id}`)
             .then(res => res.json())
             .then(data => {
               const address = data.address;

               if (!address) return alert("Address not found");

               if (window.unlockAreaAndPincode) window.unlockAreaAndPincode();

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
               if (window.lockAreaAndPincode) window.lockAreaAndPincode();
             })
             .catch(err => {
               alert('Failed to load address');
               console.error(err);
             });
         }

         // Select address
         function selectAddress(id) {
           selectedAddressId = id;
          localStorage.setItem("selectedSavedAddressId", String(id));
           document.getElementById('selectedAddressId').value = id;

          fetch(`{{ url('address/select') }}/${id}`, {
            method: 'POST',
            headers: {
              'X-CSRF-TOKEN': '{{ csrf_token() }}',
              'Accept': 'application/json'
            }
          }).catch(() => {});

           // Update UI to show selected address
           document.querySelectorAll('.address-card').forEach(card => {
             card.classList.remove('selected-address');
           });
           document.getElementById(`address-${id}`).classList.add('selected-address');

           // Enable proceed button
           document.getElementById('proceedToPayBtn').disabled = false;
         }

         // Proceed to payment
         document.getElementById('proceedToPayBtn').addEventListener('click', function() {
           if (!selectedAddressId) {
             alert('Please select an address');
             return;
           }

           // Require current location so we can validate address is in delivery area
           let savedLocation = checkoutLocationRaw();
           let hasLocation = false;
           if (savedLocation) {
             try {
               let loc = JSON.parse(savedLocation);
               if (loc.lat != null && loc.lng != null) {
                 document.getElementById('currentLatitude').value = loc.lat;
                 document.getElementById('currentLongitude').value = loc.lng;
                 hasLocation = true;
               }
             } catch (e) {}
           }
           if (!hasLocation) {
             alert('Please set your current location first (click "Use my current location" or enter and save an address in your current area).');
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
         function toggleDropdown(el) {
           document.querySelectorAll('.dropdown-menu').forEach(menu => {
             menu.style.display = 'none'; // Close all open menus first
           });
           el.nextElementSibling.style.display = 'block'; // Open the clicked one
         }

         document.addEventListener('click', function (event) {
           const isDropdown = event.target.closest('.dropdown-wrapper');

           if (!isDropdown) {
             // Clicked outside any dropdown → close all dropdowns
             document.querySelectorAll('.dropdown-menu').forEach(menu => {
               menu.style.display = 'none';
             });
           }
         });
      </script>

      <!-- Google Maps API (for location autocomplete) -->
      <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places&callback=initAutocomplete" async defer></script>
   </body>
</html>
