<?php

namespace App\Providers;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use App\Listeners\UpdateLastLogin;
use App\Modules\Operations\Models\Pqrs;
use App\Modules\Property\Models\Unidad;
use App\Policies\PqrsPolicy;
use App\Policies\UnidadPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Repositories\Interfaces\FinancialRepositoryInterface::class,
            \App\Repositories\Eloquent\EloquentFinancialRepository::class,
        );

        $this->app->bind(
            \App\Repositories\Interfaces\PqrsRepositoryInterface::class,
            \App\Repositories\Eloquent\EloquentPqrsRepository::class,
        );
        $this->app->bind(
            \App\Repositories\Interfaces\ZonaComunRepositoryInterface::class,
            \App\Repositories\Eloquent\EloquentZonaComunRepository::class,
        );
        $this->app->bind(
            \App\Repositories\Interfaces\ReservationRepositoryInterface::class,
            \App\Repositories\Eloquent\EloquentReservationRepository::class,
        );
        $this->app->bind(
            \App\Repositories\Interfaces\AnnouncementRepositoryInterface::class,
            \App\Repositories\Eloquent\EloquentAnnouncementRepository::class,
        );

        // Decorator Pattern: Analytics -> Caching -> Eloquent
        $this->app->bind(
            \App\Repositories\Interfaces\AnalyticsRepositoryInterface::class,
            function ($app) {
                $eloquentRepository = new \App\Repositories\Eloquent\EloquentAnalyticsRepository();
                return new \App\Repositories\Eloquent\CachingAnalyticsRepository(
                    $eloquentRepository,
                );
            },
        );

        // Operaciones & Captación de Leads (Clean Architecture)
        $this->app->singleton(
            \NexoPro\Operations\Contracts\LeadScoringServiceInterface::class,
            \NexoPro\Operations\Services\LeadScoringService::class
        );

        // Módulo Portería / Garita (Clean Architecture & Repository Pattern)
        $this->app->bind(
            \NexoPro\Porteria\Contracts\PorteriaRepositoryInterface::class,
            \NexoPro\Porteria\Repositories\PorteriaRepository::class
        );
        $this->app->bind(
            \NexoPro\Porteria\Contracts\PorteriaServiceInterface::class,
            \NexoPro\Porteria\Services\PorteriaService::class
        );

        // Módulo Backoffice / CRM Comercial
        $this->app->bind(
            \NexoPro\CRM\Contracts\LeadCrmServiceInterface::class,
            \NexoPro\CRM\Services\LeadCrmService::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force HTTPS in production/staging or when APP_URL is https to prevent Mixed Content
        if (app()->environment('production', 'staging') || str_starts_with((string) config('app.url'), 'https://') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        // Policies
        Gate::policy(Pqrs::class, PqrsPolicy::class);
        Gate::policy(Unidad::class, UnidadPolicy::class);

        // Domain Events Registration
        Event::listen(
            \App\Events\CopropiedadCreated::class,
            \App\Listeners\SeedDefaultConceptsListener::class,
        );

        Event::listen(
            \App\Events\PaymentRegistered::class,
            \App\Listeners\AuditPaymentListener::class,
        );

        Event::listen(
            \App\Events\PaymentRegistered::class,
            \App\Listeners\InvalidateFinancialCache::class,
        );

        Event::listen(
            \App\Events\PaymentRegistered::class,
            \App\Listeners\SendPaymentNotificationListener::class,
        );

        // Track login time for Super Admin analytics
        Event::listen(Login::class, UpdateLastLogin::class);
    }
}
