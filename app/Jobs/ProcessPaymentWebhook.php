<?php

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * Job xử lý webhook thanh toán bất đồng bộ.
 *
 * Thay vì xử lý trực tiếp trong WebhookController (đồng bộ),
 * dispatch job này vào queue để xử lý nền.
 *
 * Cách dùng:
 *   ProcessPaymentWebhook::dispatch($payload, 'casso');
 *   ProcessPaymentWebhook::dispatch($payload, 'momo');
 *   ProcessPaymentWebhook::dispatch($payload, 'paypal');
 *
 * Khi QUEUE_CONNECTION=sync (mặc định), job chạy ngay lập tức.
 * Khi QUEUE_CONNECTION=database, job chạy nền qua `php artisan queue:work`.
 */
class ProcessPaymentWebhook implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Số lần retry nếu job thất bại
     */
    public int $tries = 3;

    /**
     * Timeout (giây)
     */
    public int $timeout = 30;

    public function __construct(
        private readonly array  $payload,
        private readonly string $source  // 'casso' | 'momo' | 'paypal'
    ) {}

    public function handle(): void
    {
        Log::channel('daily')->info("ProcessPaymentWebhook [{$this->source}]", $this->payload);

        match ($this->source) {
            'casso'  => $this->processCasso(),
            'momo'   => $this->processMoMo(),
            'paypal' => $this->processPayPal(),
            default  => Log::warning("ProcessPaymentWebhook: Unknown source [{$this->source}]"),
        };
    }

    // ─────────────────────────────────────────────
    // CASSO / SEPAY — Chuyển khoản ngân hàng
    // ─────────────────────────────────────────────

    private function processCasso(): void
    {
        $description = $this->payload['content'] ?? $this->payload['description'] ?? '';
        $amount      = (float) ($this->payload['transferAmount'] ?? $this->payload['amount'] ?? 0);

        if (! preg_match('/XD\d{4,6}/i', $description, $matches)) {
            Log::info('Casso: No tracking code found in description.');
            return;
        }

        $trackingCode = strtoupper($matches[0]);
        $order        = Order::where('tracking_code', $trackingCode)->first();

        if (! $order) {
            Log::warning("Casso: Order {$trackingCode} not found.");
            return;
        }

        if ($order->payment_status === 'paid') {
            Log::info("Casso: Order {$trackingCode} already paid. Skipping.");
            return;
        }

        // Cho phép sai lệch 1.000đ (làm tròn)
        if (abs($amount - $order->total) > 1000) {
            Log::warning("Casso: Amount mismatch for {$trackingCode}. Got {$amount}, expected {$order->total}");
            return;
        }

        $order->update([
            'payment_status' => 'paid',
            'payment_method' => 'VietQR',
        ]);

        $this->sendConfirmationNotification($order);

        Log::info("Casso: Order {$trackingCode} marked paid. Amount={$amount}");
    }

    // ─────────────────────────────────────────────
    // MOMO IPN
    // ─────────────────────────────────────────────

    private function processMoMo(): void
    {
        $resultCode = $this->payload['resultCode'] ?? -1;

        if ((int) $resultCode !== 0) {
            Log::info("MoMo: Payment failed. resultCode={$resultCode}");
            return;
        }

        $orderId      = $this->payload['orderId'] ?? '';
        $trackingCode = explode('_', $orderId)[0]; // XD00001_timestamp → XD00001
        $order        = Order::where('tracking_code', $trackingCode)->first();

        if (! $order) {
            Log::warning("MoMo: Order {$trackingCode} not found.");
            return;
        }

        if ($order->payment_status === 'paid') {
            Log::info("MoMo: Order {$trackingCode} already paid. Skipping.");
            return;
        }

        $order->update([
            'payment_status' => 'paid',
            'payment_method' => 'MoMo',
        ]);

        $this->sendConfirmationNotification($order);

        Log::info("MoMo: Order {$trackingCode} marked paid.");
    }

    // ─────────────────────────────────────────────
    // PAYPAL WEBHOOK
    // ─────────────────────────────────────────────

    private function processPayPal(): void
    {
        $eventType = $this->payload['event_type'] ?? '';

        if ($eventType !== 'PAYMENT.CAPTURE.COMPLETED') {
            Log::info("PayPal: Skipping event type {$eventType}");
            return;
        }

        $resource = $this->payload['resource'] ?? [];
        $status   = $resource['status'] ?? '';

        if ($status !== 'COMPLETED') {
            Log::info("PayPal: Payment not completed. Status={$status}");
            return;
        }

        $trackingCode = $resource['custom_id'] ?? '';
        if (!$trackingCode) {
            $trackingCode = $resource['invoice_id'] ?? '';
        }

        if (!$trackingCode) {
            Log::warning("PayPal Webhook: No custom_id/trackingCode found.");
            return;
        }

        $order = Order::where('tracking_code', $trackingCode)->first();

        if (! $order) {
            Log::warning("PayPal: Order {$trackingCode} not found.");
            return;
        }

        if ($order->payment_status === 'paid') {
            Log::info("PayPal: Order {$trackingCode} already paid. Skipping.");
            return;
        }

        $order->update([
            'payment_status' => 'paid',
            'payment_method' => 'PayPal',
        ]);

        $this->sendConfirmationNotification($order);

        Log::info("PayPal: Order {$trackingCode} marked paid.");
    }

    private function sendConfirmationNotification(Order $order): void
    {
        if ($order->drink_status === 'pending') {
            $hasNotification = $order->user->notifications()
                ->where('data->order_id', $order->id)
                ->where('data->drink_status', 'pending')
                ->exists();
            if (!$hasNotification && $order->user) {
                $order->user->notify(new \App\Notifications\DrinkStatusUpdated($order));
            }
        }
    }

    /**
     * Xử lý khi job thất bại sau tất cả các lần retry
     */
    public function failed(\Throwable $exception): void
    {
        Log::error("ProcessPaymentWebhook [{$this->source}] FAILED: " . $exception->getMessage(), [
            'payload' => $this->payload,
        ]);
    }
}
