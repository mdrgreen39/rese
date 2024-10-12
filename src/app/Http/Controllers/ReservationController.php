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
