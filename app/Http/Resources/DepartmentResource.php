<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DepartmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'department_name' => $this->department_name,
            'company' => [
                'id' => $this->company_id,
                'name' => $this->company->name ?? 'Antigravity Corp',
            ],
            'head' => [
                'id' => $this->employee_id,
                'name' => $this->employee ? trim($this->employee->first_name . ' ' . $this->employee->last_name) : null,
            ],
            'status' => (bool) ($this->status ?? true),
        ];
    }
}
