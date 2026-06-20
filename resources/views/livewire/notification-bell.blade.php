<div class="notification-bell-wrapper" x-data="{ open: false }">
    {{-- Icon chuông thông báo hệ thống --}}
    <button
        @click="open = !open"
        class="bell-btn position-relative"
        aria-label="Thông báo"
        style="background:none;border:none;color:#fff;padding:4px 8px;cursor:pointer;"
    >
        <i class="icon-bell" style="font-size:20px;"></i>

        @if($unreadCount > 0)
            <span class="notif-badge" style="
                position:absolute;top:-4px;right:-4px;
                background:#e74c3c;color:#fff;
                border-radius:50%;width:18px;height:18px;
                font-size:10px;font-weight:700;
                display:flex;align-items:center;justify-content:center;
                animation: pulse 1.5s infinite;
            ">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
        @endif
    </button>

    {{-- Dropdown thông báo --}}
    <div
        x-show="open"
        @click.outside="open = false"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 transform scale-95"
        x-transition:enter-end="opacity-100 transform scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-95"
        style="
            position:absolute;right:0;top:calc(100% + 8px);
            width:340px;background:#fff;border-radius:12px;
            box-shadow:0 10px 40px rgba(0,0,0,0.15);
            z-index:9000;overflow:hidden;
        "
    >
        {{-- Header --}}
        <div style="
            background:linear-gradient(135deg,#8b5a2b,#c49b63);
            padding:14px 16px;
            display:flex;justify-content:space-between;align-items:center;
        ">
            <h6 style="color:#fff;margin:0;font-weight:600;font-size:14px;">
                🔔 Thông báo
                @if($unreadCount > 0)
                    <span style="background:rgba(255,255,255,0.25);border-radius:9999px;padding:1px 8px;font-size:11px;margin-left:6px;">
                        {{ $unreadCount }} mới
                    </span>
                @endif
            </h6>
            @if($unreadCount > 0)
                <button
                    wire:click="markAllRead"
                    style="background:none;border:none;color:rgba(255,255,255,0.85);font-size:11px;cursor:pointer;padding:0;"
                >
                    Đọc tất cả
                </button>
            @endif
        </div>

        {{-- Danh sách thông báo --}}
        <div style="max-height:320px;overflow-y:auto;">
            @forelse($notifications as $notification)
                @php
                    $data    = $notification->data;
                    $isRead  = $notification->read_at !== null;
                    $icon    = $data['icon'] ?? '🔔';
                    $message = $data['message'] ?? 'Bạn có thông báo mới.';
                    $time    = $notification->created_at->diffForHumans();
                @endphp
                <div
                    wire:click="markRead('{{ $notification->id }}')"
                    style="
                        padding:12px 16px;
                        border-bottom:1px solid #f0f0f0;
                        cursor:pointer;
                        background:{{ $isRead ? '#fff' : '#fdf6ec' }};
                        transition:background 0.2s;
                    "
                    onmouseover="this.style.background='#f9f0e3'"
                    onmouseout="this.style.background='{{ $isRead ? '#fff' : '#fdf6ec' }}'"
                >
                    <div style="display:flex;gap:10px;align-items:flex-start;">
                        <span style="font-size:20px;flex-shrink:0;">{{ $icon }}</span>
                        <div style="flex:1;min-width:0;">
                            <p style="margin:0;font-size:13px;color:#3a2d1f;line-height:1.4;">
                                {{ $message }}
                            </p>
                            <small style="color:#9e8a78;font-size:11px;">{{ $time }}</small>
                        </div>
                        @if(!$isRead)
                            <span style="
                                width:8px;height:8px;border-radius:50%;
                                background:#c49b63;flex-shrink:0;margin-top:4px;
                            "></span>
                        @endif
                    </div>
                </div>
            @empty
                <div style="padding:32px 16px;text-align:center;color:#9e8a78;">
                    <p style="font-size:32px;margin-bottom:8px;">🔔</p>
                    <p style="font-size:13px;margin:0;">Chưa có thông báo nào</p>
                </div>
            @endforelse
        </div>

        {{-- Footer --}}
        <div style="padding:10px 16px;border-top:1px solid #f0f0f0;text-align:center;">
            <a href="{{ route('orders.history') }}"
               style="color:#c49b63;font-size:13px;text-decoration:none;font-weight:500;">
                Xem lịch sử đơn hàng →
            </a>
        </div>
    </div>
</div>
