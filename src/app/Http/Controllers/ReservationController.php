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

    public function showPayment()
    {
        return view('payment'); // payment.blade.phpを表示
    }


    public function processPayment(Request $request)
    {
        // Stripe APIの初期化
        Stripe::setApiKey(env('STRIPE_SECRET'));

        // チェックアウトセッションを作成
        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => 'Reservation Payment',
                    ],
                    'unit_amount' => 1000, // 支払い金額
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('payment.success'),
            'cancel_url' => route('payment.cancel'),
        ]);

        return redirect($session->url);
    }


    

    // stripeデポジット支払い
    // public function payDeposit($reservationId)
    // {
        // $reservation = Reservation::findOrFail($reservationId);

        // Stripe APIキーを設定
        // Stripe::setApiKey(config('services.stripe.secret'));

        
            // Stripe Checkout セッションを作成
        // $session = StripeSession::create([
            // 'payment_method_types' => ['card'],
            // 'line_items' => [[
                // 'price_data' => [
                    // 'currency' => 'jpy',
                    // 'product_data' => [
                        // 'name' => 'Reservation Deposit',
                    // ],
                    // 'unit_amount' => 1000,
                // ],
                // 'quantity' => 1,
            // ]],
            // 'mode' => 'payment',
            // 'success_url' => config('app.url') . '/reservation/success?session_id={CHECKOUT_SESSION_ID}&reservation_id=' . $reservation->id,
            // 'cancel_url' => config('app.url') . '/cancel',
        // ]);

        // StripeセッションURLにリダイレクト
        // return redirect($session->url);
    // }

    // public function successDeposit(Request $request)
    // {
        // $session_id = $request->query('session_id');
        // $reservation_id = $request->query('reservation_id');

        // if (!$session_id || !$reservation_id) {
            // return redirect()->route('reservation.cancel');
        // }


        // Stripe::setApiKey(config('services.stripe.secret'));

        // Stripe セッション情報を取得
        // $session = StripeSession::retrieve($session_id);

        // if ($session->payment_status === 'paid') {
            // $reservation = Reservation::findOrFail($reservation_id);
            // $reservation->deposit_amount = 1000;
            // $reservation->save();
            // 支払い完了画面にリダイレクト
            // return redirect()->route('reservation.paymentDone');
        // }

    //    return redirect()->route('reservation.cancel');
    //}


    

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

    // 支払い完了ページ表示
    public function paymentSuccess()
    {
        return view('payment-Success');
    }

    // 支払いキャンセル完了ページ表示
    public function paymentCancel()
    {
        return view('payment-cancel');
    }

    
}
