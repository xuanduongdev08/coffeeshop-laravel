<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\EmailTemplate;
use App\Mail\DynamicTemplateMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('tracking_code', 'like', "%{$s}%")
                  ->orWhere('recipient_name', 'like', "%{$s}%")
                  ->orWhere('phone', 'like', "%{$s}%");
            });
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->latest()->paginate(20)->withQueryString();

        $statusCounts = [
            'all'        => Order::count(),
            'pending'    => Order::where('status', 'Chờ xử lý')->count(),
            'processing' => Order::where('status', 'Đang giao')->count(),
            'completed'  => Order::where('status', 'Hoàn thành')->count(),
            'cancelled'  => Order::where('status', 'Đã hủy')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'statusCounts'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product', 'items.modifiers']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:Chờ xử lý,Đang giao,Hoàn thành,Đã hủy',
        ]);

        $oldStatus = $order->status;
        $newStatus = $request->status;

        // Không cho phép đổi từ "Đã hủy" sang trạng thái khác (tránh sai lệch kho)
        if ($oldStatus === 'Đã hủy' && $newStatus !== 'Đã hủy') {
            return back()->with('error', 'Không thể thay đổi trạng thái đơn hàng đã bị hủy. Vui lòng tạo đơn hàng mới nếu cần.');
        }

        // Hoàn kho khi chuyển sang "Đã hủy" (và trước đó chưa hủy)
        if ($newStatus === 'Đã hủy' && $oldStatus !== 'Đã hủy') {
            $order->load('items');
            foreach ($order->items as $item) {
                Product::where('id', $item->product_id)->increment('stock', $item->quantity);
            }
        }

        // Tự động cập nhật payment_status thành 'paid' khi Hoàn thành
        $updateData = ['status' => $newStatus];
        if ($newStatus === 'Hoàn thành' && $order->payment_status !== 'paid') {
            $updateData['payment_status'] = 'paid';
        }

        $order->update($updateData);

        // Gửi email thông báo trạng thái đơn hàng cho khách hàng
        // Chỉ gửi khi đơn hàng KHÔNG có drink (đơn có drink sẽ được thông báo qua DrinkStatusController)
        // Nếu có drink, vẫn gửi email trạng thái đơn nhưng KHÔNG bị trùng với email pha chế
        try {
            $order->load('user'); // Eager load để tránh N+1
            $template = EmailTemplate::where('template_key', 'order_status_updated')->first();
            if ($template && $order->user) {
                $placeholders = [
                    '{customer_name}'    => $order->user->name,
                    '{order_code}'       => $order->tracking_code,
                    '{order_status}'     => $newStatus,
                    '{shipping_address}' => $order->shipping_address ?? 'Không có địa chỉ',
                    '{total_price}'      => $order->formatted_total,
                    '{order_link}'       => route('orders.show', $order),
                ];
                Mail::to($order->user->email)->send(new DynamicTemplateMail($template, $placeholders));
            }
        } catch (\Exception $e) {
            \Log::warning('Failed to send order status email: ' . $e->getMessage());
        }

        return back()->with('success', "Đã cập nhật trạng thái đơn #{$order->tracking_code} thành \"{$request->status}\".");
    }

    public function updatePaymentStatus(Request $request, Order $order)
    {
        $request->validate([
            'payment_status' => 'required|in:pending,paid,failed,refunded',
        ]);

        $order->update(['payment_status' => $request->payment_status]);

        return back()->with('success', "Đã cập nhật trạng thái thanh toán đơn #{$order->tracking_code}.");
    }
}
