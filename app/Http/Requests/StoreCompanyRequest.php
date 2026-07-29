<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'registration_no' => ['nullable', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150'],
            'contact_number' => ['nullable', 'string', 'max:50'],
            'website_url' => ['nullable', 'url', 'max:200'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'is_active' => ['nullable', 'integer'],
        ];
    }
}
