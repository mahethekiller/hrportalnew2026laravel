<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobInterview extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'xin_job_interviews';

    /**
     * Primary key column name.
     *
     * @var string
     */
    protected $primaryKey = 'job_interview_id';

    /**
     * Disable model timestamps.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Primary Key Accessor.
     */
    public function getIdAttribute()
    {
        return $this->attributes['job_interview_id'] ?? $this->attributes['id'] ?? null;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
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
        'expected_doj',
        'offered_ctc',
        'description',
        'remarks',
        'status',
        'offer_status',
        'salary_template_id',
        'convert_to_employee',
        'employee_id',
        'added_by',
        'created_at',
        'updated_date',
        'updated_by',
        'show_status',
    ];

    /**
     * Status Label Accessor.
     */
    public function getStatusLabelAttribute(): string
    {
        $status = strtolower((string) ($this->status ?? 'pending'));
        return match ($status) {
            'rejected' => 'Rejected',
            'selected' => 'Selected',
            'confirmed' => 'Confirmed',
            'pending' => 'Pending',
            'onhold' => 'On Hold',
            'rescheduled' => 'Rescheduled',
            'shortlisted' => 'Short Listed',
            'nextround' => 'Next Round',
            'offeraccepted' => 'Offer Accepted',
            default => ucfirst((string) $this->status),
        };
    }

    /**
     * Status Badge Class Accessor.
     */
    public function getStatusBadgeClassAttribute(): string
    {
        $status = strtolower((string) ($this->status ?? 'pending'));
        return match ($status) {
            'rejected' => 'badge-light-danger',
            'selected', 'confirmed', 'offeraccepted' => 'badge-light-success',
            'pending' => 'badge-light-warning',
            'onhold', 'shortlisted' => 'badge-light-info',
            'rescheduled', 'nextround' => 'badge-light-primary',
            default => 'badge-light-secondary',
        };
    }

    /**
     * Formatted Interview Date Accessor.
     */
    public function getFormattedInterviewDateAttribute(): string
    {
        if (empty($this->interview_date)) {
            return '--';
        }

        try {
            return Carbon::parse($this->interview_date)->format('M d, Y');
        } catch (\Throwable $e) {
            return (string) $this->interview_date;
        }
    }

    /**
     * Job Application relation.
     */
    public function jobApplication(): BelongsTo
    {
        return $this->belongsTo(JobApplication::class, 'application_id', 'application_id');
    }

    /**
     * Interviewer employee relation.
     */
    public function interviewer(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'interviewers_id', 'user_id');
    }
}
