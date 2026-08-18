<?php

namespace Database\Seeders;

use App\Models\School;
use App\Models\Department;
use App\Models\GlobalClass;
use App\Models\JobCategory;
use App\Models\Vacancy;
use Illuminate\Database\Seeder;

class VacancySeeder extends Seeder
{
    public function run(): void
    {
        $school = School::first();
        if (!$school) return;

        $teachingDept = Department::withoutGlobalScopes()->where('school_id', $school->id)->where('name', 'Teaching')->first();
        $adminDept = Department::withoutGlobalScopes()->where('school_id', $school->id)->where('name', 'Administration')->first();
        $catTeaching = JobCategory::where('name', 'like', '%Teaching%')->first();
        $catAdmin = JobCategory::where('name', 'like', '%Administration%')->first();
        $grade10 = GlobalClass::where('name', 'Grade 10')->first();

        if ($teachingDept && $catTeaching) {
            Vacancy::withoutGlobalScopes()->create([
                'school_id' => $school->id,
                'title' => 'Senior PGT Mathematics Teacher',
                'vacancy_type' => 'class',
                'department_id' => $teachingDept->id,
                'global_class_id' => $grade10?->id,
                'job_category_id' => $catTeaching->id,
                'employment_type' => 'full_time',
                'experience_level' => '3-5 years',
                'min_qualification' => 'M.Sc Mathematics with B.Ed',
                'salary_from' => 45000,
                'salary_to' => 60000,
                'salary_currency' => 'INR',
                'gender_preference' => 'any',
                'number_of_vacancies' => 2,
                'location' => 'Parade Ground, Poonch',
                'description' => 'We are seeking an experienced PGT Mathematics teacher to educate Grade 10 students and prepare them for CBSE Board Examinations.',
                'responsibilities' => "- Prepare daily lesson plans according to CBSE curriculum.\n- Conduct regular assessments and mock exams.\n- Provide extra coaching to students requiring academic support.",
                'requirements' => "- Master's Degree in Mathematics.\n- B.Ed degree from a recognized university.\n- Minimum 3 years experience teaching Grade 9-12.",
                'benefits' => "- Free transport facility.\n- Health insurance cover.\n- Provident fund and annual bonus.",
                'deadline' => now()->addDays(30)->toDateString(),
                'publish_date' => now()->toDateString(),
                'status' => 'published',
                'is_featured' => true,
            ]);
        }

        if ($adminDept && $catAdmin) {
            Vacancy::withoutGlobalScopes()->create([
                'school_id' => $school->id,
                'title' => 'Assistant Admissions Coordinator',
                'vacancy_type' => 'campus',
                'department_id' => $adminDept->id,
                'global_class_id' => null,
                'job_category_id' => $catAdmin->id,
                'employment_type' => 'full_time',
                'experience_level' => '1-2 years',
                'min_qualification' => 'Bachelor\'s Degree in Communication / Admin',
                'salary_from' => 30000,
                'salary_to' => 40000,
                'salary_currency' => 'INR',
                'gender_preference' => 'any',
                'number_of_vacancies' => 1,
                'location' => 'Admin Block',
                'description' => 'Coordinate student admissions, parent counseling sessions, and campus tours.',
                'responsibilities' => "- Handle inbound parent inquiries.\n- Conduct campus tours.\n- Maintain student admission records.",
                'requirements' => "- Excellent spoken and written English.\n- Proficiency in MS Office and school CRM software.",
                'benefits' => "- Subsidized lunch.\n- Annual performance bonus.",
                'deadline' => now()->addDays(20)->toDateString(),
                'publish_date' => now()->toDateString(),
                'status' => 'published',
                'is_featured' => false,
            ]);
        }
    }
}
