<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PhonePeService
{
    protected $baseUrl;
    protected $clientId;
    protected $clientSecret;
    protected $version;
    protected $merchantId;

    public function __construct()
    {
        $this->baseUrl = config('services.phonepe.base_url');
        $this->clientId = config('services.phonepe.client_id');
        $this->clientSecret = config('services.phonepe.client_secret');
        $this->version = config('services.phonepe.version');
        $this->merchantId = config('services.phonepe.merchant_id');
    }

    /**
     * Initiate a transaction
     */
    public function initiatePayment($orderId, $amount, $callbackUrl)
    {
        $payload = [
            'merchantId' => $this->merchantId,
            'merchantTransactionId' => $orderId,
            'merchantUserId' => 'MUID-' . uniqid(),
            'amount' => $amount * 100, // amount in paise
            'redirectUrl' => $callbackUrl,
            'redirectMode' => 'POST',
            'callbackUrl' => $callbackUrl,
            'mobileNumber' => '9999999999',
            'paymentInstrument' => [
                'type' => 'PAY_PAGE'
            ]
        ];

        $checksum = base64_encode(hash('sha256', json_encode($payload) . '/pg/v1/pay' . $this->clientSecret, true));

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'X-VERIFY' => $checksum . "###" . $this->version,
            'X-MERCHANT-ID' => $this->merchantId
        ])->post($this->baseUrl . '/pay', [
            'request' => base64_encode(json_encode($payload))
        ]);

        return $response->json();
    }

    /**
     * Check transaction status
     */
    public function checkStatus($orderId)
    {
        $apiPath = "/pg/v1/status/{$this->merchantId}/{$orderId}";

        $checksum = base64_encode(hash('sha256', $apiPath . $this->clientSecret, true));

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'X-VERIFY' => $checksum . "###" . $this->version,
            'X-MERCHANT-ID' => $this->merchantId
        ])->get($this->baseUrl . $apiPath);

        return $response->json();
    }
}
