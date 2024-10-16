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
        // 1人目の店舗代表者を指定して作成
        $storeManager = User::create([
            'name' => 'Store Manager One',
            'email' => 'storemanager1@example.com',
            'password' => bcrypt('storemanager123'),
        ]);

        $storeManager->assignRole('store_manager');
        $storeManager->update(['email_verified_at' => now()]);

        // 1人目の一般ユーザーを指定して作成
        $userOne = User::create([
            'name' => 'User One',
            'email' => 'user1@example.com',
            'password' => bcrypt('userpassword123'),
        ]);

        $userOne->assignRole('user');
        $userOne->update(['email_verified_at' => now()]);

        // 他の店舗代表者をランダムに生成
        User::factory(13)->create()->each(function ($user) {
            $user->assignRole('store_manager');
        });

        // 他の一般ユーザーをランダムに生成
        User::factory(3)->create()->each(function ($user) {
            $user->assignRole('user');
        });
    }
}
