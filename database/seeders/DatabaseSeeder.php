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
        // 1. Datos Paramétricos, del Sistema y Escenarios de Prueba
        $this->call([
            RolePermissionSeeder::class,
            GlobalSettingsSeeder::class,
            SystemModuleSeeder::class,
            LegalDocumentsSeeder::class,
            SuperAdminSeeder::class,
            ComponenteUnidadSeeder::class,
            DemoDataSeeder::class,
        ]);
    }
}
