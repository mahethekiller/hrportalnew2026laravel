<?php

namespace Database\Seeders;

use App\Models\EmailTemplate;
use Illuminate\Database\Seeder;

class EmailTemplateSeeder extends Seeder
{
    public function run(): void
    {
        EmailTemplate::updateOrCreate(
            ['template_code' => 'candidate_interview_scheduled'],
            [
                'name' => 'Candidate Interview Invitation',
                'subject' => '[Interview Invitation] {candidate_name} - {job_title}',
                'message' => '<p>Dear <strong>{candidate_name}</strong>,</p>
<p>We are pleased to invite you for an interview regarding your application for <strong>{job_title}</strong>.</p>

<div style="background: #f1f5f9; border-left: 4px solid #2563eb; padding: 16px; margin: 20px 0; border-radius: 4px;">
    <p style="margin: 0 0 8px 0;"><strong>Date:</strong> {interview_date}</p>
    <p style="margin: 0 0 8px 0;"><strong>Time:</strong> {interview_time}</p>
    <p style="margin: 0 0 8px 0;"><strong>Mode:</strong> {interview_mode}</p>
    <p style="margin: 0 0 8px 0;"><strong>Venue / Link:</strong> {interview_place}</p>
    <p style="margin: 0;"><strong>Interviewer Panel:</strong> {panelists}</p>
</div>

<p><strong>Instructions / Remarks:</strong></p>
<p style="background: #fafafa; border-left: 3px solid #cbd5e1; padding: 10px 15px; font-style: italic; color: #334155;">{remarks}</p>

<p style="margin-top: 25px;">Please confirm your availability for this interview schedule.</p>
<p>Best regards,<br><strong>{site_name} Recruitment Team</strong></p>',
                'status' => 1,
            ]
        );
    }
}
