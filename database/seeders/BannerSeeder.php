<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    public function run(): void
    {
        $banners = [
            [
                'title'      => 'Trải nghiệm cà phê tuyệt vời nhất',
                'image'      => 'images/bg_1.jpg',
                'link'       => '/san-pham',
                'position'   => 'home',
                'is_active'  => true,
                'sort_order' => 1,
            ],
            [
                'title'      => 'Hương vị tuyệt vời & Không gian đẹp',
                'image'      => 'images/bg_2.jpg',
                'link'       => '/san-pham',
                'position'   => 'home',
                'is_active'  => true,
                'sort_order' => 2,
            ],
            [
                'title'      => 'Nóng hổi và sẵn sàng phục vụ',
                'image'      => 'images/bg_3.jpg',
                'link'       => '/san-pham',
                'position'   => 'home',
                'is_active'  => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($banners as $banner) {
            Banner::create($banner);
        }

        $this->command->info('✅ Đã tạo ' . count($banners) . ' banner.');
    }
}
