<?php

namespace Database\Seeders;

use App\Modules\IAM\Models\User;
use App\Modules\IAM\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserRoleAssignmentSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'contacto@nexo-pro.com.co')->first();
        $role = Role::where('name', 'admin')->first();

        if ($user && $role) {
            DB::table('iam.role_user')->updateOrInsert(
                ['user_id' => $user->id, 'role_id' => $role->id]
            );
        }
    }
}
