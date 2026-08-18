<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDepartmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', \App\Models\Department::class) ?? false;
    }

    public function rules(): array
    {
        $schoolId = $this->input('school_id') ?? $this->user()?->school_id;
        $deptId = $this->route('department')?->id;

        return [
            'school_id' => 'required|exists:schools,id',
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:500',
            'is_active' => 'nullable|boolean',
        ];
    }
}
