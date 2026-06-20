<?php

namespace App\Observers;

use App\Models\Order;
use App\Notifications\DrinkStatusUpdated;

/**
 * Observer lắng nghe sự kiện trên Order model.
 *
 * Đăng ký trong AppServiceProvider:
 *   Order::observe(OrderObserver::class);
 */
class OrderObserver
{
    /**
     * Khi đơn hàng được cập nhật: kiểm tra drink_status có thay đổi không.
     * Nếu có → gửi notification cho khách hàng.
     */
    public function updated(Order $order): void
    {
        if ($order->isDirty('drink_status') && $order->user_id) {
            // Đảm bảo user tồn tại trước khi gửi notification
            if ($order->user) {
                $order->user->notify(new DrinkStatusUpdated($order));
            }
        }
    }
}
