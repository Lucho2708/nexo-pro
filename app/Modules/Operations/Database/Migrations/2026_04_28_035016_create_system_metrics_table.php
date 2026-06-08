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
        Schema::dropIfExists('system_metrics');
        
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('DROP TABLE IF EXISTS operations.system_metrics CASCADE');
        }

        Schema::create('operations.system_metrics', function (Blueprint $table) {
            $column = $table->uuid('id')->primary();
            $table->string('method', 10)->nullable();
            $table->float('latency_ms');
            $table->integer('status_code');
            $table->string('path')->nullable();
            $table->timestamps(); // Registraremos la métrica con su created_at
            
            $table->index('created_at'); // Indexar para consultas rápidas de series temporales
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('system_metrics');
    }
};
