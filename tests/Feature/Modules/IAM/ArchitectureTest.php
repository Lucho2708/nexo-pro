<?php

namespace Tests\Feature\Modules\IAM;

test('los modelos de identidad residen en el modulo IAM', function () {
    expect(class_exists('App\Modules\IAM\Models\User'))->toBeTrue('El modelo User no se ha movido al módulo IAM.');
    expect(class_exists('App\Modules\IAM\Models\LegalConsent'))->toBeTrue('El modelo LegalConsent no se ha movido al módulo IAM.');
});

test('la interfaz IAMServiceInterface existe (Inversion de Dependencias)', function () {
    expect(interface_exists('App\Modules\IAM\Interfaces\IAMServiceInterface'))->toBeTrue('La interfaz de servicio IAM no existe.');
});

test('la clase IAMService implementa la interfaz requerida', function () {
    expect(class_exists('App\Modules\IAM\Services\IAMService'))->toBeTrue('El servicio IAMService no existe.');
    
    $reflection = new \ReflectionClass('App\Modules\IAM\Services\IAMService');
    expect($reflection->implementsInterface('App\Modules\IAM\Interfaces\IAMServiceInterface'))->toBeTrue('IAMService no implementa la interfaz requerida.');
});
