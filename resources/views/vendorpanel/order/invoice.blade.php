<!DOCTYPE html>
<html>
<head>
    <title>Invoice - {{ $vendorOrder->order->order_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0;
        }
        .info-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .info-section div {
            width: 48%;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        table th {
            background-color: #f2f2f2;
        }
        .total {
            text-align: right;
            font-weight: bold;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Invoice</h1>
        <p>Order Number: {{ $vendorOrder->order->order_number }}</p>
        <p>Invoice Date: {{ now()->format('Y-m-d') }}</p>
    </div>

    <div class="info-section">
        <div>
            <h3>Bill To:</h3>
            <p>{{ $vendorOrder->order->user->name ?? 'N/A' }}</p>
            <p>{{ $vendorOrder->order->user->email ?? 'N/A' }}</p>
            <p>{{ $vendorOrder->order->user->phone ?? 'N/A' }}</p>
        </div>
        <div>
            <h3>Vendor:</h3>
            <p>{{ auth('vendor')->user()->name ?? 'N/A' }}</p>
            <p>{{ auth('vendor')->user()->email ?? 'N/A' }}</p>
        </div>
    </div>

    <div class="info-section">
        <div>
            <h3>Order Details:</h3>
            <p>Order Date: {{ $vendorOrder->created_at->format('Y-m-d H:i') }}</p>
            <p>Delivery Date: {{ $vendorOrder->deliverySlot ? $vendorOrder->deliverySlot->slot_date : 'N/A' }}</p>
            <p>Delivery Time: {{ $vendorOrder->deliverySlot ? $vendorOrder->deliverySlot->start_time . ' - ' . $vendorOrder->deliverySlot->end_time : 'N/A' }}</p>
        </div>
        <div>
            <h3>Shipping Address:</h3>
            <p>{{ $vendorOrder->order->address->address_line_1 ?? '' }}</p>
            <p>{{ $vendorOrder->order->address->address_line_2 ?? '' }}</p>
            <p>{{ $vendorOrder->order->address->city ?? '' }}, {{ $vendorOrder->order->address->state ?? '' }} - {{ $vendorOrder->order->address->pincode ?? '' }}</p>
        </div>
    </div>

    <h3>Order Items:</h3>
    <table>
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
                    <td>{{ $item->quantity }}</td>
                    <td>₹{{ number_format($item->price, 2) }}</td>
                    <td>₹{{ number_format($item->price * $item->quantity, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center;">No items</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="total">Grand Total:</td>
                <td class="total">₹{{ number_format($vendorOrder->final_amount, 2) }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Thank you for your business!</p>
        <p>This is an electronically generated invoice.</p>
    </div>

</body>
</html>
