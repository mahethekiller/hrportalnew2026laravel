<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'card_no' => $this->card_no,
            'employee_name' => $this->employee ? ($this->employee->first_name . ' ' . $this->employee->last_name) : 'N/A',
            'punch_date' => $this->punch_date,
            'check_in_time' => $this->formatted_check_in,
            'check_out_time' => $this->formatted_check_out,
            'show_status' => $this->show_status,
            'status_badge_class' => $this->status_badge_class,
        ];
    }
}
