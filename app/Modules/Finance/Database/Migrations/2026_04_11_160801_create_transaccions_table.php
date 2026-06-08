<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('transacciones');

        if (DB::getDriverName() === 'pgsql') {
            DB::statement('DROP TABLE IF EXISTS finance.transacciones CASCADE');
        }

        Schema::create('finance.transacciones', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('unidad_id')->constrained('property.unidades')->cascadeOnDelete();
            $table->foreignUuid('concepto_id')->constrained('finance.conceptos_cobro')->cascadeOnDelete();
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
        Schema::dropIfExists('finance.transacciones');
    }
};
