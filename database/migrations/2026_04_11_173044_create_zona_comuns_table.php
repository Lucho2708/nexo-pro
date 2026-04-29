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
        Schema::create('zonas_comunes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('copropiedad_id')->constrained('copropiedades')->cascadeOnDelete();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->integer('capacidad_maxima')->default(10);
            $table->string('imagen_path')->nullable();
            $table->decimal('costo', 15, 2)->default(0);
            $table->json('settings')->nullable(); // Horarios, lead time, cancelación, etc.
            $table->boolean('activa')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zonas_comunes');
    }
};
