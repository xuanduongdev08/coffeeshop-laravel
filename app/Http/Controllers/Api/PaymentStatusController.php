<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class PaymentStatusController extends Controller
{
    /**
     * Kiểm tra trạng thái thanh toán của đơn hàng (dùng cho polling)
     */
    public function check(Request $request, Order $order)
    {
        // Chỉ cho phép chủ đơn hàng kiểm tra
        if ($order->user_id !== $request->user()?->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json([
            'order_id'       => $order->id,
            'tracking_code'  => $order->tracking_code,
            'payment_status' => $order->payment_status,
            'is_paid'        => $order->payment_status === 'paid',
            'status'         => $order->status,
        ]);
    }
}
