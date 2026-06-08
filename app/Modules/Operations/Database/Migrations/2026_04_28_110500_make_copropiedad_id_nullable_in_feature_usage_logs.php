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
        Schema::table('operations.feature_usage_logs', function (Blueprint $table) {
            $table->foreignUuid('copropiedad_id')->nullable()->change();
            $table->foreignUuid('user_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('operations.feature_usage_logs', function (Blueprint $table) {
            $table->foreignUuid('copropiedad_id')->nullable(false)->change();
        });
    }
};
