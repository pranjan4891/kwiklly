@extends('web.include.main')
<style>
.invoicebtn .btn{
  border-color:#E94412;
  background-color:#F5DED8;
}
.delivery-card {
      display: flex;
      align-items: center;
      justify-content: space-between;
      background: #f9f9f9;
      border-radius: 12px;
      padding: 12px 16px;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
      /* max-width: 600px; */
      /* margin: 20px auto; */
    }

    .delivery-info {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .profile-img {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      object-fit: cover;
    }

    .details {
      line-height: 1.2;
    }

    .details .name {
      font-weight: 600;
      font-size: 16px;
    }

    .details .company {
      font-size: 13px;
      color: #777;
    }

    .company span {
      font-weight: bold;
      color: #0073e6;
    }

    .call-icon {
      width: 36px;
      height: 36px;
      border: 2px solid #e65100;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #e65100;
      font-size: 16px;
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

    /* Responsive */
    @media (max-width: 480px) {
      .delivery-card {
        /* flex-direction: column; */
        align-items: flex-start;
        gap: 12px;
      }

      .call-icon {
        align-self: flex-end;
      }
    }
</style>

@section('content')

<section class="extrapadding">
<div class="container py-4 bg-light">
  <div class="row g-3">
    <div class="">
      <div class="deorder-summary-box p-3 rounded shadow-sm mb-3 bg-white">
        <div class="d-flex justify-content-between">
          <div>
            <strong>Order Summary</strong><br>
            <div class="newbackground d-flex">
              <div><i class="fa fa-store"></i></div>
              <div>
                <small>{{ $order->items->first()->variant->product->vendor->business_name ?? 'Store' }}</small><br>
                <small>{{ $order->items->first()->variant->product->vendor->business_address ?? '' }}</small><br>
              </div>
            </div>
            <small>GST: {{ $order->items->first()->variant->product->vendor->gstin ?? 'XXXX XXXX XXXX XXXX' }}</small>
          </div>
          <div class="text-end">
            <strong>Delivered on: {{ $order->updated_at->format('D d M Y, h:i A') }}</strong><br>
            <small>Order ID: #{{ $order->order_number }}</small><br>
            <small>Ordered on: {{ $order->created_at->format('D d M Y, h:i A') }}</small>
          </div>
        </div>

        <hr>
        <div class="mb-3">
          <strong>{{ $order->items->count() }} items in this order</strong>

          @foreach ($order->items as $item)
          <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="d-flex align-items-center">
              <img src="{{ asset('public/' . $item->variant->product->featureImage->feature_image ?? 'public/assets/website/images/placeholder.png') }}" width="100" alt="Item">
              <div class="ms-3">
                <strong>{{ $item->variant->product->title ?? 'Product' }} - {{ $item->variant->name }}</strong><br>
                ₹{{ $item->price * $item->quantity }} <small>(₹{{ $item->price }} X {{ $item->quantity }})</small>
              </div>
            </div>
          </div>
          @endforeach
        </div>

        <!-- Delivery Person Details -->
        <div class="delivery-card">
          <div class="delivery-info">
            <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Delivery Person" class="profile-img" />
            <div class="details">
              <div class="name">{{ auth()->user()->name }}</div>
              <div class="company">Delivered by <span style="color:green;">BLUE</span><span style="color:#0047ab;"> DART</span></div>
            </div>
          </div>
          <div class="call-icon"><i class="fas fa-phone-alt"></i></div>
        </div>

        <!-- Bill Summary -->
        <div class="deorder-bill-summary mt-4">
          <h6 class="fw-bold mb-2">Bill Summary</h6>
          <div class="d-flex justify-content-between">
            <span><i class="fa fa-shopping-cart me-1"></i> Item charge</span>
            <span>₹{{ $order->total_price }}</span>
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
          <div class="d-flex justify-content-between align-items-center fw-bold">
            <span>Total Charges</span>
            <div class="text-end">
              <span>₹{{ $order->total_price }}</span>
            </div>
          </div>
        </div>

        <!-- Cancel Button -->
        <div class="text-end mt-3">
          <form action="{{ route('order.cancel.process', $order->order_number) }}" method="POST">
            @csrf
            <button type="submit" class="btn text-danger"><h5><b>Cancel Order</b></h5></button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
</section>

@endsection