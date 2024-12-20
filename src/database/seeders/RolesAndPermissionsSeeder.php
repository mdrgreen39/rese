<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::firstOrCreate(['name' => 'system_management']);
        Permission::firstOrCreate(['name' => 'store_management']);
        Permission::firstOrCreate(['name' => 'user']);

        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo('system_management');

        $storeManagerRole = Role::firstOrCreate(['name' => 'store_manager']);
        $storeManagerRole->givePermissionTo('store_management');

        $userRole = Role::firstOrCreate(['name' => 'user']);
        $userRole->givePermissionTo('user');


        // 環境によって管理者の設定を変更
        if (app()->environment('production')) {
            // 本番環境の管理者設定
            $adminUser = User::firstOrCreate(
                ['email' => 'imakoko39+sub@gmail.com'],
                [
                    'name' => 'Admin Test User',
                    'password' => bcrypt('admin123'),
                ]
            );
        } else {
            // ローカル環境の管理者設定
            $adminUser = User::firstOrCreate(
                ['email' => 'admin1@example.com'],  // 一意なメールアドレス
                [
                    'name' => 'Admin Test User',    // 管理者の名前
                    'password' => bcrypt('admin123'),     // 英数字8文字以上のパスワード
                ]
            );
        }

        $adminUser->update(['email_verified_at' => now()]);

        if ($adminUser) {
            $adminUser->assignRole($adminRole->name);
            $adminUser->givePermissionTo('system_management');
        }
    }
}
