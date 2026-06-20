<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Lấy danh sách thông báo của user hiện tại
     */
    public function index(Request $request)
    {
        $user          = $request->user();
        $notifications = $user->notifications()->latest()->take(20)->get();

        $data = $notifications->map(function ($notif) {
            return [
                'id'        => $notif->id,
                'type'      => $notif->type,
                'data'      => $notif->data,
                'read_at'   => $notif->read_at,
                'is_unread' => is_null($notif->read_at),
                'time'      => $notif->created_at->diffForHumans(),
            ];
        });

        return response()->json([
            'notifications'  => $data,
            'unread_count'   => $user->unreadNotifications()->count(),
        ]);
    }

    /**
     * Đánh dấu tất cả thông báo là đã đọc
     */
    public function markAllRead(Request $request)
    {
        $request->user()->unreadNotifications->markAsRead();

        return response()->json(['success' => true, 'unread_count' => 0]);
    }

    /**
     * Đánh dấu một thông báo là đã đọc
     */
    public function markRead(Request $request, string $id)
    {
        $notification = $request->user()->notifications()->find($id);

        if ($notification) {
            $notification->markAsRead();
        }

        return response()->json([
            'success'      => true,
            'unread_count' => $request->user()->unreadNotifications()->count(),
        ]);
    }
}
