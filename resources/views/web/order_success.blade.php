@extends('web.include.main')

@section('content')
<div class="container d-flex align-items-center justify-content-center" style="min-height: 70vh;">
    <div class="text-center p-5 shadow rounded-4 bg-white" style="max-width: 500px; width: 100%;">
        <div class="mb-4">
            <img src="{{ asset('public/assets/website/images/checkmark.gif') }}" alt="Success" style="width: 80px;">
        </div>
        <h2 class="text-success fw-bold mb-3">Thank You!</h2>
        <p class="mb-4 text-muted fs-5">Your order has been placed successfully.</p>

        <div class="d-flex justify-content-center gap-3">
            <a href="{{ route('home') }}" class="btn btn-outline-primary px-4">
                <i class="bi bi-shop me-2"></i>Continue Shopping
            </a>
            <a href="{{ route('customer.dashboard') }}" class="btn btn-primary px-4">
                <i class="bi bi-card-list me-2"></i>View Orders
            </a>
        </div>
    </div>
</div>
@endsection
