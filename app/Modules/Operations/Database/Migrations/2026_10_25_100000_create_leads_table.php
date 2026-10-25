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
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('DROP TABLE IF EXISTS operations.leads CASCADE');
        } else {
            Schema::dropIfExists('leads');
        }

        $tableName = DB::getDriverName() === 'pgsql' ? 'operations.leads' : 'leads';

        Schema::create($tableName, function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nombre');
            $table->string('email');
            $table->string('telefono');
            $table->string('copropiedad_nombre');
            $table->unsignedInteger('unidades_totales')->default(50);
            $table->string('rol')->default('administrador');
            $table->string('segmento')->default('boutique'); // boutique, standard, enterprise
            $table->decimal('cuota_promedio_mensual', 15, 2)->default(350000.00);
            $table->decimal('ahorro_estimado_anual', 15, 2)->default(0.00);
            $table->unsignedInteger('horas_ahorradas_mes')->default(0);
            $table->decimal('cartera_recuperable_estimada', 15, 2)->default(0.00);
            $table->string('estado')->default('nuevo'); // nuevo, contactado, calificado, demo_programada, descartado
            $table->unsignedInteger('score')->default(50); // 0 a 100
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['email', 'telefono']);
            $table->index(['segmento', 'estado']);
            $table->index('score');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('DROP TABLE IF EXISTS operations.leads CASCADE');
        } else {
            Schema::dropIfExists('leads');
        }
    }
};
