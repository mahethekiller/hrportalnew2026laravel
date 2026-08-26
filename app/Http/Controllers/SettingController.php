<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRoleRequest;
use App\Http\Requests\UpdateSystemSettingRequest;
use App\Models\EmailTemplate;
use App\Models\UserRole;
use App\Services\SettingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function __construct(
        protected SettingService $settingService
    ) {}

    public function index(): View
    {
        $setting = $this->settingService->getSystemSetting();

        return view('settings.index', compact('setting'));
    }

    public function updateSystemSetting(UpdateSystemSettingRequest $request): RedirectResponse
    {
        $data = $request->only([
            'application_name',
            'support_email',
            'default_currency',
            'default_currency_symbol',
            'system_timezone',
            'footer_text',
        ]);
        
        // Checkbox values handling
        $data['enable_registration'] = $request->has('enable_registration') ? 1 : 0;
        $data['module_recruitment'] = $request->has('module_recruitment') ? 1 : 0;
        $data['module_training'] = $request->has('module_training') ? 1 : 0;
        $data['module_performance'] = $request->has('module_performance') ? 1 : 0;
        $data['module_assets'] = $request->has('module_assets') ? 1 : 0;
        $data['employee_manage_own_contact'] = $request->has('employee_manage_own_contact') ? 1 : 0;
        $data['employee_manage_own_profile'] = $request->has('employee_manage_own_profile') ? 1 : 0;
        $data['employee_manage_own_qualification'] = $request->has('employee_manage_own_qualification') ? 1 : 0;
        $data['employee_manage_own_document'] = $request->has('employee_manage_own_document') ? 1 : 0;

        $this->settingService->updateSystemSetting($data);

        return redirect()->route('system-settings.index')
            ->with('success', 'Global system settings updated successfully.');
    }

    public function roles(): View
    {
        $roles = $this->settingService->getRoles();

        return view('settings.roles', compact('roles'));
    }

    public function storeRole(StoreUserRoleRequest $request): RedirectResponse
    {
        $role = $this->settingService->createRole($request->validated());

        return redirect()->route('user-roles.index')
            ->with('success', 'User Role "' . $role->role_name . '" created successfully.');
    }

    public function updateRole(StoreUserRoleRequest $request, UserRole $role): RedirectResponse
    {
        $this->settingService->updateRole($role, $request->validated());

        return redirect()->route('user-roles.index')
            ->with('success', 'User Role "' . $role->role_name . '" updated successfully.');
    }

    public function emailTemplates(): View
    {
        $templates = $this->settingService->getEmailTemplates();

        return view('settings.email_templates', compact('templates'));
    }

    public function updateEmailTemplate(Request $request, EmailTemplate $template): RedirectResponse
    {
        $request->validate([
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
        ]);

        $data = $request->only(['subject', 'message']);
        $data['status'] = $request->has('status') ? 1 : 0;

        $this->settingService->updateEmailTemplate($template, $data);

        return redirect()->route('email-templates.index')
            ->with('success', 'Email notification template "' . $template->name . '" updated successfully.');
    }

    public function smtpProfiles(): View
    {
        $mailConfig = $this->settingService->getMailConfig();
        $smtpProfiles = $mailConfig['smtp_profiles'] ?? [];
        $companies = $this->settingService->getCompanies();

        return view('settings.smtp_profiles', compact('mailConfig', 'smtpProfiles', 'companies'));
    }

    public function saveSmtpProfile(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'host' => ['required', 'string', 'max:255'],
            'port' => ['required', 'numeric'],
            'username' => ['required', 'string', 'max:255'],
            'password' => ['nullable', 'string'],
            'from_address' => ['required', 'email'],
            'from_name' => ['required', 'string', 'max:255'],
        ]);

        $data = $request->only([
            'id', 'name', 'host', 'port', 'encryption',
            'username', 'password', 'from_address', 'from_name'
        ]);

        $data['is_active'] = $request->has('is_active');
        $data['is_default'] = $request->has('is_default');

        $this->settingService->saveSmtpProfile($data);

        return redirect()->route('smtp-profiles.index')
            ->with('success', 'SMTP Sender Profile saved successfully.');
    }

    public function deleteSmtpProfile(string $id): RedirectResponse
    {
        $this->settingService->deleteSmtpProfile($id);

        return redirect()->route('smtp-profiles.index')
            ->with('success', 'SMTP Sender Profile removed.');
    }

    public function testSmtpProfile(Request $request): RedirectResponse
    {
        $request->validate([
            'test_email' => ['required', 'email'],
            'host' => ['required', 'string'],
            'port' => ['required', 'numeric'],
            'username' => ['required', 'string'],
            'from_address' => ['required', 'email'],
        ]);

        $profileData = $request->only([
            'name', 'host', 'port', 'encryption',
            'username', 'password', 'from_address', 'from_name'
        ]);

        $result = $this->settingService->testSmtpProfile($profileData, $request->input('test_email'));

        if ($result['success']) {
            return redirect()->back()->with('success', $result['message']);
        }

        return redirect()->back()->with('error', $result['message']);
    }

    public function updateMailRouting(Request $request): RedirectResponse
    {
        $config = $this->settingService->getMailConfig();

        $config['global_enabled'] = $request->has('global_enabled');

        // Module Switches
        $config['module_switches'] = [
            'leave' => $request->has('module_switch_leave'),
            'ticket' => $request->has('module_switch_ticket'),
            'announcement' => $request->has('module_switch_announcement'),
            'recruitment' => $request->has('module_switch_recruitment'),
            'payroll' => $request->has('module_switch_payroll'),
            'onboarding' => $request->has('module_switch_onboarding'),
            'resignation' => $request->has('module_switch_resignation'),
        ];

        // Module Profile Mappings
        $config['module_profile_mappings'] = [
            'leave' => $request->input('profile_leave', 'default'),
            'ticket' => $request->input('profile_ticket', 'default'),
            'announcement' => $request->input('profile_announcement', 'default'),
            'recruitment' => $request->input('profile_recruitment', 'default'),
            'payroll' => $request->input('profile_payroll', 'default'),
            'onboarding' => $request->input('profile_onboarding', 'default'),
            'resignation' => $request->input('profile_resignation', 'default'),
        ];

        // Global Extra CCs
        $config['global_extra_ccs'] = [
            'leave' => $request->input('extra_cc_leave', ''),
            'ticket' => $request->input('extra_cc_ticket', ''),
            'announcement' => $request->input('extra_cc_announcement', ''),
            'recruitment' => $request->input('extra_cc_recruitment', ''),
            'payroll' => $request->input('extra_cc_payroll', ''),
            'onboarding' => $request->input('extra_cc_onboarding', ''),
            'resignation' => $request->input('extra_cc_resignation', ''),
        ];

        $this->settingService->saveMailConfig($config);

        return redirect()->route('smtp-profiles.index')
            ->with('success', 'Global mail routing and module settings updated successfully.');
    }

    public function updateCompanyEmailSettings(Request $request): RedirectResponse
    {
        $companyId = (int) $request->input('company_id');
        if (!$companyId) {
            return redirect()->back()->with('error', 'Invalid company selected.');
        }

        $config = $this->settingService->getMailConfig();

        if (!isset($config['company_extra_ccs'])) {
            $config['company_extra_ccs'] = [];
        }

        $config['company_extra_ccs'][$companyId] = [
            'leave' => $request->input('company_extra_cc_leave', ''),
            'ticket' => $request->input('company_extra_cc_ticket', ''),
            'announcement' => $request->input('company_extra_cc_announcement', ''),
            'recruitment' => $request->input('company_extra_cc_recruitment', ''),
            'payroll' => $request->input('company_extra_cc_payroll', ''),
            'onboarding' => $request->input('company_extra_cc_onboarding', ''),
            'resignation' => $request->input('company_extra_cc_resignation', ''),
        ];

        $this->settingService->saveMailConfig($config);

        return redirect()->route('smtp-profiles.index')
            ->with('success', 'Company-specific extra CC email routing updated successfully.');
    }

    public function emailLogs(): View
    {
        $logs = $this->settingService->getEmailLogs();

        return view('settings.email_logs', compact('logs'));
    }

    public function themeSettings(): View
    {
        $themeConfig = $this->settingService->getThemeConfig();
        $colorProfiles = $this->settingService->getColorProfiles();
        $fontFamilies = $this->settingService->getFontFamilies();
        $seasonalAccents = $this->settingService->getSeasonalAccents();

        return view('settings.theme', compact('themeConfig', 'colorProfiles', 'fontFamilies', 'seasonalAccents'));
    }

    public function updateThemeSettings(Request $request): RedirectResponse
    {
        $request->validate([
            'theme_color_profile' => ['required', 'string'],
            'font_family' => ['required', 'string'],
            'theme_mode' => ['required', 'string'],
            'sidebar_style' => ['required', 'string'],
            'seasonal_accent' => ['required', 'string'],
        ]);

        $config = [
            'theme_color_profile' => $request->input('theme_color_profile', 'fern'),
            'custom_primary_hex' => $request->input('custom_primary_hex', '#2F7A63'),
            'font_family' => $request->input('font_family', 'inter'),
            'theme_mode' => $request->input('theme_mode', 'light'),
            'sidebar_style' => $request->input('sidebar_style', 'default'),
            'seasonal_accent' => $request->input('seasonal_accent', 'off'),
        ];

        $this->settingService->saveThemeConfig($config);

        return redirect()->route('settings.theme.index')
            ->with('success', 'Global portal theme & branding configurations updated successfully.');
    }

    public function updateUserThemePreference(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'profile' => ['nullable', 'string'],
            'customHex' => ['nullable', 'string'],
            'mode' => ['nullable', 'string'],
            'font' => ['nullable', 'string'],
            'sidebar' => ['nullable', 'string'],
        ]);

        // Success response for AJAX client persistence
        return response()->json([
            'success' => true,
            'message' => 'Theme preferences saved successfully.',
        ]);
    }
}
