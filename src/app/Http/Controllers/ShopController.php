<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Prefecture;
use App\Models\Genre;
use App\Models\Reservation;


class ShopController extends Controller
{
    // 店舗一覧TOPページ表示
    public function index(Request $request)
    {
        $searchTerm = $request->input('searchTerm', '');
        $prefectureId = $request->input('prefectureId', '');
        $genreId = $request->input('genreId', '');

        $shops = Shop::with('prefecture', 'genre')->get();
        $prefectures = Prefecture::all();
        $genres = Genre::all();

        return view('index', compact('shops', 'prefectures', 'genres', 'searchTerm', 'prefectureId', 'genreId'));
    }

    // 店舗詳細ページ表示
    public function show($shop_id)
    {
        $shop = Shop::findOrFail($shop_id);

        $prefecture = $shop->prefecture;
        $genre = $shop->genre;

        $startTime = '11:00';
        $endTime = '21:00';
        $interval = 30;

        $times = [];
        $current = new \DateTime($startTime);
        $end = new \DateTime($endTime);

        while ($current <= $end) {
            $times[] = $current->format('H:i');
            $current->add(new \DateInterval('PT' . $interval . 'M'));
        }

        $numberOfPeople = range(1, 10);

        $reservations = Reservation::where('shop_id', $shop_id)
            ->where('user_id', auth()->id())
            ->get();

        $canReview = $reservations->contains(function ($reservation) {
            return $reservation->can_review && $reservation->visited_at !== null;
        });

        return view('shop-detail', compact('shop', 'prefecture', 'genre', 'times', 'numberOfPeople', 'reservations', 'canReview'));
    }
}

