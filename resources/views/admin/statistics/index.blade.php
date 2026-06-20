@extends('layouts.admin')

@section('title', 'Thống kê doanh thu')
@section('page-title', 'Thống kê doanh thu')

@section('content')

{{-- Period Filter --}}
<div class="d-flex align-items-center gap-3 mb-4" style="gap:10px;flex-wrap:wrap;">
    @foreach([7 => '7 ngày', 30 => '30 ngày', 90 => '3 tháng', 365 => '1 năm'] as $days => $label)
        <a href="{{ route('admin.statistics.index', ['period' => $days]) }}"
           class="btn btn-sm {{ $period == $days ? 'btn-coffee' : 'btn-outline-secondary' }}">
            {{ $label }}
        </a>
    @endforeach
    <a href="{{ route('admin.statistics.export', ['period' => $period]) }}"
       class="btn btn-sm btn-outline-success ml-auto">
        📥 Xuất Excel
    </a>
</div>

{{-- Summary Cards --}}
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="stat-card" style="border-left-color:#27ae60;">
            <div class="stat-value" style="color:#27ae60;font-size:20px;">
                {{ number_format($totalRevenue, 0, ',', '.') }}đ
            </div>
            <div class="stat-label">Tổng doanh thu</div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stat-card">
            <div class="stat-value">{{ number_format($totalOrders) }}</div>
            <div class="stat-label">Đơn hàng đã thanh toán</div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stat-card" style="border-left-color:#3498db;">
            <div class="stat-value" style="color:#3498db;font-size:20px;">
                {{ $totalOrders > 0 ? number_format($totalRevenue / $totalOrders, 0, ',', '.') : 0 }}đ
            </div>
            <div class="stat-label">Giá trị đơn TB</div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stat-card" style="border-left-color:#9b59b6;">
            <div class="stat-value" style="color:#9b59b6;">{{ number_format($newCustomers) }}</div>
            <div class="stat-label">Khách hàng mới</div>
        </div>
    </div>
</div>

<div class="row mb-4">
    {{-- Revenue Chart --}}
    <div class="col-md-8 mb-4">
        <div class="admin-card">
            <h5>📈 Doanh thu theo ngày</h5>
            <canvas id="revenueChart" height="120"></canvas>
        </div>
    </div>

    {{-- Payment Methods --}}
    <div class="col-md-4 mb-4">
        <div class="admin-card">
            <h5>💳 Phương thức thanh toán</h5>
            <canvas id="paymentChart" height="200"></canvas>
            <div class="mt-3">
                @foreach($paymentMethods as $pm)
                    <div class="d-flex justify-content-between mb-1" style="font-size:13px;">
                        <span>{{ $pm->payment_method }}</span>
                        <strong>{{ number_format($pm->revenue, 0, ',', '.') }}đ ({{ $pm->count }} đơn)</strong>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<div class="row">
    {{-- Top Products --}}
    <div class="col-md-7 mb-4">
        <div class="admin-card">
            <h5>🏆 Sản phẩm bán chạy</h5>
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead style="background:#f8f9fa;">
                        <tr>
                            <th>#</th>
                            <th>Sản phẩm</th>
                            <th class="text-center">SL bán</th>
                            <th class="text-right">Doanh thu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topProducts as $i => $product)
                            <tr>
                                <td>
                                    @if($i < 3)
                                        <span style="font-size:16px;">{{ ['🥇','🥈','🥉'][$i] }}</span>
                                    @else
                                        {{ $i + 1 }}
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        @if($product->image)
                                            <img src="{{ asset($product->image) }}" alt=""
                                                style="width:32px;height:32px;object-fit:cover;border-radius:4px;">
                                        @endif
                                        <span style="font-size:13px;">{{ $product->name }}</span>
                                    </div>
                                </td>
                                <td class="text-center"><strong>{{ number_format($product->total_qty) }}</strong></td>
                                <td class="text-right" style="color:#c49b63;font-weight:600;">
                                    {{ number_format($product->total_revenue, 0, ',', '.') }}đ
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Revenue by Category --}}
    <div class="col-md-5 mb-4">
        <div class="admin-card">
            <h5>📂 Doanh thu theo danh mục</h5>
            @php $maxRevenue = $revenueByCategory->max('revenue') ?: 1; @endphp
            @foreach($revenueByCategory as $cat)
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1" style="font-size:13px;">
                        <span>{{ $cat->name }}</span>
                        <strong>{{ number_format($cat->revenue, 0, ',', '.') }}đ</strong>
                    </div>
                    <div class="progress" style="height:8px;border-radius:4px;">
                        <div class="progress-bar" style="width:{{ ($cat->revenue / $maxRevenue) * 100 }}%;background:#c49b63;border-radius:4px;"></div>
                    </div>
                </div>
            @endforeach
            @if($revenueByCategory->isEmpty())
                <p class="text-muted text-center py-3">Chưa có dữ liệu</p>
            @endif
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Revenue chart
var revenueCtx = document.getElementById('revenueChart').getContext('2d');
new Chart(revenueCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode($revenueByDay->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d/m'))->values()) !!},
        datasets: [{
            label: 'Doanh thu (đ)',
            data: {!! json_encode($revenueByDay->pluck('revenue')) !!},
            borderColor: '#c49b63',
            backgroundColor: 'rgba(196,155,99,0.1)',
            borderWidth: 2,
            fill: true,
            tension: 0.4,
            pointRadius: 4,
            pointBackgroundColor: '#c49b63',
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { ticks: { callback: v => new Intl.NumberFormat('vi-VN').format(v) + 'đ' } }
        }
    }
});

// Payment chart
var payCtx = document.getElementById('paymentChart').getContext('2d');
new Chart(payCtx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($paymentMethods->pluck('payment_method')) !!},
        datasets: [{
            data: {!! json_encode($paymentMethods->pluck('count')) !!},
            backgroundColor: ['#c49b63','#6f4e37','#8b6f47','#d4a96a','#a07850'],
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'bottom', labels: { font: { size: 11 } } } }
    }
});
</script>
@endpush
