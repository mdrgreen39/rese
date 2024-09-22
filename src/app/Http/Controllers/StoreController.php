<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreRequest;
use App\Models\Shop;
use App\Models\Prefecture;
use App\Models\Genre;
use App\Models\Reservation;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Jobs\PaymentQRCode;

class StoreController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:store_manager');
    }

    // 店舗代表者様ダッシュボード表示
    public function index()
    {
        return view('store.store-dashboard');
    }


    /* 登録店舗一覧TOPページ表示*/
    public function mypage(Request $request)
    {
        $userId = auth()->id();
        $shops = Shop::where('user_id', $userId)->get();
        $prefectures = Prefecture::all();
        $genres = Genre::all();

        return view('store.store-mypage', compact('shops', 'prefectures', 'genres'));
    }

    // 登録店舗詳細ページ表示
    public function show($id)
    {
        // 現在ログイン中の代表者の店舗のみを表示できるようにするための認証チェック
        $shop = Shop::where('id', $id)
            ->where('user_id', auth()->id()) // ログイン中のユーザーIDと一致する店舗のみ
            ->firstOrFail(); // 店舗が見つからない場合は404エラー

        // 都道府県とジャンルのリストを取得
        $prefectures = Prefecture::all();
        $genres = Genre::all();

        return view('store.managed-store-detail', compact('shop', 'prefectures', 'genres'));
    }


    /* QRコードでの予約確認 */
    public function verify($id)
    {
        $reservation = Reservation::find($id);

        if (!$reservation) {
            return redirect()->route('reservation.checkin')
                ->with('error', '予約が見つかりません');
        }

        if ($reservation->visited_at) {
            return redirect()->route('reservation.checkin')
                ->with('info', '来店確認は既に完了しています');
        }

        // 来店確認処理
        $reservation->visited_at = now();

        // レビュー書き込み可能のフラグを立てる
        $reservation->can_review = true;
        $reservation
            ->save();

        return redirect()->route('reservation.checkin')
            ->with('success', '来店確認が完了しました');
    }

    /* 来店確認ページ表示 */
    public function showCheckinPage()
    {
        return view('store.checkin');
    }

    

    // 店舗情報登録ページ表示
    public function register()
    {
        $prefectures = Prefecture::all();
        $genres = Genre::all();

        return view('store.store-register', compact('prefectures', 'genres'));
    }


    /* 店舗情報登録処理 */
    public function store(StoreRequest $request)
    {
        

        $shop = new Shop();
        $shop->name = $request->name;
        $shop->description = $request->description;

        $prefecture = Prefecture::find($request->prefecture_id);
        $genre = Genre::find($request->genre_id);

        if (!$prefecture || !$genre) {
            return redirect()->back()->withErrors('選択された都道府県またはジャンルが無効です。');
        }

        $shop->prefecture_id = $request->prefecture_id;
        $shop->genre_id = $request->genre_id;

        $shop->user_id = auth()->id();


        if ($request->hasFile('image')) {
            $shop->image = $request->file('image')->store('shops', 'public');
        }
        // 画像URLが指定されている場合
        elseif ($request->filled('image_url')) {
            $url = $request->input('image_url');
            $fileName = basename($url);
            $fileContents = file_get_contents($url);
            $shop->image = 'shops/' . $fileName;
            Storage::disk('public')->put($shop->image, $fileContents);
        }

        $shop->save();

        return redirect()->back()->with('message', '店舗情報が追加されました');
    }

    // 店舗編集ページ表示
    public function edit($id)
    {
        $shop = Shop::findOrFail($id);
        $prefectures = Prefecture::all();
        $genres = Genre::all();

        return view('store.edit', compact('shop', 'prefectures', 'genres'));
    }

    // 店舗情報更新処理
    public function update(StoreRequest $request, $id)
    {
        $shop = Shop::findOrFail($id);
        $shop->name = $request->name;
        $shop->description = $request->description;

        // Prefecture と Genre の取得
        $prefecture = Prefecture::find($request->prefecture_id);
        $genre = Genre::find($request->genre_id);

        // エラーチェック
        if (!$prefecture || !$genre) {
            return redirect()->back()->withErrors('選択された都道府県またはジャンルが無効です。');
        }

        $shop->prefecture_id = $request->prefecture_id;
        $shop->genre_id = $request->genre_id;

        // 画像ファイルがアップロードされている場合
        if ($request->hasFile('image')) {
            // 以前の画像ファイルが存在する場合は削除
            if ($shop->image && Storage::disk('public')->exists($shop->image)) {
                Storage::disk('public')->delete($shop->image);
            }

            // 新しい画像をストレージに保存し、パスを取得
            $shop->image = $request->file('image')->store('shops', 'public');
        }

        // 画像URLが指定されている場合
        elseif ($request->filled('image_url')) {
            // 以前の画像ファイルが存在する場合は削除
            if ($shop->image && Storage::disk('public')->exists($shop->image)) {
                Storage::disk('public')->delete($shop->image);
            }

            $url = $request->input('image_url');
            $fileName = basename($url);
            $fileContents = file_get_contents($url);
            $shop->image = 'shops/' . $fileName;
            Storage::disk('public')->put($shop->image, $fileContents);
        }

        $shop->save();

        return redirect()->route('store.dettail', ['id' => $id])->with('message', '店舗情報が更新されました');
    }

    // 店舗の予約リスト表示
    public function showReservations($shopId, Request $request)
    {
        $shop = auth()->user()->shops()->findOrFail($shopId);

        $reservations = $shop->reservations()
            ->orderBy('date', 'asc') // 日付で昇順
            ->orderBy('time', 'asc') // 時間で昇順
            ->paginate(10);

        return view('store.reservations', compact('shop', 'reservations'));
    
    }



}
