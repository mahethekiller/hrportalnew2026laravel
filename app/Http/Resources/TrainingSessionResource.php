<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TrainingSessionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'training_id' => $this->training_id,
            'employee_id' => $this->employee_id,
            'employee_name' => $this->employee ? ($this->employee->first_name . ' ' . $this->employee->last_name) : 'Employee',
            'course_type' => $this->trainingType ? $this->trainingType->type : 'General Training',
            'instructor_name' => $this->trainer ? $this->trainer->full_name : 'External Trainer',
            'start_date' => $this->start_date,
            'finish_date' => $this->finish_date,
            'training_cost' => (float) $this->training_cost,
            'formatted_cost' => $this->formatted_cost,
            'training_status' => (int) $this->training_status,
            'status_label' => $this->status_label,
            'status_badge_class' => $this->status_badge_class,
            'performance' => $this->performance,
            'description' => $this->description,
            'created_at' => $this->created_at,
        ];
    }
}
