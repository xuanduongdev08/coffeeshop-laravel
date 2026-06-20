<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ── Tạo Roles ──────────────────────────────────────────────────────────
        $roles = ['admin', 'staff', 'cashier', 'warehouse', 'customer'];
        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        }

        // ── Tạo Permissions ────────────────────────────────────────────────────
        $permissions = [
            // Sản phẩm
            'view products', 'create products', 'edit products', 'delete products',
            // Đơn hàng
            'view orders', 'edit orders', 'delete orders',
            // Khách hàng
            'view customers', 'edit customers', 'delete customers',
            // Nhân viên
            'view employees', 'create employees', 'edit employees', 'delete employees',
            // Thống kê
            'view statistics', 'export statistics',
            // Cài đặt
            'manage settings',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        // Gán quyền cho roles
        Role::findByName('admin')->syncPermissions(Permission::all());

        Role::findByName('staff')->syncPermissions([
            'view products', 'create products', 'edit products',
            'view orders', 'edit orders',
            'view customers',
        ]);

        Role::findByName('cashier')->syncPermissions([
            'view products',
            'view orders', 'edit orders',
        ]);

        Role::findByName('warehouse')->syncPermissions([
            'view products', 'edit products',
            'view orders',
        ]);

        // ── Tạo tài khoản Admin ────────────────────────────────────────────────
        $admin = User::firstOrCreate(
            ['email' => 'admin@coffeeshop.com'],
            [
                'name'     => 'Admin XDTHECOFFEEHOUSE',
                'password' => Hash::make('admin123456'),
                'phone'    => '0978853110',
                'address'  => '93 Lê Cao Lãng, Quận Tân Phú, TP.HCM',
            ]
        );
        $admin->assignRole('admin');

        // ── Tạo tài khoản Staff mẫu ────────────────────────────────────────────
        $staff = User::firstOrCreate(
            ['email' => 'staff@coffeeshop.com'],
            [
                'name'     => 'Nhân Viên Mẫu',
                'password' => Hash::make('staff123456'),
                'phone'    => '0901234567',
            ]
        );
        $staff->assignRole('staff');

        // ── Tạo tài khoản khách hàng mẫu ──────────────────────────────────────
        $customer = User::firstOrCreate(
            ['email' => 'khachhang@example.com'],
            [
                'name'     => 'Khách Hàng Mẫu',
                'password' => Hash::make('customer123'),
                'phone'    => '0912345678',
                'address'  => 'TP.HCM',
            ]
        );
        $customer->assignRole('customer');

        $this->command->info('✅ Đã tạo roles, permissions và tài khoản mẫu.');
        $this->command->info('   Admin: admin@coffeeshop.com / admin123456');
        $this->command->info('   Staff: staff@coffeeshop.com / staff123456');
        $this->command->info('   Customer: khachhang@example.com / customer123');
    }
}
