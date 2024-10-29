<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;

class EmailVerificationController extends Controller
{
    // メール確認処理
    public function __invoke(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);

        if (hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            if (!$user->hasVerifiedEmail()) {
                $user->markEmailAsVerified();
            }

            Auth::login($user);

            return redirect('/')->with('verified', true);
        }
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
