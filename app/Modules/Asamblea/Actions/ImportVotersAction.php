<?php

namespace App\Modules\Asamblea\Actions;

use App\Modules\Property\Models\Copropiedad;
use App\Modules\Property\Models\Unidad;
use App\Modules\IAM\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ImportVotersAction
{
    /**
     * Importa votantes directamente a partir de un arreglo de datos (CSV parseado).
     * Formato esperado: [torre, unidad, nombre, documento, email, coeficiente]
     */
    public function execute(Copropiedad $copropiedad, array $voters): array
    {
        $importedCount = 0;
        $errors = [];

        DB::transaction(function () use ($copropiedad, $voters, &$importedCount, &$errors) {
            foreach ($voters as $index => $row) {
                try {
                    // 1. Crear o actualizar la Unidad
                    $unidad = Unidad::updateOrCreate(
                        [
                            'copropiedad_id' => $copropiedad->id,
                            'torre' => $row['torre'] ?? 'Principal',
                            'nombre' => $row['unidad'],
                        ],
                        [
                            'coeficiente' => (float) $row['coeficiente'],
                            'propietario_nombre' => $row['nombre'],
                            'propietario_identificacion' => $row['documento'],
                            'email_contacto' => $row['email'] ?? null,
                        ]
                    );

                    // 2. Crear el Usuario para el portal (si tiene email y documento)
                    if (!empty($row['email']) && !empty($row['documento'])) {
                        $user = User::updateOrCreate(
                            ['email' => $row['email']],
                            [
                                'name' => $row['nombre'],
                                'password' => Hash::make($row['documento']), // Password inicial es su documento
                                'role' => 'owner',
                                'current_copropiedad_id' => $copropiedad->id,
                                'terms_accepted_at' => null, // Debe aceptar al entrar
                            ]
                        );

                        // Vincular usuario a la unidad
                        if (!$user->unidades()->where('unidad_id', $unidad->id)->exists()) {
                            $user->unidades()->attach($unidad->id, ['role' => 'owner']);
                        }
                    }

                    $importedCount++;
                } catch (\Exception $e) {
                    $errors[] = "Fila " . ($index + 1) . ": " . $e->getMessage();
                }
            }
        });

        return [
            'success' => $importedCount,
            'errors' => $errors
        ];
    }
}
