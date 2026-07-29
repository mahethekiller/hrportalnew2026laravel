<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeBankaccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'employee_id' => ['required', 'integer', 'exists:xin_employees,user_id'],
            'account_title' => ['required', 'string', 'max:150'],
            'account_number' => ['required', 'string', 'max:100'],
            'bank_name' => ['required', 'string', 'max:150'],
            'bank_code' => ['nullable', 'string', 'max:50'],
            'bank_branch' => ['nullable', 'string', 'max:150'],
            'is_primary' => ['nullable', 'boolean'],
        ];
    }
}
