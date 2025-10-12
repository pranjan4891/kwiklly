<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Services\PhonePeService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected PhonePeService $phonePe;

    public function __construct(PhonePeService $phonePe)
    {
        $this->phonePe = $phonePe;
    }

    public function pay(Request $request)
    {
        // ✅ Initiate Payment

        $response = $this->phonePe->initiatePayment([
            'amount' => $request->amount ?? 10000, // amount in INR
            'mobile' => $request->mobile?? 9999999999,
        ]);

        if (isset($response['data']['instrumentResponse']['redirectInfo']['url'])) {
            return redirect($response['data']['instrumentResponse']['redirectInfo']['url']);
        }

        return response()->json($response);
    }

    public function callback(Request $request)
    {
        // ✅ Handle PhonePe callback here
        // Save status in DB
        return response()->json($request->all());
    }

    public function status($txnId)
    {
        return $this->phonePe->checkStatus($txnId);
    }
}
