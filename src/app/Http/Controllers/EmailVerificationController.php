<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    // メール再送信用ページ表示
    public function show()
    {
        return view('auth.verify-email');
    }

    // メール再送信処理
    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended('/');
        }

        $request->user()->sendEmailVerificationNotification();

        return view('auth.resend-done');
    }

    // メール再送信完了ページ
    public function showResendDone()
    {
        return view('auth.resend-done');
    }

}
