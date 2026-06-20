<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductSize;
use Illuminate\Database\Seeder;

class ProductSizeSeeder extends Seeder
{
    /**
     * Tạo bảng giá M/L/XL cho tất cả sản phẩm đồ uống dùng ly (has_size = true).
     *
     * Quy tắc giá:
     *  - M  = giá gốc - 10.000đ  (size nhỏ, giá thấp hơn)
     *  - L  = giá gốc             (size vừa, giá niêm yết)
     *  - XL = giá gốc + 10.000đ  (size lớn, giá cao hơn)
     */
    public function run(): void
    {
        // Lấy tất cả sản phẩm có has_size = true
        $drinkProducts = Product::where('has_size', true)->get();

        if ($drinkProducts->isEmpty()) {
            $this->command->warn('⚠️  Không có sản phẩm nào có has_size=true. Hãy chạy ProductSeeder trước.');
            return;
        }

        $count = 0;
        foreach ($drinkProducts as $product) {
            $basePrice = (float) $product->price;

            $sizes = [
                ['size' => 'M',  'price' => max(0, $basePrice - 10000)],
                ['size' => 'L',  'price' => $basePrice],
                ['size' => 'XL', 'price' => $basePrice + 10000],
            ];

            foreach ($sizes as $sizeData) {
                ProductSize::updateOrCreate(
                    ['product_id' => $product->id, 'size' => $sizeData['size']],
                    ['price' => $sizeData['price'], 'is_active' => true]
                );
                $count++;
            }
        }

        $this->command->info("✅ ProductSizeSeeder: {$count} bản ghi size cho {$drinkProducts->count()} sản phẩm đã được tạo.");
    }
}
