<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chat_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('session_id', 128);
            $table->enum('role', ['user', 'assistant', 'system']);
            $table->text('message');
            $table->string('intent', 100)->nullable()
                  ->comment('product_lookup, order_tracking, ordering, recommendation, weather, mood, general');
            $table->string('language', 10)->default('vi');
            $table->json('metadata')->nullable()
                  ->comment('Dữ liệu bổ sung: sản phẩm gợi ý, thời tiết, tâm trạng');
            $table->timestamps();

            $table->index('session_id');
            $table->index('intent');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_logs');
    }
};
