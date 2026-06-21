@extends('layouts.shop')

@section('title', 'Thanh toán')

@section('content')

<section class="hero-page-header" style="background-image: url({{ asset('images/bg_3.jpg') }}); background-size: cover; background-position: center; height: 350px;">
    <div class="overlay"></div>
    <div class="container h-100">
        <div class="row h-100 justify-content-center align-items-center">
                <div class="col-md-7 col-sm-12 text-center ftco-animate">
                    <h1 class="mb-3 mt-5 bread">Thanh toán</h1>
                    <p class="breadcrumbs">
                        <span class="mr-2"><a href="{{ route('home') }}">Trang chủ</a></span>
                        <span>Thanh toán</span>
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
                    <div class="card-body p-5">
                        <h3 class="mb-4 text-center">Chọn phương thức thanh toán</h3>

                        {{-- Thông tin đơn hàng --}}
                        <div class="order-summary mb-4 p-4" style="background:#f8f9fa;border-radius:10px;border-left:4px solid #c49b63;">
                            <h5 class="mb-3">Thông tin đơn hàng
                                <span style="color:#c49b63;">{{ $order->tracking_code ?? '#' . $order->id }}</span>
                            </h5>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Người nhận:</span>
                                <strong>{{ $order->recipient_name }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Số điện thoại:</span>
                                <strong>{{ $order->phone }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Địa chỉ:</span>
                                <strong>{{ $order->shipping_address }}</strong>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tạm tính:</span>
                                <span>{{ number_format($order->subtotal, 0, ',', '.') }}đ</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Phí vận chuyển:</span>
                                <span>{{ number_format($order->shipping_fee, 0, ',', '.') }}đ</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <strong>Tổng cộng:</strong>
                                <strong style="color:#c49b63;font-size:1.3rem;">
                                    {{ number_format($order->total, 0, ',', '.') }}đ
                                </strong>
                            </div>
                        </div>

                        {{-- Phương thức thanh toán --}}
                        <div class="payment-methods">

                            {{-- COD --}}
                            <div class="payment-option mb-3">
                                <form method="POST" action="{{ route('payment.cod', $order) }}">
                                    @csrf
                                    <button type="submit" class="payment-btn w-100 text-left">
                                        <div class="payment-card">
                                            <div class="d-flex align-items-center">
                                                <img src="{{ asset('images/paymentlogo/COD_Payment.png') }}" alt="COD" class="payment-logo">
                                                <div>
                                                    <h5 class="mb-1">Thanh toán khi nhận hàng (COD)</h5>
                                                    <p class="mb-0 text-muted">Thanh toán bằng tiền mặt khi nhận hàng</p>
                                                </div>
                                            </div>
                                        </div>
                                    </button>
                                </form>
                            </div>

                            {{-- VietQR --}}
                            <div class="payment-option mb-3">
                                <a href="{{ route('payment.vietqr', $order) }}" class="payment-btn d-block">
                                    <div class="payment-card">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset('images/paymentlogo/Vietqr logo.png') }}" alt="VietQR" class="payment-logo">
                                            <div>
                                                <h5 class="mb-1">Thanh toán VietQR</h5>
                                                <p class="mb-0 text-muted">Quét mã QR qua App Ngân hàng hoặc Ví điện tử</p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            {{-- PayPal --}}
                            <div class="payment-option mb-3">
                                <form method="POST" action="{{ route('payment.paypal', $order) }}">
                                    @csrf
                                    <button type="submit" class="payment-btn w-100 text-left">
                                        <div class="payment-card">
                                            <div class="d-flex align-items-center">
                                                <img src="{{ asset('images/paymentlogo/PayPal_logo.png') }}" alt="PayPal" class="payment-logo">
                                                <div>
                                                    <h5 class="mb-1">Thanh toán qua PayPal</h5>
                                                    <p class="mb-0 text-muted">Thanh toán bằng tài khoản PayPal hoặc thẻ quốc tế (USD)</p>
                                                </div>
                                            </div>
                                        </div>
                                    </button>
                                </form>
                            </div>

                            {{-- MoMo --}}
                            <div class="payment-option mb-3">
                                <form method="POST" action="{{ route('payment.momo', $order) }}">
                                    @csrf
                                    <button type="submit" class="payment-btn w-100 text-left">
                                        <div class="payment-card">
                                            <div class="d-flex align-items-center">
                                                <img src="{{ asset('images/paymentlogo/MOMO-Logo-App.png') }}" alt="MoMo" class="payment-logo">
                                                <div>
                                                    <h5 class="mb-1">Thanh toán với Ví MoMo</h5>
                                                    <p class="mb-0 text-muted">Thanh toán qua ví điện tử MoMo</p>
                                                </div>
                                            </div>
                                        </div>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <a href="{{ route('orders.show', $order) }}" class="btn btn-outline-secondary">
                                ← Xem lại đơn hàng
                            </a>
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
.payment-btn {
    background: none;
    border: none;
    padding: 0;
    width: 100%;
    cursor: pointer;
    text-decoration: none;
    color: inherit;
}
.payment-card {
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    padding: 18px 24px;
    transition: all 0.25s ease-in-out;
    background: white;
}
.payment-card:hover {
    border-color: #c49b63;
    box-shadow: 0 8px 20px rgba(196, 155, 99, 0.12);
    transform: translateY(-2px);
}
.payment-logo {
    width: 60px;
    height: 40px;
    object-fit: contain;
    margin-right: 20px;
    flex-shrink: 0;
}
.payment-card h5 {
    color: #1e293b;
    font-weight: 600;
    font-size: 1.05rem;
    margin-bottom: 2px;
}
.payment-card p {
    color: #64748b;
    font-size: 0.85rem;
    line-height: 1.4;
}
.card-body > h3 {
    color: #c49b63 !important;
}
.order-summary h5 {
    color: #c49b63 !important;
}

/* Mobile Responsive Optimization */
@media (max-width: 576px) {
    .payment-card {
        padding: 14px 18px;
    }
    .payment-logo {
        width: 50px;
        height: 35px;
        margin-right: 15px;
    }
    .payment-card h5 {
        font-size: 0.95rem;
    }
    .payment-card p {
        font-size: 0.8rem;
    }
}
</style>
@endpush
