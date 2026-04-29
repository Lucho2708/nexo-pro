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
        Schema::table('announcements', function (Blueprint $table) {
            $table->foreignUuid('copropiedad_id')->nullable()->after('id')->constrained('copropiedades')->onDelete('cascade');
            $table->foreignUuid('user_id')->nullable()->after('copropiedad_id')->constrained('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->dropForeign(['copropiedad_id']);
            $table->dropForeign(['user_id']);
            $table->dropColumn(['copropiedad_id', 'user_id']);
        });
    }
};
