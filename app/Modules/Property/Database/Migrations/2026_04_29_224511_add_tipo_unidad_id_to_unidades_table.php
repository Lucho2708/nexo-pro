<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('property.unidades', function (Blueprint $table) {
            $table->foreignUuid('tipo_unidad_id')->nullable()->after('copropiedad_id')->constrained('property.tipos_unidad')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('property.unidades', function (Blueprint $table) {
            $table->dropForeign(['tipo_unidad_id']);
            $table->dropColumn('tipo_unidad_id');
        });
    }
};
