<?php

namespace App\DTOs;

class ApplicationData
{
    public function __construct(
        public int $vacancy_id,
        public int $school_id,
        public string $first_name,
        public string $last_name,
        public string $email,
        public string $phone,
        public ?string $whatsapp_number,
        public ?string $date_of_birth,
        public ?string $gender,
        public ?string $address,
        public ?string $city,
        public ?string $state,
        public string $country,
        public ?string $pin_code,
        public ?string $highest_qualification,
        public ?string $experience_years,
        public ?string $current_employer,
        public ?string $current_salary,
        public ?string $expected_salary,
        public ?string $notice_period,
        public ?string $skills,
        public ?string $languages,
        public ?string $resume_path,
        public ?string $photo_path,
        public ?string $cover_letter,
        public ?string $portfolio_url,
        public ?string $linkedin_url,
        public bool $declaration_accepted,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            vacancy_id: $data['vacancy_id'],
            school_id: $data['school_id'],
            first_name: $data['first_name'],
            last_name: $data['last_name'],
            email: strtolower(trim($data['email'])),
            phone: trim($data['phone']),
            whatsapp_number: $data['whatsapp_number'] ?? null,
            date_of_birth: $data['date_of_birth'] ?? null,
            gender: $data['gender'] ?? null,
            address: $data['address'] ?? null,
            city: $data['city'] ?? null,
            state: $data['state'] ?? null,
            country: $data['country'] ?? 'India',
            pin_code: $data['pin_code'] ?? null,
            highest_qualification: $data['highest_qualification'] ?? null,
            experience_years: $data['experience_years'] ?? null,
            current_employer: $data['current_employer'] ?? null,
            current_salary: $data['current_salary'] ?? null,
            expected_salary: $data['expected_salary'] ?? null,
            notice_period: $data['notice_period'] ?? null,
            skills: $data['skills'] ?? null,
            languages: $data['languages'] ?? null,
            resume_path: $data['resume_path'] ?? null,
            photo_path: $data['photo_path'] ?? null,
            cover_letter: $data['cover_letter'] ?? null,
            portfolio_url: $data['portfolio_url'] ?? null,
            linkedin_url: $data['linkedin_url'] ?? null,
            declaration_accepted: !empty($data['declaration_accepted']),
        );
    }

    public function toArray(): array
    {
        return [
            'vacancy_id' => $this->vacancy_id,
            'school_id' => $this->school_id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'whatsapp_number' => $this->whatsapp_number,
            'date_of_birth' => $this->date_of_birth,
            'gender' => $this->gender,
            'address' => $this->address,
            'city' => $this->city,
            'state' => $this->state,
            'country' => $this->country,
            'pin_code' => $this->pin_code,
            'highest_qualification' => $this->highest_qualification,
            'experience_years' => $this->experience_years,
            'current_employer' => $this->current_employer,
            'current_salary' => $this->current_salary,
            'expected_salary' => $this->expected_salary,
            'notice_period' => $this->notice_period,
            'skills' => $this->skills,
            'languages' => $this->languages,
            'resume_path' => $this->resume_path,
            'photo_path' => $this->photo_path,
            'cover_letter' => $this->cover_letter,
            'portfolio_url' => $this->portfolio_url,
            'linkedin_url' => $this->linkedin_url,
            'declaration_accepted' => $this->declaration_accepted,
            'status' => 'submitted',
        ];
    }
}
