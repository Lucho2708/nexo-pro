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
        Schema::table('property.copropiedades', function (Blueprint $table) {
            $table->decimal('area_construida_total', 12, 4)->nullable()->after('torres')->comment('Área construida total del conjunto en metros cuadrados (usada para calcular coeficientes)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('property.copropiedades', function (Blueprint $table) {
            $table->dropColumn('area_construida_total');
        });
    }
};
