@extends('layouts.shop')

@section('title', 'Đặt lại mật khẩu')

@section('content')

<section class="hero-page-header" style="background-image: url({{ asset('images/bg_3.jpg') }}); background-size: cover; background-position: center; height: 350px;">
    <div class="overlay"></div>
    <div class="container h-100">
        <div class="row h-100 justify-content-center align-items-center">
                <div class="col-md-7 col-sm-12 text-center ftco-animate">
                    <h1 class="mb-3 mt-5 bread">Đặt lại mật khẩu</h1>
                    <p class="breadcrumbs">
                        <span class="mr-2"><a href="{{ route('home') }}">Trang chủ</a></span>
                        <span>Đặt lại mật khẩu</span>
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
                    <div class="card-header text-center py-4" style="background: linear-gradient(135deg, #6f4e37 0%, #8b6f47 100%); border: none;">
                        <h3 class="mb-0 text-white font-weight-bold">🔒 Mật khẩu mới</h3>
                        <p class="text-white-50 mb-0 mt-1 small">Nhập mật khẩu mới cho tài khoản của bạn</p>
                    </div>
                    <div class="card-body p-4 p-md-5">

                        <form method="POST" action="{{ route('password.store') }}">
                            @csrf
                            <input type="hidden" name="token" value="{{ $request->route('token') }}">

                            <div class="form-group mb-3">
                                <label class="font-weight-600 mb-1">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" id="email"
                                    class="form-control form-control-lg @error('email') is-invalid @enderror"
                                    value="{{ old('email', $request->email) }}" required autofocus
                                    style="border-radius: 8px; border: 2px solid #e0e0e0;">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label class="font-weight-600 mb-1">Mật khẩu mới <span class="text-danger">*</span></label>
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
                                    placeholder="Nhập lại mật khẩu mới"
                                    style="border-radius: 8px; border: 2px solid #e0e0e0;">
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg btn-block" style="border-radius: 8px; font-weight: 600;">
                                Đặt lại mật khẩu
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
