<?php

namespace App\Modules\IAM\Controllers;

use App\Modules\IAM\Contracts\RoleRedirectionServiceInterface;
use App\Modules\IAM\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    public function __construct(
        private ?RoleRedirectionServiceInterface $redirectionService = null
    ) {
        $this->redirectionService = $redirectionService ?? app(RoleRedirectionServiceInterface::class);
    }
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        $request->session()->regenerate();

        $user = Auth::user();
        
        $url = $this->redirectionService->getCanonicalUrl($user, false);
        $this->redirectionService->sanitizeIntendedUrl($user, session()->get('url.intended'));

        return redirect()->intended($url);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
