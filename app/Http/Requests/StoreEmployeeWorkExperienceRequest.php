<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeWorkExperienceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'employee_id' => ['required', 'integer', 'exists:xin_employees,user_id'],
            'company_name' => ['required', 'string', 'max:150'],
            'post' => ['required', 'string', 'max:150'],
            'from_date' => ['required', 'date'],
            'to_date' => ['nullable', 'date'],
            'description' => ['nullable', 'string'],
        ];
    }
}
