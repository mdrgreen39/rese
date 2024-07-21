<?php

namespace App\Actions\Fortify;

use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class RegisterResponse implements RegisterResponseContract
{
    public function toResponse($request)
    {
        return new RedirectResponse(route('registration.thanks'));


    //登録ボタン後メールで認証する場合、ステータス表示する場合
    //    return $request->wantsJson()
    //        ? new JsonResponse('', 201)
    //        : redirect()->route('login')->with('status', 'メールを送信しました！ログインにはメールアドレスの確認が必要です、メールボックスを確認してください');
    }

}
