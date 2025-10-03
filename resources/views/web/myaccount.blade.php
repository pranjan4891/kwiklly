@extends('web.include.main')
@section('content')
<!-- first section start  -->
<section class="extrapadding">
   <div class="container py-4 bg-light">
      <div class="row g-3">
         <!-- Profile and Wallet -->
         <div class="col-md-8 position-relative">
            <div class="profile-card">
               <a href="#" class="profile-edit-btn" onclick="openPopup()">✎ Edit</a>
               <div class="d-flex align-items-center profilephoto">
                  <img src="
                     @if(auth()->user()->profile_photo)
                     {{ asset('public/' . auth()->user()->profile_photo) }}
                     @elseif(auth()->user()->avatar)
                     {{ auth()->user()->avatar }}
                     @else
                     {{ asset('public/assets/website/images/profile.jpeg') }}
                     @endif
                     "
                     class="rounded-circle me-3" alt="User">
                  @auth
                  <div class="profile-user-info">
                     <h6 class="mb-1">{{ auth()->user()->name }}</h6>
                     <small>{{ auth()->user()->email }}</small>
                     <small>+91 {{ auth()->user()->phone_number }}</small>
                  </div>
                  @endauth
               </div>
            </div>
         </div>
         <div class="col-md-4 position-relative">
            <div class="profile-card">
               <div class="d-flex align-items-center">
                  <div class="profile-user-info">
                     <h6 class="mb-3">Kwikily Wallet Balance</h6>
                     <span class="pricepopupaccount">
                     <span class="rupee-symbolaccount">₹</span> {{ number_format($walletBalance, 2) }}
                     </span>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="row mt-4">
         <!-- Sidebar -->
         <div class="col-md-3">
            <div class="profile-sidebar" id="profile-tab-group">
               <a href="#" class="profile-tab-btn active" data-target="orders">Orders</a>
               <a href="#" class="profile-tab-btn" data-target="address">Address</a>
               <a href="#" class="profile-tab-btn" data-target="couponsd">Coupons</a>
               <a href="#" class="profile-tab-btn" data-target="referrals">Referrals</a>
               <!-- Hidden Logout Form -->
               <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                  @csrf
               </form>
               <!-- Logout Link -->
               <a href="#" class="profile-tab-btn" onclick="confirmLogout(event)">Logout</a>
            </div>
         </div>
         <!-- Content -->
         <div class="col-md-9">
            <div class="profile-order-box">
               <!-- Orders -->
               <div class="profile-tab-content active" id="orders">
                  <h5 class="mb-3">Orders</h5>
                  @forelse ($groupedOrders as $order)
                  @foreach ($order['vendors'] as $vendorName => $vendorData)
                  <div class="profile-order-card">
                     <img src="{{ $vendorData['image'] }}" class="profile-order-img" alt="{{ $vendorName }}">
                     <div class="profile-order-details">
                        <h6 class="mb-1"><b>{{ $vendorName }}</b></h6>
                        <small>{{ ucfirst($order['status']) }} on: {{ $order['date'] }}</small><br>
                        <small>Order ID: #{{ $order['order_id'] }}</small><br>
                        @if($vendorData['delivery_date'])
                        <small>Estimate Delivery On: {{ $vendorData['delivery_date'] }}</small>
                        @endif
                        <!-- Show items for this vendor -->
                        <ul class="mt-2 mb-1 ps-3 small text-muted">
                           @foreach ($vendorData['items'] as $item)
                           <li>{{ $item['product_title'] }}
                              @if($item['variant'])
                              ({{ $item['variant'] }})
                              @endif
                              - {{ $item['quantity'] }} x ₹{{ number_format($item['price'], 2) }}
                           </li>
                           @endforeach
                        </ul>
                        <div class="profile-action-links mt-2">
                           <a href="{{ route('customer.orderDetails', $order['order_id']) }}">Show Details</a>
                           @if($order['status'] !== 'cancelled' && $order['status'] !== 'delivered')
                           <a href="{{ route('order.cancel', $order['order_id']) }}">Cancel Order</a>
                           @endif
                        </div>
                     </div>
                     <div class="profile-total-badge">Total ₹{{ number_format($vendorData['vendor_total'], 2) }}</div>
                  </div>
                  @endforeach
                  @empty
                  <div class="text-center py-4">
                     <p>No orders found.</p>
                  </div>
                  @endforelse
               </div>
               <!-- Address -->
               <div class="profile-tab-content" id="address">
                  <div class="address-wrapper">
                     <h3><strong>Address</strong></h3>
                     <div class="add-address-box">
                        <strong>Add new address</strong>
                     </div>
                     @forelse ($addresses as $address)
                     <div class="address-card {{ $loop->first ? 'selected' : '' }}">
                        <div class="address-left">
                           <img src="@if($address->type == 'home') https://cdn-icons-png.flaticon.com/128/69/69524.png @elseif($address->type == 'work') https://cdn-icons-png.flaticon.com/128/609/609803.png @else https://cdn-icons-png.flaticon.com/128/69/69524.png @endif" alt="{{ $address->type }}" class="icon">
                           <div>
                              <strong>{{ Str::ucfirst($address->type) }}</strong>
                              <p>{{ $address->name }}, {{ $address->flat }}, {{ $address->area }}, {{ $address->landmark }}, {{ $address->pincode }}</p>
                              <p>Phone: {{ $address->phone }} @if($address->alt_phone) / {{ $address->alt_phone }} @endif</p>
                           </div>
                        </div>
                        <div class="address-right">
                           <span class="check">&#x2714;</span>
                           <div class="dropdown-wrapper">
                              <span class="options" onclick="toggleDropdown(this)">&#8942;</span>
                              <div class="dropdown-menu">
                                 <div onclick="editAddress({{ $address->id }})">Edit</div>
                                 <div onclick="deleteAddress({{ $address->id }})">Delete</div>
                              </div>
                           </div>
                        </div>
                     </div>
                     @empty
                     <div class="text-center py-4">
                        <p>No addresses found.</p>
                     </div>
                     @endforelse
                  </div>
               </div>
               <!-- Coupons -->
               <div class="profile-tab-content" id="couponsd">
                  <h4 class="fw-bold">Coupons</h4>
                  @forelse ($coupons->groupBy('applies_to') as $type => $typeCoupons)
                  <h6 class="fw-bold mt-4">{{ ucfirst($type) }}</h6>
                  <div class="row">
                     @foreach ($typeCoupons as $coupon)
                     <div class="col-md-6 mb-3">
                        <div class="profile-coupon-card">
                           <div class="d-flex justify-content-between align-items-start">
                              <div>
                                 <h5 class="fw-bold mb-1">{{ $coupon->discount_value }}{{ $coupon->discount_type == 'percentage' ? '%' : '₹' }} OFF</h5>
                                 @if($coupon->discount_type == 'percentage' && $coupon->max_discount)
                                 <p class="text-success mb-1">MAX ₹{{ $coupon->max_discount }}</p>
                                 @endif
                                 <p class="fw-semibold mb-1">{{ $coupon->code }}</p>
                                 <p class="text-muted small mb-0">
                                    @if($coupon->vendor)by {{ $coupon->vendor->business_name }}@endif
                                    <i class="bi bi-chevron-right"></i>
                                 </p>
                              </div>
                              <div class="text-end">
                                 @if($coupon->vendor && $coupon->vendor->business_logo)
                                 <img src="{{ url('public/' . $coupon->vendor->business_logo) }}" alt="logo" class="profile-logo-img mb-2">
                                 @endif
                                 <p class="text-muted small">EXPIRES {{ $coupon->expires_at->format('d/m/Y') }}</p>
                              </div>
                           </div>
                           <div class="profile-coupon-footer text-end">
                              <span class="text-muted small">
                              Min. order: ₹{{ $coupon->min_order_amount }}
                              </span>
                           </div>
                        </div>
                     </div>
                     @endforeach
                  </div>
                  @empty
                  <div class="text-center py-4">
                     <p>No coupons available.</p>
                  </div>
                  @endforelse
               </div>
               <!-- Referrals -->
               <div class="profile-tab-content" id="referrals">
                  <h4 class="fw-bold">Refer a friend to earn extra cash</h4>
                  <h6 class="fw-bold mt-3">How it works</h6>
                  <div class="row text-center mt-4 profile-referral-steps">
                     <div class="col-md-4 mb-4">
                        <div class="position-relative d-inline-block">
                           <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" alt="step 1" class="rounded-circle profile-referral-img">
                           <div class="profile-referral-step">1</div>
                        </div>
                        <p class="mt-3">Share the referral<br> link with your friend</p>
                     </div>
                     <div class="col-md-4 mb-4">
                        <div class="position-relative d-inline-block">
                           <img src="https://cdn-icons-png.flaticon.com/512/891/891419.png" alt="step 2" class="rounded-circle profile-referral-img">
                           <div class="profile-referral-step">2</div>
                        </div>
                        <p class="mt-3">After your friend places<br> their first order, you get<br> 25% off up to ₹200 on your next order</p>
                     </div>
                     <div class="col-md-4 mb-4">
                        <div class="position-relative d-inline-block">
                           <img src="https://cdn-icons-png.flaticon.com/512/3135/3135706.png" alt="step 3" class="rounded-circle profile-referral-img">
                           <div class="profile-referral-step">3</div>
                        </div>
                        <p class="mt-3">Upon 10 successful<br> referrals, you earn<br> ₹2000</p>
                     </div>
                  </div>
                  <div class="d-flex flex-column align-items-center gap-3 mt-3">
                     <button class="profile-referral-btn profile-whatsapp-btn">
                     <i class="fa-brands fa-whatsapp"></i> Invite via whatsapp
                     </button>
                     <button class="profile-referral-btn profile-link-btn">
                     <i class="fa-solid fa-up-right-from-square me-2"></i>Share Invite Link
                     </button>
                  </div>
               </div>
               <!-- Logout -->
               <div class="profile-tab-content" id="logout">
                  <h5 class="mb-3 text-danger">Logout</h5>
                  <p>You have been logged out successfully.</p>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>
