<?php

namespace Database\Seeders;

use App\Models\Modifier;
use Illuminate\Database\Seeder;

class ModifierSeeder extends Seeder
{
    /**
     * Seed danh sách modifier mặc định cho toàn bộ sản phẩm.
     *
     * Quy tắc extra_price:
     *  - sugar / ice : miễn phí (chỉ là tùy chỉnh)
     *  - milk        : tính thêm (khác loại sữa = khác giá)
     *  - topping     : tính thêm (nguyên liệu bổ sung)
     */
    public function run(): void
    {
        $modifiers = [
            // =========================================
            // ĐƯỜNG — áp dụng cho đồ uống & trà/juice
            // =========================================
            [
                'name'                => 'Ít đường (30%)',
                'type'                => 'sugar',
                'extra_price'         => 0,
                'applies_to_drink'    => true,
                'applies_to_tea_juice'=> true,
                'sort_order'          => 1,
            ],
            [
                'name'                => 'Nửa đường (50%)',
                'type'                => 'sugar',
                'extra_price'         => 0,
                'applies_to_drink'    => true,
                'applies_to_tea_juice'=> true,
                'sort_order'          => 2,
            ],
            [
                'name'                => 'Đường bình thường',
                'type'                => 'sugar',
                'extra_price'         => 0,
                'applies_to_drink'    => true,
                'applies_to_tea_juice'=> true,
                'sort_order'          => 3,
            ],
            [
                'name'                => 'Thêm đường',
                'type'                => 'sugar',
                'extra_price'         => 0,
                'applies_to_drink'    => true,
                'applies_to_tea_juice'=> true,
                'sort_order'          => 4,
            ],

            // =========================================
            // ĐÁ — áp dụng cho đồ uống & trà/juice
            // =========================================
            [
                'name'                => 'Không đá',
                'type'                => 'ice',
                'extra_price'         => 0,
                'applies_to_drink'    => true,
                'applies_to_tea_juice'=> true,
                'sort_order'          => 5,
            ],
            [
                'name'                => 'Ít đá',
                'type'                => 'ice',
                'extra_price'         => 0,
                'applies_to_drink'    => true,
                'applies_to_tea_juice'=> true,
                'sort_order'          => 6,
            ],
            [
                'name'                => 'Đá bình thường',
                'type'                => 'ice',
                'extra_price'         => 0,
                'applies_to_drink'    => true,
                'applies_to_tea_juice'=> true,
                'sort_order'          => 7,
            ],
            [
                'name'                => 'Thêm đá',
                'type'                => 'ice',
                'extra_price'         => 0,
                'applies_to_drink'    => true,
                'applies_to_tea_juice'=> true,
                'sort_order'          => 8,
            ],

            // =========================================
            // SỮA — chỉ áp dụng cho đồ uống dùng ly
            // =========================================
            [
                'name'                => 'Sữa tươi',
                'type'                => 'milk',
                'extra_price'         => 5000,
                'applies_to_drink'    => true,
                'applies_to_tea_juice'=> false,
                'sort_order'          => 9,
            ],
            [
                'name'                => 'Sữa đặc',
                'type'                => 'milk',
                'extra_price'         => 3000,
                'applies_to_drink'    => true,
                'applies_to_tea_juice'=> false,
                'sort_order'          => 10,
            ],

            // =========================================
            // TOPPING — chỉ áp dụng cho trà / nước trái cây
            // =========================================
            [
                'name'                => 'Trân châu trắng',
                'type'                => 'topping',
                'extra_price'         => 10000,
                'applies_to_drink'    => false,
                'applies_to_tea_juice'=> true,
                'sort_order'          => 11,
            ],
            [
                'name'                => 'Trân châu đen',
                'type'                => 'topping',
                'extra_price'         => 10000,
                'applies_to_drink'    => false,
                'applies_to_tea_juice'=> true,
                'sort_order'          => 12,
            ],
            [
                'name'                => 'Thạch cà phê',
                'type'                => 'topping',
                'extra_price'         => 8000,
                'applies_to_drink'    => false,
                'applies_to_tea_juice'=> true,
                'sort_order'          => 13,
            ],
            [
                'name'                => 'Thạch lá dứa',
                'type'                => 'topping',
                'extra_price'         => 8000,
                'applies_to_drink'    => false,
                'applies_to_tea_juice'=> true,
                'sort_order'          => 14,
            ],
            [
                'name'                => 'Pudding trứng',
                'type'                => 'topping',
                'extra_price'         => 12000,
                'applies_to_drink'    => false,
                'applies_to_tea_juice'=> true,
                'sort_order'          => 15,
            ],
        ];

        foreach ($modifiers as $data) {
            Modifier::create(array_merge($data, ['is_active' => true]));
        }

        $this->command->info('✅ ModifierSeeder: ' . count($modifiers) . ' modifier đã được tạo.');
    }
}
