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

        // 1. PIN de Garita en iam.users
        $usersTable = $isPgsql ? 'iam.users' : 'users';
        if (Schema::hasTable($usersTable) && !Schema::hasColumn($usersTable, 'pin_porteria')) {
            Schema::table($usersTable, function (Blueprint $table) {
                $table->string('pin_porteria', 255)->nullable()->after('role');
            });
        }

        // 2. PIN Secreto de Entrega y Marca de Notificación en operations.paquetes
        $paquetesTable = $isPgsql ? 'operations.paquetes' : 'paquetes';
        if (Schema::hasTable($paquetesTable)) {
            Schema::table($paquetesTable, function (Blueprint $table) {
                if (!Schema::hasColumn($table->getTable(), 'pin_entrega')) {
                    $table->string('pin_entrega', 10)->nullable()->after('estado');
                }
                if (!Schema::hasColumn($table->getTable(), 'notificado_at')) {
                    $table->timestamp('notificado_at')->nullable()->after('pin_entrega');
                }
            });
        }
    }

    public function down(): void
    {
        $isPgsql = DB::getDriverName() === 'pgsql';

        $usersTable = $isPgsql ? 'iam.users' : 'users';
        if (Schema::hasTable($usersTable) && Schema::hasColumn($usersTable, 'pin_porteria')) {
            Schema::table($usersTable, function (Blueprint $table) {
                $table->dropColumn('pin_porteria');
            });
        }

        $paquetesTable = $isPgsql ? 'operations.paquetes' : 'paquetes';
        if (Schema::hasTable($paquetesTable)) {
            Schema::table($paquetesTable, function (Blueprint $table) {
                if (Schema::hasColumn($table->getTable(), 'pin_entrega')) {
                    $table->dropColumn('pin_entrega');
                }
                if (Schema::hasColumn($table->getTable(), 'notificado_at')) {
                    $table->dropColumn('notificado_at');
                }
            });
        }
    }
};
