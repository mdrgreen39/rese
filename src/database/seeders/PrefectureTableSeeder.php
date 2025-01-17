<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PrefectureTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $prefectures = [
            "東京都",
            "大阪府",
            "福岡県",
        ];

        foreach ($prefectures as $prefecture) {
            DB::table('prefectures')->insert([
                'name' => $prefecture,
            ]);
        }
    }
}
