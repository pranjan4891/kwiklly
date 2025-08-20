<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PhonePeService
{
    protected $merchantId;
    protected $saltKey;
    protected $baseUrl;
    protected $clientId;

    public function __construct()
    {
        $this->merchantId = env('PHONEPE_MERCHANT_ID');
        $this->saltKey = env('PHONEPE_SALT_KEY');
        $this->clientId = env('PHONEPE_CLIENT_ID');
        $this->baseUrl = rtrim(env('PHONEPE_BASE_URL'), '/'); // remove trailing slash if any
    }

    /**
     * Initiate a PhonePe transaction
     */
    public function initiatePayment($orderId, $amount, $callbackUrl)
    {
        $payload = [
            "merchantId" => $this->merchantId,
            "merchantTransactionId" => $orderId,
            "merchantUserId" => "user123", // optional
            "amount" => $amount * 100, // in paise
            "redirectUrl" => $callbackUrl,
            "redirectMode" => "POST",
            "callbackUrl" => $callbackUrl,
            "paymentInstrument" => [
                "type" => "PAY_PAGE"
            ]
        ];

        $jsonPayload = json_encode($payload, JSON_UNESCAPED_SLASHES);
        $base64Payload = base64_encode($jsonPayload);

        $path = "/pg/v1/pay";
        $xVerify = hash('sha256', $base64Payload . $path . $this->saltKey) . "###1";

        $url = $this->baseUrl . $path;

        try {
            $response = Http::withOptions([
                'verify' => true // Set to false only in localhost without CA certs
            ])->withHeaders([
                "Content-Type" => "application/json",
                "X-VERIFY" => $xVerify,
                "X-CLIENT-ID" => $this->clientId,
            ])->post($url, [
                'request' => $base64Payload
            ]);

            if ($response->failed()) {
                Log::error('PhonePe Payment Init Failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'payload' => $payload,
                ]);
            }

            return $response->json();

        } catch (\Exception $e) {
            Log::error('PhonePe API Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => 'Exception during PhonePe API call.'
            ];
        }
    }

    /**
     * Verify a PhonePe transaction status
     */
    public function verifyPayment($orderId)
    {
        $path = "/pg/v1/status/{$this->merchantId}/{$orderId}";
        $xVerify = hash('sha256', $path . $this->saltKey) . "###1";

        $url = $this->baseUrl . $path;

        try {
            $response = Http::withOptions([
                'verify' => true
            ])->withHeaders([
                "Content-Type" => "application/json",
                "X-VERIFY" => $xVerify,
                "X-CLIENT-ID" => $this->clientId,
            ])->get($url);

            if ($response->failed()) {
                Log::error('PhonePe Payment Verification Failed', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
            }

            return $response->json();

        } catch (\Exception $e) {
            Log::error('PhonePe Verify Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => 'Exception during payment verification.'
            ];
        }
    }
}
