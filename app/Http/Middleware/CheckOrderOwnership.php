<?php

namespace App\Http\Middleware;

use App\Models\Order;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckOrderOwnership
{
    /**
     * Kiểm tra đơn hàng thuộc về người dùng hiện tại.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $orderId = $request->route('order');

        if ($orderId) {
            $order = $orderId instanceof Order ? $orderId : Order::find($orderId);

            if ($order && $order->user_id !== $request->user()?->id) {
                abort(403, 'Bạn không có quyền xem đơn hàng này.');
            }
        }

        return $next($request);
    }
}
