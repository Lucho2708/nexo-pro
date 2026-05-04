<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('componentes_unidad', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nombre')->unique(); // Ej: Habitación, Baño, Balcón
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('componentes_unidad');
    }
};
