@extends('web.include.main')

@section('content')

<section class="payment-result-container success-page">
    <div class="container">
        <div class="payment-result-box success-box">
            <div class="success-icon">
                <i class="fas fa-check"></i>
            </div>
            <h1 class="success-title">Payment Successful!</h1>
            <p class="success-message">{{ $message ?? 'Your payment has been processed successfully. Your order will be delivered soon!' }}</p>
            
            @if($order)
            <div class="order-info-box">
                <div class="order-info-row">
                    <span class="order-info-label">Order ID:</span>
                    <span class="order-info-value success-value">#{{ $order->order_number }}</span>
                </div>
                <div class="order-info-row">
                    <span class="order-info-label">Total Amount:</span>
                    <span class="order-info-value success-value">₹{{ number_format($order->final_amount, 2) }}</span>
                </div>
                <div class="order-info-row">
                    <span class="order-info-label">Payment Status:</span>
                    <span class="order-info-value success-value">✓ Completed</span>
                </div>
                @if($order->address)
                <div class="order-info-row">
                    <span class="order-info-label">Delivery Address:</span>
                    <span class="order-info-value address-value">
                        {{ $order->address->flat }}, {{ $order->address->area }}, {{ $order->address->pincode }}
                    </span>
                </div>
                @endif
            </div>
            @endif

            <div class="action-buttons">
                <a href="{{ route('home') }}" class="btn-continue">
                    <i class="fas fa-shopping-bag"></i>Continue Shopping
                </a>
                <a href="{{ route('customer.dashboard') }}" class="btn-orders">
                    <i class="fas fa-list-alt"></i>View My Orders
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
