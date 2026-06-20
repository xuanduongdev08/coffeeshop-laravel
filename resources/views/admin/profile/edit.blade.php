@extends('layouts.admin')

@section('title', 'Hồ sơ cá nhân')
@section('page-title', 'Hồ sơ cá nhân')

@section('content')
<div class="row">
    {{-- Thông tin cá nhân --}}
    <div class="col-lg-4 mb-4">
        <div class="admin-card text-center">
            <div style="margin-bottom: 20px;">
                @if($user->avatar)
                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar"
                         style="width:100px;height:100px;border-radius:50%;object-fit:cover;border:3px solid var(--coffee-pale);">
                @else
                    <div style="width:100px;height:100px;border-radius:50%;background:linear-gradient(135deg, var(--coffee), var(--coffee-pale));color:#fff;display:inline-flex;align-items:center;justify-content:center;font-size:36px;font-weight:700;">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif
            </div>
            <h5 style="border:none;padding:0;margin:0 0 4px;">{{ $user->name }}</h5>
            <p style="color:#888;font-size:13px;margin:0 0 12px;">{{ $user->email }}</p>
            <span class="badge" style="background:var(--coffee-pale);color:#fff;font-size:12px;padding:4px 12px;border-radius:20px;">
                {{ $user->roles->first()?->name ?? 'admin' }}
            </span>

            {{-- Upload Avatar --}}
            <form action="{{ route('admin.profile.avatar') }}" method="POST" enctype="multipart/form-data" class="mt-4">
                @csrf
                <div class="form-group">
                    <label for="avatar" style="font-size:13px;color:#666;">Đổi ảnh đại diện</label>
                    <input type="file" name="avatar" id="avatar" class="form-control-file" accept="image/*" style="font-size:13px;">
                    @error('avatar') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <button type="submit" class="btn btn-coffee btn-sm btn-block" style="border-radius:8px;">
                    Cập nhật avatar
                </button>
            </form>
        </div>
    </div>

    {{-- Chỉnh sửa thông tin --}}
    <div class="col-lg-8">
        {{-- Form thông tin cá nhân --}}
        <div class="admin-card mb-4">
            <h5>Thông tin cá nhân</h5>
            <form action="{{ route('admin.profile.update') }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="form-group">
                    <label for="name" style="font-size:13px;font-weight:600;">Họ và tên</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" required style="border-radius:8px;">
                    @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="form-group">
                    <label for="email" style="font-size:13px;font-weight:600;">Email</label>
                    <input type="email" class="form-control" value="{{ $user->email }}" disabled style="border-radius:8px;background:#f8f9fa;">
                    <small class="text-muted">Email đăng nhập không thể thay đổi tại đây.</small>
                </div>
                <div class="form-group">
                    <label for="phone" style="font-size:13px;font-weight:600;">Số điện thoại</label>
                    <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $user->phone) }}" placeholder="Nhập số điện thoại" style="border-radius:8px;">
                    @error('phone') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="form-group">
                    <label for="address" style="font-size:13px;font-weight:600;">Địa chỉ</label>
                    <input type="text" name="address" id="address" class="form-control" value="{{ old('address', $user->address) }}" placeholder="Nhập địa chỉ" style="border-radius:8px;">
                    @error('address') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="form-group">
                    <label style="font-size:13px;font-weight:600;">Vai trò</label>
                    <input type="text" class="form-control" value="{{ $user->roles->pluck('name')->implode(', ') ?: 'admin' }}" disabled style="border-radius:8px;background:#f8f9fa;">
                    <small class="text-muted">Vai trò chỉ được thay đổi bởi Admin tại mục Quản lý nhân viên.</small>
                </div>
                <button type="submit" class="btn btn-coffee" style="border-radius:8px;padding:8px 24px;">
                    Lưu thay đổi
                </button>
            </form>
        </div>

        {{-- Form đổi mật khẩu --}}
        <div class="admin-card">
            <h5>Đổi mật khẩu</h5>
            <form action="{{ route('admin.profile.password') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="current_password" style="font-size:13px;font-weight:600;">Mật khẩu hiện tại</label>
                    <input type="password" name="current_password" id="current_password" class="form-control" required style="border-radius:8px;">
                    @error('current_password') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="form-group">
                    <label for="password" style="font-size:13px;font-weight:600;">Mật khẩu mới</label>
                    <input type="password" name="password" id="password" class="form-control" required style="border-radius:8px;">
                    @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="form-group">
                    <label for="password_confirmation" style="font-size:13px;font-weight:600;">Xác nhận mật khẩu mới</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required style="border-radius:8px;">
                </div>
                <button type="submit" class="btn btn-coffee" style="border-radius:8px;padding:8px 24px;">
                    Đổi mật khẩu
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
