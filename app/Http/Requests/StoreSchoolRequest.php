<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSchoolRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('Super Admin') ?? false;
    }

    public function rules(): array
    {
        $school = $this->route('school');
        $adminUserId = $school ? $school->users()->first()?->id : null;

        return [
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'website' => 'nullable|url|max:255',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'status' => 'required|in:active,inactive',
            'timezone' => 'required|string|max:100',
            'logo' => 'nullable|image|mimes:jpeg,png,webp|max:2048',
            'cover_image' => 'nullable|image|mimes:jpeg,png,webp|max:4096',
            'admin_name' => $school ? 'nullable|string|max:255' : 'required|string|max:255',
            'admin_email' => $school ? 'nullable|email|unique:users,email,' . ($adminUserId ?? 'NULL') . ',id' : 'required|email|unique:users,email',
            'admin_password' => $school ? 'nullable|min:8' : 'required|min:8',
        ];
    }
}
