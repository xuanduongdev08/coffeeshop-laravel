<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('recipient_name');
            $table->text('shipping_address');
            $table->string('phone', 20);
            $table->decimal('subtotal', 10, 2);
            $table->decimal('shipping_fee', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->string('payment_method')->default('COD')
                  ->comment('COD, VietQR, MoMo, VNPay');
            $table->string('payment_status')->default('pending')
                  ->comment('pending, paid, failed');
            $table->string('status')->default('Chờ xử lý')
                  ->comment('Chờ xử lý, Đang giao, Hoàn thành, Đã hủy');
            $table->string('tracking_code')->nullable()
                  ->comment('Mã theo dõi đơn hàng, VD: XD0001');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
