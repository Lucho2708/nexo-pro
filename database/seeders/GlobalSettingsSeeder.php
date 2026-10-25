<?php

namespace Database\Seeders;

use App\Modules\Operations\Models\GlobalSetting;
use Illuminate\Database\Seeder;

class GlobalSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'maintenance_mode', 'value' => 'false'],
            ['key' => 'registration_enabled', 'value' => 'false'],
            ['key' => 'platform_name', 'value' => 'NEXO-PRO'],
            ['key' => 'default_currency', 'value' => 'COP'],
        ];

        foreach ($settings as $setting) {
            GlobalSetting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
