<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PerformanceAppraisalResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'performance_appraisal_id' => $this->performance_appraisal_id,
            'employee_id' => $this->employee_id,
            'employee_name' => $this->employee ? ($this->employee->first_name . ' ' . $this->employee->last_name) : 'N/A',
            'manager_id' => $this->manager_id,
            'manager_name' => $this->manager ? ($this->manager->first_name . ' ' . $this->manager->last_name) : 'N/A',
            'appraisal_year_month' => $this->appraisal_year_month,
            'formatted_month' => $this->formatted_month,
            'overall_rating' => $this->overall_rating,
            'rating_label' => $this->rating_label,
            'rating_badge_class' => $this->rating_badge_class,
            'competencies' => [
                'quality_of_work' => (float) $this->quality_of_work,
                'efficiency' => (float) $this->efficiency,
                'job_knowledge' => (float) $this->job_knowledge,
                'teamwork' => (float) ($this->teamwork ?? $this->team_work),
                'communication' => (float) $this->communication,
                'problem_solving' => (float) $this->problem_solving,
                'attendance' => (float) $this->attendance,
            ],
            'remarks' => $this->remarks,
            'area_strength' => $this->area_strength,
            'area_imp' => $this->area_imp,
            'future_goals' => $this->future_goals,
            'created_at' => $this->created_at,
        ];
    }
}
