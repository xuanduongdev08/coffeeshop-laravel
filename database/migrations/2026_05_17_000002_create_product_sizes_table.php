<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bảng product_sizes: Lưu giá M / L / XL cho từng sản phẩm đồ uống dùng ly.
     * - M: giá thấp nhất  (giá cơ bản)
     * - L: giá trung bình (giá hiện tại trong products.price)
     * - XL: giá cao nhất
     */
    public function up(): void
    {
        Schema::create('product_sizes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->enum('size', ['M', 'L', 'XL'])->comment('M=nhỏ, L=vừa, XL=lớn');
            $table->decimal('price', 10, 2)->comment('Giá tiền ứng với size này');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Mỗi sản phẩm chỉ được có 1 bản ghi cho mỗi size
            $table->unique(['product_id', 'size']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_sizes');
    }
};
