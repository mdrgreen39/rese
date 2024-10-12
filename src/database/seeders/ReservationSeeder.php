<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\Reservation;
use App\Jobs\GenerateQrCode;
use App\Models\User;
use App\Models\Shop;

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $today = Carbon::today();
        $availableTimes = $this->generateTimeOptions('11:00', '21:00', 30); // 2時間ごとに


        for ($i = 0; $i < 30; $i++) {
            $date = $today->copy()->addDays(rand(0, 30))->format('Y-m-d');
            $shopId = rand(1, 10);
            $userId = rand(1, 10);

            $time = $availableTimes[array_rand($availableTimes)];

            $reservation = Reservation::create([
                'date' => $date,
                'time' => $time,
                'people' => rand(1, 10),
                'shop_id' => $shopId,
                'user_id' => $userId,
                // QRコードはジョブで生成するためここでは指定しない
            ]);

            // QRコード生成のジョブをディスパッチ
            GenerateQrCode::dispatch($reservation);
        }

        // 指定したユーザーID
        $userId = User::where('email', 'user1@example.com')->first()->id;

        // 全ての店舗を取得
        $shops = Shop::all();

        // ランダムに訪問する店舗の数を指定
        $numberOfReservations = rand(2, 5); // 例: 2〜5件の予約をランダムに作成

        // ランダムな店舗を選択
        $randomShops = $shops->random($numberOfReservations);

        // 過去の予約データを作成
        foreach ($randomShops as $shop) {
            $date = now()->subDays(rand(1, 30))->format('Y-m-d'); // 過去のランダムな日付
            $time = '12:00:00'; // 固定の時間

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

        // 時間を生成
        for ($current = $startTime; $current <= $endTime; $current += $interval * 60) {
            $times[] = date('H:i:s', $current); // フォーマットを H:i:s に
        }

        return $times;
    }
}
