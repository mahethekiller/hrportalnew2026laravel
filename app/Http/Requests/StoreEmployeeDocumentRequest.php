<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'employee_id' => ['required', 'integer', 'exists:xin_employees,user_id'],
            'title' => ['required', 'string', 'max:200'],
            'document_type_id' => ['nullable', 'integer'],
            'date_of_expiry' => ['nullable', 'date'],
            'notification_email' => ['nullable', 'email', 'max:150'],
            'description' => ['nullable', 'string'],
            'document_file' => ['nullable', 'file', 'mimes:pdf,doc,docx,jpg,png,webp', 'max:5120'],
        ];
    }
}
