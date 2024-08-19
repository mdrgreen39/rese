<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReservationRequest;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Reservation;


class ReservationController extends Controller
{
    /* 予約処理 */
    public function store(ReservationRequest $request, Shop $shop)
    {
        $reservation = new Reservation();
        $reservation->date = $request->input('date');
        $reservation->time = $request->input('time');
        $reservation->people = (int) $request->input('people');
        $reservation->shop_id = $shop->id;
        $reservation->user_id = Auth::id();
        $reservation->save();

        return redirect()->route('reservation.done');
    }
    /* 予約完了ページ表示 */
    public function done()
    {
        return view('done');
    }

    /* 予約削除完了ページ表示 */
    public function deleted()
    {
        return view('deleted');
    }

    /* 予約更新完了ページ表示 */
    public function updated()
    {
        return view('updated');
    }
}
