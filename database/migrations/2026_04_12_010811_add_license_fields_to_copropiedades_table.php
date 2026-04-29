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
        Schema::table('copropiedades', function (Blueprint $table) {
            $table->string('license_status')->default('active')->after('plan'); // active, suspended, expired
            $table->date('license_expires_at')->nullable()->after('license_status');
            $table->date('trial_ends_at')->nullable()->after('license_expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('copropiedades', function (Blueprint $table) {
            $table->dropColumn(['license_status', 'license_expires_at', 'trial_ends_at']);
        });
    }
};
