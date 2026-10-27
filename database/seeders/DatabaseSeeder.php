<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Datos Paramétricos (Indispensables para el arranque)
        $this->call([
            RolePermissionSeeder::class,
            GlobalSettingsSeeder::class,
            SystemModuleSeeder::class,
            LegalDocumentsSeeder::class,
            SuperAdminSeeder::class,
            ComponenteUnidadSeeder::class,
        ]);

        // 2. Datos de Demostración (Entornos de desarrollo, pruebas y QA/staging)
        if (app()->environment('local', 'testing', 'staging')) {
            $this->call([
                DemoDataSeeder::class,
            ]);
        }
    }
}
