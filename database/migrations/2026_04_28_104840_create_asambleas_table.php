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
        Schema::create('asambleas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('copropiedad_id')->constrained('copropiedades')->onDelete('cascade');
            $table->string('titulo');
            $table->dateTime('fecha');
            $table->string('status')->default('scheduled'); // scheduled, in_progress, finished
            $table->json('settings')->nullable();
            $table->timestamps();

            $table->index(['copropiedad_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asambleas');
    }
};
