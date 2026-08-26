<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreJobInterviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'application_id' => ['required', 'integer'],
            'interviewers_id' => ['nullable'],
            'interview_mode' => ['required', 'string', 'max:50'],
            'interview_place' => ['nullable', 'string', 'max:200'],
            'interview_date' => ['required', 'date'],
            'interview_time' => ['required', 'string', 'max:50'],
            'offered_ctc' => ['nullable', 'string', 'max:100'],
            'expected_doj' => ['nullable', 'date'],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
