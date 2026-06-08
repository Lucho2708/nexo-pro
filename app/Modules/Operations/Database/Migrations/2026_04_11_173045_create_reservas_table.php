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
        Schema::dropIfExists('reservas');
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('DROP TABLE IF EXISTS operations.reservas CASCADE');
        }
        Schema::create('operations.reservas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('zona_id')->constrained('property.zonas_comunes')->cascadeOnDelete();
            $table->foreignUuid('user_id')->constrained('iam.users')->cascadeOnDelete();
            $table->foreignUuid('unidad_id')->constrained('property.unidades')->cascadeOnDelete();
            $table->date('fecha');
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->integer('cantidad_personas')->default(1);
            $table->decimal('monto_pagado', 15, 2)->default(0);
            $table->enum('estado', ['pendiente', 'aprobada', 'rechazada', 'cancelada', 'pagada'])->default('pendiente');
            $table->text('notas_admin')->nullable();
            $table->timestamps();

            $table->index(['zona_id', 'fecha']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operations.reservas');
    }
};
