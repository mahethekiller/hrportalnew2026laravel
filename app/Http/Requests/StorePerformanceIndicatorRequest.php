<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePerformanceIndicatorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'designation_id' => ['required', 'integer'],
            'company_id' => ['nullable', 'integer'],
            'quality_of_work' => ['required', 'numeric', 'min:1', 'max:5'],
            'efficiency' => ['required', 'numeric', 'min:1', 'max:5'],
            'integrity' => ['nullable', 'numeric', 'min:1', 'max:5'],
            'professionalism' => ['nullable', 'numeric', 'min:1', 'max:5'],
            'team_work' => ['nullable', 'numeric', 'min:1', 'max:5'],
            'attendance' => ['nullable', 'numeric', 'min:1', 'max:5'],
            'ability_to_meet_deadline' => ['nullable', 'numeric', 'min:1', 'max:5'],
        ];
    }
}
