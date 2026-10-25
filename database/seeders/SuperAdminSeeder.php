<?php

namespace Database\Seeders;

use App\Modules\IAM\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Seed the application's Super Admin.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@nexo-pro.com.co'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('1070608568Luis'),
                'role' => 'super_admin',
            ]
        );
    }
}
