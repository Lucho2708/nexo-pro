<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('intervenciones');
        
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('DROP TABLE IF EXISTS asamblea.intervenciones CASCADE');
        }

        Schema::create('asamblea.intervenciones', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('asamblea_id')->constrained('asamblea.asambleas')->onDelete('cascade');
            $table->uuid('user_id'); // Referencia al usuario que habla
            $table->string('status')->default('pending'); // pending, active, completed, cancelled, forced_close
            $table->timestamp('requested_at', 3)->useCurrent();
            $table->timestamp('started_at', 3)->nullable();
            $table->timestamp('finished_at', 3)->nullable();
            $table->integer('duration_seconds')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('iam.users');
            $table->index(['asamblea_id', 'status']);        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asamblea.intervenciones');
    }
};
