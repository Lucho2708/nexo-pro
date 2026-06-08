<?php

namespace App\Modules\IAM\Responses;

use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\TwoFactorLoginResponse as TwoFactorLoginResponseContract;

class TwoFactorLoginResponse implements TwoFactorLoginResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        $user = Auth::user();

        $home = match (true) {
            $user->isSuperAdmin() => route('superadmin.dashboard'),
            $user->isAdmin() => route('dashboard'),
            default => route('owner.dashboard'),
        };

        return $request->wantsJson()
            ? response('', 204)
            : redirect()->intended($home);
    }
}
