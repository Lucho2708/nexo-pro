<?php

namespace App\Modules\Asamblea\Controllers;

use App\Modules\Asamblea\Models\Asamblea;
use App\Modules\IAM\Models\User;
use App\Services\Tenant\StandaloneOnboardingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AsambleaGuestController extends \App\Http\Controllers\Controller
{
    public function __construct(
        protected StandaloneOnboardingService $onboardingService
    ) {}

    /**
     * Show the guest login landing page.
     */
    public function showLogin(Asamblea $asamblea)
    {
        return Inertia::render('Asamblea/GuestLogin', [
            'asamblea' => $asamblea->load('copropiedad'),
        ]);
    }

    /**
     * Attempt to "login" as a guest using document validation.
     */
    public function login(Request $request, Asamblea $asamblea)
    {
        $request->validate([
            'nombre_unidad' => 'required|string',
            'documento_ultimos_4' => 'required|string|size:4',
        ]);

        $record = $this->onboardingService->validateAccess(
            $asamblea, 
            $request->nombre_unidad, 
            $request->documento_ultimos_4
        );

        if (!$record) {
            return back()->withErrors([
                'nombre_unidad' => 'Los datos no coinciden con nuestros registros de la asamblea.',
            ]);
        }

        // Create or Find a Lightweight Guest User
        $guestEmail = "guest_" . substr($asamblea->id, 0, 8) . "_" . substr($record->unidad_id, 0, 8) . "@nexo.pro";
        
        $user = User::firstOrCreate(
            ['email' => $guestEmail],
            [
                'name' => "Propietario " . $request->nombre_unidad,
                'password' => bcrypt(\Illuminate\Support\Str::random(16)),
                'role' => 'invitado',
                'current_copropiedad_id' => $asamblea->copropiedad_id,
            ]
        );

        // Ensure the current context is correct
        $user->update([
            'current_copropiedad_id' => $asamblea->copropiedad_id,
            'legal_consent' => true, // Bypass legal consent for standalone guests
            'two_factor_confirmed_at' => now(), // Bypass 2FA for standalone guests
        ]);

        // Link user to unit in the dynamic quorum table if not already linked
        $shortId = substr($asamblea->id, 0, 8);
        \DB::table("asquorum_{$shortId}")
            ->where('id', $record->id)
            ->update(['user_id' => $user->id]);

        Auth::login($user);

        return redirect()->route('asambleas.show', $asamblea);
    }
}
