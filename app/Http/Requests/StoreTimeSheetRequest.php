<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTimeSheetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'project_id' => 'required|exists:projects,id',
            'hourly_rate_id' => 'required|exists:hourly_rates,id',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'nullable',
            'hour_number' => 'nullable|numeric|min:0|max:24',
            'work_description' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'project_id.required' => 'Please select a project.',
            'project_id.exists' => 'The selected project does not exist.',
            'hourly_rate_id.required' => 'Please select a rate type.',
            'hourly_rate_id.exists' => 'The selected rate does not exist.',
            'date.required' => 'Please enter a date.',
            'start_time.required' => 'Please enter a start time.',
            'hour_number.max' => 'Hours cannot exceed 24.',
        ];
    }
}