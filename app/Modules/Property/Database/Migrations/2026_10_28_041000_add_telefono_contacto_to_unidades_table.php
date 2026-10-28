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
        if (Schema::hasTable('property.unidades')) {
            Schema::table('property.unidades', function (Blueprint $table) {
                if (!Schema::hasColumn('property.unidades', 'telefono_contacto')) {
                    $table->string('telefono_contacto', 50)->nullable()->after('email_contacto');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('property.unidades')) {
            Schema::table('property.unidades', function (Blueprint $table) {
                if (Schema::hasColumn('property.unidades', 'telefono_contacto')) {
                    $table->dropColumn('telefono_contacto');
                }
            });
        }
    }
};
