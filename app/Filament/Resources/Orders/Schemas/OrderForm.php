<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Thông tin khách hàng')
                    ->columns(2)
                    ->schema([
                        Select::make('user_id')
                            ->label('Khách hàng')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload(),
                        TextInput::make('recipient_name')
                            ->label('Tên người nhận')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('phone')
                            ->label('Số điện thoại')
                            ->tel()
                            ->required()
                            ->maxLength(20),
                        Textarea::make('shipping_address')
                            ->label('Địa chỉ giao hàng')
                            ->required()
                            ->columnSpanFull(),
                        Textarea::make('notes')
                            ->label('Ghi chú')
                            ->columnSpanFull(),
                    ]),

                Section::make('Thanh toán')
                    ->columns(3)
                    ->schema([
                        TextInput::make('subtotal')
                            ->label('Tạm tính (đ)')
                            ->required()
                            ->numeric()
                            ->prefix('₫'),
                        TextInput::make('shipping_fee')
                            ->label('Phí vận chuyển (đ)')
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->prefix('₫'),
                        TextInput::make('total')
                            ->label('Tổng cộng (đ)')
                            ->required()
                            ->numeric()
                            ->prefix('₫'),
                        Select::make('payment_method')
                            ->label('Phương thức thanh toán')
                            ->required()
                            ->options([
                                'COD'    => 'COD (Tiền mặt)',
                                'VietQR' => 'VietQR',
                                'MoMo'   => 'MoMo',
                                'VNPay'  => 'VNPay',
                            ])
                            ->default('COD'),
                        Select::make('payment_status')
                            ->label('Trạng thái thanh toán')
                            ->required()
                            ->options([
                                'pending' => 'Chờ thanh toán',
                                'paid'    => 'Đã thanh toán',
                                'failed'  => 'Thất bại',
                            ])
                            ->default('pending'),
                    ]),

                Section::make('Trạng thái đơn hàng')
                    ->columns(2)
                    ->schema([
                        TextInput::make('tracking_code')
                            ->label('Mã theo dõi')
                            ->disabled(),
                        Select::make('status')
                            ->label('Trạng thái')
                            ->required()
                            ->options([
                                'Chờ xử lý' => 'Chờ xử lý',
                                'Đang giao'  => 'Đang giao',
                                'Hoàn thành' => 'Hoàn thành',
                                'Đã hủy'     => 'Đã hủy',
                            ])
                            ->default('Chờ xử lý'),
                        Select::make('drink_status')
                            ->label('Trạng thái pha chế')
                            ->options([
                                'pending'   => '✅ Đã nhận đơn',
                                'brewing'   => '☕ Đang pha chế',
                                'completed' => '🎉 Đã hoàn thành',
                            ])
                            ->nullable(),
                        DateTimePicker::make('brewing_at')
                            ->label('Bắt đầu pha chế lúc'),
                        DateTimePicker::make('completed_at')
                            ->label('Hoàn thành lúc'),
                    ]),
            ]);
    }
}
