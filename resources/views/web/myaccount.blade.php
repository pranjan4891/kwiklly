@extends('web.include.main')
@section('content')
<style>
.profile-order-img-wrapper {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    min-width: 190px;
}
.order-date-above-img {
    display: block;
    margin-bottom: 10px;
    font-size: 12px;
    font-weight: 600;
    color: #333;
    text-align: center;
    white-space: nowrap;
}
.profile-order-img {
    width: 190px;
    height: 148px;
    object-fit: cover;
    border-radius: 10px;
}

/* Mobile responsive */
@media (max-width: 768px) {
    .profile-order-img-wrapper {
        min-width: 100%;
        width: 100%;
    }
    .profile-order-img {
        width: 100%;
        max-width: 190px;
        height: 148px;
    }
    .order-date-above-img {
        font-size: 11px;
    }
}
</style>
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
                  <!-- <div class="profile-user-info">
                     <h6 class="mb-3">Kwikily Wallet Balance</h6>
                     <span class="pricepopupaccount">
                     <span class="rupee-symbolaccount">₹</span> {{ number_format($walletBalance, 2) }}
                     </span>
                  </div> -->
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
               {{-- <a href="#" class="profile-tab-btn" data-target="couponsd">Coupons</a> --}}
               {{-- <a href="#" class="profile-tab-btn" data-target="referrals">Referrals</a> --}}
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
                  @php
                     $deliveryStatus = $vendorData['delivery_status'] ?? 'pending';
                     
                     // Determine if cancelled by user or vendor
                     $isCancelled = ($deliveryStatus === 'cancelled');
                     $isUserCancelled = false;
                     if ($isCancelled) {
                        // User can only cancel within 5 minutes of order creation
                        $orderCreatedAt = $order['created_at'];
                        $currentTime = now()->timestamp;
                        $fiveMinutesInSeconds = 5 * 60;
                        $timeElapsed = $currentTime - $orderCreatedAt;
                        // If cancelled and within 5 minutes, likely user cancelled
                        // If cancelled after 5 minutes, likely vendor cancelled
                        $isUserCancelled = ($timeElapsed <= $fiveMinutesInSeconds);
                     }
                     
                     $statusColors = [
                        'pending' => ['bg' => '#FFF3CD', 'text' => '#856404', 'label' => 'Pending'],
                        'packed' => ['bg' => '#D1ECF1', 'text' => '#0C5460', 'label' => 'Packed'],
                        'shipped' => ['bg' => '#D4EDDA', 'text' => '#155724', 'label' => 'Out for Delivery'],
                        'delivered' => ['bg' => '#D1F2EB', 'text' => '#0E6655', 'label' => 'Delivered'],
                        'cancelled' => [
                           'bg' => '#F8D7DA', 
                           'text' => '#721C24', 
                           'label' => $isUserCancelled ? 'Cancelled' : 'Rejected by Store'
                        ]
                     ];
                     $statusConfig = $statusColors[$deliveryStatus] ?? $statusColors['pending'];
                  @endphp
                  <div class="profile-order-card">
                     <div class="profile-order-img-wrapper">
                        <small class="order-date-above-img"> {{ $order['date'] }}</small>
                     <img src="{{ $vendorData['image'] }}" class="profile-order-img" alt="{{ $vendorName }}">
                     </div>
                     <div class="profile-order-details">
                        <a href="{{ route('explorestore', ['vendor_id' => $vendorData['vendor_id'],'cat_id'=>'0']) }}" onclick="return redirectWithLocation(this.href)"><h6 class="mb-1"><b>{{ $vendorName }}</b></h6></a>
                        <small><strong>Order ID:</strong> #{{ $order['order_id'] }}</small><br>
                        @php
                          $pStatus = $order['payment_status'] ?? 'pending';
                          $pMethod = $order['payment_method'] ?? 'N/A';
                          $pMethodLabel = $pMethod === 'cod' ? 'Cash on Delivery' : ($pMethod === 'phonepe' ? 'PhonePe' : ucfirst($pMethod));
                          $pStatusLabel = ucfirst(str_replace('_', ' ', $pStatus));
                        @endphp
                        <small><strong>Payment:</strong> {{ $pMethodLabel }} · <span class="text-muted">{{ $pStatusLabel }}</span></small><br>
                        @if($vendorData['delivery_date'])
                        <small><strong>Estimate Delivery On:</strong> {{ $vendorData['delivery_date'] }}</small>
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
                           <a href="{{ route('customer.orderDetails', $order['order_id']) }}?vendor_id={{ $vendorData['vendor_id'] ?? '' }}">Show Details</a>
                           @if($order['status'] !== 'cancelled' && $order['status'] !== 'delivered')
                           @php
                              $orderCreatedAt = $order['created_at'];
                              $currentTime = now()->timestamp;
                              $fiveMinutesInSeconds = 5 * 60;
                              $canCancel = ($currentTime - $orderCreatedAt) <= $fiveMinutesInSeconds;
                           @endphp
                           <a href="{{ route('order.cancel', $order['order_id']) }}?vendor_id={{ $vendorData['vendor_id'] ?? '' }}" 
                              class="cancel-order-btn {{ $canCancel ? '' : 'disabled' }}" 
                              data-order-id="{{ $order['order_id'] }}"
                              data-vendor-id="{{ $vendorData['vendor_id'] ?? '' }}"
                              data-created-at="{{ $orderCreatedAt }}"
                              @if(!$canCancel) onclick="return false;" style="pointer-events: none; opacity: 0.5; cursor: not-allowed;" @endif>
                              Cancel Order
                           </a>
                           @endif
                        </div>
                     </div>
                     <div class="profile-order-status-wrapper">
                        <span class="delivery-status-badge" style="background-color: {{ $statusConfig['bg'] }}; color: {{ $statusConfig['text'] }};">
                           {{ $statusConfig['label'] }}
                        </span>
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
                     <div class="d-flex justify-content-between align-items-center mb-3">
                     <h3 class="mb-0"><strong>Address</strong></h3>                     
                     <button class="btn btn-success" onclick="openAddAddressPopup()">Add Address</button>
                 </div>
                     
                     @forelse ($addresses as $address)
                     <div class="address-card {{ $loop->first ? 'selected' : '' }}">
                        <div class="address-left">
                           @if($address->type == 'home')
                              <span class="address-icon">🏠</span>
                           @elseif($address->type == 'work')
                              <span class="address-icon">🏢</span>
                           @else
                              <span class="address-icon">🏠</span>
                           @endif
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
               {{-- <!-- Coupons -->
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
                                 <h5 class="fw-bold mb-1">{{ $coupon->discount_type == 'percentage' ? (int) round($coupon->discount_value) : $coupon->discount_value }}{{ $coupon->discount_type == 'percentage' ? '%' : '₹' }} OFF</h5>
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
               </div> --}}
               {{-- <!-- Referrals -->
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
               </div> --}}
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

