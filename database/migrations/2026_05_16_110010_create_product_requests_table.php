<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_requests', function (Blueprint $table) {
            $table->id();
            $table->string('product_name');
            $table->integer('request_count')->default(1);
            $table->text('sample_query')->nullable()
                  ->comment('Câu hỏi mẫu từ khách hàng');
            $table->enum('status', ['new', 'reviewed', 'added', 'rejected'])->default('new');
            $table->timestamp('last_requested_at')->nullable();
            $table->timestamps();

            $table->index('product_name');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_requests');
    }
};
