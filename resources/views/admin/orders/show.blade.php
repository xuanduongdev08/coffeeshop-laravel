@extends('layouts.admin')

@section('title', 'Chi tiết đơn #' . $order->tracking_code)
@section('page-title', 'Chi tiết đơn hàng #' . $order->tracking_code)

@section('content')

<div class="row">
    {{-- Order Info --}}
    <div class="col-md-8">
        <div class="admin-card mb-4">
            <h5>📦 Thông tin đơn hàng</h5>
            <div class="row">
                <div class="col-sm-6">
                    <p><strong>Người nhận:</strong> {{ $order->recipient_name }}</p>
                    <p><strong>SĐT:</strong> {{ $order->phone }}</p>
                    <p><strong>Địa chỉ:</strong> {{ $order->shipping_address }}</p>
                    @if($order->notes)
                        <p><strong>Ghi chú:</strong> {{ $order->notes }}</p>
                    @endif
                </div>
                <div class="col-sm-6">
                    <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                    <p><strong>Phương thức TT:</strong> {{ $order->payment_method }}</p>
                    @if($order->user)
                        <p><strong>Tài khoản:</strong> {{ $order->user->email }}</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Items --}}
        <div class="admin-card mb-4">
            <h5>🛒 Sản phẩm đã đặt</h5>
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead style="background:#f8f9fa;">
                        <tr>
                            <th>Sản phẩm</th>
                            <th>Size</th>
                            <th>Tùy chỉnh</th>
                            <th class="text-right">Đơn giá</th>
                            <th class="text-center">SL</th>
                            <th class="text-right">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        @if($item->product_image)
                                            <img src="{{ asset($item->product_image) }}" alt="" style="width:40px;height:40px;object-fit:cover;border-radius:6px;">
                                        @endif
                                        <span>{{ $item->product_name ?? $item->product?->name ?? 'Sản phẩm đã xóa' }}</span>
                                    </div>
                                </td>
                                <td>{{ $item->size ?? '—' }}</td>
                                <td>
                                    @if($item->modifiers->count() > 0)
                                        <small class="text-muted">{{ $item->modifiers->pluck('name')->join(', ') }}</small>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="text-right">{{ number_format($item->unit_price ?? $item->price, 0, ',', '.') }}đ</td>
                                <td class="text-center">{{ $item->quantity }}</td>
                                <td class="text-right"><strong>{{ number_format($item->subtotal, 0, ',', '.') }}đ</strong></td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5" class="text-right">Tạm tính:</td>
                            <td class="text-right">{{ number_format($order->subtotal, 0, ',', '.') }}đ</td>
                        </tr>
                        <tr>
                            <td colspan="5" class="text-right">Phí vận chuyển:</td>
                            <td class="text-right">{{ number_format($order->shipping_fee, 0, ',', '.') }}đ</td>
                        </tr>
                        @if($order->discount_amount > 0)
                            <tr>
                                <td colspan="5" class="text-right text-success">Giảm giá:</td>
                                <td class="text-right text-success">-{{ number_format($order->discount_amount, 0, ',', '.') }}đ</td>
                            </tr>
                        @endif
                        <tr>
                            <td colspan="5" class="text-right"><strong>Tổng cộng:</strong></td>
                            <td class="text-right"><strong style="color:#c49b63;font-size:16px;">{{ number_format($order->total, 0, ',', '.') }}đ</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    {{-- Actions --}}
    <div class="col-md-4">
        {{-- Trạng thái đơn hàng --}}
        <div class="admin-card mb-4">
            <h5>📋 Cập nhật trạng thái</h5>
            <form method="POST" action="{{ route('admin.orders.status', $order) }}">
                @csrf @method('PATCH')
                <div class="form-group">
                    <label class="small font-weight-bold">Trạng thái đơn hàng</label>
                    <select name="status" class="form-control form-control-sm">
                        @foreach(['Chờ xử lý', 'Đang giao', 'Hoàn thành', 'Đã hủy'] as $s)
                            <option value="{{ $s }}" {{ $order->status === $s ? 'selected' : '' }}>{{ $s }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-sm btn-coffee w-100">Cập nhật</button>
            </form>
        </div>

        {{-- Trạng thái thanh toán --}}
        <div class="admin-card mb-4">
            <h5>💳 Trạng thái thanh toán</h5>
            <form method="POST" action="{{ route('admin.orders.payment-status', $order) }}">
                @csrf @method('PATCH')
                <div class="form-group">
                    <select name="payment_status" class="form-control form-control-sm">
                        @foreach(['pending' => 'Chờ thanh toán', 'paid' => 'Đã thanh toán', 'failed' => 'Thất bại', 'refunded' => 'Hoàn tiền'] as $val => $label)
                            <option value="{{ $val }}" {{ $order->payment_status === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-sm btn-coffee w-100">Cập nhật</button>
            </form>
        </div>

        {{-- Trạng thái pha chế --}}
        @if($order->drink_status !== null)
            <div class="admin-card mb-4">
                <h5>☕ Trạng thái pha chế</h5>
                <div class="text-center mb-3">
                    @php
                        $steps = ['pending' => 1, 'brewing' => 2, 'completed' => 3];
                        $current = $steps[$order->drink_status] ?? 0;
                    @endphp
                    <div class="d-flex justify-content-between align-items-center">
                        @foreach(['pending' => '✅ Nhận', 'brewing' => '☕ Pha', 'completed' => '🎉 Xong'] as $s => $l)
                            <div class="text-center" style="flex:1;">
                                <div style="width:32px;height:32px;border-radius:50%;margin:0 auto 4px;display:flex;align-items:center;justify-content:center;font-size:14px;background:{{ $steps[$s] <= $current ? '#c49b63' : '#e0e0e0' }};color:{{ $steps[$s] <= $current ? '#fff' : '#999' }};">
                                    {{ $steps[$s] }}
                                </div>
                                <small style="font-size:10px;color:{{ $steps[$s] <= $current ? '#c49b63' : '#999' }};">{{ $l }}</small>
                            </div>
                            @if($s !== 'completed')
                                <div style="flex:0.5;height:2px;background:{{ $steps[$s] < $current ? '#c49b63' : '#e0e0e0' }};margin-bottom:20px;"></div>
                            @endif
                        @endforeach
                    </div>
                </div>
                @if($order->next_drink_status)
                    <form method="POST" action="{{ route('admin.orders.drink-status.update', $order) }}">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn btn-sm btn-coffee w-100">
                            {{ $order->drink_status === 'pending' ? '▶ Bắt đầu pha chế' : '✅ Đánh dấu hoàn thành' }}
                        </button>
                    </form>
                @else
                    <div class="alert alert-success text-center py-2 mb-0" style="font-size:13px;">🎉 Đã hoàn thành</div>
                @endif
            </div>
        @endif

        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm w-100">← Quay lại</a>
    </div>
</div>

@endsection
