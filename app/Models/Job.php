<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'jobs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
        'job_code',
        'job_title',
        'designation_id',
        'job_type',
        'is_featured',
        'job_vacancy',
        'gender',
        'minimum_experience',
        'maximum_experience',
        'start_date',
        'date_of_closing',
        'department',
        'priority',
        'hiring_manager',
        'job_location',
        'short_description',
        'long_description',
        'status',
        'show_on_website',
        'added_by',
        'updated_date',
        'updated_by',
        'show_status'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class, 'designation_id');
    }

    public function jobApplications()
    {
        return $this->hasMany(JobApplication::class, 'job_id');
    }

    public function jobInterviews()
    {
        return $this->hasMany(JobInterview::class, 'job_id');
    }

    public function jobInterviewLogs()
    {
        return $this->hasMany(JobInterviewLog::class, 'job_id');
    }
}
