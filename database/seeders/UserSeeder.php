<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Seed single super admin account.
     */
    public function run(): void
    {
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);

        $superAdmin = User::updateOrCreate(
            ['email' => 'admin@school.edu'],
            [
                'name' => 'Super Administrator',
                'password' => Hash::make('Admin@1234'),
                'phone' => '+91 99999 88888',
                'status' => 'active',
                'school_id' => null,
            ]
        );

        $superAdmin->syncRoles($superAdminRole);
    }
}
