<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        \Illuminate\Support\Facades\DB::listen(function ($query) {
            // we don't want to dump all queries, but we can catch exceptions if we listen to something else
        });
        
        \Illuminate\Support\Facades\Event::listen(\Illuminate\Database\Events\QueryExecuted::class, function ($event) {
            // success
        });

        // Simular respuestas exitosas para servicios externos por defecto (Pusher/LiveKit)
        \Illuminate\Support\Facades\Http::fake([
            '*' => \Illuminate\Support\Facades\Http::response([], 200),
        ]);

        $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\PreventRequestForgery::class);
        
        // Desactivar SSR de Inertia en tests
        config(['inertia.ssr.enabled' => false]);
    }
}
