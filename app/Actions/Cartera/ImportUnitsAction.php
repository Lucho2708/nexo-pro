<?php

namespace App\Actions\Cartera;

use App\Models\Unidad;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ImportUnitsAction
{
    /**
     * Import units from a CSV file path.
     *
     * @param string $filePath
     * @param string $copropiedadId
     * @return array{success: int, errors: array}
     */
    public function execute(string $filePath, string $copropiedadId): array
    {
        $results = [
            'success' => 0,
            'errors' => []
        ];

        if (!($handle = fopen($filePath, 'r'))) {
            throw new \Exception("No se pudo abrir el archivo para lectura.");
        }

        // Skip header
        fgetcsv($handle);

        DB::beginTransaction();
        try {
            $rowNumber = 1;
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $rowNumber++;
                
                // Expecting: Torre, Nombre, Coeficiente, Propietario, ID, Email, Saldo
                if (count($data) < 2) continue;

                try {
                    $unidad = Unidad::updateOrCreate(
                        [
                            'copropiedad_id' => $copropiedadId,
                            'torre' => $data[0] ?? null,
                            'nombre' => $data[1] ?? 'Sin Nombre',
                        ],
                        [
                            'coeficiente' => $data[2] ? (float) $data[2] : 0,
                            'propietario_nombre' => $data[3] ?? null,
                            'propietario_identificacion' => $data[4] ?? null,
                            'email_contacto' => $data[5] ?? null,
                            'saldo_actual' => $data[6] ? (float) $data[6] : 0,
                        ]
                    );

                    // Automatic User Linking Logic
                    $email = $data[5] ?? null;
                    if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $user = User::where('email', $email)->first();
                        
                        if (!$user) {
                            // Create "Invitation Pending" User
                            $user = User::create([
                                'name' => $data[3] ?? "Residente {$data[1]}",
                                'email' => $email,
                                'password' => Hash::make(Str::random(16)), // Placeholder password
                                'current_copropiedad_id' => $copropiedadId
                            ]);
                        }

                        // Link user to unit if not already linked
                        $unidad->users()->syncWithoutDetaching([$user->id => ['role' => 'propietario']]);
                    }

                    $results['success']++;
                } catch (\Exception $e) {
                    $results['errors'][] = "Fila {$rowNumber}: " . $e->getMessage();
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error masivo importando unidades: " . $e->getMessage());
            throw $e;
        } finally {
            fclose($handle);
        }

        return $results;
    }
}
