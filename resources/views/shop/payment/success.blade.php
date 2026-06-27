@extends('layouts.shop')

@section('title', 'Đặt hàng thành công')

@section('content')

<section class="hero-page-header" style="background-image: url({{ asset('images/bg_3.jpg') }}); background-size: cover; background-position: center; height: 350px;">
    <div class="overlay"></div>
    <div class="container h-100">
        <div class="row h-100 justify-content-center align-items-center">
                <div class="col-md-7 col-sm-12 text-center ftco-animate">
                    <h1 class="mb-3 mt-5 bread">Đặt hàng thành công</h1>
                    <p class="breadcrumbs">
                        <span class="mr-2"><a href="{{ route('home') }}">Trang chủ</a></span>
                        <span>Thành công</span>
                    </p>
                </div>
        </div>
    </div>
</section>

<section class="ftco-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg border-0">
                    <div class="card-body p-5 text-center">

                        {{-- Success Icon --}}
                        <div class="success-icon mb-4">
                            <div class="success-circle">
                                <span style="font-size:60px;line-height:1;color:#ffffff !important;">✓</span>
                            </div>
                        </div>

                        <h2 class="mb-3" style="color:#28a745;">Đặt hàng thành công!</h2>
                        <p class="lead mb-4">
                            Cảm ơn bạn đã đặt hàng tại <strong style="color:#c49b63;">XDTHECOFFEEHOUSE</strong>.<br>
                            Chúng tôi sẽ xử lý đơn hàng của bạn sớm nhất có thể.
                        </p>

                        {{-- Mã đơn hàng --}}
                        <div class="order-number mb-4 p-4" style="background:#f8f9fa;border-radius:10px;border-left:4px solid #c49b63;">
                            <h5 class="mb-2">Mã đơn hàng của bạn</h5>
                            <h3 style="color:#c49b63;font-weight:bold;">
                                {{ $order->tracking_code ?? 'XD' . str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
                            </h3>
                            <p class="text-muted mb-0 small">Lưu lại mã này để theo dõi đơn hàng</p>
                        </div>

                        {{-- Tóm tắt đơn --}}
                        <div class="row text-left mb-4">
                            <div class="col-md-6">
                                <p><strong>Người nhận:</strong> {{ $order->recipient_name }}</p>
                                <p><strong>Số điện thoại:</strong> {{ $order->phone }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Địa chỉ:</strong> {{ $order->shipping_address }}</p>
                                <p>
                                    <strong>Tổng tiền:</strong>
                                    <span style="color:#c49b63;font-weight:700;">
                                        {{ number_format($order->total, 0, ',', '.') }}đ
                                    </span>
                                </p>
                            </div>
                        </div>

                        {{-- Thông báo --}}
                        <div class="alert alert-info mb-4" style="border-radius:10px;">
                            <i class="icon-info-circle mr-2"></i>
                            @if($order->payment_method === 'COD')
                                Đơn hàng COD — Bạn sẽ thanh toán khi nhận hàng.
                            @else
                                Chúng tôi sẽ xác nhận thanh toán và liên hệ với bạn sớm.
                            @endif
                        </div>

                        {{-- Buttons --}}
                        <div class="d-flex justify-content-center flex-wrap" style="gap:12px;">
                            <a href="{{ route('home') }}" class="btn btn-outline-primary btn-lg mb-2">
                                Về trang chủ
                            </a>
                            <a href="{{ route('orders.show', $order) }}" class="btn btn-primary btn-lg mb-2">
                                Xem chi tiết đơn hàng
                            </a>
                            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-lg mb-2">
                                Tiếp tục mua sắm
                            </a>
                        </div>
                    </div>
                </div>
 
                {{-- Các bước tiếp theo --}}
                <div class="card mt-4 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h5 class="mb-3">
                            Các bước tiếp theo
                        </h5>
                        <div class="row text-center">
                            <div class="col-md-4 mb-3">
                                <div class="p-3" style="transition:all 0.3s;">
                                    <div style="width:50px;height:50px;border-radius:50%;background:#c49b63;color:white;display:inline-flex;align-items:center;justify-content:center;font-weight:bold;font-size:1.2rem;margin-bottom:10px;">1</div>
                                    <p class="mb-0 font-weight-600">Đơn hàng được xác nhận</p>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="p-3">
                                    <div style="width:50px;height:50px;border-radius:50%;background:#c49b63;color:white;display:inline-flex;align-items:center;justify-content:center;font-weight:bold;font-size:1.2rem;margin-bottom:10px;">2</div>
                                    <p class="mb-0">Đơn hàng được giao</p>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="p-3">
                                    <div style="width:50px;height:50px;border-radius:50%;background:#c49b63;color:white;display:inline-flex;align-items:center;justify-content:center;font-weight:bold;font-size:1.2rem;margin-bottom:10px;">3</div>
                                    <p class="mb-0">Bạn nhận hàng</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
.success-circle {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: white !important;
    animation: scaleIn 0.5s ease-out;
}
.success-circle span {
    color: #ffffff !important;
}
@keyframes scaleIn {
    0%   { transform: scale(0); opacity: 0; }
    50%  { transform: scale(1.1); }
    100% { transform: scale(1); opacity: 1; }
}
.card {
    background: #ffffff !important;
}
.card h5 {
    color: #c49b63 !important;
    font-weight: 700 !important;
}
.card p {
    color: #555555 !important;
}
.card strong {
    color: #2b2b2b !important;
}
.card-body {
    color: #555555 !important;
}
</style>
@endpush
