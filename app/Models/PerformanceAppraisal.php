<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PerformanceAppraisal extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'xin_performance_appraisal';

    /**
     * Primary key column name.
     *
     * @var string
     */
    protected $primaryKey = 'performance_appraisal_id';

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
        return $this->attributes['performance_appraisal_id'] ?? $this->attributes['id'] ?? null;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
        'employee_id',
        'manager_id',
        'appraisal_year_month',
        'customer_experience',
        'marketing',
        'management',
        'administration',
        'presentation_skill',
        'quality_of_work',
        'efficiency',
        'integrity',
        'professionalism',
        'team_work',
        'critical_thinking',
        'conflict_management',
        'attendance',
        'attendance_emp',
        'job_knowledge',
        'job_knowledge_emp',
        'quality_of_work_emp',
        'teamwork',
        'teamwork_emp',
        'communication',
        'communication_emp',
        'problem_solving',
        'problem_solving_emp',
        'ability_to_meet_deadline',
        'remarks',
        'remarks_emp',
        'area_strength',
        'area_imp',
        'future_goals',
        'added_by',
        'created_at',
        'manager_update_date',
        'emp_update_date',
        'show_status',
    ];

    /**
     * Calculate overall average rating score (out of 5).
     */
    public function getOverallRatingAttribute(): float
    {
        $ratings = array_filter([
            (float) ($this->quality_of_work ?? 0),
            (float) ($this->efficiency ?? 0),
            (float) ($this->job_knowledge ?? 0),
            (float) ($this->team_work ?? $this->teamwork ?? 0),
            (float) ($this->communication ?? 0),
            (float) ($this->problem_solving ?? 0),
            (float) ($this->attendance ?? 0),
            (float) ($this->integrity ?? 0),
            (float) ($this->professionalism ?? 0),
            (float) ($this->ability_to_meet_deadline ?? 0),
        ], fn($val) => $val > 0);

        if (empty($ratings)) {
            return 3.0;
        }

        return round(array_sum($ratings) / count($ratings), 1);
    }

    /**
     * Rating Label Accessor.
     */
    public function getRatingLabelAttribute(): string
    {
        $score = $this->overall_rating;

        if ($score >= 4.5) return 'Outstanding';
        if ($score >= 3.8) return 'Exceeds Expectations';
        if ($score >= 3.0) return 'Meets Expectations';
        if ($score >= 2.0) return 'Needs Improvement';
        return 'Unsatisfactory';
    }

    /**
     * Rating Badge Class Accessor.
     */
    public function getRatingBadgeClassAttribute(): string
    {
        $score = $this->overall_rating;

        if ($score >= 4.5) return 'badge-light-success';
        if ($score >= 3.8) return 'badge-light-primary';
        if ($score >= 3.0) return 'badge-light-info';
        if ($score >= 2.0) return 'badge-light-warning';
        return 'badge-light-danger';
    }

    /**
     * Formatted Appraisal Period Accessor.
     */
    public function getFormattedMonthAttribute(): string
    {
        if (empty($this->appraisal_year_month)) {
            return date('M Y');
        }

        try {
            return Carbon::parse($this->appraisal_year_month . '-01')->format('F Y');
        } catch (\Throwable $e) {
            return (string) $this->appraisal_year_month;
        }
    }

    /**
     * Employee relation.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'user_id');
    }

    /**
     * Manager relation.
     */
    public function manager(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'manager_id', 'user_id');
    }

    /**
     * Company relation.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id', 'company_id');
    }
}
