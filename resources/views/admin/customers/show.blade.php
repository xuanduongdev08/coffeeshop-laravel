@extends('layouts.admin')

@section('title', 'Khách hàng: ' . $user->name)
@section('page-title', 'Chi tiết khách hàng')

@section('content')

<div class="row">
    <div class="col-md-4">
        <div class="admin-card mb-4 text-center">
            @if($user->avatar)
                <img src="{{ asset('storage/' . $user->avatar) }}" alt=""
                    style="width:80px;height:80px;border-radius:50%;object-fit:cover;border:3px solid #c49b63;margin-bottom:12px;">
            @else
                <div style="width:80px;height:80px;border-radius:50%;background:#6f4e37;color:#fff;display:flex;align-items:center;justify-content:center;font-size:32px;font-weight:700;margin:0 auto 12px;">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
            @endif
            <h5 class="mb-1">{{ $user->name }}</h5>
            <p class="text-muted mb-2" style="font-size:13px;">{{ $user->email }}</p>
            <span class="badge badge-success">Khách hàng</span>
            <hr>
            <div class="text-left" style="font-size:13px;">
                <p><strong>Số điện thoại:</strong> {{ $user->phone ?? '—' }}</p>
                <p><strong>Địa chỉ:</strong> {{ $user->address ?? '—' }}</p>
                <p><strong>Đăng nhập qua:</strong> {{ $user->provider ? ucfirst($user->provider) : 'Email' }}</p>
                <p><strong>Ngày tham gia:</strong> {{ $user->created_at->format('d/m/Y H:i') }}</p>
            </div>
            <hr>
            <form action="{{ route('admin.customers.destroy', $user) }}" method="POST">
                @csrf @method('DELETE')
                <button type="button" class="btn btn-danger btn-sm btn-block btn-delete-confirm" data-name="{{ $user->name }}">🗑️ Xóa tài khoản</button>
            </form>
        </div>
    </div>

    <div class="col-md-8">
        <div class="admin-card">
            <h5>📋 Lịch sử đơn hàng ({{ $user->orders->count() }} đơn)</h5>
            @if($user->orders->count() > 0)
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead style="background:#f8f9fa;">
                            <tr>
                                <th>Mã đơn</th>
                                <th>Tổng tiền</th>
                                <th>Thanh toán</th>
                                <th>Trạng thái</th>
                                <th>Ngày đặt</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($user->orders->take(10) as $order)
                                <tr>
                                    <td><strong style="color:#c49b63;">{{ $order->tracking_code }}</strong></td>
                                    <td>{{ number_format($order->total, 0, ',', '.') }}đ</td>
                                    <td>
                                        <span class="badge badge-{{ $order->payment_status === 'paid' ? 'success' : 'warning' }}">
                                            {{ $order->payment_status === 'paid' ? 'Đã TT' : 'Chờ TT' }}
                                        </span>
                                    </td>
                                    <td><span class="badge badge-secondary">{{ $order->status }}</span></td>
                                    <td style="font-size:12px;">{{ $order->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-secondary" style="font-size:11px;">Xem</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted text-center py-3">Chưa có đơn hàng nào</p>
            @endif
        </div>
    </div>
</div>

<div class="mt-3">
    <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-secondary btn-sm">← Quay lại</a>
</div>

@endsection
