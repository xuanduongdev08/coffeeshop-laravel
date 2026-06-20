<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Cà phê',      'description' => 'Hương vị cà phê đích thực',          'sort_order' => 1],
            ['name' => 'Hạt cà phê',  'description' => 'Các loại hạt cà phê nguyên chất',    'sort_order' => 2],
            ['name' => 'Nước trái cây','description' => 'Các loại nước trái cây tươi ngon',  'sort_order' => 3],
            ['name' => 'Bánh ngọt',   'description' => 'Bánh ngọt hảo hạng',                 'sort_order' => 4],
        ];

        foreach ($categories as $cat) {
            Category::create($cat); // slug tự động tạo bởi Sluggable
        }
    }
}
