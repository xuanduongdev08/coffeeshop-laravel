@extends('layouts.shop')

@section('title', 'Thanh toán VietQR')

@section('content')

<section class="hero-page-header" style="background-image: url({{ asset('images/bg_3.jpg') }}); background-size: cover; background-position: center; height: 350px;">
    <div class="overlay"></div>
    <div class="container h-100">
        <div class="row h-100 justify-content-center align-items-center">
            <div class="col-md-7 col-sm-12 text-center ftco-animate">
                <h1 class="mb-3 bread">Thanh toán VietQR</h1>
                <p class="breadcrumbs">
                    <span class="mr-2"><a href="{{ route('home') }}">Trang chủ</a></span>
                    <span>Thanh toán</span>
                </p>
            </div>
        </div>
    </div>
</section>

@php
    $trackingCode = $order->tracking_code ?? 'XD' . str_pad($order->id, 5, '0', STR_PAD_LEFT);
    $accountNo    = '0801130171003';
    $accountName  = 'NGUYEN XUAN DUONG';
    $bankCode     = 'MBBank';
    $amount       = (int) $order->total;
    // SePay QR URL — tự điền số tiền & nội dung khi quét
    $sePayQrUrl   = "https://qr.sepay.vn/img?bank={$bankCode}&acc={$accountNo}&template=compact&amount={$amount}&des={$trackingCode}";
@endphp

<section class="ftco-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg border-0">
                    <div class="card-body p-5 text-center">
                        {{-- Tiêu đề --}}
                        <h3 class="mb-2" style="color:#3d2b1f;">Quét mã QR để thanh toán</h3>
                        <p class="mb-4" style="color:#6f4e37;">
                            Mã đơn hàng:
                            <strong style="color:#c49b63;">{{ $trackingCode }}</strong>
                        </p>

                        {{-- QR Code từ SePay — tự điền số tiền + nội dung --}}
                        <div class="qr-wrapper mb-4" style="display:inline-block;padding:20px;background:#fff;border-radius:16px;box-shadow:0 4px 20px rgba(0,0,0,0.1);">
                            <img src="{{ $sePayQrUrl }}"
                                 alt="VietQR thanh toán {{ $trackingCode }}"
                                 style="width:280px;height:280px;object-fit:contain;"
                                 onerror="this.onerror=null;this.src='{{ asset('images/QRCODE.jpg') }}';">
                        </div>

                        {{-- Thông tin chuyển khoản --}}
                        <div class="transfer-info mb-4 p-4 text-left" style="background:#f8f9fa;border-radius:12px;border-left:4px solid #c49b63;">
                            <h5 class="mb-3" style="color:#6f4e37;">📋 Thông tin chuyển khoản</h5>
                            <div class="row">
                                <div class="col-6"><strong style="color:#3d2b1f;">Ngân hàng:</strong></div>
                                <div class="col-6" style="color:#3d2b1f;">MB Bank</div>

                                <div class="col-6 mt-2"><strong style="color:#3d2b1f;">Số tài khoản:</strong></div>
                                <div class="col-6 mt-2">
                                    <span id="account-number" style="font-size:1.1rem;font-weight:700;color:#c49b63;">{{ $accountNo }}</span>
                                    <button onclick="copyText('{{ $accountNo }}')" class="btn btn-sm btn-outline-secondary ml-2" style="padding:2px 8px;font-size:11px;">Copy</button>
                                </div>

                                <div class="col-6 mt-2"><strong style="color:#3d2b1f;">Chủ tài khoản:</strong></div>
                                <div class="col-6 mt-2" style="color:#3d2b1f;">{{ $accountName }}</div>

                                <div class="col-6 mt-2"><strong style="color:#3d2b1f;">Số tiền:</strong></div>
                                <div class="col-6 mt-2">
                                    <span style="font-size:1.2rem;font-weight:700;color:#c49b63;">
                                        {{ number_format($order->total, 0, ',', '.') }}đ
                                    </span>
                                </div>

                                <div class="col-6 mt-2"><strong style="color:#3d2b1f;">Nội dung CK:</strong></div>
                                <div class="col-6 mt-2">
                                    <span id="transfer-content" style="font-weight:700;color:#c49b63;">{{ $trackingCode }}</span>
                                    <button onclick="copyText('{{ $trackingCode }}')" class="btn btn-sm btn-outline-secondary ml-2" style="padding:2px 8px;font-size:11px;">Copy</button>
                                </div>
                            </div>
                        </div>

                        {{-- Hướng dẫn --}}
                        <div class="alert alert-info text-left mb-4" style="border-radius:10px;">
                            <strong>📌 Hướng dẫn:</strong>
                            <ol class="mb-0 mt-2 pl-4">
                                <li>Mở App Ngân hàng hoặc Ví điện tử</li>
                                <li>Chọn <strong>Quét mã QR</strong> hoặc <strong>Chuyển khoản</strong></li>
                                <li>Quét mã — số tiền và nội dung sẽ <strong>tự động điền sẵn</strong></li>
                                <li>Xác nhận thanh toán</li>
                                <li>Hệ thống sẽ tự động xác nhận trong vài phút</li>
                            </ol>
                        </div>

                        {{-- Trạng thái chờ --}}
                        <div id="payment-status-box" class="mb-4 p-3" style="background:#fff8e1;border-radius:10px;border:1px solid #ffe082;">
                            <div class="d-flex align-items-center justify-content-center">
                                <div class="spinner-border spinner-border-sm text-warning mr-2" role="status"></div>
                                <span style="color:#f57f17;font-weight:600;">Đang chờ xác nhận thanh toán...</span>
                            </div>
                            <p class="text-muted small mb-0 mt-2">Trang sẽ tự động chuyển hướng khi nhận được thanh toán</p>
                        </div>

                        {{-- Buttons --}}
                        <div class="d-flex justify-content-center" style="gap:12px;">
                            <a href="{{ route('payment.success', $order) }}" class="btn btn-success py-3 px-4">
                                ✅ Tôi đã chuyển khoản xong
                            </a>
                            <a href="{{ route('payment.index', $order) }}" class="btn btn-outline-secondary py-3 px-4">
                                ← Chọn phương thức khác
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
function copyText(text) {
    navigator.clipboard.writeText(text).then(function() {
        Swal.fire({ icon: 'success', title: 'Đã copy!', text: '"' + text + '" đã được copy vào clipboard', timer: 1500, showConfirmButton: false });
    }).catch(function() {
        var el = document.createElement('textarea');
        el.value = text;
        document.body.appendChild(el);
        el.select();
        document.execCommand('copy');
        document.body.removeChild(el);
        Swal.fire({ icon: 'success', title: 'Đã copy!', timer: 1200, showConfirmButton: false });
    });
}

// Polling kiểm tra trạng thái thanh toán mỗi 5 giây
var orderId = {{ $order->id }};
var pollInterval = setInterval(function() {
    fetch('/thanh-toan/status/' + orderId, {
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        if (data.is_paid) {
            clearInterval(pollInterval);
            document.getElementById('payment-status-box').innerHTML =
                '<div style="color:#2e7d32;font-weight:700;font-size:1.1rem;">✅ Đã nhận thanh toán! Đang chuyển hướng...</div>';
            setTimeout(function() {
                window.location.href = '{{ route('payment.success', $order) }}';
            }, 1500);
        }
    })
    .catch(function() {});
}, 5000);

// Dừng polling sau 10 phút
setTimeout(function() { clearInterval(pollInterval); }, 600000);
</script>
@endpush
