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
        Schema::create('pqrs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('unidad_id')->constrained('unidades')->cascadeOnDelete();
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('tipo', [
                'peticion', 
                'queja', 
                'reclamo', 
                'sugerencia', 
                'reporte_danos', 
                'felicitaciones'
            ]);
            $table->string('asunto');
            $table->text('mensaje');
            $table->enum('prioridad', ['baja', 'media', 'alta'])->default('media');
            $table->enum('estado', ['abierto', 'en_proceso', 'cerrado', 'reabierto'])->default('abierto');
            $table->text('respuesta')->nullable();
            $table->timestamp('fecha_respuesta')->nullable();
            $table->json('adjuntos')->nullable();
            $table->timestamps();

            $table->index(['unidad_id', 'estado']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pqrs');
    }
};
