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
        $tables = ['operations.pqrs', 'operations.reservas', 'finance.transacciones'];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->foreignUuid('copropiedad_id')->nullable()->after('id')->constrained('property.copropiedades')->cascadeOnDelete();
                $table->index('copropiedad_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = ['operations.pqrs', 'operations.reservas', 'finance.transacciones'];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropForeign(['copropiedad_id']); 
                $table->dropColumn('copropiedad_id');
            });
        }
    }
};
