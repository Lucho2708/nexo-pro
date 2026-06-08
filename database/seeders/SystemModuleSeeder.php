<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SystemModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modules = [
            ['name' => 'Panel de Control', 'key' => 'DASHBOARD'],
            ['name' => 'Gestión de Usuarios', 'key' => 'USUARIOS'],
            ['name' => 'Gestión de Conjuntos', 'key' => 'COPROPIEDADES'],
            ['name' => 'Licenciamiento', 'key' => 'LICENCIAS'],
            ['name' => 'Cartera y Cobros', 'key' => 'CARTERA'],
            ['name' => 'PQRS y Requerimientos', 'key' => 'PQRS'],
            ['name' => 'Zonas y Reservas', 'key' => 'RESERVAS'],
            ['name' => 'Comunicación y Anuncios', 'key' => 'ANUNCIOS'],
            ['name' => 'Seguridad y 2FA', 'key' => 'SEGURIDAD'],
            ['name' => 'Transacciones Financieras', 'key' => 'TRANSACCIONES'],
            ['name' => 'Centro de Notificaciones', 'key' => 'NOTIFICACIONES'],
            ['name' => 'Configuración Global', 'key' => 'AJUSTES'],
        ];

        foreach ($modules as $module) {
            SystemModule::updateOrCreate(['key' => $module['key']], $module);
        }
    }
}
$module);
        }
    }
}
