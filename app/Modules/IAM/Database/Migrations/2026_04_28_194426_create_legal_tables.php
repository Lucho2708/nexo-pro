<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('iam.legal_consents');
        Schema::dropIfExists('iam.legal_documents');
        
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('DROP TABLE IF EXISTS iam.legal_consents CASCADE');
            DB::statement('DROP TABLE IF EXISTS iam.legal_documents CASCADE');
        }

        Schema::create('iam.legal_documents', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type')->index(); // terms, privacy, cookies
            $table->string('title');
            $table->longText('body');
            $table->string('version')->default('1.0.0');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['type', 'is_active']);
        });

        Schema::create('iam.legal_consents', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('user_id')->constrained('iam.users')->cascadeOnDelete();
            $table->foreignUuid('legal_document_id')->constrained('iam.legal_documents')->cascadeOnDelete();
            $table->string('version');
            $table->timestamp('accepted_at');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'legal_document_id', 'version'], 'user_legal_version_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('iam.legal_consents');
        Schema::dropIfExists('iam.legal_documents');
    }
};
