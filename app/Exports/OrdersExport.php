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

        $headerStyle = (new \OpenSpout\Common\Entity\Style\Style())
            ->setFontBold()
            ->setFontSize(12)
            ->setFontColor(\OpenSpout\Common\Entity\Style\Color::WHITE)
            ->setBackgroundColor('6F4E37'); // Nâu cà phê sang trọng

        $rowsStyle = (new \OpenSpout\Common\Entity\Style\Style())
            ->setFontSize(11);

        return (new FastExcel($orders))
            ->headerStyle($headerStyle)
            ->rowsStyle($rowsStyle)
            ->download(
                'XDTHECOFFEEHOUSE-REPORT-' . now()->format('Ymd-His') . '.xlsx',
                function (Order $order) {
                return [
                    'Mã đơn'              => $order->tracking_code,
                    'Khách hàng'          => $order->user?->name ?? 'Khách vãng lai',
                    'Người nhận'          => $order->recipient_name,
                    'SĐT'                 => $order->phone,
                    'Địa chỉ'             => $order->shipping_address,
                    'Tạm tính'            => number_format($order->subtotal, 0, ',', '.') . 'đ',
                    'Phí ship'            => number_format($order->shipping_fee, 0, ',', '.') . 'đ',
                    'Tổng cộng'           => number_format($order->total, 0, ',', '.') . 'đ',
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
