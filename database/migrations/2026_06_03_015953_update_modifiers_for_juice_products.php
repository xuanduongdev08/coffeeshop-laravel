<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Sugar hiện tại chỉ áp dụng cho đồ uống dùng ly (applies_to_drink), KHÔNG cho juice
        DB::table('modifiers')
            ->where('type', 'sugar')
            ->update(['applies_to_tea_juice' => false]);

        // 2. Thêm 3 mức ngọt riêng cho juice (applies_to_drink = false, applies_to_tea_juice = true)
        $juiceSugars = [
            ['name' => 'Ngọt ít',   'sort_order' => 21],
            ['name' => 'Ngọt vừa',  'sort_order' => 22],
            ['name' => 'Ngọt nhiều','sort_order' => 23],
        ];
        foreach ($juiceSugars as $item) {
            DB::table('modifiers')->insert([
                'name'                 => $item['name'],
                'type'                 => 'sugar',
                'extra_price'          => 0,
                'applies_to_drink'     => false,
                'applies_to_tea_juice' => true,
                'is_active'            => true,
                'sort_order'           => $item['sort_order'],
                'created_at'           => now(),
                'updated_at'           => now(),
            ]);
        }

        // 3. Đổi tên topping
        DB::table('modifiers')
            ->where('type', 'topping')
            ->where('name', 'Thạch cà phê')
            ->update(['name' => 'Thạch vải']);

        DB::table('modifiers')
            ->where('type', 'topping')
            ->where('name', 'Thạch lá dứa')
            ->update(['name' => 'Hạt sen']);

        DB::table('modifiers')
            ->where('type', 'topping')
            ->where('name', 'Pudding trứng')
            ->update(['name' => 'Thạch đào']);
    }

    public function down(): void
    {
        // Revert tên topping
        DB::table('modifiers')->where('type', 'topping')->where('name', 'Thạch vải')->update(['name' => 'Thạch cà phê']);
        DB::table('modifiers')->where('type', 'topping')->where('name', 'Hạt sen')->update(['name' => 'Thạch lá dứa']);
        DB::table('modifiers')->where('type', 'topping')->where('name', 'Thạch đào')->update(['name' => 'Pudding trứng']);

        // Xóa juice sugars
        DB::table('modifiers')->whereIn('name', ['Ngọt ít', 'Ngọt vừa', 'Ngọt nhiều'])->delete();

        // Revert sugar applies_to_tea_juice
        DB::table('modifiers')
            ->where('type', 'sugar')
            ->whereIn('name', ['Ít đường (30%)', 'Nửa đường (50%)', 'Đường bình thường', 'Thêm đường'])
            ->update(['applies_to_tea_juice' => true]);
    }
};
