@extends('admin.includes.main')

@section('main')

<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">

    <div class="row">
        <div class="col-md-12">

            <!-- START CART TABLE PANEL -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Cart Items</h3>
                </div>

                <div class="panel-body">
                    @if($cartItems->isEmpty())
                        <div class="alert alert-info text-center">No items in the cart.</div>
                    @else
                        <form method="POST" action="{{ route('admin.cart.update') }}">
                            @csrf
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>User</th>
                                            <th>Product</th>
                                            <th>Image</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th>Subtotal</th>
                                            <th>Created At</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $total = 0; @endphp
                                        @foreach($cartItems as $index => $item)
                                            @php
                                                $subtotal = $item->price * $item->quantity;
                                                $total += $subtotal;
                                            @endphp
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $item->user->name ?? 'N/A' }}</td>
                                                <td>{{ $item->product->name ?? 'Product Name' }}</td>
                                                <td>
                                                    <img src="{{ asset('public/' . $item->product->featureImage->feature_image ?? 'noimage.png') }}" alt="Product Image" style="width: 80px;">
                                                </td>
                                                <td>₹{{ number_format($item->price, 2) }}</td>
                                                <td>
                                                    <input type="number" name="quantities[{{ $item->id }}]" value="{{ $item->quantity }}" min="1" class="form-control" style="width: 70px;">
                                                </td>
                                                <td>₹{{ number_format($subtotal, 2) }}</td>
                                                <td>{{$item->created_at}}</td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="{{ route('admin.cart.remove', $item->id) }}" class="btn btn-danger" title="Remove" onclick="return confirm('Remove this item?')">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                        <tr class="success">
                                            <td colspan="7" class="text-right"><strong>Total:</strong></td>
                                            <td colspan="2"><strong>₹{{ number_format($total, 2) }}</strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="text-right mt-3">
                                <button type="submit" class="btn btn-primary">Update Cart</button>
                                <a href="{{ route('admin.products') }}" class="btn btn-default">Back to Products</a>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
            <!-- END CART TABLE PANEL -->

        </div>
    </div>

</div>
<!-- END PAGE CONTENT WRAPPER -->

@endsection
