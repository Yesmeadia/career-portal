<?php

namespace Tests\Feature;

use App\Models\School;
use App\Models\User;
use App\Models\Vacancy;
use App\Models\Department;
use App\Models\JobCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class TenantIsolationTest extends TestCase
{
    use RefreshDatabase;

    public function test_school_admin_can_only_see_their_own_school_vacancies(): void
    {
        // Create Role
        $role = Role::firstOrCreate(['name' => 'School Admin']);

        // School 1
        $school1 = School::create([
            'name' => 'School A',
            'code' => 'SCH-A',
            'email' => 'a@school.com',
            'status' => 'active',
        ]);
        $admin1 = User::create([
            'name' => 'Admin A',
            'email' => 'admina@school.com',
            'password' => bcrypt('password'),
            'school_id' => $school1->id,
            'status' => 'active',
        ]);
        $admin1->assignRole($role);

        // School 2
        $school2 = School::create([
            'name' => 'School B',
            'code' => 'SCH-B',
            'email' => 'b@school.com',
            'status' => 'active',
        ]);
        $admin2 = User::create([
            'name' => 'Admin B',
            'email' => 'adminb@school.com',
            'password' => bcrypt('password'),
            'school_id' => $school2->id,
            'status' => 'active',
        ]);
        $admin2->assignRole($role);

        $category = JobCategory::create(['name' => 'Teaching', 'slug' => 'teaching']);
        $dept1 = Department::withoutGlobalScopes()->create(['school_id' => $school1->id, 'name' => 'Dept A']);
        $dept2 = Department::withoutGlobalScopes()->create(['school_id' => $school2->id, 'name' => 'Dept B']);

        $vacancy1 = Vacancy::withoutGlobalScopes()->create([
            'school_id' => $school1->id,
            'title' => 'Vacancy for School A',
            'slug' => 'vac-a',
            'department_id' => $dept1->id,
            'job_category_id' => $category->id,
            'description' => 'Test A',
            'status' => 'published',
        ]);

        $vacancy2 = Vacancy::withoutGlobalScopes()->create([
            'school_id' => $school2->id,
            'title' => 'Vacancy for School B',
            'slug' => 'vac-b',
            'department_id' => $dept2->id,
            'job_category_id' => $category->id,
            'description' => 'Test B',
            'status' => 'published',
        ]);

        // Login as Admin A
        $this->actingAs($admin1);

        $vacancies = Vacancy::all();

        $this->assertTrue($vacancies->contains($vacancy1));
        $this->assertFalse($vacancies->contains($vacancy2));
    }
}
