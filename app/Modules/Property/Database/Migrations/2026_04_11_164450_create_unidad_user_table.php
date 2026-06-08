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
        Schema::dropIfExists('unidad_user');
        DB::statement('DROP TABLE IF EXISTS property.unidad_user CASCADE');

        Schema::create('property.unidad_user', function (Blueprint $table) {
            $table->foreignUuid('unidad_id')->constrained('property.unidades')->onDelete('cascade');
            $table->foreignUuid('user_id')->constrained('iam.users')->onDelete('cascade');
            $table->string('role')->default('propietario'); // propietario, residente, apoderado, etc.
            $table->timestamps();

            $table->primary(['unidad_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property.unidad_user');
    }
};
