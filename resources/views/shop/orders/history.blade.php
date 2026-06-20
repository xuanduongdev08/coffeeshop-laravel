@extends('layouts.shop')

@section('title', 'Lịch sử đơn hàng')

@section('content')

<section class="hero-page-header" style="background-image: url({{ asset('images/bg_3.jpg') }}); background-size: cover; background-position: center; height: 350px;">
    <div class="overlay"></div>
    <div class="container h-100">
        <div class="row h-100 justify-content-center align-items-center">
                <div class="col-md-7 col-sm-12 text-center ftco-animate">
                    <h1 class="mb-3 mt-5 bread">Lịch sử đơn hàng</h1>
                    <p class="breadcrumbs">
                        <span class="mr-2"><a href="{{ route('home') }}">Trang chủ</a></span>
                        <span>Lịch sử mua hàng</span>
                    </p>
                </div>
        </div>
    </div>
</section>

<section class="ftco-section">
    <div class="container">
        <div class="row">
            <div class="col-md-12 ftco-animate">
                @if($orders->isEmpty())
                    <div class="text-center py-5">
                        <div style="font-size:80px;margin-bottom:20px;">📦</div>
                        <h3>Bạn chưa có đơn hàng nào</h3>
                        <p class="text-muted">Hãy mua sắm và đặt hàng ngay!</p>
                        <a href="{{ route('products.index') }}" class="btn btn-primary mt-3 py-3 px-5">Mua sắm ngay</a>
                    </div>
                @else
                    <div class="cart-list">
                        <table class="table">
                            <thead class="thead-primary">
                                <tr class="text-center">
                                    <th>Mã đơn</th>
                                    <th>Ngày đặt</th>
                                    <th>Người nhận</th>
                                    <th>Tổng tiền</th>
                                    <th>Thanh toán</th>
                                    <th>Trạng thái</th>
                                    <th>Chi tiết</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                    <tr class="text-center">
                                        <td>
                                            <strong style="color:#c49b63;">{{ $order->tracking_code ?? '#' . $order->id }}</strong>
                                        </td>
                                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                        <td>{{ $order->recipient_name }}</td>
                                        <td><strong>{{ number_format($order->total, 0, ',', '.') }}đ</strong></td>
                                        <td>
                                            @switch($order->payment_method)
                                                @case('COD')
                                                    <span class="badge badge-success">💵 COD</span>
                                                    @break
                                                @case('VietQR')
                                                    <span class="badge badge-info" style="background:#005baa;">📱 VietQR</span>
                                                    @break
                                                @case('MoMo')
                                                    <span class="badge badge-danger">📲 MoMo</span>
                                                    @break
                                                @default
                                                    <span class="badge badge-secondary">{{ $order->payment_method }}</span>
                                            @endswitch
                                        </td>
                                        <td>
                                            @switch($order->status)
                                                @case('Chờ xử lý')
                                                    <span class="badge badge-warning">⏳ Chờ xử lý</span>
                                                    @break
                                                @case('Đang giao')
                                                    <span class="badge badge-info">🚚 Đang giao</span>
                                                    @break
                                                @case('Hoàn thành')
                                                    <span class="badge badge-success">✅ Hoàn thành</span>
                                                    @break
                                                @case('Đã hủy')
                                                    <span class="badge badge-danger">❌ Đã hủy</span>
                                                    @break
                                                @default
                                                    <span class="badge badge-secondary">{{ $order->status }}</span>
                                            @endswitch
                                        </td>
                                        <td>
                                            <a href="{{ route('orders.show', $order) }}"
                                                class="btn btn-primary btn-sm btn-outline-primary">
                                                Xem chi tiết
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if($orders->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $orders->links() }}
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</section>

@endsection
