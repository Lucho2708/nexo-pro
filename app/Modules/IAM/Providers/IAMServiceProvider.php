<?php

namespace App\Modules\IAM\Providers;

use App\Modules\IAM\Actions\Fortify\CreateNewUser;
use App\Modules\IAM\Actions\Fortify\ResetUserPassword;
use App\Modules\IAM\Actions\Fortify\UpdateUserPassword;
use App\Modules\IAM\Actions\Fortify\UpdateUserProfileInformation;
use App\Modules\IAM\Responses\LoginResponse;
use App\Modules\IAM\Responses\TwoFactorLoginResponse;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;
use Laravel\Fortify\Fortify;
use Inertia\Inertia;

class IAMServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->registerFortifyResponses();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->registerRoutes();
        $this->registerMigrations();
        $this->configureFortify();
        $this->configureRateLimiters();
        $this->registerPermissions();
    }

    /**
     * Register dynamic gates for all permissions.
     */
    protected function registerPermissions(): void
    {
        try {
            // Solo registrar si estamos en un entorno con DB o durante el request
            if (!app()->runningInConsole()) {
                \Illuminate\Support\Facades\Gate::before(function ($user, $ability) {
                    if ($user->isSuperAdmin()) return true;
                });
            }
        } catch (\Throwable $e) {
            // Silencio para no romper comandos de artisan
        }
    }

    /**
     * Register the module's routes.
     */
    protected function registerRoutes(): void
    {
        Route::middleware('web')
            ->group(function () {
                $this->loadRoutesFrom(__DIR__ . '/../Routes/web.php');
            });

        Route::prefix('api')
            ->middleware('api')
            ->group(function () {
                $this->loadRoutesFrom(__DIR__ . '/../Routes/api.php');
            });
    }

    /**
     * Register the module's migrations.
     */
    protected function registerMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }

    /**
     * Configure Fortify actions and views.
     */
    protected function configureFortify(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::redirectUserForTwoFactorAuthenticationUsing(RedirectIfTwoFactorAuthenticatable::class);

        Fortify::loginView(function () {
            return Inertia::render('Auth/Login');
        });

        Fortify::twoFactorChallengeView(function () {
            return Inertia::render('Auth/TwoFactorChallenge');
        });
    }

    /**
     * Register custom Fortify responses.
     */
    protected function registerFortifyResponses(): void
    {
        $this->app->singleton(
            \Laravel\Fortify\Contracts\LoginResponse::class,
            LoginResponse::class
        );

        $this->app->singleton(
            \Laravel\Fortify\Contracts\TwoFactorLoginResponse::class,
            TwoFactorLoginResponse::class
        );
    }

    /**
     * Configure rate limiters for authentication.
     */
    protected function configureRateLimiters(): void
    {
        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });
    }
}
