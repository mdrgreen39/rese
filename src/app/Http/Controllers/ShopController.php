<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Prefecture;
use App\Models\Genre;


class ShopController extends Controller
{
    /* ログインページ表示 */
    // public function showLoginForm()
    // {
        // return view('auth.login');
    // }

    /* 店舗一覧TOPページ表示*/
    public function index(Request $request)
    {
        $shops = Shop::with('prefecture', 'genre')->get();
        $prefectures = Prefecture::all();
        $genres = Genre::all();

        return view('index', compact('shops', 'prefectures', 'genres'));
    }


    /* 検索機能 */
    public function search(Request $request)
    {
        $query = Shop::query();

        if ($request->has('prefecture_id') && $request->input('prefecture_id') != 'All area') {
            $query->where('prefecture_id', $request->input('prefecture_id'));
        }

        if ($request->has('genre_id') && $request->input('genre_id') != 'All genre') {
            $query->where('genre_id', $request->input('genre_id'));
        }

        if ($request->has('search') && !empty($request->input('search'))) {
            $query->where('name', 'like', '%' .  $request->input('search') . '%');
        }

        $shops = $query->get();

        return view('results', compact('shops'));
    }

    /* 店舗詳細ページ表示*/
    public function show($shop_id)
    {
        $shop = Shop::findOrFail($shop_id);

        $prefecture = $shop->prefecture;
        $genre = $shop->genre;

        // 予約フォーム用のデータを生成
        $startTime = '11:00';
        $endTime = '21:00';
        $interval = 30; // 分

        // 時間の選択肢を生成
        $times = [];
        $current = new \DateTime($startTime);
        $end = new \DateTime($endTime);

        while ($current <= $end) {
            $times[] = $current->format('H:i');
            $current->add(new \DateInterval('PT' . $interval . 'M')); // 30分加算
        }

        // 人数の選択肢を生成
        $numberOfPeople = range(1, 10); // 1から10人までの選択肢

        return view('shop_detail', compact('shop', 'prefecture', 'genre', 'times', 'numberOfPeople'));

    }
    

}

