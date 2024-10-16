<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GenresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $genres = [
            "和食",
            "洋食",
            "イタリアン",
            "フレンチ",
            "中華料理",
            "韓国料理",
            "エスニック料理",
            "創作料理",
            "カフェ",
            "ラーメン",
            "カレー",
            "寿司",
            "そば",
            "居酒屋",
            "バー",
            "焼肉",
            "焼鳥",
            "ハンバーガー",
            "スイーツ",
        ];

        foreach ($genres as $genre) {
            DB::table('genres')->insert([
                'name' => $genre,
            ]);
        }
    }
}
