<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDesignationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'designation_name' => ['required', 'string', 'max:150'],
            'department_id' => ['nullable', 'integer', 'exists:departments,id'],
            'company_id' => ['nullable', 'integer', 'exists:companies,id'],
            'top_designation_id' => ['nullable', 'integer'],
            'status' => ['nullable', 'integer'],
        ];
    }
}
