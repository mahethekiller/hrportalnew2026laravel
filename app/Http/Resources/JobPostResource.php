<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobPostResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'job_id' => $this->job_id,
            'job_code' => $this->job_code,
            'job_title' => $this->job_title,
            'job_type' => $this->job_type,
            'job_vacancy' => (int) $this->job_vacancy,
            'job_location' => $this->job_location,
            'department' => $this->department,
            'minimum_experience' => $this->minimum_experience,
            'maximum_experience' => $this->maximum_experience,
            'priority' => $this->priority,
            'date_of_closing' => $this->date_of_closing,
            'formatted_closing_date' => $this->formatted_closing_date,
            'status' => (int) $this->status,
            'status_label' => $this->status_label,
            'status_badge_class' => $this->status_badge_class,
            'applications_count' => $this->applications ? $this->applications->count() : 0,
            'created_at' => $this->created_at,
        ];
    }
}
