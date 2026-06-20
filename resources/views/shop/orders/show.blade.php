@extends('layouts.shop')

@section('title', 'Chi tiết đơn hàng ' . ($order->tracking_code ?? '#' . $order->id))

@section('content')

<section class="hero-page-header" style="background-image: url({{ asset('images/bg_3.jpg') }}); background-size: cover; background-position: center; height: 350px;">
    <div class="overlay"></div>
    <div class="container h-100">
        <div class="row h-100 justify-content-center align-items-center">
                <div class="col-md-7 col-sm-12 text-center ftco-animate">
                    <h1 class="mb-3 mt-5 bread">Theo dõi đơn hàng</h1>
                    <p class="breadcrumbs">
                        <span class="mr-2"><a href="{{ route('home') }}">Trang chủ</a></span>
                        <span class="mr-2"><a href="{{ route('orders.history') }}">Lịch sử</a></span>
                        <span>Chi tiết</span>
                    </p>
                </div>
        </div>
    </div>
</section>

<section class="ftco-section">
    <div class="container">
        <div class="row mb-3">
            <div class="col-12">
                <a href="{{ route('orders.history') }}"
                    style="display:inline-flex;align-items:center;gap:8px;padding:9px 20px;background:transparent;border:2px solid #b5883e;color:#b5883e;border-radius:25px;font-weight:600;font-size:14px;text-decoration:none;"
                    onmouseover="this.style.background='#b5883e';this.style.color='#fff';"
                    onmouseout="this.style.background='transparent';this.style.color='#b5883e';">
                    ← Quay lại lịch sử đơn hàng
                </a>
            </div>
        </div>

        <div class="row">
            {{-- Thông tin đơn hàng --}}
            <div class="col-md-12 mb-4">
                <div class="card profile-card">
                    <div class="card-body">
                        <h3 class="mb-4 text-center">
                            Thông tin đơn hàng
                            <span style="color:#c49b63;">{{ $order->tracking_code ?? '#' . $order->id }}</span>
                        </h3>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Ngày đặt hàng:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                                <p><strong>Người nhận:</strong> {{ $order->recipient_name }}</p>
                                <p><strong>Số điện thoại:</strong> {{ $order->phone }}</p>
                                <p><strong>Địa chỉ giao hàng:</strong> {{ $order->shipping_address }}</p>
                                @if($order->notes)
                                    <p><strong>Ghi chú:</strong> {{ $order->notes }}</p>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <p>
                                    <strong>Trạng thái đơn hàng:</strong>
                                    @switch($order->status)
                                        @case('Chờ xử lý')
                                            <span class="badge badge-warning">⏳ Chờ xử lý</span>
                                            @break
                                        @case('Đang giao')
                                            <span class="badge badge-info">🚚 Đang giao hàng</span>
                                            @break
                                        @case('Hoàn thành')
                                            <span class="badge badge-success">✅ Giao thành công</span>
                                            @break
                                        @case('Đã hủy')
                                            <span class="badge badge-danger">❌ Đã hủy</span>
                                            @break
                                        @default
                                            <span class="badge badge-secondary">{{ $order->status }}</span>
                                    @endswitch
                                </p>
                                <p>
                                    <strong>Phương thức thanh toán:</strong>
                                    @switch($order->payment_method)
                                        @case('COD')
                                            <span class="badge badge-success">💵 Thanh toán tiền mặt (COD)</span>
                                            @break
                                        @case('VietQR')
                                            <span class="badge badge-info" style="background:#005baa;">📱 Chuyển khoản VietQR</span>
                                            @break
                                        @case('MoMo')
                                            <span class="badge badge-danger">📲 MoMo</span>
                                            @break
                                        @default
                                            <span class="badge badge-secondary">{{ $order->payment_method }}</span>
                                    @endswitch
                                </p>
                                <p>
                                    <strong>Trạng thái thanh toán:</strong>
                                    @if($order->payment_status === 'paid')
                                        <span class="badge badge-success">✅ Đã thanh toán</span>
                                    @elseif($order->payment_status === 'failed')
                                        <span class="badge badge-danger">❌ Thanh toán thất bại</span>
                                    @else
                                        <span class="badge badge-warning">⏳ Chờ thanh toán</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        {{-- Nút hủy đơn --}}
                        @if($order->status === 'Chờ xử lý')
                            <div class="text-right mt-3">
                                <form method="POST" action="{{ route('orders.cancel', $order) }}"
                                    onsubmit="return confirm('Bạn có chắc muốn hủy đơn hàng này?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        ❌ Hủy đơn hàng
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Chi tiết sản phẩm --}}
            <div class="col-md-12">
                <div class="card profile-card">
                    <div class="card-body">
                        <h4 class="mb-4">Chi tiết sản phẩm</h4>
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="thead-primary">
                                    <tr>
                                        <th>Sản phẩm</th>
                                        <th>Đơn giá</th>
                                        <th>Số lượng</th>
                                        <th>Thành tiền</th>
                                        <th>Đánh giá</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div style="width:50px;height:50px;background-image:url({{ $item->product_image ? asset($item->product_image) : asset('images/menu-1.jpg') }});background-size:cover;border-radius:4px;margin-right:15px;flex-shrink:0;"></div>
                                                    <div>
                                                        <span class="d-block">{{ $item->product_name ?? $item->product?->name ?? 'Sản phẩm' }}</span>
                                                        @if($item->size)
                                                            <small class="text-muted">Size: <strong>{{ $item->size }}</strong></small>
                                                        @endif
                                                        @if($item->modifiers->count() > 0)
                                                            <br><small class="text-muted" style="font-size:11px;">{{ $item->modifiers->pluck('name')->join(', ') }}</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ number_format($item->unit_price ?? $item->price, 0, ',', '.') }}đ</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>{{ number_format($item->subtotal, 0, ',', '.') }}đ</td>
                                            <td>
                                                @if($order->status === 'Hoàn thành' && $item->product)
                                                    <a href="{{ route('products.show', $item->product->slug) }}#reviewSection"
                                                        class="btn btn-sm btn-primary" style="white-space:nowrap;">
                                                        ✏️ Viết đánh giá
                                                    </a>
                                                @else
                                                    <span class="text-muted" style="font-size:12px;">Hoàn thành đơn để đánh giá</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-right"><strong>Tiền hàng:</strong></td>
                                        <td><strong>{{ number_format($order->subtotal, 0, ',', '.') }}đ</strong></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-right"><strong>Phí ship:</strong></td>
                                        <td>
                                            @if($order->shipping_fee > 0)
                                                <strong>{{ number_format($order->shipping_fee, 0, ',', '.') }}đ</strong>
                                            @else
                                                <strong class="text-success">Miễn phí</strong>
                                            @endif
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr style="border-top:2px solid #b5883e;">
                                        <td colspan="3" class="text-right">
                                            <strong style="font-size:1.05em;color:#b5883e;">Tổng thanh toán:</strong>
                                        </td>
                                        <td>
                                            <strong style="font-size:1.05em;color:#b5883e;">
                                                {{ number_format($order->total, 0, ',', '.') }}đ
                                            </strong>
                                        </td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="text-center mt-4">
                            <a href="{{ route('products.index') }}" class="btn btn-primary py-3 px-4">
                                Tiếp tục mua hàng
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
