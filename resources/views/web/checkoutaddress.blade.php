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
      <link rel="stylesheet" href="{{ asset('public/assets/website/assets/css/checkoutaddress.css')}}">
      <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
      <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
      <style>
         .selected-address {
            border: 2px solid #28a745 !important;
            background-color: #f8fff9;
         }
      </style>
   </head>
   <body>
      <section>
         <div class="container">
            <div class="row">
               <!-- Steps Navigation -->
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
               <hr style="border: 1px solid #D8C2BC;">
               <div class="col-md-6 main-content-box">
                  <div class="p-3 ">
                     <!-- Location address text -->
                     <div class="pata-location-title">Your Location</div>
                     <div class="pata-location-desc">
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
                        <div class="pata-input"><input type="text" name="area" placeholder="Area / Sector / Locality*" class="form-control" required></div>
                        <div class="pata-input"><input type="text" name="flat" placeholder="Flat / Building no*" class="form-control" required></div>
                        <div class="pata-input"><input type="text" name="landmark" placeholder="Landmark (optional)" class="form-control"></div>
                        <div class="pata-input"><input type="text" name="pincode" placeholder="Pincode*" class="form-control" required></div>
                        <div class="pata-input"><input type="text" name="name" placeholder="Name*" class="form-control" required></div>
                        <div class="pata-input"><input type="text" name="phone" placeholder="Phone Number*" class="form-control" required></div>
                        <div class="pata-input"><input type="text" name="alt_phone" placeholder="Alternate Phone Number (optional)" class="form-control"></div>
                        <button type="submit" class="pata-save-btn mt-3 w-100">Save Address</button>
                     </form>
                  </div>
               </div>
               <!-- RIGHT: Address List -->
               <div class="col-md-6 main-content-box">
                  <div class="address-section pataoverflow">
                     <div class="section-title text-center pt-3">
                        <h4>Your saved address for current location</h4>
                     </div>
                     <!-- Dynamic Address List Here -->
                     <div id="savedAddressList"></div>
                  </div>

                  <!-- Proceed button -->
                  <div class="text-center p-0 mt-3">
                     <button id="proceedToPayBtn" class="btn proceed-btn2" disabled>Proceed to Pay ₹{{ number_format($order->final_amount, 2) }}</button>
                  </div>
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

      <!-- Navbar -->
      <nav class="navbar navbar-expand-lg fixed-top">
         <div class="container-fluid">
            <!-- Desktop: Logo + Location & Search -->
            <div class="d-flex align-items-center w-100  d-md-flex">
               <a class="navbar-brand" href="{{ route('home')}}">
               <img src="{{ asset('public/assets/website/images/logo.png')}}" alt="Logo">
               </a>
            </div>
         </div>
      </nav>
      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
      <script type="text/javascript">
         let selectedAddressId = null;

         document.addEventListener("DOMContentLoaded", function () {
           const homeBtn = document.getElementById("pataHomeBtn");
           const workBtn = document.getElementById("pataWorkBtn");
           const addressType = document.getElementById("addressType");
           const form = document.getElementById('addressForm');
           const saveBtn = document.querySelector('.pata-save-btn');
           const proceedBtn = document.getElementById('proceedToPayBtn');

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

           // Submit form (Add/Update)
           form.addEventListener('submit', function (e) {
             e.preventDefault();

             const formData = new FormData(this);
             const editId = form.getAttribute('data-edit-id');
             const url = editId
               ? `{{ url('address/update') }}/${editId}`
               : `{{ route('address.store') }}`;
             const method = 'POST';

             fetch(url, {
               method: method,
               headers: {
                 'X-CSRF-TOKEN': '{{ csrf_token() }}'
               },
               body: formData
             })
             .then(res => {
               if (!res.ok) return res.text().then(text => { throw new Error(text) });
               return res.json();
             })
             .then(data => {
               alert(data.message);
               resetForm();
               loadSavedAddresses();
             })
             .catch(err => {
               console.error('Submission error:', err);
               alert('Something went wrong!');
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
           }

           // Load saved addresses
           function loadSavedAddresses() {
             fetch("{{ route('address.list') }}")
               .then(res => res.json())
               .then(data => {
                 const section = document.getElementById("savedAddressList");
                 section.innerHTML = '';

                 if (!data.length) {
                   section.innerHTML = '<p class="text-center">No saved addresses</p>';
                   return;
                 }

                 data.forEach(addr => {
                   const icon = addr.type === 'work' ? '609/609803' : '69/69524';
                   const card = `
                     <div class="address-card" id="address-${addr.id}" onclick="selectAddress(${addr.id})">
                       <div class="address-left">
                         <img src="https://cdn-icons-png.flaticon.com/128/${icon}.png" class="icon">
                         <div>
                           <strong>${addr.type.charAt(0).toUpperCase() + addr.type.slice(1)}</strong>
                           <p>${addr.name}, ${addr.flat}, ${addr.area}, ${addr.landmark || ''}, ${addr.pincode}</p>
                         </div>
                       </div>
                       <div class="address-right">
                         <label class="fancy-checkbox">
                           <input type="radio" name="selected_address" value="${addr.id}">
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
               });
           }

           // Load on page load
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
             })
             .catch(err => {
               alert('Failed to load address');
               console.error(err);
             });
         }

         // Select address
         function selectAddress(id) {
           selectedAddressId = id;
           document.getElementById('selectedAddressId').value = id;

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

           document.getElementById('proceedToPaymentForm').submit();
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
   </body>
</html>
