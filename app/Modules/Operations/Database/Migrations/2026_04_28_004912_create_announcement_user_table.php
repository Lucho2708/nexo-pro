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
        Schema::dropIfExists('announcement_user');
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('DROP TABLE IF EXISTS operations.announcement_user CASCADE');
        }
        Schema::create('operations.announcement_user', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('announcement_id')->constrained('operations.announcements')->onDelete('cascade');
            $table->foreignUuid('user_id')->constrained('iam.users')->onDelete('cascade');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('operations.announcement_user');
    }
};
