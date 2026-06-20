<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Thống kê tổng quan
        $stats = [
            'total_orders'    => Order::count(),
            'pending_orders'  => Order::where('status', 'Chờ xử lý')->count(),
            'total_revenue'   => Order::where('payment_status', 'paid')->sum('total'),
            'total_customers' => User::role('customer')->count(),
            'total_products'  => Product::where('is_active', true)->count(),
            'low_stock'       => Product::where('stock', '<=', 5)->where('is_active', true)->count(),
        ];

        // Đơn hàng mới nhất (10 đơn)
        $latestOrders = Order::with('user')
            ->latest()
            ->take(10)
            ->get();

        // Doanh thu 7 ngày gần nhất
        $revenueChart = Order::where('payment_status', 'paid')
            ->where('created_at', '>=', now()->subDays(6))
            ->selectRaw('DATE(created_at) as date, SUM(total) as revenue, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        // Tạo mảng 7 ngày đầy đủ (kể cả ngày không có đơn)
        $chartLabels  = [];
        $chartRevenue = [];
        $chartOrders  = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $chartLabels[]  = now()->subDays($i)->format('d/m');
            $chartRevenue[] = $revenueChart[$date]->revenue ?? 0;
            $chartOrders[]  = $revenueChart[$date]->count ?? 0;
        }

        // Đơn hàng đang pha chế (drink_status)
        $brewingOrders = Order::whereIn('drink_status', ['pending', 'brewing'])
            ->with('user')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'latestOrders',
            'chartLabels',
            'chartRevenue',
            'chartOrders',
            'brewingOrders'
        ));
    }
}
