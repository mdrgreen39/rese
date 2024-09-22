<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Http\Controllers\RegisterController;
use Laravel\Fortify\Http\Requests\LoginRequest as FortifyLoginRequest;
use App\Http\Requests\LoginRequest;
use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;
use App\Actions\Fortify\RegisterResponse;
use App\Http\Responses\LoginResponse;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Laravel\Fortify\Fortify;

use Laravel\Fortify\Http\Controllers\RegisteredUserController;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);

        Fortify::authenticateUsing(
            function ($request) {
                $user = User::where('email', $request->email)->first();

                if ($user && Hash::check($request->password, $user->password)) {
                    if (!$user->hasVerifiedEmail()) {
                        // メール確認がされていない場合のカスタム処理
                        return null; // 認証を失敗させる
                    }

                    return $user;
                }
            });


        Fortify::registerView(function() {
            return view('auth.register');
        });

        Fortify::loginView(function() {
            return view('auth.login');
        });


        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->email;

            return Limit::perMinute(10)->by($email . $request->ip());
        });

        


    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(
            RegisteredUserController::class,
            RegisterController::class
        );

        $this->app->singleton(
            FortifyLoginRequest::class,
            LoginRequest::class
        );

        $this->app->singleton(
            RegisterResponseContract::class,
            RegisterResponse::class
        );

    }

}
