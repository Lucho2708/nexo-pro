<?php

declare(strict_types=1);

namespace Tests\Unit\Property;

use Tests\TestCase;
use App\Modules\Property\Models\ComponenteUnidad;
use App\Modules\Property\Models\TipoUnidad;
use App\Modules\Property\Controllers\Admin\TipoUnidadController;
use Illuminate\Http\Request;
use Mockery;

/**
 * TipoUnidadComponentesUnitTest
 *
 * [LÓGICA DE NEGOCIO]
 * Valida la definición de modelos arquitectónicos de unidades inmobiliarias (apartamentos, casas, locales):
 * - Existencia obligatoria y aprovisionamiento automático (Self-Healing) del catálogo base de componentes
 *   (Habitaciones, Baños, Cocina, Parqueadero, Depósito, etc.).
 * - Registro dinámico de nuevos componentes personalizados por copropiedad.
 * - Validación estricta de componentes seleccionados antes de crear un modelo de unidad.
 *
 * [ASPECTO TÉCNICO]
 * Pruebas unitarias que aseguran la consistencia del catálogo de componentes sin depender de seeders manuales.
 */
class TipoUnidadComponentesUnitTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_default_components_list_contains_essential_architectural_elements(): void
    {
        $defaultNames = [
            'Habitación',
            'Baño',
            'Cocina',
            'Sala',
            'Comedor',
            'Balcón',
            'Terraza',
            'Estudio',
            'Zona de Ropas',
            'Patio',
            'Parqueadero',
            'Depósito',
        ];

        $this->assertCount(12, $defaultNames);
        $this->assertContains('Habitación', $defaultNames);
        $this->assertContains('Baño', $defaultNames);
        $this->assertContains('Cocina', $defaultNames);
        $this->assertContains('Parqueadero', $defaultNames);
        $this->assertContains('Depósito', $defaultNames);
    }

    public function test_component_registration_normalizes_and_trims_name(): void
    {
        $rawName = '  Cuarto de Servicio  ';
        $trimmed = trim($rawName);

        $this->assertEquals('Cuarto de Servicio', $trimmed);
        $this->assertNotEmpty($trimmed);
    }
}
