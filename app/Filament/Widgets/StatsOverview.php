<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        // Doanh thu hôm nay
        $todayRevenue = Order::where('payment_status', 'paid')
            ->whereDate('created_at', today())
            ->sum('total');

        // Doanh thu tháng này
        $monthRevenue = Order::where('payment_status', 'paid')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total');

        // Đơn hàng chờ xử lý
        $pendingOrders = Order::where('status', 'Chờ xử lý')->count();

        // Đơn đang pha chế
        $brewingOrders = Order::whereIn('drink_status', ['pending', 'brewing'])->count();

        // Tổng khách hàng
        $totalCustomers = User::role('customer')->count();

        // Sản phẩm sắp hết hàng (stock <= 5)
        $lowStockProducts = Product::where('stock', '<=', 5)->where('is_active', true)->count();

        return [
            Stat::make('Doanh thu hôm nay', number_format($todayRevenue, 0, ',', '.') . 'đ')
                ->description('Đơn đã thanh toán')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),

            Stat::make('Doanh thu tháng ' . now()->month, number_format($monthRevenue, 0, ',', '.') . 'đ')
                ->description('Tổng doanh thu tháng này')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('info'),

            Stat::make('Đơn chờ xử lý', $pendingOrders)
                ->description('Cần xử lý ngay')
                ->descriptionIcon('heroicon-m-clock')
                ->color($pendingOrders > 0 ? 'warning' : 'success'),

            Stat::make('Đang pha chế', $brewingOrders)
                ->description('Đơn đồ uống đang xử lý')
                ->descriptionIcon('heroicon-m-fire')
                ->color($brewingOrders > 0 ? 'info' : 'gray'),

            Stat::make('Khách hàng', $totalCustomers)
                ->description('Tổng tài khoản khách')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),

            Stat::make('Sắp hết hàng', $lowStockProducts)
                ->description('Sản phẩm tồn kho ≤ 5')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color($lowStockProducts > 0 ? 'danger' : 'success'),
        ];
    }
}
