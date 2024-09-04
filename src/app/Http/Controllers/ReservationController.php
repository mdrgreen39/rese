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
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Checkout\Session;


class ReservationController extends Controller
{
    /* 予約処理 */
    public function store(ReservationRequest $request, Shop $shop)
    {
        // 予約情報の保存
        $reservation = new Reservation();
        $reservation->date = $request->input('date');
        $reservation->time = $request->input('time');
        $reservation->people = (int) $request->input('people');
        $reservation->shop_id = $shop->id;
        $reservation->user_id = Auth::id();
        $reservation->save();

        // StripeのAPIキーを設定
        Stripe::setApiKey(config('services.stripe.secret'));

        // Stripe Checkoutセッションの作成
        $session = StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy', // 通貨を設定
                    'product_data' => [
                        'name' => 'Reservation Deposit',
                    ],
                    'unit_amount' => 1000, // デポジット額（例: 1000円）
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('reservation.success', ['session_id' => '{CHECKOUT_SESSION_ID}', 'reservation_id' => $reservation->id]),
            'cancel_url' => route('reservation.cancel'),
        ]);

        // QRコード生成のジョブをディスパッチ
        GenerateQrCode::dispatch($reservation)->onQueue('default');

        // Stripe Checkoutページにリダイレクト
        return redirect($session->url);
    }

    public function success(Request $request)
    {
        $sessionId = $request->query('session_id');
        $reservationId = $request->query('reservation_id');

        // StripeのAPIキーを設定
        Stripe::setApiKey(config('services.stripe.secret'));

        // セッションIDを使用してCheckoutセッションを取得
        $session = StripeSession::retrieve($sessionId);

        if ($session->payment_status === 'paid') {
            // 支払いが成功した場合、予約を確定
            $reservation = Reservation::find($reservationId);
            $reservation->status = 'confirmed'; // 状態を「確定」に変更
            $reservation->save();
            return view('done');
        }

        // 支払い失敗時の処理
        return redirect()->route('reservation.cancel');
    }

    public function cancel()
    {
        // キャンセルページの処理
        return view('cancel');
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
