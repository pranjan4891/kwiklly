<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Services\PhonePeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $phonePe;

    public function __construct(PhonePeService $phonePe)
    {
        $this->phonePe = $phonePe;
    }

    public function initiate(Request $request)
    {
        $orderId = 'ORD' . time();
        $amount = 100; // ₹100
        $callbackUrl = route('payment.callback');

        $response = $this->phonePe->initiatePayment($orderId, $amount, $callbackUrl);
        Log::info('PhonePe Initiate Response: ', $response);
        if (isset($response['success']) && $response['success'] == true) {
            return redirect()->away($response['data']['instrumentResponse']['redirectInfo']['url']);
        }

        return back()->with('error', 'Payment initiation failed');
    }

    public function callback(Request $request)
    {
        $orderId = $request->input('transactionId'); // or your own tracking ID

        $status = $this->phonePe->checkStatus($orderId);

        if ($status['success'] && $status['code'] == 'PAYMENT_SUCCESS') {
            // ✅ Payment success, update DB
            return view('payment.success');
        }

        return view('payment.failed', ['status' => $status]);
    }
}
