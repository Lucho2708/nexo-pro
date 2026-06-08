<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('tickets');
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('DROP TABLE IF EXISTS operations.tickets CASCADE');
        }
        Schema::create('operations.tickets', function (Blueprint $blueprint) {
            $blueprint->uuid('id')->primary();
            $blueprint->foreignUuid('user_id')->constrained('iam.users')->onDelete('cascade');
            $blueprint->foreignUuid('copropiedad_id')->nullable()->constrained('property.copropiedades')->onDelete('set null');
            
            $blueprint->string('subject');
            $blueprint->text('description');
            
            $blueprint->enum('category', [
                'payments',      // Errores en pasarela o conciliación
                'reservations',  // Fallos en motor de reservas
                'billing',       // Problemas con liquidación masiva
                'ui_ux',         // Bugs visuales o de experiencia
                'access',        // Problemas de login o permisos
                'performance',   // Lentitud o timeouts
                'other'          // Misceláneos del sistema
            ])->default('other');

            $blueprint->enum('priority', [
                'low',           // Consulta o sugerencia
                'medium',        // Error menor, bypass disponible
                'high',          // Función crítica inoperable
                'critical',      // Caída total o pérdida de integridad
            ])->default('low');

            $blueprint->enum('status', [
                'open',          // Recién reportado
                'in_progress',   // Siendo analizado por ingeniería
                'resolved',      // Bug corregido
                'closed',        // Verificado y finalizado
            ])->default('open');

            $blueprint->text('resolution_notes')->nullable();
            
            $blueprint->timestamps();
            $blueprint->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operations.tickets');
    }
};
