<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SystemSettingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'setting_id' => $this->setting_id,
            'application_name' => $this->application_name,
            'support_email' => $this->support_email,
            'default_currency' => $this->default_currency,
            'default_currency_symbol' => $this->default_currency_symbol,
            'system_timezone' => $this->system_timezone,
            'enable_registration' => (bool) $this->enable_registration,
            'modules' => [
                'recruitment' => (bool) $this->module_recruitment,
                'training' => (bool) $this->module_training,
                'performance' => (bool) $this->module_performance,
                'assets' => (bool) $this->module_assets,
            ],
            'footer_text' => $this->footer_text,
            'updated_at' => $this->updated_at,
        ];
    }
}