<!-- Edit Profile Popup -->
<div class="mypopup-overlay" id="editPopup">
   <div class="mypopup-content">
      <div class="mypopup-header">
         <h2>Edit Profile</h2>
         <span class="mypopup-close" onclick="closePopup()">&times;</span>
      </div>
      <div class="mypopup-image">
         <img id="profilePreview" src="{{ auth()->user()->profile_photo
            ? asset('public/' . auth()->user()->profile_photo)
            :  asset('public/assets/website/images/profile.jpeg') }}"
            class="rounded-circle me-3" alt="User">
         <!-- ✅ Only ONE file input, inside form -->
         <input type="file" id="profilePhotoInput" name="profile_photo" accept="image/*" style="display:none;" onchange="previewImage(event)" />
         <div class="mypopup-image-links">
            <a href="javascript:void(0);" onclick="document.getElementById('profilePhotoInput').click()">Change image</a>
            <a href="javascript:void(0);" onclick="deleteImage()">Delete Image</a>
         </div>
      </div>
      <form id="updateProfileForm" class="mypopup-form">
         <input type="text" name="name" placeholder="Name*" value="{{ auth()->user()->name }}" required />
         <input type="email" name="email" placeholder="Email*" value="{{ auth()->user()->email }}" required />
         <input type="tel" name="phone_number" placeholder="Phone Number*" value="{{ auth()->user()->phone_number }}" required />
         <input type="hidden" name="delete_avatar" id="deleteAvatar" value="0" />
         <button type="button" onclick="updateProfile()">Save</button>
      </form>
   </div>
