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
        // Solo ejecutamos lógica de esquemas si estamos en PostgreSQL
        if (\Illuminate\Support\Facades\Schema::getConnection()->getDriverName() === 'pgsql') {
            \Illuminate\Support\Facades\DB::statement('CREATE SCHEMA IF NOT EXISTS iam;');
            
            // Movemos las tablas de identidad al nuevo esquema si no existen allí
            if (\Illuminate\Support\Facades\Schema::hasTable('users') && !\Illuminate\Support\Facades\Schema::hasTable('iam.users')) {
                \Illuminate\Support\Facades\DB::statement('ALTER TABLE users SET SCHEMA iam;');
            }
            if (\Illuminate\Support\Facades\Schema::hasTable('password_reset_tokens') && !\Illuminate\Support\Facades\Schema::hasTable('iam.password_reset_tokens')) {
                \Illuminate\Support\Facades\DB::statement('ALTER TABLE password_reset_tokens SET SCHEMA iam;');
            }
            if (\Illuminate\Support\Facades\Schema::hasTable('sessions') && !\Illuminate\Support\Facades\Schema::hasTable('iam.sessions')) {
                \Illuminate\Support\Facades\DB::statement('ALTER TABLE sessions SET SCHEMA iam;');
            }
            if (\Illuminate\Support\Facades\Schema::hasTable('legal_documents') && !\Illuminate\Support\Facades\Schema::hasTable('iam.legal_documents')) {
                \Illuminate\Support\Facades\DB::statement('ALTER TABLE legal_documents SET SCHEMA iam;');
            }
            if (\Illuminate\Support\Facades\Schema::hasTable('legal_consents') && !\Illuminate\Support\Facades\Schema::hasTable('iam.legal_consents')) {
                \Illuminate\Support\Facades\DB::statement('ALTER TABLE legal_consents SET SCHEMA iam;');
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (\Illuminate\Support\Facades\Schema::getConnection()->getDriverName() === 'pgsql') {
            if (\Illuminate\Support\Facades\Schema::hasTable('iam.users')) {
                \Illuminate\Support\Facades\DB::statement('ALTER TABLE iam.users SET SCHEMA public;');
            }
            if (\Illuminate\Support\Facades\Schema::hasTable('iam.password_reset_tokens')) {
                \Illuminate\Support\Facades\DB::statement('ALTER TABLE iam.password_reset_tokens SET SCHEMA public;');
            }
            if (\Illuminate\Support\Facades\Schema::hasTable('iam.sessions')) {
                \Illuminate\Support\Facades\DB::statement('ALTER TABLE iam.sessions SET SCHEMA public;');
            }
            if (\Illuminate\Support\Facades\Schema::hasTable('iam.legal_documents')) {
                \Illuminate\Support\Facades\DB::statement('ALTER TABLE iam.legal_documents SET SCHEMA public;');
            }
            if (\Illuminate\Support\Facades\Schema::hasTable('iam.legal_consents')) {
                \Illuminate\Support\Facades\DB::statement('ALTER TABLE iam.legal_consents SET SCHEMA public;');
            }
            
            \Illuminate\Support\Facades\DB::statement('DROP SCHEMA IF EXISTS iam CASCADE;');
        }
    }
};
