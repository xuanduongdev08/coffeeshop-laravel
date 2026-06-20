@extends('layouts.shop')

@section('title', 'Xác nhận mật khẩu')

@section('content')

{{-- Page Header --}}
<section class="hero-page-header" style="background-image: url({{ asset('images/bg_3.jpg') }}); background-size: cover; background-position: center; height: 350px;">
    <div class="overlay"></div>
    <div class="container h-100">
        <div class="row h-100 justify-content-center align-items-center">
                <div class="col-md-7 col-sm-12 text-center ftco-animate">
                    <h1 class="mb-3 mt-5 bread">Xác nhận mật khẩu</h1>
                    <p class="breadcrumbs">
                        <span class="mr-2"><a href="{{ route('home') }}">Trang chủ</a></span>
                        <span>Xác nhận mật khẩu</span>
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
                        <h3 class="mb-0 text-white font-weight-bold">🔐 Khu vực bảo mật</h3>
                        <p class="text-white-50 mb-0 mt-1 small">Vui lòng xác nhận mật khẩu để tiếp tục</p>
                    </div>
                    <div class="card-body p-4 p-md-5">

                        <p class="text-muted mb-4">
                            Đây là khu vực bảo mật của ứng dụng. Vui lòng xác nhận mật khẩu trước khi tiếp tục.
                        </p>

                        <form method="POST" action="{{ route('password.confirm') }}">
                            @csrf

                            <div class="form-group mb-4">
                                <label class="font-weight-600 mb-1">Mật khẩu <span class="text-danger">*</span></label>
                                <input type="password" name="password" id="password"
                                    class="form-control form-control-lg @error('password') is-invalid @enderror"
                                    required autocomplete="current-password"
                                    placeholder="Nhập mật khẩu của bạn"
                                    style="border-radius: 8px; border: 2px solid #e0e0e0;">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg btn-block"
                                style="border-radius: 8px; font-weight: 600;">
                                Xác nhận
                            </button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
