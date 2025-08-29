<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kwiklly - Payment Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset('public/assets/website/assets/css/checkoutaddress.css')}}">
    <link rel="stylesheet" href="{{ asset('public/assets/website/assets/css/paymentsdetails.css')}}">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        .payment-option.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        .payment-note {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <section>
        <div class="container">
            <div class="row extracartmargin">
                <!-- Steps Navigation -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="d-flex gap-4">
                        <!-- Step 1 -->
                        <div class="d-flex align-items-center step-box2 active-step">
                            <div class="step-circle inactive">
                                <span>&#10003;</span>
                            </div>
                            <span class="ms-md-2 step-label text-secondary">Shopping Details</span>
                        </div>
                        <!-- Step 2 -->
                        <div class="d-flex align-items-center step-box2 active-step">
                            <div class="step-circle inactive">
                                <span>&#10003;</span>
                            </div>
                            <span class="ms-md-2 step-label text-secondary">Delivery Address</span>
                        </div>
                        <!-- Step 3 -->
                        <div class="d-flex align-items-center step-box2 active-step">
                            <div class="step-circle">3</div>
                            <span class="ms-md-2 step-label fw-bold">Payment Details</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-7">
                    <!-- Order Summary -->
                    <div class="main-content-box">
                        <div class="order-summary-box">
                            <div class="order-summary-title">Order Summary</div>

                            <div class="order-summary-row">
                                <span>Order ID:</span>
                                <span>#{{$order->order_number}}</span>
                            </div>

                            <div class="order-summary-row">
                                <span>Items Total:</span>
                                <span>₹{{number_format($order->total_price, 2)}}</span>
                            </div>

                            @if($order->wallet_discount > 0)
                            <div class="order-summary-row">
                                <span>Wallet Discount:</span>
                                <span class="text-green">-₹{{number_format($order->wallet_discount, 2)}}</span>
                            </div>
                            @endif

                            @if($order->coupon_discount > 0)
                            <div class="order-summary-row">
                                <span>Coupon Discount:</span>
                                <span class="text-green">-₹{{number_format($order->coupon_discount, 2)}}</span>
                            </div>
                            @endif

                            <div class="order-summary-row grand-total-row">
                                <span>Final Amount:</span>
                                <span>₹{{number_format($order->final_amount, 2)}}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Delivery Address -->
                    <div class="main-content-box">
                        <div class="order-summary-box">
                            <div class="order-summary-title">Delivery Address</div>

                            @if($order->address)
                            <div class="address-card active">
                                <div class="address-content">
                                    <span class="address-type">
                                        @if(strtolower($order->address->type) === 'office')
                                            <i class="fas fa-building me-1"></i> Office
                                        @else
                                            <i class="fas fa-home me-1"></i> Home
                                        @endif
                                    </span>
                                    <b>{{ $order->address->name }}</b>
                                    <p>
                                        @if($order->address->flat)
                                            {{ $order->address->flat }},
                                        @endif
                                        @if($order->address->area)
                                            {{ $order->address->area }},
                                        @endif
                                        @if($order->address->landmark)
                                            {{ $order->address->landmark }},
                                        @endif
                                        {{ $order->address->pincode }}
                                    </p>
                                    <p>{{ $order->address->full_address }}</p>
                                    <p><i class="fas fa-phone me-2"></i>{{ $order->address->phone }}</p>
                                    @if($order->address->alt_phone)
                                        <p><i class="fas fa-phone-alt me-2"></i>Alt: {{ $order->address->alt_phone }}</p>
                                    @endif
                                </div>
                            </div>
                            @else
                            <div class="alert alert-warning">
                                No delivery address found. Please add an address to continue.
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-5">
                    <!-- Payment Methods -->
                    <div class="main-content-box">
                        <div class="payment-methods-box">
                            <div class="order-summary-title">Select Payment Method</div>

                            <div class="payment-option disabled">
                                <input type="radio" id="card" name="payment_method" value="card" disabled>
                                <i class="fas fa-credit-card payment-icon"></i>
                                <label for="card">Credit/Debit Card</label>
                            </div>

                            <div class="payment-option disabled">
                                <input type="radio" id="upi" name="payment_method" value="upi" disabled>
                                <i class="fas fa-mobile-alt payment-icon"></i>
                                <label for="upi">UPI</label>
                            </div>

                            <div class="payment-option selected" onclick="selectPayment('cod')">
                                <input type="radio" id="cod" name="payment_method" value="cod" checked>
                                <i class="fas fa-money-bill-wave payment-icon"></i>
                                <label for="cod">Cash on Delivery</label>
                            </div>

                            <div class="payment-note">
                                <i class="fas fa-info-circle me-2"></i>
                                Online payment options will be available soon. Currently, only Cash on Delivery is supported.
                            </div>

                            <!-- Pay Now Button -->
                            <button class="pay-now-btn" onclick="processCOD()">
                                Confirm Order ₹{{number_format($order->final_amount, 2)}}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script>
        function selectPayment(method) {
            if (method !== 'cod') return;

            // Update radio button selection
            document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
                radio.checked = (radio.value === method);
            });

            // Update visual selection
            document.querySelectorAll('.payment-option').forEach(option => {
                option.classList.remove('selected');
            });
            document.querySelector('.payment-option:not(.disabled)').classList.add('selected');
        }

        function processCOD() {
            // Show confirmation dialog first
            Swal.fire({
                title: 'Confirm Order?',
                text: 'Are you sure you want to place this order with Cash on Delivery?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, confirm order!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // User confirmed, process the order
                    processOrder();
                }
            });
        }

        function processOrder() {
            // Show loading state
            const btn = document.querySelector('.pay-now-btn');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';
            btn.disabled = true;

            // Send AJAX request to process COD order
            fetch('{{ route("order.process.cod") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    order_id: {{ $order->id }}
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    Swal.fire({
                        title: 'Order Confirmed!',
                        text: 'Your order has been placed successfully.',
                        icon: 'success',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Go to Dashboard'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Redirect to dashboard
                            window.location.href = '{{ route("customer.dashboard") }}';
                        }
                    });
                } else {
                    // Show error message
                    Swal.fire({
                        title: 'Error!',
                        text: data.message || 'Something went wrong. Please try again.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error!',
                    text: 'An error occurred. Please try again.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                btn.innerHTML = originalText;
                btn.disabled = false;
            });
        }

        // Initialize the page with COD selected
        document.addEventListener('DOMContentLoaded', function() {
            selectPayment('cod');
        });
    </script>
</body>
</html>
