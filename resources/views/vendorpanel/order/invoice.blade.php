<!DOCTYPE html>
<html>
<head>
    <title>Tax Invoice - {{ $vendorOrder->order->order_number }}</title>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        @page { 
            size: A4; 
            margin: 15mm 20mm 15mm 20mm;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.3;
            padding: 5px;
        }
        
        .invoice-container {
            max-width: 100%;
            padding: 5px;
        }
        
        .header-section {
            margin-bottom: 8px;
            padding: 5px 0;
        }
        
        .invoice-title {
            text-align: center;
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 8px;
            color: #000;
            padding: 5px 0;
        }
        
        .top-right {
            position: absolute;
            top: 15mm;
            right: 20mm;
            text-align: right;
        }
        
        .qr-code {
            width: 80px;
            height: 80px;
            border: 1px solid #ddd;
            margin-bottom: 5px;
        }
        
        .invoice-number {
            font-size: 10px;
            color: #666;
        }
        
        .seller-section {
            margin-bottom: 8px;
            padding: 5px 0;
        }
        
        .section-title {
            font-weight: bold;
            font-size: 12px;
            margin-bottom: 3px;
            color: #000;
        }
        
        .seller-info {
            font-size: 10px;
            line-height: 1.4;
        }
        
        .order-details-section {
            display: table;
            width: 100%;
            margin-bottom: 8px;
            padding: 5px 0;
        }
        
        .order-details-left, .order-details-middle, .order-details-right {
            display: table-cell;
            vertical-align: top;
            padding: 6px;
            width: 33.33%;
        }
        
        .order-details-left {
            border-right: 1px solid #ddd;
        }
        
        .order-details-middle {
            border-right: 1px solid #ddd;
        }
        
        .detail-label {
            font-weight: bold;
            font-size: 10px;
            margin-bottom: 3px;
        }
        
        .detail-value {
            font-size: 10px;
            color: #333;
            margin-bottom: 4px;
        }
        
        .warranty-note {
            font-size: 8px;
            color: #666;
            font-style: italic;
        }
        
        .items-section {
            margin-bottom: 8px;
            padding: 5px 0;
        }
        
        .total-items {
            font-size: 11px;
            font-weight: bold;
            margin-bottom: 5px;
            padding: 3px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
        }
        
        table th {
            background-color: #f5f5f5;
            padding: 6px 5px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #ddd;
            font-size: 9px;
        }
        
        table td {
            padding: 6px 5px;
            border: 1px solid #ddd;
            vertical-align: top;
        }

        .product-col {
            width: 20%;
        }
        
        .title-col {
            width: 25%;
        }
        
        .qty-col {
            width: 8%;
            text-align: center;
        }
        
        .amount-col {
            width: 12%;
            text-align: right;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .strikethrough {
            text-decoration: line-through;
            color: #999;
        }
        
        .product-category {
            font-weight: bold;
            font-size: 10px;
        }
        
        .product-fsn {
            font-size: 9px;
            color: #666;
        }
        
        .product-hsn {
            font-size: 9px;
            color: #666;
        }
        
        .product-title {
            font-size: 9px;
            margin: 2px 0;
        }
        
        .product-warranty {
            font-size: 8px;
            color: #666;
        }
        
        .product-tax {
            font-size: 8px;
            color: #666;
        }
        
        .grand-total-section {
            margin-top: 10px;
            margin-bottom: 10px;
            padding: 8px 0;
        }
        
        .grand-total-row {
            display: table;
            width: 100%;
            margin-bottom: 8px;
        }
        
        .grand-total-left {
            display: table-cell;
            width: 70%;
            vertical-align: middle;
        }
        
        .grand-total-right {
            display: table-cell;
            width: 30%;
            text-align: right;
            vertical-align: middle;
        }
        
        .grand-total-label {
            font-weight: bold;
            font-size: 12px;
            display: inline-block;
            margin-right: 10px;
        }
        
        .grand-total-amount {
            font-size: 14px;
            font-weight: bold;
            color: #000;
            display: inline-block;
        }
        
        .grand-total-row-content {
            text-align: right;
        }
        
        .company-name {
            font-size: 10px;
            color: #333;
            margin-top: 3px;
        }
        
        .signature-line {
            border-top: 1px solid #000;
            width: 150px;
            margin-top: 15px;
            margin-left: auto;
        }
        
        .authorized-signatory {
            font-size: 8px;
            color: #666;
            margin-top: 3px;
            text-align: right;
        }
        
        .footer-section {
            margin-top: 10px;
            padding-top: 8px;
            padding-bottom: 5px;
            border-top: 1px solid #ddd;
        }
        
        .footer-logo {
            text-align: right;
            margin-bottom: 5px;
        }
        
        .logo-img {
            height: 25px;
        }
        
        .thank-you {
            text-align: right;
            font-size: 12px;
            font-weight: bold;
            color: #2874f0;
            margin-bottom: 3px;
        }

        .thank-you-sub {
            text-align: right;
            font-size: 9px;
            color: #666;
            margin-bottom: 8px;
        }
        
        .footer-policies {
            font-size: 8px;
            line-height: 1.4;
            color: #333;
        }
        
        .footer-policies strong {
            font-weight: bold;
        }
        
        .footer-contact {
            font-size: 8px;
            margin-top: 5px;
            color: #333;
        }
        
        .footer-bottom {
            display: table;
            width: 100%;
            margin-top: 8px;
            font-size: 8px;
            color: #666;
        }
        
        .footer-bottom-left {
            display: table-cell;
            text-align: left;
        }
        
        .footer-bottom-right {
            display: table-cell;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Header -->
        <div class="header-section">
            <div class="top-right">
                {{-- <div class="qr-code" style="background: #f0f0f0; display: flex; align-items: center; justify-content: center; font-size: 8px;">
                    QR Code
                </div> --}}
                <div class="invoice-number">Invoice Number # {{ strtoupper($vendorOrder->order->order_number) }}</div>
            </div>
            
            <div class="invoice-title">Tax Invoice</div>
        </div>

@php
    $vendor = auth('vendor')->user();
    $order = $vendorOrder->order;
            $invoiceDate = now()->format('d-m-Y');
            $orderDate = $order->created_at->format('d-m-Y');
@endphp

        <!-- Seller Information -->
        <div class="seller-section">
            <div class="section-title">Sold By:</div>
            <div class="seller-info">
                <strong>{{ $vendor->business_name ?? 'KWIKLLY' }},</strong><br>
                {{ $vendor->business_address ?? 'Address not available' }},<br>
                @if($vendor && $vendor->city)
                    {{ $vendor->city }}, 
                @endif
                @if($vendor && $vendor->state)
                    {{ $vendor->state }}, 
                @endif
                India - {{ $order->address->pincode ?? 'N/A' }},<br>
                @if($vendor && $vendor->state_code)
                    IN-{{ $vendor->state_code }}, 
                @endif
                <strong>GSTIN:</strong> {{ $vendor->gstin ?? 'N/A' }}
            </div>
        </div>
        
        <!-- Order Details Section -->
        <div class="order-details-section">
            <div class="order-details-left">
                <div class="detail-label">Order Details</div>
                <div class="detail-value">
                    <strong>Order ID:</strong> {{ $order->order_number }}<br>
                    <strong>Order Date:</strong> {{ $orderDate }}<br>
                    <strong>Invoice Date:</strong> {{ $invoiceDate }}<br>
                    @if($vendor && $vendor->gstin)
                        <strong>PAN:</strong> {{ substr($vendor->gstin, 2, 10) }}
                    @endif
                </div>
            </div>
            
            <div class="order-details-middle">
                <div class="detail-label">Bill To</div>
                <div class="detail-value">
                    <strong>{{ $order->user->name ?? 'N/A' }}</strong><br>
                    @if($order->address)
                        {{ $order->address->flat ?? '' }}, {{ $order->address->area ?? '' }},<br>
                        @if($order->address->landmark)
                            {{ $order->address->landmark }},<br>
                        @endif
                        {{ $order->address->pincode ?? 'N/A' }}<br>
                        Phone: {{ $order->address->phone ?? 'xxxxxxxxxx' }}
                    @else
                        Address not available<br>
                        Phone: xxxxxxxxxx
                    @endif
                </div>
            </div>
            
            <div class="order-details-right">
                <div class="detail-label">Ship To</div>
                <div class="detail-value">
            <strong>{{ $order->user->name ?? 'N/A' }}</strong><br>
            @if($order->address)
                        {{ $order->address->flat ?? '' }}, {{ $order->address->area ?? '' }},<br>
                @if($order->address->landmark)
                            {{ $order->address->landmark }},<br>
                @endif
                {{ $order->address->pincode ?? 'N/A' }}<br>
                        Phone: {{ $order->address->phone ?? 'xxxxxxxxxx' }}
            @else
                        Address not available<br>
                        Phone: xxxxxxxxxx
            @endif
                </div>
            </div>
        </div>
        
        <!-- Items Section -->
        <div class="items-section">
            <div class="total-items">Total items: {{ $vendorOrder->orderItems->count() }}</div>
            
            <table>
                <thead>
                    <tr>
                        <th class="product-col">Product</th>
                        <th class="title-col">Title</th>
                        <th class="qty-col">Qty</th>
                        <th class="amount-col">Gross Amount Rs.</th>
                        <th class="amount-col">Discounts /Coupons Rs.</th>
                        <th class="amount-col">Taxable Value Rs.</th>
                        <th class="amount-col">SGST /UTGST Rs.</th>
                        <th class="amount-col">CGST Rs.</th>
                        <th class="amount-col">Total Rs.</th>
    </tr>
                </thead>
                <tbody>
                    @php
                        $totalQty = 0;
                        $totalGross = 0;
                        $totalDiscount = 0;
                        $totalTaxable = 0;
                        $totalSGST = 0;
                        $totalCGST = 0;
                        $grandTotal = 0;
                    @endphp
                    
                    @foreach($vendorOrder->orderItems as $item)
                        @php
                            $product = $item->product;
                            $variant = $item->variant;
                            $qty = $item->quantity;
                            $unitPrice = $item->price; // Price already includes GST
                            $originalPrice = $variant->variant_actual_price ?? $unitPrice;
                            $grossAmount = $originalPrice * $qty;
                            $discount = ($originalPrice - $unitPrice) * $qty;
                            $taxableValue = $unitPrice * $qty; // Since price includes GST, taxable value is same
                            $gstRate = 9; // Default 9% GST (for display only)
                            $sgst = 0.00; // GST already included in price
                            $cgst = 0.00; // GST already included in price
                            $itemTotal = $unitPrice * $qty; // Total is just price * qty since GST is included
                            
                            $totalQty += $qty;
                            $totalGross += $grossAmount;
                            $totalDiscount += $discount;
                            $totalTaxable += $taxableValue;
                            $totalSGST += $sgst;
                            $totalCGST += $cgst;
                            $grandTotal += $itemTotal;
                        @endphp
                        
                        <tr>
                            <td>
                                <div class="product-category">
                                    {{ $product->category->name ?? '' }}{{ $product->subcategory ? ' > ' . $product->subcategory->sub_cat_name : '' }}
                                </div>
                            </td>
                            <td>
                                <div class="product-title">
                                    {{ $product->title }}
                                    @if($variant)
                                        ({{ $variant->variant_name ?? 'Standard' }})
                                    @endif
                                    <br>
                                    <span style="font-size: 8px; color: #2874f0; font-weight: bold;">Include GST</span>
                                </div>
                            </td>
                            <td class="text-center">{{ $qty }}</td>
                            <td class="text-right">{{ number_format($grossAmount, 2) }}</td>
                            <td class="text-right">
                                @if($discount > 0)
                                    -{{ number_format($discount, 2) }}
            @else
                                    0.00
            @endif
        </td>
                            <td class="text-right">{{ number_format($taxableValue, 2) }}</td>
                            <td class="text-right">{{ number_format($sgst, 2) }}</td>
                            <td class="text-right">{{ number_format($cgst, 2) }}</td>
                            <td class="text-right">{{ number_format($itemTotal, 2) }}</td>
    </tr>
    @endforeach

                    @if($vendorOrder->delivery_charge && $vendorOrder->delivery_charge > 0)
                        @php
                            $deliveryCharge = $vendorOrder->delivery_charge;
                            $grandTotal += $deliveryCharge;
                        @endphp
                        <tr>
                            <td colspan="3"><strong>Handling Fee</strong></td>
                            <td class="text-right">{{ number_format($deliveryCharge, 2) }}</td>
                            <td class="text-right">-{{ number_format($deliveryCharge, 2) }}</td>
                            <td class="text-right">0.00</td>
                            <td class="text-right">0.00</td>
                            <td class="text-right">0.00</td>
                            <td class="text-right">0.00</td>
    </tr>
    @endif

                    <!-- Total Row -->
                    <tr style="background-color: #f9f9f9; font-weight: bold;">
                        <td colspan="2"><strong>Total</strong></td>
                        <td class="text-center"><strong>{{ $totalQty }}</strong></td>
                        <td class="text-right"><strong>{{ number_format($totalGross, 2) }}</strong></td>
                        <td class="text-right"><strong>-{{ number_format($totalDiscount, 2) }}</strong></td>
                        <td class="text-right"><strong>{{ number_format($totalTaxable, 2) }}</strong></td>
                        <td class="text-right"><strong>{{ number_format($totalSGST, 2) }}</strong></td>
                        <td class="text-right"><strong>{{ number_format($totalCGST, 2) }}</strong></td>
                        <td class="text-right"><strong>{{ number_format($grandTotal, 2) }}</strong></td>
    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Grand Total Section -->
        <div class="grand-total-section">
            <div class="grand-total-row">
                <div class="grand-total-left"></div>
                <div class="grand-total-right">
                    <div class="grand-total-row-content">
                        <span class="grand-total-label">Grand Total</span>
                        <span class="grand-total-amount">Rs. {{ number_format($grandTotal, 2) }}</span>
                    </div>
                    <div class="company-name">{{ $vendor->business_name ?? 'KWIKLLY' }}</div>
                    <div class="signature-line"></div>
                    <div class="authorized-signatory">Authorized Signatory</div>
                </div>
            </div>
        </div>
        
        <!-- Footer Section -->
        <div class="footer-section">
            <div class="footer-logo">
                <img src="{{ public_path('assets/website/images/logo.png') }}" alt="Kwiklly" class="logo-img" onerror="this.style.display='none';">
                <div class="thank-you">Thank You!</div>
                <div class="thank-you-sub">for shopping with us</div>
            </div>
            
            <div class="footer-policies">
                <strong>Returns Policy:</strong> We hope you will be delighted with your purchase. However, if you wish to return an item, please do so within the return period mentioned on the product detail page. For a successful return, the item must be unused and in its original packaging with all accessories, brand/manufacturer tags, user manual, warranty card, and invoice. Items marked as "non-returnable" on the product detail page cannot be returned.<br><br>
                
                The goods sold are intended for end user consumption and not for re-sale.<br><br>
                
                <strong>Registered Office:</strong> {{ $vendor->business_name ?? 'KWIKLLY' }}, {{ $vendor->business_address ?? 'Address not available' }}, 
                @if($vendor && $vendor->city)
                    {{ $vendor->city }}, 
                @endif
                @if($vendor && $vendor->state)
                    {{ $vendor->state }}, 
                @endif
                India - {{ $order->address->pincode ?? 'N/A' }}<br><br>
                
                <strong>Contact Information:</strong> 
                @if($vendor && $vendor->business_contact_no)
                    {{ $vendor->business_contact_no }} | 
                @endif
                @if($vendor && $vendor->email)
                    {{ $vendor->email }} | 
                @endif
                www.kwiklly.com/helpcentre
            </div>
            
            <div class="footer-bottom">
                <div class="footer-bottom-left">E. & O.E.</div>
                <div class="footer-bottom-right">page 1 of 1</div>
            </div>
        </div>
    </div>
</body>
</html>
