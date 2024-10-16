<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\App;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PrefectureTableSeeder::class,
            GenresTableSeeder::class,
            RolesAndPermissionsSeeder::class,
        ]);

        if (App::environment('local')) {
            $this->call([
                UserSeeder::class,
                ShopsTableSeeder::class,
                ReservationSeeder::class,
                FavoritesSeeder::class,
            ]);
        }
    }
}