<!-- Add New Address Popup -->
<div class="mypopup-overlay" id="addAddressPopup">
  <div class="mypopup-content">
    <div class="mypopup-header">
      <h2>Add New Address</h2>
      <span class="mypopup-close" onclick="closeAddAddressPopup()">&times;</span>
    </div>

    <form id="addAddressForm">
        <input type="hidden" name="type" id="addAddressType" value="home">
        <input type="hidden" name="latitude" id="addLatitude" value="">
        <input type="hidden" name="longitude" id="addLongitude" value="">
        
        <div class="pata-input my-2">
             <select id="addAddressTypeSelect" name="type" class="form-control" required>
                <option value="home">Home</option>
                <option value="work">Work</option>
             </select>
        </div>
        <div class="pata-input">
            <input type="text" id="addAutocomplete" name="area" placeholder="Area / Sector / Locality*" class="form-control" required>
        </div>
        <div class="pata-input">
            <input type="text" name="flat" placeholder="Flat / Building no*" class="form-control" required>
        </div>
        <div class="pata-input">
            <input type="text" name="landmark" placeholder="Landmark (optional)" class="form-control">
        </div>
        <div class="pata-input">
            <input type="text" name="pincode" placeholder="Pincode*" class="form-control" required>
        </div>
        <div class="pata-input">
            <input type="text" name="name" placeholder="Name*" class="form-control" required>
        </div>
        <div class="pata-input">
            <input type="text" name="phone" placeholder="Phone Number*" class="form-control" required>
        </div>
        <div class="pata-input">
            <input type="text" name="alt_phone" placeholder="Alternate Phone Number (optional)" class="form-control">
        </div>
        
        <button type="button" class="pata-save-btn mt-3 w-100" onclick="saveNewAddress()">Save Address</button>
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
        <input type="hidden" name="latitude" id="editLatitude" value="">
        <input type="hidden" name="longitude" id="editLongitude" value="">
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
           document.getElementById('editLatitude').value = data.latitude || '';
           document.getElementById('editLongitude').value = data.longitude || '';
           document.querySelector('#addressForm [name=name]').value = data.name;
           document.querySelector('#addressForm [name=phone]').value = data.phone || '';
           document.querySelector('#addressForm [name=alt_phone]').value = data.alt_phone || '';
           document.getElementById("editAddressPopup").style.display = "flex";
           setTimeout(function() { initEditAddressAutocomplete(); }, 100);
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

   // Check and disable cancel buttons after 5 minutes
   function checkCancelButtonTimeout() {
       const cancelButtons = document.querySelectorAll('.cancel-order-btn');
       const fiveMinutesInSeconds = 5 * 60; // 5 minutes in seconds
       
       cancelButtons.forEach(button => {
           const createdAt = parseInt(button.getAttribute('data-created-at'));
           if (!createdAt) return;
           
           const currentTime = Math.floor(Date.now() / 1000); // Current timestamp in seconds
           const timeElapsed = currentTime - createdAt;
           
           if (timeElapsed > fiveMinutesInSeconds) {
               // Disable the button
               button.classList.add('disabled');
               button.style.pointerEvents = 'none';
               button.style.opacity = '0.5';
               button.style.cursor = 'not-allowed';
               button.onclick = function(e) {
                   e.preventDefault();
                   return false;
               };
           }
       });
   }

   // Check on page load
   document.addEventListener('DOMContentLoaded', function() {
       checkCancelButtonTimeout();
       
       // Check every 30 seconds to update button states
       setInterval(checkCancelButtonTimeout, 30000);

       // Initialize address type select for add address
       const addAddressTypeSelect = document.getElementById('addAddressTypeSelect');
       const addAddressType = document.getElementById('addAddressType');

       if (addAddressTypeSelect && addAddressType) {
           addAddressTypeSelect.addEventListener('change', function() {
               addAddressType.value = this.value;
           });
       }

   });

   function openAddAddressPopup() {
       document.getElementById("addAddressPopup").style.display = "flex";
       resetAddAddressForm();
       // Initialize autocomplete after a short delay to ensure Google Maps is loaded
       setTimeout(function() {
           initAddAddressAutocomplete();
       }, 100);
   }

   function closeAddAddressPopup() {
       document.getElementById("addAddressPopup").style.display = "none";
       resetAddAddressForm();
   }

   function resetAddAddressForm() {
       const form = document.getElementById('addAddressForm');
       if (form) {
           form.reset();
           document.getElementById('addAddressType').value = 'home';
           document.getElementById('addLatitude').value = '';
           document.getElementById('addLongitude').value = '';
           const typeSelect = document.getElementById('addAddressTypeSelect');
           if (typeSelect) typeSelect.value = 'home';
       }
   }

   function saveNewAddress() {
       const form = document.getElementById('addAddressForm');
       const formData = new FormData(form);

       fetch('{{ route("address.store") }}', {
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
                   closeAddAddressPopup();
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

   // Initialize Google Places Autocomplete for add address
   function initAddAddressAutocomplete() {
       const input = document.getElementById('addAutocomplete');
       if (!input) return;
       
       if (typeof google === 'undefined' || typeof google.maps === 'undefined') {
           console.warn('Google Maps API not loaded');
           return;
       }

       // Remove existing autocomplete if any
       if (window.addAddressAutocomplete) {
           google.maps.event.clearInstanceListeners(window.addAddressAutocomplete);
       }

       window.addAddressAutocomplete = new google.maps.places.Autocomplete(input, {
           types: ['geocode'],
           componentRestrictions: { country: 'in' }
       });

       window.addAddressAutocomplete.addListener('place_changed', function() {
           const place = window.addAddressAutocomplete.getPlace();
           if (!place.geometry) {
               console.warn("No details available for input: '" + place.name + "'");
               return;
           }
           extractAddAddressComponents(place);
       });
   }

   // Extract address components for add address form
   function extractAddAddressComponents(place) {
       let streetNumber = '';
       let route = '';
       let locality = '';
       let postalCode = '';

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
           }
       }

       const form = document.getElementById('addAddressForm');
       if (form) {
           if (streetNumber || route) {
               const flatInput = form.querySelector('input[name="flat"]');
               if (flatInput) flatInput.value = [streetNumber, route].filter(Boolean).join(' ');
           }
           if (locality) {
               const areaInput = form.querySelector('input[name="area"]');
               if (areaInput) areaInput.value = locality;
           }
           if (postalCode) {
               const pincodeInput = form.querySelector('input[name="pincode"]');
               if (pincodeInput) pincodeInput.value = postalCode;
           }
           var lat = place.geometry && place.geometry.location ? place.geometry.location.lat() : null;
           var lng = place.geometry && place.geometry.location ? place.geometry.location.lng() : null;
           if (lat != null && lng != null) {
               document.getElementById('addLatitude').value = lat;
               document.getElementById('addLongitude').value = lng;
           }
       }
   }

   // Edit address: init autocomplete and capture lat/lng
   function initEditAddressAutocomplete() {
       const input = document.getElementById('autocomplete');
       if (!input) return;
       if (typeof google === 'undefined' || typeof google.maps === 'undefined') return;
       if (window.editAddressAutocomplete) {
           google.maps.event.clearInstanceListeners(window.editAddressAutocomplete);
       }
       window.editAddressAutocomplete = new google.maps.places.Autocomplete(input, {
           types: ['geocode'],
           componentRestrictions: { country: 'in' }
       });
       window.editAddressAutocomplete.addListener('place_changed', function() {
           var place = window.editAddressAutocomplete.getPlace();
           if (!place.geometry) return;
           var lat = place.geometry.location.lat();
           var lng = place.geometry.location.lng();
           document.getElementById('editLatitude').value = lat;
           document.getElementById('editLongitude').value = lng;
           var locality = '', postalCode = '';
           for (var i = 0; i < place.address_components.length; i++) {
               var c = place.address_components[i];
               if (c.types[0] === 'locality') locality = c.long_name;
               if (c.types[0] === 'postal_code') postalCode = c.long_name;
           }
           if (locality) document.querySelector('#addressForm [name=area]').value = locality;
           if (postalCode) document.querySelector('#addressForm [name=pincode]').value = postalCode;
       });
   }

</script>
@if(env('GOOGLE_MAPS_API_KEY'))
<script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places"></script>
@endif
@endpush
