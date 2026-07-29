<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\EmailTemplate;
use App\Models\SystemSetting;
use App\Models\UserRole;
use Illuminate\Database\Eloquent\Collection;

class SettingRepository
{
    public function getSystemSetting(): SystemSetting
    {
        $setting = SystemSetting::first();
        if (!$setting) {
            $setting = SystemSetting::create([
                'application_name' => 'Antigravity HR Portal',
                'default_currency' => 'INR',
                'default_currency_symbol' => '₹',
                'support_email' => 'support@company.com',
                'system_timezone' => 'Asia/Kolkata',
                'module_recruitment' => 1,
                'module_training' => 1,
                'module_performance' => 1,
                'module_assets' => 1,
            ]);
        }
        return $setting;
    }

    public function updateSystemSetting(SystemSetting $setting, array $data): bool
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $setting->update($data);
    }

    public function getRoles(): Collection
    {
        return UserRole::orderBy('id', 'asc')->get();
    }

    public function createRole(array $data): UserRole
    {
        $data['company_id'] = $data['company_id'] ?? 1;

        return UserRole::create($data);
    }

    public function updateRole(UserRole $role, array $data): bool
    {
        return $role->update($data);
    }

    public function getEmailTemplates(): Collection
    {
        return EmailTemplate::orderBy('template_id', 'asc')->get();
    }

    public function updateEmailTemplate(EmailTemplate $template, array $data): bool
    {
        return $template->update($data);
    }
}
