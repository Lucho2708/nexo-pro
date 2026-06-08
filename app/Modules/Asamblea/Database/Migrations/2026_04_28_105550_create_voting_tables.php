<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('votos');
        Schema::dropIfExists('opciones');
        Schema::dropIfExists('preguntas');
        
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('DROP TABLE IF EXISTS asamblea.votos CASCADE');
            DB::statement('DROP TABLE IF EXISTS asamblea.opciones CASCADE');
            DB::statement('DROP TABLE IF EXISTS asamblea.preguntas CASCADE');
        }

        Schema::create('asamblea.preguntas', function (Blueprint $table) {
            $column = $table->uuid('id')->primary();
            $table->foreignUuid('asamblea_id')->constrained('asamblea.asambleas')->onDelete('cascade');
            $table->string('titulo');
            $table->text('descripcion')->nullable();
            $table->string('tipo')->default('simple'); // simple, multiple
            $table->string('status')->default('draft'); // draft, open, closed
            $table->timestamps();
        });

        Schema::create('asamblea.opciones', function (Blueprint $table) {
            $column = $table->uuid('id')->primary();
            $table->foreignUuid('pregunta_id')->constrained('asamblea.preguntas')->onDelete('cascade');
            $table->string('titulo');
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::create('asamblea.votos', function (Blueprint $table) {
            $column = $table->uuid('id')->primary();
            $table->foreignUuid('pregunta_id')->constrained('asamblea.preguntas')->onDelete('cascade');
            $table->foreignUuid('user_id')->constrained('iam.users')->onDelete('cascade');
            $table->foreignUuid('unidad_id')->constrained('property.unidades')->onDelete('cascade');
            $table->foreignUuid('opcion_id')->constrained('asamblea.opciones')->onDelete('cascade');
            $table->decimal('peso', 10, 5)->default(0);
            $table->timestamps();

            $table->unique(['pregunta_id', 'unidad_id']); // Unique vote per unit per question
            $table->index(['pregunta_id', 'opcion_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asamblea.votos');
        Schema::dropIfExists('asamblea.opciones');
        Schema::dropIfExists('asamblea.preguntas');
    }
};
