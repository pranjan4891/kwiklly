@extends('web.include.main')

@section('content')

<section class="payment-result-container failure-page">
    <div class="container">
        <div class="payment-result-box failure-box">
            <div class="failure-icon">
                <i class="fas fa-times"></i>
            </div>
            <h1 class="failure-title">Payment Failed</h1>
            <div class="failure-message">
                <i class="fas fa-exclamation-triangle"></i>
                {{ $error ?? 'Your payment could not be processed. Please try again.' }}
            </div>
            
            @if($order)
            <div class="order-info-box">
                <div class="order-info-row">
                    <span class="order-info-label">Order ID:</span>
                    <span class="order-info-value failure-value">#{{ $order->order_number }}</span>
                </div>
                <div class="order-info-row">
                    <span class="order-info-label">Amount:</span>
                    <span class="order-info-value failure-value">₹{{ number_format($order->final_amount, 2) }}</span>
                </div>
                <div class="order-info-row">
                    <span class="order-info-label">Status:</span>
                    <span class="order-info-value failure-value">✗ Failed</span>
                </div>
            </div>
            @endif

            <div class="help-text">
                <strong><i class="fas fa-info-circle me-2"></i>What to do next?</strong>
                <ul>
                    <li>Check your payment method and try again</li>
                    <li>Ensure you have sufficient balance in your account</li>
                    <li>Contact your bank if the issue persists</li>
                    <li>You can try a different payment method</li>
                </ul>
            </div>

            <div class="action-buttons">
                @if($order && $order->status === 'pending')
                <a href="{{ route('payment.details', $order->id) }}" class="btn-retry">
                    <i class="fas fa-redo"></i>Try Payment Again
                </a>
                @else
                <a href="{{ route('cart.view') }}" class="btn-retry">
                    <i class="fas fa-shopping-cart"></i>Go to Cart
                </a>
                @endif
                <a href="{{ route('home') }}" class="btn-home">
                    <i class="fas fa-home"></i>Back to Home
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
