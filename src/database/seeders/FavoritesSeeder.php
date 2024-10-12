<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Shop;

class FavoritesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // お気に入りに登録するユーザーと店舗を指定する
        $users = User::all(); // すべてのユーザーを取得
        $shops = Shop::all(); // すべての店舗を取得

        // 各ユーザーにランダムにお気に入りを作成する
        foreach ($users as $user) {
            // ランダムにお気に入りを作成する店舗の数
            $randomShops = $shops->random(rand(1, 5)); // 1〜3店舗

            foreach ($randomShops as $shop) {
                $user->favorites()->attach($shop->id); // お気に入りを追加
            }
        }
    }
}
