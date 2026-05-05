<?php

namespace Tests\Feature\Security;

use App\Modules\IAM\Models\User;
use App\Models\Copropiedad;
use App\Models\Unidad;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class FileIntegrityTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_rejects_a_php_file_masquerading_as_a_csv()
    {
        $copropiedad = Copropiedad::factory()->create();
        $admin = User::factory()->create(['role' => 'admin', 'current_copropiedad_id' => $copropiedad->id]);

        // Create a fake PHP file but name it .csv
        $content = "<?php echo 'Hacked'; ?>";
        $file = UploadedFile::fake()->createWithContent('malicious.csv', $content)->mimeType('application/x-php');

        $response = $this->actingAs($admin)
            ->post(route('cartera.import'), [
                'file' => $file,
            ]);

        $response->assertSessionHasErrors('file');
    }

    public function test_payment_support_must_be_a_valid_document_type()
    {
        $copropiedad = Copropiedad::factory()->create();
        $admin = User::factory()->create(['role' => 'admin', 'current_copropiedad_id' => $copropiedad->id]);
        $unidad = Unidad::factory()->create(['copropiedad_id' => $copropiedad->id]);

        $file = UploadedFile::fake()->create('exploit.sh', 100, 'text/x-shellscript');

        $response = $this->actingAs($admin)
            ->post(route('cartera.payment'), [
                'unidad_id' => $unidad->id,
                'concepto_id' => \App\Models\ConceptoCobro::factory()->create(['copropiedad_id' => $copropiedad->id])->id,
                'monto' => 100,
                'fecha' => now()->toDateString(),
                'soporte' => $file,
            ]);

        $response->assertSessionHasErrors('soporte');
    }

    public function test_bulk_generate_rejects_malformed_json_settings()
    {
        $copropiedad = Copropiedad::factory()->create();
        $admin = User::factory()->create(['role' => 'admin', 'current_copropiedad_id' => $copropiedad->id]);

        $response = $this->actingAs($admin)
            ->post(route('unidades.bulk-generate'), [
                'torre' => 'Torre A',
                'pisos' => 5,
                'aptos_por_piso' => 4,
                'custom_settings' => '{ invalid json }' // Malformed JSON
            ]);

        $response->assertSessionHasErrors('custom_settings');
    }
}
