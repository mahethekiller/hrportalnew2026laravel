<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\EmailTemplate;
use App\Models\SystemSetting;
use App\Models\UserRole;
use App\Repositories\SettingRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class SettingService
{
    public function __construct(
        protected SettingRepository $settingRepository,
        protected MailService $mailService,
        protected ThemeService $themeService
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

    public function getMailConfig(): array
    {
        return $this->mailService->getMailConfig();
    }

    public function saveMailConfig(array $config): bool
    {
        return $this->mailService->saveMailConfig($config);
    }

    public function getSmtpProfiles(): array
    {
        return $this->mailService->getSmtpProfiles();
    }

    public function saveSmtpProfile(array $data): array
    {
        return $this->mailService->saveSmtpProfile($data);
    }

    public function deleteSmtpProfile(string $id): bool
    {
        return $this->mailService->deleteSmtpProfile($id);
    }

    public function testSmtpProfile(array $profileData, string $recipientEmail): array
    {
        return $this->mailService->testSmtpConnection($profileData, $recipientEmail);
    }

    public function getEmailLogs(): LengthAwarePaginator
    {
        return $this->settingRepository->getEmailLogs();
    }

    public function getCompanies(): Collection
    {
        return $this->settingRepository->getCompanies();
    }

    public function getThemeConfig(): array
    {
        return $this->themeService->getThemeConfig();
    }

    public function saveThemeConfig(array $config): bool
    {
        return $this->themeService->saveThemeConfig($config);
    }

    public function getColorProfiles(): array
    {
        return $this->themeService->getColorProfiles();
    }

    public function getFontFamilies(): array
    {
        return $this->themeService->getFontFamilies();
    }

    public function getSeasonalAccents(): array
    {
        return $this->themeService->getSeasonalAccents();
    }
}
