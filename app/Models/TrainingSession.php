<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrainingSession extends Model
{
    use HasFactory;

    /**
     * Table associated with the model.
     *
     * @var string
     */
    protected $table = 'xin_training';

    /**
     * Primary key column.
     *
     * @var string
     */
    protected $primaryKey = 'training_id';

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
        return $this->attributes['training_id'] ?? $this->attributes['id'] ?? null;
    }

    /**
     * Mass assignable attributes.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
        'employee_id',
        'training_type_id',
        'trainer_id',
        'start_date',
        'finish_date',
        'training_cost',
        'training_status',
        'description',
        'performance',
        'remarks',
        'created_at',
    ];

    /**
     * Status Label Accessor.
     */
    public function getStatusLabelAttribute(): string
    {
        $status = (int) ($this->training_status ?? 0);
        return match ($status) {
            0 => 'Pending',
            1 => 'In Progress',
            2 => 'Completed',
            3 => 'Terminated',
            default => 'Pending',
        };
    }

    /**
     * Status Badge Class Accessor.
     */
    public function getStatusBadgeClassAttribute(): string
    {
        $status = (int) ($this->training_status ?? 0);
        return match ($status) {
            0 => 'badge-light-warning',
            1 => 'badge-light-primary',
            2 => 'badge-light-success',
            3 => 'badge-light-danger',
            default => 'badge-light-secondary',
        };
    }

    /**
     * Formatted Training Cost Accessor.
     */
    public function getFormattedCostAttribute(): string
    {
        $cost = (float) ($this->training_cost ?? 0);
        return '₹' . number_format($cost, 2);
    }

    /**
     * Employee relation.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'user_id');
    }

    /**
     * Trainer relation.
     */
    public function trainer(): BelongsTo
    {
        return $this->belongsTo(Trainer::class, 'trainer_id', 'trainer_id');
    }

    /**
     * Training Type relation.
     */
    public function trainingType(): BelongsTo
    {
        return $this->belongsTo(TrainingType::class, 'training_type_id', 'training_type_id');
    }
}
