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
        Schema::dropIfExists('iam.profiles');
        Schema::dropIfExists('iam.role_user');
        Schema::dropIfExists('iam.permission_role');
        Schema::dropIfExists('iam.permissions');
        Schema::dropIfExists('iam.roles');

        if (DB::getDriverName() === 'pgsql') {
            DB::statement('DROP TABLE IF EXISTS iam.profiles CASCADE');
            DB::statement('DROP TABLE IF EXISTS iam.role_user CASCADE');
            DB::statement('DROP TABLE IF EXISTS iam.permission_role CASCADE');
            DB::statement('DROP TABLE IF EXISTS iam.permissions CASCADE');
            DB::statement('DROP TABLE IF EXISTS iam.roles CASCADE');
        }

        // 1. Roles
        Schema::create('iam.roles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->unique(); // admin, owner, accountant, concierge
            $table->string('display_name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 2. Permisos
        Schema::create('iam.permissions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->unique(); // property:manage, assembly:vote, finance:view
            $table->string('display_name');
            $table->timestamps();
        });

        // 3. Pivote: Permisos asignados a Roles
        Schema::create('iam.permission_role', function (Blueprint $table) {
            $table->foreignUuid('role_id')->constrained('iam.roles')->onDelete('cascade');
            $table->foreignUuid('permission_id')->constrained('iam.permissions')->onDelete('cascade');
            $table->primary(['role_id', 'permission_id']);
        });

        // 4. Pivote: Roles asignados a Usuarios
        Schema::create('iam.role_user', function (Blueprint $table) {
            $table->foreignUuid('user_id')->constrained('iam.users')->onDelete('cascade');
            $table->foreignUuid('role_id')->constrained('iam.roles')->onDelete('cascade');
            $table->primary(['user_id', 'role_id']);
        });

        // 5. Tabla de Perfil (Información extendida)
        Schema::create('iam.profiles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('iam.users')->onDelete('cascade');
            $table->string('avatar_path')->nullable();
            $table->string('phone')->nullable();
            $table->date('birth_date')->nullable();
            $table->jsonb('preferences')->nullable(); // Tema, idiomas, notificaciones
            $table->text('bio')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('iam.profiles');
        Schema::dropIfExists('iam.role_user');
        Schema::dropIfExists('iam.permission_role');
        Schema::dropIfExists('iam.permissions');
        Schema::dropIfExists('iam.roles');
    }
};
