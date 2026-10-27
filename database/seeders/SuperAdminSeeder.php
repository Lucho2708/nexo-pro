<?php

namespace Database\Seeders;

use App\Modules\IAM\Models\User;
use App\Modules\IAM\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Seed the application's Super Admin.
     */
    public function run(): void
    {
        $user = User::updateOrCreate(
            ['email' => 'admin@nexo-pro.com.co'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('1070608568Luis'),
                'role' => 'super_admin',
                'email_verified_at' => now(),
            ]
        );

        $superRole = Role::where('name', 'super_admin')->first();
        if ($superRole && !$user->roles()->where('role_id', $superRole->id)->exists()) {
            $user->roles()->attach($superRole->id);
        }
    }
}
