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

<section class="ftco-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                {{-- Cập nhật thông tin --}}
                <div class="card mb-4">
                    <div class="card-body p-4">
                        <h4 class="mb-4" style="color:#6f4e37;">👤 Thông tin cá nhân</h4>

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
                                <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary">← Hủy bỏ</a>
                                <button type="submit" class="btn btn-primary px-5">Lưu thay đổi</button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Đổi mật khẩu --}}
                @if(!$user->provider)
                <div class="card mb-4">
                    <div class="card-body p-4">
                        <h4 class="mb-4" style="color:#6f4e37;">🔒 Đổi mật khẩu</h4>

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
                                <button type="submit" class="btn btn-warning px-5">Đổi mật khẩu</button>
                            </div>
                        </form>
                    </div>
                </div>
                @endif

                {{-- Xóa tài khoản --}}
                <div class="card border-danger">
                    <div class="card-body p-4">
                        <h4 class="mb-2 text-danger">⚠️ Vùng nguy hiểm</h4>
                        <p class="text-muted mb-3">Xóa tài khoản sẽ xóa toàn bộ dữ liệu của bạn và không thể khôi phục.</p>

                        <button type="button" class="btn btn-outline-danger" data-toggle="modal" data-target="#deleteModal">
                            Xóa tài khoản
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Delete Account Modal --}}
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger">⚠️ Xác nhận xóa tài khoản</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa tài khoản? Hành động này <strong>không thể hoàn tác</strong>.</p>
                <form method="POST" action="{{ route('profile.destroy') }}" id="delete-account-form">
                    @csrf
                    @method('DELETE')
                    <div class="form-group">
                        <label>Nhập mật khẩu để xác nhận:</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                <button type="submit" form="delete-account-form" class="btn btn-danger">Xóa tài khoản</button>
            </div>
        </div>
    </div>
</div>

@endsection
