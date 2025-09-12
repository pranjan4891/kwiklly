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
        .payment-option {
            cursor: pointer;
            transition: all 0.3s ease;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 10px;
        }
        .payment-option:hover {
            background-color: #f8f9fa;
        }
        .payment-option.selected {
            background-color: #e7f3ff;
            border-color: #0d6efd;
        }
        .payment-note {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
            font-size: 0.9rem;
        }
        .phonepe-option {
            display: flex;
            align-items: center;
        }
        .phonepe-icon {
            width: 24px;
            height: 24px;
            margin-right: 10px;
            background-color: #6739B5;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 12px;
        }
        .pay-now-btn {
            width: 100%;
            padding: 12px;
            background-color: #0d6efd;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            margin-top: 15px;
            cursor: pointer;
        }
        .pay-now-btn:hover {
            background-color: #0b5ed7;
        }
        .pay-now-btn:disabled {
            background-color: #6c757d;
            cursor: not-allowed;
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

                            <div class="payment-option" onclick="selectPayment('phonepe')">
                                <input type="radio" id="phonepe" name="payment_method" value="phonepe">
                                <div class="phonepe-option">
                                    <div class="phonepe-icon">P</div>
                                    <label for="phonepe">PhonePe (Online Payment)</label>
                                </div>
                            </div>

                            <div class="payment-option selected" onclick="selectPayment('cod')">
                                <input type="radio" id="cod" name="payment_method" value="cod" checked>
                                <i class="fas fa-money-bill-wave payment-icon"></i>
                                <label for="cod">Cash on Delivery</label>
                            </div>

                            <div class="payment-note">
                                <i class="fas fa-info-circle me-2"></i>
                                PhonePe offers secure online payments via UPI, credit/debit cards, and net banking.
                            </div>

                            <!-- Pay Now Button -->
                            <button class="pay-now-btn" id="pay-now-button">
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
        let selectedPaymentMethod = 'cod';

        function selectPayment(method) {
            selectedPaymentMethod = method;

            // Update radio button selection
            document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
                radio.checked = (radio.value === method);
            });

            // Update visual selection
            document.querySelectorAll('.payment-option').forEach(option => {
                option.classList.remove('selected');
                if (option.querySelector('input').value === method) {
                    option.classList.add('selected');
                }
            });

            // Update button text based on selection
            const payButton = document.getElementById('pay-now-button');
            if (method === 'cod') {
                payButton.textContent = `Confirm Order ₹{{number_format($order->final_amount, 2)}}`;
            } else {
                payButton.textContent = `Pay Now ₹{{number_format($order->final_amount, 2)}}`;
            }
        }

        function processPayment() {
            if (selectedPaymentMethod === 'cod') {
                processCOD();
            } else if (selectedPaymentMethod === 'phonepe') {
                processPhonePe();
            }
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

        function processPhonePe() {
            // Show loading state
            const btn = document.getElementById('pay-now-button');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Redirecting...';
            btn.disabled = true;

            // Send AJAX request to initiate PhonePe payment
            fetch('{{ route("phonepe.pay") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    order_id: {{ $order->id }},
                    amount: {{ $order->final_amount * 100 }} // Convert to paise
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log('PhonePe Response:', data);

                if (data.success && data.redirectUrl) {
                    // DIRECTLY REDIRECT to PhonePe payment page without SweetAlert
                    window.location.href = data.redirectUrl;
                } else {
                    // Show error message in console only (no SweetAlert)
                    console.error('PhonePe Error:', data.message, data.debug);

                    // Simple alert instead of SweetAlert
                    alert('Payment Error: ' + (data.message || 'Something went wrong. Please try again.'));

                    btn.innerHTML = originalText;
                    btn.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Simple alert instead of SweetAlert
                alert('An error occurred. Please try again.');
                btn.innerHTML = originalText;
                btn.disabled = false;
            });
        }

        function processOrder() {
            // Show loading state
            const btn = document.getElementById('pay-now-button');
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

            // Add event listener to the pay button
            document.getElementById('pay-now-button').addEventListener('click', processPayment);
        });
    </script>
</body>
</html>
