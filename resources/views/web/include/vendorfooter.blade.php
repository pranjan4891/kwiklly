
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
        let step = 0;
        let formDataObj = {};
        let errorMessages = {};
        let map, marker = null, boundaryPolygon = null;
            //   let mapInitialized = false;

        function updateForm() {
            let container = document.getElementById("dynamic-container");
            let confirmBtn = document.getElementById("confirm-btn");
            let backBtn = document.getElementById("back-btn");
            let heading = document.getElementById("form-heading");

            backBtn.style.display = step > 0 ? "inline-block" : "none";

            if (step === 0) {
                heading.textContent = "Register Today";
                container.innerHTML = `
                    <div id="map_canvas" class="map-container mb-3" style="height: 300px;"></div>
                    <hr>
                    <div class="form-group row">
                        <button type="button" id="current-location-btn" class="btn btn-primary btn-lg btn-block" style="border-radius: 25px;">
                            <i class="fas fa-location-arrow"></i> Use Current Location
                        </button>
                    </div>

                    <div class="log-in-divider">or</div>


                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">Pincode <span class="text-danger">*</span></label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" id="postal_code" name="postal_code" maxlength="6" required value="${formDataObj.postal_code || ''}">
                        </div>

                        <label class="col-lg-2 col-form-label">Place <span class="text-danger">*</span></label>
                        <div class="col-lg-4">
                            <select id="place" name="place" class="form-control" required disabled>
                                <option value="">Select Place</option>
                            </select>
                            <div class="error text-danger mb-2" data-error-for="place"></div>
                        </div>
                        <div class="error text-danger mb-2" id="postal_error" data-error-for="postal_code"></div>

                    </div>

                    <input type="hidden" name="place_name" id="place_name" value="${formDataObj.place_name || ''}">
                    <input type="hidden" name="lat_long" id="lat_long" value='${formDataObj.lat_long || ''}'>
                    <input type="hidden" name="latitude" id="latitude" value="${formDataObj.latitude || ''}">
                    <input type="hidden" name="longitude" id="longitude" value="${formDataObj.longitude || ''}">
                `;

                confirmBtn.textContent = "Confirm and Proceed";

                setTimeout(() => {
                    if (typeof google !== 'undefined') {
                        initializeMap();

                        const postal_code = formDataObj.postal_code || '';
                        if (postal_code) {
                            fetch("{{ route('admin.get.area') }}?pincode=" + postal_code)
                                .then(res => res.json())
                                .then(data => {
                                    const placeDropdown = document.getElementById('place');
                                    const postalError = document.getElementById('postal_error');
                                    placeDropdown.innerHTML = '<option value="">Select Place</option>';
                                    placeDropdown.disabled = true;
                                    postalError.textContent = ''; // Clear any previous error

                                    const places = data.places || data;
                                    if (Array.isArray(places) && places.length > 0) {
                                        places.forEach(loc => {
                                            if (loc.place && loc.lat_long) {
                                                try {
                                                    JSON.parse(loc.lat_long);
                                                    const option = document.createElement('option');
                                                    option.value = loc.lat_long;
                                                    option.text = loc.place;
                                                    option.setAttribute('data-place-name', loc.place);
                                                    if (loc.place === formDataObj.place_name) {
                                                        option.selected = true;
                                                    }
                                                    placeDropdown.appendChild(option);
                                                } catch (e) {}
                                            }
                                        });

                                        placeDropdown.disabled = false;

                                        // Draw boundary
                                        if (formDataObj.lat_long) {
                                            try {
                                                const latLong = JSON.parse(formDataObj.lat_long);
                                                drawPolygonBoundary(latLong);
                                            } catch (e) {}
                                        }

                                        // Place marker
                                        if (formDataObj.latitude && formDataObj.longitude) {
                                            placeMarker(new google.maps.LatLng(parseFloat(formDataObj.latitude), parseFloat(formDataObj.longitude)));
                                        }

                                    } else {
                                        postalError.textContent = "Sorry, currently we are not providing service here.";
                                    }
                                })
                                .catch(() => {
                                    document.getElementById('postal_error').textContent = "An error occurred. Please try again.";
                                });
                        }
                    }
                }, 100);
            }


            else if (step === 1) {
                heading.textContent = "Register Today";
                container.innerHTML = `
                    <h4>Your Location</h4>
                    <p><strong>Selected Place:</strong> ${formDataObj.place_name || "Not selected yet"}</p>

                    <h4>Personal Details</h4>
                    <div class="form-group">
                        <input type="email" name="email" class="form-control mb-1" placeholder="Email Id" value="${formDataObj.email || ''}">
                        <div class="error text-danger mb-2" data-error-for="email"></div>
                    </div>

                    <div class="form-group">
                        <input type="text" name="phone" class="form-control mb-1" placeholder="Phone Number" maxlength="10" required value="${formDataObj.phone || ''}">
                        <div class="error text-danger mb-2" data-error-for="phone"></div>
                    </div>

                    <div class="form-group">
                        <input type="password" name="password" class="form-control mb-1" placeholder="Password">
                        <div class="error text-danger mb-2" data-error-for="password"></div>
                    </div>

                    <div class="form-group">
                        <input type="password" name="password_confirmation" class="form-control mb-1" placeholder="Confirm Password">
                        <div class="error text-danger mb-2" data-error-for="password_confirmation"></div>
                    </div>
                `;
            }

            else if (step === 2) {
                heading.textContent = "Register Today";
                container.innerHTML = `
                    <h4>Company Details</h4>
                    <div class="form-group">
                        <input type="text" name="company_name" class="form-control mb-1" placeholder="Company Name" value="${formDataObj.company_name || ''}">
                        <div class="error text-danger mb-2" data-error-for="company_name"></div>
                    </div>

                    <div class="form-group">
                        <input type="text" name="gst_number" class="form-control mb-1" placeholder="GST Number" value="${formDataObj.gst_number || ''}">
                        <div class="error text-danger mb-2" data-error-for="gst_number"></div>
                    </div>

                    <div class="form-group">
                        <input type="text" name="company_address" class="form-control mb-1" placeholder="Address" value="${formDataObj.company_address || ''}">
                        <div class="error text-danger mb-2" data-error-for="company_address"></div>
                    </div>
                `;
            }

            else if (step === 3) {
                heading.textContent = "Register Today";
                confirmBtn.textContent = "Submit";
                container.innerHTML = `
                    <h4>Bank Details</h4>
                    <div class="form-group">
                        <input type="text" name="account_holder" class="form-control mb-1" placeholder="Account Holder Name" value="${formDataObj.account_holder || ''}">
                        <div class="error text-danger mb-2" data-error-for="account_holder"></div>
                    </div>

                    <div class="form-group">
                        <input type="text" name="account_number" class="form-control mb-1" placeholder="Account Number" value="${formDataObj.account_number || ''}">
                        <div class="error text-danger mb-2" data-error-for="account_number"></div>
                    </div>

                    <div class="form-group">
                        <input type="text" name="ifsc_code" class="form-control mb-1" placeholder="IFSC Code" value="${formDataObj.ifsc_code || ''}">
                        <div class="error text-danger mb-2" data-error-for="ifsc_code"></div>
                    </div>

                    <label class="pb-2">Cancelled Cheque</label>
                    <input type="file" name="cancelled_cheque" class="form-control mb-1">
                    <div class="error text-danger mb-2" data-error-for="cancelled_cheque"></div>
                `;
            }

            else if (step === 4) {
                Swal.fire({ text: "Thank you for your confirmation", icon: "success", confirmButtonText: "Continue" })
                    .then(() => {
                        step = 0;
                        formDataObj = {};
                        updateForm();
                    });
            }
        }

        function nextStep() {
            const inputs = document.querySelectorAll("#dynamic-container input, #dynamic-container select");
            errorMessages = {};

            inputs.forEach(input => {
                const name = input.name;
                const value = input.type === "file" ? input.files[0] : input.value.trim();

                if (!value && input.required) {
                    errorMessages[name] = "This field is required";
                } else {
                    if (name === "email" && !/^\S+@\S+\.\S+$/.test(value)) {
                        errorMessages[name] = "Invalid email format";
                    }
                    if (name === "phone" && !/^\d{10}$/.test(value)) {
                        errorMessages[name] = "Phone number must be 10 digits";
                    }
                    if (name === "password" && value.length < 6) {
                        errorMessages[name] = "Password must be at least 6 characters";
                    }
                    if (name === "password_confirmation" && value !== document.querySelector('input[name="password"]').value) {
                        errorMessages[name] = "Passwords do not match";
                    }
                    if (name === "postal_code" && (!/^\d{6}$/.test(value))) {
                        errorMessages[name] = "Pincode must be 6 digits";
                    }
                }
            });

            if (step === 0 && (!document.getElementById("latitude").value || !document.getElementById("longitude").value)) {
                errorMessages["map"] = "Please mark your location on the map";
                Swal.fire("Location Required", "Please mark your location on the map before proceeding.", "warning");
            }

            showErrors();

            if (Object.keys(errorMessages).length === 0) {
                inputs.forEach(input => {
                    if (input.type === "file") {
                        formDataObj[input.name] = input.files[0];
                    } else {
                        formDataObj[input.name] = input.value.trim();
                    }
                });

                if (step === 3) {
                    // Show loader
                    Swal.fire({ title: "Submitting...", allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                    submitForm();
                } else {
                    step++;
                    updateForm();
                }
            }
        }

        function prevStep() {
            if (step > 0) {
                step--;
                updateForm();
            }
        }

        function showErrors() {
            const errorElements = document.querySelectorAll("#dynamic-container .error");
            errorElements.forEach(div => {
                const fieldName = div.getAttribute("data-error-for");
                div.textContent = errorMessages[fieldName] || "";
            });
        }

        function submitForm() {
            const formData = new FormData();
            Object.entries(formDataObj).forEach(([key, value]) => {
                formData.append(key, value);
            });
            formData.append("_token", document.querySelector('input[name="_token"]').value);

            fetch("{{ route('registration.submit') }}", {
                method: "POST",
                body: formData,
            })
            .then(async res => {
                const text = await res.text();
                let data;
                try {
                    data = JSON.parse(text);
                } catch {
                    throw new Error(text); // Laravel error HTML
                }

                if (res.status === 422) {
                    // Validation error
                    const errors = data.errors || {};
                    errorMessages = {}; // reset

                    Object.keys(errors).forEach(field => {
                        errorMessages[field] = errors[field][0]; // take first error
                    });

                    showErrors();

                    Swal.fire({
                        title: "Validation Error",
                        html: Object.values(errors).map(errArr => `<div>${errArr[0]}</div>`).join(""),
                        icon: "error"
                    });

                    throw new Error("Validation failed");
                }

                return data;
            })
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: "Success!",
                        text: data.message,
                        icon: "success",
                        timer: 2000,
                        showConfirmButton: false
                    });
                    setTimeout(() => window.location.href = data.redirect, 2000);
                } else {
                    Swal.fire("Error", data.message, "error");
                }
            })
            .catch(err => {
                if (err.message !== "Validation failed") {
                    console.error("Server Error:", err);
                    Swal.fire("Error", "Something went wrong: " + err.message, "error");
                }
            });
        }

        function initializeMap() {
            map = new google.maps.Map(document.getElementById("map_canvas"), {
                center: { lat: 22.9734, lng: 78.6569 },
                zoom: 5,
                gestureHandling: 'greedy'
            });

            map.addListener("click", function(event) {
                handleMapClick(event.latLng);
            });
        }

        function handleMapClick(clickedLatLng) {
            if (!boundaryPolygon) {
                alert("Please select a place first.");
                return;
            }

            const isInside = google.maps.geometry.poly.containsLocation(clickedLatLng, boundaryPolygon);
            if (!isInside) {
                alert("Marker must be within the defined boundary.");
                return;
            }

            placeMarker(clickedLatLng);
        }

        function placeMarker(position) {
            if (marker) marker.setMap(null);

            marker = new google.maps.Marker({
                position: position,
                map: map,
                title: "Branch Location",
                draggable: true,
                icon: {
                    url: "{{ asset('public/marker.png') }}", // custom marker image
                    scaledSize: new google.maps.Size(40, 40), // resize if needed
                    origin: new google.maps.Point(0, 0),
                    anchor: new google.maps.Point(20, 40) // adjust anchor so tip points correctly
                }
            });

            formDataObj.latitude = position.lat();
            formDataObj.longitude = position.lng();

            document.getElementById("latitude").value = formDataObj.latitude;
            document.getElementById("longitude").value = formDataObj.longitude;

            marker.addListener('dragend', function() {
                const newPosition = marker.getPosition();
                if (!google.maps.geometry.poly.containsLocation(newPosition, boundaryPolygon)) {
                    alert("Marker must stay within the area.");
                    marker.setPosition(position);
                    return;
                }

                formDataObj.latitude = newPosition.lat();
                formDataObj.longitude = newPosition.lng();
                document.getElementById("latitude").value = formDataObj.latitude;
                document.getElementById("longitude").value = formDataObj.longitude;
            });

            map.setCenter(position);
            map.setZoom(16);
        }

        function drawPolygonBoundary(coords) {
            if (boundaryPolygon) boundaryPolygon.setMap(null);
            const path = coords.map(coord => new google.maps.LatLng(parseFloat(coord.lat), parseFloat(coord.lng)));
            boundaryPolygon = new google.maps.Polygon({
                paths: path,
                strokeColor: "#0000FF",
                strokeOpacity: 0.8,
                strokeWeight: 2,
                fillColor: "#0000FF",
                fillOpacity: 0.2,
                map: map,
                clickable: false
            });

            const bounds = new google.maps.LatLngBounds();
            path.forEach(coord => bounds.extend(coord));
            map.fitBounds(bounds);
            formDataObj.lat_long = JSON.stringify(coords);
        }

        document.addEventListener("DOMContentLoaded", () => {
            updateForm(); // initial render

            // ===== delegate click for the "Use Current Location" button =====
            document.body.addEventListener("click", function (e) {
                if (!e.target) return;
                if (e.target.id !== "current-location-btn") return;

                if (!navigator.geolocation) {
                    Swal.fire("Error", "Geolocation not supported by browser.", "error");
                    return;
                }

                Swal.fire({ title: "Detecting location...", allowOutsideClick: false, didOpen: () => Swal.showLoading() });

                navigator.geolocation.getCurrentPosition(async (position) => {
                    Swal.close();
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;

                    try {
                        const url = "{{ route('admin.check.lat.lon') }}?lat=" + encodeURIComponent(lat) + "&lng=" + encodeURIComponent(lng);
                        const res = await fetch(url);

                        if (!res.ok) {
                            // try to parse JSON error, otherwise show generic
                            let errObj = null;
                            try { errObj = await res.json(); } catch (_){ }
                            throw new Error(errObj && errObj.message ? errObj.message : ("Server returned " + res.status));
                        }

                        const data = await res.json();

                        if (data.success) {
                            // Fill pincode and place
                            const postalInput = document.getElementById("postal_code");
                            if (postalInput) postalInput.value = data.pincode || "";

                            document.getElementById('place_name').value = data.place || "";
                            formDataObj.postal_code = data.pincode || "";
                            formDataObj.place_name = data.place || "";
                            formDataObj.lat_long = JSON.stringify(data.lat_long || []);

                            const placeDropdown = document.getElementById('place');
                            if (placeDropdown) {
                                placeDropdown.innerHTML = '<option value="">Select Place</option>';
                                const option = document.createElement('option');
                                option.value = JSON.stringify(data.lat_long || []);
                                option.text = data.place || "Selected Place";
                                option.selected = true;
                                option.setAttribute('data-place-name', data.place || "");
                                placeDropdown.appendChild(option);
                                placeDropdown.disabled = false;
                            }

                            // Draw polygon & place marker
                            if (Array.isArray(data.lat_long)) {
                                drawPolygonBoundary(data.lat_long);
                            }
                            const latLng = new google.maps.LatLng(lat, lng);
                            placeMarker(latLng);
                        } else {
                            Swal.fire("Sorry!", data.message || "No service in your area.", "warning");
                        }
                    } catch (err) {
                        console.error("Check-lat-lon error:", err);
                        Swal.fire("Error", err.message || "Unable to check current location.", "error");
                    }

                }, (err) => {
                    Swal.close();
                    console.error("Geolocation error:", err);
                    Swal.fire("Error", "Location access denied or unavailable. Please allow location.", "error");
                }, { enableHighAccuracy: true, timeout: 10000 });
            });


            // ===== pincode input (manual entry) =====
            document.body.addEventListener("input", function (e) {
                if (!e.target) return;
                if (e.target.id !== 'postal_code') return;

                const pin = e.target.value.trim();
                if (pin.length === 6 && /^\d+$/.test(pin)) {
                    const url = "{{ route('admin.get.area') }}?pincode=" + encodeURIComponent(pin);

                    fetch(url)
                        .then(async res => {
                            if (!res.ok) {
                                let txt = "";
                                try { txt = await res.text(); } catch (_) {}
                                throw new Error("Server error: " + (txt || res.status));
                            }
                            return res.json();
                        })
                        .then(data => {
                            const placeDropdown = document.getElementById('place');
                            const errorMsg = document.getElementById('postal_error');
                            placeDropdown.innerHTML = '<option value="">Select Place</option>';
                            placeDropdown.disabled = true;
                            if (errorMsg) errorMsg.textContent = '';

                            const places = data.places || data;
                            if (Array.isArray(places) && places.length > 0) {
                                places.forEach(loc => {
                                    let latLong = loc.lat_long;
                                    if (typeof latLong === 'string') {
                                        try { latLong = JSON.parse(latLong); } catch (_) { latLong = null; }
                                    }
                                    if (Array.isArray(latLong)) {
                                        const option = document.createElement('option');
                                        option.value = JSON.stringify(latLong);
                                        option.text = loc.place || "";
                                        option.setAttribute('data-place-name', loc.place || "");
                                        placeDropdown.appendChild(option);
                                    }
                                });
                                placeDropdown.disabled = false;
                            } else {
                                if (errorMsg) errorMsg.textContent = "Sorry, currently we are not providing service here.";
                            }
                        })
                        .catch(err => {
                            console.error('Fetch error (get-area):', err);
                            const postalErr = document.getElementById('postal_error');
                            if (postalErr) postalErr.textContent = "An error occurred. Please try again.";
                        });
                }
            });


            // ===== place select change (draw polygon from selected option) =====
            document.body.addEventListener("change", function (e) {
                if (!e.target) return;
                if (e.target.id !== 'place') return;

                const selectedOption = e.target.options[e.target.selectedIndex];
                if (!selectedOption || !selectedOption.value) return;

                const placeName = selectedOption.getAttribute('data-place-name') || '';
                document.getElementById('place_name').value = placeName;
                formDataObj.place_name = placeName;

                try {
                    const latLong = JSON.parse(selectedOption.value);
                    if (Array.isArray(latLong)) {
                        drawPolygonBoundary(latLong);
                        if (marker) marker.setMap(null);
                        marker = null;
                        document.getElementById("latitude").value = '';
                        document.getElementById("longitude").value = '';
                        delete formDataObj.latitude;
                        delete formDataObj.longitude;
                    }
                } catch (err) {
                    console.warn("Could not parse lat_long from option value", err);
                }
            });

        }); // DOMContentLoaded
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
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=geometry,places"></script>
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
