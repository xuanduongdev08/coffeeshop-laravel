@extends('layouts.admin')

@section('title', 'Quản lý đơn hàng')
@section('page-title', 'Quản lý đơn hàng')

@section('content')

{{-- Status Tabs --}}
<div class="d-flex gap-2 mb-4" style="gap:8px;flex-wrap:wrap;">
    @php
        $tabs = [
            ''           => ['label' => 'Tất cả',     'count' => $statusCounts['all'],        'color' => 'secondary'],
            'Chờ xử lý'  => ['label' => 'Chờ xử lý',  'count' => $statusCounts['pending'],    'color' => 'warning'],
            'Đang giao'  => ['label' => 'Đang giao',   'count' => $statusCounts['processing'], 'color' => 'info'],
            'Hoàn thành' => ['label' => 'Hoàn thành',  'count' => $statusCounts['completed'],  'color' => 'success'],
            'Đã hủy'     => ['label' => 'Đã hủy',      'count' => $statusCounts['cancelled'],  'color' => 'danger'],
        ];
        $currentStatus = request('status', '');
    @endphp
    @foreach($tabs as $val => $tab)
        <a href="{{ route('admin.orders.index', array_merge(request()->except('status','page'), $val ? ['status' => $val] : [])) }}"
           class="btn btn-sm btn-{{ $currentStatus === $val ? $tab['color'] : 'outline-' . $tab['color'] }}">
            {{ $tab['label'] }} <span class="badge badge-light ml-1">{{ $tab['count'] }}</span>
        </a>
    @endforeach
</div>

{{-- Filters --}}
<div class="admin-card mb-4">
    <form method="GET" action="{{ route('admin.orders.index') }}" class="row align-items-end">
        <input type="hidden" name="status" value="{{ request('status') }}">
        <div class="col-md-4 mb-2">
            <label class="small font-weight-bold">Tìm kiếm</label>
            <input type="text" name="search" class="form-control form-control-sm"
                placeholder="Mã đơn, tên, SĐT..." value="{{ request('search') }}">
        </div>
        <div class="col-md-2 mb-2">
            <label class="small font-weight-bold">Thanh toán</label>
            <select name="payment_status" class="form-control form-control-sm">
                <option value="">Tất cả</option>
                <option value="pending"  {{ request('payment_status') === 'pending'  ? 'selected' : '' }}>Chờ TT</option>
                <option value="paid"     {{ request('payment_status') === 'paid'     ? 'selected' : '' }}>Đã TT</option>
                <option value="failed"   {{ request('payment_status') === 'failed'   ? 'selected' : '' }}>Thất bại</option>
                <option value="refunded" {{ request('payment_status') === 'refunded' ? 'selected' : '' }}>Hoàn tiền</option>
            </select>
        </div>
        <div class="col-md-2 mb-2">
            <label class="small font-weight-bold">Từ ngày</label>
            <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}">
        </div>
        <div class="col-md-2 mb-2">
            <label class="small font-weight-bold">Đến ngày</label>
            <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}">
        </div>
        <div class="col-md-2 mb-2">
            <button type="submit" class="btn btn-sm btn-coffee w-100">🔍 Lọc</button>
        </div>
    </form>
</div>

{{-- Table --}}
<div class="admin-table">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Mã đơn</th>
                    <th>Khách hàng</th>
                    <th>SĐT</th>
                    <th>Tổng tiền</th>
                    <th>Phương thức</th>
                    <th>Thanh toán</th>
                    <th>Trạng thái</th>
                    <th>Pha chế</th>
                    <th>Ngày đặt</th>
                    <th style="text-align: center; width: 120px;">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td><strong style="color:#c49b63;">{{ $order->tracking_code }}</strong></td>
                        <td>{{ $order->recipient_name }}</td>
                        <td>{{ $order->phone }}</td>
                        <td><strong>{{ number_format($order->total, 0, ',', '.') }}đ</strong></td>
                        <td><span class="badge badge-secondary">{{ $order->payment_method }}</span></td>
                        <td>
                            @php
                                $pBadge = match($order->payment_status) {
                                    'paid'     => 'success',
                                    'pending'  => 'warning',
                                    'failed'   => 'danger',
                                    'refunded' => 'secondary',
                                    default    => 'secondary',
                                };
                                $pLabel = match($order->payment_status) {
                                    'paid'     => 'Đã TT',
                                    'pending'  => 'Chờ TT',
                                    'failed'   => 'Thất bại',
                                    'refunded' => 'Hoàn tiền',
                                    default    => $order->payment_status,
                                };
                            @endphp
                            <span class="badge badge-{{ $pBadge }}">{{ $pLabel }}</span>
                        </td>
                        <td>
                            @php
                                $sBadge = match($order->status) {
                                    'Chờ xử lý' => 'warning',
                                    'Đang giao'  => 'info',
                                    'Hoàn thành' => 'success',
                                    'Đã hủy'     => 'danger',
                                    default      => 'secondary',
                                };
                            @endphp
                            <span class="badge badge-{{ $sBadge }}">{{ $order->status }}</span>
                        </td>
                        <td>
                            @if($order->drink_status)
                                @php
                                    $dBadge = match($order->drink_status) {
                                        'pending'   => 'warning',
                                        'brewing'   => 'info',
                                        'completed' => 'success',
                                        default     => 'secondary',
                                    };
                                    $dLabel = match($order->drink_status) {
                                        'pending'   => '✅ Nhận',
                                        'brewing'   => '☕ Pha',
                                        'completed' => '🎉 Xong',
                                        default     => $order->drink_status,
                                    };
                                @endphp
                                <span class="badge badge-{{ $dBadge }}">{{ $dLabel }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td style="font-size:12px;color:#888;">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td style="text-align: center;">
                            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-secondary" style="font-size:11px; padding: 4px 10px; border-radius: 6px; display: inline-flex; align-items: center; gap: 4px;">
                                <span class="ion-md-eye"></span> Chi tiết
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center py-4 text-muted">Không có đơn hàng nào</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($orders->hasPages())
        <div class="p-3">{{ $orders->links() }}</div>
    @endif
</div>

@endsection