</div>

<!-- Edit Address Popup -->
<div class="mypopup-overlay" id="editAddressPopup">
  <div class="mypopup-content">
    <div class="mypopup-header">
      <h2>Edit Address</h2>
      <span class="mypopup-close" onclick="closeAddressPopup()">&times;</span>
    </div>

    <form id="addressForm">
        <input type="hidden" id="addressId" name="id" value="">
        <div class="pata-input my-2">
             <select id="addressType" name="type" class="form-control" required>
                <option value="home">Home</option>
                <option value="work">Work</option>
             </select>
        </div>
        <div class="pata-input">
            <input type="text" id="autocomplete" name="area" placeholder="Area / Sector / Locality*" class="form-control" required>
        </div>
        <div class="pata-input"><input type="text" name="flat" placeholder="Flat / Building no*" class="form-control" required></div>
        <div class="pata-input"><input type="text" name="landmark" placeholder="Landmark (optional)" class="form-control"></div>
        <div class="pata-input"><input type="text" name="pincode" placeholder="Pincode*" class="form-control" required></div>
        <div class="pata-input"><input type="text" name="name" placeholder="Name*" class="form-control" required></div>
        <div class="pata-input"><input type="text" name="phone" placeholder="Phone Number*" class="form-control" required></div>
        <div class="pata-input"><input type="text" name="alt_phone" placeholder="Alternate Phone Number (optional)" class="form-control"></div>
        <button type="button" class="pata-save-btn mt-3 w-100" onclick="updateAddress()">Save Address</button>
    </form>
  </div>
