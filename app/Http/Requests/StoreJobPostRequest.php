<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreJobPostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'job_title' => ['required', 'string', 'max:255'],
            'job_code' => ['nullable', 'string', 'max:100'],
            'job_type' => ['nullable', 'string', 'max:100'],
            'job_vacancy' => ['required', 'integer', 'min:1'],
            'job_location' => ['nullable', 'string', 'max:255'],
            'designation_id' => ['nullable', 'integer'],
            'department' => ['nullable', 'string', 'max:150'],
            'minimum_experience' => ['nullable', 'string', 'max:50'],
            'maximum_experience' => ['nullable', 'string', 'max:50'],
            'priority' => ['nullable', 'string', 'max:50'],
            'date_of_closing' => ['nullable', 'date'],
            'short_description' => ['nullable', 'string', 'max:1000'],
            'status' => ['nullable', 'integer'],
        ];
    }
}
