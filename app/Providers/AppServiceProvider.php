<?php

namespace App\Providers;

use App\Models\Order;
use App\Observers\OrderObserver;
use App\Services\CartService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind CartService vào container để dùng dependency injection
        $this->app->singleton(CartService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Dùng Bootstrap 4 pagination view (phù hợp với CSS theme hiện tại)
        Paginator::useBootstrap();

        // Đăng ký Observer theo dõi thay đổi drink_status trên Order
        // → tự động gửi DrinkStatusUpdated notification cho khách hàng
        Order::observe(OrderObserver::class);
    }
}