</div>
<!-- section end  -->
@endsection
@push('scripts')
<script>
   const tabs = document.querySelectorAll(".profile-tab-btn");
   const tabContents = document.querySelectorAll(".profile-tab-content");

   tabs.forEach(tab => {
     tab.addEventListener("click", function(e) {
       e.preventDefault();

       // Remove active class from all tabs and hide all content
       tabs.forEach(t => t.classList.remove("active"));
       tabContents.forEach(c => c.classList.remove("active"));

       // Add active class to clicked tab and show corresponding content
       this.classList.add("active");
       document.getElementById(this.dataset.target).classList.add("active");
     });
   });
</script>
<!-- SweetAlert2 -->
<script>
   function toggleDropdown(el) {
     const dropdown = el.nextElementSibling;
     document.querySelectorAll('.dropdown-menu').forEach(menu => {
       if (menu !== dropdown) menu.style.display = 'none';
     });
     dropdown.style.display = dropdown.style.display === 'flex' ? 'none' : 'flex';
   }



     function deleteAddress(addressId) {
         Swal.fire({
             title: 'Are you sure?',
             text: 'Do you want to delete this address?',
             icon: 'warning',
             showCancelButton: true,
             confirmButtonColor: '#ff4d00',
             cancelButtonColor: '#aaa',
             confirmButtonText: 'Yes, delete it!',
             cancelButtonText: 'Cancel'
         }).then((result) => {
             if (result.isConfirmed) {
             // AJAX call to delete address
             fetch('{{ route("address.delete", ":id") }}'.replace(':id', addressId), {
                 method: 'DELETE',
                 headers: {
                 'X-CSRF-TOKEN': '{{ csrf_token() }}',
                 'Content-Type': 'application/json'
                 }
             })
             .then(response => response.json())
             .then(data => {
                 if (data.success) {
                 Swal.fire('Deleted!', 'Your address has been deleted.', 'success');
                 // Remove the address card from DOM
                 document.querySelector(`.address-card[data-id="${addressId}"]`).remove();
                 } else {
                 Swal.fire('Error!', 'There was a problem deleting the address.', 'error');
                 }
             });
             }
         });
     }

   // Close dropdown on outside click
   document.addEventListener('click', function(e) {
     if (!e.target.closest('.dropdown-wrapper')) {
       document.querySelectorAll('.dropdown-menu').forEach(menu => {
         menu.style.display = 'none';
       });
     }
   });
</script>
<script>
   function openPopup() {
     document.getElementById("editPopup").style.display = "flex";
   }

   function closePopup() {
     document.getElementById("editPopup").style.display = "none";
   }

   function deleteImage() {
     document.getElementById('profilePreview').src = "{{ asset('public/assets/website/images/profile.jpeg') }}";
     document.getElementById('profilePhotoInput').value = "";
     document.getElementById('deleteAvatar').value = '1'; // mark for deletion
   }

   function previewImage(event) {
     const file = event.target.files[0];
     if (file) {
         const reader = new FileReader();
         reader.onload = function() {
             document.getElementById('profilePreview').src = reader.result;
         };
         reader.readAsDataURL(file);
         document.getElementById('deleteAvatar').value = '0'; // reset delete flag
     }
   }

   function updateProfile() {
     const form = document.getElementById('updateProfileForm');
     const formData = new FormData(form);

     const fileInput = document.getElementById('profilePhotoInput');
     if (fileInput.files.length > 0) {
         formData.set('profile_photo', fileInput.files[0]);
     }

     fetch('{{ route("update.profile.save") }}', {
         method: 'POST',
         body: formData,
         headers: {
             'X-CSRF-TOKEN': '{{ csrf_token() }}',
             'Accept': 'application/json' // ✅ Force JSON response
         }
     })
     .then(response => response.json())
     .then(data => {
         if (data.success) {
             Swal.fire('Success!', data.message, 'success').then(() => {
                 closePopup();
                 location.reload(); // reload UI to see updated photo
             });
         } else {
             Swal.fire('Error!', data.errors ? Object.values(data.errors).flat().join('<br>') : 'Something went wrong.', 'error');
         }
     })
     .catch(error => {
         Swal.fire('Error!', 'An error occurred. Please try again.', 'error');
     });
   }
