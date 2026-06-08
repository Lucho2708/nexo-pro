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
        Schema::dropIfExists('admin_copropiedad');
        DB::statement('DROP TABLE IF EXISTS property.admin_copropiedad CASCADE');

        Schema::create('property.admin_copropiedad', function (Blueprint $table) {
            $table->foreignUuid('user_id')->constrained('iam.users')->onDelete('cascade');
            $table->foreignUuid('copropiedad_id')->constrained('property.copropiedades')->onDelete('cascade');
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
        Schema::dropIfExists('property.admin_copropiedad');
    }
};
