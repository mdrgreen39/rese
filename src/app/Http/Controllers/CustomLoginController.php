<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class CustomLoginController extends Controller
{
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if (!$user->hasVerifiedEmail()) {
                return redirect()->route('verification.notice');
            }

            // ロールの取得
            $roles = $user->roles()->pluck('name');

            if ($roles->contains('admin')) {

                return redirect()->route('admin.dashboard');

            } elseif ($roles->contains('store_manager')) {

                return redirect()->route('store.dashboard');
            }

            return redirect()->intended('/');
            }

        return redirect()->back()->withErrors([
            'email' => '認証に失敗しました',
        ]);
    }
}
