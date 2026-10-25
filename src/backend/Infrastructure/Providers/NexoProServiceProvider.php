<?php

declare(strict_types=1);

namespace NexoPro\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use NexoPro\Assembly\Contracts\AssemblyTableManagerInterface;
use NexoPro\Assembly\Contracts\VotePublisherInterface;
use NexoPro\Assembly\Contracts\VotingTallyStrategyInterface;
use NexoPro\Assembly\Strategies\CoefficientVotingStrategy;
use NexoPro\Finance\Contracts\PaymentGatewayInterface;
use NexoPro\Finance\Gateways\WompiPaymentGateway;
use NexoPro\Tenancy\Contracts\TenantContextInterface;
use NexoPro\Infrastructure\Adapters\LaravelAssemblyTableManagerAdapter;
use NexoPro\Infrastructure\Adapters\LaravelVotePublisherAdapter;
use NexoPro\Infrastructure\Adapters\LaravelTenantContextAdapter;

/**
 * NexoProServiceProvider
 *
 * [LÓGICA DE NEGOCIO]
 * Proveedor de servicios que registra e inyecta las dependencias del motor optimizado NexoPro
 * en el contenedor de servicios de la aplicación de propiedad horizontal.
 *
 * [ASPECTO TÉCNICO]
 * Aplica Inversión de Dependencias (DIP) y Segregación de Interfaces (ISP).
 * Vincula contratos abstractos a sus implementaciones concretas y adaptadores de infraestructura.
 */
class NexoProServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // 1. Tenancy Bindings
        $this->app->singleton(TenantContextInterface::class, LaravelTenantContextAdapter::class);

        // 2. Assembly Bindings
        $this->app->singleton(AssemblyTableManagerInterface::class, LaravelAssemblyTableManagerAdapter::class);
        $this->app->singleton(VotePublisherInterface::class, LaravelVotePublisherAdapter::class);
        $this->app->bind(VotingTallyStrategyInterface::class, CoefficientVotingStrategy::class);

        // 3. Finance Bindings
        $this->app->bind(PaymentGatewayInterface::class, WompiPaymentGateway::class);
    }

    public function boot(): void
    {
        // Boot logic if needed
    }
}
