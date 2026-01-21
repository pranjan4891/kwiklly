@extends('vendorpanel.include.main')
@section('content')

<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="{{ route('vendor.dashboard') }}">Home</a></li>
    <li><a href="{{ route('vendor.orderlist') }}">Orders</a></li>
    <li class="active">Order Details</li>
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE TITLE -->
<div class="page-title">
    <h3 class="title">Order Details - {{ $vendorOrder->order->order_number ?? 'N/A' }}</h3>
</div>
<!-- END PAGE TITLE -->

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
                        <p><strong>Order Date:</strong> {{ $vendorOrder->created_at ? $vendorOrder->created_at->format('d/m/Y H:i') : 'N/A' }}</p>
                        @php
                            $deliverySlot = $vendorOrder->deliverySlot;
                            $deliveryDate = $deliverySlot && $deliverySlot->date ? $deliverySlot->date->format('d/m/Y') : 'N/A';
                            $deliveryTime = $deliverySlot && $deliverySlot->start_time && $deliverySlot->end_time 
                                ? date('h:i A', strtotime($deliverySlot->start_time)) . ' - ' . date('h:i A', strtotime($deliverySlot->end_time))
                                : 'N/A';
                        @endphp
                        <p><strong>Delivery Date:</strong> {{ $deliveryDate }}</p>
                        <p><strong>Delivery Time:</strong> {{ $deliveryTime }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Status:</strong>
                            <span class="label label-default">{{ $vendorOrder->delivery_status ? strtoupper(str_replace('_', ' ', $vendorOrder->delivery_status)) : 'N/A' }}</span>
                        </p>
                        <p><strong>Total Amount:</strong> ₹{{ number_format($vendorOrder->final_amount ?? 0, 2) }}</p>
                        @php
                            $payment = $vendorOrder->order->payments->first();
                            $paymentStatus = $payment ? ucfirst($payment->payment_status) : 'Pending';
                            $paymentMethod = $payment ? ucfirst(str_replace('_', ' ', $payment->payment_method)) : 'Online';
                            $paymentStatusClass = $payment && $payment->payment_status == 'success' ? 'label-success' : 
                                                ($payment && $payment->payment_status == 'pending' ? 'label-warning' : 'label-danger');
                        @endphp
                        <p><strong>Payment Status:</strong>
                            <span class="label {{ $paymentStatusClass }}">{{ $paymentStatus }}</span>
                        </p>
                        <p><strong>Payment Method:</strong> {{ $paymentMethod }}</p>
                        @if($payment && $payment->transaction_id)
                            <p><strong>Transaction ID:</strong> {{ $payment->transaction_id }}</p>
                        @endif
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
                        @php
                            $address = $vendorOrder->order->address; // CustomerAddress
                            $customerName = $vendorOrder->order->user->name ?? ($address->name ?? 'N/A');
                            $customerEmail = $vendorOrder->order->user->email ?? 'N/A';
                            $customerPhone = $address->phone ?? ($vendorOrder->order->user->phone ?? 'N/A');
                        @endphp
                        <p><strong>Name:</strong> {{ $customerName }}</p>
                        <p><strong>Email:</strong> {{ $customerEmail }}</p>
                        <p><strong>Phone:</strong> {{ $customerPhone }}</p>
                        @if($address && $address->alt_phone)
                            <p><strong>Alternate Phone:</strong> {{ $address->alt_phone }}</p>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <p><strong>Address:</strong></p>
                        <address>
                            @if($address)
                                @if($address->full_address)
                                    {{ $address->full_address }}<br>
                                @else
                                    @if($address->name)
                                        <strong>{{ $address->name }}</strong><br>
                                    @endif
                                    @if($address->flat)
                                        {{ $address->flat }}<br>
                                    @endif
                                    @if($address->area)
                                        {{ $address->area }}<br>
                                    @endif
                                    @if($address->landmark)
                                        {{ $address->landmark }}<br>
                                    @endif
                                @endif
                                @if($address->pincode)
                                    Pincode: {{ $address->pincode }}
                                @endif
                            @else
                                Address not available
                            @endif
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
                                    <td>
                                        @if($item->variant)
                                            @php
                                                $variantDisplay = '';
                                                if ($item->variant->variant_name) {
                                                    $variantDisplay = $item->variant->variant_name;
                                                } elseif ($item->variant->attributes && is_array($item->variant->attributes) && !empty($item->variant->attributes)) {
                                                    $attrParts = [];
                                                    foreach ($item->variant->attributes as $key => $value) {
                                                        if (!empty($value)) {
                                                            $attrParts[] = $key . ': ' . $value;
                                                        }
                                                    }
                                                    $variantDisplay = !empty($attrParts) ? implode(' | ', $attrParts) : 'N/A';
                                                } else {
                                                    $variantDisplay = 'N/A';
                                                }
                                            @endphp
                                            {{ $variantDisplay }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
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
                <form method="POST" action="{{ route('vendor.order.update.status', $vendorOrder->id) }}">
                    @csrf
                    @method('PUT')
                    @php
                        $currentStatus = $vendorOrder->delivery_status ?? 'pending';
                        // Define status hierarchy
                        $statusHierarchy = [
                            'pending' => 1,
                            'packed' => 2,
                            'shipped' => 3,
                            'delivered' => 4,
                            'cancelled' => 5
                        ];
                        $currentLevel = $statusHierarchy[$currentStatus] ?? 1;
                        
                        // Determine which statuses to disable
                        $isDisabled = function($status) use ($currentStatus, $statusHierarchy, $currentLevel) {
                            // Final statuses cannot be changed
                            if (in_array($currentStatus, ['delivered', 'cancelled'])) {
                                return true;
                            }
                            // Can't go back to previous statuses
                            $statusLevel = $statusHierarchy[$status] ?? 0;
                            return $statusLevel < $currentLevel;
                        };
                        $isFinalStatus = in_array($currentStatus, ['delivered', 'cancelled']);
                    @endphp
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control" {{ $isFinalStatus ? 'disabled' : '' }}>
                            <option value="pending" {{ $currentStatus == 'pending' ? 'selected' : '' }} {{ $isDisabled('pending') ? 'disabled' : '' }}>Pending</option>
                            <option value="packed" {{ $currentStatus == 'packed' ? 'selected' : '' }} {{ $isDisabled('packed') ? 'disabled' : '' }}>Packed</option>
                            <option value="shipped" {{ $currentStatus == 'shipped' ? 'selected' : '' }} {{ $isDisabled('shipped') ? 'disabled' : '' }}>Shipped</option>
                            <option value="delivered" {{ $currentStatus == 'delivered' ? 'selected' : '' }} {{ $isDisabled('delivered') ? 'disabled' : '' }}>Delivered</option>
                            <option value="cancelled" {{ $currentStatus == 'cancelled' ? 'selected' : '' }} {{ $isDisabled('cancelled') ? 'disabled' : '' }}>Cancelled</option>
                        </select>
                        @if($isFinalStatus)
                            <small class="text-muted">This order status cannot be changed.</small>
                        @else
                            <small class="text-muted">You can only move forward in status. Previous statuses are disabled.</small>
                        @endif
                    </div>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </form>
            </div>
        </div>

        <!-- Back Button -->
        <div class="text-center">
            <a href="{{ route('vendor.orderlist') }}" class="btn btn-default">Back to Orders</a>
        </div>
    </div>
</div>

@endsection
