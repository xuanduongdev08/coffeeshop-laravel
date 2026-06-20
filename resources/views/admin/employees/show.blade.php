@extends('layouts.admin')

@section('title', 'Nhân viên: ' . $user->name)
@section('page-title', 'Chi tiết nhân viên')

@section('content')

<div class="row">
    <div class="col-md-4">
        <div class="admin-card mb-4 text-center">
            @if($user->avatar)
                <img src="{{ asset('storage/' . $user->avatar) }}" alt=""
                    style="width:80px;height:80px;border-radius:50%;object-fit:cover;border:3px solid #c49b63;margin-bottom:12px;">
            @else
                <div style="width:80px;height:80px;border-radius:50%;background:#6f4e37;color:#fff;display:flex;align-items:center;justify-content:center;font-size:32px;font-weight:700;margin:0 auto 12px;">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
            @endif
            <h5 class="mb-1">{{ $user->name }}</h5>
            <p class="text-muted mb-2" style="font-size:13px;">{{ $user->email }}</p>
            @foreach($user->roles as $role)
                @php
                    $roleColor = match($role->name) {
                        'admin'     => 'danger',
                        'staff'     => 'warning',
                        'cashier'   => 'info',
                        'warehouse' => 'secondary',
                        default     => 'secondary',
                    };
                @endphp
                <span class="badge badge-{{ $roleColor }}">{{ ucfirst($role->name) }}</span>
            @endforeach
            <hr>
            <div class="text-left" style="font-size:13px;">
                <p><strong>Số điện thoại:</strong> {{ $user->phone ?? '—' }}</p>
                <p><strong>Địa chỉ:</strong> {{ $user->address ?? '—' }}</p>
                <p><strong>Ngày tạo:</strong> {{ $user->created_at->format('d/m/Y H:i') }}</p>
            </div>

            @if($user->id !== auth()->id())
                <hr>
                {{-- Phân quyền nhanh --}}
                <div class="text-left">
                    <p class="font-weight-bold mb-2" style="font-size:13px;">🔐 Phân quyền</p>
                    <form action="{{ route('admin.employees.role', $user) }}" method="POST">
                        @csrf @method('PATCH')
                        <div class="form-group mb-2">
                            <select name="role" class="form-control form-control-sm">
                                @foreach(['admin', 'staff', 'cashier', 'warehouse'] as $roleName)
                                    <option value="{{ $roleName }}" {{ $user->hasRole($roleName) ? 'selected' : '' }}>
                                        {{ ucfirst($roleName) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-coffee btn-sm btn-block">💾 Cập nhật vai trò</button>
                    </form>
                </div>
                <hr>
                <form action="{{ route('admin.employees.destroy', $user) }}" method="POST">
                    @csrf @method('DELETE')
                    <button type="button" class="btn btn-danger btn-sm btn-block btn-delete-confirm" data-name="{{ $user->name }}">🗑️ Xóa tài khoản</button>
                </form>
            @else
                <hr>
                <div class="alert alert-info" style="font-size:12px;padding:8px;">
                    Đây là tài khoản của bạn, không thể tự phân quyền hoặc xóa.
                </div>
            @endif
        </div>
    </div>

    <div class="col-md-8">
        {{-- Thông tin quyền hạn --}}
        <div class="admin-card mb-4">
            <h5>🔐 Quyền hạn theo vai trò</h5>
            @php
                $currentRole = $user->roles->first()?->name ?? '';
                $permissions = [
                    'admin'     => ['Toàn quyền quản trị hệ thống', 'Quản lý sản phẩm & danh mục', 'Quản lý đơn hàng', 'Quản lý người dùng', 'Xem thống kê & xuất báo cáo'],
                    'staff'     => ['Quản lý sản phẩm & danh mục', 'Xem & chỉnh sửa đơn hàng', 'Xem danh sách khách hàng'],
                    'cashier'   => ['Xem sản phẩm', 'Xem & chỉnh sửa đơn hàng'],
                    'warehouse' => ['Xem & chỉnh sửa sản phẩm', 'Xem đơn hàng'],
                ];
                $permList = $permissions[$currentRole] ?? [];
            @endphp
            @if($permList)
                <ul class="mb-0" style="font-size:13px;">
                    @foreach($permList as $perm)
                        <li>✅ {{ $perm }}</li>
                    @endforeach
                </ul>
            @else
                <p class="text-muted" style="font-size:13px;">Chưa xác định quyền hạn</p>
            @endif
        </div>

        {{-- Đơn hàng liên quan --}}
        <div class="admin-card">
            <h5>📋 Đơn hàng liên quan ({{ $user->orders->count() }} đơn)</h5>
            @if($user->orders->count() > 0)
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead style="background:#f8f9fa;">
                            <tr>
                                <th>Mã đơn</th>
                                <th>Tổng tiền</th>
                                <th>Thanh toán</th>
                                <th>Trạng thái</th>
                                <th>Ngày đặt</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($user->orders->take(10) as $order)
                                <tr>
                                    <td><strong style="color:#c49b63;">{{ $order->tracking_code }}</strong></td>
                                    <td>{{ number_format($order->total, 0, ',', '.') }}đ</td>
                                    <td>
                                        <span class="badge badge-{{ $order->payment_status === 'paid' ? 'success' : 'warning' }}">
                                            {{ $order->payment_status === 'paid' ? 'Đã TT' : 'Chờ TT' }}
                                        </span>
                                    </td>
                                    <td><span class="badge badge-secondary">{{ $order->status }}</span></td>
                                    <td style="font-size:12px;">{{ $order->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-secondary" style="font-size:11px;">Xem</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted text-center py-3">Chưa có đơn hàng nào</p>
            @endif
        </div>
    </div>
</div>

<div class="mt-3">
    <a href="{{ route('admin.employees.index') }}" class="btn btn-outline-secondary btn-sm">← Quay lại</a>
</div>

@endsection
