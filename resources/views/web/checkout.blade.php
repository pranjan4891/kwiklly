@extends('web.include.main')

@section('content')
<div class="container py-5">
    <h2>Checkout</h2>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('checkout.place') }}">
        @csrf

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Variant</th>
                    <th>Qty</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cartItems as $item)
                <tr>
                    <td>{{ $item->product->title }}</td>
                    <td>
                        @php
                            $attrs = json_decode($item->variant->attributes, true);
                        @endphp
                        {{ $attrs['Volume'] ?? 'N/A' }}
                    </td>
                    <td>{{ $item->quantity }}</td>
                    <td>₹{{ $item->price * $item->quantity }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <h5>Total: ₹{{ $total }}</h5>

        <button type="submit" class="btn btn-primary">Place Order</button>
    </form>
</div>
<!-- gugss -->
@endsection
