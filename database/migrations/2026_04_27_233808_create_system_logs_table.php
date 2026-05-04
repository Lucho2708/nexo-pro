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
        Schema::create('system_logs', function (Blueprint $table) {
            $column = $table->uuid('id')->primary();
            if (DB::getDriverName() === 'pgsql') {
                $column->default(DB::raw('gen_random_uuid()'));
            }
            $table->string('level_name', 50)->index();
            $table->integer('level')->index();
            $table->text('message');
            $table->json('context')->nullable();
            $table->string('env')->nullable();
            
            // Relaciones para análisis multi-tenant
            $table->uuid('copropiedad_id')->nullable()->index();
            $table->uuid('user_id')->nullable()->index();
            
            $table->timestamps();

            // Referencias
            $table->foreign('copropiedad_id')->references('id')->on('copropiedades')->nullOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_logs');
    }
};
