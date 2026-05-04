<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('copropiedades', function (Blueprint $table) {
            $table->boolean('unit_types_locked')->default(false)->after('unidades_totales');
        });
    }

    public function down(): void
    {
        Schema::table('copropiedades', function (Blueprint $table) {
            $table->dropColumn('unit_types_locked');
        });
    }
};
