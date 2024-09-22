<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    public function show()
    {
        return view('auth.verify-email');
    }

    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended('/');
        }

        $request->user()->sendEmailVerificationNotification(); // 再送信処理

        return view('auth.resend-done');
    }

    public function showResendDone()
    {
        return view('auth.resend-done');
    }

}
