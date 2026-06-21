@php
    // Số lượng giỏ hàng từ session
    $cartCount = 0;
    $cart = session('cart', []);
    foreach ($cart as $item) {
        $cartCount += $item['quantity'] ?? 0;
    }

    // Số thông báo chưa đọc
    $unreadNotifications = 0;
    if (auth()->check()) {
        $unreadNotifications = auth()->user()->unreadNotifications->count();
    }

    // Danh mục sản phẩm
    $navCategories = \App\Models\Category::orderBy('sort_order')->get();
@endphp

<nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">XDTHECOFFEEHOUSE</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav"
            aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="oi oi-menu"></span> Menu
        </button>

        <div class="collapse navbar-collapse" id="ftco-nav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
                    <a href="{{ route('home') }}" class="nav-link">Trang chủ</a>
                </li>
                <li class="nav-item {{ request()->routeIs('products.*') ? 'active' : '' }}">
                    <a href="{{ route('products.index') }}" class="nav-link">Sản phẩm</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="javascript:void(0)" id="dropdown04" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">Danh mục</a>
                    <div class="dropdown-menu" aria-labelledby="dropdown04">
                        <a class="dropdown-item" href="{{ route('products.index') }}">Tất cả sản phẩm</a>
                        <div class="dropdown-divider"></div>
                        @foreach($navCategories as $cat)
                            <a class="dropdown-item" href="{{ route('categories.show', $cat->slug) }}">
                                {{ $cat->name }}
                            </a>
                        @endforeach
                    </div>
                </li>

                @auth
                    {{-- Notification Bell --}}
                    <li class="nav-item notification-bell" id="notificationBell">
                        <a class="nav-link" href="javascript:void(0)" id="notifBellBtn" onclick="toggleNotifDropdown(event)">
                            <span class="icon-bell" style="font-size: 18px;"></span>
                            <span class="d-lg-none ml-2">Thông báo</span>
                            <span class="notif-badge" id="notifBadge"
                                style="{{ $unreadNotifications > 0 ? '' : 'display:none;' }}">
                                {{ $unreadNotifications }}
                            </span>
                        </a>
                        <div class="notif-dropdown" id="notifDropdown">
                            <div class="notif-header">
                                <span class="notif-title">Thông báo</span>
                                <a href="#" onclick="markAllRead(event)" class="notif-mark-all">Đọc tất cả</a>
                            </div>
                            <div class="notif-list" id="notifList">
                                <div class="notif-loading">Đang tải...</div>
                            </div>
                        </div>
                    </li>

                    {{-- User Dropdown --}}
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="javascript:void(0)" id="userDropdown" data-toggle="dropdown">
                            @php
                                $avatarUrl = null;
                                $userAvatar = auth()->user()->avatar;
                                if ($userAvatar) {
                                    // Avatar từ Google/Facebook là URL đầy đủ, avatar upload là path local
                                    $avatarUrl = str_starts_with($userAvatar, 'http')
                                        ? $userAvatar
                                        : asset('storage/' . $userAvatar);
                                }
                            @endphp
                            @if($avatarUrl)
                                <img src="{{ $avatarUrl }}" alt="Avatar"
                                    style="width:30px;height:30px;border-radius:50%;object-fit:cover;margin-right:5px;border:1px solid #c49b63;">
                            @endif
                            {{ auth()->user()->name }}
                        </a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="{{ route('profile.show') }}">
                                <i class="icon-person mr-2"></i> Thông tin tài khoản
                            </a>
                            <a class="dropdown-item" href="{{ route('orders.history') }}">
                                <i class="icon-list mr-2"></i> Theo dõi đơn hàng
                            </a>
                            @if(auth()->user()->hasAnyRole(['admin','staff','cashier','warehouse']))
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ url('/admin') }}">
                                    <i class="icon-settings mr-2"></i> Quản trị
                                </a>
                            @endif
                            <div class="dropdown-divider"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="icon-logout mr-2"></i> Đăng xuất
                                </button>
                            </form>
                        </div>
                    </li>
                @else
                    <li class="nav-item {{ request()->routeIs('login') ? 'active' : '' }}">
                        <a href="{{ route('login') }}" class="nav-link">Đăng nhập</a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('register') ? 'active' : '' }}">
                        <a href="{{ route('register') }}" class="nav-link">Đăng ký</a>
                    </li>
                @endauth

                {{-- Cart --}}
                <li class="nav-item cart">
                    <a href="{{ route('cart.index') }}" class="nav-link">
                        <span class="icon icon-shopping_cart"></span>
                        <span class="d-lg-none ml-2">Giỏ hàng</span>
                        <span class="bag d-flex justify-content-center align-items-center">
                            <small>{{ $cartCount }}</small>
                        </span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

