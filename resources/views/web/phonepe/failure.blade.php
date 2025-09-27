
@extends('web.include.main')
@section('content')
    <div class="text-center mt-5">
        <h2 class="text-danger">❌ Payment Failed</h2>
        <p>Order ID: {{ request('merchantOrderId') }}</p>
    </div>
@endsection
