<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kwiklly - Payment Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset('public/assets/website/CSS/checkoutdelivery.css')}}">    
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>
<body>
    <section>
        <div class="container px-3 px-md-4">
            <div class="row g-3">
                <!-- Steps Navigation -->
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center extracartmargin">
                        <!-- Steps -->
                        <div class="d-flex gap-4">
                            <!-- Step 1 -->
                            <div class="d-flex align-items-center step-box2 active-step">
                                <div class="step-circle inactive" style="background-color: #28a745;"><span style="color: white;">&#10003;</span></div>
                                <span class="ms-md-2 step-label text-secondary">Shopping Details</span>
                            </div>
                            <!-- Step 2 -->
                            <div class="d-flex align-items-center step-box2 active-step">
                                <div class="step-circle inactive" style="background-color: #28a745;"><span style="color: white;">&#10003;</span></div>
                                <span class="ms-md-2 step-label text-secondary">Delivery Address</span>
                            </div>
                            <!-- Step 3 -->
                            <div class="d-flex align-items-center step-box2 active-step">
                                <div class="step-circle">3</div>
                                <span class="ms-md-2 step-label fw-bold">Payment Details</span>
                            </div>
                        </div>
                    </div>
                </div>
                <hr style="border: 1px solid #D8C2BC;" class="my-3">
            </div>
            <div class="row g-3">
                <div class="col-12 col-md-7">
                    <!-- Order Summary -->
                    <div class="main-content-box mb-3">
                        <div class="p-3">
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
                                <span><strong>Final Amount:</strong></span>
                                <span><strong>₹{{number_format($order->final_amount, 2)}}</strong></span>
                            </div>
                        </div>
                    </div>

                    <!-- Delivery Address -->
                    <div class="main-content-box">
                        <div class="p-3">
                            <div class="order-summary-title">Delivery Address</div>

                            @if($order->address)
                            <div class="address-card selected-address">
                                <div class="address-left">
                                    <span class="address-icon-emoji">
                                        @if(strtolower($order->address->type) === 'work' || strtolower($order->address->type) === 'office')
                                            🏢
                                        @else
                                            🏠
                                        @endif
                                    </span>
                                    <div>
                                        <strong>{{ ucfirst($order->address->type) }}</strong>
                                        <p>
                                            {{ $order->address->name }}, 
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
                                    </div>
                                </div>
                            </div>
                            @if($order->address->full_address)
                            <div class="mt-2">
                                <p class="mb-1"><small class="text-muted">{{ $order->address->full_address }}</small></p>
                                <p class="mb-1"><small><i class="fas fa-phone me-2"></i>{{ $order->address->phone }}</small></p>
                                @if($order->address->alt_phone)
                                    <p class="mb-0"><small><i class="fas fa-phone-alt me-2"></i>Alt: {{ $order->address->alt_phone }}</small></p>
                                @endif
                            </div>
                            @endif
                            @else
                            <div class="alert alert-warning">
                                No delivery address found. Please add an address to continue.
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-5">
                    <!-- Payment Methods -->
                    <div class="main-content-box">
                        <div class="p-3">
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
                            <button class="pay-now-btn proceed-btn2" id="pay-now-button">
                                Confirm Order ₹{{number_format($order->final_amount, 2)}}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container-fluid">
            <!-- Desktop: Logo + Location & Search -->
            <div class="d-flex align-items-center w-100 d-md-flex">
                <a class="navbar-brand" href="{{ route('home')}}">
                    <img src="{{ asset('public/assets/website/images/logo.png')}}" alt="Logo">
                </a>
            </div>
        </div>
    </nav>
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
                confirmButtonColor: '#E94412',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, Confirm Order',
                cancelButtonText: 'Cancel',
                reverseButtons: true
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

            // Send AJAX request to initiate PhonePe payment (order_id 0 = from session checkout)
            fetch('{{ $order->id ? route("phonepe.pay") : route("payment.initiate.phonepe") }}', {
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
                    order_id: {{ $order->id ?? 0 }}
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message with two buttons
                    Swal.fire({
                        title: 'Order Confirmed!',
                        text: 'Your order has been placed successfully.',
                        icon: 'success',
                        showDenyButton: true,
                        showCancelButton: false,
                        confirmButtonColor: '#E94412',
                        denyButtonColor: '#6c757d',
                        confirmButtonText: 'Go to Dashboard',
                        denyButtonText: 'Go to Home',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Redirect to dashboard
                            window.location.href = '{{ route("customer.dashboard") }}';
                        } else if (result.isDenied) {
                            // Redirect to home
                            window.location.href = '{{ route("home") }}';
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
