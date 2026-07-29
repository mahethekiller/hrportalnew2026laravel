<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSalaryIncrementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'employee_id' => ['required', 'integer'],
            'old_salary' => ['required', 'numeric', 'min:0'],
            'new_salary' => ['required', 'numeric', 'min:0'],
            'appraisal_date' => ['required', 'date'],
            'added_by' => ['nullable', 'string', 'max:100'],
        ];
    }
}
