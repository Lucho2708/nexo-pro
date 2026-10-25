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
        $isPgsql = DB::getDriverName() === 'pgsql';

        // 1. VISITAS Y CONTROL DE ACCESOS
        if ($isPgsql) {
            DB::statement('DROP TABLE IF EXISTS operations.visitas CASCADE');
        } else {
            Schema::dropIfExists('visitas');
        }

        $visitasTable = $isPgsql ? 'operations.visitas' : 'visitas';
        Schema::create($visitasTable, function (Blueprint $table) use ($isPgsql) {
            $table->uuid('id')->primary();
            $table->uuid('copropiedad_id');
            $table->uuid('unidad_id');
            $table->string('visitante_nombre');
            $table->string('visitante_documento', 50);
            $table->string('tipo_visita', 30)->default('personal'); // personal, domicilio, contratista, taxi
            $table->string('placa_vehiculo', 20)->nullable();
            $table->string('empresa_servicio', 100)->nullable();
            $table->boolean('pre_autorizado')->default(false);
            $table->string('estado', 30)->default('ingresado'); // ingresado, finalizado
            $table->timestamp('ingreso_at')->useCurrent();
            $table->timestamp('salida_at')->nullable();
            $table->uuid('guarda_id');
            $table->text('observaciones')->nullable();
            $table->timestamps();

            $table->index(['copropiedad_id', 'ingreso_at']);
            $table->index('unidad_id');
            $table->index('visitante_documento');
            $table->index('placa_vehiculo');
        });

        // 2. RECIBOS PÚBLICOS
        if ($isPgsql) {
            DB::statement('DROP TABLE IF EXISTS operations.recibos_publicos CASCADE');
        } else {
            Schema::dropIfExists('recibos_publicos');
        }

        $recibosTable = $isPgsql ? 'operations.recibos_publicos' : 'recibos_publicos';
        Schema::create($recibosTable, function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('copropiedad_id');
            $table->uuid('unidad_id');
            $table->string('tipo_servicio', 50); // acueducto, energia, gas, telecomunicaciones, otros
            $table->string('empresa_emisora', 100)->nullable();
            $table->string('periodo_facturado', 50)->nullable();
            $table->string('estado', 30)->default('en_casillero'); // en_casillero, entregado
            $table->uuid('recibido_por_guarda_id');
            $table->timestamp('entregado_at')->nullable();
            $table->string('entregado_a_nombre', 255)->nullable();
            $table->timestamps();

            $table->index(['copropiedad_id', 'unidad_id']);
            $table->index('estado');
        });

        // 3. PAQUETERÍA Y ENCOMIENDAS
        if ($isPgsql) {
            DB::statement('DROP TABLE IF EXISTS operations.paquetes CASCADE');
        } else {
            Schema::dropIfExists('paquetes');
        }

        $paquetesTable = $isPgsql ? 'operations.paquetes' : 'paquetes';
        Schema::create($paquetesTable, function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('copropiedad_id');
            $table->uuid('unidad_id');
            $table->string('empresa_mensajeria', 100);
            $table->string('codigo_guia', 100)->nullable();
            $table->string('descripcion', 255)->nullable();
            $table->string('estado', 30)->default('recibido'); // recibido, notificado, entregado
            $table->uuid('recibido_por_guarda_id');
            $table->timestamp('entregado_at')->nullable();
            $table->string('entregado_a_nombre', 255)->nullable();
            $table->timestamps();

            $table->index(['copropiedad_id', 'estado']);
            $table->index('unidad_id');
        });

        // 4. PARQUEADEROS REGISTRO Y CAPACIDAD
        if ($isPgsql) {
            DB::statement('DROP TABLE IF EXISTS operations.parqueaderos_registro CASCADE');
        } else {
            Schema::dropIfExists('parqueaderos_registro');
        }

        $parqueaderosTable = $isPgsql ? 'operations.parqueaderos_registro' : 'parqueaderos_registro';
        Schema::create($parqueaderosTable, function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('copropiedad_id');
            $table->uuid('unidad_id')->nullable();
            $table->string('tipo_espacio', 30)->default('visitante'); // visitante, comunal, asignado
            $table->string('placa_vehiculo', 20);
            $table->string('numero_bahia', 20)->nullable();
            $table->string('estado', 30)->default('activo'); // activo, finalizado
            $table->timestamp('ingreso_at')->useCurrent();
            $table->timestamp('salida_at')->nullable();
            $table->uuid('guarda_id');
            $table->timestamps();

            $table->index(['copropiedad_id', 'estado']);
            $table->index('placa_vehiculo');
        });

        // 5. MINUTA DIGITAL DE PORTERÍA
        if ($isPgsql) {
            DB::statement('DROP TABLE IF EXISTS operations.minuta_porteria CASCADE');
        } else {
            Schema::dropIfExists('minuta_porteria');
        }

        $minutaTable = $isPgsql ? 'operations.minuta_porteria' : 'minuta_porteria';
        Schema::create($minutaTable, function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('copropiedad_id');
            $table->uuid('guarda_id');
            $table->string('tipo_evento', 50); // cambio_turno, novedad, ronda, incidente, aviso
            $table->string('prioridad', 20)->default('informativo'); // informativo, advertencia, urgente
            $table->text('descripcion');
            $table->timestamps();

            $table->index(['copropiedad_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $isPgsql = DB::getDriverName() === 'pgsql';

        Schema::dropIfExists($isPgsql ? 'operations.minuta_porteria' : 'minuta_porteria');
        Schema::dropIfExists($isPgsql ? 'operations.parqueaderos_registro' : 'parqueaderos_registro');
        Schema::dropIfExists($isPgsql ? 'operations.paquetes' : 'paquetes');
        Schema::dropIfExists($isPgsql ? 'operations.recibos_publicos' : 'recibos_publicos');
        Schema::dropIfExists($isPgsql ? 'operations.visitas' : 'visitas');
    }
};
