<?php

namespace App\DTOs;

class VacancyData
{
    public function __construct(
        public int $school_id,
        public string $title,
        public string $vacancy_type,
        public int $department_id,
        public ?int $global_class_id,
        public int $job_category_id,
        public string $employment_type,
        public ?string $experience_level,
        public ?string $min_qualification,
        public ?float $salary_from,
        public ?float $salary_to,
        public string $salary_currency,
        public string $gender_preference,
        public int $number_of_vacancies,
        public ?string $location,
        public string $description,
        public ?string $responsibilities,
        public ?string $requirements,
        public ?string $benefits,
        public ?string $deadline,
        public ?string $publish_date,
        public string $status,
        public bool $is_featured,
        public ?string $meta_title,
        public ?string $meta_description,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            school_id: $data['school_id'],
            title: $data['title'],
            vacancy_type: $data['vacancy_type'] ?? 'campus',
            department_id: $data['department_id'],
            global_class_id: !empty($data['global_class_id']) ? (int) $data['global_class_id'] : null,
            job_category_id: $data['job_category_id'],
            employment_type: $data['employment_type'] ?? 'full_time',
            experience_level: $data['experience_level'] ?? null,
            min_qualification: $data['min_qualification'] ?? null,
            salary_from: isset($data['salary_from']) ? (float) $data['salary_from'] : null,
            salary_to: isset($data['salary_to']) ? (float) $data['salary_to'] : null,
            salary_currency: $data['salary_currency'] ?? 'INR',
            gender_preference: $data['gender_preference'] ?? 'any',
            number_of_vacancies: (int) ($data['number_of_vacancies'] ?? 1),
            location: $data['location'] ?? null,
            description: $data['description'],
            responsibilities: $data['responsibilities'] ?? null,
            requirements: $data['requirements'] ?? null,
            benefits: $data['benefits'] ?? null,
            deadline: $data['deadline'] ?? null,
            publish_date: $data['publish_date'] ?? null,
            status: $data['status'] ?? 'draft',
            is_featured: !empty($data['is_featured']),
            meta_title: $data['meta_title'] ?? null,
            meta_description: $data['meta_description'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'school_id' => $this->school_id,
            'title' => $this->title,
            'vacancy_type' => $this->vacancy_type,
            'department_id' => $this->department_id,
            'global_class_id' => $this->global_class_id,
            'job_category_id' => $this->job_category_id,
            'employment_type' => $this->employment_type,
            'experience_level' => $this->experience_level,
            'min_qualification' => $this->min_qualification,
            'salary_from' => $this->salary_from,
            'salary_to' => $this->salary_to,
            'salary_currency' => $this->salary_currency,
            'gender_preference' => $this->gender_preference,
            'number_of_vacancies' => $this->number_of_vacancies,
            'location' => $this->location,
            'description' => $this->description,
            'responsibilities' => $this->responsibilities,
            'requirements' => $this->requirements,
            'benefits' => $this->benefits,
            'deadline' => $this->deadline,
            'publish_date' => $this->publish_date,
            'status' => $this->status,
            'is_featured' => $this->is_featured,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
        ];
    }
}
