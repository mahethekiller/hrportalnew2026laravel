<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreJobApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'candidate_name' => ['required', 'string', 'max:200'],
            'email' => ['required', 'email', 'max:150'],
            'contact_no' => ['required', 'string', 'max:50'],
            'gender' => ['nullable', 'string', 'max:20'],
            'experience' => ['nullable', 'string', 'max:50'],
            'department_id' => ['nullable', 'integer'],
            'current_company' => ['nullable', 'string', 'max:200'],
            'current_location' => ['nullable', 'string', 'max:150'],
            'current_package' => ['nullable', 'string', 'max:50'],
            'expected_package' => ['nullable', 'string', 'max:50'],
            'notice_period' => ['nullable', 'string', 'max:50'],
            'change_reason' => ['nullable', 'string', 'max:500'],
            'application_status' => ['nullable', 'string', 'max:50'],
            'application_remarks' => ['nullable', 'string', 'max:500'],
        ];
    }
}
