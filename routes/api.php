<?php

use App\Http\Controllers\Api\ChatController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — XDTHECOFFEEHOUSE
|--------------------------------------------------------------------------
*/

// CaféAI Chatbox
Route::post('/chat', [ChatController::class, 'handle'])
    ->middleware('web')
    ->name('api.chat');

// Webhook SePay / Casso
Route::post('/sepay', [\App\Http\Controllers\WebhookController::class, 'handleCasso'])->name('api.sepay');

// Thông báo người dùng
Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/notifications', [\App\Http\Controllers\Api\NotificationController::class, 'index'])
        ->name('api.notifications.index');
    Route::post('/notifications/read-all', [\App\Http\Controllers\Api\NotificationController::class, 'markAllRead'])
        ->name('api.notifications.read-all');
    Route::post('/notifications/{id}/read', [\App\Http\Controllers\Api\NotificationController::class, 'markRead'])
        ->name('api.notifications.read');
});
