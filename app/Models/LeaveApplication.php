<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveApplication extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'xin_leave_applications';

    /**
     * Primary key column name.
     *
     * @var string
     */
    protected $primaryKey = 'leave_id';

    /**
     * Disable model timestamps since table uses legacy columns.
     *
     * @var bool
     */
    public $timestamps = false;

    public const STATUS_PENDING = 1;
    public const STATUS_APPROVED = 2;
    public const STATUS_REJECTED = 3;

    public const STATUSES = [
        1 => 'Pending',
        2 => 'Approved',
        3 => 'Rejected',
    ];

    /**
     * Primary Key Accessor.
     */
    public function getIdAttribute()
    {
        return $this->attributes['leave_id'] ?? $this->attributes['id'] ?? null;
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
        'leave_type_id',
        'start_duration',
        'from_date',
        'to_date',
        'end_duration',
        'applied_on',
        'casual_deducted',
        'earned_deducted',
        'reason',
        'remarks',
        'status',
        'created_at',
    ];

    /**
     * Status Label Accessor.
     */
    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[(int) $this->status] ?? 'Pending';
    }

    /**
     * Status Badge Class Accessor.
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match ((int) $this->status) {
            2 => 'badge-light-success',
            3 => 'badge-light-danger',
            default => 'badge-light-warning',
        };
    }

    /**
     * Total Days Accessor.
     */
    public function getTotalDaysAttribute(): int
    {
        if (empty($this->from_date) || empty($this->to_date)) {
            return 1;
        }

        try {
            $from = Carbon::parse($this->from_date);
            $to = Carbon::parse($this->to_date);
            return max(1, $from->diffInDays($to) + 1);
        } catch (\Throwable $e) {
            return 1;
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
     * Leave type relation.
     */
    public function leaveType(): BelongsTo
    {
        return $this->belongsTo(LeaveType::class, 'leave_type_id', 'leave_type_id');
    }

    /**
     * Company relation.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id', 'company_id');
    }

    /**
     * Manager relation.
     */
    public function manager(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'manager_id', 'user_id');
    }
}