</script>
<script>
   function confirmCancel(event) {
     event.preventDefault(); // prevent default link behavior

     Swal.fire({
       // title: 'Are you sure?',
       text: "Do you really want to cancel this order?",
       showCancelButton: true,
       confirmButtonColor: '#d33',
       cancelButtonColor: '#3085d6',
       confirmButtonText: 'Yes, cancel it !',
       cancelButtonText: 'No'
     }).then((result) => {
       if (result.isConfirmed) {
         // Replace this with your cancel logic
         Swal.fire(
           'Cancelled!',
           'Your order has been cancelled.',
           'success'
         );
       }
     });
   }
</script>

<script>
   let currentAddressId = null;

   function editAddress(addressId) {
       currentAddressId = addressId;
       fetch('{{ route("address.show", ":id") }}'.replace(':id', addressId), {
           headers: {
               'Accept': 'application/json'
           }
       })
       .then(response => response.json())
       .then(data => {
           // Populate form
           document.getElementById('addressId').value = addressId;
           document.getElementById('addressType').value = data.type.toLowerCase();
           document.querySelector('#addressForm [name=area]').value = data.area;
           document.querySelector('#addressForm [name=flat]').value = data.flat;
           document.querySelector('#addressForm [name=landmark]').value = data.landmark || '';
           document.querySelector('#addressForm [name=pincode]').value = data.pincode;
           document.querySelector('#addressForm [name=name]').value = data.name;
           document.querySelector('#addressForm [name=phone]').value = data.phone || '';
           document.querySelector('#addressForm [name=alt_phone]').value = data.alt_phone || '';
           document.getElementById("editAddressPopup").style.display = "flex";
       })
       .catch(error => {
           Swal.fire('Error!', 'Unable to load address.', 'error');
       });
   }

   function closeAddressPopup() {
       document.getElementById("editAddressPopup").style.display = "none";
   }

   function updateAddress() {
       const form = document.getElementById('addressForm');
       const formData = new FormData(form);
       formData.append('_method', 'POST');

       fetch('{{ route("address.update", ":id") }}'.replace(':id', currentAddressId), {
           method: 'POST',
           body: formData,
           headers: {
               'X-CSRF-TOKEN': '{{ csrf_token() }}',
               'Accept': 'application/json'
           }
       })
       .then(response => response.json())
       .then(data => {
           if (data.success) {
               Swal.fire('Success!', data.message, 'success').then(() => {
                   closeAddressPopup();
                   location.reload(); // reload to update addresses
               });
           } else {
               Swal.fire('Error!', data.errors ? Object.values(data.errors).flat().join('<br>') : 'Something went wrong.', 'error');
           }
       })
       .catch(error => {
           Swal.fire('Error!', 'An error occurred. Please try again.', 'error');
       });
   }

   function confirmLogout(event) {
       event.preventDefault(); // Stop default link behavior

       Swal.fire({
           title: 'Are you sure?',
           text: "You will be logged out of your account.",
           icon: 'warning',
           showCancelButton: true,
           confirmButtonColor: '#d33',
           cancelButtonColor: '#3085d6',
           confirmButtonText: 'Yes, Logout',
           cancelButtonText: 'Cancel'
       }).then((result) => {
           if (result.isConfirmed) {
               document.getElementById('logout-form').submit();
           }
       });
   }
</script>
@endpush
