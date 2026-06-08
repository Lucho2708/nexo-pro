<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('CREATE SCHEMA IF NOT EXISTS iam');
        DB::statement('CREATE SCHEMA IF NOT EXISTS property');
        DB::statement('CREATE SCHEMA IF NOT EXISTS operations');
        DB::statement('CREATE SCHEMA IF NOT EXISTS finance');
        DB::statement('CREATE SCHEMA IF NOT EXISTS asamblea');
    }

    public function down(): void
    {
        // Don't drop schemas automatically to avoid accidental data loss
    }
};
