@extends('web.include.main')

<style>
.invoicebtn .btn{
  border-color:#E94412;
  background-color:#F5DED8;
  color:#dc3545;
}
.invoicebtn .btn:hover{
  border-color:#E94412;
  background-color:#F5DED8;
  color:#dc3545;
}
.newbackground{
  background-color:#F9F9F9;
  padding:10px;
  border-radius:8px;
}
.newbackground .fa{
  font-size: 25px;
  margin: 10px 10px 0px 0px;
}
</style>




@section('content')
<!-- Order Details Section -->
<section class="extrapadding">
  <div class="container py-4 bg-light">
    <div class="mb-3">
      <button onclick="window.history.back()" class="back-btn">
        <i class="fas fa-arrow-left"></i> Back
      </button>
    </div>
    <div class="row g-3">
      <div class="">
        <div class="deorder-summary-box p-3 rounded shadow-sm mb-3 bg-white">
          <div class="d-flex justify-content-between">
            <div>
              <strong>Order Summary</strong><br>
            </div>
            <div class="text-end">
              @php
                // Get estimated delivery date/time
                $estimatedDelivery = null;
                if (isset($vendorId) && $vendorId) {
                  // If vendor filter is applied, show that vendor's delivery date
                  $vendorOrder = $order->vendorOrders->firstWhere('vendor_id', $vendorId);
                  if ($vendorOrder && $vendorOrder->deliverySlot) {
                    $estimatedDelivery = $vendorOrder->deliverySlot->formatted_date . ' ' . $vendorOrder->deliverySlot->time_range;
                  }
                } else {
                  // If no vendor filter, show the earliest delivery date from all vendors
                  $earliestSlot = $order->vendorOrders
                    ->filter(function($vo) { return $vo->deliverySlot !== null; })
                    ->map(function($vo) { return $vo->deliverySlot; })
                    ->sortBy(function($slot) {
                      return $slot->date->format('Y-m-d') . ' ' . $slot->start_time;
                    })
                    ->first();
                  
                  if ($earliestSlot) {
                    $estimatedDelivery = $earliestSlot->formatted_date . ' ' . $earliestSlot->time_range;
                  }
                }
                
                // Fallback if no delivery slot found
                if (!$estimatedDelivery) {
                  $estimatedDelivery = $order->created_at->addDays(2)->format('D j M Y, h:i A');
                }
              @endphp
              <strong>Estimated Delivery: {{ $estimatedDelivery }}</strong><br>
              <small>Order ID: #{{ $order->order_number }}</small><br>
              <small>Ordered on: {{ $order->created_at->format('D j M Y, h:i A') }}</small>
              @php
                $latestPayment = $order->payments->sortByDesc('created_at')->first();
              @endphp
              @if($latestPayment)
              <br><small><strong>Payment:</strong> {{ $latestPayment->payment_method === 'cod' ? 'Cash on Delivery' : ($latestPayment->payment_method === 'phonepe' ? 'PhonePe' : ucfirst($latestPayment->payment_method)) }} · <span class="text-muted">{{ ucfirst(str_replace('_', ' ', $latestPayment->payment_status)) }}</span></small>
              @else
              <br><small><strong>Payment:</strong> <span class="text-muted">Pending</span></small>
              @endif
            </div>
          </div>

          <hr>
          
          {{-- Vendor-wise Products --}}
          @php
            // Filter vendor orders if vendor_id is provided
            $vendorOrdersToShow = $order->vendorOrders;
            if (isset($vendorId) && $vendorId) {
              $vendorOrdersToShow = $order->vendorOrders->filter(function($vo) use ($vendorId) {
                return $vo->vendor_id == $vendorId;
              });
            }
          @endphp
          
          @foreach ($vendorOrdersToShow as $vendorOrder)
            @php
              $vendor = $vendorOrder->vendor;
              $vendorItems = $vendorOrder->orderItems;
            @endphp
            
            <div class="mb-4">
              {{-- Vendor Info --}}
              <div class="newbackground d-flex mb-3">
                <div><i class="fa fa-store"></i></div>
                <div>
                  <small><strong>{{ $vendor->business_name ?? 'N/A' }}</strong></small><br>
                  <small>{{ $vendor->business_address ?? 'No address available' }}</small><br>
                  <small>GST: {{ $vendor->gstin ?? '000000000000000' }}</small>
                </div>
              </div>

              {{-- Vendor Items --}}
          <div class="mb-3">
                <strong>{{ $vendorItems->count() }} items from this store</strong>

                @foreach ($vendorItems as $item)
              @php
                    $product = $item->product;
                $key = $product->id . '_' . $item->variant_id;
              @endphp

              <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="d-flex align-items-center">
                  <img src="{{ $product->feature_image_id ? asset('public/' . $product->featureImage->feature_image) : asset('public/assets/website/images/default.png') }}" width="100" alt="{{ $product->title }}">
                  <div class="ms-3">
                    <a href="{{ route('subcategory.products', ['vendor_id' => $product->vendor_id,'category_id' => $product->category_id,'subcategory_id' => $product->sub_category_id]) }}" onclick="return redirectWithLocation(this.href)"><strong>{{ $product->title }}{{ $item->variant && $item->variant->variant_name ? ' - ' . $item->variant->variant_name : '' }}</strong></a><br>
                    ₹{{ $item->price * $item->quantity }} <small>(₹{{ $item->price }} X {{ $item->quantity }})</small>
                  </div>
                </div>
                <div>
                  <div class="qty-box" id="qtyBox-{{ $key }}">
                    <button class="add-btn py-2 add-again-btn" 
                      data-product-id="{{ $product->id }}"
                      data-variant-id="{{ $item->variant_id }}"
                      data-key="{{ $key }}"
                      onclick="handleAddAgain(this)">
                      <span class="btn-text">Add Again</span>
                      <img src="{{ asset('public/assets/website/images/cart.svg') }}" alt="" class="ms-2 btn-icon">
                    </button>
                  </div>
                  <div class="text-center text-muted mt-1" style="font-size: 12px;">
                    Order for ₹{{ $item->price }} per unit
                  </div>
                </div>
              </div>
            @endforeach
          </div>

              {{-- Vendor Bill Summary --}}
              <div class="deorder-bill-summary mb-4">
                <h6 class="fw-bold mb-2">Bill Summary - {{ $vendor->business_name }}</h6>
                @php
                  $vendorSubtotal = $vendorItems->sum(fn($i) => $i->price * $i->quantity);
                  $vendorDeliveryCharge = $vendorOrder->delivery_charge ?? 0;
                  $vendorCouponDiscount = $vendorOrder->coupon_discount ?? 0;
                  $vendorWalletDiscount = $vendorOrder->wallet_discount ?? 0;
                  $vendorGrandTotal = $vendorOrder->final_amount ?? ($vendorSubtotal + $vendorDeliveryCharge - $vendorCouponDiscount - $vendorWalletDiscount);
                  
                  // Calculate original total for saved amount
                  $vendorOriginalTotal = $vendorItems->sum(function($i) {
                    return ($i->variant->variant_actual_price ?? $i->price) * $i->quantity;
                  });
                  $vendorSavedAmount = $vendorOriginalTotal - $vendorSubtotal;
                @endphp
                <div class="d-flex justify-content-between">
                  <span><i class="fa fa-shopping-cart me-1"></i> Item charge</span>
                  <span>
                    ₹{{ number_format($vendorSubtotal, 2) }}
                    @if($vendorSavedAmount > 0)
                      <s class="text-muted">₹{{ number_format($vendorOriginalTotal, 2) }}</s>
                    @endif
                  </span>
                </div>
                <div class="d-flex justify-content-between">
                  <span><i class="fa fa-truck me-1"></i> Delivery Charges</span>
                  <span>₹{{ number_format($vendorDeliveryCharge, 2) }}</span>
                </div>
                @if($vendorCouponDiscount > 0)
                <div class="d-flex justify-content-between">
                  <span><i class="fa fa-tag me-1"></i> Coupon Discount</span>
                  <span class="text-success">- ₹{{ number_format($vendorCouponDiscount, 2) }}</span>
                </div>
                @endif
                @if($vendorWalletDiscount > 0)
                <div class="d-flex justify-content-between">
                  <span><i class="fa fa-wallet me-1"></i> Wallet Discount</span>
                  <span class="text-success">- ₹{{ number_format($vendorWalletDiscount, 2) }}</span>
                </div>
                @endif
                <hr>
                <div class="d-flex justify-content-between align-items-center fw-bold">
                  <span>Total Charges</span>
                  <div class="text-end">
                    <div>
                      @if($vendorSavedAmount > 0)
                        <span class="text-muted text-decoration-line-through">₹{{ number_format($vendorOriginalTotal + $vendorDeliveryCharge, 2) }}</span>
                      @endif
                      <span class="ms-1">₹{{ number_format($vendorGrandTotal, 2) }}</span>
                    </div>
                    @if($vendorSavedAmount > 0)
                    <span class="badge bg-success bg-opacity-10 text-success mt-1 rounded-pill px-2 py-1" style="font-weight: 500;">
                      Saved ₹{{ number_format($vendorSavedAmount, 0) }}
                    </span>
                    @endif
                  </div>
                </div>
              </div>
              
              @if(!$loop->last)
                <hr class="my-4">
              @endif
            </div>
          @endforeach

          {{-- Overall Order Summary --}}
          @if(!isset($vendorId) || !$vendorId)
          <hr>
          <div class="deorder-bill-summary">
            <h6 class="fw-bold mb-2">Overall Bill Summary</h6>
            @php
              $totalSubtotal = $order->vendorOrders->sum(function($vo) {
                return $vo->orderItems->sum(fn($i) => $i->price * $i->quantity);
              });
              $totalDelivery = $order->vendorOrders->sum(fn($vo) => $vo->delivery_charge ?? 0);
              $totalCoupon = $order->vendorOrders->sum(fn($vo) => $vo->coupon_discount ?? 0);
              $totalWallet = $order->vendorOrders->sum(fn($vo) => $vo->wallet_discount ?? 0);
              $grandTotal = $order->total_price ?? ($totalSubtotal + $totalDelivery - $totalCoupon - $totalWallet);
              
              $totalOriginal = $order->vendorOrders->sum(function($vo) {
                return $vo->orderItems->sum(function($i) {
                  return ($i->variant->variant_actual_price ?? $i->price) * $i->quantity;
                });
              });
              $totalSaved = $totalOriginal - $totalSubtotal;
            @endphp
            <div class="d-flex justify-content-between">
              <span><i class="fa fa-shopping-cart me-1"></i> Item charge</span>
              <span>
                ₹{{ number_format($totalSubtotal, 2) }}
                @if($totalSaved > 0)
                  <s class="text-muted">₹{{ number_format($totalOriginal, 2) }}</s>
                @endif
              </span>
            </div>
            <div class="d-flex justify-content-between">
              <span><i class="fa fa-truck me-1"></i> Delivery Charges</span>
              <span>₹{{ number_format($totalDelivery, 2) }}</span>
            </div>
            @if($totalCoupon > 0)
            <div class="d-flex justify-content-between">
              <span><i class="fa fa-tag me-1"></i> Coupon Discount</span>
              <span class="text-success">- ₹{{ number_format($totalCoupon, 2) }}</span>
            </div>
            @endif
            @if($totalWallet > 0)
            <div class="d-flex justify-content-between">
              <span><i class="fa fa-wallet me-1"></i> Wallet Discount</span>
              <span class="text-success">- ₹{{ number_format($totalWallet, 2) }}</span>
            </div>
            @endif
            <hr>
            <div class="d-flex justify-content-between align-items-center fw-bold">
              <span>Total Charges</span>
              <div class="text-end">
                <div>
                  @if($totalSaved > 0)
                    <span class="text-muted text-decoration-line-through">₹{{ number_format($totalOriginal + $totalDelivery, 2) }}</span>
                  @endif
                  <span class="ms-1">₹{{ number_format($grandTotal, 2) }}</span>
                </div>
                @if($totalSaved > 0)
                <span class="badge bg-success bg-opacity-10 text-success mt-1 rounded-pill px-2 py-1" style="font-weight: 500;">
                  Saved ₹{{ number_format($totalSaved, 0) }}
                </span>
                @endif
              </div>
            </div>
          </div>
          @endif

          @php
            // Check if any vendor order has delivered status
            $isDelivered = $order->vendorOrders->contains(function($vendorOrder) {
                return $vendorOrder->delivery_status === 'delivered';
            });
          @endphp
          
          @if($isDelivered)
          <div class="text-end mt-3 invoicebtn">
            <a href="{{ route('customer.order.invoice', $order->order_number) }}" class="btn btn-outline-danger">
              <i class="fas fa-file-invoice"></i> <b>Download Invoice</b>
            </a>
          </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</section>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Check if product is in cart on page load
