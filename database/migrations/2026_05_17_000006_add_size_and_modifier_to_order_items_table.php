<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Thêm cột modifier & size vào bảng order_items:
     *
     * size           : Size đã chọn (M / L / XL), null nếu sản phẩm không có size
     * base_price     : Giá gốc theo size (snapshot lúc đặt)
     * modifier_extra : Tổng phụ phí modifier đã chọn (snapshot lúc đặt)
     * unit_price     : Đơn giá cuối = base_price + modifier_extra
     *
     * Cột price cũ được giữ lại để tương thích ngược (dần thay bằng unit_price).
     */
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->enum('size', ['M', 'L', 'XL'])->nullable()->after('product_image')
                  ->comment('Size đã chọn, null nếu sản phẩm không có size');
            $table->decimal('base_price', 10, 2)->default(0)->after('size')
                  ->comment('Giá gốc theo size tại thời điểm đặt');
            $table->decimal('modifier_extra', 8, 2)->default(0)->after('base_price')
                  ->comment('Tổng phụ phí modifier (topping, sữa...)');
            $table->decimal('unit_price', 10, 2)->default(0)->after('modifier_extra')
                  ->comment('Đơn giá cuối = base_price + modifier_extra');
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['size', 'base_price', 'modifier_extra', 'unit_price']);
        });
    }
};
