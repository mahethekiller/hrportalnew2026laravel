<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Trainer extends Model
{
    use HasFactory;

    /**
     * Table associated with the model.
     *
     * @var string
     */
    protected $table = 'xin_trainers';

    /**
     * Primary key column.
     *
     * @var string
     */
    protected $primaryKey = 'trainer_id';

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
        return $this->attributes['trainer_id'] ?? $this->attributes['id'] ?? null;
    }

    /**
     * Mass assignable attributes.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
        'first_name',
        'last_name',
        'contact_number',
        'email',
        'designation_id',
        'expertise',
        'address',
        'status',
        'created_at',
    ];

    /**
     * Full Name Accessor.
     */
    public function getFullNameAttribute(): string
    {
        return trim(($this->first_name ?? '') . ' ' . ($this->last_name ?? ''));
    }

    /**
     * Status Badge Class Accessor.
     */
    public function getStatusBadgeClassAttribute(): string
    {
        $status = strtolower((string) ($this->status ?? 'active'));
        return match ($status) {
            'active', '1' => 'badge-light-success',
            'inactive', '0' => 'badge-light-danger',
            default => 'badge-light-secondary',
        };
    }

    /**
     * Designation relation.
     */
    public function designation(): BelongsTo
    {
        return $this->belongsTo(Designation::class, 'designation_id', 'designation_id');
    }

    /**
     * Trainings relation.
     */
    public function trainings(): HasMany
    {
        return $this->hasMany(TrainingSession::class, 'trainer_id', 'trainer_id');
    }
}
