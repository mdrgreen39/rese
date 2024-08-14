<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;

class CustomAuthenticate extends Middleware
{
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            return route('login');
        }
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]  ...$guards
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle($request, Closure $next, ...$guards)
    {
        if (!Auth::guard(current($guards))->check()) {
            $message = '予約するには会員登録が必要です。<br>すでに会員登録済みの方は<a href="' . route('login') . '">こちら</a>からログインしてください。';
            return redirect()->route('register')->with('message', $message);
        }

        return $next($request);
    }
}
