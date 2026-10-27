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
        // 1. Datos Paramétricos y del Sistema (Indispensables para el arranque)
        $this->call([
            RolePermissionSeeder::class,
            GlobalSettingsSeeder::class,
            SystemModuleSeeder::class,
            LegalDocumentsSeeder::class,
            SuperAdminSeeder::class,
            ComponenteUnidadSeeder::class,
        ]);

        // 2. Datos de Demostración (ÚNICAMENTE en local si se solicita explícitamente)
        if (app()->environment('local') && env('SEED_DEMO_DATA', false) === true) {
            $this->call([
                DemoDataSeeder::class,
            ]);
        }
    }
}
