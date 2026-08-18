<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Roles
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin']);
        $schoolAdminRole = Role::firstOrCreate(['name' => 'School Admin']);

        // Create Super Admin User
        $superAdmin = User::updateOrCreate(
            ['email' => 'admin@school.edu'],
            [
                'name' => 'Super Administrator',
                'password' => Hash::make('Admin@1234'),
                'phone' => '+91 99999 88888',
                'status' => 'active',
            ]
        );

        $superAdmin->assignRole($superAdminRole);
    }
}
