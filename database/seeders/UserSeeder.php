<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\School;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Seed real admin users.
     */
    public function run(): void
    {
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);

        // ── Super Admin Accounts ──────────────────────────────────────
        $superAdminEmails = [
            'admin@school.edu'
        ];

        foreach ($superAdminEmails as $email) {
            $superAdmin = User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => 'Super Administrator',
                    'password' => Hash::make('Admin@1234'),
                    'phone' => '1234567890',
                    'status' => 'active',
                    'school_id' => null,
                ]
            );
            $superAdmin->syncRoles($superAdminRole);
        }

        // ── School Admins (one per seeded school) ─────────────────────
        $schoolAdminRole = Role::firstOrCreate(['name' => 'School Admin', 'guard_name' => 'web']);

        $schools = School::all();

        $schoolAdmins = [
            'EC YES Campus' => ['EC YES Campus Administrator', 'admin.ecyes@ruihss.in', 'School@1234'],
            'Creasent Campus' => ['Creasent Campus Administrator', 'admin.creasent@ruihss.in', 'School@1234'],
            'Pared Campus' => ['Pared Campus Administrator', 'admin.pared@ruihss.in', 'School@1234'],
            'Allapir Campus' => ['Allapir Campus Administrator', 'admin.allapir@ruihss.in', 'School@1234'],
        ];

        foreach ($schools as $school) {
            if (!isset($schoolAdmins[$school->name])) {
                continue;
            }

            [$name, $email, $password] = $schoolAdmins[$school->name];

            $admin = User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'password' => Hash::make($password),
                    'school_id' => $school->id,
                    'status' => 'active',
                ]
            );

            $admin->syncRoles($schoolAdminRole);
        }
    }
}
