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

Route::get('/', [ShopController::class, 'index'])->name('all-shops');
Route::get('/thanks', function () {
    return view('thanks');
})->name('registration.thanks');