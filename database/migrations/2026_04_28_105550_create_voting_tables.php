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
        Schema::create('preguntas', function (Blueprint $table) {
            $column = $table->uuid('id')->primary();
            if (DB::getDriverName() === 'pgsql') {
                $column->default(DB::raw('gen_random_uuid()'));
            }
            $table->foreignUuid('asamblea_id')->constrained('asambleas')->onDelete('cascade');
            $table->string('titulo');
            $table->text('descripcion')->nullable();
            $table->string('tipo')->default('simple'); // simple, multiple
            $table->string('status')->default('draft'); // draft, open, closed
            $table->timestamps();
        });

        Schema::create('opciones', function (Blueprint $table) {
            $column = $table->uuid('id')->primary();
            if (DB::getDriverName() === 'pgsql') {
                $column->default(DB::raw('gen_random_uuid()'));
            }
            $table->foreignUuid('pregunta_id')->constrained('preguntas')->onDelete('cascade');
            $table->string('titulo');
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::create('votos', function (Blueprint $table) {
            $column = $table->uuid('id')->primary();
            if (DB::getDriverName() === 'pgsql') {
                $column->default(DB::raw('gen_random_uuid()'));
            }
            $table->foreignUuid('pregunta_id')->constrained('preguntas')->onDelete('cascade');
            $table->foreignUuid('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignUuid('unidad_id')->constrained('unidades')->onDelete('cascade');
            $table->foreignUuid('opcion_id')->constrained('opciones')->onDelete('cascade');
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
        Schema::dropIfExists('votos');
        Schema::dropIfExists('opciones');
        Schema::dropIfExists('preguntas');
    }
};
