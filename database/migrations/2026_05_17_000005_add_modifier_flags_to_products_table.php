<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Thêm các cột phân loại sản phẩm vào bảng products:
     *
     * has_size         : true  = đồ uống dùng ly (cà phê, trà...) → có bảng product_sizes
     * has_topping      : true  = trà / nước trái cây → cho phép chọn topping
     * allow_sugar      : true  = cho phép chọn mức đường
     * allow_ice        : true  = cho phép chọn mức đá
     * allow_milk       : true  = cho phép chọn loại sữa (sữa tươi / sữa đặc)
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('has_size')->default(false)->after('is_featured')
                  ->comment('true = đồ uống dùng ly, có bảng product_sizes (M/L/XL)');
            $table->boolean('has_topping')->default(false)->after('has_size')
                  ->comment('true = trà / nước trái cây, cho phép thêm topping');
            $table->boolean('allow_sugar')->default(true)->after('has_topping')
                  ->comment('Cho phép khách chọn mức đường');
            $table->boolean('allow_ice')->default(true)->after('allow_sugar')
                  ->comment('Cho phép khách chọn mức đá');
            $table->boolean('allow_milk')->default(false)->after('allow_ice')
                  ->comment('Cho phép khách chọn loại sữa (sữa tươi / sữa đặc)');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['has_size', 'has_topping', 'allow_sugar', 'allow_ice', 'allow_milk']);
        });
    }
};
