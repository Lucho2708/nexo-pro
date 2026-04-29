<?php

use Inertia\Testing\AssertableInertia as Assert;

it('displays the landing page with the Welcome component and product features', function () {
    $this->get('/')
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page
            ->component('Welcome')
            ->has('features', 3)
            ->where('features.0.title', 'Recaudo Automatizado')
            ->where('features.1.title', 'Asambleas Virtuales')
            ->where('features.2.title', 'Soporte de Ingeniería')
            ->has('plans', 3)
            ->where('plans.0.name', 'BASIC CORE')
            ->where('plans.1.name', 'ELITE MONITOR')
            ->where('plans.2.name', 'ENTERPRISE')
        );
});

it('validates contact demo form inputs', function () {
    $this->post('/demo/request', [])
        ->assertSessionHasErrors(['fullName', 'email', 'phone', 'buildingName']);

    $this->post('/demo/request', [
        'fullName' => 'Juan Perez',
        'email' => 'juan-invalid-email',
        'phone' => '12345678',
        'buildingName' => 'Edificio',
    ])->assertSessionHasErrors(['email']);
});

it('blocks bots with honeypot in contact demo form', function () {
    $this->post('/demo/request', [
        'fullName' => 'Bot Perez',
        'email' => 'bot@bot.com',
        'phone' => '12345678',
        'buildingName' => 'Bot Central',
        'honeypot' => 'Filled Honeypot' // Bot action
    ])->assertStatus(403);
});

it('successfully processes contact demo form', function () {
    $this->post('/demo/request', [
        'fullName' => 'Juan Perez',
        'email' => 'juan@edificio.com',
        'phone' => '1234567890',
        'buildingName' => 'Residencial Las Acacias',
    ])->assertRedirect()
      ->assertSessionHas('success');
});

