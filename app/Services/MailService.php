<?php

declare(strict_types=1);

namespace App\Services;

use App\Mail\GenericPortalMail;
use App\Models\EmailHistory;
use App\Models\EmailTemplate;
use App\Models\SystemSetting;
use App\Traits\HasCleanContent;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class MailService
{
    use HasCleanContent;

    protected string $settingsFilePath;

    public function __construct()
    {
        $this->settingsFilePath = storage_path('app/settings/mail_system_config.json');
    }

    /**
     * Load full mail configuration array.
     */
    public function getMailConfig(): array
    {
        if (!File::exists($this->settingsFilePath)) {
            $defaultConfig = [
                'global_enabled' => true,
                'smtp_profiles' => [
                    'default' => [
                        'id' => 'default',
                        'name' => 'Default System Mailer',
                        'host' => env('MAIL_HOST', 'smtp.mailtrap.io'),
                        'port' => (int) env('MAIL_PORT', 2525),
                        'encryption' => env('MAIL_ENCRYPTION', 'tls'),
                        'username' => env('MAIL_USERNAME', ''),
                        'password' => env('MAIL_PASSWORD', ''),
                        'from_address' => env('MAIL_FROM_ADDRESS', 'noreply@company.com'),
                        'from_name' => env('MAIL_FROM_NAME', 'Antigravity HR Portal'),
                        'is_default' => true,
                        'is_active' => true,
                    ]
                ],
                'module_switches' => [
                    'leave' => true,
                    'ticket' => true,
                    'announcement' => true,
                    'recruitment' => true,
                    'payroll' => true,
                    'onboarding' => true,
                    'resignation' => true,
                ],
                'module_profile_mappings' => [
                    'leave' => 'default',
                    'ticket' => 'default',
                    'announcement' => 'default',
                    'recruitment' => 'default',
                    'payroll' => 'default',
                    'onboarding' => 'default',
                    'resignation' => 'default',
                ],
                'global_extra_ccs' => [
                    'leave' => '',
                    'ticket' => '',
                    'announcement' => '',
                    'recruitment' => '',
                    'payroll' => '',
                    'onboarding' => '',
                    'resignation' => '',
                ],
                'company_extra_ccs' => [],
            ];

            $this->saveMailConfig($defaultConfig);
            return $defaultConfig;
        }

        $content = File::get($this->settingsFilePath);
        return json_decode($content, true) ?: [];
    }

    /**
     * Save full mail configuration array.
     */
    public function saveMailConfig(array $config): bool
    {
        $directory = dirname($this->settingsFilePath);
        if (!File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        return File::put($this->settingsFilePath, json_encode($config, JSON_PRETTY_PRINT)) !== false;
    }

    /**
     * Get list of SMTP Profiles.
     */
    public function getSmtpProfiles(): array
    {
        $config = $this->getMailConfig();
        return $config['smtp_profiles'] ?? [];
    }

    /**
     * Save or update an SMTP Profile.
     */
    public function saveSmtpProfile(array $profileData): array
    {
        $config = $this->getMailConfig();
        $id = $profileData['id'] ?? 'smtp_' . time();
        $profileData['id'] = $id;
        $profileData['port'] = (int) ($profileData['port'] ?? 587);
        $profileData['is_active'] = !empty($profileData['is_active']);
        $profileData['is_default'] = !empty($profileData['is_default']);

        if (!isset($config['smtp_profiles'])) {
            $config['smtp_profiles'] = [];
        }

        // If setting as default, clear default flag on others
        if ($profileData['is_default']) {
            foreach ($config['smtp_profiles'] as &$existing) {
                $existing['is_default'] = false;
            }
        }

        $config['smtp_profiles'][$id] = $profileData;
        $this->saveMailConfig($config);

        return $profileData;
    }

    /**
     * Delete an SMTP Profile.
     */
    public function deleteSmtpProfile(string $id): bool
    {
        $config = $this->getMailConfig();
        if (isset($config['smtp_profiles'][$id])) {
            unset($config['smtp_profiles'][$id]);
            $this->saveMailConfig($config);
            return true;
        }
        return false;
    }

    /**
     * Test SMTP connection with recipient test email.
     */
    public function testSmtpConnection(array $profileData, string $recipientEmail): array
    {
        try {
            $this->applyDynamicSmtpConfig($profileData);

            $mailable = new GenericPortalMail(
                mailSubject: 'SMTP Diagnostic Test Email',
                htmlContent: '<p>Hello,</p><p>This is a test notification verifying that your SMTP Profile <strong>' . e($profileData['name'] ?? 'SMTP Test') . '</strong> is working perfectly!</p>',
                fromEmail: $profileData['from_address'] ?? null,
                fromName: $profileData['from_name'] ?? null
            );

            Mail::to($recipientEmail)->send($mailable);

            return [
                'success' => true,
                'message' => 'Test email successfully dispatched to ' . $recipientEmail,
            ];
        } catch (Throwable $e) {
            Log::error('SMTP Test Failed: ' . $e->getMessage(), ['exception' => $e]);
            return [
                'success' => false,
                'message' => 'SMTP Diagnostic Failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Dynamically configure Laravel's mailer settings.
     */
    protected function applyDynamicSmtpConfig(array $profileData): void
    {
        Config::set('mail.default', 'smtp');
        Config::set('mail.mailers.smtp.transport', 'smtp');
        Config::set('mail.mailers.smtp.host', $profileData['host'] ?? 'smtp.mailtrap.io');
        Config::set('mail.mailers.smtp.port', (int) ($profileData['port'] ?? 587));
        Config::set('mail.mailers.smtp.encryption', strtolower((string) ($profileData['encryption'] ?? 'tls')) === 'none' ? null : ($profileData['encryption'] ?? 'tls'));
        Config::set('mail.mailers.smtp.username', $profileData['username'] ?? '');
        Config::set('mail.mailers.smtp.password', $profileData['password'] ?? '');
        Config::set('mail.from.address', $profileData['from_address'] ?? 'noreply@company.com');
        Config::set('mail.from.name', $profileData['from_name'] ?? 'Antigravity HR Portal');
    }

    /**
     * Resolve active SMTP profile for module.
     */
    public function resolveSmtpProfileForModule(string $moduleKey): array
    {
        $config = $this->getMailConfig();
        $profiles = $config['smtp_profiles'] ?? [];
        $mappings = $config['module_profile_mappings'] ?? [];

        $profileId = $mappings[$moduleKey] ?? 'default';

        if (isset($profiles[$profileId]) && !empty($profiles[$profileId]['is_active'])) {
            return $profiles[$profileId];
        }

        // Fallback to default active profile
        foreach ($profiles as $profile) {
            if (!empty($profile['is_default']) && !empty($profile['is_active'])) {
                return $profile;
            }
        }

        // Fallback to first active profile
        foreach ($profiles as $profile) {
            if (!empty($profile['is_active'])) {
                return $profile;
            }
        }

        return [
            'host' => env('MAIL_HOST', 'smtp.mailtrap.io'),
            'port' => (int) env('MAIL_PORT', 2525),
            'encryption' => env('MAIL_ENCRYPTION', 'tls'),
            'username' => env('MAIL_USERNAME', ''),
            'password' => env('MAIL_PASSWORD', ''),
            'from_address' => env('MAIL_FROM_ADDRESS', 'noreply@company.com'),
            'from_name' => env('MAIL_FROM_NAME', 'Antigravity HR Portal'),
        ];
    }

    /**
     * Resolve Extra CC email addresses for module and company.
     */
    public function resolveExtraCcEmails(string $moduleKey, ?int $companyId = null): array
    {
        $config = $this->getMailConfig();
        $emails = [];

        // Global Module Extra CCs
        if (!empty($config['global_extra_ccs'][$moduleKey])) {
            $globalList = array_map('trim', explode(',', $config['global_extra_ccs'][$moduleKey]));
            $emails = array_merge($emails, $globalList);
        }

        // Company Specific Extra CCs
        if ($companyId && !empty($config['company_extra_ccs'][$companyId][$moduleKey])) {
            $companyList = array_map('trim', explode(',', $config['company_extra_ccs'][$companyId][$moduleKey]));
            $emails = array_merge($emails, $companyList);
        }

        // Filter valid unique emails
        return array_unique(array_filter($emails, fn($e) => filter_var($e, FILTER_VALIDATE_EMAIL)));
    }

    /**
     * Send dynamic event email using EmailTemplate.
     */
    public function sendTemplateEmail(
        string $templateCode,
        string|array $toEmails,
        array $replacements = [],
        string $moduleKey = 'leave',
        ?int $companyId = null,
        ?string $actionUrl = null,
        ?string $actionText = null,
        ?int $userId = null
    ): bool {
        $config = $this->getMailConfig();

        // 1. Check Global Switch
        if (empty($config['global_enabled'])) {
            Log::info("Email dispatch skipped: Global mail system is disabled.");
            return false;
        }

        // 2. Check Module Switch
        if (isset($config['module_switches'][$moduleKey]) && empty($config['module_switches'][$moduleKey])) {
            Log::info("Email dispatch skipped: Module '{$moduleKey}' email notifications are disabled.");
            return false;
        }

        // 3. Check Email Template
        $template = EmailTemplate::where('template_code', $templateCode)->first();
        if (!$template) {
            // Fallback lookup by template_id if integer passed
            if (is_numeric($templateCode)) {
                $template = EmailTemplate::find((int) $templateCode);
            }
        }

        if ($template && isset($template->status) && (int)$template->status === 0) {
            Log::info("Email dispatch skipped: Template '{$templateCode}' is disabled (status = 0).");
            return false;
        }

        $rawSubject = $template ? $template->subject : ($replacements['{subject}'] ?? 'Portal Notification');
        $rawMessage = $template ? $template->message : ($replacements['{message}'] ?? '');

        // Standard Replacements
        $replacements['{site_name}'] = config('app.name', 'Antigravity HR Portal');
        $replacements['{site_url}'] = url('/');

        $parsedSubject = $this->parsePlaceholders($rawSubject, $replacements);
        $parsedMessage = $this->parsePlaceholders($rawMessage, $replacements);

        // Sanitize Message
        $cleanMessage = self::sanitizeContent($parsedMessage, false);

        // Resolve Profile & Apply
        $smtpProfile = $this->resolveSmtpProfileForModule($moduleKey);
        $this->applyDynamicSmtpConfig($smtpProfile);

        // Resolve Recipients & Extra CCs
        $primaryRecipients = is_array($toEmails) ? $toEmails : array_map('trim', explode(',', $toEmails));
        $primaryRecipients = array_unique(array_filter($primaryRecipients, fn($e) => filter_var($e, FILTER_VALIDATE_EMAIL)));

        if (empty($primaryRecipients)) {
            Log::warning("Email dispatch skipped: No valid primary recipient email provided.");
            return false;
        }

        $extraCcs = $this->resolveExtraCcEmails($moduleKey, $companyId);

        try {
            $mailable = new GenericPortalMail(
                mailSubject: $parsedSubject,
                htmlContent: $cleanMessage,
                actionUrl: $actionUrl,
                actionText: $actionText,
                fromEmail: $smtpProfile['from_address'] ?? null,
                fromName: $smtpProfile['from_name'] ?? null
            );

            $mailCall = Mail::to($primaryRecipients);
            if (!empty($extraCcs)) {
                $mailCall->cc($extraCcs);
            }
            $mailCall->send($mailable);

            // Log Delivery in xin_email_history
            $allTo = implode(', ', array_merge($primaryRecipients, $extraCcs));
            EmailHistory::create([
                'subject' => $parsedSubject,
                'message' => $cleanMessage,
                'from_email' => $smtpProfile['from_address'] ?? 'noreply@company.com',
                'to_emails' => $allTo,
                'sent_date' => date('Y-m-d H:i:s'),
                'mail_type' => $moduleKey,
                'mail_type_id' => $template ? $template->template_id : null,
                'user_id' => $userId,
                'show_status' => 1,
            ]);

            return true;
        } catch (Throwable $e) {
            Log::error("Failed to send template email [{$templateCode}]: " . $e->getMessage(), ['exception' => $e]);
            return false;
        }
    }

    /**
     * Replace template placeholders `{var_name}` with replacement values.
     */
    protected function parsePlaceholders(string $content, array $replacements): string
    {
        foreach ($replacements as $key => $val) {
            if (is_scalar($val) || (is_object($val) && method_exists($val, '__toString'))) {
                $content = str_replace($key, (string) $val, $content);
                // Also handle variations like {var_name} or {var name}
                $cleanKey = str_replace(['{', '}'], '', $key);
                $content = str_replace(['{var ' . $cleanKey . '}', '{' . $cleanKey . '}'], (string) $val, $content);
            }
        }
        return $content;
    }

    /**
     * Dispatch single-threaded resignation notification emails to Employee, Manager, and Admin Extra CCs.
     */
    public function sendResignationNotification(
        string $event,
        \App\Models\EmployeeResignation $resignation,
        string $subjectText,
        string $bodyHtml,
        ?string $actionUrl = null
    ): bool {
        try {
            $companyId = (int) ($resignation->company_id ?? 0);
            $this->applyModuleSmtpProfile('resignation');

            $employee = $resignation->employee;
            if (!$employee) {
                return false;
            }

            $primaryRecipients = [];
            if (!empty($employee->email)) {
                $primaryRecipients[] = $employee->email;
            }

            // Reporting Manager
            if ($resignation->manager && !empty($resignation->manager->email)) {
                $primaryRecipients[] = $resignation->manager->email;
            } elseif ($employee->manager && !empty($employee->manager->email)) {
                $primaryRecipients[] = $employee->manager->email;
            }

            // Department Clearance Assigned Persons (if present)
            if ($resignation->itPerson && !empty($resignation->itPerson->email)) {
                $primaryRecipients[] = $resignation->itPerson->email;
            }
            if ($resignation->accountPerson && !empty($resignation->accountPerson->email)) {
                $primaryRecipients[] = $resignation->accountPerson->email;
            }
            if ($resignation->hrPerson && !empty($resignation->hrPerson->email)) {
                $primaryRecipients[] = $resignation->hrPerson->email;
            }

            // Admin Extra CC Recipients (Kamal Sir, Priyanka, etc.)
            $extraCcs = $this->getModuleExtraCcs('resignation', $companyId);
            $allTo = array_values(array_unique(array_filter(array_merge($primaryRecipients, $extraCcs))));

            if (empty($allTo)) {
                return false;
            }

            $threadSubject = sprintf('[i2u2 Portal] Resignation Request - %s %s (%s)', 
                $employee->first_name ?? $employee->name ?? 'Employee',
                $employee->last_name ?? '',
                $employee->employee_id ?? $employee->id ?? 'EMP'
            );

            // Single Thread Headers
            $threadId = 'resignation-' . $resignation->resignation_id . '@i2u2portal.local';
            $messageIdHeader = "<{$event}-" . time() . "-{$threadId}>";
            $inReplyToHeader = "<init-{$threadId}>";
            $referencesHeader = "<init-{$threadId}>";

            $mailable = new \App\Mail\ResignationMail(
                $event,
                $resignation,
                $threadSubject,
                $bodyHtml,
                $actionUrl,
                $messageIdHeader,
                $event === 'submitted' ? null : $inReplyToHeader,
                $event === 'submitted' ? null : $referencesHeader
            );

            Mail::to($allTo)->send($mailable);

            // Log Email History
            EmailHistory::create([
                'subject' => $threadSubject,
                'message' => $bodyHtml,
                'from_email' => Config::get('mail.from.address', 'noreply@company.com'),
                'to_emails' => implode(', ', $allTo),
                'sent_date' => date('Y-m-d H:i:s'),
                'mail_type' => 'resignation',
                'mail_type_id' => $resignation->resignation_id,
                'user_id' => $employee->user_id ?? $employee->id ?? 1,
                'show_status' => 1,
            ]);

            return true;
        } catch (Throwable $e) {
            Log::error("Failed to send resignation notification [{$event}]: " . $e->getMessage(), ['exception' => $e]);
            return false;
        }
    }
}
