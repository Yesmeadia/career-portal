<?php

namespace Database\Seeders;

use App\Models\School;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SchoolSeeder extends Seeder
{
    public function run(): void
    {
        $schools = [
            [
                'name' => 'EC YES Campus',
                'email' => 'ecyes@ruihss.in',
                'phone' => '+91 1800 123 4567',
                'website' => 'https://ruihss.in',
                'address' => 'Parade Ground, Old City, Poonch',
                'city' => 'Poonch',
                'state' => 'Jammu and Kashmir',
                'country' => 'India',
                'postal_code' => '185101',
                'status' => 'active',
                'admin_email' => 'admin.ecyes@ruihss.in',
                'admin_name' => 'EC YES Campus Administrator',
            ],
            [
                'name' => 'Creasent Campus',
                'email' => 'creasent@ruihss.in',
                'phone' => '+91 1800 123 4567',
                'website' => 'https://ruihss.in',
                'address' => 'Parade Ground, Old City, Poonch',
                'city' => 'Poonch',
                'state' => 'Jammu and Kashmir',
                'country' => 'India',
                'postal_code' => '185101',
                'status' => 'active',
                'admin_email' => 'admin.creasent@ruihss.in',
                'admin_name' => 'Creasent Campus Administrator',
            ],
            [
                'name' => 'Pared Campus',
                'email' => 'pared@ruihss.in',
                'phone' => '+91 1800 123 4567',
                'website' => 'https://ruihss.in',
                'address' => 'Parade Ground, Old City, Poonch',
                'city' => 'Poonch',
                'state' => 'Jammu and Kashmir',
                'country' => 'India',
                'postal_code' => '185101',
                'status' => 'active',
                'admin_email' => 'admin.pared@ruihss.in',
                'admin_name' => 'Pared Campus Administrator',
            ],
            [
                'name' => 'Allapir Campus',
                'email' => 'allapir@ruihss.in',
                'phone' => '+91 1800 123 4567',
                'website' => 'https://ruihss.in',
                'address' => 'Parade Ground, Old City, Poonch',
                'city' => 'Poonch',
                'state' => 'Jammu and Kashmir',
                'country' => 'India',
                'postal_code' => '185101',
                'status' => 'active',
                'admin_email' => 'admin.allapir@ruihss.in',
                'admin_name' => 'Allapir Campus Administrator',
            ],
        ];

        foreach ($schools as $data) {
            $adminEmail = $data['admin_email'];
            $adminName = $data['admin_name'];
            unset($data['admin_email'], $data['admin_name']);

            $school = School::updateOrCreate(['name' => $data['name']], $data);

            $admin = User::updateOrCreate(
                ['email' => $adminEmail],
                [
                    'name' => $adminName,
                    'password' => Hash::make('password'),
                    'school_id' => $school->id,
                    'status' => 'active',
                ]
            );

            $admin->assignRole('School Admin');
        }
    }
}
