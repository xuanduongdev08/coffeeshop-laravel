@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

{{-- Stat Cards --}}
<div class="row mb-4">
    <div class="col-lg-4 col-md-6 col-sm-6 mb-4">
        <div class="stat-card card-orders h-100">
            <div>
                <div class="stat-value">{{ number_format($stats['total_orders']) }}</div>
                <div class="stat-label">Tổng đơn hàng</div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-6 mb-4">
        <div class="stat-card card-pending h-100">
            <div>
                <div class="stat-value" style="color:#e74c3c;">{{ number_format($stats['pending_orders']) }}</div>
                <div class="stat-label">Chờ xử lý</div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-6 mb-4">
        <div class="stat-card card-revenue h-100">
            <div>
                <div class="stat-value" style="color:#27ae60;">{{ number_format($stats['total_revenue'], 0, ',', '.') }}đ</div>
                <div class="stat-label">Doanh thu</div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-6 mb-4">
        <div class="stat-card card-customers h-100">
            <div>
                <div class="stat-value" style="color:#3498db;">{{ number_format($stats['total_customers']) }}</div>
                <div class="stat-label">Khách hàng</div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-6 mb-4">
        <div class="stat-card card-products h-100">
            <div>
                <div class="stat-value" style="color:#9b59b6;">{{ number_format($stats['total_products']) }}</div>
                <div class="stat-label">Sản phẩm</div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-6 mb-4">
        <div class="stat-card card-warning h-100">
            <div>
                <div class="stat-value" style="color:#e67e22;">{{ number_format($stats['low_stock']) }}</div>
                <div class="stat-label">Sắp hết hàng</div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    {{-- Revenue Chart --}}
    <div class="col-12 mb-4">
        <div class="admin-card">
            <h5><span class="ion-md-stats" style="margin-right:6px;color:#c49b63;opacity:0.6;"></span>Doanh thu 7 ngày gần nhất</h5>
            <canvas id="revenueChart" height="80"></canvas>
        </div>
    </div>
</div>

{{-- Latest Orders --}}
<div class="admin-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 style="margin:0;border:none;padding:0;"><span class="ion-md-clipboard" style="margin-right:6px;color:#c49b63;opacity:0.6;"></span>Đơn hàng mới nhất</h5>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-coffee">Xem tất cả</a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead style="background:#f8f9fa;">
                <tr>
                    <th>Mã đơn</th>
                    <th>Khách hàng</th>
                    <th>Tổng tiền</th>
                    <th>Thanh toán</th>
                    <th>Trạng thái</th>
                    <th>Ngày đặt</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($latestOrders as $order)
                    <tr>
                        <td><strong style="color:#c49b63;">{{ $order->tracking_code }}</strong></td>
                        <td>{{ $order->recipient_name }}</td>
                        <td>{{ number_format($order->total, 0, ',', '.') }}đ</td>
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
                        <td style="font-size:12px;color:#888;">{{ $order->created_at->format('d/m H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-secondary" style="font-size:11px;">Chi tiết</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script>
var ctx = document.getElementById('revenueChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($chartLabels) !!},
        datasets: [
            {
                label: 'Doanh thu (đ)',
                data: {!! json_encode($chartRevenue) !!},
                backgroundColor: 'rgba(196,155,99,0.7)',
                borderColor: '#c49b63',
                borderWidth: 2,
                borderRadius: 6,
                yAxisID: 'y',
            },
            {
                label: 'Số đơn',
                data: {!! json_encode($chartOrders) !!},
                type: 'line',
                borderColor: '#6f4e37',
                backgroundColor: 'rgba(111,78,55,0.1)',
                borderWidth: 2,
                pointRadius: 4,
                tension: 0.4,
                yAxisID: 'y1',
            }
        ]
    },
    options: {
        responsive: true,
        interaction: { mode: 'index', intersect: false },
        plugins: { legend: { position: 'top' } },
        scales: {
            y: {
                type: 'linear', position: 'left',
                ticks: { callback: v => new Intl.NumberFormat('vi-VN').format(v) + 'đ' }
            },
            y1: {
                type: 'linear', position: 'right',
                grid: { drawOnChartArea: false },
                ticks: { stepSize: 1 }
            }
        }
    }
});
</script>
@endpush
