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
        Schema::table('iam.users', function (Blueprint $table) {
            $table->string('role')->default('owner')->after('email'); // super_admin, admin, owner
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('iam.users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