document.addEventListener('DOMContentLoaded', function() {
    checkCartItems();
});

// Check which items are in cart and update button states
function checkCartItems() {
    const buttons = document.querySelectorAll('.add-again-btn');
    
    // Get cart data
    fetch('{{ route("cart.data") }}', {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        const cart = data.cart || {};
        const allItems = {};
        
        // Flatten cart structure
        Object.keys(cart).forEach(businessName => {
            if (cart[businessName] && typeof cart[businessName] === 'object') {
                Object.keys(cart[businessName]).forEach(key => {
                    allItems[key] = cart[businessName][key];
                });
            }
        });
        
        // Update button states
        buttons.forEach(button => {
            const key = button.getAttribute('data-key');
            if (allItems[key]) {
                updateButtonToRemove(button);
            } else {
                updateButtonToAdd(button);
            }
        });
    })
    .catch(error => {
        console.error('Error checking cart:', error);
    });
}

// Handle Add Again / Remove button click
function handleAddAgain(button) {
    const productId = button.getAttribute('data-product-id');
    const variantId = button.getAttribute('data-variant-id');
    const key = button.getAttribute('data-key');
    const isRemove = button.classList.contains('remove-mode');
    
    if (isRemove) {
        // Remove from cart
        removeFromCart(button, key);
    } else {
        // Add to cart
        addToCart(button, productId, variantId);
    }
}

