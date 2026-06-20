<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

/**
 * MoMo Payment Service — API v2
 * Tài liệu: https://developers.momo.vn/v3/docs/payment/api/payment-api
 */
class MoMoService
{
    private string $partnerCode;
    private string $accessKey;
    private string $secretKey;
    private string $endpoint;
    private string $redirectUrl;
    private string $ipnUrl;

    public function __construct()
    {
        $this->partnerCode = config('services.momo.partner_code', '');
        $this->accessKey   = config('services.momo.access_key', '');
        $this->secretKey   = config('services.momo.secret_key', '');
        $this->endpoint    = config('services.momo.endpoint', 'https://test-payment.momo.vn/v2/gateway/api/create');
        $this->redirectUrl = url('/thanh-toan/momo/ket-qua');
        $this->ipnUrl      = url('/webhook/momo');
    }

    /**
     * Tạo link thanh toán MoMo
     * Trả về ['success' => bool, 'pay_url' => string, 'message' => string]
     */
    public function createPayment(Order $order): array
    {
        $orderId     = $order->tracking_code . '_' . time();
        $requestId   = Str::uuid()->toString();
        $amount      = (string) (int) $order->total;
        $orderInfo   = 'Thanh toan don hang ' . $order->tracking_code;
        $extraData   = base64_encode(json_encode(['order_id' => $order->id]));
        $requestType = 'payWithMethod'; // hoặc 'captureWallet'

        // Tạo chữ ký HMAC SHA256
        $rawHash = "accessKey={$this->accessKey}"
            . "&amount={$amount}"
            . "&extraData={$extraData}"
            . "&ipnUrl={$this->ipnUrl}"
            . "&orderId={$orderId}"
            . "&orderInfo={$orderInfo}"
            . "&partnerCode={$this->partnerCode}"
            . "&redirectUrl={$this->redirectUrl}"
            . "&requestId={$requestId}"
            . "&requestType={$requestType}";

        $signature = hash_hmac('sha256', $rawHash, $this->secretKey);

        $body = [
            'partnerCode' => $this->partnerCode,
            'accessKey'   => $this->accessKey,
            'requestId'   => $requestId,
            'amount'      => $amount,
            'orderId'     => $orderId,
            'orderInfo'   => $orderInfo,
            'redirectUrl' => $this->redirectUrl,
            'ipnUrl'      => $this->ipnUrl,
            'extraData'   => $extraData,
            'requestType' => $requestType,
            'signature'   => $signature,
            'lang'        => 'vi',
        ];

        try {
            $response = Http::timeout(10)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post($this->endpoint, $body);

            if ($response->successful()) {
                $data = $response->json();
                if (($data['resultCode'] ?? -1) === 0) {
                    return [
                        'success' => true,
                        'pay_url' => $data['payUrl'],
                        'message' => 'Tạo link MoMo thành công',
                    ];
                }
                return [
                    'success' => false,
                    'pay_url' => null,
                    'message' => $data['message'] ?? 'MoMo trả về lỗi: ' . ($data['resultCode'] ?? 'unknown'),
                ];
            }
        } catch (\Exception $e) {
            \Log::error('MoMo createPayment error: ' . $e->getMessage());
        }

        return [
            'success' => false,
            'pay_url' => null,
            'message' => 'Không thể kết nối MoMo. Vui lòng thử lại.',
        ];
    }

    /**
     * Xác thực chữ ký IPN từ MoMo
     */
    public function verifySignature(array $data): bool
    {
        $rawHash = "accessKey={$this->accessKey}"
            . "&amount={$data['amount']}"
            . "&extraData={$data['extraData']}"
            . "&message={$data['message']}"
            . "&orderId={$data['orderId']}"
            . "&orderInfo={$data['orderInfo']}"
            . "&orderType={$data['orderType']}"
            . "&partnerCode={$data['partnerCode']}"
            . "&payType={$data['payType']}"
            . "&requestId={$data['requestId']}"
            . "&responseTime={$data['responseTime']}"
            . "&resultCode={$data['resultCode']}"
            . "&transId={$data['transId']}";

        $expected = hash_hmac('sha256', $rawHash, $this->secretKey);
        return hash_equals($expected, $data['signature'] ?? '');
    }

    /**
     * Kiểm tra giao dịch thành công
     */
    public function isSuccess(array $data): bool
    {
        return ($data['resultCode'] ?? -1) === 0;
    }

    /**
     * Lấy tracking_code từ orderId MoMo (bỏ phần _timestamp)
     */
    public function extractTrackingCode(string $orderId): string
    {
        return explode('_', $orderId)[0];
    }
}
