<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobApplicationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'application_id' => $this->application_id,
            'candidate_name' => $this->candidate_name,
            'email' => $this->email,
            'contact_no' => $this->contact_no,
            'experience' => $this->experience,
            'current_company' => $this->current_company,
            'current_location' => $this->current_location,
            'current_package' => $this->current_package,
            'expected_package' => $this->expected_package,
            'notice_period' => $this->notice_period,
            'application_status' => $this->application_status,
            'status_label' => $this->status_label,
            'status_badge_class' => $this->status_badge_class,
            'created_at' => $this->created_at,
            'formatted_date' => $this->formatted_date,
        ];
    }
}
