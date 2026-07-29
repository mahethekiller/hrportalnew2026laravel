<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTrainingSessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'employee_id' => ['required', 'integer'],
            'training_type_id' => ['required', 'integer'],
            'trainer_id' => ['required', 'integer'],
            'start_date' => ['required', 'date'],
            'finish_date' => ['required', 'date'],
            'training_cost' => ['nullable', 'numeric', 'min:0'],
            'training_status' => ['nullable', 'integer'],
            'description' => ['nullable', 'string', 'max:1000'],
            'performance' => ['nullable', 'string', 'max:255'],
        ];
    }
}
