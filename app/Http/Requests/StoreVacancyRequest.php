<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVacancyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->user()?->school_id && !$this->filled('school_id')) {
            $this->merge([
                'school_id' => $this->user()->school_id,
            ]);
        }

        if (!$this->filled('gender_preference')) {
            $this->merge([
                'gender_preference' => 'any',
            ]);
        }

        if ($this->has('is_featured')) {
            $this->merge([
                'is_featured' => $this->boolean('is_featured'),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'school_id' => 'required|exists:schools,id',
            'title' => 'required|string|max:255',
            'vacancy_type' => 'required|in:campus,class',
            'department_id' => 'required|exists:departments,id',
            'global_class_id' => 'nullable|exists:global_classes,id',
            'job_category_id' => 'required|exists:job_categories,id',
            'employment_type' => 'required|in:full_time,part_time,contract,temporary',
            'experience_level' => 'nullable|string|max:100',
            'min_qualification' => 'nullable|string|max:255',
            'salary_from' => 'nullable|numeric|min:0',
            'salary_to' => 'nullable|numeric|min:0',
            'gender_preference' => 'nullable|in:any,male,female',
            'number_of_vacancies' => 'required|integer|min:1',
            'location' => 'nullable|string|max:255',
            'description' => 'required|string',
            'responsibilities' => 'nullable|string',
            'requirements' => 'nullable|string',
            'benefits' => 'nullable|string',
            'deadline' => 'nullable|date',
            'publish_date' => 'nullable|date',
            'status' => 'required|in:draft,published,closed,expired,archived',
            'is_featured' => 'nullable|boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ];
    }
}
