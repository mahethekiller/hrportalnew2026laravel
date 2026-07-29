<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobPost extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'xin_jobs';

    /**
     * Primary key column name.
     *
     * @var string
     */
    protected $primaryKey = 'job_id';

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
        return $this->attributes['job_id'] ?? $this->attributes['id'] ?? null;
    }

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
        'created_at',
        'added_by',
        'updated_date',
        'updated_by',
        'show_status',
    ];

    /**
     * Status Label Accessor.
     */
    public function getStatusLabelAttribute(): string
    {
        $status = (int) ($this->status ?? 1);
        return match ($status) {
            1 => 'Published / Active',
            0 => 'Closed / Draft',
            default => 'Inactive',
        };
    }

    /**
     * Status Badge Class Accessor.
     */
    public function getStatusBadgeClassAttribute(): string
    {
        $status = (int) ($this->status ?? 1);
        return match ($status) {
            1 => 'badge-light-success',
            0 => 'badge-light-danger',
            default => 'badge-light-secondary',
        };
    }

    /**
     * Formatted Closing Date Accessor.
     */
    public function getFormattedClosingDateAttribute(): string
    {
        if (empty($this->date_of_closing)) {
            return 'Open Until Filled';
        }

        try {
            return Carbon::parse($this->date_of_closing)->format('M d, Y');
        } catch (\Throwable $e) {
            return (string) $this->date_of_closing;
        }
    }

    /**
     * Company relation.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id', 'company_id');
    }

    /**
     * Designation relation.
     */
    public function designation(): BelongsTo
    {
        return $this->belongsTo(Designation::class, 'designation_id', 'designation_id');
    }

    /**
     * Job Applications relation.
     */
    public function applications(): HasMany
    {
        return $this->hasMany(JobApplication::class, 'job_id', 'job_id');
    }
}
