<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Attributes\Poll;
use Livewire\Component;

/**
 * Livewire component: Chuông thông báo trạng thái đồ uống trên Header.
 *
 * Polling mỗi 5 giây để kiểm tra đơn hàng đang xử lý.
 * Render: resources/views/livewire/order-status-bell.blade.php
 *
 * Cách dùng trong layout:
 *   @auth
 *       <livewire:order-status-bell />
 *   @endauth
 */
class OrderStatusBell extends Component
{
    public int $unreadCount = 0;

    /**
     * Cập nhật số đơn đang xử lý mỗi 5 giây.
     */
    #[Poll('5s')]
    public function refreshCount(): void
    {
        $this->unreadCount = $this->getPendingOrders()->count();
    }

    private function getPendingOrders()
    {
        if (! auth()->check()) {
            return collect();
        }

        return Order::where('user_id', auth()->id())
            ->whereIn('drink_status', ['pending', 'brewing'])
            ->latest('updated_at')
            ->take(5)
            ->get();
    }

    public function render()
    {
        $orders = $this->getPendingOrders();
        $this->unreadCount = $orders->count();

        return view('livewire.order-status-bell', [
            'orders'      => $orders,
            'unreadCount' => $this->unreadCount,
        ]);
    }
}
