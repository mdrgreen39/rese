<?php

namespace App\Actions\Fortify;

use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;
use Illuminate\Http\RedirectResponse;

class RegisterResponse implements RegisterResponseContract
{
    public function toResponse($request)
    {
        return new RedirectResponse(route('registration.thanks'));
    }
}
