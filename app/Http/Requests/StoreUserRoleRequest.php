<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'role_name' => ['required', 'string', 'max:100'],
            'role_access' => ['nullable', 'string', 'max:50'],
            'role_resources' => ['nullable', 'array'],
        ];
    }
}
