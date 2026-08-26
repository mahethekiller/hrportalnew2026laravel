<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSystemSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'application_name' => ['required', 'string', 'max:255'],
            'support_email' => ['nullable', 'email', 'max:150'],
            'default_currency' => ['nullable', 'string', 'max:20'],
            'default_currency_symbol' => ['nullable', 'string', 'max:10'],
            'system_timezone' => ['nullable', 'string', 'max:100'],
            'enable_registration' => ['nullable', 'integer'],
            'module_recruitment' => ['nullable', 'integer'],
            'module_training' => ['nullable', 'integer'],
            'module_performance' => ['nullable', 'integer'],
            'module_assets' => ['nullable', 'integer'],
            'employee_manage_own_contact' => ['nullable', 'integer'],
            'employee_manage_own_profile' => ['nullable', 'integer'],
            'employee_manage_own_qualification' => ['nullable', 'integer'],
            'employee_manage_own_document' => ['nullable', 'integer'],
            'footer_text' => ['nullable', 'string', 'max:500'],
        ];
    }
}
