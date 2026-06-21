<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * PayPal Payment Service using REST API v2
 */
class PayPalService
{
    private string $clientId;
    private string $clientSecret;
    private string $mode;
    private ?string $webhookId;
    private float $vndToUsdRate;
    private string $baseUrl;

    public function __construct()
    {
        $this->clientId     = config('services.paypal.client_id') ?: '';
        $this->clientSecret = config('services.paypal.client_secret') ?: '';
        $this->mode         = config('services.paypal.mode') ?: 'sandbox';
        $this->webhookId    = config('services.paypal.webhook_id');
        $this->vndToUsdRate = (float) (config('services.paypal.vnd_to_usd_rate') ?: 25000);
        $this->baseUrl      = $this->mode === 'live' ? 'https://api-m.paypal.com' : 'https://api-m.sandbox.paypal.com';
    }

    /**
     * Get access token from PayPal (cached for 8 hours)
     */
    public function getAccessToken(): ?string
    {
        if (empty($this->clientId) || empty($this->clientSecret)) {
            Log::error('PayPalService: Client ID or Client Secret is not configured.');
            return null;
        }

        return cache()->remember('paypal_access_token', now()->addHours(8), function () {
            try {
                $response = Http::asForm()
                    ->withBasicAuth($this->clientId, $this->clientSecret)
                    ->post("{$this->baseUrl}/v1/oauth2/token", [
                        'grant_type' => 'client_credentials'
                    ]);

                if ($response->successful()) {
                    return $response->json('access_token');
                }

                Log::error('PayPal getAccessToken failed', [
                    'status' => $response->status(),
                    'body'   => $response->body()
                ]);
            } catch (\Exception $e) {
                Log::error('PayPal getAccessToken exception: ' . $e->getMessage());
            }

            return null;
        });
    }

    /**
     * Create PayPal Checkout Order and return the approval link
     */
    public function createOrder(Order $order): ?string
    {
        $accessToken = $this->getAccessToken();
        if (!$accessToken) {
            return null;
        }

        // Convert VND total to USD
        $usdAmount = round($order->total / $this->vndToUsdRate, 2);

        try {
            $response = Http::withToken($accessToken)
                ->post("{$this->baseUrl}/v2/checkout/orders", [
                    'intent' => 'CAPTURE',
                    'purchase_units' => [
                        [
                            'reference_id' => $order->tracking_code,
                            'custom_id'    => $order->tracking_code,
                            'amount' => [
                                'currency_code' => 'USD',
                                'value' => number_format($usdAmount, 2, '.', '')
                            ],
                            'description' => 'Thanh toan don hang ' . $order->tracking_code
                        ]
                    ],
                    'payment_source' => [
                        'paypal' => [
                            'experience_context' => [
                                'payment_method_preference' => 'IMMEDIATE_PAYMENT_REQUIRED',
                                'brand_name' => config('app.name', 'XDTHECOFFEEHOUSE'),
                                'locale' => 'vi-VN',
                                'landing_page' => 'NO_PREFERENCE',
                                'user_action' => 'PAY_NOW',
                                'return_url' => route('payment.paypal.return'),
                                'cancel_url' => route('payment.paypal.cancel', ['order' => $order->id])
                            ]
                        ]
                    ]
                ]);

            if ($response->successful()) {
                $links = $response->json('links') ?: [];
                foreach ($links as $link) {
                    if (($link['rel'] ?? '') === 'approve') {
                        return $link['href'];
                    }
                }
            }

            Log::error('PayPal createOrder failed', [
                'status' => $response->status(),
                'body'   => $response->body()
            ]);
        } catch (\Exception $e) {
            Log::error('PayPal createOrder exception: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Capture the PayPal Order when user is redirected back
     */
    public function captureOrder(string $paypalOrderId): ?array
    {
        $accessToken = $this->getAccessToken();
        if (!$accessToken) {
            return null;
        }

        try {
            $response = Http::withToken($accessToken)
                ->withHeaders([
                    'Content-Type' => 'application/json'
                ])
                ->post("{$this->baseUrl}/v2/checkout/orders/{$paypalOrderId}/capture");

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('PayPal captureOrder failed', [
                'order_id' => $paypalOrderId,
                'status'   => $response->status(),
                'body'     => $response->body()
            ]);
        } catch (\Exception $e) {
            Log::error('PayPal captureOrder exception: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Verify Webhook Signature from PayPal
     */
    public function verifyWebhookSignature(Request $request): bool
    {
        $accessToken = $this->getAccessToken();
        if (!$accessToken) {
            return false;
        }

        if (empty($this->webhookId)) {
            Log::warning('PayPal Webhook Verification: PAYPAL_WEBHOOK_ID is not configured.');
            return false;
        }

        try {
            $response = Http::withToken($accessToken)
                ->post("{$this->baseUrl}/v1/notifications/verify-webhook-signature", [
                    'auth_algo'         => $request->header('PAYPAL-AUTH-ALGO'),
                    'cert_url'          => $request->header('PAYPAL-CERT-URL'),
                    'transmission_id'   => $request->header('PAYPAL-TRANSMISSION-ID'),
                    'transmission_sig'  => $request->header('PAYPAL-TRANSMISSION-SIG'),
                    'transmission_time' => $request->header('PAYPAL-TRANSMISSION-TIME'),
                    'webhook_id'        => $this->webhookId,
                    'webhook_event'     => $request->all(),
                ]);

            if ($response->successful() && $response->json('verification_status') === 'SUCCESS') {
                return true;
            }

            Log::warning('PayPal Webhook signature verification failed', [
                'status' => $response->status(),
                'body'   => $response->json()
            ]);
        } catch (\Exception $e) {
            Log::error('PayPal Webhook verification exception: ' . $e->getMessage());
        }

        return false;
    }
}
