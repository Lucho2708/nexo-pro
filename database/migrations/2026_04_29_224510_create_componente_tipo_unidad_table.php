<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('componente_tipo_unidad', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('tipo_unidad_id')->constrained('tipos_unidad')->onDelete('cascade');
            $table->foreignUuid('componente_id')->constrained('componentes_unidad')->onDelete('cascade');
            $table->integer('cantidad')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('componente_tipo_unidad');
    }
};
