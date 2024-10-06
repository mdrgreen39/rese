<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Prefecture;
use App\Models\Genre;
use App\Models\Reservation;

class MyPageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index(Request $request)
    {
        $user = auth()->user();
        $favoriteShops = $user->favorites()->with('prefecture', 'genre')->get();
        $prefectures = Prefecture::all();
        $genres = Genre::all();
        $reservations = $user->reservations()
        ->with('shop')
        ->orderBy('date', 'asc')
        ->orderBy('time', 'asc')
        ->get();

        return view('mypage', compact('favoriteShops', 'prefectures', 'genres', 'reservations'));
    }
}
