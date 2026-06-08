<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('asambleas');
        
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('DROP TABLE IF EXISTS asamblea.asambleas CASCADE');
        }

        Schema::create('asamblea.asambleas', function (Blueprint $table) {
            $column = $table->uuid('id')->primary();
            $table->foreignUuid('copropiedad_id')->constrained('property.copropiedades')->onDelete('cascade');
            $table->string('titulo');
            $table->dateTime('fecha');
            $table->string('status')->default('scheduled'); // scheduled, in_progress, finished
            $table->json('settings')->nullable();
            $table->timestamps();

            $table->index(['copropiedad_id', 'status']);
            // PostgreSQL Partial Index for active assemblies
            if (DB::getDriverName() === 'pgsql') {
                $table->index(['status'], 'idx_asambleas_active')->where('status', 'in_progress');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asamblea.asambleas');
    }
};
