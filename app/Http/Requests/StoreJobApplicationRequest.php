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
            'email' => ['required', 'email', 'max:150', 'unique:xin_job_applications,email'],
            'contact_no' => ['nullable', 'string', 'max:50'],
            'job_id' => ['nullable', 'integer'],
            'gender' => ['nullable', 'string', 'max:20'],
            'experience' => ['nullable', 'string', 'max:50'],
            'department_id' => ['nullable', 'integer'],
            'current_company' => ['nullable', 'string', 'max:200'],
            'current_location' => ['nullable', 'string', 'max:150'],
            'current_package' => ['nullable', 'string', 'max:50'],
            'expected_package' => ['nullable', 'string', 'max:50'],
            'notice_period' => ['nullable', 'string', 'max:50'],
            'change_reason' => ['nullable', 'string', 'max:500'],
            'hr_remarks' => ['nullable', 'string', 'max:500'],
            'application_status' => ['nullable', 'string', 'max:50'],
            'application_remarks' => ['nullable', 'string', 'max:500'],
            'job_resume' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:10240'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'A candidate application with this email address already exists.',
        ];
    }
}
