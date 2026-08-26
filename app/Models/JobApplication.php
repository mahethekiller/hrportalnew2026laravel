<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobApplication extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'xin_job_applications';

    /**
     * Primary key column name.
     *
     * @var string
     */
    protected $primaryKey = 'application_id';

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
        return $this->attributes['application_id'] ?? $this->attributes['id'] ?? null;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'job_id',
        'candidate_name',
        'email',
        'gender',
        'experience',
        'user_id',
        'message',
        'job_resume',
        'source',
        'sub_source',
        'referral_name',
        'date_cv_sourced',
        'company',
        'department_id',
        'current_location',
        'current_package',
        'expected_package',
        'contact_no',
        'notice_period',
        'change_reason',
        'current_company',
        'application_status',
        'application_remarks',
        'hr_remarks',
        'covid_status',
        'profile_picture',
        'reason_to_leave',
        'created_at',
        'added_by',
        'updated_by',
        'updated_date',
        'show_status',
        'remarks',
    ];

    /**
     * Status Label Accessor.
     */
    public function getStatusLabelAttribute(): string
    {
        $status = strtolower((string) ($this->application_status ?? 'applied'));
        return match ($status) {
            'shortlisted' => 'Shortlisted',
            'interview scheduled', 'interview_scheduled' => 'Interview Scheduled',
            'hired', 'offered' => 'Hired / Offered',
            'rejected' => 'Rejected',
            default => 'Applied / New',
        };
    }

    /**
     * Status Badge Class Accessor.
     */
    public function getStatusBadgeClassAttribute(): string
    {
        $status = strtolower((string) ($this->application_status ?? 'applied'));
        return match ($status) {
            'shortlisted' => 'badge-light-primary',
            'interview scheduled', 'interview_scheduled' => 'badge-light-info',
            'hired', 'offered' => 'badge-light-success',
            'rejected' => 'badge-light-danger',
            default => 'badge-light-secondary',
        };
    }

    /**
     * Formatted Date Accessor.
     */
    public function getFormattedDateAttribute(): string
    {
        if (empty($this->created_at)) {
            return date('M d, Y');
        }

        try {
            return Carbon::parse($this->created_at)->format('M d, Y');
        } catch (\Throwable $e) {
            return (string) $this->created_at;
        }
    }

    /**
     * Job Opening Post relation.
     */
    public function job(): BelongsTo
    {
        return $this->belongsTo(JobPost::class, 'job_id', 'job_id');
    }

    /**
     * Department relation.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id', 'department_id');
    }

    /**
     * Creator Employee relation.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'added_by', 'user_id');
    }

    /**
     * Creator Name Accessor.
     */
    public function getCreatorNameAttribute(): string
    {
        if (!empty($this->creator?->first_name)) {
            return trim($this->creator->first_name . ' ' . ($this->creator->last_name ?? ''));
        }

        if (is_numeric($this->added_by)) {
            $emp = Employee::where('user_id', $this->added_by)->first();
            if ($emp) {
                return trim(($emp->first_name ?? '') . ' ' . ($emp->last_name ?? ''));
            }

            $user = User::find($this->added_by);
            if ($user?->name) {
                return $user->name;
            }
        }

        return !empty($this->added_by) ? (string) $this->added_by : 'System Admin';
    }

    /**
     * Interviews relation.
     */
    public function interviews(): HasMany
    {
        return $this->hasMany(JobInterview::class, 'application_id', 'application_id');
    }
}
