<?php

use App\Modules\Property\Models\Copropiedad;
use App\Modules\Property\Models\TipoUnidad;
use App\Modules\Property\Models\Unidad;
use App\Modules\Property\Services\CoeficienteCalculator;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->calculator = new CoeficienteCalculator();
});

test('garantiza que la suma de coeficientes sea exactamente 100 por ciento ajustando el residuo', function () {
    // 1. Escenario de 3 unidades iguales (10m2 cada una, total 30m2)
    $copropiedad = Copropiedad::factory()->create([
        'area_construida_total' => 30.00,
        'nombre' => 'Edificio Residuo'
    ]);

    $tipo = TipoUnidad::factory()->create([
        'copropiedad_id' => $copropiedad->id,
        'area_m2' => 10.00
    ]);

    Unidad::factory()->count(3)->create([
        'copropiedad_id' => $copropiedad->id,
        'tipo_unidad_id' => $tipo->id
    ]);

    // 2. Act: Calcular
    $this->calculator->calculateForCopropiedad($copropiedad);

    // 3. Assert: La suma debe ser exactamente 100.0000
    $suma = Unidad::where('copropiedad_id', $copropiedad->id)->sum('coeficiente');
    
    expect((float) $suma)->toBe(100.0000);
    
    // Verificar que una unidad tiene el ajuste (33.3334) y las otras el base (33.3333)
    $coeficientes = Unidad::where('copropiedad_id', $copropiedad->id)->pluck('coeficiente')->toArray();
    expect(array_map('floatval', $coeficientes))->toContain(33.3334);
    expect(array_map('floatval', $coeficientes))->toContain(33.3333);
});

test('ajusta el residuo en la unidad más grande cuando hay áreas diferentes', function () {
    $copropiedad = Copropiedad::factory()->create([
        'area_construida_total' => 33.33,
        'nombre' => 'Edificio Desigual'
    ]);

    $tipoGrande = TipoUnidad::factory()->create(['copropiedad_id' => $copropiedad->id, 'area_m2' => 15.00]);
    $tipoPequeno = TipoUnidad::factory()->create(['copropiedad_id' => $copropiedad->id, 'area_m2' => 9.165]);

    $uGrande = Unidad::factory()->create([
        'copropiedad_id' => $copropiedad->id, 
        'tipo_unidad_id' => $tipoGrande->id, 
        'nombre' => 'Penthouse'
    ]);
    
    Unidad::factory()->count(2)->create([
        'copropiedad_id' => $copropiedad->id, 
        'tipo_unidad_id' => $tipoPequeno->id
    ]);

    // Act
    $this->calculator->calculateForCopropiedad($copropiedad);

    // Assert
    $suma = Unidad::where('copropiedad_id', $copropiedad->id)->sum('coeficiente');
    expect((float) $suma)->toBe(100.0000);
    
    $coeficienteGrande = Unidad::where('id', $uGrande->id)->first()->coeficiente;
    $coeficientePequeno = Unidad::where('tipo_unidad_id', $tipoPequeno->id)->first()->coeficiente;
    
    expect((float) $coeficienteGrande)->toBeGreaterThan((float) $coeficientePequeno);
});
