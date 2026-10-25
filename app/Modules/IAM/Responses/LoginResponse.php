<?php

namespace App\Modules\IAM\Responses;

use App\Modules\IAM\Contracts\RoleRedirectionServiceInterface;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function __construct(
        private ?RoleRedirectionServiceInterface $redirectionService = null
    ) {
        $this->redirectionService = $redirectionService ?? app(RoleRedirectionServiceInterface::class);
    }

    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        $user = Auth::user();

        // Obtener la URL canónica del rol y sanitizar cualquier URL previa en sesión
        $home = $this->redirectionService->getCanonicalUrl($user, false);
        $this->redirectionService->sanitizeIntendedUrl($user, session()->get('url.intended'));

        return $request->wantsJson()
            ? response()->json(['two_factor' => false])
            : redirect()->intended($home);
    }
}
