<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Public candidates can submit applications accountless
    }

    public function rules(): array
    {
        return [
            'vacancy_id' => 'required|exists:vacancies,id',
            'school_id' => 'required|exists:schools,id',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:30',
            'whatsapp_number' => 'nullable|string|max:30',
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => 'nullable|string|in:male,female,other',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'pin_code' => 'nullable|string|max:20',
            'highest_qualification' => 'required|string|max:255',
            'experience_years' => 'required|string|max:100',
            'current_employer' => 'nullable|string|max:255',
            'current_salary' => 'nullable|string|max:100',
            'expected_salary' => 'nullable|string|max:100',
            'notice_period' => 'nullable|string|max:100',
            'skills' => 'nullable|string|max:1000',
            'languages' => 'nullable|string|max:500',
            'photo' => 'required|file|mimes:jpg,jpeg,png,webp|max:2048',
            'cover_letter' => 'nullable|string|max:5000',
            'portfolio_url' => 'nullable|url|max:255',
            'linkedin_url' => 'nullable|url|max:255',
            'declaration_accepted' => 'required|accepted',
        ];
    }

    public function messages(): array
    {
        return [
            'photo.required' => 'Please upload your passport size photograph.',
            'photo.max'      => 'The profile photo size must not exceed 2MB. Please upload a smaller image.',
            'photo.mimes'    => 'The profile photo must be a valid JPG, JPEG, PNG, or WEBP image.',
            'declaration_accepted.accepted' => 'Please confirm and accept the application declaration.',
        ];
    }
}
