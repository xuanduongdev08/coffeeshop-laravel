@extends('layouts.shop')

@section('title', 'Đăng nhập')

@section('content')

{{-- Page Header --}}
<section class="hero-page-header" style="background-image: url({{ asset('images/bg_3.jpg') }}); background-size: cover; background-position: center; height: 350px;">
    <div class="overlay"></div>
    <div class="container h-100">
        <div class="row h-100 justify-content-center align-items-center">
                <div class="col-md-7 col-sm-12 text-center ftco-animate">
                    <h1 class="mb-3 mt-5 bread">Đăng nhập</h1>
                    <p class="breadcrumbs">
                        <span class="mr-2"><a href="{{ route('home') }}">Trang chủ</a></span>
                        <span>Đăng nhập</span>
                    </p>
                </div>
        </div>
    </div>
</section>

<section class="ftco-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-lg border-0" style="border-radius: 16px; overflow: hidden;">

                    {{-- Header card --}}
                    <div class="card-header text-center py-4" style="background: linear-gradient(135deg, #6f4e37 0%, #8b6f47 100%); border: none;">
                        <h3 class="mb-0 text-white font-weight-bold">☕ Chào mừng trở lại</h3>
                        <p class="text-white-50 mb-0 mt-1 small">Đăng nhập để tiếp tục mua sắm</p>
                    </div>

                    <div class="card-body p-4 p-md-5">

                        {{-- Session Status --}}
                        @if (session('status'))
                            <div class="alert alert-success mb-4">{{ session('status') }}</div>
                        @endif

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="form-group mb-3">
                                <label class="font-weight-600 mb-1">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" id="email"
                                    class="form-control form-control-lg @error('email') is-invalid @enderror"
                                    value="{{ old('email') }}" required autofocus
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
                                    required autocomplete="current-password"
                                    placeholder="Nhập mật khẩu"
                                    style="border-radius: 8px; border: 2px solid #e0e0e0;">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <label class="d-flex align-items-center mb-0" style="cursor: pointer;">
                                    <input type="checkbox" name="remember" id="remember_me" class="mr-2">
                                    <span class="small" style="color:#6f4e37;">Ghi nhớ đăng nhập</span>
                                </label>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="small" style="color: #c49b63;">
                                        Quên mật khẩu?
                                    </a>
                                @endif
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg btn-block"
                                style="border-radius: 8px; font-weight: 600; letter-spacing: 0.5px;">
                                Đăng nhập
                            </button>
                        </form>

                        <div class="text-center mt-4">
                            <p style="color:#555;margin-bottom:0;">Chưa có tài khoản?
                                <a href="{{ route('register') }}" style="color: #c49b63; font-weight: 600;">
                                    Đăng ký ngay
                                </a>
                            </p>
                        </div>

                        {{-- Divider --}}
                        <div class="d-flex align-items-center my-4">
                            <hr style="flex:1;border-color:#e0e0e0;">
                            <span class="mx-3 small" style="color:#888;">hoặc đăng nhập với</span>
                            <hr style="flex:1;border-color:#e0e0e0;">
                        </div>

                        {{-- Social Login --}}
                        <div class="d-flex" style="gap:12px;">
                            <a href="{{ route('auth.google') }}"
                               class="btn btn-outline-danger d-flex align-items-center justify-content-center"
                               style="border-radius:8px;font-weight:600;padding:10px 16px;flex:1;gap:8px;min-height:46px;">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="flex-shrink:0;">
                                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                                </svg>
                                Đăng nhập với Google
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
