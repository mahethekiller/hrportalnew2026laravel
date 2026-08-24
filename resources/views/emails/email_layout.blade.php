<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject ?? 'Portal Notification' }}</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f4f6f9;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            color: #333333;
            -webkit-font-smoothing: antialiased;
        }
        .email-wrapper {
            width: 100%;
            background-color: #f4f6f9;
            padding: 30px 15px;
            box-sizing: border-box;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border: 1px solid #e9ecef;
        }
        .email-header {
            background-color: #1e293b;
            padding: 24px 30px;
            text-align: center;
        }
        .email-header h1 {
            color: #ffffff;
            font-size: 20px;
            font-weight: 700;
            margin: 0;
            letter-spacing: 0.5px;
        }
        .email-body {
            padding: 30px;
            font-size: 15px;
            line-height: 1.6;
            color: #334155;
        }
        .email-body p {
            margin-top: 0;
            margin-bottom: 16px;
        }
        .btn-container {
            text-align: center;
            margin: 28px 0;
        }
        .btn-action {
            display: inline-block;
            background-color: #3b82f6;
            color: #ffffff !important;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            padding: 12px 28px;
            border-radius: 6px;
            box-shadow: 0 2px 6px rgba(59, 130, 246, 0.3);
        }
        .email-footer {
            background-color: #f8fafc;
            padding: 20px 30px;
            text-align: center;
            font-size: 12px;
            color: #64748b;
            border-top: 1px solid #e2e8f0;
        }
        .email-footer p {
            margin: 4px 0;
        }
        .badge-info {
            display: inline-block;
            background-color: #eff6ff;
            color: #1d4ed8;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-container">
            <!-- Header -->
            <div class="email-header">
                <h1>{{ $appName ?? config('app.name', 'Antigravity HR Portal') }}</h1>
            </div>

            <!-- Body Content -->
            <div class="email-body">
                {!! $content !!}

                @if(!empty($actionUrl))
                <div class="btn-container">
                    <a href="{{ $actionUrl }}" class="btn-action" target="_blank">
                        {{ $actionText ?? 'View Details in Portal' }}
                    </a>
                </div>
                @endif
            </div>

            <!-- Footer -->
            <div class="email-footer">
                <p>This is an automated notification sent from <strong>{{ $appName ?? 'Antigravity HR Portal' }}</strong>.</p>
                <p>Please do not reply directly to this automated email.</p>
                <p>&copy; {{ date('Y') }} All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>
