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
        Schema::create('copropiedades', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nit')->unique();
            $table->string('nombre');
            $table->string('direccion');
            $table->string('ciudad');
            $table->string('plan')->default('basic');
            $table->integer('unidades_totales')->default(0);
            $table->integer('torres')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('copropiedades');
    }
};
