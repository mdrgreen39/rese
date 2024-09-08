<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        // パーミッションを作成
        Permission::create(['name' => 'system_management']);
        Permission::create(['name' => 'store_management']);
        Permission::create(['name' => 'user']);

        // ロールを作成してパーミッションを割り当て
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo('system_management');

        $storeManagerRole = Role::create(['name' => 'store_manager']);
        $storeManagerRole->givePermissionTo('store_management');

        $userRole = Role::create(['name' => 'user']);
        $userRole->givePermissionTo('user');


        // 管理者を設定
        $adminUser = User::firstOrCreate(
            ['email' => 'test39@example.com'],  // 一意なメールアドレス
            [
                'name' => 'test39',       // 管理者の名前
                'password' => bcrypt('39393939'),  // 英数字8文字以上のパスワード
            ]
        );

        // 管理者ロールとパーミッションを割り当て
        if ($adminUser) {
            $adminUser->assignRole($adminRole); // 管理者ロールを割り当て
            $adminUser->givePermissionTo('system_management'); // システム管理パーミッションを付与
        }
    
    }
}
