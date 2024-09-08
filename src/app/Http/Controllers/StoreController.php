<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAmountRequest;
use Illuminate\Http\Request;
use App\Models\Reservation;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Jobs\PaymentQRCode;

class StoreController extends Controller
{
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

    public function create()
    {
        $prefectures = Prefecture::all();
        $genres = Genre::all();

        return view('admin.shop_register', compact('prefectures', 'genres'));
    }

    /* 店舗情報追加処理 */
    public function store(ShopRequest $request)
    {
        $shop = new Shop();
        $shop->name = $request->name;
        $prefecture = Prefecture::find($request->prefecture_id);
        $genre = Genre::find($request->genre_id);
        $shop->description = $request->description;

        if ($request->hasFile('image')) {
            $shop->image = $request->file('image')->store('shops', 'public');
        }


        $shop->save();

        return redirect()->back()->with('success', '店舗情報が追加されました');
    }

    public function end($id)
    {
        $shop = Shop::findOrFail($id);

        return view('shops.edit', compact('shop'));
    }

    public function update(ShopRequest $request, $id)
    {
        $shop = Shop::findOrFail($id);
        $prefecture = Prefecture::find($request->prefecture_id);
        $genre = Genre::find($request->genre_id);
        $shop->description = $request->description;


        if ($request->hasFile('image')) {
            if ($shop->image) {
                Storage::disk('public')->delete($shop->image);
            }

            $shop->image = $request->file('image')->store('shops', 'public');
        }

        $shop->save();

        return redirect()->route('shops.index')->with('success', '店舗情報が更新されました');
    }
}
