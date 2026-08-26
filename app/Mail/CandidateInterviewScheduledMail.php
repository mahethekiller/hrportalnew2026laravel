<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\JobInterview;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CandidateInterviewScheduledMail extends Mailable
{
    use Queueable, SerializesModels;

    public JobInterview $interview;
    public string $customSubject;
    public ?string $customBody;

    public string $siteName;

    public function __construct(JobInterview $interview, ?string $customSubject = null, ?string $customBody = null)
    {
        $this->interview = $interview;
        
        $candidateName = $interview->jobApplication->candidate_name ?? 'Candidate';
        $jobTitle = $interview->jobApplication->job->job_title ?? 'Position';

        $siteName = config('app.name', 'I2U2 Portal');
        try {
            $setting = \App\Models\SystemSetting::first();
            if ($setting && !empty($setting->application_name)) {
                $siteName = $setting->application_name;
            }
        } catch (\Throwable $e) {}
        $this->siteName = $siteName;

        try {
            $dbTemplate = \App\Models\EmailTemplate::where('template_code', 'candidate_interview_scheduled')->first();
        } catch (\Throwable $e) {
            $dbTemplate = null;
        }

        $panelists = $interview->interviewer_list;
        $panelistNames = $panelists->isNotEmpty()
            ? $panelists->map(fn($p) => trim(($p->first_name ?? '') . ' ' . ($p->last_name ?? '')))->implode(', ')
            : ($interview->interviewer ? trim(($interview->interviewer->first_name ?? '') . ' ' . ($interview->interviewer->last_name ?? '')) : 'Recruitment Panel');

        $remarks = !empty($interview->remarks)
            ? $interview->remarks
            : (!empty($interview->description)
                ? $interview->description
                : (!empty($interview->jobApplication->application_remarks)
                    ? $interview->jobApplication->application_remarks
                    : 'No special remarks provided.'));

        $replacements = [
            '{candidate_name}' => $candidateName,
            '{job_title}' => $jobTitle,
            '{interview_date}' => date('F d, Y (l)', strtotime($interview->interview_date)),
            '{interview_time}' => $interview->interview_time,
            '{interview_mode}' => $interview->interview_mode,
            '{interview_place}' => $interview->interview_place ?? 'Online / Office Room',
            '{panelists}' => $panelistNames,
            '{remarks}' => $remarks,
            '{site_name}' => $siteName,
            '{application_remarks}' => $remarks,
        ];

        if (!empty($customSubject)) {
            $this->customSubject = str_replace(array_keys($replacements), array_values($replacements), $customSubject);
        } elseif ($dbTemplate && !empty($dbTemplate->subject)) {
            $this->customSubject = str_replace(array_keys($replacements), array_values($replacements), $dbTemplate->subject);
        } else {
            $this->customSubject = "[Interview Invitation] {$candidateName} - {$jobTitle}";
        }

        $rawMessage = !empty($customBody) ? $customBody : ($dbTemplate->message ?? null);
        if (!empty($rawMessage)) {
            $this->customBody = str_replace(array_keys($replacements), array_values($replacements), $rawMessage);
        } else {
            $this->customBody = null;
        }
    }

    public function build(): self
    {
        return $this->subject($this->customSubject)
            ->view('emails.candidate_interview_scheduled')
            ->with([
                'interview' => $this->interview,
                'application' => $this->interview->jobApplication,
                'panelists' => $this->interview->interviewer_list,
                'customBody' => $this->customBody,
                'siteName' => $this->siteName,
            ]);
    }
}
