<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

/**
 * Thông báo real-time khi trạng thái pha chế đồ uống thay đổi.
 *
 * Được gửi qua channel 'database' → Livewire OrderStatusBell đọc
 * và hiển thị trên header cho khách hàng.
 *
 * Luồng: pending → brewing → completed
 */
class DrinkStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public readonly Order $order) {}

    public function via(mixed $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(mixed $notifiable): array
    {
        $message = match ($this->order->drink_status) {
            'pending'   => "Đơn hàng #{$this->order->tracking_code} đã được nhận. Chúng tôi đang chuẩn bị!",
            'brewing'   => "Đơn hàng #{$this->order->tracking_code} đang được pha chế. Vui lòng chờ trong giây lát!",
            'completed' => "Đơn hàng #{$this->order->tracking_code} đã hoàn thành. Mời bạn nhận đồ!",
            default     => "Đơn hàng #{$this->order->tracking_code} đã được cập nhật.",
        };

        $icon = match ($this->order->drink_status) {
            'pending'   => '✅',
            'brewing'   => '☕',
            'completed' => '🎉',
            default     => '🔔',
        };

        return [
            'order_id'      => $this->order->id,
            'tracking_code' => $this->order->tracking_code,
            'drink_status'  => $this->order->drink_status,
            'status_label'  => $this->order->drink_status_label,
            'icon'          => $icon,
            'message'       => $message,
            'brewing_at'    => $this->order->brewing_at?->toISOString(),
            'completed_at'  => $this->order->completed_at?->toISOString(),
        ];
    }
}
