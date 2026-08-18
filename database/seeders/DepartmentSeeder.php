<?php

namespace Database\Seeders;

use App\Models\School;
use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            ['name' => 'Teaching', 'code' => 'TCH'],
            ['name' => 'Administration', 'code' => 'ADM'],
            ['name' => 'Accounts', 'code' => 'ACC'],
            ['name' => 'Library', 'code' => 'LIB'],
            ['name' => 'Sports', 'code' => 'SPT'],
            ['name' => 'Transport', 'code' => 'TRN'],
            ['name' => 'Security', 'code' => 'SEC'],
            ['name' => 'IT', 'code' => 'IT'],
            ['name' => 'Maintenance', 'code' => 'MNT'],
            ['name' => 'Housekeeping', 'code' => 'HSK'],
        ];

        $schools = School::all();

        foreach ($schools as $school) {
            foreach ($departments as $dept) {
                Department::withoutGlobalScopes()->firstOrCreate(
                    ['school_id' => $school->id, 'name' => $dept['name']],
                    ['code' => $dept['code'], 'is_active' => true]
                );
            }
        }
    }
}
