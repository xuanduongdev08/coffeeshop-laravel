<?php

namespace App\Livewire;

use Livewire\Attributes\Poll;
use Livewire\Component;

/**
 * Livewire component: Chuông thông báo hệ thống (database notifications).
 * Thay thế ajax_thongbao.php cũ.
 *
 * Polling mỗi 10 giây để kiểm tra thông báo chưa đọc.
 * Render: resources/views/livewire/notification-bell.blade.php
 *
 * Cách dùng trong layout:
 *   @auth
 *       <livewire:notification-bell />
 *   @endauth
 */
class NotificationBell extends Component
{
    public int $unreadCount = 0;

    #[Poll('10s')]
    public function refreshNotifications(): void
    {
        $this->unreadCount = $this->getUnreadCount();
    }

    /**
     * Đánh dấu tất cả thông báo là đã đọc
     */
    public function markAllRead(): void
    {
        if (auth()->check()) {
            auth()->user()->unreadNotifications->markAsRead();
            $this->unreadCount = 0;
        }
    }

    /**
     * Đánh dấu 1 thông báo là đã đọc
     */
    public function markRead(string $notificationId): void
    {
        if (auth()->check()) {
            $notification = auth()->user()
                ->notifications()
                ->find($notificationId);

            if ($notification) {
                $notification->markAsRead();
                $this->unreadCount = max(0, $this->unreadCount - 1);
            }
        }
    }

    private function getUnreadCount(): int
    {
        if (! auth()->check()) {
            return 0;
        }
        return auth()->user()->unreadNotifications()->count();
    }

    private function getNotifications()
    {
        if (! auth()->check()) {
            return collect();
        }

        return auth()->user()
            ->notifications()
            ->latest()
            ->take(10)
            ->get();
    }

    public function render()
    {
        $notifications    = $this->getNotifications();
        $this->unreadCount = $this->getUnreadCount();

        return view('livewire.notification-bell', [
            'notifications' => $notifications,
            'unreadCount'   => $this->unreadCount,
        ]);
    }
}
