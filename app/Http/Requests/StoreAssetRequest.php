<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAssetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'company_asset_code' => ['nullable', 'string', 'max:100'],
            'serial_number' => ['nullable', 'string', 'max:150'],
            'manufacturer' => ['nullable', 'string', 'max:150'],
            'invoice_number' => ['nullable', 'string', 'max:100'],
            'employee_id' => ['nullable', 'integer'],
            'company_id' => ['nullable', 'integer'],
            'purchase_date' => ['nullable', 'date'],
            'warranty_end_date' => ['nullable', 'date'],
            'asset_note' => ['nullable', 'string', 'max:1000'],
            'is_working' => ['nullable', 'integer'],
        ];
    }
}
