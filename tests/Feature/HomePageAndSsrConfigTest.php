<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;

describe('Home Page, CSP Vite Dynamic Origin & Ziggy SSR Props', function () {
    it('includes dynamic Vite hot server origin in CSP headers during debug mode', function () {
        Config::set('app.debug', true);

        $hotFile = public_path('hot');
        $originalHot = File::exists($hotFile) ? File::get($hotFile) : null;

        try {
            File::put($hotFile, 'http://127.0.0.1:5174');

            $response = $this->get('/');

            $response->assertStatus(200);
            $csp = $response->headers->get('Content-Security-Policy');

            expect($csp)
                ->toContain('http://127.0.0.1:5174')
                ->toContain('ws://127.0.0.1:5174');
        } finally {
            if ($originalHot !== null) {
                File::put($hotFile, $originalHot);
            }
        }
    });

    it('shares ziggy routes and location in Inertia props for SSR compatibility', function () {
        $response = $this->get('/');

        $response->assertStatus(200);
        $page = $response->viewData('page');

        expect($page['props'])
            ->toHaveKey('ziggy')
            ->and($page['props']['ziggy'])
            ->toHaveKeys(['url', 'routes', 'location']);
    });
});
