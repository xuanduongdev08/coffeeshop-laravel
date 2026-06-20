<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Thông tin cơ bản')
                    ->columns(2)
                    ->schema([
                        Select::make('category_id')
                            ->label('Danh mục')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('name')
                            ->label('Tên sản phẩm')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('slug')
                            ->label('Slug (URL)')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        Textarea::make('description')
                            ->label('Mô tả')
                            ->columnSpanFull()
                            ->rows(3),
                    ]),

                Section::make('Giá & Kho hàng')
                    ->columns(3)
                    ->schema([
                        TextInput::make('price')
                            ->label('Giá gốc (đ)')
                            ->required()
                            ->numeric()
                            ->prefix('₫')
                            ->minValue(0),
                        TextInput::make('discount_price')
                            ->label('Giá khuyến mãi (đ)')
                            ->numeric()
                            ->prefix('₫')
                            ->minValue(0),
                        TextInput::make('stock')
                            ->label('Tồn kho')
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->minValue(0),
                    ]),

                Section::make('Hình ảnh')
                    ->schema([
                        FileUpload::make('image')
                            ->label('Ảnh sản phẩm')
                            ->image()
                            ->directory('products')
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('1:1')
                            ->columnSpanFull(),
                    ]),

                Section::make('Trạng thái & Tùy chọn')
                    ->columns(2)
                    ->schema([
                        Toggle::make('is_active')
                            ->label('Đang bán')
                            ->default(true),
                        Toggle::make('is_featured')
                            ->label('Nổi bật (trang chủ)')
                            ->default(false),
                    ]),

                Section::make('Tùy chỉnh đồ uống')
                    ->description('Cấu hình cho sản phẩm đồ uống (cà phê, trà, nước trái cây)')
                    ->columns(3)
                    ->schema([
                        Toggle::make('has_size')
                            ->label('Có size M/L/XL')
                            ->helperText('Đồ uống dùng ly'),
                        Toggle::make('has_topping')
                            ->label('Có topping')
                            ->helperText('Trà / nước trái cây'),
                        Toggle::make('allow_sugar')
                            ->label('Chọn đường')
                            ->default(true),
                        Toggle::make('allow_ice')
                            ->label('Chọn đá')
                            ->default(true),
                        Toggle::make('allow_milk')
                            ->label('Chọn loại sữa')
                            ->default(false),
                    ]),
            ]);
    }
}
