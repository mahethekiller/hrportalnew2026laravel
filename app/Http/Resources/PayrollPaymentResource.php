<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PayrollPaymentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'make_payment_id' => $this->make_payment_id,
            'employee_id' => $this->employee_id,
            'employee_name' => $this->employee ? ($this->employee->first_name . ' ' . $this->employee->last_name) : 'N/A',
            'employee_code' => $this->employee->employee_id ?? 'N/A',
            'payment_date' => $this->payment_date,
            'formatted_payment_date' => $this->formatted_payment_date,
            'basic_salary' => (float) $this->basic_salary,
            'gross_salary' => (float) $this->gross_salary,
            'total_allowances' => (float) $this->total_allowances,
            'total_deductions' => (float) $this->total_deductions,
            'net_salary' => (float) $this->net_salary,
            'payment_method' => $this->payment_method,
            'status' => $this->status,
            'status_label' => $this->status_label,
            'status_badge_class' => $this->status_badge_class,
        ];
    }
}
