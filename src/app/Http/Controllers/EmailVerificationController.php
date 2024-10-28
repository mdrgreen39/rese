<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;

class EmailVerificationController extends Controller
{
    public function __invoke(Request $request, $id, $hash)
    {
        $user = User::find($id);

        if (!$user || !hash_equals(sha1($user->getEmailForVerification()), $hash)) {
            return redirect()->route('verification.notice');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->intended('/');
        }

        $user->markEmailAsVerified();

        return redirect()->intended('/');
    }

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
