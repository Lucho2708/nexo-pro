<?php

use Illuminate\Support\Facades\Route;
use App\Modules\IAM\Controllers\ProfileController;
use App\Modules\IAM\Controllers\TenantSwitchController;
use App\Modules\IAM\Controllers\OnboardingController;
use App\Modules\IAM\Controllers\AuthenticatedSessionController;

// ── RUTAS PÚBLICAS DE AUTENTICACIÓN / ONBOARDING ────────────────
Route::middleware(['guest', 'throttle:login'])->group(function () {
    Route::get('/register', [OnboardingController::class, 'index'])->name('register');
    Route::post('/register', [OnboardingController::class, 'store']);
    
    // Si no se usa Fortify para el login view, se pueden activar estas:
    // Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    // Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});

// ── RUTAS PROTEGIDAS ───────────────────────────────────────────
Route::middleware(['auth', 'verified', 'ensure-legal'])->group(function () {
    
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::middleware('two-factor')->group(function () {
        // ── PERFIL GLOBAL ───────────────────────────────────────
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

        // ── MULTI-TENANT SWITCH ─────────────────────────────────
        Route::post('/switch-tenant/{copropiedad}', TenantSwitchController::class)->name('tenant.switch');
    });
});
