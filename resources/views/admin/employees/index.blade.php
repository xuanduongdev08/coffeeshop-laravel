@extends('layouts.admin')

@section('title', 'Quản lý nhân viên')
@section('page-title', 'Quản lý nhân viên')

@section('content')

@if(auth()->user()->hasRole('admin'))
<div class="d-flex justify-content-between align-items-center mb-4">
    <div></div>
    <a href="{{ route('admin.employees.create') }}" class="btn btn-coffee">+ Thêm nhân viên</a>
</div>
@endif

{{-- Filters --}}
<div class="admin-card mb-4">
    <form method="GET" class="row align-items-end">
        <div class="col-md-5 mb-2">
            <input type="text" name="search" class="form-control form-control-sm"
                placeholder="Tìm tên, email, SĐT..." value="{{ request('search') }}">
        </div>
        <div class="col-md-3 mb-2">
            <select name="role" class="form-control form-control-sm">
                <option value="">Tất cả vai trò nhân viên</option>
                @foreach($roles as $role)
                    @php
                        $roleLabel = match($role->name) {
                            'admin' => 'Quản lý',
                            'cashier' => 'Thu ngân',
                            'staff' => 'Nhân viên phục vụ',
                            default => $role->name
                        };
                    @endphp
                    <option value="{{ $role->name }}" {{ request('role') === $role->name ? 'selected' : '' }}>
                        {{ $roleLabel }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 mb-2">
            <button type="submit" class="btn btn-sm btn-coffee w-100"><span class="ion-md-search" style="margin-right:4px;"></span>Lọc</button>
        </div>
    </form>
</div>

<div class="admin-table">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Avatar</th>
                    <th>Tên nhân viên</th>
                    <th>Email</th>
                    <th>Số điện thoại</th>
                    <th>Vai trò</th>
                    <th>Ngày tạo</th>
                    <th style="width: 220px;">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>
                            @if($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}" alt=""
                                    style="width:38px;height:38px;border-radius:50%;object-fit:cover;">
                            @else
                                <div style="width:38px;height:38px;border-radius:50%;background:#6f4e37;color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:14px;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endif
                        </td>
                        <td>
                            <strong>{{ $user->name }}</strong>
                            @if($user->id === auth()->id())
                                <span class="badge badge-warning ml-1">Bạn</span>
                            @endif
                        </td>
                        <td style="font-size:13px;">{{ $user->email }}</td>
                        <td style="font-size:13px;">{{ $user->phone ?? '—' }}</td>
                        <td>
                            @foreach($user->roles as $role)
                                @php
                                    $roleColor = match($role->name) {
                                        'admin'     => 'danger',
                                        'staff'     => 'warning',
                                        'cashier'   => 'info',
                                        default     => 'secondary',
                                    };
                                    $roleLabel = match($role->name) {
                                        'admin'     => 'Quản lý',
                                        'staff'     => 'Nhân viên phục vụ',
                                        'cashier'   => 'Thu ngân',
                                        default     => $role->name,
                                    };
                                @endphp
                                <span class="badge badge-{{ $roleColor }}">{{ $roleLabel }}</span>
                            @endforeach
                        </td>
                        <td style="font-size:12px;color:#888;">{{ $user->created_at->format('d/m/Y') }}</td>
                        <td style="text-align: center;">
                            <div class="d-flex align-items-center justify-content-center" style="gap: 6px;">
                                <a href="{{ route('admin.employees.show', $user) }}" class="btn btn-sm btn-outline-secondary" style="font-size:11px; padding: 4px 10px; border-radius: 6px; display: inline-flex; align-items: center; gap: 4px;">
                                    <span class="ion-md-eye"></span> Chi tiết
                                </a>
                                @if($user->id !== auth()->id())
                                    <button type="button" class="btn btn-sm btn-outline-primary" style="font-size:11px; padding: 4px 10px; border-radius: 6px; display: inline-flex; align-items: center; gap: 4px;"
                                        onclick="openRoleModal({{ $user->id }}, '{{ $user->name }}', '{{ $user->roles->first()?->name }}')">
                                        <span class="ion-md-key"></span> Phân quyền
                                    </button>
                                    <form action="{{ route('admin.employees.destroy', $user) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-outline-danger btn-delete-confirm" data-name="{{ $user->name }}" style="font-size:11px; padding: 4px 10px; border-radius: 6px; display: inline-flex; align-items: center; gap: 4px;">
                                            <span class="ion-md-trash"></span> Xóa
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center py-4 text-muted">Không có nhân viên nào</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
        <div class="p-3">{{ $users->links() }}</div>
    @endif
</div>

{{-- Modal phân quyền --}}
<div class="modal fade" id="roleModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header" style="background:linear-gradient(135deg,#6f4e37,#8b6f47);">
                <h5 class="modal-title text-white">🔐 Phân quyền nhân viên</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form id="roleForm" method="POST">
                @csrf @method('PATCH')
                <div class="modal-body">
                    <p class="mb-1" style="font-size:13px;color:#888;">Nhân viên:</p>
                    <p id="roleModalUser" class="font-weight-bold mb-3"></p>
                    <div class="form-group mb-2">
                        <label style="font-size:13px;font-weight:600;">Vai trò mới</label>
                        <select name="role" id="roleSelect" class="form-control">
                            @foreach($roles as $role)
                                @php
                                    $roleLabel = match($role->name) {
                                        'admin' => 'Quản lý',
                                        'cashier' => 'Thu ngân',
                                        'staff' => 'Nhân viên phục vụ',
                                        default => $role->name
                                    };
                                    $roleDesc = match($role->name) {
                                        'admin'     => 'Toàn quyền quản trị',
                                        'staff'     => 'Xem sản phẩm, đơn hàng & Cập nhật pha chế',
                                        'cashier'   => 'Xem sản phẩm & Xử lý đơn hàng, thanh toán',
                                        default     => ''
                                    };
                                @endphp
                                <option value="{{ $role->name }}">{{ $roleLabel }} — {{ $roleDesc }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div style="background:#fff8e1;border-radius:8px;padding:10px;font-size:12px;color:#856404;">
                        ⚠️ Thay đổi vai trò sẽ ảnh hưởng đến quyền truy cập ngay lập tức.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-coffee btn-sm">💾 Lưu phân quyền</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function openRoleModal(userId, userName, currentRole) {
    document.getElementById('roleModalUser').textContent = userName;
    document.getElementById('roleForm').action = '/admin/employees/' + userId + '/role';
    var select = document.getElementById('roleSelect');
    for (var i = 0; i < select.options.length; i++) {
        if (select.options[i].value === currentRole) {
            select.selectedIndex = i;
            break;
        }
    }
    $('#roleModal').modal('show');
}
</script>
@endpush
