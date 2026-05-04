<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Activar extensión para búsqueda avanzada en Postgres (Solo si el driver es pgsql)
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('CREATE EXTENSION IF NOT EXISTS pg_trgm');
        }

        Schema::create('unidades', function (Blueprint $table) {
            $column = $table->uuid('id')->primary();
            if (DB::getDriverName() === 'pgsql') {
                $column->default(DB::raw('gen_random_uuid()'));
            }
            $table->foreignUuid('copropiedad_id')->constrained('copropiedades')->cascadeOnDelete();
            $table->string('nombre'); // e.g. 402
            $table->string('torre')->nullable(); // e.g. Torre A
            $table->string('piso')->nullable();
            $table->decimal('coeficiente', 8, 4)->nullable();
            $table->string('propietario_nombre')->nullable();
            $table->string('propietario_identificacion')->nullable();
            $table->string('email_contacto')->nullable();
            $table->decimal('saldo_actual', 15, 2)->default(0);
            $table->timestamps();
            
            $table->unique(['copropiedad_id', 'torre', 'nombre']);
        });

        // Índice GIN para búsqueda rápida de propietarios y unidades (Solo si el driver es pgsql)
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('CREATE INDEX idx_unidades_search ON unidades USING GIN (nombre gin_trgm_ops, propietario_nombre gin_trgm_ops)');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unidades');
    }
};
