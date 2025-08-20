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
    <div class="row g-3">
      <div class="">
        <div class="deorder-summary-box p-3 rounded shadow-sm mb-3 bg-white">
          <div class="d-flex justify-content-between">
            <div>
              <strong>Order Summary</strong><br>
              @php
                $vendor = $order->items->first()->variant->product->vendor;
              @endphp
              <div class="newbackground d-flex">
                <div><i class="fa fa-store"></i></div>
                <div>
                  <small>{{ $vendor->business_name }}</small><br>
                  <small>{{ $vendor->business_address ?? 'No address available' }}</small><br>
                </div>
              </div>
              <small>GST: {{ $vendor->gstin ?? '000000000000000' }}</small>
            </div>
            <div class="text-end">
              <strong>Delivered on: {{ $order->created_at->addDays(2)->format('D j M Y, h:i A') }}</strong><br>
              <small>Order ID: #{{ $order->order_number }}</small><br>
              <small>Ordered on: {{ $order->created_at->format('D j M Y, h:i A') }}</small>
            </div>
          </div>

          <hr>
          <div class="mb-3">
            <strong>{{ $order->items->count() }} items in this order</strong>

            @foreach ($order->items as $item)
              @php
                $product = $item->variant->product;
                $key = $product->id . '_' . $item->variant_id;
              @endphp

              <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="d-flex align-items-center">
                  <img src="{{ $product->feature_image_id ? asset('public/' . $product->featureImage->feature_image) : asset('public/assets/website/images/default.png') }}" width="100" alt="{{ $product->title }}">
                  <div class="ms-3">
                    <strong>{{ $product->title }}</strong><br>
                    ₹{{ $item->price * $item->quantity }} <small>(₹{{ $item->price }} X {{ $item->quantity }})</small>
                  </div>
                </div>
                <div>
                  <div class="qty-box" id="qtyBox-{{ $key }}">
                    <button class="add-btn py-2" onclick="convertToQty(this)"
                      data-product-id="{{ $product->id }}"
                      data-variant-id="{{ $item->variant_id }}">
                      Add Again
                      <img src="{{ asset('public/assets/website/images/cart.svg') }}" alt="" class="ms-2">
                    </button>
                  </div>
                  <div class="text-center text-muted mt-1" style="font-size: 12px;">
                    Order for ₹{{ $item->price }} per unit
                  </div>
                </div>
              </div>
            @endforeach
          </div>

          <hr>
          <div class="deorder-bill-summary">
            <h6 class="fw-bold mb-2">Bill Summary</h6>
            <div class="d-flex justify-content-between">
              <span><i class="fa fa-shopping-cart me-1"></i> Item charge</span>
              <span>₹{{ $order->items->sum(fn($i) => $i->price * $i->quantity) }}</span>
            </div>
            <div class="d-flex justify-content-between">
              <span><i class="fa fa-truck me-1"></i> Delivery Charges</span>
              <span>₹0</span>
            </div>
            <div class="d-flex justify-content-between">
              <span><i class="fa fa-tag me-1"></i> Coupon Discount</span>
              <span>- ₹0</span>
            </div>
            <div class="d-flex justify-content-between">
              <span><i class="fa fa-wallet me-1"></i> Wallet Discount</span>
              <span>- ₹0</span>
            </div>
            <hr>
            @php
              $total = $order->items->sum(fn($i) => $i->price * $i->quantity);
              $grandTotal = $total + 0 - 0 - 0;
            @endphp
            <div class="d-flex justify-content-between align-items-center fw-bold">
              <span>Total Charges</span>
              <div class="text-end">
                <div>
                  <span class="text-muted text-decoration-line-through">₹{{ $total + 0 }}</span>
                  <span class="ms-1">₹{{ $grandTotal }}</span>
                </div>
                <span class="badge bg-success bg-opacity-10 text-success mt-1 rounded-pill px-2 py-1" style="font-weight: 500;">
                  Saved ₹{{ ($total + 0) - $grandTotal }}
                </span>
              </div>
            </div>
          </div>

          <div class="text-end mt-3 invoicebtn">
            <button class="btn btn-outline-danger">
              <i class="fas fa-file-invoice"></i> <b>Download Invoice</b>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
