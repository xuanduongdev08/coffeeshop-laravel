<?php

namespace App\Livewire;

use Livewire\Component;

/**
 * Livewire component: CaféAI Chatbox Widget.
 * Thay thế cafe_ai_chatbox.html cũ.
 *
 * Giao tiếp với ChatController qua fetch() JS (không dùng Livewire wire:click
 * để tránh reload toàn bộ component khi chat — giữ UX mượt mà).
 *
 * Render: resources/views/livewire/cafe-ai-chatbox.blade.php
 *
 * Cách dùng trong layout (shop.blade.php):
 *   <livewire:cafe-a-i-chatbox />
 */
class CafeAIChatbox extends Component
{
    public bool $isOpen = false;

    public function toggle(): void
    {
        $this->isOpen = ! $this->isOpen;
    }

    public function open(): void
    {
        $this->isOpen = true;
    }

    public function close(): void
    {
        $this->isOpen = false;
    }

    public function render()
    {
        return view('livewire.cafe-ai-chatbox', [
            'apiUrl'   => url('/api/chat'),
            'userName' => auth()->user()?->name,
            'isAuth'   => auth()->check(),
        ]);
    }
}
