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

    
}
