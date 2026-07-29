<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeQualificationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'employee_id' => ['required', 'integer', 'exists:xin_employees,user_id'],
            'name' => ['required', 'string', 'max:150'],
            'specialization' => ['nullable', 'string', 'max:150'],
            'from_year' => ['nullable', 'string', 'max:10'],
            'to_year' => ['nullable', 'string', 'max:10'],
            'description' => ['nullable', 'string'],
        ];
    }
}
