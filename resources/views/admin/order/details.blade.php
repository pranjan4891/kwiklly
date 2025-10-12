@extends('admin.includes.main')
@section('main')
<div class="wrapper container-fluid">
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}">Home</a></li>
        <li><a href="{{ route('admin.orderlist') }}">Orders</a></li>
        <li class="active">Order Details</li>
    </ul>
    <!-- END BREADCRUMB -->

    <!-- PAGE TITLE -->
    <div class="page-title">
        <h3 class="title">Order Details - {{ $vendorOrder->order->order_number ?? 'N/A' }}</h3>
    </div>
    <!-- END PAGE TITLE -->

    <!-- PAGE CONTENT WRAPPER -->
    <div class="page-content-wrap">
        <div class="row">
        <div class="col-md-12">
            <!-- Order Information -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Order Information</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Order Number:</strong> {{ $vendorOrder->order->order_number ?? 'N/A' }}</p>
                            <p><strong>Vendor Name:</strong> {{ $vendorOrder->vendor->name ?? 'N/A' }}</p>
                            <p><strong>Order Date:</strong> {{ $vendorOrder->created_at ? $vendorOrder->created_at->format('d/m/Y H:i') : 'N/A' }}</p>
                            <p><strong>Delivery Date:</strong> {{ $vendorOrder->deliverySlot ? $vendorOrder->deliverySlot->slot_date : 'N/A' }}</p>
                            <p><strong>Delivery Time:</strong> {{ $vendorOrder->deliverySlot ? $vendorOrder->deliverySlot->start_time . ' - ' . $vendorOrder->deliverySlot->end_time : 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Status:</strong>
                                <span class="label label-default">{{ $vendorOrder->delivery_status ? strtoupper(str_replace('_', ' ', $vendorOrder->delivery_status)) : 'N/A' }}</span>
                            </p>
                            <p><strong>Total Amount:</strong> ₹{{ number_format($vendorOrder->final_amount ?? 0, 2) }}</p>
                            <p><strong>Payment Status:</strong>
                                <span class="label label-success">{{ $vendorOrder->order->payment_status ?? 'PAID' }}</span>
                            </p>
                            <p><strong>Payment Method:</strong> {{ $vendorOrder->order->payment_method ?? 'Online' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Information -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Customer Information</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Name:</strong> {{ $vendorOrder->order->user->name ?? 'N/A' }}</p>
                            <p><strong>Email:</strong> {{ $vendorOrder->order->user->email ?? 'N/A' }}</p>
                            <p><strong>Phone:</strong> {{ $vendorOrder->order->user->phone ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Address:</strong></p>
                            <address>
                                {{ $vendorOrder->order->address->address_line_1 ?? '' }}<br>
                                {{ $vendorOrder->order->address->address_line_2 ?? '' }}<br>
                                {{ $vendorOrder->order->address->city ?? '' }}, {{ $vendorOrder->order->address->state ?? '' }} - {{ $vendorOrder->order->address->pincode ?? '' }}
                            </address>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Order Items</h3>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Variant</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($vendorOrder->orderItems as $item)
                                    <tr>
                                        <td>{{ $item->product->title ?? 'N/A' }}</td>
                                        <td>{{ $item->variant ? implode(' | ', array_filter([$item->variant->size ?? '', $item->variant->color ?? '', $item->variant->material ?? ''])) : 'N/A' }}</td>
                                        <td>{{ $item->quantity ?? 0 }}</td>
                                        <td>₹{{ number_format($item->price ?? 0, 2) }}</td>
                                        <td>₹{{ number_format(($item->price ?? 0) * ($item->quantity ?? 0), 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No items found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4" class="text-right">Grand Total:</th>
                                    <th>₹{{ number_format($vendorOrder->final_amount ?? 0, 2) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Update Status -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Update Order Status</h3>
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route('admin.order.update.status', $vendorOrder->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="pending" {{ $vendorOrder->delivery_status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="packed" {{ $vendorOrder->delivery_status == 'packed' ? 'selected' : '' }}>Packed</option>
                                <option value="shipped" {{ $vendorOrder->delivery_status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="delivered" {{ $vendorOrder->delivery_status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="cancelled" {{ $vendorOrder->delivery_status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Status</button>
                    </form>
                </div>
            </div>

            <!-- Back Button -->
            <div class="text-center">
                <a href="{{ route('admin.orderlist') }}" class="btn btn-default">Back to Orders</a>
            </div>
        </div>
        </div>
    </div>
</div>

@endsection
