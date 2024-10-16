<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use App\Models\Reservation;
use App\Jobs\GenerateQrCode;
use App\Models\User;
use App\Models\Shop;
use Spatie\Permission\Models\Role;

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $today = Carbon::today();
        $availableTimes = $this->generateTimeOptions('11:00', '21:00', 30);

        // 一般ユーザーのIDを取得
        $userIds = User::whereHas('roles', function ($query) {
            $query->where('name', 'user');
        })->pluck('id');

        for ($i = 0; $i < 30; $i++) {
            $date = $today->copy()->addDays(rand(0, 30))->format('Y-m-d');
            $shopId = rand(1, 10);
            $userId = $userIds->random(); // 一般ユーザーからランダムに選択

            $time = $availableTimes[array_rand($availableTimes)];

            $reservation = Reservation::create([
                'date' => $date,
                'time' => $time,
                'people' => rand(1, 10),
                'shop_id' => $shopId,
                'user_id' => $userId,
            ]);

            GenerateQrCode::dispatch($reservation);
        }

        $userId = User::where('email', 'user1@example.com')->first()->id;
        $shops = Shop::all();

        $numberOfReservations = rand(2, 5);

        $randomShops = $shops->random($numberOfReservations);

        foreach ($randomShops as $shop) {
            $date = now()->subDays(rand(1, 30))->format('Y-m-d');
            $time = '12:00:00';

            Reservation::create([
                'date' => $date,
                'time' => $time,
                'people' => rand(1, 10),
                'shop_id' => $shopId,
                'user_id' => $userId,
                'visited_at' => $date,
                'can_review' => true,
            ]);
        }
    }

    public function generateTimeOptions(string $start, string $end, int $interval): array
    {
        $startTime = strtotime($start);
        $endTime = strtotime($end);
        $times = [];

        for ($current = $startTime; $current <= $endTime; $current += $interval * 60) {
            $times[] = date('H:i:s', $current);
        }

        return $times;
    }
}
