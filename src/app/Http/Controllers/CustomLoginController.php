<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;


class CustomLoginController extends Controller
{
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if (!$user->hasVerifiedEmail()) {
                // メール未確認のユーザーには適切なリダイレクトを行う
                return redirect()->route('verification.notice');
            }

            // メール確認済みのユーザーには通常のリダイレクトを行う
            return redirect()->intended('/');
        }

        return redirect()->back()->withErrors([
            'email' => '認証に失敗しました。',
        ]);
    }

}
