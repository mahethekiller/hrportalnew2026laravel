<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLeaveTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type_name' => ['required', 'string', 'max:150'],
            'days_per_year' => ['required', 'integer', 'min:1', 'max:365'],
            'status' => ['nullable', 'integer'],
        ];
    }
}
