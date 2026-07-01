<?php

namespace App\Observers;

use App\Models\Order;
use App\Notifications\DrinkStatusUpdated;

use App\Notifications\OrderStatusUpdated;

/**
 * Observer lắng nghe sự kiện trên Order model.
 *
 * Đăng ký trong AppServiceProvider:
 *   Order::observe(OrderObserver::class);
 */
class OrderObserver
{
    /**
     * Khi đơn hàng được cập nhật: kiểm tra thay đổi trạng thái và gửi notification.
     */
    public function updated(Order $order): void
    {
        if ($order->user_id && $order->user) {
            // 1. Theo dõi trạng thái pha chế
            if ($order->isDirty('drink_status')) {
                $order->user->notify(new DrinkStatusUpdated($order));
            }

            // 2. Theo dõi trạng thái đơn hàng (status)
            if ($order->isDirty('status')) {
                $order->user->notify(new OrderStatusUpdated($order, 'status'));
            }

            // 3. Theo dõi trạng thái thanh toán (payment_status)
            if ($order->isDirty('payment_status')) {
                $order->user->notify(new OrderStatusUpdated($order, 'payment_status'));
            }
        }
    }
}
