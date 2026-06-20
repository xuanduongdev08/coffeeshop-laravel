<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bảng order_item_modifiers: Lưu modifier đã được chọn cho mỗi order_item.
     *
     * extra_price_snapshot: Giá của modifier tại thời điểm đặt hàng (snapshot).
     * Dùng snapshot để giá không thay đổi khi admin chỉnh sửa modifier sau này.
     */
    public function up(): void
    {
        Schema::create('order_item_modifiers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_item_id')
                  ->constrained('order_items')
                  ->cascadeOnDelete();
            $table->foreignId('modifier_id')
                  ->constrained('modifiers')
                  ->cascadeOnDelete();
            $table->decimal('extra_price_snapshot', 8, 2)->default(0)
                  ->comment('Snapshot giá modifier lúc đặt hàng');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_item_modifiers');
    }
};
