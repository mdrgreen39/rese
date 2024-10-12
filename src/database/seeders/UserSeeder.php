<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        User::create([
            'name' => 'Store Manager One',
            'email' => 'storemanager1@example.com',
            'password' => bcrypt('storemanager123'),
            'role' => 'store_manager', // もしくは対応するrole_id
        ]);

        // 1人目の一般ユーザーを指定して作成
        User::create([
            'name' => 'User One',
            'email' => 'user1@example.com',
            'password' => bcrypt('userpassword123'),
            'role' => 'user', // もしくは対応するrole_id
        ]);

        // 他の店舗代表者を10人ランダムに生成
        User::factory(10)->create([
            'role' => 'store_manager', // もしくは対応するrole_id
        ]);

        // 他の一般ユーザーを10人ランダムに生成
        User::factory(10)->create([
            'role' => 'user', // もしくは対応するrole_id
        ]);
    }
}
