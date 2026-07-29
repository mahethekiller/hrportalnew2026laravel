<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePerformanceAppraisalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'employee_id' => ['required', 'integer'],
            'manager_id' => ['nullable', 'integer'],
            'company_id' => ['nullable', 'integer'],
            'appraisal_year_month' => ['required', 'string', 'max:20'],
            'quality_of_work' => ['required', 'numeric', 'min:1', 'max:5'],
            'efficiency' => ['required', 'numeric', 'min:1', 'max:5'],
            'job_knowledge' => ['required', 'numeric', 'min:1', 'max:5'],
            'teamwork' => ['required', 'numeric', 'min:1', 'max:5'],
            'communication' => ['required', 'numeric', 'min:1', 'max:5'],
            'problem_solving' => ['required', 'numeric', 'min:1', 'max:5'],
            'attendance' => ['nullable', 'numeric', 'min:1', 'max:5'],
            'integrity' => ['nullable', 'numeric', 'min:1', 'max:5'],
            'professionalism' => ['nullable', 'numeric', 'min:1', 'max:5'],
            'ability_to_meet_deadline' => ['nullable', 'numeric', 'min:1', 'max:5'],
            'remarks' => ['nullable', 'string', 'max:1000'],
            'area_strength' => ['nullable', 'string', 'max:1000'],
            'area_imp' => ['nullable', 'string', 'max:1000'],
            'future_goals' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
