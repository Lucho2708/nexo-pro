<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('componente_tipo_unidad');
        DB::statement('DROP TABLE IF EXISTS property.componente_tipo_unidad CASCADE');

        Schema::create('property.componente_tipo_unidad', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('tipo_unidad_id')->constrained('property.tipos_unidad')->onDelete('cascade');
            $table->foreignUuid('componente_id')->constrained('property.componentes_unidad')->onDelete('cascade');
            $table->integer('cantidad')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('property.componente_tipo_unidad');
    }
};
