<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $cafe     = Category::where('name', 'Cà phê')->first()->id;
        $hat      = Category::where('name', 'Hạt cà phê')->first()->id;
        $nuoc     = Category::where('name', 'Nước trái cây')->first()->id;
        $banh     = Category::where('name', 'Bánh ngọt')->first()->id;

        $products = [
            // ===================== Cà phê (dùng ly: has_size, allow_milk) =====================
            ['category_id' => $cafe, 'name' => 'Cà phê Capuccino',         'price' => 59000, 'image' => 'images/menu-1.jpg',    'stock' => 50, 'is_featured' => true,
             'has_size' => true, 'allow_milk' => true,
             'description' => 'Sự kết hợp hoàn hảo giữa Espresso, sữa nóng và bọt sữa mịn màng.'],
            ['category_id' => $cafe, 'name' => 'Cà phê Latte',             'price' => 65000, 'image' => 'images/menu-2.jpg',    'stock' => 45, 'is_featured' => true,
             'has_size' => true, 'allow_milk' => true,
             'description' => 'Vị béo ngậy của sữa hòa quyện cùng hương thơm cà phê Espresso nhẹ nhàng.'],
            ['category_id' => $cafe, 'name' => 'Cà phê Espresso',          'price' => 45000, 'image' => 'images/menu-3.jpg',    'stock' => 60,
             'has_size' => true, 'allow_sugar' => false,
             'description' => 'Cà phê nguyên chất đậm đặc, hương vị mạnh mẽ chuẩn Ý.'],
            ['category_id' => $cafe, 'name' => 'Cà phê Americano',         'price' => 50000, 'image' => 'images/menu-6.jpg',    'stock' => 50,
             'has_size' => true,
             'description' => 'Cà phê Espresso thêm nước nóng, giữ nguyên hương vị nhưng nhẹ nhàng hơn.'],
            ['category_id' => $cafe, 'name' => 'Cà phê Caramel Macchiato', 'price' => 69000, 'image' => 'images/menu-8.jpg',    'stock' => 50, 'is_featured' => true,
             'has_size' => true, 'allow_milk' => true,
             'description' => 'Sự hòa quyện giữa Espresso, sữa nóng và sốt caramel ngọt ngào.'],

            // ===================== Hạt cà phê (không dùng ly, không modifier) =====================
            ['category_id' => $hat,  'name' => 'Hạt Cà Phê Robusta',      'price' => 189000, 'image' => 'images/menu-7.jpg',   'stock' => 30,
             'allow_sugar' => false, 'allow_ice' => false,
             'description' => 'Hương vị đậm đà, mạnh mẽ với hàm lượng caffeine cao.'],
            ['category_id' => $hat,  'name' => 'Hạt Cà Phê Arabica',      'price' => 129000, 'image' => 'images/menu-5.jpg',   'stock' => 35,
             'allow_sugar' => false, 'allow_ice' => false,
             'description' => 'Hương thơm nồng nàn, vị chua thanh nhẹ nhàng và hậu vị ngọt.'],
            ['category_id' => $hat,  'name' => 'Hạt Cà Phê Liberica',     'price' => 150000, 'image' => 'images/menu-9.jpg',   'stock' => 50,
             'allow_sugar' => false, 'allow_ice' => false,
             'description' => 'Hương vị độc đáo, mạnh mẽ với chút vị khói và hậu vị kéo dài.'],
            ['category_id' => $hat,  'name' => 'Hạt Cà Phê Moka',         'price' => 155000, 'image' => 'images/menu-10.jpg',  'stock' => 50,
             'allow_sugar' => false, 'allow_ice' => false,
             'description' => 'Hương thơm nồng nàn, vị chua thanh thoát, nữ hoàng cà phê.'],

            // ===================== Nước trái cây & Trà (has_size, has_topping) =====================
            ['category_id' => $nuoc, 'name' => 'Nước cam nhiệt đới',      'price' => 45000, 'image' => 'images/drink-1.jpg',   'stock' => 100,
             'has_size' => true, 'has_topping' => true,
             'description' => 'Thức uống thanh mát với hương cam thơm lừng và tươi mát.'],
            ['category_id' => $nuoc, 'name' => 'Trà Vải Nhiệt Đới',       'price' => 45000, 'image' => 'images/drink-10.jpg',  'stock' => 100,
             'has_size' => true, 'has_topping' => true,
             'description' => 'Hương vị vải thiều ngọt ngào kết hợp trà tươi mát lạnh.'],
            ['category_id' => $nuoc, 'name' => 'Trà sen vàng',            'price' => 40000, 'image' => 'images/drink-11.jpg',  'stock' => 50,
             'has_size' => true, 'has_topping' => true,
             'description' => 'Trà ô long kết hợp hạt sen bùi bùi và kem sữa béo ngậy.'],
            ['category_id' => $nuoc, 'name' => 'Trà đào cam sả',          'price' => 35000, 'image' => 'images/drink-12.jpg',  'stock' => 50,
             'has_size' => true, 'has_topping' => true,
             'description' => 'Vị thanh ngọt của đào, chua nhẹ của cam và hương sả thơm mát.'],

            // ===================== Bánh ngọt (không dùng ly, không modifier) =====================
            ['category_id' => $banh, 'name' => 'Bánh Mật Ong',            'price' => 55000, 'image' => 'images/dessert-1.jpg', 'stock' => 40,
             'allow_sugar' => false, 'allow_ice' => false,
             'description' => 'Bánh ngọt mật ong mềm mịn, ngọt ngào khó cưỡng.'],
            ['category_id' => $banh, 'name' => 'Bánh Croissant',          'price' => 30000, 'image' => 'images/dessert-7.jpg', 'stock' => 50,
             'allow_sugar' => false, 'allow_ice' => false,
             'description' => 'Bánh sừng bò ngàn lớp, giòn rụm bên ngoài, mềm dai bên trong.'],
            ['category_id' => $banh, 'name' => 'Bánh Tiramisu',           'price' => 40000, 'image' => 'images/dessert-8.jpg', 'stock' => 50,
             'allow_sugar' => false, 'allow_ice' => false,
             'description' => 'Bánh tráng miệng Ý với hương vị cà phê, rượu rum và phô mai Mascarpone.'],
            ['category_id' => $banh, 'name' => 'Bánh Su kem',             'price' => 25000, 'image' => 'images/dessert-9.jpg', 'stock' => 50,
             'allow_sugar' => false, 'allow_ice' => false,
             'description' => 'Vỏ bánh mềm xốp, nhân kem sữa tươi ngọt ngào, mát lạnh.'],
        ];

        foreach ($products as $product) {
            Product::create(array_merge([
                'is_active'    => true,
                'is_featured'  => false,
                'has_size'     => false,
                'has_topping'  => false,
                'allow_sugar'  => true,
                'allow_ice'    => true,
                'allow_milk'   => false,
            ], $product));
        }

    }
}
