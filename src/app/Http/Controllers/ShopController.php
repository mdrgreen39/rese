<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShopController extends Controller
{
    /* ログインページ表示 */
    public function showLoginForm()
    {
        return view('auth.login');
    }
}
