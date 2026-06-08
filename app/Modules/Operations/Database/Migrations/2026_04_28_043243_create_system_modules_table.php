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
        Schema::dropIfExists('system_modules');
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('DROP TABLE IF EXISTS operations.system_modules CASCADE');
        }
        Schema::create('operations.system_modules', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nombre legible: 'Gestión de Usuarios'
            $table->string('key')->unique(); // Llave técnica: 'USUARIOS'
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operations.system_modules');
    }
};
