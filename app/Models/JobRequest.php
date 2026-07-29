<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobRequest extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'job_requests';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'post_name',
        'vacancies',
        'company_id',
        'department_id',
        'team',
        'position_level',
        'min_experience',
        'max_experience',
        'job_role',
        'min_salary',
        'max_salary',
        'ctc_budget',
        'shift_timings',
        'timing_details',
        'work_days',
        'priority',
        'interview_rounds',
        'interview_round_details',
        'questionare',
        'competitor',
        'profile_description',
        'project_description',
        'certification',
        'education',
        'skills',
        'gender_preference',
        'description',
        'added_by',
        'updated_date',
        'updated_by',
        'approve_status',
        'status',
        'show_status'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
}
