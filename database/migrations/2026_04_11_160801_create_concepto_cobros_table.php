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
        Schema::create('concepto_cobros', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('copropiedad_id')->constrained('copropiedades')->cascadeOnDelete();
            $table->string('nombre');
            $table->string('codigo')->index();
            $table->text('descripcion')->nullable();
            $table->decimal('valor_fijo', 15, 2)->nullable();
            $table->boolean('es_obligatorio')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('concepto_cobros');
    }
};