{{-- Notification Bell Styles --}}
<style>
.notification-bell { position: relative; }
.notification-bell .nav-link { position: relative; padding: 8px 12px !important; }
.notif-badge {
    position: absolute; top: 4px; right: 2px;
    background: #e74c3c; color: #fff; border-radius: 50%;
    width: 18px; height: 18px; font-size: 10px; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    animation: notifPulse 2s infinite;
}
@keyframes notifPulse {
    0% { box-shadow: 0 0 0 0 rgba(231,76,60,0.5); }
    70% { box-shadow: 0 0 0 8px rgba(231,76,60,0); }
    100% { box-shadow: 0 0 0 0 rgba(231,76,60,0); }
}
.notif-dropdown {
    display: none; position: absolute; top: 100%; right: 0;
    width: 360px; max-height: 420px; background: #fff;
    border-radius: 12px; box-shadow: 0 10px 40px rgba(0,0,0,0.18);
    z-index: 9999; overflow: hidden; border: 1px solid #e8e0d5;
}
.notif-dropdown.show { display: block; animation: notifSlideDown 0.25s ease; }
@keyframes notifSlideDown {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}
.notif-header {
    display: flex; justify-content: space-between; align-items: center;
    padding: 14px 18px; border-bottom: 1px solid #f0e8dd;
    background: linear-gradient(135deg, #6f4e37 0%, #8b6f47 100%);
}
.notif-title { font-weight: 700; font-size: 15px; color: #fff; }
.notif-mark-all { font-size: 12px; color: #f0e8dd; text-decoration: none; cursor: pointer; }
.notif-mark-all:hover { color: #fff; text-decoration: underline; }
.notif-list { max-height: 350px; overflow-y: auto; }
.notif-list::-webkit-scrollbar { width: 4px; }
.notif-list::-webkit-scrollbar-thumb { background: #c49b63; border-radius: 4px; }
.notif-item {
    display: flex; align-items: flex-start; padding: 14px 18px;
    border-bottom: 1px solid #f5f0eb; cursor: pointer;
    transition: background 0.2s; text-decoration: none; color: inherit;
}
.notif-item:hover { background: #fdfaf7; text-decoration: none; color: inherit; }
.notif-item.unread { background: #fffbf0; border-left: 3px solid #c49b63; }
.notif-icon {
    width: 38px; height: 38px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    margin-right: 12px; flex-shrink: 0; font-size: 16px;
}
.notif-content { flex: 1; min-width: 0; }
.notif-content .notif-item-title { font-weight: 600; font-size: 13px; color: #333; margin-bottom: 3px; }
.notif-content .notif-item-desc { font-size: 12px; color: #888; }
.notif-content .notif-time { font-size: 11px; color: #c49b63; margin-top: 4px; font-weight: 500; }
.notif-empty { text-align: center; padding: 40px 20px; color: #aaa; }
.notif-loading { text-align: center; padding: 30px; color: #999; }
@media (max-width: 480px) { .notif-dropdown { width: 300px; right: -50px; } }

/* Collapsed Mobile Navbar styling overrides */
@media (max-width: 991.98px) {
    .navbar-nav {
        padding: 10px 15px !important;
        background: #000000;
        border-radius: 8px;
    }
    .navbar-nav .nav-item {
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        padding: 2px 0;
        width: 100%;
    }
    .navbar-nav .nav-item:last-child {
        border-bottom: none;
    }
    .navbar-nav .nav-link {
        display: flex !important;
        align-items: center !important;
        justify-content: flex-start !important;
        width: 100%;
        padding: 12px 10px !important;
        text-align: left !important;
    }
    .navbar-nav .dropdown-toggle::after {
        margin-left: auto !important;
    }
    .navbar-nav .dropdown-menu {
        background: rgba(255, 255, 255, 0.03) !important;
        border: none !important;
        padding-left: 15px !important;
        margin: 5px 0 !important;
        box-shadow: none !important;
        float: none !important;
        position: static !important;
        width: 100% !important;
    }
    .navbar-nav .dropdown-item {
        color: rgba(255, 255, 255, 0.75) !important;
        padding: 8px 15px !important;
        text-align: left !important;
        display: flex !important;
        align-items: center;
    }
    .notif-badge {
        position: static !important;
        margin-left: 10px !important;
        transform: none !important;
    }
    .nav-item.cart .bag {
        position: static !important;
        margin-left: 10px !important;
        transform: none !important;
        width: 20px !important;
        height: 20px !important;
    }
    .notif-dropdown {
        position: fixed !important;
        top: 60px !important;
        left: 15px !important;
        right: 15px !important;
        width: auto !important;
        max-width: none !important;
        z-index: 10000 !important;
    }
}
</style>

<script>
var notifDropdownOpen = false;

function toggleNotifDropdown(e) {
    e.preventDefault(); e.stopPropagation();
    var dropdown = document.getElementById('notifDropdown');
    if (dropdown.classList.contains('show')) {
        dropdown.classList.remove('show');
        notifDropdownOpen = false;
    } else {
        dropdown.classList.add('show');
        notifDropdownOpen = true;
        loadNotifications();
    }
}

document.addEventListener('click', function(e) {
    var bell = document.getElementById('notificationBell');
    if (bell && !bell.contains(e.target)) {
        var dropdown = document.getElementById('notifDropdown');
        if (dropdown) { dropdown.classList.remove('show'); notifDropdownOpen = false; }
    }
});

function loadNotifications() {
    var list = document.getElementById('notifList');
    if (!list) return;
    list.innerHTML = '<div class="notif-loading">Đang tải...</div>';

    fetch('/api/notifications', {
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        credentials: 'same-origin'
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        if (!data.notifications || data.notifications.length === 0) {
            list.innerHTML = '<div class="notif-empty"><i class="icon-bell"></i><br>Chưa có thông báo</div>';
            return;
        }
        var html = '';
        data.notifications.forEach(function(n) {
            var unreadClass = n.is_unread ? ' unread' : '';
            var title = (n.data && n.data.title) ? n.data.title : 'Thông báo';
            var desc  = (n.data && n.data.message) ? n.data.message : '';
            var icon  = (n.data && n.data.icon) ? n.data.icon : '🔔';
            html += '<div class="notif-item' + unreadClass + '" onclick="markRead(\'' + n.id + '\')">';
            html += '<div class="notif-icon" style="background:#fff8e1;">' + icon + '</div>';
            html += '<div class="notif-content">';
            html += '<div class="notif-item-title">' + title + '</div>';
            if (desc) html += '<div class="notif-item-desc">' + desc + '</div>';
            html += '<div class="notif-time">' + n.time + '</div>';
            html += '</div></div>';
        });
        list.innerHTML = html;

        // Cập nhật badge
        var badge = document.getElementById('notifBadge');
        if (badge) {
            if (data.unread_count > 0) {
                badge.textContent = data.unread_count;
                badge.style.display = 'flex';
            } else {
                badge.style.display = 'none';
            }
        }
    })
    .catch(function() {
        list.innerHTML = '<div class="notif-empty">Lỗi tải thông báo</div>';
    });
}

function markRead(id) {
    fetch('/api/notifications/' + id + '/read', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
        credentials: 'same-origin'
    }).then(function(r) { return r.json(); }).then(function(data) {
        var badge = document.getElementById('notifBadge');
        if (badge) {
            if (data.unread_count > 0) { badge.textContent = data.unread_count; badge.style.display = 'flex'; }
            else { badge.style.display = 'none'; }
        }
    }).catch(function() {});
}

function markAllRead(e) {
    e.preventDefault(); e.stopPropagation();
    fetch('/api/notifications/read-all', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
        credentials: 'same-origin'
    }).then(function() {
        var badge = document.getElementById('notifBadge');
        if (badge) badge.style.display = 'none';
        // Reload list để cập nhật trạng thái
        loadNotifications();
    }).catch(function() {});
}

function updateCartBadge(count) {
    var badge = document.querySelector('.bag small');
    if (badge) badge.textContent = count;
}
</script>
