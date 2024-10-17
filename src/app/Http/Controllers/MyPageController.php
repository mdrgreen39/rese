<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Prefecture;
use App\Models\Genre;

class MyPageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:user');
    }

    // マイページ表示
    public function index(Request $request)
    {
        $user = auth()->user();
        $favoriteShops = $user->favorites()->with('prefecture', 'genre')->get();
        $prefectures = Prefecture::all();
        $genres = Genre::all();

        $now = now();
        $todayDate = $now->toDateString();
        $currentTime = $now->toTimeString();

        $reservations = $user->reservations()
        ->with('shop')
        ->get();

        $filteredReservations = $user->reservations()
        ->with('shop')
        ->where(function ($query) use ($now) {
            $query->where('date', '>', $now->toDateString())
            ->orWhere(function ($query) use ($now) {
                $query->where('date', '=', $now->toDateString())
                ->where('time', '>=', $now->toTimeString());
            });
        })
        ->orderBy('date', 'asc')
        ->orderBy('time', 'asc')
        ->get();

        return view('mypage', compact('favoriteShops', 'prefectures', 'genres', 'filteredReservations'));
    }

    // レビュー投稿完了ページ表示
    public function showReviewSuccess(Shop $shop)
    {
        return view('review-success', compact('shop'));
    }
}
