<?php

namespace App\Filament\Pages;

use App\Exports\OrdersExport;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Carbon;

class Statistics extends Page
{
    protected string $view = 'filament.pages.statistics';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    protected static ?string $navigationLabel = 'Thống kê';

    protected static string|\UnitEnum|null $navigationGroup = 'Quản lý hệ thống';

    protected static ?int $navigationSort = 2;

    public string $period = '30';

    public function mount(): void
    {
        $this->period = request('period', '30');
    }

    public function getViewData(): array
    {
        $days = (int) $this->period;
        $from = Carbon::now()->subDays($days);

        // Doanh thu theo ngày
        $revenueByDay = Order::where('payment_status', 'paid')
            ->where('created_at', '>=', $from)
            ->selectRaw('DATE(created_at) as date, SUM(total) as revenue, COUNT(*) as orders')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top 10 sản phẩm bán chạy
        $topProducts = OrderItem::with('product')
            ->where('created_at', '>=', $from)
            ->selectRaw('product_id, product_name, SUM(quantity) as total_qty, SUM(subtotal) as total_revenue')
            ->groupBy('product_id', 'product_name')
            ->orderByDesc('total_qty')
            ->limit(10)
            ->get();

        // Phân tích phương thức thanh toán
        $paymentMethods = Order::where('payment_status', 'paid')
            ->where('created_at', '>=', $from)
            ->selectRaw('payment_method, COUNT(*) as count, SUM(total) as revenue')
            ->groupBy('payment_method')
            ->get();

        // Khách hàng mới
        $newCustomers = User::where('created_at', '>=', $from)->count();

        // Tổng doanh thu kỳ
        $totalRevenue = Order::where('payment_status', 'paid')
            ->where('created_at', '>=', $from)
            ->sum('total');

        // Tổng đơn hàng kỳ
        $totalOrders = Order::where('created_at', '>=', $from)->count();

        return compact(
            'revenueByDay',
            'topProducts',
            'paymentMethods',
            'newCustomers',
            'totalRevenue',
            'totalOrders',
        );
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('export')
                ->label('Xuất Excel')
                ->icon(Heroicon::OutlinedArrowDownTray)
                ->color('success')
                ->action(function () {
                    return (new OrdersExport($this->period))->download();
                }),
        ];
    }
}
