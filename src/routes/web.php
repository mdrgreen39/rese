<?php

use App\Http\Controllers\MyPageController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\CustomLoginController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\App;
use Laravel\Fortify\Fortify;
use Livewire\Livewire;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



Route::get('/email/verify', [EmailVerificationController::class, 'show'])->middleware(['auth', 'throttle:6,1'])->name('verification.notice');

Route::post('/email/verification-notification', [EmailVerificationController::class, 'resend'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.send');

Route::get('/email/resend-done', [EmailVerificationController::class, 'showResendDone'])
    ->middleware(['auth'])
    ->name('verification.resend.done');



Route::middleware(['set-register-message'])->group(function () {
    Fortify::registerView(fn () => view('auth.register'));
});

Route::get('/thanks', [RegisterController::class, 'showThanks'])->name('registration.thanks');

Route::post('/login', [CustomLoginController::class, 'login']);

// Route::get('/auth/login', [ShopController::class,'showLoginForm'])->name('login');

// Route::get('/thanks', function () {
    // return view('thanks');
// })->name('registration.thanks');

Route::get('/', [ShopController::class, 'index'])->name('shops.index');
// Route::get('/search', [ShopController::class, 'search'])->name('search.results');


Route::get('/detail/{shop_id}', [ShopController::class, 'show'])->name('shop.detail');


// ロールつける！！！
Route::middleware('auth', 'verified')->group(function ()
{
    Route::get('/mypage', [MyPageController::class, 'index'])->name('user.mypage');
    Route::get('/shops/{shop}/review-success', [MyPageController::class, 'showReviewSuccess'])->name('review.success');

    Route::get('/done', [ReservationController::class, 'done'])->name('reservation.done');
    // Route::get('/cancel', [ReservationController::class, 'cancel'])->name('reservation.cancel');
    Route::get('/deleted', [ReservationController::class, 'deleted'])->name('reservation.deleted');
    Route::get('/updated', [ReservationController::class, 'updated'])->name('reservation.updated');

    Route::get('/payment', [ReservationController::class,'showPayment'])->name('payment.show');

    Route::get('/payment/process', [ReservationController::class, 'processPayment'])->name('payment.action');

    // 支払い成功・キャンセルのルート
    Route::get('/payment/success', [ReservationController::class, 'paymentSuccess'])->name('payment.success');

    Route::get('/payment/cancel', [ReservationController::class, 'paymentCancel'])->name('payment.cancel');

    // Route::post('/reservation/pay-deposit/{reservation_id}', [ReservationController::class, 'payDeposit'])->name('reservation.payDeposit');

    // Route::get('/reservation/success', [ReservationController::class, 'successDeposit'])->name('reservation.successDeposit');


    // Route::get('/reservation/payment-done', [ReservationController::class, 'paymentDone'])->name('reservation.paymentDone');

    // Route::post('/shops/{shop}/review', [ShopController::class, 'store'])->name('review.store');
});


// 予約時のログイン確認・予約処理
Route::post('/shops/{shop}/reservations', [ReservationController::class, 'store'])->middleware('custom_auth')->name('reservations.store');



// 店舗代表者
Route::middleware(['auth', 'verified', 'role:store_manager'])->group(function () {

    Route::get('/store/dashboard', [StoreController::class, 'index'])->name('store.dashboard');

    Route::get('/store/mypage', [StoreController::class, 'myPage'])->name('store.mypage');

    Route::get('/store/store-detail/{id}', [StoreController::class, 'show'])
    ->name('store.detail');

    // 店舗登録
    Route::get('/store/register', [StoreController::class, 'register'])->name('store.register');
    Route::post('/store/register', [StoreController::class, 'store'])->name('store.store');
    Route::get('/store/register/done', [StoreController::class, 'registerDone'])->name('store.register.done');

    // 店舗編集
    Route::get('/store/edit/{id}', [StoreController::class, 'edit'])->name('store.edit');
    Route::put('/store/edit/{id}', [StoreController::class, 'update'])->name('store.update');
    Route::get('/store/edit-done', [StoreController::class, 'editDone'])->name('store.editDone');


    // 予約リスト
    Route::get('/store/{shop}/reservations', [StoreController::class, 'showReservations'])->name('store.reservation');

    // 来店確認
    Route::get('/store/verify/{id}', [StoreController::class, 'verify'])->name('reservation.verify');
    Route::get('/store/checkin', [StoreController::class, 'showCheckinPage'])->name('reservation.checkin');
});




// 管理者
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/owner-register', [AdminController::class, 'showRegisterForm'])->name('admin.ownerRegister');
    Route::post('/admin/owner-register', [AdminController::class, 'store'])->name('admin.storeOwner');
    Route::get('/admin/owner-register/done', [AdminController::class, 'registerDone'])->name('admin.ownerRegisterDone');
    Route::get('/admin/email-notification', [AdminController::class, 'showNotificationForm'])->name('admin.emailNotification');
    Route::post('/admin/email-notification/send', [AdminController::class, 'sendNotification'])->name('admin.sendNotification');
    Route::get('/admin/email-notification/sent', [AdminController::class, 'emailSent'])->name('admin.emailNotificationSent');
});


// テスト用表示ページ
Route::get('/test', function () {
    return view('welcome');
});


//メールテスト用
Route::get('/test-email', function () {
    if (App::environment('production') && !config('mail.test_mode')) {

        return 'Test email is disabled in production';
    }

    Mail::raw('This is a test email', function ($message) {
        //to('')に送信先のメールアドレスを入力：例('xxx@example.com')
        $message->to('')
            ->subject('Test Email');
    });

    return 'Test email sent!';
});