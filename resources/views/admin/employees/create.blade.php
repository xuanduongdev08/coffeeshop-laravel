@extends('layouts.admin')

@section('title', 'Thêm nhân viên mới')
@section('page-title', 'Thêm nhân viên mới')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="admin-card">
            <h5 class="mb-4">Thông tin nhân viên mới</h5>
            <form action="{{ route('admin.employees.store') }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label for="name" class="font-weight-600">Họ và tên <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required placeholder="Nhập họ tên nhân viên">
                    @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="form-group">
                    <label for="email" class="font-weight-600">Địa chỉ Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required placeholder="email@coffeeshop.com">
                    @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password" class="font-weight-600">Mật khẩu <span class="text-danger">*</span></label>
                            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required placeholder="Tối thiểu 8 ký tự">
                            @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password_confirmation" class="font-weight-600">Xác nhận mật khẩu <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required placeholder="Nhập lại mật khẩu">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="phone" class="font-weight-600">Số điện thoại</label>
                            <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="Nhập số điện thoại">
                            @error('phone') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="role" class="font-weight-600">Vai trò <span class="text-danger">*</span></label>
                            <select name="role" id="role" class="form-control @error('role') is-invalid @enderror" required>
                                <option value="">-- Chọn vai trò --</option>
                                @foreach($roles as $role)
                                    @php
                                        $roleLabel = match($role->name) {
                                            'admin' => 'Quản lý',
                                            'cashier' => 'Thu ngân',
                                            'staff' => 'Nhân viên phục vụ',
                                            default => $role->name
                                        };
                                    @endphp
                                    <option value="{{ $role->name }}" {{ old('role') === $role->name ? 'selected' : '' }}>
                                        {{ $roleLabel }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-coffee" style="border-radius:8px;padding:8px 24px;">
                        💾 Thêm nhân viên
                    </button>
                    <a href="{{ route('admin.employees.index') }}" class="btn btn-light" style="border-radius:8px;padding:8px 24px;border:1px solid #ddd;">
                        Quay lại
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
