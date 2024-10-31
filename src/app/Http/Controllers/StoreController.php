<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreRequest;
use App\Http\Requests\StoreEditRequest;
use App\Models\Shop;
use App\Models\Prefecture;
use App\Models\Genre;
use App\Models\Reservation;

class StoreController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:store_manager');
    }

    // 店舗代表者管理画面表示
    public function index()
    {
        return view('store.store-dashboard');
    }

    // 登録店舗一覧TOPページ表示
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
        $shop = Shop::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $prefectures = Prefecture::all();
        $genres = Genre::all();

        return view('store.managed-store-detail', compact('shop', 'prefectures', 'genres'));
    }

    /* QRコードでの予約確認 */
    public function verify($id)
    {
        // ログイン状態を確認
        if (!auth()->check()) {
            return redirect()->route('login')->withErrors(['message' => 'ログインしてください']);
        }

        $reservation = Reservation::find($id);

        if (!$reservation) {
            return redirect()->route('reservation.checkin')
                ->with('error', '予約が見つかりません');
        }

        if ($reservation->visited_at) {
            return redirect()->route('reservation.checkin')
                ->with('info', '来店確認はすでに完了しています');
        }

        $reservation->visited_at = now();

        $reservation->can_review = true;
        $reservation
            ->save();

        return redirect()->route('reservation.checkin')
            ->with('success', '来店確認が完了しました');
    }

    // 来店確認ページ表示
    public function showCheckinPage()
    {
        return view('store.checkin');
    }

    // 店舗情報登録ページ表示
    public function register()
    {
        $prefectures = Prefecture::all();
        $genres = Genre::all();

        $shop = new Shop();

        return view('store.store-register', compact('prefectures', 'genres', 'shop'));
    }

    //  店舗情報登録処理
    public function store(StoreRequest $request)
    {
        $shop = new Shop();
        $shop->name = $request->name;
        $shop->description = $request->description;
        $shop->prefecture_id = $request->prefecture_id;
        $shop->genre_id = $request->genre_id;
        $shop->user_id = auth()->id();

        if ($request->hasFile('image')) {
            $shop->image = $request->file('image')->store('shops', 'public');
        } elseif ($request->filled('image_url')) {
            $url = $request->input('image_url');
            $fileName = basename($url);
            $fileContents = file_get_contents($url);
            $shop->image = 'shops/' . $fileName;

            if (app()->environment('production')) {
                Storage::disk('s3')->put($shop->image, $fileContents);
            } else {
                Storage::disk('public')->put($shop->image, $fileContents);
            }
        }

        $shop->save();

        return redirect()->route('store.register.done');
    }

    // 店舗登録完了へージ表示
    public function registerDone()
    {
        return view('store.store-register-done');
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
    public function update(StoreEditRequest $request, $id)
    {
        $shop = Shop::findOrFail($id);

        if ($request->filled('name')) {
            $shop->name = $request->input('name');
        }

        if ($request->filled('description')) {
            $shop->description = $request->input('description');
        }
        $shop->prefecture_id = $request->input('prefecture_id', $shop->prefecture_id);
        $shop->genre_id = $request->input('genre_id', $shop->genre_id);

        if (!$shop->image && !$request->hasFile('image') && !$request->filled('image_url')) {
            return redirect()->back()->withErrors('画像ファイルまたは画像URLを指定してください');
        }

        if ($request->hasFile('image')) {
            if ($shop->image && Storage::disk('public')->exists($shop->image)) {
                Storage::disk('public')->delete($shop->image);
            }

            $shop->image = $request->file('image')->store('shops', 'public');
        }

        elseif ($request->filled('image_url')) {
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

        return redirect()->route('store.editDone');
    }

    // 店舗情報更新完了ページ表示
    public function editDone()
    {
        return view('store.edit-done');
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
