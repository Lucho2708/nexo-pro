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
        Schema::create('transacciones', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('unidad_id')->constrained('unidades')->cascadeOnDelete();
            $table->foreignUuid('concepto_id')->constrained('concepto_cobros')->cascadeOnDelete();
            $table->enum('tipo', ['cargo', 'abono']);
            $table->decimal('monto', 15, 2);
            $table->date('fecha');
            $table->string('referencia')->nullable();
            $table->string('soporte_path')->nullable();
            $table->timestamps();
            
            $table->index(['unidad_id', 'fecha']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transacciones');
    }
};
