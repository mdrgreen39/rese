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

    /* お店一覧TOPページ表示*/
    public function index(Request $request)
    {
        //$shops = Shop::all();
        //return view('index', compact('shops'));

        return view('index');
    }

    
}

