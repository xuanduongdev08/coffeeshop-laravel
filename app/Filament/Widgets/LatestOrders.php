<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestOrders extends TableWidget
{
    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = '10 đơn hàng mới nhất';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                fn (): Builder => Order::query()
                    ->with(['user'])
                    ->latest()
                    ->limit(10)
            )
            ->columns([
                TextColumn::make('tracking_code')
                    ->label('Mã đơn')
                    ->weight('bold')
                    ->copyable(),
                TextColumn::make('user.name')
                    ->label('Khách hàng')
                    ->default('Khách vãng lai'),
                TextColumn::make('recipient_name')
                    ->label('Người nhận'),
                TextColumn::make('total')
                    ->label('Tổng tiền')
                    ->formatStateUsing(fn ($state) => number_format($state, 0, ',', '.') . 'đ'),
                TextColumn::make('payment_method')
                    ->label('Thanh toán')
                    ->badge(),
                TextColumn::make('payment_status')
                    ->label('TT Thanh toán')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'paid'    => 'success',
                        'pending' => 'warning',
                        'failed'  => 'danger',
                        default   => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'paid'    => 'Đã thanh toán',
                        'pending' => 'Chờ thanh toán',
                        'failed'  => 'Thất bại',
                        default   => $state,
                    }),
                TextColumn::make('status')
                    ->label('Trạng thái')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'Chờ xử lý' => 'warning',
                        'Đang giao'  => 'info',
                        'Hoàn thành' => 'success',
                        'Đã hủy'     => 'danger',
                        default      => 'gray',
                    }),
                TextColumn::make('drink_status')
                    ->label('Pha chế')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'pending'   => 'warning',
                        'brewing'   => 'info',
                        'completed' => 'success',
                        default     => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'pending'   => '✅ Đã nhận',
                        'brewing'   => '☕ Đang pha',
                        'completed' => '🎉 Xong',
                        default     => '—',
                    }),
                TextColumn::make('created_at')
                    ->label('Thời gian')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->paginated(false);
    }
}
