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
        $data = $request->validated();
        
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

        $this->settingService->updateEmailTemplate($template, $request->only(['subject', 'message']));

        return redirect()->route('email-templates.index')
            ->with('success', 'Email notification template "' . $template->name . '" updated successfully.');
    }
}
