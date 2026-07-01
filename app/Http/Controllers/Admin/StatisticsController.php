<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', '30'); // 7, 30, 90, 365 ngày

        $startDate = now()->subDays((int) $period);

        // Doanh thu theo ngày
        $revenueByDay = Order::where('payment_status', 'paid')
            ->where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, SUM(total) as revenue, COUNT(*) as orders')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Tổng doanh thu kỳ này
        $totalRevenue = $revenueByDay->sum('revenue');
        $totalOrders  = $revenueByDay->sum('orders');

        // Sản phẩm bán chạy (chỉ tính đơn đã thanh toán)
        $topProducts = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.payment_status', 'paid') // Chỉ tính đơn đã thanh toán
            ->where('orders.created_at', '>=', $startDate)
            ->selectRaw('products.name, products.image, SUM(order_items.quantity) as total_qty, SUM(order_items.subtotal) as total_revenue')
            ->groupBy('products.id', 'products.name', 'products.image')
            ->orderByDesc('total_qty')
            ->take(10)
            ->get();

        // Doanh thu theo danh mục
        $revenueByCategory = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.payment_status', 'paid')
            ->where('orders.created_at', '>=', $startDate)
            ->selectRaw('categories.name, SUM(order_items.subtotal) as revenue')
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('revenue')
            ->get();

        // Phương thức thanh toán
        $paymentMethods = Order::where('payment_status', 'paid')
            ->where('created_at', '>=', $startDate)
            ->selectRaw('payment_method, COUNT(*) as count, SUM(total) as revenue')
            ->groupBy('payment_method')
            ->orderByDesc('revenue')
            ->get();

        // Khách hàng mới
        $newCustomers = User::role('customer')
            ->where('created_at', '>=', $startDate)
            ->count();

        return view('admin.statistics.index', compact(
            'revenueByDay',
            'totalRevenue',
            'totalOrders',
            'topProducts',
            'revenueByCategory',
            'paymentMethods',
            'newCustomers',
            'period'
        ));
    }

    /**
     * Export báo cáo Excel
     */
    public function export(Request $request)
    {
        $period    = $request->get('period', '30');
        $startDate = now()->subDays((int) $period);

        $orders = Order::with(['user', 'items'])
            ->where('created_at', '>=', $startDate)
            ->latest()
            ->get();

        // Dùng rap2hpoutre/fast-excel
        $rows = $orders->map(fn ($o) => [
            'Mã đơn'          => $o->tracking_code,
            'Khách hàng'      => $o->recipient_name,
            'SĐT'             => $o->phone,
            'Địa chỉ'         => $o->shipping_address,
            'Tổng tiền'       => number_format($o->total, 0, ',', '.') . 'đ',
            'Thanh toán'      => $o->payment_method,
            'TT thanh toán'   => $o->payment_status,
            'Trạng thái'      => $o->status,
            'Ngày đặt'        => $o->created_at->format('d/m/Y H:i'),
        ]);

        $fileName = 'XDTHECOFFEEHOUSE-REPORT-' . now()->format('Ymd-His') . '.xlsx';

        $headerStyle = (new \OpenSpout\Common\Entity\Style\Style())
            ->setFontBold()
            ->setFontSize(12)
            ->setFontColor(\OpenSpout\Common\Entity\Style\Color::WHITE)
            ->setBackgroundColor('6F4E37'); // Nâu cà phê sang trọng

        $rowsStyle = (new \OpenSpout\Common\Entity\Style\Style())
            ->setFontSize(11);

        return (new \Rap2hpoutre\FastExcel\FastExcel($rows))
            ->headerStyle($headerStyle)
            ->rowsStyle($rowsStyle)
            ->download($fileName);
    }
}
