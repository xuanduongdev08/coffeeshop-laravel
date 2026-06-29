@extends('layouts.admin')

@section('title', 'Chi tiết đơn #' . $order->tracking_code)
@section('page-title', 'Chi tiết đơn hàng #' . $order->tracking_code)

@section('content')

<div class="row">
    {{-- Order Info --}}
    <div class="col-md-8">
        <div class="admin-card mb-4">
            <h5><span class="ion-md-cube" style="margin-right:6px;color:#c49b63;opacity:0.7;"></span>Thông tin đơn hàng</h5>
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
            @if($order->status === 'Đã hủy' && $order->cancel_reason)
                <div class="mt-3 p-3" style="background:#fff5f5; border-left:4px solid #e74c3c; border-radius:6px; color:#c0392b;">
                    <strong><span class="ion-md-alert" style="margin-right:6px;"></span>Lý do hủy đơn:</strong>
                    <p class="mb-0 mt-1" style="font-style: italic; color:#555;">{{ $order->cancel_reason }}</p>
                </div>
            @endif
        </div>

        {{-- Items --}}
        <div class="admin-card mb-4">
            <h5><span class="ion-md-cart" style="margin-right:6px;color:#c49b63;opacity:0.7;"></span>Sản phẩm đã đặt</h5>
            <div class="table-responsive">
                <table class="table table-sm" style="vertical-align: middle;">
                    <thead style="background:#f8f9fa;">
                        <tr>
                            <th>Sản phẩm</th>
                            <th>Size</th>
                            <th>Tùy chỉnh</th>
                            <th class="text-right" style="width: 110px; white-space: nowrap;">Đơn giá</th>
                            <th class="text-center" style="width: 60px; white-space: nowrap;">SL</th>
                            <th class="text-right" style="width: 120px; white-space: nowrap;">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td style="vertical-align: middle;">
                                    <div class="d-flex align-items-center">
                                        @if($item->product_image)
                                            <img src="{{ asset($item->product_image) }}" alt="" style="width:40px;height:40px;object-fit:cover;border-radius:6px;margin-right:10px;flex-shrink:0;">
                                        @endif
                                        <span style="font-weight: 500;">{{ $item->product_name ?? $item->product?->name ?? 'Sản phẩm đã xóa' }}</span>
                                    </div>
                                </td>
                                <td style="vertical-align: middle;">{{ $item->size ?? '—' }}</td>
                                <td style="vertical-align: middle;">
                                    @if($item->modifiers->count() > 0)
                                        <small class="text-muted">{{ $item->modifiers->pluck('name')->join(', ') }}</small>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="text-right" style="vertical-align: middle; white-space: nowrap;">{{ number_format($item->unit_price ?? $item->price, 0, ',', '.') }}đ</td>
                                <td class="text-center" style="vertical-align: middle; white-space: nowrap;">{{ $item->quantity }}</td>
                                <td class="text-right" style="vertical-align: middle; white-space: nowrap;"><strong>{{ number_format($item->subtotal, 0, ',', '.') }}đ</strong></td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5" class="text-right">Tạm tính:</td>
                            <td class="text-right" style="white-space: nowrap;">{{ number_format($order->subtotal, 0, ',', '.') }}đ</td>
                        </tr>
                        <tr>
                            <td colspan="5" class="text-right">Phí vận chuyển:</td>
                            <td class="text-right" style="white-space: nowrap;">{{ number_format($order->shipping_fee, 0, ',', '.') }}đ</td>
                        </tr>
                        @if($order->discount_amount > 0)
                            <tr>
                                <td colspan="5" class="text-right text-success">Giảm giá:</td>
                                <td class="text-right text-success" style="white-space: nowrap;">-{{ number_format($order->discount_amount, 0, ',', '.') }}đ</td>
                            </tr>
                        @endif
                        <tr>
                            <td colspan="5" class="text-right"><strong>Tổng cộng:</strong></td>
                            <td class="text-right" style="white-space: nowrap;"><strong style="color:#c49b63;font-size:16px;">{{ number_format($order->total, 0, ',', '.') }}đ</strong></td>
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
            <h5><span class="ion-md-list" style="margin-right:6px;color:#c49b63;opacity:0.7;"></span>Cập nhật trạng thái</h5>
            @if(in_array($order->status, ['Hoàn thành', 'Đã hủy']))
                <div class="alert alert-secondary text-center py-2 mb-0" style="font-size:13px; border-radius: 8px; background-color: #f8f9fa; border: 1px solid #e9ecef; color: #6c757d; font-weight: 500;">
                    🔒 Không thể thay đổi đơn hàng đã <strong>{{ $order->status }}</strong>
                </div>
            @else
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
            @endif
        </div>

        {{-- Trạng thái thanh toán --}}
        <div class="admin-card mb-4">
            <h5><span class="ion-md-card" style="margin-right:6px;color:#c49b63;opacity:0.7;"></span>Trạng thái thanh toán</h5>
            <form method="POST" action="{{ route('admin.orders.payment-status', $order) }}">
                @csrf @method('PATCH')
                <div class="form-group">
                    <select name="payment_status" class="form-control form-control-sm">
                        @if($order->payment_status === 'paid')
                            <option value="paid" selected>Đã thanh toán</option>
                            <option value="refunded">Hoàn tiền</option>
                        @elseif($order->payment_status === 'refunded')
                            <option value="refunded" selected>Hoàn tiền</option>
                        @else
                            @foreach(['pending' => 'Chờ thanh toán', 'paid' => 'Đã thanh toán', 'failed' => 'Thất bại', 'refunded' => 'Hoàn tiền'] as $val => $label)
                                <option value="{{ $val }}" {{ $order->payment_status === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <button type="submit" class="btn btn-sm btn-coffee w-100">Cập nhật</button>
            </form>
        </div>

        {{-- Trạng thái pha chế --}}
        @if($order->drink_status !== null)
            <div class="admin-card mb-4">
                <h5><span class="ion-md-cafe" style="margin-right:6px;color:#c49b63;opacity:0.7;"></span>Trạng thái pha chế</h5>
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
