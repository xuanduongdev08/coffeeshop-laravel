@extends('layouts.shop')

@section('title', 'Xác thực email')

@section('content')

{{-- Page Header --}}
<section class="hero-page-header" style="background-image: url({{ asset('images/bg_3.jpg') }}); background-size: cover; background-position: center; height: 350px;">
    <div class="overlay"></div>
    <div class="container h-100">
        <div class="row h-100 justify-content-center align-items-center">
                <div class="col-md-7 col-sm-12 text-center ftco-animate">
                    <h1 class="mb-3 mt-5 bread">Xác thực email</h1>
                    <p class="breadcrumbs">
                        <span class="mr-2"><a href="{{ route('home') }}">Trang chủ</a></span>
                        <span>Xác thực email</span>
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
                        <h3 class="mb-0 text-white font-weight-bold">📧 Xác thực email</h3>
                        <p class="text-white-50 mb-0 mt-1 small">Kiểm tra hộp thư để xác thực tài khoản</p>
                    </div>
                    <div class="card-body p-4 p-md-5 text-center">

                        <div class="mb-4">
                            <i class="ion-ios-mail" style="font-size: 60px; color: #c49b63;"></i>
                        </div>

                        <p class="text-muted mb-4">
                            Cảm ơn bạn đã đăng ký! Trước khi bắt đầu, vui lòng xác thực địa chỉ email bằng cách nhấp vào link chúng tôi vừa gửi cho bạn.
                            Nếu bạn chưa nhận được email, chúng tôi sẽ gửi lại cho bạn.
                        </p>

                        @if (session('status') == 'verification-link-sent')
                            <div class="alert alert-success mb-4">
                                <i class="ion-ios-checkmark-circle mr-2"></i>
                                Một link xác thực mới đã được gửi đến địa chỉ email bạn đã đăng ký.
                            </div>
                        @endif

                        <form method="POST" action="{{ route('verification.send') }}" class="mb-3">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-lg btn-block"
                                style="border-radius: 8px; font-weight: 600;">
                                Gửi lại email xác thực
                            </button>
                        </form>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-secondary btn-block"
                                style="border-radius: 8px;">
                                Đăng xuất
                            </button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
