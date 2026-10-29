<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $isPgsql = DB::getDriverName() === 'pgsql';
        $parqueaderosTable = $isPgsql ? 'operations.parqueaderos_registro' : 'parqueaderos_registro';

        if (Schema::hasTable($parqueaderosTable)) {
            Schema::table($parqueaderosTable, function (Blueprint $table) {
                if (!Schema::hasColumn($table->getTable(), 'minutos_permanencia')) {
                    $table->unsignedInteger('minutos_permanencia')->nullable()->after('salida_at');
                }
                if (!Schema::hasColumn($table->getTable(), 'minutos_cobrables')) {
                    $table->unsignedInteger('minutos_cobrables')->default(0)->after('minutos_permanencia');
                }
                if (!Schema::hasColumn($table->getTable(), 'valor_liquidado')) {
                    $table->decimal('valor_liquidado', 12, 2)->default(0)->after('minutos_cobrables');
                }
                if (!Schema::hasColumn($table->getTable(), 'metodo_pago')) {
                    $table->string('metodo_pago', 30)->nullable()->after('valor_liquidado');
                }
                if (!Schema::hasColumn($table->getTable(), 'referencia_pago')) {
                    $table->string('referencia_pago', 64)->nullable()->index()->after('metodo_pago');
                }
                if (!Schema::hasColumn($table->getTable(), 'pagado_at')) {
                    $table->timestamp('pagado_at')->nullable()->after('referencia_pago');
                }
            });
        }
    }

    public function down(): void
    {
        $isPgsql = DB::getDriverName() === 'pgsql';
        $parqueaderosTable = $isPgsql ? 'operations.parqueaderos_registro' : 'parqueaderos_registro';

        if (Schema::hasTable($parqueaderosTable)) {
            Schema::table($parqueaderosTable, function (Blueprint $table) {
                $columns = ['pagado_at', 'referencia_pago', 'metodo_pago', 'valor_liquidado', 'minutos_cobrables', 'minutos_permanencia'];
                foreach ($columns as $column) {
                    if (Schema::hasColumn($table->getTable(), $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
};
