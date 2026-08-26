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

    public function __construct(JobInterview $interview, ?string $customSubject = null, ?string $customBody = null)
    {
        $this->interview = $interview;
        
        $candidateName = $interview->jobApplication->candidate_name ?? 'Candidate';
        $jobTitle = $interview->jobApplication->job->job_title ?? 'Position';

        try {
            $dbTemplate = \App\Models\EmailTemplate::where('template_code', 'candidate_interview_scheduled')->first();
        } catch (\Throwable $e) {
            $dbTemplate = null;
        }

        if (!empty($customSubject)) {
            $this->customSubject = $customSubject;
        } elseif ($dbTemplate && !empty($dbTemplate->subject)) {
            $this->customSubject = str_replace(
                ['{candidate_name}', '{job_title}'],
                [$candidateName, $jobTitle],
                $dbTemplate->subject
            );
        } else {
            $this->customSubject = "[Interview Invitation] {$candidateName} - {$jobTitle}";
        }

        if (!empty($customBody)) {
            $this->customBody = $customBody;
        } elseif ($dbTemplate && !empty($dbTemplate->message)) {
            $panelists = $interview->interviewer_list;
            $panelistNames = $panelists->isNotEmpty()
                ? $panelists->pluck('first_name')->zip($panelists->pluck('last_name'))->map(fn($p) => trim($p[0].' '.$p[1]))->implode(', ')
                : 'Recruitment Panel';

            $replacements = [
                '{candidate_name}' => $candidateName,
                '{job_title}' => $jobTitle,
                '{interview_date}' => date('F d, Y (l)', strtotime($interview->interview_date)),
                '{interview_time}' => $interview->interview_time,
                '{interview_mode}' => $interview->interview_mode,
                '{interview_place}' => $interview->interview_place ?? 'Online / Office Room',
                '{panelists}' => $panelistNames,
                '{remarks}' => $interview->remarks ?? $interview->description ?? 'N/A',
                '{site_name}' => config('app.name', 'Antigravity HR Portal'),
            ];

            $this->customBody = str_replace(array_keys($replacements), array_values($replacements), $dbTemplate->message);
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
            ]);
    }
}
