<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('intervenciones', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('asamblea_id')->constrained()->onDelete('cascade');
            $table->uuid('user_id'); // Referencia al usuario que habla
            $table->string('status')->default('pending'); // pending, active, completed, cancelled, forced_close
            $table->timestamp('requested_at', 3)->useCurrent();
            $table->timestamp('started_at', 3)->nullable();
            $table->timestamp('finished_at', 3)->nullable();
            $table->integer('duration_seconds')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users');
            $table->index(['asamblea_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('intervenciones');
    }
};
