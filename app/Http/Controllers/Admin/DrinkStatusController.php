<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

/**
 * Controller cho nhân viên cập nhật trạng thái pha chế đồ uống.
 *
 * Route (trong routes/web.php — admin group):
 *   Route::patch('/orders/{order}/drink-status', [DrinkStatusController::class, 'update'])
 *        ->name('admin.orders.drink-status.update');
 *
 * Dashboard admin hiển thị nút:
 *   - "Bắt đầu pha chế" (pending → brewing)
 *   - "Hoàn thành"      (brewing → completed)
 */
class DrinkStatusController extends Controller
{
    public function update(Request $request, Order $order)
    {
        // Chỉ xử lý đơn có đồ uống
        if (! $order->has_drink) {
            return back()->with('error', 'Đơn hàng này không có sản phẩm đồ uống.');
        }

        $nextStatus = $order->next_drink_status;

        if (! $nextStatus) {
            return back()->with('info', 'Đơn hàng đã ở trạng thái cuối cùng.');
        }

        $updateData = ['drink_status' => $nextStatus];

        if ($nextStatus === 'brewing') {
            $updateData['brewing_at'] = now();
        } elseif ($nextStatus === 'completed') {
            $updateData['completed_at'] = now();
        }

        $order->update($updateData);

        $labels = [
            'brewing'   => 'Đang pha chế',
            'completed' => 'Đã hoàn thành',
        ];

        // Gửi email thông báo trạng thái pha chế
        try {
            $template = \App\Models\EmailTemplate::where('template_key', 'drink_status_updated')->first();
            if ($template && $order->user) {
                $statusLabel = $labels[$nextStatus] ?? $nextStatus;
                $placeholders = [
                    '{customer_name}'      => $order->user->name,
                    '{order_code}'         => $order->tracking_code,
                    '{drink_status_label}' => $statusLabel,
                    '{order_link}'         => route('orders.show', $order),
                ];
                \Illuminate\Support\Facades\Mail::to($order->user->email)->send(new \App\Mail\DynamicTemplateMail($template, $placeholders));
            }
        } catch (\Exception $e) {
            \Log::warning('Failed to send drink status update email: ' . $e->getMessage());
        }

        return back()->with('success', "Đơn #{$order->tracking_code}: " . ($labels[$nextStatus] ?? $nextStatus));
    }
}
