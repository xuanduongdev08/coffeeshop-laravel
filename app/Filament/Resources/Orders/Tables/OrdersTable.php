<?php

namespace App\Filament\Resources\Orders\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tracking_code')
                    ->label('Mã đơn')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->copyable(),
                TextColumn::make('user.name')
                    ->label('Khách hàng')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('recipient_name')
                    ->label('Người nhận')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('phone')
                    ->label('SĐT')
                    ->searchable(),
                TextColumn::make('total')
                    ->label('Tổng tiền')
                    ->formatStateUsing(fn ($state) => number_format($state, 0, ',', '.') . 'đ')
                    ->sortable(),
                TextColumn::make('payment_method')
                    ->label('Thanh toán')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'COD'    => 'gray',
                        'VietQR' => 'info',
                        'MoMo'   => 'pink',
                        'VNPay'  => 'success',
                        default  => 'gray',
                    }),
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
                    ->label('Ngày đặt')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'Chờ xử lý' => 'Chờ xử lý',
                        'Đang giao'  => 'Đang giao',
                        'Hoàn thành' => 'Hoàn thành',
                        'Đã hủy'     => 'Đã hủy',
                    ]),
                SelectFilter::make('payment_status')
                    ->label('TT Thanh toán')
                    ->options([
                        'pending' => 'Chờ thanh toán',
                        'paid'    => 'Đã thanh toán',
                        'failed'  => 'Thất bại',
                    ]),
                SelectFilter::make('payment_method')
                    ->label('Phương thức')
                    ->options([
                        'COD'    => 'COD',
                        'VietQR' => 'VietQR',
                        'MoMo'   => 'MoMo',
                        'VNPay'  => 'VNPay',
                    ]),
                SelectFilter::make('drink_status')
                    ->label('Pha chế')
                    ->options([
                        'pending'   => 'Đã nhận đơn',
                        'brewing'   => 'Đang pha chế',
                        'completed' => 'Đã hoàn thành',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
