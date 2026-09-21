<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ $subject ?? 'Notification' }} - {{ $appName ?? 'I2U2 Portal' }}</title>
    <style>
        /* Base Reset & Typography */
        body {
            margin: 0;
            padding: 0;
            background-color: #f1f5f9;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            color: #1e293b;
            -webkit-font-smoothing: antialiased;
            -ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;
        }
        table {
            border-collapse: collapse;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }
        img {
            border: 0;
            height: auto;
            line-height: 100%;
            outline: none;
            text-decoration: none;
        }
        .email-wrapper {
            width: 100%;
            background-color: #f1f5f9;
            padding: 40px 15px;
            box-sizing: border-box;
        }
        .email-container {
            max-width: 620px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(15, 23, 42, 0.08);
            border: 1px solid #e2e8f0;
        }
        /* Header */
        .email-header {
            background-color: #ffffff;
            padding: 24px 32px;
            border-bottom: 1px solid #f1f5f9;
        }
        .brand-accent-line {
            height: 3px;
            background: linear-gradient(90deg, #1d4ed8 0%, #3b82f6 50%, #06b6d4 100%);
        }
        /* Body */
        .email-body {
            padding: 36px 32px;
            font-size: 15px;
            line-height: 1.65;
            color: #334155;
        }
        .subject-badge {
            display: inline-block;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #1d4ed8;
            background-color: #eff6ff;
            border: 1px solid #dbeafe;
            padding: 4px 10px;
            border-radius: 4px;
            margin-bottom: 10px;
        }
        .subject-heading {
            margin: 0 0 20px 0;
            font-size: 20px;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.35;
        }
        .content-card {
            background-color: #ffffff;
            color: #334155;
        }
        .content-card p {
            margin-top: 0;
            margin-bottom: 16px;
        }
        .content-card table {
            width: 100% !important;
            margin: 16px 0;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            overflow: hidden;
        }
        .content-card th {
            background-color: #f8fafc;
            color: #475569;
            font-weight: 600;
            font-size: 13px;
            padding: 10px 14px;
            border-bottom: 1px solid #e2e8f0;
        }
        .content-card td {
            padding: 10px 14px;
            font-size: 14px;
            border-bottom: 1px solid #f1f5f9;
        }
        /* Action Button */
        .btn-container {
            text-align: center;
            margin: 32px 0 16px 0;
        }
        .btn-action {
            display: inline-block;
            background-color: #1d4ed8;
            color: #ffffff !important;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            padding: 13px 30px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(29, 78, 216, 0.25);
            transition: all 0.2s ease;
        }
        /* Footer */
        .email-footer {
            background-color: #f8fafc;
            padding: 28px 32px;
            text-align: center;
            font-size: 12px;
            color: #64748b;
            border-top: 1px solid #e2e8f0;
        }
        .email-footer p {
            margin: 4px 0;
            line-height: 1.5;
        }
        .footer-brand {
            font-weight: 700;
            color: #1e293b;
            font-size: 13px;
            letter-spacing: 0.2px;
        }
        .footer-support-link {
            color: #1d4ed8;
            text-decoration: none;
            font-weight: 500;
        }
        @media only screen and (max-width: 600px) {
            .email-wrapper {
                padding: 15px 8px;
            }
            .email-header {
                padding: 18px 20px;
            }
            .email-body {
                padding: 24px 20px;
            }
            .email-footer {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    @php
        $appName = $appName ?? \App\Models\SystemSetting::value('application_name') ?? config('app.name', 'I2U2 Portal');
        $supportEmail = $supportEmail ?? \App\Models\SystemSetting::value('support_email') ?? 'support@i2k2.com';
        
        $embedLogo = null;
        $logoPath = public_path('assets/images/portal_logo_120h.png');
        if (isset($message) && method_exists($message, 'embed') && file_exists($logoPath)) {
            try {
                $embedLogo = $message->embed($logoPath);
            } catch (\Throwable $e) {
                $embedLogo = null;
            }
        }
        $finalLogo = $embedLogo ?? $logoUrl ?? asset('assets/images/portal_logo_120h.png');
    @endphp

    <div class="email-wrapper">
        <div class="email-container">
            <!-- Header Brand Banner with Official Logo -->
            <div class="email-header">
                <table cellpadding="0" cellspacing="0" border="0" width="100%">
                    <tr>
                        <td align="left" style="vertical-align: middle;">
                            <a href="{{ url('/') }}" target="_blank" style="text-decoration: none; display: inline-block;">
                                <img src="{{ $finalLogo }}" alt="{{ $appName }}" style="max-height: 44px; height: 44px; width: auto; display: block; border: 0;" />
                            </a>
                        </td>
                        <td align="right" style="vertical-align: middle;">
                            <span style="font-size: 13px; font-weight: 700; color: #475569; letter-spacing: 0.5px; text-transform: uppercase;">
                                {{ $appName }}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
            
            <!-- Sleek Gradient Accent Line -->
            <div class="brand-accent-line"></div>

            <!-- Body Content -->
            <div class="email-body">
                @if(!empty($subject))
                <div style="margin-bottom: 20px;">
                    <div class="subject-badge">Portal Notification</div>
                    <h1 class="subject-heading">{{ $subject }}</h1>
                </div>
                @endif

                <div class="content-card">
                    {!! $content !!}
                </div>

                @if(!empty($actionUrl))
                <div class="btn-container">
                    <a href="{{ $actionUrl }}" class="btn-action" target="_blank">
                        {{ $actionText ?? 'Open in ' . $appName }} &rarr;
                    </a>
                </div>
                @endif
            </div>

            <!-- Footer with Portal Branding & Compliance -->
            <div class="email-footer">
                <div style="margin-bottom: 6px;">
                    <span class="footer-brand">{{ $appName }}</span>
                </div>
                <p>This is an automated notification sent from <strong>{{ $appName }}</strong>.</p>
                <p>Please do not reply directly to this automated email.</p>
                <p>Need assistance? Contact our team at <a href="mailto:{{ $supportEmail }}" class="footer-support-link">{{ $supportEmail }}</a>.</p>
                <p style="margin-top: 10px; font-size: 11px; color: #94a3b8;">
                    &copy; {{ date('Y') }} {{ $appName }}. All rights reserved.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
