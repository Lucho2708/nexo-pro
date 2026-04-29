<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('system_metrics', function (Blueprint $table) {
            $table->id();
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
