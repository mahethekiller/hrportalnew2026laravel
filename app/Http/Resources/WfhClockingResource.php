<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WfhClockingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'userid' => $this->userid,
            'employee_name' => $this->employee ? ($this->employee->first_name . ' ' . $this->employee->last_name) : 'N/A',
            'clock_in' => $this->clock_in,
            'clock_out' => $this->clock_out,
            'formatted_clock_in' => $this->formatted_clock_in,
            'formatted_clock_out' => $this->formatted_clock_out,
            'total_hours' => $this->total_hours,
            'description' => $this->description,
            'show_status' => $this->show_status,
            'created_at' => $this->created_at,
        ];
    }
}
