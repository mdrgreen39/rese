<?php

use App\Http\Controllers\MyPageController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ReservationController;
use Illuminate\Support\Facades\Route;
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
    Route::get('/done', [ReservationController::class, 'done'])->name('reservation.done');
    Route::get('/deleted', [ReservationController::class, 'deleted'])->name('reservation.deleted');
    Route::get('/updated', [ReservationController::class, 'updated'])->name('reservation.updated');
});

Route::post('/shops/{shop}/reservations', [ReservationController::class, 'store'])->middleware('custom_auth')->name('reservations.store');


Route::get('/test', function () {
    return view('welcome');
});