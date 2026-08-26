<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $customSubject ?? 'Interview Invitation' }}</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background-color: #f8fafc; color: #1e293b; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05); border: 1px solid #e2e8f0; }
        .header { background: #1e40af; color: #ffffff; padding: 24px; text-align: center; }
        .header h1 { margin: 0; font-size: 20px; font-weight: 700; }
        .content { padding: 24px; }
        .details-box { background: #f1f5f9; border-left: 4px solid #2563eb; padding: 16px; margin: 20px 0; border-radius: 4px; }
        .details-item { margin-bottom: 10px; font-size: 14px; }
        .details-item strong { display: inline-block; width: 140px; color: #475569; }
        .btn { display: inline-block; background: #2563eb; color: #ffffff !important; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: 600; margin-top: 15px; }
        .footer { background: #f8fafc; padding: 16px; text-align: center; font-size: 12px; color: #64748b; border-top: 1px solid #e2e8f0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Interview Invitation Notice</h1>
        </div>
        <div class="content">
            @if(!empty($customBody))
                {!! $customBody !!}
            @else
                <p>Dear <strong>{{ $application->candidate_name ?? 'Candidate' }}</strong>,</p>
                <p>We are pleased to invite you for an interview regarding your application for <strong>{{ $application->job->job_title ?? 'Applied Position' }}</strong>.</p>
            
                <div class="details-box">
                    <div class="details-item">
                        <strong>Date:</strong> {{ date('F d, Y (l)', strtotime($interview->interview_date)) }}
                    </div>
                    <div class="details-item">
                        <strong>Time:</strong> {{ $interview->interview_time }}
                    </div>
                    <div class="details-item">
                        <strong>Mode:</strong> {{ $interview->interview_mode }}
                    </div>
                    @if(!empty($interview->interview_place))
                        <div class="details-item">
                            <strong>Venue / Link:</strong> {{ $interview->interview_place }}
                        </div>
                    @endif
                    @if(!empty($interview->next_round_date))
                        <div class="details-item">
                            <strong>Next Round Date:</strong> {{ date('F d, Y', strtotime($interview->next_round_date)) }}
                        </div>
                    @endif
                    @if(isset($panelists) && $panelists->isNotEmpty())
                        <div class="details-item">
                            <strong>Interviewer Panel:</strong>
                            {{ $panelists->pluck('first_name')->zip($panelists->pluck('last_name'))->map(fn($p) => trim($p[0].' '.$p[1]))->implode(', ') }}
                        </div>
                    @endif
                </div>

                @if(!empty($interview->remarks) || !empty($interview->description))
                    <p><strong>Instructions / Remarks:</strong></p>
                    <blockquote style="background: #fafafa; border-left: 3px solid #cbd5e1; margin: 0 0 15px 0; padding: 10px 15px; font-style: italic; color: #334155;">
                        {{ $interview->remarks ?? $interview->description }}
                    </blockquote>
                @endif

                @if(!empty($interview->interview_place) && str_starts_with(strtolower($interview->interview_place), 'http'))
                    <p style="text-align: center;">
                        <a href="{{ $interview->interview_place }}" class="btn" target="_blank">Join Meeting Online</a>
                    </p>
                @endif

                <p style="margin-top: 25px;">Please confirm your availability for this interview schedule.</p>
                <p>Best regards,<br><strong>Recruitment Team</strong></p>
            @endif
        </div>
        <div class="footer">
            This is an automated notification from Antigravity HR Portal.
        </div>
    </div>
</body>
</html>
