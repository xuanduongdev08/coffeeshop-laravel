<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Thêm các cột theo dõi trạng thái pha chế vào bảng orders.
     *
     * drink_status : Chỉ dùng cho đơn có sản phẩm dùng ly (cà phê, trà, nước).
     *   - null      = đơn không có sản phẩm đồ uống (vd: chỉ có bánh, hạt cà phê)
     *   - pending   = Đã nhận đơn
     *   - brewing   = Đang pha chế
     *   - completed = Đã hoàn thành
     *
     * brewing_at   : Thời điểm bắt đầu pha chế (nhân viên bấm nút)
     * completed_at : Thời điểm hoàn thành      (nhân viên bấm nút)
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('drink_status', ['pending', 'brewing', 'completed'])
                  ->nullable()
                  ->after('notes')
                  ->comment('Trạng thái pha chế: null=không phải đơn đồ uống');
            $table->timestamp('brewing_at')->nullable()->after('drink_status')
                  ->comment('Thời điểm bắt đầu pha chế');
            $table->timestamp('completed_at')->nullable()->after('brewing_at')
                  ->comment('Thời điểm hoàn thành đồ uống');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['drink_status', 'brewing_at', 'completed_at']);
        });
    }
};
