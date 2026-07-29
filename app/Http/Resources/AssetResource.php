<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssetResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'assets_id' => $this->assets_id,
            'company_asset_code' => $this->company_asset_code,
            'name' => $this->name,
            'manufacturer' => $this->manufacturer,
            'serial_number' => $this->serial_number,
            'invoice_number' => $this->invoice_number,
            'employee_id' => $this->employee_id,
            'employee_name' => $this->employee ? ($this->employee->first_name . ' ' . $this->employee->last_name) : 'Unassigned (In Stock)',
            'purchase_date' => $this->purchase_date,
            'formatted_purchase_date' => $this->formatted_purchase_date,
            'warranty_end_date' => $this->warranty_end_date,
            'formatted_warranty_date' => $this->formatted_warranty_date,
            'is_working' => (int) $this->is_working,
            'status_label' => $this->status_label,
            'status_badge_class' => $this->status_badge_class,
            'created_at' => $this->created_at,
        ];
    }
}
