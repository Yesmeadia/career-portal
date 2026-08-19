<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            GlobalClassSeeder::class,
            JobCategorySeeder::class,
            SchoolSeeder::class,
            UserSeeder::class,
            DepartmentSeeder::class,
            VacancySeeder::class,
            InterviewSeeder::class,
        ]);
    }
}
