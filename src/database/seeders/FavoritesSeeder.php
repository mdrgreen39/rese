<?php

namespace Database\Seeders;

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
        $users = User::role('user')->get();
        $shops = Shop::all();

        foreach ($users as $user) {
            $randomShops = $shops->random(rand(1, 5));

            foreach ($randomShops as $shop) {
                $user->favorites()->attach($shop->id);
            }
        }
    }
}
