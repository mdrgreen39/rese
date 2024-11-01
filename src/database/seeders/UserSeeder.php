<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (app()->environment('local')) {
            // 1人目の店舗代表者を指定して作成
            $storeManager = User::create([
                'name' => 'Store Manager One',
                'email' => 'store1@example.com',
                'password' => bcrypt('store123'),
            ]);
            $storeManager->assignRole('store_manager');
            $storeManager->givePermissionTo('store_management');
            $storeManager->update(['email_verified_at' => now()]);

            // 1人目の一般ユーザーを指定して作成
            $userOne = User::create([
                'name' => 'User One',
                'email' => 'user1@example.com',
                'password' => bcrypt('user1234'),
            ]);
            $userOne->assignRole('user');
            $userOne->givePermissionTo('user');
            $userOne->update(['email_verified_at' => now()]);

            // 他の店舗代表者をランダムに生成
            User::factory(4)->create()->each(function ($user) {
                $user->assignRole('store_manager');
                $user->givePermissionTo('store_management');
            });

            // 他の一般ユーザーをランダムに生成
            User::factory(9)->create()->each(function ($user) {
                $user->assignRole('user');
                $user->givePermissionTo('user');
            });
        } elseif (app()->environment('production')) {
            // 1人目の店舗代表者を指定して作成
            $storeManager = User::create([
                'name' => 'Store Manager One',
                'email' => 'imakoko39+sub2@gmail.com',
                'password' => bcrypt('store123'),
            ]);
            $storeManager->assignRole('store_manager');
            $storeManager->givePermissionTo('store_management');
            $storeManager->update(['email_verified_at' => now()]);
        }
    }
}
