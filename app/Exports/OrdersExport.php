<?php

namespace App\Exports;

use App\Models\Order;
use Illuminate\Support\Collection;
use Rap2hpoutre\FastExcel\FastExcel;

class OrdersExport
{
    protected string $period;

    public function __construct(string $period = '30')
    {
        $this->period = $period;
    }

    /**
     * Xuất danh sách đơn hàng ra file Excel.
     * Dùng rap2hpoutre/fast-excel (tương thích PHP 8.5).
     */
    public function download(): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $orders = $this->getData();

        return (new FastExcel($orders))->download(
            'don-hang-' . now()->format('Y-m-d') . '.xlsx',
            function (Order $order) {
                return [
                    'Mã đơn'              => $order->tracking_code,
                    'Khách hàng'          => $order->user?->name ?? 'Khách vãng lai',
                    'Người nhận'          => $order->recipient_name,
                    'SĐT'                 => $order->phone,
                    'Địa chỉ'             => $order->shipping_address,
                    'Tạm tính (đ)'        => $order->subtotal,
                    'Phí ship (đ)'        => $order->shipping_fee,
                    'Tổng cộng (đ)'       => $order->total,
                    'Phương thức TT'      => $order->payment_method,
                    'Trạng thái TT'       => match ($order->payment_status) {
                        'paid'    => 'Đã thanh toán',
                        'pending' => 'Chờ thanh toán',
                        'failed'  => 'Thất bại',
                        default   => $order->payment_status,
                    },
                    'Trạng thái đơn'      => $order->status,
                    'Trạng thái pha chế'  => match ($order->drink_status) {
                        'pending'   => 'Đã nhận đơn',
                        'brewing'   => 'Đang pha chế',
                        'completed' => 'Đã hoàn thành',
                        default     => '—',
                    },
                    'Ghi chú'             => $order->notes ?? '',
                    'Ngày đặt'            => $order->created_at->format('d/m/Y H:i'),
                ];
            }
        );
    }

    protected function getData(): Collection
    {
        return Order::with('user')
            ->when($this->period !== 'all', function ($query) {
                $query->where('created_at', '>=', now()->subDays((int) $this->period));
            })
            ->orderByDesc('created_at')
            ->get();
    }
}
