<?php

namespace App\Services\Tenant;

use App\Models\Asamblea;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StandaloneOnboardingService
{
    public function __construct(
        protected AssemblyTableManager $tableManager
    ) {}

    /**
     * Import external quorum data into an assembly.
     * $data is an array of units: [['torre', 'apto', 'nombre', 'coeficiente', 'documento'], ...]
     */
    public function importExternalData(Asamblea $asamblea, array $data): void
    {
        $shortId = substr($asamblea->id, 0, 8);
        $tableName = "asquorum_{$shortId}";

        // 1. Ensure tables exist
        $this->tableManager->createAssemblyEcosystem($shortId, $asamblea->copropiedad_id);

        // 2. Create Real "Shell" Units in the main table for Eloquent compatibility
        $units = collect($data)->map(function ($row) use ($asamblea) {
            // Create in main table and let HasUuids do its magic
            $unitModel = \App\Models\Unidad::create([
                'copropiedad_id' => $asamblea->copropiedad_id,
                'nombre' => "{$row['torre']} - {$row['apto']}",
                'torre' => $row['torre'],
                'piso' => $row['apto'],
                'coeficiente' => $row['coeficiente'],
            ]);

            return [
                'id' => Str::uuid(),
                'unidad_id' => $unitModel->id, // Real ID from DB
                'user_id' => null,
                'documento_identidad' => $row['documento'],
                'nombre_unidad' => "{$row['torre']} - {$row['apto']}",
                'coeficiente' => $row['coeficiente'],
                'presente' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        })->toArray();

        // 3. Insert into the dynamic table
        DB::table($tableName)->insert($units);
    }

    /**
     * Validate a user attempt to join a standalone assembly.
     */
    public function validateAccess(Asamblea $asamblea, string $nombreUnidad, string $last4Digits): ?object
    {
        $shortId = substr($asamblea->id, 0, 8);
        $tableName = "asquorum_{$shortId}";

        return DB::table($tableName)
            ->where('nombre_unidad', 'LIKE', "%{$nombreUnidad}%")
            ->get()
            ->first(function ($record) use ($last4Digits) {
                // We check if the last 4 digits match the document
                return Str::endsWith($record->documento_identidad, $last4Digits);
            });
    }
}
