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

        // Không cho phép cập nhật pha chế khi đơn đã bị hủy
        if ($order->status === 'Đã hủy') {
            return back()->with('error', 'Không thể cập nhật trạng thái pha chế cho đơn hàng đã bị hủy.');
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

        // Nhãn trạng thái pha chế
        $labels = [
            'brewing'   => 'Đang pha chế',
            'completed' => 'Đã hoàn thành',
        ];

        /**
         * Gửi email thông báo trạng thái pha chế cho khách hàng.
         *
         * Lưu ý:
         * - Email này dành riêng cho đơn có sản phẩm đồ uống (drink_status != null).
         * - Khi drink_status = 'brewing'  → báo "đang pha chế, vui lòng chờ".
         * - Khi drink_status = 'completed' → báo "đã xong / đã giao hàng".
         * - OrderObserver (DrinkStatusUpdated notification) chỉ gửi thông báo database
         *   (in-app bell icon), KHÔNG phải email → không bị trùng.
         */
        try {
            $order->load('user'); // Eager load để tránh N+1

            $template = \App\Models\EmailTemplate::where('template_key', 'drink_status_updated')->first();
            if ($template && $order->user) {
                $statusLabel = $labels[$nextStatus] ?? $nextStatus;

                // Thêm ghi chú bổ sung tùy theo trạng thái
                $extraNote = match ($nextStatus) {
                    'brewing'   => 'Đồ uống của bạn đang được pha chế. Vui lòng chờ trong giây lát!',
                    'completed' => 'Đồ uống của bạn đã hoàn thành và sẵn sàng được giao. Chúng tôi sẽ giao hàng đến bạn ngay!',
                    default     => '',
                };

                $placeholders = [
                    '{customer_name}'      => $order->user->name,
                    '{order_code}'         => $order->tracking_code,
                    '{drink_status_label}' => $statusLabel,
                    '{extra_note}'         => $extraNote,
                    '{order_link}'         => route('orders.show', $order),
                ];
                \Illuminate\Support\Facades\Mail::to($order->user->email)
                    ->send(new \App\Mail\DynamicTemplateMail($template, $placeholders));
            }
        } catch (\Exception $e) {
            \Log::warning('Failed to send drink status update email: ' . $e->getMessage());
        }

        return back()->with('success', "Đơn #{$order->tracking_code}: " . ($labels[$nextStatus] ?? $nextStatus));
    }
}
