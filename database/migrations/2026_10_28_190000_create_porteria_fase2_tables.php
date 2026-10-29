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

        // 1. PASES DE VISITA PRE-AUTORIZADOS Y QR
        if ($isPgsql) {
            DB::statement('CREATE SCHEMA IF NOT EXISTS operations');
            DB::statement('DROP TABLE IF EXISTS operations.pases_visita CASCADE');
        } else {
            Schema::dropIfExists('pases_visita');
        }

        $pasesTable = $isPgsql ? 'operations.pases_visita' : 'pases_visita';
        Schema::create($pasesTable, function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('copropiedad_id');
            $table->uuid('unidad_id');
            $table->uuid('residente_id');
            $table->string('token_acceso', 64)->unique();
            $table->string('codigo_pin', 12)->nullable()->index();
            $table->string('visitante_nombre', 255)->nullable();
            $table->string('visitante_documento', 50)->nullable();
            $table->string('placa_vehiculo', 20)->nullable();
            $table->string('tipo_pase', 30)->default('individual'); // individual, evento, contratista
            $table->timestamp('valido_desde');
            $table->timestamp('valido_hasta');
            $table->unsignedInteger('usos_permitidos')->default(1);
            $table->unsignedInteger('usos_consumidos')->default(0);
            $table->string('estado', 20)->default('activo'); // activo, agotado, expirado, cancelado
            $table->timestamps();

            $table->index(['copropiedad_id', 'estado']);
            $table->index('unidad_id');
            $table->index('residente_id');
        });

        // 2. SOLICITUDES DE CITOFONÍA VIRTUAL (WHATSAPP / PUSH)
        if ($isPgsql) {
            DB::statement('DROP TABLE IF EXISTS operations.solicitudes_citofonia CASCADE');
        } else {
            Schema::dropIfExists('solicitudes_citofonia');
        }

        $citofoniaTable = $isPgsql ? 'operations.solicitudes_citofonia' : 'solicitudes_citofonia';
        Schema::create($citofoniaTable, function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('copropiedad_id');
            $table->uuid('unidad_id');
            $table->uuid('guarda_id');
            $table->string('visitante_nombre', 255);
            $table->string('visitante_documento', 50)->nullable();
            $table->string('tipo_visita', 30)->default('personal');
            $table->string('token_respuesta', 64)->unique();
            $table->string('estado', 20)->default('pendiente'); // pendiente, autorizado, denegado, expirado
            $table->uuid('respondido_por_id')->nullable();
            $table->timestamp('respondido_at')->nullable();
            $table->timestamp('expira_at');
            $table->timestamps();

            $table->index(['copropiedad_id', 'estado']);
            $table->index('unidad_id');
        });

        // 3. CAMPOS DE CONTROL EXPRESS EN VISITAS
        $visitasTable = $isPgsql ? 'operations.visitas' : 'visitas';
        if (Schema::hasTable($visitasTable)) {
            Schema::table($visitasTable, function (Blueprint $table) {
                if (!Schema::hasColumn($table->getTable(), 'tiempo_limite_minutos')) {
                    $table->unsignedInteger('tiempo_limite_minutos')->nullable()->default(15)->after('empresa_servicio');
                }
                if (!Schema::hasColumn($table->getTable(), 'pase_visita_id')) {
                    $table->uuid('pase_visita_id')->nullable()->after('pre_autorizado');
                }
            });
        }
    }

    public function down(): void
    {
        $isPgsql = DB::getDriverName() === 'pgsql';

        Schema::dropIfExists($isPgsql ? 'operations.solicitudes_citofonia' : 'solicitudes_citofonia');
        Schema::dropIfExists($isPgsql ? 'operations.pases_visita' : 'pases_visita');

        $visitasTable = $isPgsql ? 'operations.visitas' : 'visitas';
        if (Schema::hasTable($visitasTable)) {
            Schema::table($visitasTable, function (Blueprint $table) {
                if (Schema::hasColumn($table->getTable(), 'tiempo_limite_minutos')) {
                    $table->dropColumn('tiempo_limite_minutos');
                }
                if (Schema::hasColumn($table->getTable(), 'pase_visita_id')) {
                    $table->dropColumn('pase_visita_id');
                }
            });
        }
    }
};
