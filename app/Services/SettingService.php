<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\EmailTemplate;
use App\Models\SystemSetting;
use App\Models\UserRole;
use App\Repositories\SettingRepository;
use Illuminate\Database\Eloquent\Collection;

class SettingService
{
    public function __construct(
        protected SettingRepository $settingRepository
    ) {}

    public function getSystemSetting(): SystemSetting
    {
        return $this->settingRepository->getSystemSetting();
    }

    public function updateSystemSetting(array $data): bool
    {
        $setting = $this->getSystemSetting();
        return $this->settingRepository->updateSystemSetting($setting, $data);
    }

    public function getRoles(): Collection
    {
        return $this->settingRepository->getRoles();
    }

    public function createRole(array $data): UserRole
    {
        $permissions = $data['role_resources'] ?? [];
        if (is_array($permissions)) {
            $data['role_resources'] = implode(',', $permissions);
        }
        return $this->settingRepository->createRole($data);
    }

    public function updateRole(UserRole $role, array $data): bool
    {
        $permissions = $data['role_resources'] ?? [];
        if (is_array($permissions)) {
            $data['role_resources'] = implode(',', $permissions);
        }
        return $this->settingRepository->updateRole($role, $data);
    }

    public function getEmailTemplates(): Collection
    {
        return $this->settingRepository->getEmailTemplates();
    }

    public function updateEmailTemplate(EmailTemplate $template, array $data): bool
    {
        return $this->settingRepository->updateEmailTemplate($template, $data);
    }
}
