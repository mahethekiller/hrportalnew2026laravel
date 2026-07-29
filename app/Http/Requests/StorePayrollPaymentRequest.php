<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePayrollPaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'employee_id' => ['required', 'integer'],
            'department_id' => ['nullable', 'integer'],
            'company_id' => ['nullable', 'integer'],
            'designation_id' => ['nullable', 'integer'],
            'payment_date' => ['required', 'date'],
            'basic_salary' => ['required', 'numeric', 'min:0'],
            'house_rent_allowance' => ['nullable', 'numeric', 'min:0'],
            'medical_allowance' => ['nullable', 'numeric', 'min:0'],
            'travelling_allowance' => ['nullable', 'numeric', 'min:0'],
            'dearness_allowance' => ['nullable', 'numeric', 'min:0'],
            'provident_fund' => ['nullable', 'numeric', 'min:0'],
            'tax_deduction' => ['nullable', 'numeric', 'min:0'],
            'security_deposit' => ['nullable', 'numeric', 'min:0'],
            'advance_salary_amount' => ['nullable', 'numeric', 'min:0'],
            'payment_method' => ['required', 'string', 'max:100'],
            'comments' => ['nullable', 'string', 'max:500'],
            'status' => ['nullable', 'integer'],
        ];
    }
}
