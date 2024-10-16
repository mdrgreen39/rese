<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReservationRequest;
use App\Models\Shop;
use App\Models\Reservation;
use App\Jobs\GenerateQrCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
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

        GenerateQrCode::dispatch($reservation)->onQueue('default');

        return redirect()->route('reservation.done');
    }

    // Stripe支払い確認ページ表紙
    public function showPayment()
    {
        return view('payment');
    }


    // Stripe支払い処理
    public function processPayment(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => 'Reservation Payment',
                    ],
                    'unit_amount' => 1000,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('payment.success'),
            'cancel_url' => route('payment.cancel'),
        ]);

        return redirect($session->url);
    }

    //  予約完了ページ表示
    public function done()
    {
        return view('done');
    }

    //  予約削除完了ページ表示
    public function deleted()
    {
        return view('deleted');
    }

    // 予約更新完了ページ表示
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
