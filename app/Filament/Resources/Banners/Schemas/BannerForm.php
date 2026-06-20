<?php

namespace App\Filament\Resources\Banners\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class BannerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Thông tin banner')
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->label('Tiêu đề')
                            ->maxLength(255),
                        TextInput::make('link')
                            ->label('Đường dẫn (URL)')
                            ->url()
                            ->maxLength(500),
                        Select::make('position')
                            ->label('Vị trí hiển thị')
                            ->required()
                            ->options([
                                'home'     => 'Trang chủ (slider)',
                                'sidebar'  => 'Sidebar',
                                'category' => 'Trang danh mục',
                            ])
                            ->default('home'),
                        TextInput::make('sort_order')
                            ->label('Thứ tự')
                            ->required()
                            ->numeric()
                            ->default(0),
                        Toggle::make('is_active')
                            ->label('Hiển thị')
                            ->default(true),
                        FileUpload::make('image')
                            ->label('Ảnh banner')
                            ->image()
                            ->required()
                            ->directory('banners')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
