<?php

namespace Database\Seeders;

use App\Models\JobCategory;
use Illuminate\Database\Seeder;

class JobCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Teaching (Primary)', 'description' => 'Primary school teaching positions for Grade 1 to 5.'],
            ['name' => 'Teaching (Secondary & PGT)', 'description' => 'Secondary and post-graduate teacher vacancies.'],
            ['name' => 'Administration & Leadership', 'description' => 'Principals, Vice-Principals, Coordinators, and Admin staff.'],
            ['name' => 'Accounts & Finance', 'description' => 'School accountants, cashiers, and bursars.'],
            ['name' => 'Library & Media', 'description' => 'Librarians and resource center managers.'],
            ['name' => 'Sports & Physical Education', 'description' => 'PET coaches, sports trainers, and fitness instructors.'],
            ['name' => 'IT & Technology Services', 'description' => 'System admins, computer lab teachers, and IT support.'],
            ['name' => 'Transport & Security', 'description' => 'Fleet managers, drivers, and security supervisors.'],
        ];

        foreach ($categories as $cat) {
            JobCategory::firstOrCreate(['name' => $cat['name']], $cat);
        }
    }
}
