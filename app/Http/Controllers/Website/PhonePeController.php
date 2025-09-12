<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\PhonePeService;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PhonePeController extends Controller
{
    protected PhonePeService $phonePe;

    public function __construct(PhonePeService $phonePe)
    {
        $this->phonePe = $phonePe;
    }

    // Start Payment
    public function pay(Request $request)
    {
        try {
            $request->validate([
                'order_id' => 'required|exists:orders,id',
                'amount' => 'required|numeric'
            ]);

            // Verify order belongs to authenticated user and is pending
            $order = Order::where('id', $request->order_id)
                        ->where('user_id', Auth::id())
                        ->where('status', 'pending')
                        ->first();

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found or already processed'
                ], 404);
            }

            // Create a payment record with shorter payment_method value
            $payment = Payment::create([
                'order_id' => $order->id,
                'user_id' => Auth::id(),
                'payment_method' => 'phonepe', // Using 'pp' instead of 'online' to avoid truncation
                'payment_status' => 'initiated',
                'amount' => $order->final_amount,
                'currency' => 'INR',
                'transaction_id' => 'ORD_' . uniqid() // Match PhonePe format
            ]);

            // Create order with PhonePe
            $result = $this->phonePe->createOrder($request->amount);

            Log::info('PhonePe API Response: ', $result);

            // Check if the API call was successful - NEW RESPONSE FORMAT
            if (isset($result['orderId']) && isset($result['redirectUrl'])) {
                // Update payment with PhonePe reference
                $payment->update([
                    'gateway_reference' => $result['orderId'] ?? null,
                    'payment_status' => 'pending'
                ]);

                return response()->json([
                    'success' => true,
                    'redirectUrl' => $result['redirectUrl']
                ]);
            }

            // If failed, update payment status
            $errorMessage = $result['message'] ?? ($result['error'] ?? 'Unknown error from PhonePe');

            $payment->update([
                'payment_status' => 'failed',
                'failure_reason' => $errorMessage
            ]);

            return response()->json([
                'success' => false,
                'message' => $errorMessage,
                'debug' => $result // For debugging
            ], 500);

        } catch (\Exception $e) {
            Log::error('PhonePe Payment Initiation Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Internal server error: ' . $e->getMessage()
            ], 500);
        }
    }

    // Redirect after payment
    public function redirect(Request $request, $orderId)
    {
        try {
            // Find the payment
            $payment = Payment::where('transaction_id', $orderId)->first();

            if (!$payment) {
                return redirect()->route('phonepe.failure')->with('error', 'Payment not found');
            }

            // Check payment status with PhonePe
            $status = $this->phonePe->checkStatus($orderId);
            Log::info('PhonePe Status Check: ', $status);

            // Check different possible success indicators based on PhonePe API response
            $isSuccess = (
                ($status['code'] ?? '') === 'PAYMENT_SUCCESS' ||
                ($status['success'] ?? false) === true ||
                ($status['state'] ?? '') === 'COMPLETED' ||
                ($status['paymentState'] ?? '') === 'SUCCESS' ||
                ($status['state'] ?? '') === 'SUCCESS'
            );

            if ($isSuccess) {
                // Update payment status
                $payment->update([
                    'payment_status' => 'completed',
                    'gateway_response' => json_encode($status)
                ]);

                // Update order status
                $payment->order->update([
                    'status' => 'confirmed'
                ]);

                return redirect()->route('phonepe.success')->with([
                    'success' => 'Payment completed successfully',
                    'order_id' => $payment->order_id
                ]);
            }

            // Payment failed or still pending
            if (($status['state'] ?? '') === 'PENDING') {
                return redirect()->route('phonepe.failure')->with('error', 'Payment is still pending. Please check back later.');
            }

            // Payment failed
            $payment->update([
                'payment_status' => 'failed',
                'gateway_response' => json_encode($status),
                'failure_reason' => $status['message'] ?? ($status['error'] ?? 'Payment failed')
            ]);

            return redirect()->route('phonepe.failure')->with('error', $status['message'] ?? ($status['error'] ?? 'Payment failed'));

        } catch (\Exception $e) {
            Log::error('PhonePe Redirect Error: ' . $e->getMessage());
            return redirect()->route('phonepe.failure')->with('error', 'Error processing payment: ' . $e->getMessage());
        }
    }

    // Callback from PhonePe
    public function callback(Request $request)
    {
        Log::info('PhonePe Callback Data: ', $request->all());

        try {
            $merchantOrderId = $request->input('data.merchantOrderId') ?? $request->input('merchantOrderId');

            if ($merchantOrderId) {
                // Find the payment
                $payment = Payment::where('transaction_id', $merchantOrderId)->first();

                if ($payment) {
                    $status = $request->input('data.state') ?? $request->input('status');

                    if ($status === 'COMPLETED' || $status === 'SUCCESS') {
                        // Update payment status
                        $payment->update([
                            'payment_status' => 'completed',
                            'gateway_response' => json_encode($request->all())
                        ]);

                        // Update order status
                        $payment->order->update([
                            'status' => 'confirmed'
                        ]);
                    } else {
                        // Payment failed
                        $payment->update([
                            'payment_status' => 'failed',
                            'gateway_response' => json_encode($request->all()),
                            'failure_reason' => $request->input('data.responseMessage') ?? ($request->input('message') ?? 'Payment failed')
                        ]);
                    }
                }
            }

            return response()->json(['status' => 'callback received']);

        } catch (\Exception $e) {
            Log::error('PhonePe Callback Error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    // Manual status check
    public function status(Request $request)
    {
        try {
            $orderId = $request->input('merchantOrderId');
            return $this->phonePe->checkStatus($orderId);
        } catch (\Exception $e) {
            Log::error('PhonePe Status Check Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
