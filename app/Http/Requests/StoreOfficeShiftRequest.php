<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOfficeShiftRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'shift_name' => ['required', 'string', 'max:150'],
            'company_id' => ['nullable', 'integer'],
            'monday_in_time' => ['nullable', 'string'],
            'monday_out_time' => ['nullable', 'string'],
            'tuesday_in_time' => ['nullable', 'string'],
            'tuesday_out_time' => ['nullable', 'string'],
            'wednesday_in_time' => ['nullable', 'string'],
            'wednesday_out_time' => ['nullable', 'string'],
            'thursday_in_time' => ['nullable', 'string'],
            'thursday_out_time' => ['nullable', 'string'],
            'friday_in_time' => ['nullable', 'string'],
            'friday_out_time' => ['nullable', 'string'],
            'saturday_in_time' => ['nullable', 'string'],
            'saturday_out_time' => ['nullable', 'string'],
            'sunday_in_time' => ['nullable', 'string'],
            'sunday_out_time' => ['nullable', 'string'],
        ];
    }
}
