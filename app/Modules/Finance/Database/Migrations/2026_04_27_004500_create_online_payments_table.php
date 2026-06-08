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
        Schema::dropIfExists('online_payments');

        if (DB::getDriverName() === 'pgsql') {
            DB::statement('DROP TABLE IF EXISTS finance.online_payments CASCADE');
        }

        Schema::create('finance.online_payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('iam.users')->cascadeOnDelete();
            $table->foreignUuid('unidad_id')->constrained('property.unidades')->cascadeOnDelete();
            $table->decimal('amount', 15, 2);
            $table->string('currency', 3)->default('COP');
            $table->string('reference')->unique();
            $table->string('wompi_id')->nullable()->index();
            $table->string('status')->default('PENDING'); // PENDING, APPROVED, DECLINED, VOIDED, ERROR
            $table->string('payment_method')->nullable();
            $table->string('signature')->nullable();
            $table->json('gateway_response')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finance.online_payments');
    }
};
