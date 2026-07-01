<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class OrderStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly Order $order,
        public readonly string $type // 'status' | 'payment_status'
    ) {}

    public function via(mixed $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(mixed $notifiable): array
    {
        $message = '';
        $icon = '🔔';
        $title = 'Cập nhật đơn hàng';

        if ($this->type === 'status') {
            $title = match ($this->order->status) {
                'Chờ xử lý' => '⏳ Đơn hàng chờ xử lý',
                'Đang giao'  => '🚚 Đơn hàng đang giao',
                'Hoàn thành' => '🎁 Đơn hàng hoàn thành',
                'Đã hủy'     => '❌ Đơn hàng đã hủy',
                default      => '🔔 Trạng thái đơn hàng',
            };

            $message = match ($this->order->status) {
                'Chờ xử lý' => "Đơn hàng #{$this->order->tracking_code} của bạn đang ở trạng thái chờ xử lý.",
                'Đang giao'  => "Đơn hàng #{$this->order->tracking_code} đang được giao đến bạn!",
                'Hoàn thành' => "Đơn hàng #{$this->order->tracking_code} đã giao thành công. Cảm ơn bạn đã ủng hộ!",
                'Đã hủy'     => "Đơn hàng #{$this->order->tracking_code} đã bị hủy.",
                default      => "Đơn hàng #{$this->order->tracking_code} đã thay đổi trạng thái sang: {$this->order->status}.",
            };

            $icon = match ($this->order->status) {
                'Chờ xử lý' => '⏳',
                'Đang giao'  => '🚚',
                'Hoàn thành' => '🎁',
                'Đã hủy'     => '❌',
                default      => '🔔',
            };
        } elseif ($this->type === 'payment_status') {
            $title = match ($this->order->payment_status) {
                'pending'   => '💰 Chờ thanh toán',
                'paid'      => '💳 Đã thanh toán',
                'failed'    => '⚠️ Thanh toán thất bại',
                'refunded'  => '↩️ Đã hoàn tiền',
                default     => '💰 Trạng thái thanh toán',
            };

            $message = match ($this->order->payment_status) {
                'pending'   => "Đơn hàng #{$this->order->tracking_code} đang chờ bạn thanh toán.",
                'paid'      => "Đơn hàng #{$this->order->tracking_code} đã thanh toán thành công!",
                'failed'    => "Giao dịch thanh toán cho đơn hàng #{$this->order->tracking_code} đã thất bại.",
                'refunded'  => "Đơn hàng #{$this->order->tracking_code} đã được hoàn tiền thành công.",
                default     => "Trạng thái thanh toán đơn hàng #{$this->order->tracking_code} đã thay đổi: {$this->order->payment_status}.",
            };

            $icon = match ($this->order->payment_status) {
                'paid'      => '💳',
                'refunded'  => '↩️',
                'failed'    => '⚠️',
                default      => '💰',
            };
        }

        return [
            'order_id'      => $this->order->id,
            'tracking_code' => $this->order->tracking_code,
            'type'          => $this->type,
            'title'         => $title,
            'status'        => $this->order->status,
            'payment_status'=> $this->order->payment_status,
            'icon'          => $icon,
            'message'       => $message,
        ];
    }
}
