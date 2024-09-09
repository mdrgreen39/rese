<?php

use App\Http\Controllers\MyPageController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StoreController;
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


Route::middleware(['set-register-message'])->group(function () {
    Fortify::registerView(fn () => view('auth.register'));
});


// Route::get('/auth/login', [ShopController::class,'showLoginForm'])->name('login');

Route::get('/thanks', function () {
    return view('thanks');
})->name('registration.thanks');

Route::get('/', [ShopController::class, 'index'])->name('shops.index');
// Route::get('/search', [ShopController::class, 'search'])->name('search.results');


Route::get('/detail/{shop_id}', [ShopController::class, 'show'])->name('shop.detail');

Route::middleware('auth')->group(function ()
{
    Route::get('/mypage', [MyPageController::class, 'index'])->name('user.mypage');
    Route::get('/done', [ReservationController::class, 'done'])->name('reservation.success');
    Route::get('/cancel', [ReservationController::class, 'cancel'])->name('reservation.cancel');
    Route::get('/deleted', [ReservationController::class, 'deleted'])->name('reservation.deleted');
    Route::get('/updated', [ReservationController::class, 'updated'])->name('reservation.updated');
    // Route::post('/shops/{shop}/review', [ShopController::class, 'store'])->name('review.store');
    Route::get('/shops/{shop}/review-success', [ShopController::class, 'showReviewSuccess'])->name('review.success');



});

// 予約時のログイン確認
Route::post('/shops/{shop}/reservations', [ReservationController::class, 'store'])->middleware('custom_auth')->name('reservations.store');



// 管理者
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/admin-register', [AdminController::class, 'showRegisterForm'])->name('admin.register');
    Route::post('/admin/register', [AdminController::class, 'store'])->name('admin.adminUser');
});

// 店舗代表者
Route::middleware(['auth', 'role:store_manager'])->group(function () {
    Route::get('/store/mypage', [StoreController::class, 'myPage'])->name('store.mypage');

    Route::get('store/store-detail/{id}', [StoreController::class, 'show'])
    ->name('store.detail');

    // 店舗登録画面
    Route::get('/store/register', [StoreController::class, 'register'])->name('store.register');
    Route::post('/store/register', [StoreController::class, 'store'])->name('store.store');

    // 店舗編集画面
    Route::get('/store/edit/{id}', [StoreController::class, 'edit'])->name('store.edit');
    Route::put('/store/edit/{id}', [StoreController::class, 'update'])->name('store.update');

    // 予約リスト
    Route::get('/store/{shop}/reservations', [StoreController::class, 'showReservations'])->name('store.reservation');

    // 来店確認
    Route::get('/store/verify/{id}', [StoreController::class, 'verify'])->name('reservation.verify');
    Route::get('/store/checkin', [StoreController::class, 'showCheckinPage'])->name('reservation.checkin');
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