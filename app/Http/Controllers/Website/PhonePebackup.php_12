<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\PhonePeService;
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
        $amount = $request->input('amount', 1000); // paise (₹10)
        $result = $this->phonePe->createOrder($amount);

        if (isset($result['redirectUrl'])) {
            return redirect()->away($result['redirectUrl']);
        }

        return response()->json($result, 500);
    }

    // Redirect after payment
    public function redirect(string $orderId)
    {
        $status = $this->phonePe->checkStatus($orderId);

        if (($status['state'] ?? '') === 'COMPLETED') {
            return redirect(config('services.phonepe.frontend_url') . "/success?merchantOrderId={$orderId}");
        }

        return redirect(config('services.phonepe.frontend_url') . "/failure?merchantOrderId={$orderId}");
    }

    // Callback from PhonePe
    public function callback(Request $request)
    {
        Log::info('PhonePe Callback Data: ', $request->all());

        $merchantOrderId = $request->input('merchantOrderId');
        if ($merchantOrderId) {
            $status = $this->phonePe->checkStatus($merchantOrderId);
            Log::info("PhonePe Order Status: ", $status);
        }

        return response()->json(['status' => 'callback received']);
    }

    // Manual status check
    public function status(Request $request)
    {
        $orderId = $request->input('merchantOrderId');
        return $this->phonePe->checkStatus($orderId);
    }
}
