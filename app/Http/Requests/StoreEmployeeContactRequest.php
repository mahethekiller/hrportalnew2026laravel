<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'employee_id' => ['required', 'integer', 'exists:xin_employees,user_id'],
            'contact_name' => ['required', 'string', 'max:150'],
            'relation' => ['required', 'string', 'max:50'],
            'mobile_phone' => ['required', 'string', 'max:30'],
            'work_phone' => ['nullable', 'string', 'max:30'],
            'personal_email' => ['nullable', 'email', 'max:150'],
            'is_primary' => ['nullable', 'boolean'],
            'is_dependent' => ['nullable', 'boolean'],
            'address_1' => ['nullable', 'string', 'max:255'],
        ];
    }
}
