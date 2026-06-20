@extends('layouts.shop')

@section('title', 'Đăng ký tài khoản')

@section('content')

{{-- Page Header --}}
<section class="hero-page-header" style="background-image: url({{ asset('images/bg_3.jpg') }}); background-size: cover; background-position: center; height: 350px;">
    <div class="overlay"></div>
    <div class="container h-100">
        <div class="row h-100 justify-content-center align-items-center">
                <div class="col-md-7 col-sm-12 text-center ftco-animate">
                    <h1 class="mb-3 mt-5 bread">Đăng ký tài khoản</h1>
                    <p class="breadcrumbs">
                        <span class="mr-2"><a href="{{ route('home') }}">Trang chủ</a></span>
                        <span>Đăng ký</span>
                    </p>
                </div>
        </div>
    </div>
</section>

<section class="ftco-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-6">
                <div class="card shadow-lg border-0" style="border-radius: 16px; overflow: hidden;">

                    {{-- Header card --}}
                    <div class="card-header text-center py-4" style="background: linear-gradient(135deg, #6f4e37 0%, #8b6f47 100%); border: none;">
                        <h3 class="mb-0 text-white font-weight-bold">☕ Tạo tài khoản mới</h3>
                        <p class="text-white-50 mb-0 mt-1 small">Tham gia cộng đồng XDTHECOFFEEHOUSE</p>
                    </div>

                    <div class="card-body p-4 p-md-5">

                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="form-group mb-3">
                                <label class="font-weight-600 mb-1">Họ và tên <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name"
                                    class="form-control form-control-lg @error('name') is-invalid @enderror"
                                    value="{{ old('name') }}" required autofocus
                                    placeholder="Nhập họ và tên đầy đủ"
                                    style="border-radius: 8px; border: 2px solid #e0e0e0;">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label class="font-weight-600 mb-1">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" id="email"
                                    class="form-control form-control-lg @error('email') is-invalid @enderror"
                                    value="{{ old('email') }}" required
                                    placeholder="Nhập địa chỉ email"
                                    style="border-radius: 8px; border: 2px solid #e0e0e0;">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label class="font-weight-600 mb-1">Mật khẩu <span class="text-danger">*</span></label>
                                <input type="password" name="password" id="password"
                                    class="form-control form-control-lg @error('password') is-invalid @enderror"
                                    required autocomplete="new-password"
                                    placeholder="Tối thiểu 8 ký tự"
                                    style="border-radius: 8px; border: 2px solid #e0e0e0;">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-4">
                                <label class="font-weight-600 mb-1">Xác nhận mật khẩu <span class="text-danger">*</span></label>
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    class="form-control form-control-lg"
                                    required autocomplete="new-password"
                                    placeholder="Nhập lại mật khẩu"
                                    style="border-radius: 8px; border: 2px solid #e0e0e0;">
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg btn-block"
                                style="border-radius: 8px; font-weight: 600; letter-spacing: 0.5px;">
                                Tạo tài khoản
                            </button>
                        </form>

                        <div class="text-center mt-4">
                            <p style="color:#555;margin-bottom:0;">Đã có tài khoản?
                                <a href="{{ route('login') }}" style="color: #c49b63; font-weight: 600;">
                                    Đăng nhập ngay
                                </a>
                            </p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
