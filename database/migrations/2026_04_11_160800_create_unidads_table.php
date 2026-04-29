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
        Schema::create('unidades', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('copropiedad_id')->constrained('copropiedades')->cascadeOnDelete();
            $table->string('nombre'); // e.g. 402
            $table->string('torre')->nullable(); // e.g. Torre A
            $table->string('piso')->nullable();
            $table->decimal('coeficiente', 8, 4)->nullable();
            $table->string('propietario_nombre')->nullable();
            $table->string('propietario_identificacion')->nullable();
            $table->string('email_contacto')->nullable();
            $table->decimal('saldo_actual', 15, 2)->default(0);
            $table->timestamps();
            
            $table->unique(['copropiedad_id', 'torre', 'nombre']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unidades');
    }
};
