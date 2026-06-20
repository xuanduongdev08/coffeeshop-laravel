<x-filament-panels::page>
    {{-- Bộ lọc kỳ thống kê --}}
    <div class="flex gap-2 mb-6">
        @foreach(['7' => '7 ngày', '30' => '30 ngày', '90' => '90 ngày', '365' => '1 năm'] as $value => $label)
            <a href="{{ request()->fullUrlWithQuery(['period' => $value]) }}"
               class="px-4 py-2 rounded-lg text-sm font-medium transition
                      {{ $this->period == $value
                          ? 'bg-amber-500 text-white'
                          : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600 hover:bg-gray-50' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    @php
        $data = $this->getViewData();
        extract($data);
    @endphp

    {{-- Tổng quan --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow-sm border border-gray-200 dark:border-gray-700">
            <p class="text-sm text-gray-500 dark:text-gray-400">Tổng doanh thu</p>
            <p class="text-2xl font-bold text-green-600 mt-1">{{ number_format($totalRevenue, 0, ',', '.') }}đ</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow-sm border border-gray-200 dark:border-gray-700">
            <p class="text-sm text-gray-500 dark:text-gray-400">Tổng đơn hàng</p>
            <p class="text-2xl font-bold text-blue-600 mt-1">{{ number_format($totalOrders) }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow-sm border border-gray-200 dark:border-gray-700">
            <p class="text-sm text-gray-500 dark:text-gray-400">Khách hàng mới</p>
            <p class="text-2xl font-bold text-purple-600 mt-1">{{ number_format($newCustomers) }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Top sản phẩm bán chạy --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow-sm border border-gray-200 dark:border-gray-700">
            <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4">🏆 Top 10 sản phẩm bán chạy</h3>
            <div class="space-y-3">
                @forelse($topProducts as $i => $item)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="w-6 h-6 rounded-full bg-amber-100 text-amber-700 text-xs font-bold flex items-center justify-center">
                                {{ $i + 1 }}
                            </span>
                            <span class="text-sm text-gray-700 dark:text-gray-300">{{ $item->product_name }}</span>
                        </div>
                        <div class="text-right">
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ number_format($item->total_qty) }} ly</span>
                            <p class="text-xs text-gray-500">{{ number_format($item->total_revenue, 0, ',', '.') }}đ</p>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">Chưa có dữ liệu</p>
                @endforelse
            </div>
        </div>

        {{-- Phương thức thanh toán --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow-sm border border-gray-200 dark:border-gray-700">
            <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4">💳 Phương thức thanh toán</h3>
            <div class="space-y-3">
                @php $totalPaid = $paymentMethods->sum('revenue'); @endphp
                @forelse($paymentMethods as $method)
                    @php $pct = $totalPaid > 0 ? round($method->revenue / $totalPaid * 100) : 0; @endphp
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-gray-700 dark:text-gray-300 font-medium">{{ $method->payment_method }}</span>
                            <span class="text-gray-500">{{ $method->count }} đơn · {{ number_format($method->revenue, 0, ',', '.') }}đ</span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                            <div class="bg-amber-500 h-2 rounded-full" style="width: {{ $pct }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">Chưa có dữ liệu</p>
                @endforelse
            </div>
        </div>

        {{-- Doanh thu theo ngày --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow-sm border border-gray-200 dark:border-gray-700 lg:col-span-2">
            <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4">📈 Doanh thu theo ngày</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 dark:border-gray-700">
                            <th class="text-left py-2 text-gray-500 font-medium">Ngày</th>
                            <th class="text-right py-2 text-gray-500 font-medium">Số đơn</th>
                            <th class="text-right py-2 text-gray-500 font-medium">Doanh thu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($revenueByDay as $row)
                            <tr class="border-b border-gray-100 dark:border-gray-700/50 hover:bg-gray-50 dark:hover:bg-gray-700/30">
                                <td class="py-2 text-gray-700 dark:text-gray-300">
                                    {{ \Carbon\Carbon::parse($row->date)->format('d/m/Y') }}
                                </td>
                                <td class="py-2 text-right text-gray-600 dark:text-gray-400">{{ $row->orders }}</td>
                                <td class="py-2 text-right font-semibold text-green-600">
                                    {{ number_format($row->revenue, 0, ',', '.') }}đ
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-4 text-center text-gray-500">Chưa có dữ liệu</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-filament-panels::page>
