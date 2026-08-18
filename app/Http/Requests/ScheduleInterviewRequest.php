<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScheduleInterviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', \App\Models\Interview::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'application_id' => 'required|exists:applications,id',
            'scheduled_date' => 'required|date|after_or_equal:today',
            'scheduled_time' => 'required',
            'location_type' => 'required|in:in_person,online',
            'location_address_or_link' => 'required|string|max:500',
            'panel_members' => 'nullable|string|max:500',
            'remarks' => 'nullable|string|max:1000',
        ];
    }
}
