<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeaveApplicationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->leave_id,
            'leave_id' => $this->leave_id,
            'company_id' => $this->company_id,
            'employee_id' => $this->employee_id,
            'employee_name' => $this->employee ? ($this->employee->first_name . ' ' . $this->employee->last_name) : 'N/A',
            'leave_type_id' => $this->leave_type_id,
            'leave_type_name' => $this->leaveType->type_name ?? 'N/A',
            'from_date' => $this->from_date,
            'to_date' => $this->to_date,
            'total_days' => $this->total_days,
            'reason' => $this->reason,
            'remarks' => $this->remarks,
            'status' => (int) $this->status,
            'status_label' => $this->status_label,
            'applied_on' => $this->applied_on,
            'created_at' => $this->created_at,
        ];
    }
}
