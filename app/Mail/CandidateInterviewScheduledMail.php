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
        $this->customBody = $customBody;
        
        $candidateName = $interview->jobApplication->candidate_name ?? 'Candidate';
        $jobTitle = $interview->jobApplication->job->job_title ?? 'Position';
        
        $this->customSubject = !empty($customSubject) ? $customSubject : "[Interview Invitation] {$candidateName} - {$jobTitle}";
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
