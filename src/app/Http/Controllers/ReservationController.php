<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReservationRequest;
use App\Models\Shop;
use App\Models\Reservation;
use App\Jobs\GenerateQrCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


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

        // QRコード生成のジョブをディスパッチ
        GenerateQrCode::dispatch($reservation)->onQueue('default');

        return redirect()->route('reservation.done');
    }


    /* QRコードでの予約確認 */
    public function verify($id)
    {
        $reservation = Reservation::find($id);

        if (!$reservation) {
            return redirect()->route('reservation.checkin')
            ->with('error', '予約が見つかりません。');
        }

        if ($reservation->visited_at) {
            return redirect()->route('reservation.checkin')
            ->with('info', '来店確認は既に完了しています。');
        }

        // 来店確認処理
        $reservation->visited_at = now();

        // レビュー書き込み可能のフラグを立てる
        $reservation->can_review = true;
        $reservation
        ->save();

        return redirect()->route('reservation.checkin')
        ->with('success', '来店確認が完了しました。');
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
