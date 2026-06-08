<?php

namespace App\Services\Tenant;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class AssemblyTableManager
{
    /**
     * Create all necessary tables for a specific assembly isolation.
     */
    public function createAssemblyEcosystem(string $shortId, string $copropiedadId): void
    {
        $this->createLogTable($shortId);
        $this->createVotesTable($shortId);
        $this->createQuorumTable($shortId);
        $this->createQuestionsTable($shortId);
        $this->createOptionsTable($shortId);
        
        $this->populateQuorumSnapshot($shortId, $copropiedadId);
    }

    /**
     * Snapshots existing units into the dynamic quorum table.
     */
    public function populateQuorumSnapshot(string $shortId, string $copropiedadId): void
    {
        $tableName = "asquorum_{$shortId}";
        
        // Only populate if it's empty
        if (DB::table($tableName)->count() > 0) {
            return;
        }

        $units = DB::table('property.unidades')
            ->where('copropiedad_id', $copropiedadId)
            ->select('id as unidad_id', 'nombre as nombre_unidad', 'coeficiente')
            ->get()
            ->map(function ($unit) {
                return [
                    'id' => \Illuminate\Support\Str::uuid(),
                    'unidad_id' => $unit->unidad_id,
                    'nombre_unidad' => $unit->nombre_unidad,
                    'coeficiente' => $unit->coeficiente,
                    'presente' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })->toArray();

        if (!empty($units)) {
            DB::table($tableName)->insert($units);
        }
    }

    public function registerVote(string $shortId, array $voteData, ?string $copropiedadId = null): void
    {
        $tableName = "asvotos_{$shortId}";
        
        if (!Schema::hasTable($tableName) && $copropiedadId) {
            $this->createAssemblyEcosystem($shortId, $copropiedadId);
        }

        DB::table($tableName)->insert(array_merge($voteData, [
            'id' => \Illuminate\Support\Str::uuid(),
            'created_at' => now(),
            'updated_at' => now(),
        ]));
    }

    public function hasVoted(string $shortId, string $preguntaId, string $unidadId): bool
    {
        $tableName = "asvotos_{$shortId}";

        if (!Schema::hasTable($tableName)) {
            return false;
        }

        return DB::table($tableName)
            ->where('pregunta_id', $preguntaId)
            ->where('unidad_id', $unidadId)
            ->exists();
    }

    private function createLogTable(string $shortId): void
    {
        $tableName = "aslog_{$shortId}";
        if (!Schema::hasTable($tableName)) {
            Schema::create($tableName, function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('copropiedad_id');
                $table->uuid('user_id');
                $table->uuid('unidad_id')->nullable();
                $table->string('event_type');
                $table->jsonb('payload')->nullable();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->timestamps();
                $table->index(['event_type']);
            });

            if (DB::getDriverName() === 'pgsql') {
                DB::statement("CREATE INDEX idx_{$tableName}_payload_gin ON {$tableName} USING GIN (payload)");
            }
        }
    }

    private function createVotesTable(string $shortId): void
    {
        $tableName = "asvotos_{$shortId}";
        if (!Schema::hasTable($tableName)) {
            Schema::create($tableName, function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('pregunta_id');
                $table->uuid('opcion_id');
                $table->uuid('unidad_id');
                $table->uuid('user_id');
                $table->decimal('peso', 10, 5)->default(0);
                $table->timestamps();
                $table->unique(['pregunta_id', 'unidad_id']);
            });
        }
    }

    private function createQuorumTable(string $shortId): void
    {
        $tableName = "asquorum_{$shortId}";
        if (!Schema::hasTable($tableName)) {
            Schema::create($tableName, function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('unidad_id');
                $table->uuid('user_id')->nullable(); // Para soporte standalone
                $table->string('documento_identidad')->nullable(); // Para validación Opción B
                $table->string('nombre_unidad');
                $table->decimal('coeficiente', 10, 5);
                $table->boolean('presente')->default(false);
                $table->timestamp('entry_at')->nullable();
                $table->timestamps();
                
                $table->index(['user_id']);
            });
        }
    }

    private function createQuestionsTable(string $shortId): void
    {
        $tableName = "aspreguntas_{$shortId}";
        if (!Schema::hasTable($tableName)) {
            Schema::create($tableName, function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('titulo');
                $table->text('descripcion')->nullable();
                $table->string('tipo')->default('simple');
                $table->string('status')->default('draft');
                $table->timestamps();
            });
        }
    }

    private function createOptionsTable(string $shortId): void
    {
        $tableName = "asopciones_{$shortId}";
        if (!Schema::hasTable($tableName)) {
            Schema::create($tableName, function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('pregunta_id');
                $table->string('titulo');
                $table->integer('order')->default(0);
                $table->timestamps();
            });
        }
    }
}
