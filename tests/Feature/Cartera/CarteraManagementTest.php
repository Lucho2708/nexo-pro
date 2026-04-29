<?php

namespace Tests\Feature\Cartera;

use App\Actions\Cartera\ImportUnitsAction;
use App\Actions\Cartera\ProcessPaymentAction;
use App\Models\ConceptoCobro;
use App\Models\Copropiedad;
use App\Models\Unidad;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class CarteraManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->copropiedad = Copropiedad::create([
            'nombre' => 'Test Property', 
            'direccion' => 'Calle 123',
            'nit' => '900123456-1',
            'ciudad' => 'Bogotá'
        ]);
        
        $this->user = User::factory()->create([
            'current_copropiedad_id' => $this->copropiedad->id,
            'role' => 'admin'
        ]);
        $this->actingAs($this->user);
    }

    public function test_it_can_import_units_from_csv()
    {
        $csvContent = "Torre,Nombre,Coeficiente,Propietario,Identificacion,Email,Saldo\n";
        $csvContent .= "Torre A,101,1.5,Juan Perez,123456,juan@test.com,500000";
        
        $filePath = tempnam(sys_get_temp_dir(), 'test_import');
        file_put_contents($filePath, $csvContent);

        $action = new ImportUnitsAction();
        $results = $action->execute($filePath, $this->copropiedad->id);

        $this->assertEquals(1, $results['success']);
        $this->assertDatabaseHas('unidades', [
            'nombre' => '101',
            'torre' => 'Torre A',
            'saldo_actual' => 500000
        ]);

        unlink($filePath);
    }

    public function test_it_can_process_a_manual_payment()
    {
        $unidad = Unidad::create([
            'copropiedad_id' => $this->copropiedad->id,
            'nombre' => '402',
            'torre' => 'Torre B',
            'saldo_actual' => 1000000
        ]);

        $concepto = ConceptoCobro::create([
            'copropiedad_id' => $this->copropiedad->id,
            'nombre' => 'Administracion',
            'codigo' => 'ADM'
        ]);

        $action = new ProcessPaymentAction();
        $action->execute(
            $unidad->id,
            $concepto->id,
            300000,
            date('Y-m-d'),
            'REF-123'
        );

        $this->assertEquals(700000, $unidad->fresh()->saldo_actual);
        $this->assertDatabaseHas('transacciones', [
            'unidad_id' => $unidad->id,
            'monto' => 300000,
            'tipo' => 'abono'
        ]);
    }
}
