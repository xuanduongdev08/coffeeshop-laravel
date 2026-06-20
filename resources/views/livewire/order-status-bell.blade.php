<div class="order-status-bell" x-data="{ open: false }">
    {{-- Icon chuông --}}
    <button
        id="order-bell-btn"
        @click="open = !open"
        class="bell-btn relative"
        aria-label="Thông báo đơn hàng"
    >
        <svg xmlns="http://www.w3.org/2000/svg" class="bell-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="22" height="22">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002
                     6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388
                     6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0
                     11-6 0v-1m6 0H9" />
        </svg>

        @if($unreadCount > 0)
            <span class="bell-badge" id="order-bell-badge">{{ $unreadCount }}</span>
        @endif
    </button>

    {{-- Dropdown danh sách đơn đang xử lý --}}
    <div
        x-show="open"
        @click.outside="open = false"
        class="bell-dropdown"
        x-transition
        id="order-bell-dropdown"
    >
        <div class="bell-dropdown-header">
            <h4>☕ Trạng thái đơn hàng</h4>
        </div>

        <div class="bell-dropdown-body">
            @forelse($orders as $order)
                <div class="bell-item bell-item--{{ $order->drink_status }}">
                    <div class="bell-item-icon">
                        @if($order->drink_status === 'pending')   ✅
                        @elseif($order->drink_status === 'brewing') ☕
                        @else 🎉
                        @endif
                    </div>
                    <div class="bell-item-content">
                        <strong>#{{ $order->tracking_code }}</strong>
                        <span class="bell-item-status">{{ $order->drink_status_label }}</span>
                        <small class="bell-item-time">{{ $order->updated_at->diffForHumans() }}</small>
                    </div>
                </div>
            @empty
                <div class="bell-empty">
                    <p>Không có đơn hàng đang xử lý</p>
                </div>
            @endforelse
        </div>

        <div class="bell-dropdown-footer">
            <a href="{{ route('orders.history') }}" id="view-all-orders-link">Xem tất cả đơn hàng</a>
        </div>
    </div>
</div>
