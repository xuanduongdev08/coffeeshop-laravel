<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bảng modifiers: Danh sách tất cả tùy chọn có thể chọn cho sản phẩm.
     *
     * type:
     *   - sugar  : Mức đường (ít, nửa, bình thường, thêm) — không tính tiền thêm
     *   - ice    : Mức đá   (không, ít, bình thường, thêm) — không tính tiền thêm
     *   - milk   : Loại sữa (sữa tươi, sữa đặc)           — tính tiền thêm
     *   - topping: Topping  (trân châu, thạch...)           — tính tiền thêm, chỉ cho trà/juice
     *
     * applies_to_drink     : áp dụng cho đồ uống dùng ly (cà phê, trà, nước)
     * applies_to_tea_juice : áp dụng cho trà / nước trái cây (topping)
     */
    public function up(): void
    {
        Schema::create('modifiers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('VD: Ít đường, Sữa tươi, Trân châu trắng');
            $table->enum('type', ['sugar', 'ice', 'milk', 'topping']);
            $table->decimal('extra_price', 8, 2)->default(0)
                  ->comment('Phụ phí thêm vào đơn giá (0 = miễn phí)');
            $table->boolean('applies_to_drink')->default(true)
                  ->comment('Áp dụng cho đồ uống dùng ly');
            $table->boolean('applies_to_tea_juice')->default(false)
                  ->comment('Áp dụng cho trà / nước trái cây (topping)');
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0)
                  ->comment('Thứ tự hiển thị trong UI');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('modifiers');
    }
};
