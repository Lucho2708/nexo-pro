<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('tipos_unidad');
        DB::statement('DROP TABLE IF EXISTS property.tipos_unidad CASCADE');

        Schema::create('property.tipos_unidad', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('copropiedad_id')->constrained('property.copropiedades')->onDelete('cascade');
            $table->string('nombre'); // Ej: Apartamento Tipo A
            $table->decimal('area_m2', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('property.tipos_unidad');
    }
};
