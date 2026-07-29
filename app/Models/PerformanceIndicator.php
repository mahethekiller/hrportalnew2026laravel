<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PerformanceIndicator extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'xin_performance_indicator';

    /**
     * Primary key column name.
     *
     * @var string
     */
    protected $primaryKey = 'performance_indicator_id';

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
        return $this->attributes['performance_indicator_id'] ?? $this->attributes['id'] ?? null;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
        'designation_id',
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
        'ability_to_meet_deadline',
        'added_by',
        'created_at',
    ];

    /**
     * Designation relation.
     */
    public function designation(): BelongsTo
    {
        return $this->belongsTo(Designation::class, 'designation_id', 'designation_id');
    }

    /**
     * Company relation.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id', 'company_id');
    }
}
