<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('feature_usage_logs');
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('DROP TABLE IF EXISTS operations.feature_usage_logs CASCADE');
        }
        Schema::create('operations.feature_usage_logs', function (Blueprint $table) {
            $column = $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('iam.users')->cascadeOnDelete();
            $table->foreignUuid('copropiedad_id')->constrained('property.copropiedades')->cascadeOnDelete();
            // Feature keys: pqrs, reservas, pagos, dashboard, cartera
            $table->string('feature', 50)->index();
            $table->timestamp('used_at')->useCurrent();

            $table->index(['copropiedad_id', 'feature']);
            $table->index(['used_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('operations.feature_usage_logs');
    }
};
