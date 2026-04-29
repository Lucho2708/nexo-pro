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
        Schema::create('admin_copropiedad', function (Blueprint $table) {
            $table->foreignUuid('user_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('copropiedad_id')->constrained('copropiedades')->onDelete('cascade');
            $table->timestamps();

            // Un admin no puede ser asignado dos veces a la misma copropiedad
            $table->primary(['user_id', 'copropiedad_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_copropiedad');
    }
};
