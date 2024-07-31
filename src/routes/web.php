<?php

use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

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

Route::get('/auth/login', [ShopController::class,'showLoginForm'])->name('login');

Route::get('/thanks', function () {
    return view('thanks');
})->name('registration.thanks');

Route::get('/', [ShopController::class, 'index'])->name('shops.index');
Route::get('/search', [ShopController::class, 'search'])->name('search.results');


Route::get('/detail/{shop_id}', [ShopController::class, 'show'])->name('shop.detail');