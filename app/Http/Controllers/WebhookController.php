<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessPaymentWebhook;
use App\Models\Order;
use App\Services\MoMoService;
use App\Services\PayPalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Xử lý webhook từ các cổng thanh toán.
 *
 * Controller chỉ làm 2 việc:
 *   1. Xác thực chữ ký (nếu có)
 *   2. Dispatch ProcessPaymentWebhook Job vào queue
 *
 * Logic cập nhật đơn hàng nằm trong Job để xử lý bất đồng bộ.
 * Khi QUEUE_CONNECTION=sync, job chạy ngay lập tức (không cần queue worker).
 */
class WebhookController extends Controller
{
    /**
     * Webhook từ SePay — tự động xác nhận thanh toán chuyển khoản
     * SePay gửi header: Authorization: apikey XDTHECOFFEE_SECURE_2026
     */
    public function handleCasso(Request $request)
    {
        // ── Xác thực API Key từ SePay ──────────────────────────────────────
        $expectedKey  = config('services.sepay.api_key');
        $sentAuth     = $request->header('Authorization', '');          // "apikey XDTHECOFFEE_SECURE_2026"
        $sentKey      = trim(str_ireplace('apikey', '', $sentAuth));    // "XDTHECOFFEE_SECURE_2026"

        if ($expectedKey && ! hash_equals($expectedKey, $sentKey)) {
            Log::warning('SePay Webhook: Invalid API Key. Rejected.', [
                'ip'   => $request->ip(),
                'auth' => $sentAuth,
            ]);
            return response()->json(['status' => 'unauthorized'], 401);
        }
        // ───────────────────────────────────────────────────────────────────

        Log::channel('daily')->info('SePay Webhook received', [
            'ip'      => $request->ip(),
            'payload' => $request->all(),
        ]);

        ProcessPaymentWebhook::dispatch($request->all(), 'casso');

        return response()->json(['status' => 'queued']);
    }

    /**
     * Webhook IPN từ MoMo
     */
    public function handleMoMo(Request $request, MoMoService $momo)
    {
        Log::channel('daily')->info('MoMo IPN received', $request->all());

        $data = $request->all();

        // Xác thực chữ ký trước khi dispatch
        if (! $momo->verifySignature($data)) {
            Log::warning('MoMo IPN: Invalid signature. Rejected.');
            return response()->json(['status' => 1, 'message' => 'Invalid signature'], 400);
        }

        ProcessPaymentWebhook::dispatch($data, 'momo');

        return response()->json(['status' => 0, 'message' => 'success']);
    }

    /**
     * Webhook IPN từ PayPal
     */
    public function handlePayPal(Request $request, PayPalService $paypal)
    {
        Log::channel('daily')->info('PayPal Webhook received', $request->all());

        // Xác thực chữ ký webhook trước khi dispatch
        if (! $paypal->verifyWebhookSignature($request)) {
            Log::warning('PayPal Webhook: Invalid signature. Rejected.');
            return response()->json(['status' => 'failed', 'message' => 'Invalid signature'], 400);
        }

        ProcessPaymentWebhook::dispatch($request->all(), 'paypal');

        return response()->json(['status' => 'success', 'message' => 'Confirm Success']);
    }
}
