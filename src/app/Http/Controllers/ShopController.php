<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ReviewRequest;
use App\Models\Shop;
use App\Models\Prefecture;
use App\Models\Genre;
use App\Models\Reservation;
use App\Models\Review;


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
        $searchTerm = $request->input('searchTerm', '');
        $prefectureId = $request->input('prefectureId', '');
        $genreId = $request->input('genreId', '');

        $shops = Shop::with('prefecture', 'genre')->get();
        $prefectures = Prefecture::all();
        $genres = Genre::all();

        // logger()->info('Shops data:', ['shops' => $shops]);

        return view('index', compact('shops', 'prefectures', 'genres', 'searchTerm', 'prefectureId', 'genreId'));
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

        // 現在のユーザーの予約を取得（例: 最新の予約）
        $reservations = Reservation::where('shop_id', $shop_id)
            ->where('user_id', auth()->id())
            ->get();
    
        // ユーザーが来店したかどうかを判断するロジック
        $canReview = $reservations->contains(function ($reservation) {
            return $reservation->can_review && $reservation->visited_at !== null;
        });

        // ビューにデータを渡す
        return view('shop-detail', compact('shop', 'prefecture', 'genre', 'times', 'numberOfPeople', 'reservations', 'canReview'));
    }

    // public function store(ReviewRequest $request, Shop $shop)
    // {
        // logger()->info('Store method called');

        // $reservation = Auth::user()->reservations()->where('shop_id', $shop->id)->first();

        // if (!$reservation || !$reservation->can_review) {
            // return redirect()->back()->with('error', 'レビューを投稿する権限がありません。');
        // }

        // logger()->info('Reservation found, proceeding to save review');


        // Review::create([
            // 'user_id' => Auth::id(),
            // 'shop_id' => $shop->id,
            // 'rating' => $request->input('rating'),
            // 'comment' => $request->input('comment'),
        // ]);

        // $reservation->can_review = false;
        // $reservation->save();

        // logger()->info('Review saved successfully');

        // return redirect()->route('review.success', ['shop' => $shop->id]);
    // }
// }


    /* レビュー投稿完了ページ表示 */
    // public function showReviewSuccess(Shop $shop)
    // {
        // return view('review-success', compact('shop'));
    // }
}

