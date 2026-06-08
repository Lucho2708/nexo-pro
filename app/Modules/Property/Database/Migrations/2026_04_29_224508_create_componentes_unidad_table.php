<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('componentes_unidad');
        DB::statement('DROP TABLE IF EXISTS property.componentes_unidad CASCADE');

        Schema::create('property.componentes_unidad', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nombre')->unique(); // Ej: Habitación, Baño, Balcón
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('property.componentes_unidad');
    }
};
