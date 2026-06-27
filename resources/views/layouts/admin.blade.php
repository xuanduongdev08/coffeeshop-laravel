<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Quản trị') — XDTHECOFFEEHOUSE</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    {{-- Bootstrap 4 --}}
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    {{-- Ionicons --}}
    <link rel="stylesheet" href="{{ asset('css/ionicons.min.css') }}">
    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <style>
        :root {
            --coffee-dark: #1e130c;
            --coffee: #6f4e37;
            --coffee-light: #8b6f47;
            --coffee-pale: #c49b63;
            --coffee-cream: #f5efe6;
            --sidebar-w: 260px;
            --transition-smooth: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        * { box-sizing: border-box; }
        body {
            font-family: 'Poppins', sans-serif;
            background: #f8f9fa;
            margin: 0;
            -webkit-font-smoothing: antialiased;
        }

        /* ===== Sidebar ===== */
        .admin-sidebar {
            position: fixed; top: 0; left: 0; width: var(--sidebar-w);
            height: 100vh;
            background: linear-gradient(180deg, var(--coffee-dark) 0%, #2c1810 40%, var(--coffee) 100%);
            overflow-y: auto; z-index: 1000;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            scrollbar-width: thin;
            scrollbar-color: rgba(196,155,99,0.3) transparent;
        }
        .admin-sidebar::-webkit-scrollbar { width: 4px; }
        .admin-sidebar::-webkit-scrollbar-thumb { background: rgba(196,155,99,0.3); border-radius: 4px; }

        .sidebar-brand {
            padding: 24px 20px 20px;
            border-bottom: 1px solid rgba(196,155,99,0.15);
        }
        .sidebar-brand h4 {
            color: var(--coffee-pale); font-weight: 700; margin: 0; font-size: 15px;
            letter-spacing: 0.5px;
        }
        .sidebar-brand small { color: rgba(255,255,255,0.4); font-size: 11px; display: block; margin-top: 4px; }

        .sidebar-nav { padding: 12px 0; }

        .sidebar-link {
            display: flex; align-items: center; gap: 12px;
            padding: 11px 20px; color: rgba(255,255,255,0.6);
            text-decoration: none; font-size: 13.5px; font-weight: 500;
            transition: var(--transition-smooth);
            border-left: 3px solid transparent;
            margin: 1px 0;
            position: relative;
        }
        .sidebar-link:hover {
            background: rgba(196,155,99,0.1); color: rgba(255,255,255,0.9);
            border-left-color: rgba(196,155,99,0.4);
            text-decoration: none;
            transform: translateX(3px);
        }
        .sidebar-link.active {
            background: rgba(196,155,99,0.15); color: #fff;
            border-left-color: var(--coffee-pale);
            text-decoration: none;
        }
        .sidebar-link .icon {
            font-size: 16px; width: 20px; text-align: center;
            opacity: 0.7; transition: var(--transition-smooth);
        }
        .sidebar-link:hover .icon, .sidebar-link.active .icon { opacity: 1; color: var(--coffee-pale); }

        .sidebar-badge {
            margin-left: auto; background: #e74c3c; color: #fff;
            border-radius: 10px; padding: 2px 8px; font-size: 11px; font-weight: 700;
            min-width: 20px; text-align: center;
            animation: pulse-badge 2s infinite;
        }
        @keyframes pulse-badge {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .sidebar-divider {
            height: 1px; background: rgba(255,255,255,0.06);
            margin: 8px 20px;
        }

        /* ===== Main Content ===== */
        .admin-main {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
            display: flex; flex-direction: column;
        }

        /* ===== Topbar ===== */
        .admin-topbar {
            background: #fff; padding: 0 24px; height: 60px;
            display: flex; align-items: center; justify-content: space-between;
            box-shadow: 0 1px 8px rgba(0,0,0,0.04);
            position: sticky; top: 0; z-index: 100;
            border-bottom: 1px solid rgba(0,0,0,0.04);
        }
        .admin-topbar h5 { margin: 0; font-weight: 700; color: #2d2522; font-size: 16px; }

        /* Topbar User Dropdown */
        .topbar-user-dropdown { position: relative; }
        .topbar-user-btn {
            display: flex; align-items: center; gap: 10px;
            background: none; border: 1px solid rgba(0,0,0,0.08);
            border-radius: 50px; padding: 5px 14px 5px 5px;
            cursor: pointer; transition: var(--transition-smooth);
        }
        .topbar-user-btn:hover { background: var(--coffee-cream); border-color: var(--coffee-pale); }
        .topbar-user-btn img {
            width: 34px; height: 34px; border-radius: 50%;
            object-fit: cover; border: 2px solid var(--coffee-pale);
        }
        .topbar-user-btn .avatar-placeholder {
            width: 34px; height: 34px; border-radius: 50%;
            background: linear-gradient(135deg, var(--coffee), var(--coffee-pale));
            color: #fff; display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 14px;
        }
        .topbar-user-btn .user-info { text-align: left; }
        .topbar-user-btn .user-name { font-size: 13px; font-weight: 600; color: #2d2522; display: block; line-height: 1.2; }
        .topbar-user-btn .user-role { font-size: 10px; color: #888; display: block; text-transform: capitalize; }
        .topbar-user-btn .dropdown-arrow { font-size: 10px; color: #999; margin-left: 4px; transition: var(--transition-smooth); }
        .topbar-user-btn[aria-expanded="true"] .dropdown-arrow { transform: rotate(180deg); }

        .user-dropdown-menu {
            position: absolute; right: 0; top: calc(100% + 8px);
            background: #fff; border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
            border: 1px solid rgba(0,0,0,0.06);
            min-width: 200px; padding: 6px 0;
            opacity: 0; visibility: hidden; transform: translateY(-8px);
            transition: var(--transition-smooth);
            z-index: 200;
        }
        .user-dropdown-menu.show {
            opacity: 1; visibility: visible; transform: translateY(0);
        }
        .user-dropdown-menu a,
        .user-dropdown-menu button {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 16px; font-size: 13px; color: #444;
            text-decoration: none; transition: var(--transition-smooth);
            width: 100%; border: none; background: none; cursor: pointer; text-align: left;
        }
        .user-dropdown-menu a:hover,
        .user-dropdown-menu button:hover {
            background: var(--coffee-cream); color: var(--coffee);
        }
        .user-dropdown-menu .dropdown-divider-custom {
            height: 1px; background: #f0f0f0; margin: 4px 12px;
        }
        .user-dropdown-menu .icon { width: 18px; text-align: center; font-size: 14px; opacity: 0.6; }

        /* ===== Content Area ===== */
        .admin-content { padding: 24px; flex: 1; }

        /* ===== Cards ===== */
        .stat-card {
            background: #fff; border-radius: 14px; padding: 24px 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            border-left: 5px solid var(--coffee-pale);
            transition: var(--transition-smooth);
            position: relative;
            overflow: hidden;
        }
        .stat-card:hover { 
            transform: translateY(-4px); 
            box-shadow: 0 8px 25px rgba(0,0,0,0.08); 
        }
        .stat-card .stat-value { font-size: 30px; font-weight: 700; color: var(--coffee); line-height: 1.2; }
        .stat-card .stat-label { font-size: 13px; font-weight: 600; color: #7f8c8d; margin-top: 6px; text-transform: uppercase; letter-spacing: 0.5px; }
        
        /* Card Specific Styles */
        .stat-card.card-orders { border-left-color: var(--coffee-pale); background: linear-gradient(135deg, #ffffff 0%, #fffbf6 100%); }
        .stat-card.card-pending { border-left-color: #e74c3c; background: linear-gradient(135deg, #ffffff 0%, #fffbfb 100%); }
        .stat-card.card-revenue { border-left-color: #27ae60; background: linear-gradient(135deg, #ffffff 0%, #fbfdfa 100%); }
        .stat-card.card-customers { border-left-color: #3498db; background: linear-gradient(135deg, #ffffff 0%, #fafcff 100%); }
        .stat-card.card-products { border-left-color: #9b59b6; background: linear-gradient(135deg, #ffffff 0%, #fcfaff 100%); }
        .stat-card.card-warning { border-left-color: #e67e22; background: linear-gradient(135deg, #ffffff 0%, #fffbfa 100%); }

        /* ===== Tables ===== */
        .admin-table {
            background: #fff; border-radius: 12px; overflow: hidden;
            box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        }
        .admin-table .table { margin: 0; }
        .admin-table .table thead th {
            background: var(--coffee); color: #fff; border: none;
            font-weight: 600; font-size: 13px; padding: 12px 16px;
            white-space: nowrap;
            vertical-align: middle;
        }
        .admin-table .table tbody td {
            padding: 12px 16px; vertical-align: middle;
            border-color: #f5f5f5; font-size: 13px;
        }
        .admin-table .table tbody tr { transition: var(--transition-smooth); }
        .admin-table .table tbody tr:hover { background: #fdfaf7; }

        /* ===== Badges ===== */
        .badge-pending   { background: #fff3cd; color: #856404; }
        .badge-paid      { background: #d1e7dd; color: #0f5132; }
        .badge-failed    { background: #f8d7da; color: #842029; }
        .badge-brewing   { background: #cff4fc; color: #055160; }
        .badge-completed { background: #d1e7dd; color: #0f5132; }

        /* ===== Buttons ===== */
        .btn-coffee {
            background: var(--coffee); color: #fff; border: none;
            transition: var(--transition-smooth);
        }
        .btn-coffee:hover { background: var(--coffee-light); color: #fff; transform: translateY(-1px); }

        /* ===== Forms & Cards ===== */
        .admin-card {
            background: #fff; border-radius: 12px; padding: 24px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        }
        .admin-card h5 {
            color: var(--coffee); font-weight: 700; margin-bottom: 20px;
            padding-bottom: 12px; border-bottom: 2px solid #f0e8dd;
        }

        /* ===== Alerts ===== */
        .alert { border-radius: 10px; border: none; font-size: 13.5px; }

        /* ===== Responsive ===== */
        @media (max-width: 768px) {
            .admin-sidebar { transform: translateX(-100%); }
            .admin-sidebar.open { transform: translateX(0); }
            .admin-main { margin-left: 0; }
            .topbar-user-btn .user-info { display: none; }
        }
    </style>
    @stack('styles')
</head>
<body>

{{-- Sidebar --}}
<aside class="admin-sidebar" id="adminSidebar">
    <div class="sidebar-brand">
        <h4><span class="ion-md-cafe" style="margin-right:6px;"></span>XDTHECOFFEEHOUSE</h4>
        <small>Trang quản trị</small>
    </div>
    <nav class="sidebar-nav">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <span class="icon ion-md-speedometer"></span> Quản lý Dashboard
        </a>
        <a href="{{ route('admin.products.index') }}" class="sidebar-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
            <span class="icon ion-md-cafe"></span> Quản lý sản phẩm
        </a>
        <a href="{{ route('admin.categories.index') }}" class="sidebar-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
            <span class="icon ion-md-folder"></span> Quản lý danh mục
        </a>
        <a href="{{ route('admin.orders.index') }}" class="sidebar-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
            <span class="icon ion-md-clipboard"></span> Quản lý đơn hàng
            @php $pendingCount = \App\Models\Order::where('status','Chờ xử lý')->count(); @endphp
            @if($pendingCount > 0)
                <span class="sidebar-badge">{{ $pendingCount }}</span>
            @endif
        </a>

        <div class="sidebar-divider"></div>

        @role('admin')
        <a href="{{ route('admin.customers.index') }}" class="sidebar-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
            <span class="icon ion-md-people"></span> Quản lý khách hàng
        </a>
        <a href="{{ route('admin.employees.index') }}" class="sidebar-link {{ request()->routeIs('admin.employees.*') ? 'active' : '' }}">
            <span class="icon ion-md-person"></span> Quản lý nhân viên
        </a>

        <div class="sidebar-divider"></div>
        @endrole

        @role('admin|cashier')
        <a href="{{ route('admin.statistics.index') }}" class="sidebar-link {{ request()->routeIs('admin.statistics.*') ? 'active' : '' }}">
            <span class="icon ion-md-stats"></span> Thống kê doanh thu
        </a>
        @endrole

        @role('admin')
        <a href="{{ route('admin.email-templates.index') }}" class="sidebar-link {{ request()->routeIs('admin.email-templates.*') ? 'active' : '' }}">
            <span class="icon ion-md-mail"></span> Email Template
        </a>
        @endrole

        <div class="sidebar-divider"></div>

        <a href="{{ route('home') }}" class="sidebar-link" target="_blank">
            <span class="icon ion-md-globe"></span> Xem website
        </a>
    </nav>
</aside>

{{-- Main --}}
<div class="admin-main">
    {{-- Topbar --}}
    <div class="admin-topbar">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-sm d-md-none" onclick="document.getElementById('adminSidebar').classList.toggle('open')" style="background:none;border:1px solid #ddd;border-radius:8px;padding:6px 10px;">
                <span class="ion-md-menu" style="font-size:18px;"></span>
            </button>
            <h5>@yield('page-title', 'Dashboard')</h5>
        </div>
        <div class="topbar-user-dropdown">
            <button class="topbar-user-btn" id="userDropdownBtn" aria-expanded="false">
                @if(auth()->user()->avatar)
                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Avatar">
                @else
                    <div class="avatar-placeholder">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                @endif
                <div class="user-info">
                    <span class="user-name">{{ auth()->user()->name }}</span>
                    <span class="user-role">{{ auth()->user()->roles->first()?->name ?? 'admin' }}</span>
                </div>
                <span class="dropdown-arrow ion-md-arrow-dropdown"></span>
            </button>
            <div class="user-dropdown-menu" id="userDropdownMenu">
                <a href="{{ route('admin.profile.edit') }}">
                    <span class="icon ion-md-contact"></span> Hồ sơ cá nhân
                </a>
                <div class="dropdown-divider-custom"></div>
                <form method="POST" action="{{ route('admin.logout') }}" style="margin:0;">
                    @csrf
                    <button type="submit">
                        <span class="icon ion-md-log-out"></span> Đăng xuất
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Flash Messages are now handled by SweetAlert2 below --}}

    {{-- Content --}}
    <div class="admin-content">
        @yield('content')
    </div>
</div>

{{-- JS --}}
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script>
var csrfToken = '{{ csrf_token() }}';

// User Dropdown Toggle
(function() {
    var btn = document.getElementById('userDropdownBtn');
    var menu = document.getElementById('userDropdownMenu');
    if (!btn || !menu) return;

    btn.addEventListener('click', function(e) {
        e.stopPropagation();
        var isOpen = menu.classList.contains('show');
        menu.classList.toggle('show');
        btn.setAttribute('aria-expanded', !isOpen);
    });

    document.addEventListener('click', function(e) {
        if (!btn.contains(e.target) && !menu.contains(e.target)) {
            menu.classList.remove('show');
            btn.setAttribute('aria-expanded', 'false');
        }
    });
})();

// ===== SweetAlert2 Toast Notifications =====
(function() {
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000,
        timerProgressBar: true,
        showCloseButton: true,
        didOpen: function(toast) {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
        },
        customClass: {
            popup: 'swal-toast-custom'
        }
    });

    @if(session('success'))
        Toast.fire({
            icon: 'success',
            title: '{{ addslashes(session("success")) }}'
        });
    @endif

    @if(session('error'))
        Toast.fire({
            icon: 'error',
            title: '{{ addslashes(session("error")) }}'
        });
    @endif

    @if(session('warning'))
        Toast.fire({
            icon: 'warning',
            title: '{{ addslashes(session("warning")) }}'
        });
    @endif

    @if(session('info'))
        Toast.fire({
            icon: 'info',
            title: '{{ addslashes(session("info")) }}'
        });
    @endif

    @if($errors->any())
        var errorHtml = '<ul style="text-align:left; margin-bottom:0; font-size:14px; padding-left:20px;">';
        @foreach($errors->all() as $error)
            errorHtml += '<li>{{ addslashes($error) }}</li>';
        @endforeach
        errorHtml += '</ul>';

        Swal.fire({
            icon: 'error',
            title: 'Lỗi nhập liệu!',
            html: errorHtml,
            confirmButtonText: 'Đóng',
            confirmButtonColor: '#d33'
        });
    @endif
})();

// ===== Confirm Delete with SweetAlert2 =====
document.addEventListener('click', function(e) {
    var deleteBtn = e.target.closest('.btn-delete-confirm');
    if (!deleteBtn) return;
    e.preventDefault();
    var form = deleteBtn.closest('form');
    var itemName = deleteBtn.getAttribute('data-name') || 'mục này';
    Swal.fire({
        title: 'Xác nhận xóa?',
        html: 'Bạn có chắc chắn muốn xóa <strong>' + itemName + '</strong>?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e74c3c',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Xóa',
        cancelButtonText: 'Hủy',
        customClass: {
            popup: 'swal-custom-popup'
        }
    }).then(function(result) {
        if (result.isConfirmed && form) {
            form.submit();
        }
    });
});
</script>
@stack('scripts')
</body>
</html>
