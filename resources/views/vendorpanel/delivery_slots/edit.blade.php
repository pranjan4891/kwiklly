@extends('vendorpanel.include.main')

@section('content')
<div class="container">
    <h3>Edit Delivery Slot</h3>
    @include('vendorpanel.delivery_slots._form', ['slot' => $slot])
</div>
@endsection
