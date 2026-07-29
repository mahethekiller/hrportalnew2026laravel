<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobInterviewLog extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'job_interviews_logs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'job_interview_id',
        'job_id',
        'application_id',
        'interviewers_id',
        'interview_mode',
        'interview_place',
        'interview_date',
        'interview_date2',
        'new_date',
        'next_round_date',
        'interview_time',
        'interviewees_id',
        'description',
        'remarks',
        'status',
        'offer_status',
        'salary_template_id',
        'convert_to_employee',
        'employee_id',
        'added_by',
        'updated_date',
        'updated_by',
        'show_status'
    ];

    public function jobInterview()
    {
        return $this->belongsTo(JobInterview::class, 'job_interview_id');
    }

    public function job()
    {
        return $this->belongsTo(Job::class, 'job_id');
    }

    public function salaryTemplate()
    {
        return $this->belongsTo(SalaryTemplate::class, 'salary_template_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
