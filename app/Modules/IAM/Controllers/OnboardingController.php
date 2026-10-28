<?php

namespace App\Modules\IAM\Controllers;

use App\Modules\IAM\Actions\Auth\RegisterCopropiedadAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\IAM\Requests\StoreOnboardingRequest;
use App\DTOs\RegisterCopropiedadDTO;
use App\Services\Subscription\PlanService;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class OnboardingController extends Controller
{
    /**
     * Show the onboarding wizard page.
     */
    public function index(Request $request, PlanService $planService): Response
    {
        $selectedPlanSlug = $request->query('plan', 'condominio_pro');
        $billingCycle = $request->query('billing', 'monthly');

        return Inertia::render('Auth/Onboarding', [
            'available_plans' => array_values($planService->getPlans()),
            'selected_plan_slug' => $selectedPlanSlug,
            'initial_billing_cycle' => $billingCycle,
        ]);
    }

    /**
     * Store a new copropiedad and administrator.
     */
    public function store(StoreOnboardingRequest $request, RegisterCopropiedadAction $registerAction)
    {
        $dto = RegisterCopropiedadDTO::fromRequest($request->validated());

        $user = $registerAction->execute($dto);

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', '¡Registro completado con éxito! Bienvenido a NEXO PRO.');
    }
}
