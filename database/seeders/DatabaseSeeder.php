<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * Thứ tự quan trọng: Users/Roles trước, sau đó Categories, Products, Banners.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,        // Roles + Permissions + tài khoản mẫu
            CategorySeeder::class,    // 4 danh mục cà phê
            ProductSeeder::class,     // 17 sản phẩm từ DB cũ (có modifier flags)
            BannerSeeder::class,      // 3 banner trang chủ
            ModifierSeeder::class,    // 15 modifier: đường/đá/sữa/topping
            ProductSizeSeeder::class, // Giá M/L/XL cho sản phẩm has_size=true
            EmailTemplateSeeder::class, // Mẫu email mặc định
        ]);
    }

}