// Add product to cart
function addToCart(button, productId, variantId) {
    button.disabled = true;
    const originalText = button.querySelector('.btn-text').textContent;
    button.querySelector('.btn-text').textContent = 'Adding...';
    
    fetch('{{ route("cart.add") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            product_id: productId,
            variant_id: variantId,
            quantity: 1
        })
    })
    .then(response => response.json())
    .then(data => {
        button.disabled = false;
        
        // Show success popup
        Swal.fire({
            title: 'Success!',
            text: 'Product is added in your cart',
            icon: 'success',
            confirmButtonColor: '#E94412',
            timer: 2000,
            showConfirmButton: true
        });
        
        // Update button to Remove
        updateButtonToRemove(button);
        
        // Update cart count if element exists
        if (document.querySelector('.cart-count')) {
            document.querySelector('.cart-count').textContent = data.count || 0;
        }
        
        // Refresh sidebar cart - use cart from response if available, otherwise fetch
        const refreshSidebarCart = (cartData) => {
            if (typeof loadSideCartItems === 'function') {
                if (cartData && cartData.cart) {
                    loadSideCartItems(cartData.cart);
                } else {
                    // If cart not in response, fetch it
                    fetch('{{ route("cart.data") }}', {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(fetchedCartData => {
                        if (fetchedCartData.cart) {
                            loadSideCartItems(fetchedCartData.cart);
                        }
                    })
                    .catch(error => {
                        console.error('Error refreshing sidebar cart:', error);
                    });
                }
            }
        };
        
        // Try to use cart from current response first
        refreshSidebarCart(data);
    })
    .catch(error => {
        console.error('Error:', error);
        button.disabled = false;
        button.querySelector('.btn-text').textContent = originalText;
        
        Swal.fire({
            title: 'Error!',
            text: 'Failed to add product to cart. Please try again.',
            icon: 'error',
            confirmButtonColor: '#E94412'
        });
    });
}

// Remove product from cart
function removeFromCart(button, key) {
    button.disabled = true;
    const originalText = button.querySelector('.btn-text').textContent;
    button.querySelector('.btn-text').textContent = 'Removing...';
    
    // Use decrement to remove (when quantity becomes 0, item is deleted)
    fetch('{{ route("cart.decrement") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            key: key,
            confirm: true
        })
    })
    .then(response => response.json())
    .then(data => {
        button.disabled = false;
        
        // Check if item still exists in cart
        const cart = data.cart || {};
        let itemExists = false;
        
        Object.keys(cart).forEach(businessName => {
            if (cart[businessName] && cart[businessName][key]) {
                itemExists = true;
            }
        });
        
        if (!itemExists) {
            // Item removed successfully
            Swal.fire({
                title: 'Removed!',
                text: 'Product has been removed from your cart',
                icon: 'success',
                confirmButtonColor: '#E94412',
                timer: 2000,
                showConfirmButton: true
            });
            
            // Update button to Add Again
            updateButtonToAdd(button);
            
            // Update cart count if element exists
            if (document.querySelector('.cart-count')) {
                document.querySelector('.cart-count').textContent = data.count || 0;
            }
            
            // Refresh sidebar cart
            if (typeof loadSideCartItems === 'function') {
                if (data.cart) {
                    loadSideCartItems(data.cart);
                } else {
                    // If cart not in response, fetch it separately
                    fetch('{{ route("cart.data") }}', {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(cartData => {
                        if (cartData.cart) {
                            loadSideCartItems(cartData.cart);
                        }
                    })
                    .catch(error => {
                        console.error('Error refreshing sidebar cart:', error);
                    });
                }
            }
        } else {
            // Item still exists (quantity > 1), just decremented
            button.querySelector('.btn-text').textContent = originalText;
            
            // Still refresh sidebar cart to update quantity
            if (typeof loadSideCartItems === 'function') {
                if (data.cart) {
                    loadSideCartItems(data.cart);
                } else {
                    fetch('{{ route("cart.data") }}', {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(cartData => {
                        if (cartData.cart) {
                            loadSideCartItems(cartData.cart);
                        }
                    })
                    .catch(error => {
                        console.error('Error refreshing sidebar cart:', error);
                    });
                }
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        button.disabled = false;
        button.querySelector('.btn-text').textContent = originalText;
        
        Swal.fire({
            title: 'Error!',
            text: 'Failed to remove product from cart. Please try again.',
            icon: 'error',
            confirmButtonColor: '#E94412'
        });
    });
}

// Update button to Remove state
function updateButtonToRemove(button) {
    button.classList.add('remove-mode');
    button.querySelector('.btn-text').textContent = 'Remove';
}

// Update button to Add Again state
function updateButtonToAdd(button) {
    button.classList.remove('remove-mode');
    button.querySelector('.btn-text').textContent = 'Add Again';
}
</script>

<style>
.add-again-btn {
    transition: all 0.3s ease-in-out;
    display: flex;
    align-items: center;
    justify-content: center;
}

.add-again-btn.remove-mode {
    background: #dc3545 !important;
    color: white !important;
}

.add-again-btn.remove-mode:hover {
    background: #c82333 !important;
}

.add-again-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}
</style>
@endsection
