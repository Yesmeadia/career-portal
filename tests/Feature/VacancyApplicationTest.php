<?php

namespace Tests\Feature;

use App\Models\School;
use App\Models\Vacancy;
use App\Models\Department;
use App\Models\JobCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class VacancyApplicationTest extends TestCase
{
    use RefreshDatabase;

    public function test_candidate_can_apply_without_account(): void
    {
        Storage::fake('public');

        $school = School::create([
            'name' => 'Test School',
            'code' => 'TEST-01',
            'email' => 'info@test.com',
            'status' => 'active',
        ]);
        $category = JobCategory::create(['name' => 'General', 'slug' => 'general']);
        $dept = Department::withoutGlobalScopes()->create(['school_id' => $school->id, 'name' => 'Teaching']);

        $vacancy = Vacancy::withoutGlobalScopes()->create([
            'school_id' => $school->id,
            'title' => 'Primary Teacher',
            'slug' => 'primary-teacher',
            'department_id' => $dept->id,
            'job_category_id' => $category->id,
            'description' => 'Great position',
            'status' => 'published',
        ]);

        $resume = UploadedFile::fake()->create('resume.pdf', 500, 'application/pdf');

        $response = $this->post(route('applications.store'), [
            'vacancy_id' => $vacancy->id,
            'school_id' => $school->id,
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'email' => 'jane@example.com',
            'phone' => '+91 99999 11111',
            'highest_qualification' => 'Bachelor\'s Degree',
            'experience_years' => '2-5 years',
            'resume' => $resume,
            'declaration_accepted' => 1,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('applications', [
            'vacancy_id' => $vacancy->id,
            'email' => 'jane@example.com',
            'first_name' => 'Jane',
        ]);
    }
}
