@extends('layouts.shop')

@section('title', 'Chỉnh sửa thông tin')

@section('content')

<section class="hero-page-header" style="background-image: url({{ asset('images/bg_3.jpg') }}); background-size: cover; background-position: center; height: 350px;">
    <div class="overlay"></div>
    <div class="container h-100">
        <div class="row h-100 justify-content-center align-items-center">
                <div class="col-md-7 col-sm-12 text-center ftco-animate">
                    <h1 class="mb-3 mt-5 bread">Chỉnh sửa thông tin</h1>
                    <p class="breadcrumbs">
                        <span class="mr-2"><a href="{{ route('home') }}">Trang chủ</a></span>
                        <span class="mr-2"><a href="{{ route('profile.show') }}">Tài khoản</a></span>
                        <span>Chỉnh sửa</span>
                    </p>
                </div>
        </div>
    </div>
</section>

<section class="ftco-section profile-section">
    <div class="container">
        <div class="row icon-view-profile justify-content-center">
            <div class="col-md-8">

                {{-- Cập nhật thông tin --}}
                <div class="card mb-4">
                    <div class="card-body p-4">
                        <h3 class="mb-4"><span class="icon-user mr-2"></span> Thông tin cá nhân</h3>

                        <form method="POST" action="{{ route('profile.update') }}">
                            @csrf
                            @method('PATCH')

                            <div class="form-group">
                                <label>Họ và tên <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $user->name) }}" required>
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" class="form-control" value="{{ $user->email }}" disabled>
                                <small class="text-muted">Email không thể thay đổi</small>
                            </div>

                            <div class="form-group">
                                <label>Số điện thoại</label>
                                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                    value="{{ old('phone', $user->phone) }}" placeholder="Nhập số điện thoại">
                                @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="form-group">
                                <label>Địa chỉ</label>
                                <textarea name="address" class="form-control @error('address') is-invalid @enderror"
                                    rows="3" placeholder="Nhập địa chỉ của bạn">{{ old('address', $user->address) }}</textarea>
                                @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('profile.show') }}" class="btn btn-outline-primary py-3 px-4" style="border-radius: 50px;">← Hủy bỏ</a>
                                <button type="submit" class="btn btn-primary py-3 px-5" style="border-radius: 50px;">Lưu thay đổi</button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Đổi mật khẩu --}}
                @if(!$user->provider)
                <div class="card mb-4">
                    <div class="card-body p-4">
                        <h3 class="mb-4"><span class="icon-lock mr-2"></span> Đổi mật khẩu</h3>

                        <form method="POST" action="{{ route('profile.password') }}">
                            @csrf

                            <div class="form-group">
                                <label>Mật khẩu hiện tại <span class="text-danger">*</span></label>
                                <input type="password" name="current_password"
                                    class="form-control @error('current_password') is-invalid @enderror" required>
                                @error('current_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="form-group">
                                <label>Mật khẩu mới <span class="text-danger">*</span></label>
                                <input type="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror" required>
                                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="form-group">
                                <label>Xác nhận mật khẩu mới <span class="text-danger">*</span></label>
                                <input type="password" name="password_confirmation" class="form-control" required>
                            </div>

                            <div class="text-right mt-4">
                                <button type="submit" class="btn btn-primary py-3 px-5" style="border-radius: 50px;">Đổi mật khẩu</button>
                            </div>
                        </form>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

@push('styles')
<style>
body .profile-section .card .form-control {
    background: #ffffff !important;
    border: 1px solid rgba(196, 155, 99, 0.3) !important;
    color: #495057 !important;
    border-radius: 10px !important;
    padding: 12px 15px !important;
    transition: all 0.3s ease !important;
    height: auto !important;
}

body .profile-section .card .form-control:focus {
    background: #ffffff !important;
    border-color: #c49b63 !important;
    box-shadow: 0 0 10px rgba(196, 155, 99, 0.2) !important;
    color: #495057 !important;
}

body .profile-section .card .form-control:disabled,
body .profile-section .card .form-control[readonly] {
    background: #e9ecef !important;
    color: #495057 !important;
    -webkit-text-fill-color: #495057 !important;
    border-color: rgba(196, 155, 99, 0.1) !important;
    cursor: not-allowed !important;
    opacity: 1 !important;
}

.profile-section label {
    color: #c49b63 !important;
    font-weight: 600 !important;
    font-size: 0.9rem !important;
    text-transform: uppercase !important;
    letter-spacing: 0.5px !important;
    margin-bottom: 8px !important;
}

.profile-section .card-body h3 {
    color: #c49b63 !important;
    font-weight: 700 !important;
    letter-spacing: 1px !important;
    border-bottom: 1px solid rgba(196, 155, 99, 0.2) !important;
    padding-bottom: 15px !important;
    margin-bottom: 25px !important;
}

.profile-section .btn-outline-primary {
    border: 1px solid #c49b63 !important;
    color: #c49b63 !important;
    background: transparent !important;
    transition: all 0.3s ease !important;
}

.profile-section .btn-outline-primary:hover {
    background: #c49b63 !important;
    color: #000 !important;
}
</style>
@endpush

@endsection
