<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class PhonePeService
{
    protected string $env;
    protected string $clientId;
    protected string $clientSecret;
    protected string $clientVersion;
    protected string $backendUrl;
    protected string $frontendUrl;

    protected array $authUrls = [
        'prod' => 'https://api.phonepe.com/apis/identity-manager/v1/oauth/token',
        'uat'  => 'https://api-preprod.phonepe.com/apis/pg-sandbox/v1/oauth/token',
    ];

    protected array $paymentUrls = [
        'prod' => 'https://api.phonepe.com/apis/pg/checkout/v2/pay',
        'uat'  => 'https://api-preprod.phonepe.com/apis/pg-sandbox/checkout/v2/pay',
    ];

    protected array $orderStatusUrls = [
        'prod' => 'https://api.phonepe.com/apis/pg/checkout/v2/order/{merchantOrderId}/status',
        'uat'  => 'https://api-preprod.phonepe.com/apis/pg-sandbox/checkout/v2/order/{merchantOrderId}/status',
    ];

    public function __construct()
    {
        $this->env = config('services.phonepe.env');
        $this->clientId = config('services.phonepe.client_id');
        $this->clientSecret = config('services.phonepe.client_secret');
        $this->clientVersion = config('services.phonepe.client_version');
        $this->backendUrl = config('services.phonepe.backend_url');
        $this->frontendUrl = config('services.phonepe.frontend_url');
    }

    /**
     * Fetch OAuth Token (cached 50 mins)
     */
    public function getToken(): ?string
    {
        return Cache::remember('phonepe_token', 50 * 60, function () {
            $response = Http::asForm()->post($this->authUrls[$this->env], [
                'client_id' => $this->clientId,
                'client_version' => $this->clientVersion,
                'client_secret' => $this->clientSecret,
                'grant_type' => 'client_credentials',
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['access_token'] ?? null;
            }

            return null;
        });
    }

    /**
     * Create Payment Order
     * @param int $amount Amount in paise
     * @param string|null $merchantOrderId Optional (e.g. PC_123 for pending checkout)
     */
    public function createOrder(int $amount, ?string $merchantOrderId = null): array
    {
        $token = $this->getToken();
        if (!$token) {
            return ['success' => false, 'message' => 'Unable to fetch token'];
        }

        $merchantOrderId = $merchantOrderId ?? ('ORD_' . uniqid());

        $payload = [
            "merchantOrderId" => $merchantOrderId,
            "amount" => $amount,
            "expireAfter" => 1200, // 20 min
            "paymentFlow" => [
                "type" => "PG_CHECKOUT",
                "message" => "Payment request",
                "merchantUrls" => [
                    "redirectUrl" => "{$this->backendUrl}/phonepe/redirect/{$merchantOrderId}",
                    "callbackUrl" => "{$this->backendUrl}/phonepe/callback", // ✅ Added callback
                ]
            ]
        ];

        $response = Http::withHeaders([
            'Authorization' => "O-Bearer {$token}",
            'Accept' => 'application/json',
        ])->post($this->paymentUrls[$this->env], $payload);

        return $response->json();
    }

    /**
     * Check Order Status
     */
    public function checkStatus(string $merchantOrderId): array
    {
        $token = $this->getToken();
        if (!$token) {
            return ['success' => false, 'message' => 'Unable to fetch token'];
        }

        $url = str_replace('{merchantOrderId}', $merchantOrderId, $this->orderStatusUrls[$this->env]);

        $response = Http::withHeaders([
            'Authorization' => "O-Bearer {$token}",
            'Accept' => 'application/json',
        ])->get($url);

        return $response->json();
    }
}
