<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * HU-SEC-09: Agregar campos forenses e inmutables a la minuta de seguridad.
     */
    public function up(): void
    {
        $isPgsql = DB::getDriverName() === 'pgsql';
        $table = $isPgsql ? 'operations.minuta_porteria' : 'minuta_porteria';

        if (Schema::hasTable($table)) {
            Schema::table($table, function (Blueprint $table) {
                if (!Schema::hasColumn($table->getTable(), 'hash_inmutable')) {
                    $table->string('hash_inmutable', 64)->nullable()->after('descripcion');
                    $table->string('prev_hash', 64)->nullable()->after('hash_inmutable');
                    $table->string('evidencia_foto_path', 255)->nullable()->after('prev_hash');
                    $table->boolean('firmado_digitalmente')->default(true)->after('evidencia_foto_path');

                    $table->index('hash_inmutable');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $isPgsql = DB::getDriverName() === 'pgsql';
        $table = $isPgsql ? 'operations.minuta_porteria' : 'minuta_porteria';

        if (Schema::hasTable($table)) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropIndex(['hash_inmutable']);
                $table->dropColumn([
                    'hash_inmutable',
                    'prev_hash',
                    'evidencia_foto_path',
                    'firmado_digitalmente',
                ]);
            });
        }
    }
};
